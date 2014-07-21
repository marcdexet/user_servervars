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

function servervars_param2caml($string) {
    $alphabet = 'abcdefghijklmnopqrstuvwxyz';

    $search = array_map(
                function($v) { return '_' . $v; },
                preg_split('//', $alphabet, -1, PREG_SPLIT_NO_EMPTY)
    );

    $replace = preg_split('//', strtoupper($alphabet), -1, PREG_SPLIT_NO_EMPTY);

    return str_ireplace($search, $replace, $string);
}

function servervars_evaluateMapping($mapping) {
    // anonymous function required to address limitations for anonymous access
    // to PHP superglobals such as $_SERVER...
    $f = create_function('', sprintf('return %s;', $mapping));

    try {
        $value = $f();
    }
    catch(Exception $e) {
        return false;
    }

    return $value;
}

function servervars_getAuthenticatedUser($mapping) {
    $uid = servervars_evaluateMapping($mapping);

    if (empty($uid)) {
        return false;
    }

    return $uid;
}
