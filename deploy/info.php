<?php

/////////////////////////////////////////////////////////////////////////////
// General information
/////////////////////////////////////////////////////////////////////////////

$app['basename'] = 'joomla';
$app['version'] = '1.0.0';
$app['release'] = '1';
$app['vendor'] = 'ClearFoundation';
$app['packager'] = 'ClearFoundation';
$app['license'] = 'GPLv3';
$app['license_core'] = 'LGPLv3';
$app['description'] = lang('joomla_app_description');

/////////////////////////////////////////////////////////////////////////////
// App name and categories
/////////////////////////////////////////////////////////////////////////////

$app['name'] = lang('joomla_app_name');
$app['category'] = lang('base_category_server');
$app['subcategory'] = lang('base_subcategory_web');

/////////////////////////////////////////////////////////////////////////////
// Controllers
/////////////////////////////////////////////////////////////////////////////

$app['controllers']['joomla']['title'] = $app['name'];
$app['controllers']['settings']['title'] = lang('base_settings');
$app['controllers']['upload']['title'] = lang('base_app_upload');
$app['controllers']['advanced']['title'] = lang('base_app_advanced_settings');

/////////////////////////////////////////////////////////////////////////////
// Packaging
/////////////////////////////////////////////////////////////////////////////

$app['core_requires'] = array(
    'app-web-server-core >= 1:1.4.40',
    'webapp-joomla',
);

/*
$app['core_file_manifest'] = array(
    'system_database_default.conf' => array ('target' => '/etc/clearos/storage.d/system_database_default.conf'),
    'system-mysqld.php' => array('target' => '/var/clearos/base/daemon/system-mysqld.php'),
);
*/
