<?php

$databases = array();
$config_directories = array();
$settings['hash_salt'] = '';
$settings['update_free_access'] = FALSE;
$settings['container_yamls'][] = $app_root . '/' . $site_path . '/services.yml';
$settings['file_scan_ignore_directories'] = [
  'node_modules',
  'bower_components',
];
if (file_exists($app_root . '/' . $site_path . '/settings.local.php')) {
  include $app_root . '/' . $site_path . '/settings.local.php';
}

$config_directories['sync'] = '../config/sync';
$settings['hash_salt'] = 'XKTqN20b6ValM_YpgTIMq1viWRKdo7O9i1KJmTIp9C1PyGtdvsLrk07h3cDghy7X8b2lKYyS-g';
