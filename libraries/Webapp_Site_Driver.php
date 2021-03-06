<?php

/**
 * Joomla webapp site driver.
 *
 * @category   apps
 * @package    joomla
 * @subpackage libraries
 * @author     ClearFoundation <developer@clearfoundation.com>
 * @copyright  2017 ClearFoundation
 * @license    http://www.gnu.org/copyleft/lgpl.html GNU Lesser General Public License version 3 or later
 * @link       http://www.clearfoundation.com/docs/developer/apps/joomla/
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
// N A M E S P A C E
///////////////////////////////////////////////////////////////////////////////

namespace clearos\apps\joomla;

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
// D E P E N D E N C I E S
///////////////////////////////////////////////////////////////////////////////

// Classes
//--------

use \clearos\apps\base\File as File;
use \clearos\apps\base\Folder as Folder;
use \clearos\apps\base\Shell as Shell;
use \clearos\apps\joomla\Webapp_Version_Driver as Webapp_Version_Driver;
use \clearos\apps\web_server\Httpd as Httpd;
use \clearos\apps\webapp\Webapp_Site_Engine as Webapp_Site_Engine;

clearos_load_library('base/File');
clearos_load_library('base/Folder');
clearos_load_library('base/Shell');
clearos_load_library('joomla/Webapp_Version_Driver');
clearos_load_library('web_server/Httpd');
clearos_load_library('webapp/Webapp_Site_Engine');

// Exceptions
//-----------

use \clearos\apps\base\Engine_Exception as Engine_Exception;
use \clearos\apps\base\Validation_Exception as Validation_Exception;

clearos_load_library('base/Engine_Exception');
clearos_load_library('base/Validation_Exception');

///////////////////////////////////////////////////////////////////////////////
// C L A S S
///////////////////////////////////////////////////////////////////////////////

/**
 * Joomla webapp site driver.
 *
 * @category   apps
 * @package    joomla
 * @subpackage libraries
 * @author     ClearFoundation <developer@clearfoundation.com>
 * @copyright  2017 ClearFoundation
 * @license    http://www.gnu.org/copyleft/lgpl.html GNU Lesser General Public License version 3 or later
 * @link       http://www.clearfoundation.com/docs/developer/apps/joomla/
 */

class Webapp_Site_Driver extends Webapp_Site_Engine
{
    ///////////////////////////////////////////////////////////////////////////
    // C O N S T A N T S
    ///////////////////////////////////////////////////////////////////////////

    const WEBAPP_BASENAME = 'joomla';
    const COMMAND_MYSQL = '/usr/bin/mysql';
    const COMMAND_UNZIP = '/usr/bin/unzip';
    const FILE_CONFIG = 'configuration.php';

    ///////////////////////////////////////////////////////////////////////////////
    // M E T H O D S
    ///////////////////////////////////////////////////////////////////////////////

    /**
     * Joomla webapp site constructor.
     */

    public function __construct()
    {
        clearos_profile(__METHOD__, __LINE__);

        parent::__construct(self::WEBAPP_BASENAME);
    }

    /**
     * Adds a new Joomla site.
     *
     * @param string  $site                    site hostname
     * @param string  $aliases                 site aliases
     * @param string  $database_name           database name
     * @param string  $database_username       database user
     * @param string  $database_password       database user password
     * @param string  $database_admin_username database admin username
     * @param string  $database_admin_password database admin password
     * @param boolean $use_existing_database   use existing database flag
     * @param string  $version                 selected version zip file name
     * @param string  $ssl_certificate         SSL certificate name
     * @param string  $group                   group for upload access
     * @param boolean $ftp_enabled             flag for FTP upload state
     * @param boolean $file_enabled            flag for file/Samba upload state
     *
     * @return void
     */

    public function add($site, $aliases, $database_name, $database_username, $database_password,
        $database_admin_username, $database_admin_password, $use_existing_database, $version,
        $ssl_certificate, $group, $ftp_enabled, $file_enabled
    )
    {
        clearos_profile(__METHOD__, __LINE__);

        $webapp_version = new Webapp_Version_Driver(self::WEBAPP_BASENAME);

        // Validation
        //-----------

        Validation_Exception::is_valid($this->validate_site($site));
        Validation_Exception::is_valid($this->validate_aliases($aliases));
        Validation_Exception::is_valid($this->validate_database_name($database_name));
        Validation_Exception::is_valid($this->validate_database_username($database_username));
        Validation_Exception::is_valid($this->validate_database_password($database_password));
        Validation_Exception::is_valid($this->validate_database_username($database_admin_username));
        Validation_Exception::is_valid($this->validate_database_password($database_admin_password));
        Validation_Exception::is_valid($this->validate_ssl_certificate($ssl_certificate));
        Validation_Exception::is_valid($this->validate_group($group));
        Validation_Exception::is_valid($this->validate_ftp_state($ftp_enabled));
        Validation_Exception::is_valid($this->validate_file_state($file_enabled));
        Validation_Exception::is_valid($webapp_version->validate_version($version));

        // Database handling
        //------------------

        $options['validate_exit_code'] = FALSE;
        $shell = new Shell();

        if ($use_existing_database)
            $params = "mysql -u $database_admin_username -p$database_admin_password -e \"GRANT ALL PRIVILEGES ON $database_name.* TO $database_username@localhost IDENTIFIED BY '$database_password'\"";
        else
            $params = "mysql -u $database_admin_username -p$database_admin_password -e \"create database $database_name; GRANT ALL PRIVILEGES ON $database_name.* TO $database_username@localhost IDENTIFIED BY '$database_password'\"";

        $retval = $shell->execute(self::COMMAND_MYSQL, $params, FALSE, $options);
        $output = $shell->get_output();
        $output_message = strtolower($output[0]);
        if (strpos($output_message, 'error') !== FALSE)
            throw new Engine_Exception($output_message);

        // Add web site via Httpd API
        // --------------------------

        $httpd = new Httpd();

        $options['require_authentication'] = FALSE;
        $options['show_index'] = TRUE;
        $options['follow_symlinks'] = TRUE;
        $options['ssi'] = TRUE;
        $options['htaccess'] = TRUE;
        $options['cgi'] = FALSE;
        $options['require_ssl'] = FALSE;
        $options['custom_configuration'] = FALSE;
        $options['php'] = TRUE;
        $options['php_engine'] = Httpd::PHP_70;
        $options['web_access'] = Httpd::ACCESS_ALL;
        $options['folder_layout'] = Httpd::FOLDER_LAYOUT_SANDBOX;
        $options['system_permissions'] = Httpd::PERMISSIONS_THIRD_PARTY;
        $options['ssl_certificate'] = $ssl_certificate;
        $options['webapp'] = self::WEBAPP_BASENAME;
        $options['comment'] = lang('joomla_app_name') . ' - ' . $site;

        $httpd->add_site(
            $site,
            $aliases,
            $group,
            $ftp_enabled,
            $file_enabled,
            Httpd::TYPE_WEB_APP,
            $options
        );

        $this->_put_joomla($site, $version);
    }

    /**
     * Get database name from config file.
     *
     * @param string $site site name
     *
     * @return string $database_name Database Name
     */

    public function get_database_name($site)
    {
        clearos_profile(__METHOD__, __LINE__);

        $main_file = $this->get_document_root($site) . '/' . self::FILE_CONFIG;

        $file = new File($main_file, TRUE);

        if (!$file->exists())
            return '';

        $line = $file->lookup_line("/db =/");
        preg_match_all('/".*?"|\'.*?\'/', $line, $matches);
        $database_name = trim($matches[0][0], "'");

        return $database_name;
    }

    ///////////////////////////////////////////////////////////////////////////////
    // P R I V A T E  M E T H O D S
    ///////////////////////////////////////////////////////////////////////////////

    /**
     * Download and setup Joomla folder.
     *
     * @param string $site    site name
     * @param string $version version
     *
     * @return void
     */

    protected function _put_joomla($site, $version)
    {
        clearos_profile(__METHOD__, __LINE__);

        $webapp_version = new Webapp_Version_Driver('joomla');

        // Validation
        //-----------

        Validation_Exception::is_valid($webapp_version->validate_version($version));

        // Grab zip file name
        //-------------------

        $versions = $webapp_version->listing();

        $zip_file = $versions[$version]['local_path'];

        $doc_root = $this->get_document_root($site);

        if (empty($doc_root) || empty($zip_file))
            throw new Engine_Exception(lang('base_ooops'));

        // Unpack
        //-------

        // Update file permissons
        $folder = new Folder($doc_root, TRUE);
        $folder->chmod('2775'); // Set the sticky bit to preserve group ownership in folder

        // Unpack
        $shell = new Shell();
        $options['stdin'] = ' ';
        $shell->execute(self::COMMAND_UNZIP, "$zip_file -d $doc_root", TRUE, $options);

        // Security policy
        //----------------

        // Insecure: set all files/folders to be owned by apache
        // This needs to be improved, but product team vetoed it.
        $folder = new Folder($doc_root, TRUE);
        $folder->chown(Httpd::SERVER_USERNAME, '', TRUE);
    }

    ///////////////////////////////////////////////////////////////////////////////
    // U N U S E D  M E T H O D S
    ///////////////////////////////////////////////////////////////////////////////

    // FIXME: the following are not used. Delete?

    /**
     * Copies configuration file from sample file.
     *
     * @param string $folder_name Folder Name
     *
     * @return void
     */

    protected function _copy_sample_config_file($folder_name)
    {
        clearos_profile(__METHOD__, __LINE__);

        $dirname = dirname(__FILE__);
        $path = explode('/', $dirname);
        array_pop($path);
        $path[] = 'htdocs';
        $htdocs = implode('/', $path).'/';
        $sample_file =  $htdocs.self::CONFIG_SAMPLE_FILE_NAME;
       
        $main_file = $this->get_document_root() . '/' . self::FILE_CONFIG;
        $sample_file_obj    = new File($sample_file, TRUE);
        $main_file_obj      = new File($main_file, TRUE);

        if (!$main_file_obj->exists())
            $sample_file_obj->copy_to($main_file);
    }

    /**
     * Sets database name in config file.
     *
     * @param string $database_name Database Name
     *
     * @return @void
     */

    protected function _set_database_name($database_name)
    {
        clearos_profile(__METHOD__, __LINE__);

        $main_file = $this->get_document_root() . '/' . self::FILE_CONFIG;

        $file = new File($main_file, TRUE);

        $replace = '    public $db = '."'$database_name'".';'.PHP_EOL;
        $file->replace_lines('/db =/', $replace, 1);
    }

    /**
     * Sets database user in config file.
     *
     * @param string $database_username Database User
     *
     * @return @void
     */

    protected function _set_database_user($database_username)
    {
        clearos_profile(__METHOD__, __LINE__);

        $main_file = $this->get_document_root() . '/' . self::FILE_CONFIG;
        
        $file = new File($main_file, TRUE);

        $replace = '    public $user = '."'$database_username'".';'.PHP_EOL;
        $file->replace_lines('/user =/', $replace, 1);
    }

    /**
     * Sets database password in config file.
     *
     * @param string $database_password database password
     *
     * @return @void
     */

    protected function _set_database_password($database_password)
    {
        clearos_profile(__METHOD__, __LINE__);

        $main_file = $this->get_document_root() . '/' . self::FILE_CONFIG;
        
        $file = new File($main_file, TRUE);

        $replace = '    public $password = ' . "'$database_password'" . ';' . PHP_EOL;
        $file->replace_lines('/password =/', $replace, 1);
    }
}
