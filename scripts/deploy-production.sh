#!/bin/bash
# Run after code is on the server (cPanel Deploy HEAD runs .cpanel.yml automatically).
# Manual SSH: bash scripts/deploy-production.sh
set -euo pipefail

APPPATH="${APPPATH:-/home2/leylasaf/leysafaris/leysafaris}"
DEPLOYPATH="${DEPLOYPATH:-/home2/leylasaf/public_html}"
REPO_ROOT="${REPO_ROOT:-$(cd "$(dirname "$0")/.." && pwd)}"

echo "==> App path: $APPPATH"
echo "==> Doc root: $DEPLOYPATH"

mkdir -p "$APPPATH" "$DEPLOYPATH/css" "$DEPLOYPATH/js"

echo "==> Sync public assets"
cp -R "$REPO_ROOT/leysafaris/public/." "$DEPLOYPATH/"
cp "$REPO_ROOT/leysafaris/public/css/admin.css" "$DEPLOYPATH/css/admin.css"
cp "$REPO_ROOT/leysafaris/public/htaccess.txt" "$DEPLOYPATH/.htaccess"

echo "==> Sync application code"
cp -R \
  "$REPO_ROOT/leysafaris/app" \
  "$REPO_ROOT/leysafaris/bootstrap" \
  "$REPO_ROOT/leysafaris/config" \
  "$REPO_ROOT/leysafaris/database" \
  "$REPO_ROOT/leysafaris/resources" \
  "$REPO_ROOT/leysafaris/routes" \
  "$REPO_ROOT/leysafaris/artisan" \
  "$REPO_ROOT/leysafaris/composer.json" \
  "$REPO_ROOT/leysafaris/composer.lock" \
  "$APPPATH/"

cd "$APPPATH"

PHP_BIN="${PHP_BIN:-php}"
if ! command -v "$PHP_BIN" >/dev/null 2>&1; then
  for candidate in /usr/local/bin/php /opt/cpanel/ea-php82/root/usr/bin/php; do
    if [ -x "$candidate" ]; then
      PHP_BIN="$candidate"
      break
    fi
  done
fi

echo "==> Using PHP: $PHP_BIN"
"$PHP_BIN" artisan config:clear
"$PHP_BIN" artisan route:clear
"$PHP_BIN" artisan view:clear

echo "==> Running migrations"
"$PHP_BIN" artisan migrate --force --no-interaction

echo "==> Seeding content (safe to re-run)"
"$PHP_BIN" artisan db:seed --class=ContentEnhancementSeeder --force
"$PHP_BIN" artisan db:seed --class=SiteContentSeeder --force

echo "==> Deploy complete"
