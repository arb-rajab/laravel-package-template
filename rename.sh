#!/usr/bin/env bash
# Renames this template to your package.
#
#   ./rename.sh acme/awesome-tools
#   ./rename.sh <vendor> / <package>   (kebab-case, lowercase letters, digits, hyphens)
#
# Replaces the placeholder tokens everywhere in the tree (except .git and
# vendor), and renames files whose names contain a token:
#   vendor-name/package-name -> acme/awesome-tools   (composer name, GitHub path)
#   vendor-name              -> acme
#   VendorName               -> Acme                 (PHP namespace root)
#   package-name             -> awesome-tools        (config key, command prefix, tags)
#   package_name             -> awesome_tools        (table names)
#   PACKAGE_NAME             -> AWESOME_TOOLS        (environment variables)
#   PackageName              -> AwesomeTools         (class names)
# Then deletes itself. Run it once, before the first commit of your package.
set -euo pipefail

if [ $# -ne 1 ] || ! [[ "$1" =~ ^[a-z][a-z0-9-]*/[a-z][a-z0-9-]*$ ]]; then
  echo "usage: $0 <vendor>/<package>   (kebab-case, e.g. acme/awesome-tools)" >&2
  exit 1
fi

vendor_kebab="${1%%/*}"
package_kebab="${1##*/}"

studly() {
  local out="" part
  IFS='-' read -ra parts <<< "$1"
  for part in "${parts[@]}"; do
    out+="$(tr '[:lower:]' '[:upper:]' <<< "${part:0:1}")${part:1}"
  done
  printf '%s' "$out"
}

vendor_studly="$(studly "$vendor_kebab")"
package_studly="$(studly "$package_kebab")"
package_snake="${package_kebab//-/_}"
package_upper="$(tr '[:lower:]' '[:upper:]' <<< "$package_snake")"

cd "$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"

grep -rIl --exclude-dir=.git --exclude-dir=vendor --exclude-dir=build --exclude=rename.sh \
  -e 'vendor-name' -e 'VendorName' -e 'package-name' -e 'package_name' \
  -e 'PACKAGE_NAME' -e 'PackageName' . |
  while IFS= read -r file; do
    sed -i \
      -e "s#vendor-name/package-name#${vendor_kebab}/${package_kebab}#g" \
      -e "s#vendor-name#${vendor_kebab}#g" \
      -e "s#VendorName#${vendor_studly}#g" \
      -e "s#package-name#${package_kebab}#g" \
      -e "s#package_name#${package_snake}#g" \
      -e "s#PACKAGE_NAME#${package_upper}#g" \
      -e "s#PackageName#${package_studly}#g" \
      "$file"
  done

# Rename files whose names contain a token (deepest paths first).
find . -depth \( -path ./.git -o -path ./vendor -o -path ./build \) -prune -o \
  \( -name '*PackageName*' -o -name '*package-name*' -o -name '*package_name*' \) -print |
  while IFS= read -r path; do
    dir="$(dirname "$path")"
    base="$(basename "$path")"
    new="${base//PackageName/${package_studly}}"
    new="${new//package-name/${package_kebab}}"
    new="${new//package_name/${package_snake}}"
    mv -- "$path" "$dir/$new"
  done

echo "Renamed to '${vendor_kebab}/${package_kebab}' (namespace ${vendor_studly}\\${package_studly})."
echo "Next: set your name and email in composer.json and LICENSE, run composer install,"
echo "then composer lint:fix (the new namespace changes import order)."
rm -- "$0"
