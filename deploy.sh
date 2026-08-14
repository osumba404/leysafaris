#!/bin/bash
# Run once on the server after git pull if cPanel "Deploy HEAD" is unavailable:
#   bash deploy.sh
set -euo pipefail

DEPLOYPATH=/home2/leylasaf/public_html
APPPATH=/home2/leylasaf/leysafaris/leysafaris
REPO_ROOT="$(cd "$(dirname "$0")" && pwd)"
PUBLIC="$REPO_ROOT/leysafaris/public"

/bin/cp "$PUBLIC/index.php" "$DEPLOYPATH/"
/bin/cp "$PUBLIC/htaccess.txt" "$DEPLOYPATH/.htaccess"
/bin/mkdir -p "$DEPLOYPATH/images"
/bin/cp -R "$PUBLIC/images/." "$DEPLOYPATH/images/"
# CSS/JS are served by Laravel from public/ in git — do not copy stale copies here
/bin/rm -rf "$DEPLOYPATH/css" "$DEPLOYPATH/js"

/bin/cp -R "$REPO_ROOT/leysafaris/app" "$REPO_ROOT/leysafaris/bootstrap" "$REPO_ROOT/leysafaris/config" "$REPO_ROOT/leysafaris/database" "$REPO_ROOT/leysafaris/resources" "$REPO_ROOT/leysafaris/routes" "$REPO_ROOT/leysafaris/artisan" "$REPO_ROOT/leysafaris/composer.json" "$REPO_ROOT/leysafaris/composer.lock" "$APPPATH/"
cd "$APPPATH" && php artisan config:clear && php artisan route:clear && php artisan view:clear

echo "Deployed. CSS/JS load from Laravel (git public/). Test: https://leylasafaritours.com/safaris"
