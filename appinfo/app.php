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

if (OCP\App::isEnabled('user_servervars')) {

    require_once 'user_servervars/user_servervars.php';

    OCP\App::registerAdmin('user_servervars', 'settings');

    OC_User::useBackend('SERVERVARS');

    OC::$CLASSPATH['OC_USER_SERVERVARS_Hooks']
        = 'user_servervars/lib/hooks.php';
    OCP\Util::connectHook('OC_User', 'post_login',
                            'OC_USER_SERVERVARS_Hooks', 'post_login');
    OCP\Util::connectHook('OC_User', 'logout',
                            'OC_USER_SERVERVARS_Hooks', 'logout');

    if(isset($_GET['app']) && $_GET['app'] == 'user_servervars') {

        require_once 'user_servervars/config/parameters.php';
        require_once 'user_servervars/lib/functions.php';

        $params = servervars_getParameters();

        $loginName
            =  OCP\Config::getAppValue('user_servervars',
                                        'servervars_login_name',
                                        $params['servervars_login_name']);

        $uid = servervars_getAuthenticatedUser($loginName);

        if ($uid === false) {
            $ssoURL
                = OCP\Config::getAppValue('user_servervars',
                                            'servervars_sso_url',
                                            $params['servervars_sso_url']);

            OCP\Response::redirect($ssoURL);

            exit();
        }

        if (!OCP\User::isLoggedIn() && !OC_User::login($uid, '')) {
            OC_Log::write('servervars',
                            'Error trying to log-in the user' . $uid,
                            OC_Log::DEBUG);
        }

        if (isset($_GET["linktoapp"])) {
            $path = OC::$WEBROOT . '/?app=' . $_GET["linktoapp"];

            if (isset($_GET["linktoargs"])) {
                $path .= '&'.urldecode($_GET["linktoargs"]);
            }

            OCP\Response::redirect($path);

            exit();
        }

        OC::$REQUESTEDAPP = '';
        OC_Util::redirectToDefaultPage();
    }
}
