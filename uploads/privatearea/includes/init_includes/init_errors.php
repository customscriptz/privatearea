<?php
/**
 * @package admin
 * @copyright Copyright 2003-2010 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version $Id: init_errors.php 17422 2010-08-31 08:42:09Z drbyte $
 */
if (!defined('IS_ADMIN_FLAG')) {
  die('Illegal Access');
}
// check if a default currency is set
  if (!defined('DEFAULT_CURRENCY')) {
    $messageStack->add(ERROR_NO_DEFAULT_CURRENCY_DEFINED, 'error');
  }

// check if a default language is set
  if (!defined('DEFAULT_LANGUAGE') || DEFAULT_LANGUAGE=='') {
    $messageStack->add(ERROR_NO_DEFAULT_LANGUAGE_DEFINED, 'error');
  }

  if (function_exists('ini_get') && ((bool)ini_get('file_uploads') == false) ) {
    $messageStack->add(WARNING_FILE_UPLOADS_DISABLED, 'warning');
  }

// set demo message
  if (zen_get_configuration_key_value('ADMIN_DEMO')=='1') {
    if (zen_admin_demo()) {
      $messageStack->add(ADMIN_DEMO_ACTIVE, 'warning');
    } else {
      $messageStack->add(ADMIN_DEMO_ACTIVE_EXCLUSION, 'warning');
    }
  }

// this will let the admin know that the website is DOWN FOR MAINTENANCE to the public
  if (DOWN_FOR_MAINTENANCE == 'true') {
    $messageStack->add(WARNING_ADMIN_DOWN_FOR_MAINTENANCE,'caution');
  }

// include the password crypto functions
  require(DIR_WS_FUNCTIONS . 'password_funcs.php');
?>