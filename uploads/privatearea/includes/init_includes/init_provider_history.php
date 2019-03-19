<?php
/**
 * @package admin
 * @copyright Copyright 2003-2007 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version $Id: init_provider_history.php
 */
if (!defined('IS_ADMIN_FLAG')) {
  die('Illegal Access');
}

  // log page visit into admin activity history
  if (basename($PHP_SELF) != FILENAME_DEFAULT . '.php' AND basename($PHP_SELF) != FILENAME_LOGIN . '.php') {
    $sql_data_array = array( 'access_date' => 'now()',
                             'provider_id' => (int)$_SESSION['provider_id'],
                             'page_accessed' =>  basename($PHP_SELF) . (!isset($_SESSION['provider_id']) || (int)$_SESSION['provider_id'] == 0 ? ' ' . (isset($_POST['provider_id']) ? $_POST['manufacturers_name'] : (isset($_POST['manufacturers_email']) ? $_POST['manufacturers_email'] : '') ) : ''),
                             'page_parameters' => zen_get_all_get_params(),
                             'ip_address' => substr($_SERVER['REMOTE_ADDR'],0,15)
                             );
    zen_db_perform(TABLE_PROVIDER_ACTIVITY_LOG, $sql_data_array);
  }
?>