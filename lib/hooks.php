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

/**
 * This class contains all hooks.
 */
class OC_USER_SERVERVARS_Hooks {


    static public function post_login($parameters) {
        $uid = $parameters['uid'];

        $user_backend = new OC_USER_SERVERVARS();

        $just_created = false;
        if ($uid == servervars_getAuthenticatedUser($user_backend->loginName)) {

            if (!OC_User::userExists($uid)) {
                if ($user_backend->autocreate) {
                    if (preg_match( '/[^a-zA-Z0-9_.@-]/', $uid)) {
                        OC_Log::write('servervars',
                                        'Invalid username "'
                                            . $uid
                                            . '": allowed chars [a-zA-Z0-9_.@-]',
                                        OC_Log::DEBUG);

                        return false;
                    }
                    else {
                        OC_Log::write('servervars',
                                        'Creating new user: ' . $uid,
                                        OC_Log::DEBUG);
                        OC_User::createUser($uid, random_password());

                        if (!OC_User::userExists($uid)) {
                            return false;
                        }

                        $just_created = true;
                    }
                }
                else {
                    return false;
                }
            }

            if ($just_created || $user_backend->updateUserData) {
                $displayName
                    = servervars_evaluateMapping($user_backend->displayName);
                if ($displayName !== false) {
                    update_displayName($uid, $displayName);
                }

                $email = servervars_evaluateMapping($user_backend->email);
                if ($email !== false) {
                    update_mail($uid, $email);
                }

                $groups = servervars_evaluateMapping($user_backend->group);
                if (empty($groups) && !empty($user_backend->defaultGroups)) {
                    OC_Log::write('servervars',
                                    'Using default groups "'
                                        . $groups
                                        . '" for the user: '
                                        . $uid,
                                    OC_Log::DEBUG);
                    $groups = $user_backend->defaultGroups;
                }

                if ($groups !== false) {
                    update_groups($uid,
                                    $groups,
                                    $user_backend->protectedGroups,
                                    $just_created);
                }
            }

            return true;
        }

        return false;
    }


    static public function logout($parameters) {
        $user_backend = new OC_USER_SERVERVARS();

        $uid = servervars_getAuthenticatedUser($user_backend->loginName);
        OC_Log::write('servervars',
                        'Executing SERVERVARS logout: ' . $uid,
                        OC_Log::DEBUG);

        session_unset();
        session_destroy();
        OC_User::unsetMagicInCookie();

        if (!empty($user_backend->sloUrl)) {
            OCP\Response::redirect($user_backend->sloUrl);

            exit();
        }

        return true;
    }

}

function update_displayName($uid, $displayName) {
    if ($displayName !== OC_User::getDisplayName($uid)) {
        OC_Log::write('servervars',
                        'Set displayname "'
                            . $displayName
                            . '" for the user: '
                            . $uid,
                        OC_Log::DEBUG);
        OC_User::setDisplayName($uid, $displayName);
    }
}

function update_mail($uid, $email) {
    if ($email !== OC_Preferences::getValue($uid, 'settings', 'email', '')) {
        OC_Log::write('servervars',
                        'Set email "'
                            . $email
                            . '" for the user: '
                            . $uid,
                        OC_Log::DEBUG);
        OC_Preferences::setValue($uid, 'settings', 'email', $email);
    }
}

function update_groups($uid, $groups,
                        $protected_groups='', $just_created=false) {

    $groups = preg_split('/[, ]+/', $groups, -1, PREG_SPLIT_NO_EMPTY);
    $protected_groups = preg_split('/[, ]+/', $protected_groups, -1,
                                    PREG_SPLIT_NO_EMPTY);

    if(!$just_created) {
        $old_groups = OC_Group::getUserGroups($uid);
        foreach($old_groups as $group) {
            if(!in_array($group, $protected_groups)
                && !in_array($group, $groups)) {
                OC_Log::write('servervars',
                                'Remove "'
                                    . $uid
                                    . '" from group "'
                                    . $group
                                    .'"',
                                OC_Log::DEBUG);
                OC_Group::removeFromGroup($uid, $group);
            }
        }
    }

    foreach($groups as $group) {
        if (preg_match('/[^a-zA-Z0-9 _.@-]/', $group)) {
            OC_Log::write('servervars',
                            'Invalid group "'
                                . $group
                                . '": allowed chars [a-zA-Z0-9_.@-]',
                            OC_Log::DEBUG);
        }
        elseif (!OC_Group::inGroup($uid, $group)) {
            if (!OC_Group::groupExists($group)) {
                OC_Log::write('servervars',
                                'Adding new group: ' . $group,
                                OC_Log::DEBUG);
                OC_Group::createGroup($group);
            }
            OC_Log::write('servervars',
                            'Adding "'
                                . $uid
                                . '" to group "'
                                . $group
                                .'"',
                            OC_Log::DEBUG);
            OC_Group::addToGroup($uid, $group);
        }
    }
}

function random_password() {
    $valid_chars
        = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
    $length = 20;
    $ind_max_valid_chars = strlen($valid_chars) - 1;

    $random_string = '';
    for ($i = 0; $i < $length; $i++) {
        $random_pick = mt_rand(0, $ind_max_valid_chars);
        $random_char = $valid_chars[$random_pick];
        $random_string .= $random_char;
    }

    return $random_string;
}
