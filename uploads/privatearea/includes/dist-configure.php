<?php
/**
 * @copyright Custom Scriptz
 * @copyright Copyright 2003-2010 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 */

//put your shop and admin dir names. eg: 'shop/' - 'shop/admin/'  <- leave the trailing slash
//if your shop is on the top dir:
//define('STORE_DIR', '/');
//define('ADMIN_DIR', '/admin/');

define('STORE_DIR', '/shop/');
define('ADMIN_DIR', '/shop/admin/');

// -----------------------------------------------------------------------------------------------
// YOU CAN LEAVE AS IS BELOW THIS LINE
// ------------------------------------------------------------------------------------------------

define('PATH', $_SERVER['DOCUMENT_ROOT']);
define('STORE_PATH', PATH . STORE_DIR);
define('ADMIN_PATH', PATH . ADMIN_DIR);
define('PROVIDER_DIR', basename(getcwd()) . '/');
define('PROVIDER_PATH', STORE_PATH . PROVIDER_DIR);

define('DIR_FS_SQL_CACHE', STORE_PATH . PROVIDER_DIR . 'cache');
define('DIR_FS_PROVIDER', PROVIDER_PATH);

//path to your admin configure.php file
if (!file_exists(ADMIN_PATH . 'includes/configure.php')) {
    die('The file ' . ADMIN_PATH . 'includes/configure.php' . ' couldn\'t be found, are you sure that your ' . PROVIDER_PATH . 'includes/configure.php file is correct?');
}

require ADMIN_PATH . 'includes/configure.php';

// NOTE: be sure to leave the trailing '/' at the end of these lines if you make changes!
// * DIR_WS_* = Webserver directories (virtual/URL)
// these paths are relative to top of your webspace ... (ie: under the public_html or httpdocs folder)
define('DIR_WS_PROVIDER', DIR_WS_CATALOG . PROVIDER_DIR);
define('DIR_WS_HTTPS_PROVIDER', DIR_WS_HTTPS_CATALOG . PROVIDER_DIR);
