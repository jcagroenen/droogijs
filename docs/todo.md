# Deployment Todo

## Deployer Setup

- [ ] Add Sage optimization to Deployer config
- [ ] Run `npm run build` in theme during deploy
- [ ] Run `composer install --no-dev --optimize-autoloader` in theme
- [ ] Run `wp acorn optimize` after deploy
- [ ] Configure web server to deny access to `*.blade.php` files

## Notes

- PHP versions must match between local and production
- Assets are built during deploy, not committed to git
