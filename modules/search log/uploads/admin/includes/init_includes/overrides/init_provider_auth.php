<?php
/**
 * @package provider profiles
 * @copyright Copyright Custom Scriptz - 2009
 * @copyright Portions Copyright kuroi web design 2006-2007
 * @copyright Portions Copyright 2003-2007 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version $Id: init_provider_auth.php
 */
if (!defined('IS_ADMIN_FLAG')) {
  die('Illegal Access');
}

  if (!(basename($PHP_SELF) == FILENAME_LOGIN . ".php")) {
  	$page = basename($PHP_SELF, ".php");
  	if ($page != FILENAME_DEFAULT &&
		$page != FILENAME_PRODUCT &&
		$page != FILENAME_LOGOFF &&
		$page != FILENAME_ALT_NAV &&
		$page != FILENAME_PASSWORD_FORGOTTEN &&
		$page != 'help' &&
		$page != 'ajax' &&
  		$page != 'salemaker_popup' &&
  		$page != 'coupon_restrict' &&
  		$page != 'stats_search_log' &&
  		$page != 'my_sales' &&
		$page != 'denied') {
		if (!checkAccess($page)) header("Location: denied.php");
	}
    if (!isset($_SESSION['provider_id'])) {
      if (!(basename($PHP_SELF) == FILENAME_PASSWORD_FORGOTTEN . '.php')) {
        zen_redirect(zen_href_link(FILENAME_LOGIN, '', 'SSL'));
      }
    }
  }


  if ((basename($PHP_SELF) == FILENAME_LOGIN . '.php') and (substr_count(dirname($PHP_SELF),'//') > 0 or substr_count(dirname($PHP_SELF),'.php') > 0)) {
    zen_redirect(zen_href_link(FILENAME_LOGIN, '', 'SSL'));
  }
?>