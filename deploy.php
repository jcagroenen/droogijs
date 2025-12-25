<?php
namespace Deployer;

require 'recipe/wordpress.php';

// Config

set('repository', 'git@github.com:jcagroenen/droogijs.git');

add('shared_files', []);
add('shared_dirs', []);
add('writable_dirs', []);

// Hosts

host('')
    ->set('remote_user', 'deployer')
    ->set('deploy_path', '~/droogijs');

// Hooks

after('deploy:failed', 'deploy:unlock');
