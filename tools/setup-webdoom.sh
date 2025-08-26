#!/usr/bin/env bash
# Build Freedoom WebAssembly engine assets without committing binaries.
set -euo pipefail

DEST="$(dirname "$0")/../page/assets/doom/engine"
TMP="$(mktemp -d)"

# Clone webDOOM source
git clone --depth=1 https://github.com/UstymUkhman/webDOOM "$TMP/webDOOM"

# Ensure cloned shell scripts are executable
find "$TMP/webDOOM" -type f -name '*.sh' -exec chmod +x {} +

# Download Freedoom WADs
curl -L https://github.com/freedoom/freedoom/releases/latest/download/freedoom-0.13.0.zip -o "$TMP/freedoom.zip"
unzip -j "$TMP/freedoom.zip" freedoom-0.13.0/freedoom1.wad freedoom-0.13.0/freedoom2.wad -d "$TMP"

# Prepare build directory with Freedoom assets
mv "$TMP/freedoom1.wad" "$TMP/webDOOM/build/doom1.wad"
mv "$TMP/freedoom2.wad" "$TMP/webDOOM/build/doom2.wad"

# Build Freedoom phase 1
pushd "$TMP/webDOOM" >/dev/null
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
