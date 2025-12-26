<?php
namespace Deployer;

require 'recipe/wordpress.php';

// Config

set('repository', 'git@github.com:jcagroenen/droogijs.git');

add('shared_files', [
    '.env'
]);
add('shared_dirs', []);

set('shared_dirs', [
    'web/app/uploads',
    'web/app/uploads-webpc'
]);

set('writable_dirs', [
    'web/app/uploads',
    'web/app/uploads-webpc'
]);

// Hosts

host('droogijs.groenen-webdev.nl')
    ->set('remote_user', 'juulst')
    ->set('deploy_path', '/home/juulst/droogijs.groenen-webdev.nl')
    ->set('branch', 'main');


//Releases
set('keep_releases', 3);

// Hooks

after('deploy:failed', 'deploy:unlock');


task('deploy:theme:composer-install', function () {
    run('cd {{release_path}}/web/app/themes/droogijs && composer install');
});

task('deploy:theme:build', function () {
    run('cd {{release_path}}/web/app/themes/droogijs && npm ci && npm run build');
});


desc('Deploys project');
task('deploy', [
    'deploy:prepare',
    'deploy:vendors',
    'deploy:theme:composer-install',
    'deploy:theme:build',
    'deploy:publish',
]);
