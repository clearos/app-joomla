<?php

/////////////////////////////////////////////////////////////////////////////
// General information
/////////////////////////////////////////////////////////////////////////////

$app['basename'] = 'joomla';
$app['version'] = '1.0.2';
$app['release'] = '1';
$app['vendor'] = 'Xtreem Solution';
$app['packager'] = 'Xtreem Solution';
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
$app['controllers']['site']['title'] = lang('base_settings');
$app['controllers']['backup']['title'] = lang('base_backup');
$app['controllers']['version']['title'] = lang('base_version');

/////////////////////////////////////////////////////////////////////////////
// Packaging
/////////////////////////////////////////////////////////////////////////////

$app['core_requires'] = array(
    'app-certificate-manager-core',
    'app-flexshare-core',
    'app-mariadb-core',
    'app-php-engines-core',
    'app-web-server-core >= 1:2.4.5',
    'app-webapp >= 1:2.4.0',
);

$app['requires'] = array(
    'app-certificate-manager',
    'app-mariadb',
    'app-php-engines',
    'app-web-server >= 1:2.4.0',
    'unzip',
    'zip',
);

$app['core_directory_manifest'] = array(
    '/var/clearos/joomla/backup' => array(),
    '/var/clearos/joomla/backup' => array(
        'mode' => '0775',
        'owner' => 'webconfig',
        'group' => 'webconfig'
	),
    '/var/clearos/joomla/versions' => array(
        'mode' => '0775',
        'owner' => 'webconfig',
        'group' => 'webconfig'
    ),
);
