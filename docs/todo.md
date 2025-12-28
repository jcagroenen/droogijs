# Deployment Todo

## Completed ✓

- [x] Deployer config with theme build tasks
- [x] `npm run build` in theme during deploy
- [x] `composer install` in theme during deploy
- [x] Multisite installed on staging
- [x] SSL configured for all subdomains
- [x] DNS wildcard for subdomains
- [x] WooCommerce network activated
- [x] Site switcher for dev/staging
- [x] Mobile menu (Alpine.js)
- [x] Shop page layout fixes

## Remaining

- [ ] Run `wp acorn optimize` after deploy (add to deploy.php)
- [ ] Configure web server to deny access to `*.blade.php` files
- [ ] Set up production domains when ready
- [ ] Pin Alpine.js to specific version (currently using @3.x.x, pin to 3.14.3)

## Notes

- PHP versions must match between local and production
- Assets are built during deploy, not committed to git
- See [deployment.md](deployment.md) for full deployment guide
