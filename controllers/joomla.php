<?php

/**
 * Joomla controller.
 *
 * @category   apps
 * @package    joomla
 * @subpackage controllers
 * @author     ClearFoundation <developer@clearfoundation.com>
 * @copyright  2014 ClearFoundation
 * @license    http://www.gnu.org/copyleft/gpl.html GNU General Public License version 3 or later
 * @link       http://www.clearfoundation.com/docs/developer/apps/joomla/
 */

///////////////////////////////////////////////////////////////////////////////
//
// This program is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with this program.  If not, see <http://www.gnu.org/licenses/>.
//
///////////////////////////////////////////////////////////////////////////////

///////////////////////////////////////////////////////////////////////////////
// C L A S S
///////////////////////////////////////////////////////////////////////////////

/**
 * Joomla controller.
 *
 * @category   apps
 * @package    joomla
 * @subpackage controllers
 * @author     ClearFoundation <developer@clearfoundation.com>
 * @copyright  2014 ClearFoundation
 * @license    http://www.gnu.org/copyleft/gpl.html GNU General Public License version 3 or later
 * @link       http://www.clearfoundation.com/docs/developer/apps/joomla/
 */

class Joomla extends ClearOS_Controller
{
    /**
     * Joomla default controller.
     *
     * @return view
     */

    function index()
    {
        // Load libraries
        //---------------

        $this->lang->load('joomla');
        $this->load->library('joomla/Webapp_Driver');

        // Load view data
        //---------------

        try {
            $is_initialized = $this->webapp_driver->is_initialized();
        } catch (\Exception $e) {
            $this->page->view_exception($e);
            return;
        }

        // Load controllers
        //-----------------

        if (!$is_initialized)
            redirect('/joomla/initialize');

        $views = array('joomla/overview', 'joomla/upload', 'joomla/settings', 'joomla/advanced');

        $this->page->view_controllers($views, lang('joomla_app_name'));
    }
}
