#!/usr/bin/env bash
# Download webDOOM engine assets without committing binaries.
set -euo pipefail

DEST="$(dirname "$0")/../page/assets/doom/engine"
BASE_URL="https://raw.githubusercontent.com/UstymUkhman/webDOOM/master/public"
FILES=(
  doom1.js
  doom1.wasm
  doom1.data
  doom2.js
  doom2.wasm
  doom2.data
  fonts/White-Rabbit.eot
  fonts/White-Rabbit.woff
  fonts/White-Rabbit.ttf
  fonts/White-Rabbit.svg
  img/doom1.jpg
  img/doom2.jpg
  preview.gif
)

mkdir -p "$DEST"
for f in "${FILES[@]}"; do
  mkdir -p "$DEST/$(dirname "$f")"
  curl -L "$BASE_URL/$f" -o "$DEST/$f"
done

echo "webDOOM engine assets downloaded to $DEST"
