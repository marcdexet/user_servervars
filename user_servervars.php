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

require_once 'user_servervars/config/parameters.php';
require_once 'user_servervars/lib/functions.php';

class OC_USER_SERVERVARS extends OC_User_Backend {

    public $ssoUrl;
    public $sloUrl;
    public $autocreate;
    public $updateUserData;
    public $protectedGroups;
    public $defaultGroups;
    public $loginName;
    public $displayName;
    public $email;
    public $group;


    public function __construct() {
        foreach (servervars_getParameters() as $name => $default_value) {
            $attribute = servervars_param2caml(
                            preg_replace('/^servervars_/', '', $name));
            $this->$attribute = OCP\Config::getAppValue('user_servervars',
                                                        $name,
                                                        $default_value);
        }
    }


    public function checkPassword($uid, $password) {
        if (isset($this->loginName)) {
            $uid = servervars_getAuthenticatedUser($this->loginName);
            if ($uid === false) {
                return false;
            }
            else {
                OC_Log::write('servervars',
                                'Authenticated user ' . $uid,
                                OC_Log::DEBUG);
            }
        }
        else {
            OC_Log::write('servervars',
                            'Required mapping \'servervars_login_name\' not found',
                            OC_Log::DEBUG);
        }

        return $uid;
    }

}
