#!/bin/bash
# Run once on the server after git pull if cPanel "Deploy HEAD" is unavailable:
#   bash deploy.sh
set -euo pipefail

DEPLOYPATH=/home2/leylasaf/public_html
APPPATH=/home2/leylasaf/leysafaris/leysafaris
REPO_ROOT="$(cd "$(dirname "$0")" && pwd)"

/bin/cp -R "$REPO_ROOT/leysafaris/public/." "$DEPLOYPATH/"
/bin/cp "$REPO_ROOT/leysafaris/public/css/admin.css" "$DEPLOYPATH/css/admin.css"
/bin/cp "$REPO_ROOT/leysafaris/public/htaccess.txt" "$DEPLOYPATH/.htaccess"
/bin/cp -R "$REPO_ROOT/leysafaris/app" "$REPO_ROOT/leysafaris/bootstrap" "$REPO_ROOT/leysafaris/config" "$REPO_ROOT/leysafaris/database" "$REPO_ROOT/leysafaris/resources" "$REPO_ROOT/leysafaris/routes" "$REPO_ROOT/leysafaris/artisan" "$REPO_ROOT/leysafaris/composer.json" "$REPO_ROOT/leysafaris/composer.lock" "$APPPATH/"
cd "$APPPATH" && php artisan config:clear && php artisan route:clear && php artisan view:clear

echo "Deployed public_html + Laravel app. Test: https://leylasafaritours.com/safaris"
