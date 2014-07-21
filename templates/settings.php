<link rel="stylesheet" type="text/css"
  href="user_servervars/css/servervars.css" />

<form id="servervars" action="#" method="post">
  <div id="servervarsSettings" class="personalblock">

  <strong><?php
    p($l->t('ServerVars Authentication and Provisioning backend'));
    ?></strong>

  <ul>

    <li><a href="#servervarsSettings-1"><?php
      p($l->t('Basic')); ?></a></li>

    <li><a href="#servervarsSettings-2"><?php
      if (defined('SERVERVARS_RO_BINDING')) {
        p($l->t('Mapping (Read-Only)'));
      }
      else {
        p($l->t('Mapping'));
      } ?></a></li>

  </ul>

  <fieldset id="servervarsSettings-1">

    <p>
      <label for="servervars_sso_url">
        <?php p($l->t('External URL to redirect to for authentication')); ?>
      </label>
      <input type="text"
        id="servervars_sso_url" name="servervars_sso_url"
	style="width:240px"
        value="<?php p($_['servervars_sso_url']); ?>">
    </p>

    <p>
      <label for="servervars_slo_url">
        <?php p($l->t('External URL to redirect to on logout')); ?>
      </label>
      <input type="text"
        id="servervars_slo_url" name="servervars_slo_url"
	style="width:240px"
        value="<?php p($_['servervars_slo_url']); ?>">
    </p>

    <p>
      <label for="servervars_autocreate">
        <?php p($l->t('Autocreate user after ServerVars login ?')); ?>
      </label>
      <input type="checkbox"
        id="servervars_autocreate" name="servervars_autocreate"
        <?php p((($_['servervars_autocreate'] != false) ? 'checked="checked"' : '')); ?>>
    </p>

    <p>
      <label for="servervars_update_user_data">
        <?php p($l->t('Update user data after login ?')); ?>
      </label>
      <input type="checkbox"
        id="servervars_update_user_data" name="servervars_update_user_data"
        <?php p((($_['servervars_update_user_data'] != false) ? 'checked="checked"' : '')); ?>>
    </p>

    <p>
      <label for="servervars_protected_groups">
        <?php p($l->t('Groups protected against synchronisation')); ?>
      </label>
      <input type="text"
        id="servervars_protected_groups" name="servervars_protected_groups"
	style="width:240px"
        value="<?php p($_['servervars_protected_groups']); ?>" />
    </p>

    <?php p($l->t('(protected groups are multivalued, use comma and/or space to separate values)')); ?>

    <p>
      <label for="servervars_default_groups">
        <?php p($l->t('Default group when autocreating user and no group data found for user')); ?>
      </label>
      <input type="text"
        id="servervars_default_groups" name="servervars_default_groups"
	style="width:240px"
        value="<?php p($_['servervars_default_groups']); ?>">
    </p>

  </fieldset>

  <fieldset id="servervarsSettings-2">

    <p>
      <label for="servervars_login_name">
        <?php p($l->t('Login name variable')); ?>
      </label>
      <input type="text"
        id="servervars_login_name" name="servervars_login_name"
	style="width:240px"
        value="<?php p($_['servervars_login_name']); ?>"
        <?php p((defined('SERVERVARS_RO_BINDING') ? 'readonly="readonly"' : '')); ?> />
    </p>

    <p>
      <label for="servervars_display_name">
        <?php p($l->t('Display name variable')); ?>
      </label>
      <input type="text"
        id="servervars_display_name" name="servervars_display_name"
	style="width:240px"
        value="<?php p($_['servervars_display_name']); ?>"
        <?php p((defined('SERVERVARS_RO_BINDING') ? 'readonly="readonly"' : '')); ?> />
    </p>

    <p>
      <label for="servervars_email">
        <?php p($l->t('Email variable')); ?>
      </label>
      <input type="text"
        id="servervars_email" name="servervars_email"
	style="width:240px"
        value="<?php p($_['servervars_email']); ?>"
        <?php p((defined('SERVERVARS_RO_BINDING') ? 'readonly="readonly"' : '')); ?> />
    </p>

    <p>
      <label for="servervars_group">
        <?php p($l->t('Primary group variable')); ?>
      </label>
      <input type="text"
        id="servervars_group" name="servervars_group"
	style="width:240px"
        value="<?php p($_['servervars_group']); ?>"
        <?php p((defined('SERVERVARS_RO_BINDING') ? 'readonly="readonly"' : '')); ?> />
    </p>

  </fieldset>

  <input type="submit" value="Save" />

  </div>
</form>
