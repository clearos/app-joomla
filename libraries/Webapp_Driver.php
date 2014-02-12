<?php

/**
 * Joomla webapp driver.
 *
 * @category   apps
 * @package    joomla
 * @subpackage libraries
 * @author     ClearFoundation <developer@clearfoundation.com>
 * @copyright  2014 ClearFoundation
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

use \clearos\apps\base\File as File;
use \clearos\apps\webapp\Webapp_Engine as Webapp_Engine;

clearos_load_library('base/File');
clearos_load_library('webapp/Webapp_Engine');

///////////////////////////////////////////////////////////////////////////////
// C L A S S
///////////////////////////////////////////////////////////////////////////////

/**
 * Joomla webapp driver.
 *
 * @category   apps
 * @package    joomla
 * @subpackage libraries
 * @author     ClearFoundation <developer@clearfoundation.com>
 * @copyright  2014 ClearFoundation
 * @license    http://www.gnu.org/copyleft/lgpl.html GNU Lesser General Public License version 3 or later
 * @link       http://www.clearfoundation.com/docs/developer/apps/joomla/
 */

class Webapp_Driver extends Webapp_Engine
{
    ///////////////////////////////////////////////////////////////////////////////
    // M E T H O D S
    ///////////////////////////////////////////////////////////////////////////////

    /**
     * Joomla webapp constructor.
     */

    public function __construct()
    {
        clearos_profile(__METHOD__, __LINE__);

        parent::__construct('joomla');
    }

    /**
     * Returns admin URLs.
     *
     * @return array list of admin URLs
     * @throws Engine_Exception
     */

    function get_admin_urls()
    {
        clearos_profile(__METHOD__, __LINE__);

        $this->_load_config();

        $urls = array();

        if ($this->get_hostname_access())
            $urls[] = 'https://' . $this->get_hostname() . '/administrator';

        if ($this->get_directory_access())
            $urls[] = 'https://' . $this->_get_ip_for_url() . $this->get_directory() . '/administrator';

        return $urls;
    }

    /**
     * Returns default directory access policy.
     *
     * @return boolean default directory access policy
     */

    function get_directory_access_default()
    {
        clearos_profile(__METHOD__, __LINE__);

        return TRUE;
    }

    /**
     * Returns default hostname access policy.
     *
     * @return boolean default hostname access policy
     */

    function get_hostname_access_default()
    {
        clearos_profile(__METHOD__, __LINE__);

        return TRUE;
    }

    /**
     * Returns webapp nickname.
     *
     * @return string webapp nickname
     */

    public function get_nickname()
    {
        clearos_profile(__METHOD__, __LINE__);

        return 'joomla';
    }

    /**
     * Returns getting started message to guide end user.
     *
     * @return string getting started message
     */

    public function get_getting_started_message()
    {
        clearos_profile(__METHOD__, __LINE__);

        return lang('joomla_getting_started_message');
    }

    /**
     * Returns getting started URL.
     *
     * @return string getting started URL
     */

    public function get_getting_started_url()
    {
        clearos_profile(__METHOD__, __LINE__);

        if ($this->get_directory_access())
            return 'https://' . $this->_get_ip_for_url() . $this->get_directory();

        if ($this->get_hostname_access())
            return 'https://' . $this->get_hostname();
    }

    /**
     * Hook called by Webapp engine after unpacking files.
     *
     * @return void
     */

    protected function _post_unpacking_hook()
    {
        clearos_profile(__METHOD__, __LINE__);

        $target_path = $this->path_install . '/' . self::PATH_WEBROOT . '/' . self::PATH_LIVE . '/';

        // Robots.txt
        $file = new File($target_path . '/robots.txt.dist');
        if ($file->exists())
            $file->move_to($target_path . '/robots.txt');

        // .htaccess
        $file = new File($target_path . '/htaccess.txt');
        if ($file->exists())
            $file->move_to($target_path . '/.htaccess');
    }
}
