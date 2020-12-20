<?php

namespace Deployer;

require 'recipe/laravel.php';

// Project name
set('application', 'Bryce\'s Fun Projects');

// Project repository
set('repository', 'git@github.com:brycelarge/laravel-vue-kanban-board.git');

// Shared files/dirs between deploys
add('shared_dirs', [
    'storage',
]);

set('writable_dirs', [
    'bootstrap/cache',
    'storage/app',
    'storage/app/public',
    'storage/framework',
    'storage/framework/cache',
    'storage/framework/sessions',
    'storage/framework/views',
    'storage/logs',
]);

set('shared_files', [
    '.env',
]);

// Further config
set('http_user', 'brycelargeco');
set('default_stage', 'production');
set('keep_releases', 2);
set('writable_mode', 'chmod');
set('deploy_path', '/home/brycelargeco');
set('branch', 'master');

host('production')
    ->hostname('102.130.116.169')
    ->user('root')
    ->forwardAgent(true)
    ->multiplexing(true)
    ->set('composer_options', 'install --verbose --prefer-dist --optimize-autoloader --no-progress --no-interaction --no-dev --ignore-platform-reqs');

// Overridden from recipes/sentry
task('deploy', [
    'deploy:info',
    'deploy:prepare',
    'deploy:lock',
    'deploy:release',
    'deploy:update_code',
    'deploy:shared',
    'deploy:vendors',
    'deploy:writable',
    'artisan:view:clear',
    'artisan:route:cache',
    'artisan:config:cache',
    'deploy:ownership',
    'deploy:symlink',
    'deploy:unlock',
    'cleanup',
]);

task('deploy:ownership', function () {
    $httpUser = get('http_user');
    run("chown -R $httpUser:$httpUser {{release_path}}");
});

// -- Reset laravel cached code, may be outdated
after('rollback', 'artisan:view:clear');
after('rollback', 'artisan:config:clear');
after('rollback', 'artisan:route:clear');
after('deploy:failed', 'deploy:unlock');
