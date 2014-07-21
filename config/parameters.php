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

function servervars_getParameters() {
    return array(
        'servervars_sso_url'            => '',
        'servervars_slo_url'            => '',
        'servervars_autocreate'         => 1,
        'servervars_update_user_data'   => 1,
        'servervars_protected_groups'   => 'admin',
        'servervars_default_groups'     => '',
        'servervars_login_name'         => '$_SERVER[\'REMOTE_USER\']',
        'servervars_display_name'       => '$_SERVER[\'REMOTE_USER_DISPLAY_NAME\']',
        'servervars_email'              => '$_SERVER[\'REMOTE_USER_EMAIL\']',
        'servervars_group'              => '$_SERVER[\'REMOTE_USER_GROUP\']',
    );
}
