#!/usr/bin/env sh
cd ../../..;
# Extract string for default locale
echo '# Extracting translations from vendor/ezsystems/hybrid-platform-ui';
./bin/console translation:extract en -v \
  --dir=./vendor/ezsystems/hybrid-platform-ui/src \
  --output-dir=./vendor/ezsystems/hybrid-platform-ui/src/bundle/Resources/translations \
  --keep
  "$@"

cd vendor/ezsystems/hybrid-platform-ui;

echo '# Clean file references';
sed -i .bak "s|>.*/hybrid-platform-ui/|>|g" ./src/bundle/Resources/translations/*.xlf

echo 'Translation extraction done';
