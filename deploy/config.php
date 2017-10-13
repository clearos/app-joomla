<?php

/**
 * Joomla definition file.
 *
 * @category   apps
 * @package    joomla
 * @subpackage configuration
 * @author     ClearFoundation <developer@clearfoundation.com>
 * @copyright  2017 ClearFoundation
 * @license    http://www.gnu.org/copyleft/lgpl.html GNU Lesser General Public License version 3 or later
 * @link       http://www.clearfoundation.com/docs/developer/apps/openldap_directory/
 */

///////////////////////////////////////////////////////////////////////////////
//
// This program is free software: you can redistribute it and/or modify
// it under the terms of the GNU Lesser General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU Lesser General Public License for more details.
//
// You should have received a copy of the GNU Lesser General Public License
// along with this program.  If not, see <http://www.gnu.org/licenses/>.
//
///////////////////////////////////////////////////////////////////////////////

///////////////////////////////////////////////////////////////////////////////
// B O O T S T R A P
///////////////////////////////////////////////////////////////////////////////

$bootstrap = getenv('CLEAROS_BOOTSTRAP') ? getenv('CLEAROS_BOOTSTRAP') : '/usr/clearos/framework/shared';
require_once $bootstrap . '/bootstrap.php';

///////////////////////////////////////////////////////////////////////////////
// T R A N S L A T I O N S
///////////////////////////////////////////////////////////////////////////////

clearos_load_language('joomla');

///////////////////////////////////////////////////////////////////////////////
// C O N F I G
///////////////////////////////////////////////////////////////////////////////

$config['description'] = lang('joomla_app_name');
$config['backup_path'] = '/var/clearos/joomla/backup';
$config['version_path'] = '/var/clearos/joomla/versions';
$config['versions'] = array(
    '3.7.4' => array(
        'download_url' => 'https://downloads.joomla.org/cms/joomla3/3-7-4/Joomla_3-7.4-Stable-Full_Package.zip',
        'deletable' => FALSE,
        'size' => '',
    ),
    '3.7.3' => array(
        'download_url' => 'https://downloads.joomla.org/cms/joomla3/3-7-3/Joomla_3.7.3-Stable-Full_Package.zip',
        'deletable' => TRUE,
        'size' => '',
    ),
    '3.7.2' => array(
        'download_url' => 'https://downloads.joomla.org/cms/joomla3/3-7-2/Joomla_3-7.2-Stable-Full_Package.zip',
        'deletable' => TRUE,
        'size' => '',
    ),
    '3.7.1' => array(
        'download_url' => 'https://downloads.joomla.org/cms/joomla3/3-7-1/joomla_3-7-1-stable-full_package-zip',
        'deletable' => TRUE,
        'size' => '',
    ),
    '3.7.0' => array(
        'download_url' => 'https://downloads.joomla.org/cms/joomla3/3-7-0/joomla_3-7-0-stable-full_package-zip',
        'deletable' => TRUE,
        'size' => '',
    ),
    '3.6.5' => array(
        'download_url' => 'https://downloads.joomla.org/cms/joomla3/3-6-5/joomla_3-6-5-stable-full_package-zip',
        'deletable' => TRUE,
        'size' => '',
    ),
    '3.6.4' => array(
        'download_url' => 'https://downloads.joomla.org/cms/joomla3/3-6-4/joomla_3-6-4-stable-full_package-zip',
        'deletable' => TRUE,
        'size' => '',
    ),
    '3.6.3' => array(
        'download_url' => 'https://downloads.joomla.org/cms/joomla3/3-6-3/joomla_3-6-3-stable-full_package-zip',
        'deletable' => TRUE,
        'size' => '',
    ),
);

