<?php

/////////////////////////////////////////////////////////////////////////////
// General information
/////////////////////////////////////////////////////////////////////////////

$app['basename'] = 'joomla';
$app['version'] = '2.0.14';
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
$app['subcategory'] = lang('base_subcategory_content_management_systems');

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

$app['requires'] = array(
    'app-webapp',
    'app-system-database',
);

$app['core_requires'] = array(
    'app-webapp-core >= 1:1.6.1',
    'app-system-database-core >= 1:1.6.1',
    'webapp-joomla',
);

$app['core_directory_manifest'] = array(
    '/var/clearos/joomla' => array(),
    '/var/clearos/joomla/archive' => array(),
    '/var/clearos/joomla/backup' => array(),
    '/var/clearos/joomla/webroot' => array(),
);

$app['core_file_manifest'] = array(
    'webapp-joomla-flexshare.conf' => array(
        'target' => '/etc/clearos/flexshare.d/webapp-joomla.conf',
        'config' => TRUE,
        'config_params' => 'noreplace'
    ),
    'webapp-joomla-httpd.conf' => array(
        'target' => '/etc/httpd/conf.d/webapp-joomla.conf',
        'config' => TRUE,
        'config_params' => 'noreplace'
    )
);

