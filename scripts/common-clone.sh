#!/bin/bash
#
# Clone the private php-sdk-common sibling repository into packages/common.
#
# Mirrors the platform-sdk remote flow: the user MUST pass a stage
# (staging|production) and a domain (FQDN). The package is cloned from
# git@gitlab.<domain>:wenex/php-sdk-common.git and the remote is registered
# under the stage name — never from a hardcoded public URL.
#
# Usage: composer common:clone -- <stage> <domain>
#   e.g. composer common:clone -- staging wenex.org

set -euo pipefail

ROOT="$(cd "$(dirname "$0")/.." && pwd)"
DIR="$ROOT/packages/common"

STAGE="${1:-}"
DOMAIN="${2:-}"

if [[ "$STAGE" != "staging" && "$STAGE" != "production" ]]; then
  echo "Error: STAGE must be 'staging' or 'production'." >&2
  echo "Usage: composer common:clone -- <stage> <domain>   e.g. composer common:clone -- staging wenex.org" >&2
  exit 1
fi

if ! [[ "$DOMAIN" =~ ^([a-zA-Z0-9-]+\.)+[a-zA-Z]{2,}$ ]]; then
  echo "Error: DOMAIN must be a valid FQDN (e.g., wenex.org)." >&2
  echo "Usage: composer common:clone -- <stage> <domain>   e.g. composer common:clone -- staging wenex.org" >&2
  exit 1
fi

URL="git@gitlab.$DOMAIN:wenex/php-sdk-common.git"

if [ -e "$DIR" ]; then
  echo "packages/common already exists — use: composer common:update -- $STAGE $DOMAIN"
  exit 0
fi

git clone "$URL" "$DIR"
git -C "$DIR" remote rename origin "$STAGE"
echo "packages/common cloned from $URL (remote: $STAGE)"
echo "Next: composer install"
