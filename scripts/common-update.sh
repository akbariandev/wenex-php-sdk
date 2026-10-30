#!/bin/bash
#
# Update the packages/common working copy of php-sdk-common.
#
# Mirrors the platform-sdk remote flow: the user MUST pass a stage
# (staging|production) and a domain (FQDN). The stage names the git remote
# (registered by common:clone) and the package is fetched from
# git@gitlab.<domain>:wenex/php-sdk-common.git.
#
# Usage: composer common:update -- <stage> <domain>
#   e.g. composer common:update -- staging wenex.org

set -euo pipefail

ROOT="$(cd "$(dirname "$0")/.." && pwd)"
DIR="$ROOT/packages/common"

STAGE="${1:-}"
DOMAIN="${2:-}"

if [[ "$STAGE" != "staging" && "$STAGE" != "production" ]]; then
  echo "Error: STAGE must be 'staging' or 'production'." >&2
  echo "Usage: composer common:update -- <stage> <domain>   e.g. composer common:update -- staging wenex.org" >&2
  exit 1
fi

if ! [[ "$DOMAIN" =~ ^([a-zA-Z0-9-]+\.)+[a-zA-Z]{2,}$ ]]; then
  echo "Error: DOMAIN must be a valid FQDN (e.g., wenex.org)." >&2
  echo "Usage: composer common:update -- <stage> <domain>   e.g. composer common:update -- staging wenex.org" >&2
  exit 1
fi

if [ ! -e "$DIR/.git" ]; then
  echo "packages/common is not a git checkout. Run: composer common:clone -- $STAGE $DOMAIN" >&2
  exit 1
fi

cd "$DIR"

URL="git@gitlab.$DOMAIN:wenex/php-sdk-common.git"

if ! git remote get-url "$STAGE" >/dev/null 2>&1; then
  git remote add "$STAGE" "$URL"
fi

git fetch "$STAGE" main
git checkout -B main FETCH_HEAD
echo "packages/common updated to $(git log -1 --format='%h %s' FETCH_HEAD) (from $STAGE: $(git remote get-url $STAGE))"
