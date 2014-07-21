<?php

/**
 * ownCloud - user_servervars
 *
 * @author Jean-Jacques Puig
 * @copyright 2013 Jean-Jacques Puig // ESPCI ParisTech
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU AFFERO GENERAL PUBLIC LICENSE
 * License as published by the Free Software Foundation; either
 * version 3 of the License, or any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU AFFERO GENERAL PUBLIC LICENSE for more details.
 *
 * You should have received a copy of the GNU Affero General Public
 * License along with this library.  If not, see <http://www.gnu.org/licenses/>.
 *
 */

OCP\App::checkAppEnabled('user_servervars');

OC_Util::checkAdminUser();

// define('SERVERVARS_RO_BINDING', true);

require_once 'user_servervars/config/parameters.php';

$params = servervars_getParameters();

OCP\Util::addscript('user_servervars', 'settings');

if ($_POST) {
    foreach ($params as $param => $default_value) {
        if (defined('SERVERVARS_RO_BINDING')) {
            switch ($param) {
            case 'servervars_login_name':
            case 'servervars_display_name':
            case 'servervars_email':
            case 'servervars_group':
                continue 2;
            }
        }

        if (isset($_POST[$param])) {
            switch ($param) {
            case 'servervars_autocreate':
            case 'servervars_update_user_data':
                // unchecked checkboxes are not included in the post parameters
                OCP\Config::setAppValue('user_servervars',
                                            $param, 0);
                break;

            default:
                OCP\Config::setAppValue('user_servervars',
                                            $param, $_POST[$param]);
                break;
            }
        }
    }
}

$tmpl = new OCP\Template('user_servervars', 'settings');
foreach ($params as $param => $default_value) {
    $value = OCP\Config::getAppValue('user_servervars', $param, $default_value);
    $value = htmlentities($value);
    $tmpl->assign($param, $value);
}

return $tmpl->fetchPage();
