#!/usr/bin/env bash
# Build Freedoom WebAssembly engine assets without committing binaries.
set -euo pipefail

# Install build dependencies when missing. These are required by
# the webDOOM project to compile its WebAssembly binaries and mirror
# the prerequisites from the PrBoom installation guide (SDL and its
# SDL_mixer extension).
if ! command -v emcc >/dev/null 2>&1 || \
   ! command -v autoheader >/dev/null 2>&1 || \
   ! command -v aclocal >/dev/null 2>&1 || \
   ! command -v pkg-config >/dev/null 2>&1 || \
   ( ! pkg-config --exists SDL_mixer >/dev/null 2>&1 && \
     ! pkg-config --exists SDL2_mixer >/dev/null 2>&1 ); then
  echo "Installing webDOOM build dependencies..."
  if command -v apt-get >/dev/null 2>&1; then
    PKGS=(
      build-essential
      autoconf
      automake
      libtool
      pkg-config
      libsdl1.2-dev
      libsdl-mixer1.2-dev
      curl
      git
      unzip
      emscripten
    )
    SUDO=""
    if command -v sudo >/dev/null 2>&1; then
      SUDO="sudo"
    fi
    $SUDO apt-get update
    $SUDO apt-get install -y "${PKGS[@]}"
  elif command -v brew >/dev/null 2>&1; then
    brew update
    brew install emscripten autoconf automake libtool pkg-config sdl12-compat sdl2_mixer
  else
    echo "No supported package manager found. Please install emscripten, autoconf, automake, libtool, pkg-config, SDL, and SDL_mixer (or SDL2_mixer)." >&2
    exit 1
  fi
fi

DEST="$(dirname "$0")/../page/assets/doom/engine"
TMP="$(mktemp -d)"

# Clone webDOOM source
git clone --depth=1 https://github.com/UstymUkhman/webDOOM "$TMP/webDOOM"

# Ensure cloned shell scripts are executable
# Some scripts like `bootstrap` lack a .sh extension, so adjust permissions
# on those as well to avoid "Permission denied" errors during the build.
# Use `find` with `-print0` and `xargs` for portability across BSD and GNU
# variants, including macOS.
find "$TMP/webDOOM" -type f \( -name '*.sh' -o -name 'bootstrap' -o -name 'missing' \) -print0 | xargs -0 chmod +x

# The upstream repository ships a pre-generated `prboom.spec` but not the
# template file `prboom.spec.in` expected by Autotools. When `bootstrap`
# regenerates the build system, `configure.ac` references `prboom.spec`,
# causing the process to abort if the `.in` file is missing. Provide the
# expected template by copying the existing spec file when necessary so the
# configure step can proceed.
if [ -f "$TMP/webDOOM/prboom.spec" ] && [ ! -f "$TMP/webDOOM/prboom.spec.in" ]; then
  cp "$TMP/webDOOM/prboom.spec" "$TMP/webDOOM/prboom.spec.in"
fi

# Some environments may lack the SDL autotools macro `AM_PATH_SDL`, leading to
# bootstrap failures. Stub the macro so Autotools can continue even without
# system SDL development files. The build itself relies on Emscripten's SDL and
# its related mixer/net libraries, so wire those linker flags directly into the
# macro's output to ensure the compiler and final link step pull in the bundled
# implementations.
# Use SDL2 ports from Emscripten. Opt in to the SDL2 variant of SDL_mixer so
# the necessary headers and libraries are available in the sysroot.
SDL_FLAGS="-sUSE_SDL=2 -sUSE_SDL_MIXER=2"
if ! grep -q 'AM_PATH_SDL' "$TMP/webDOOM/acinclude.m4" 2>/dev/null; then
  cat <<EOF >> "$TMP/webDOOM/acinclude.m4"
AC_DEFUN([AM_PATH_SDL], [
  SDL_CFLAGS="$SDL_FLAGS"
  SDL_LIBS="$SDL_FLAGS"
  sdl_main=yes
  AC_SUBST([SDL_CFLAGS])
  AC_SUBST([SDL_LIBS])
])
EOF
fi

# Propagate the same SDL flags through the rest of the build. These ensure the
# compiler uses Emscripten's in-tree SDL port rather than searching for system
# headers that might not exist in a clean CI environment.
export CFLAGS="${CFLAGS:-} ${SDL_FLAGS}"
export LDFLAGS="${LDFLAGS:-} ${SDL_FLAGS}"

# SDL_net support is disabled for this build, so no include rewriting is
# necessary.

# Update legacy SDL1 key and function names so the sources build against SDL2.
find "$TMP/webDOOM/src/SDL" -name 'i_video.c' -print0 |
  LC_ALL=C xargs -0 sed -i.bak \
    -e 's/SDL_keysym/SDL_Keysym/g' \
    -e 's/SDLK_KP0/SDLK_KP_0/g' \
    -e 's/SDLK_KP1/SDLK_KP_1/g' \
    -e 's/SDLK_KP2/SDLK_KP_2/g' \
    -e 's/SDLK_KP3/SDLK_KP_3/g' \
    -e 's/SDLK_KP4/SDLK_KP_4/g' \
    -e 's/SDLK_KP5/SDLK_KP_5/g' \
    -e 's/SDLK_KP6/SDLK_KP_6/g' \
    -e 's/SDLK_KP7/SDLK_KP_7/g' \
    -e 's/SDLK_KP8/SDLK_KP_8/g' \
    -e 's/SDLK_KP9/SDLK_KP_9/g' \
    -e 's/SDLK_LMETA/SDLK_LGUI/g' \
    -e 's/SDLK_RMETA/SDLK_RGUI/g' \
    -e 's/SDL_WM_GrabInput/SDL_SetRelativeMouseMode/g' \
    -e 's/SDL_GRAB_ON/SDL_TRUE/g' \
    -e 's/SDL_GRAB_OFF/SDL_FALSE/g' \
    -e 's/SDL_WarpMouse(/SDL_WarpMouseInWindow(NULL, /g'

# Remove obsolete palette handling code not supported by SDL2
find "$TMP/webDOOM/src/SDL" -name 'i_video.c' -print0 | xargs -0 sed -i.bak '/SDL_SetPalette(/,/);/d'

# Autoconf's library tests for SDL_mixer fail under Emscripten because there
# is no native `libSDL_mixer` archive to link against. Pre-seed the cache
# variable so `configure` believes the dependency is available and defines the
# expected feature macro.
export ac_cv_lib_SDL_mixer_Mix_OpenAudio=yes

# The upstream build script links the final binary with a raw `emcc` command.
# Inject the SDL flags so the produced WebAssembly module bundles the SDL
# runtime and audio/network extensions.
sed -i.bak "s/emcc final.bc/emcc final.bc ${SDL_FLAGS}/" "$TMP/webDOOM/build.sh"

# Download Freedoom WADs
curl -L https://github.com/freedoom/freedoom/releases/latest/download/freedoom-0.13.0.zip -o "$TMP/freedoom.zip"
unzip -j "$TMP/freedoom.zip" freedoom-0.13.0/freedoom1.wad freedoom-0.13.0/freedoom2.wad -d "$TMP"

# Prepare build directory with Freedoom assets
mv "$TMP/freedoom1.wad" "$TMP/webDOOM/build/doom1.wad"
mv "$TMP/freedoom2.wad" "$TMP/webDOOM/build/doom2.wad"

# Build Freedoom phase 1
pushd "$TMP/webDOOM" >/dev/null
# Regenerate autotools files for the local environment
./bootstrap

# Replace outdated config.guess and config.sub with modern versions that
# recognize the wasm32-unknown-emscripten target. The versions shipped in the
# upstream repository predate WebAssembly support, causing `configure` to abort
# when passed the `--host=wasm32-unknown-emscripten` triple.
curl -L "https://raw.githubusercontent.com/gcc-mirror/gcc/master/config.sub" -o autotools/config.sub
curl -L "https://raw.githubusercontent.com/gcc-mirror/gcc/master/config.guess" -o autotools/config.guess
chmod +x autotools/config.sub autotools/config.guess
# Explicitly set the host triple so Autoconf treats the build as a cross
# compilation targeting WebAssembly and skips executing test binaries, which
# would fail under Emscripten when Node.js is unavailable.
emconfigure ./configure --host=wasm32-unknown-emscripten

# The `ac_cv_lib_*` cache variable above convinces `configure` that SDL_mixer is
# present, but the generated `config.h` still leaves its macro undefined.
# Force-enable it so the SDL sound backend is compiled when using Emscripten's
# in-tree implementation.
sed -i.bak 's/\/\* #undef HAVE_LIBSDL_MIXER \*\//#define HAVE_LIBSDL_MIXER 1/' config.h
./build.sh
mkdir -p "$DEST"
mv build/web/doom1.js "$DEST/freedoom1.js"
mv build/web/doom1.wasm "$DEST/freedoom1.wasm"
mv build/web/doom1.data "$DEST/freedoom1.data"

# Build Freedoom phase 2
./build.sh doom2
mv build/web/doom2.js "$DEST/freedoom2.js"
mv build/web/doom2.wasm "$DEST/freedoom2.wasm"
mv build/web/doom2.data "$DEST/freedoom2.data"

# Fonts and preview assets
mkdir -p "$DEST/fonts" "$DEST/img"
cp public/fonts/White-Rabbit.eot "$DEST/fonts/White-Rabbit.eot"
cp public/fonts/White-Rabbit.woff "$DEST/fonts/White-Rabbit.woff"
cp public/fonts/White-Rabbit.ttf "$DEST/fonts/White-Rabbit.ttf"
cp public/fonts/White-Rabbit.svg "$DEST/fonts/White-Rabbit.svg"

curl -L https://freedoom.github.io/img/screenshots/tn_p1_3.jpg -o "$DEST/img/freedoom1.jpg"
curl -L https://freedoom.github.io/img/screenshots/tn_p2_3.jpg -o "$DEST/img/freedoom2.jpg"
cp public/preview.gif "$DEST/preview.gif"

popd >/dev/null
rm -rf "$TMP"

echo "Freedoom engine assets downloaded to $DEST"
