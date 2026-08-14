#!/bin/bash
# Run after code is on the server (cPanel Deploy HEAD runs .cpanel.yml automatically).
# Manual SSH: bash scripts/deploy-production.sh
set -euo pipefail

APPPATH="${APPPATH:-/home2/leylasaf/leysafaris/leysafaris}"
DEPLOYPATH="${DEPLOYPATH:-/home2/leylasaf/public_html}"
REPO_ROOT="${REPO_ROOT:-$(cd "$(dirname "$0")/.." && pwd)}"
PUBLIC="$REPO_ROOT/leysafaris/public"

echo "==> App path: $APPPATH"
echo "==> Doc root: $DEPLOYPATH"

mkdir -p "$DEPLOYPATH"

echo "==> Sync docroot entry files (images/css/js served by Laravel from git public/)"
cp "$PUBLIC/index.php" "$DEPLOYPATH/"
cp "$PUBLIC/htaccess.txt" "$DEPLOYPATH/.htaccess"
cp "$PUBLIC/favicon.ico" "$DEPLOYPATH/" 2>/dev/null || true
cp "$PUBLIC/robots.txt" "$DEPLOYPATH/" 2>/dev/null || true
rm -rf "$DEPLOYPATH/css" "$DEPLOYPATH/js" "$DEPLOYPATH/images"

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
