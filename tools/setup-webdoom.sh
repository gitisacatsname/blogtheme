#!/usr/bin/env bash
# Build Freedoom WebAssembly engine assets without committing binaries.
set -euo pipefail

# Install build dependencies when missing. These are required by
# the webDOOM project to compile its WebAssembly binaries and mirror
# the prerequisites from the PrBoom installation guide (SDL, SDL_mixer,
# SDL_net).
if ! command -v emcc >/dev/null 2>&1 || \
   ! command -v autoheader >/dev/null 2>&1 || \
   ! command -v aclocal >/dev/null 2>&1 || \
   ! command -v pkg-config >/dev/null 2>&1 || \
   ! pkg-config --exists SDL_mixer >/dev/null 2>&1 || \
   ! pkg-config --exists SDL_net >/dev/null 2>&1; then
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
      libsdl-net1.2-dev
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
    brew install emscripten autoconf automake libtool pkg-config sdl sdl_mixer sdl_net
  else
    echo "No supported package manager found. Please install emscripten, autoconf, automake, libtool, pkg-config, SDL, SDL_mixer and SDL_net." >&2
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
find "$TMP/webDOOM" -type f \( -name '*.sh' -o -name 'bootstrap' -o -name 'missing' \) -exec chmod +x {} +

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
# system SDL development files. The build itself relies on Emscripten's SDL.
if ! grep -q 'AM_PATH_SDL' "$TMP/webDOOM/acinclude.m4" 2>/dev/null; then
  cat <<'EOF' >> "$TMP/webDOOM/acinclude.m4"
AC_DEFUN([AM_PATH_SDL], [:])
EOF
fi

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
emconfigure ./configure
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
