STAGE=$1
DOMAIN=$2

# Check if STAGE is valid
if [[ "$STAGE" != "staging" && "$STAGE" != "production" ]]; then
  echo "Error: STAGE must be 'staging' or 'production'."
  exit 1
fi

# Check if DOMAIN is a valid FQDN
# if ! [[ "$DOMAIN" =~ ^([a-zA-Z0-9-]+\.)+[a-zA-Z]{2,}$ ]]; then
#   echo "Error: DOMAIN must be a valid FQDN (e.g., wenex.org)."
#   exit 1
# fi

# Check if the current branch is main
echo "Checking out to main branch..."
bash ./scripts/git-submodule.sh checkout main && echo ""

git remote add "$STAGE" "git@gitlab.com:akbarian.dev/php-sdk.git"
if [ $? -ne 0 ]; then
  echo "Error: Failed to add remote repository for php-sdk."
else
  git push "$STAGE" main
  echo "Remote repository for php-sdk added successfully."
fi

# Navigate to the packages/common directory of the project
cd "packages/common"
git remote add "$STAGE" "git@gitlab.com:akbarian.dev/php-sdk-common.git"
if [ $? -ne 0 ]; then
  echo "Error: Failed to add remote repository for php-sdk-common."
else
  git push "$STAGE" main
  echo "Remote repository for php-sdk-common added successfully."
fi
