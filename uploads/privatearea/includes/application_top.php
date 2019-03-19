<?php
/**
 * @package admin
 * @copyright Copyright 2003-2010 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version $Id: application_top.php 15766 2010-03-31 20:17:56Z drbyte $
 */
/**
 * File contains just application_top code
 *
 * Initializes common classes & methods. Controlled by an array which describes
 * the elements to be initialised and the order in which that happens.
 *
 * @package admin
 * @copyright Copyright 2003-2010 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 */
/**
 * boolean if true the autoloader scripts will be parsed and their output shown. For debugging purposes only.
 */
define('DEBUG_AUTOLOAD', false);
/**
 * boolean used to see if we are in the admin script, obviously set to false here.
 * DO NOT REMOVE THE define BELOW. WILL BREAK ADMIN
 */
define('IS_ADMIN_FLAG', true);
/**
 * integer saves the time at which the script started.
 */
// Start the clock for the page parse time log
define('PAGE_PARSE_START_TIME', microtime());
/**
 * set the level of error reporting
 *
 * Note STRICT_ERROR_REPORTING should never be set to true on a production site. <br />
 * It is mainly there to show php warnings during testing/bug fixing phases.<br />
 * note for strict error reporting we also turn on show_errors as this may be disabled<br />
 * in php.ini. Otherwise we respect the php.ini setting
 *
 */
if (defined('STRICT_ERROR_REPORTING') && STRICT_ERROR_REPORTING == true) {
    @ini_set('display_errors', true);
    error_reporting(version_compare(PHP_VERSION, 5.3, '>=') ? E_ALL & ~E_DEPRECATED & ~E_NOTICE : version_compare(PHP_VERSION, 6.0, '>=') ? E_ALL & ~E_DEPRECATED & ~E_NOTICE & ~E_STRICT : E_ALL & ~E_NOTICE);
}
/*
 * turn off magic-quotes support, for both runtime and sybase, as both will cause problems if enabled
 */
// set php_self in the local scope
if (!isset($PHP_SELF)) {
    $PHP_SELF = $_SERVER['PHP_SELF'];
}

/**
 * Set the local configuration parameters - mainly for developers
 */
if (file_exists('includes/local/configure.php')) {
    /**
   * load any local(user created) configure file.
   */
  include('includes/local/configure.php');
}
/**
 * check for and load application configuration parameters
 */
if (file_exists('includes/configure.php')) {
    /**
   * load the main configure file.
   */
  include('includes/configure.php');
} else {
    die('ERROR: ' . basename(getcwd()) . '/includes/configure.php file not found.');
}

if (file_exists(ADMIN_PATH . 'includes/defined_paths.php')) {
    require_once(ADMIN_PATH . 'includes/defined_paths.php');
}
/**
 * include the list of extra configure files
 */
if ($za_dir = @dir(DIR_WS_INCLUDES . 'extra_configures')) {
    while ($zv_file = $za_dir->read()) {
        if (preg_match('/\.php$/', $zv_file) > 0) {
            /**
       * load any user/contribution specific configuration files.
       */
      include(DIR_WS_INCLUDES . 'extra_configures/' . $zv_file);
        }
    }
    $za_dir->close();
}
/**
 * init some vars
 */
$template_dir = '';
define('DIR_WS_TEMPLATES', DIR_WS_INCLUDES . 'templates/');
/**
 * Prepare init-system
 */
unset($loaderPrefix); // admin doesn't need this override
$autoLoadConfig = array();
if (isset($loaderPrefix)) {
    $loaderPrefix = preg_replace('/[a-z_]^/', '', $loaderPrefix);
} else {
    $loaderPrefix = 'config';
}
$loader_file = $loaderPrefix . '.core.php';
require('includes/initsystem.php');
/**
 * load the autoloader interpreter code.
 */
  require(DIR_FS_CATALOG . 'includes/autoload_func.php');

/*************************************************************************************************************************************/
/*************************************************************************************************************************************/
/*************************************************************************************************************************************/
/*************************************************************************************************************************************/

 unset($module);
 $module['id'] = 'privatearea';
 $module['name'] = 'PrivateArea';
 $module['version'] = '3.2';
 $module['release_date'] = '2015-01-16';
 
 @unlink('includes/functions/extra_functions/custom_functions.php');
 @unlink('includes/functions/extra_functions/privatearea.php');

 if (PROVIDER_PANEL_STATUS == 'Disabled' and basename($PHP_SELF) != "login.php" and basename($PHP_SELF) != "logoff.php") {
    zen_redirect(zen_href_link('logoff', '', 'SSL'));
}

//check if the coupon id is from this provider
function checkcoupon($coupon_id)
{
    global $db;

    $sql = "select manufacturers_id
      from " . TABLE_COUPONS . "
      where coupon_id = '" . (int)$coupon_id . "'";

    $coupon = $db->Execute($sql);

    return ($coupon->RecordCount() > 0) ? $coupon->fields['manufacturers_id'] : "";
}

function checkAccess($page, $redirect = false)
{
    global $db;

    $sql = "select manufacturers_accesscontrol
      from " . TABLE_MANUFACTURERS . "
      where manufacturers_id = '" . (int)$_SESSION['provider_id'] . "'";

    $result = $db->Execute($sql);

    $accesslevel = unserialize($result->fields['manufacturers_accesscontrol']);

    if (in_array($page, $accesslevel) || in_array($page . '.php', $accesslevel)) {
        return true;
    }

    if ($redirect) {
        zen_redirect(zen_href_link('denied'));
    }
    return false;
}

function custom_get_provider_cpath($category = '')
{
    if (empty($category)) {
        $category = get_manufacturers_data($_SESSION['provider_id'], 'category_id');
    }

    $provider_cPath = zen_get_generated_category_path_ids($category);
    $provider_cPath = explode('_', $provider_cPath);
    $provider_cPath = array_reverse($provider_cPath);
    $provider_cPath = implode('_', $provider_cPath);

    return $provider_cPath;
}
//check if the sales is from a provider
function checksales($sales_id)
{
    global $db;

    $sql = "select manufacturers_id
                      from " . TABLE_SALEMAKER_SALES . "
                      where sale_id = '" . (int)$sales_id . "'";

    $sales = $db->Execute($sql);

    return ($sales->RecordCount() > 0) ? $sales->fields['manufacturers_id'] : "";
}

function get_mid_by_categoryid($category_id)
{
    global $db;

    $sql = "select manufacturers_id
                      from " . TABLE_SALEMAKER_SALES . "
                      where category_id = '" . (int)$category_id . "'";

    $mid = $db->Execute($sql);

    return ($mid->RecordCount() > 0) ? $mid->fields['manufacturers_id'] : "";
}

function get_manufacturers_data($manufacturers_id, $field = 'manufacturers_name')
{
    global $db;

    if (isset($manufacturers_id)) {
        $sql = "select $field
        from " . TABLE_MANUFACTURERS . " m
        where m.manufacturers_id = '" . (int)$manufacturers_id . "'";

        $manufacturer = $db->Execute($sql);

        return ($manufacturer->RecordCount() > 0) ? $manufacturer->fields[$field] : "";
    }
}

if (!function_exists('zen_get_products_manufacturers_id')) {
    function zen_get_products_manufacturers_id($product_id)
    {
        global $db;

        $product_query = "select p.manufacturers_id
          from " . TABLE_PRODUCTS . " p
          where p.products_id = '" . (int)$product_id . "'";

        $product = $db->Execute($product_query);

        return $product->fields['manufacturers_id'];
    }
}

//generate a warning message to the user and send a email message with the warning to the admin
function gen_warning($warning, $additional = '', $type = 'warning')
{
    global $messageStack, $filename;

    switch ($warning) {
    case 'page':
      $forbidden = sprintf(FORBIDDEN_PAGE, $additional);
      $usermessage = sprintf(NOT_ALLOWED_PAGE, $additional);
      break;

    case 'action':
      $forbidden = sprintf(FORBIDDEN_ACTION, $additional, $_SESSION['provider_name']);
      $usermessage = sprintf(NOT_ALLOWED_ACTION, $additional);
      break;

    case 'section':
      $forbidden = sprintf(FORBIDDEN_SECTION, $additional, $_SESSION['provider_name']);
      $usermessage = NOT_ALLOWED_SECTION;
      break;

    case 'coupon':
      $forbidden = sprintf(FORBIDDEN_COUPON, $additional, $_SESSION['provider_name']);
      $usermessage = NOT_ALLOWED_COUPON;
      break;

    case 'sales':
      $forbidden = sprintf(FORBIDDEN_SALES, $additional, $_SESSION['provider_name']);
      $usermessage = NOT_ALLOWED_SECTION;
      break;

    case 'product':
      $forbidden = sprintf(FORBIDDEN_PRODUCT, $additional, $_SESSION['provider_name']);
      $usermessage = NOT_ALLOWED_PRODUCT;
      break;

    case 'category':
      $forbidden = sprintf(FORBIDDEN_CATEGORY, $additional, $_SESSION['provider_name']);
      $usermessage = NOT_ALLOWED_CATEGORY;
      break;

    case 'nocategory':
      $forbidden = sprintf(MANUFACTURER_NO_CATEGORY, $additional);
      break;
  }

    if ($usermessage) {
        $messageStack->add_session($usermessage, $type);
    }

    $subject = sprintf(FORBIDDEN_SUBJ, $_SESSION['provider_name']);
    $message = sprintf(FORBIDDEN_MSG, $_SESSION['provider_name']) . "\n\n";
    $message .= $forbidden . "\n\n";
    $html_msg['EMAIL_MESSAGE_HTML'] = nl2br($message);
    zen_mail(STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS, $subject, $message, $_SESSION['provider_name'], $_SESSION['provider_email'], $html_msg);

    zen_redirect(zen_href_link(FILENAME_DEFAULT));
}

function zen_get_manufacturers_tous($manufacturers_id, $fn_language_id)
{
    global $db;
    $sql = "select manufacturers_tous
             from " . TABLE_MANUFACTURERS_INFO . "
             where manufacturers_id = '" . (int)$manufacturers_id . "'
             and languages_id = '" . $fn_language_id . "'";

    $tous = $db->Execute($sql);

    return $tous->fields['manufacturers_tous'];
}

function provider_log_login($message, $provider_login, $provider_password, $provider_id = 0, $provider_name = '')
{
    global $db;

    $sql_data_array['provider_id'] = (int)$provider_id;
    $sql_data_array['provider_name'] = zen_db_prepare_input($provider_name);
    $sql_data_array['provider_login'] = zen_db_prepare_input($provider_login);
    $sql_data_array['provider_password'] = zen_db_prepare_input($provider_password);
    $sql_data_array['date'] = 'now()';
    $sql_data_array['log_message'] = zen_db_prepare_input($message);
    $sql_data_array['ip_address'] = substr($_SERVER['REMOTE_ADDR'], 0, 15);
    zen_db_perform(TABLE_PROVIDER_LOGIN_LOG, $sql_data_array, 'insert');

    return true;
}


//hold the data for manufacturer individual
if ($_SESSION['provider_id']) {
    $provider = $db->Execute("SELECT * FROM " . TABLE_MANUFACTURERS . " WHERE manufacturers_id = " . $_SESSION['provider_id']);

    global $provider_category;
    $provider_category = get_manufacturers_data($_SESSION['provider_id'], 'category_id');
/*
    $access_role = array(
        'alt_nav' 						=> array(),
        FILENAME_ATTRIBUTES_CONTROLLER 	=> array(
            'add_product_attributes',
            'attributes_preview',
            'update_product',
            'set_flag_attributes_required',
            'update_attribute',
            'update_product_attribute',
            'delete_product_attribute',
            'delete_attribute',
            'set_products_filter',
            'new_cat',

        ),
        FILENAME_CATEGORIES 			=> array(
            'new_product',
            'set_editor',
            'attribute_features',
            'setflag',
            'edit_category',
            'update_category',
        ),
        FILENAME_COUPON_ADMIN 			=> array(
            'new',
            'update',
            'update_confirm',
            'voucherreport',
            'voucheredit',
            'vouchercopy',
            'confirmcopy',
            'voucherdelete',
            'confirmdelete',
            'email',
            'preview_email',
            'send_email_to_user',
        ),
        'coupon_restrict' 				=> array(
            'add_category',
            'add_product',
            'remove',
            'switch_status',
        ),
        FILENAME_DEFAULT				=> array(),
        'help'							=> array(),
        FILENAME_IMAGE_HANDLER			=> array(
            'set_products_filter',
            'layout_new',
            'save',
            'layout_delete',
            'layout_info',
            'layout_edit',
            'delete',
        ),
        FILENAME_LOGIN 					=> array(),
        FILENAME_LOGOFF 				=> array(),
        FILENAME_MY_PROFILE				=> array(
            'save',
            'edit',
            'logoff',
        ),
        FILENAME_PRODUCT 				=> array(
            'new_product',
            'new_product_preview',
            'copy_to',
            'copy_to_confirm',
            'update_product',
            'delete_product',
            'delete_product_confirm',
            'insert_product',
            'move_product',
            'move_product_confirm',

        ),
        FILENAME_PRODUCTS_PRICE_MANAGER => array(
            'set_products_filter',
            'delete_special',
            'edit',
            'edit_update',
            'update',
            'new_cat',
        ),
        FILENAME_PRODUCTS_TO_CATEGORIES => array(
            'set_master_categories_id',
            'update_product',
            'edit',
            'set_products_filter',
            'remove_categories_products_to_another_category_linked',
            'new_cat',
        ),
        FILENAME_SALEMAKER 				=> array(
            'new',
            'insert',
            'edit',
            'update',
            'setflag',
            'delete',
            'deleteconfirm',
            'copy',
            'copyconfirm',
        ),
        FILENAME_SALEMAKER_INFO 		=> array(),
        FILENAME_SALEMAKER_POPUP 		=> array(),
        FILENAME_SPECIALS		 		=> array(
            'insert',
            'edit',
            'delete',
            'deleteconfirm',
            'setflag',
            'new',
            'update',
        ),
        FILENAME_STATS_SALES_REPORT		=> array(
            'install',
        ),
    );

    */
    $filename = basename($_SERVER['SCRIPT_FILENAME'], '.php');

    //check if the user has access to the page
    /*if (!array_key_exists($filename, $access_role) AND $filename != FILENAME_CATEGORIES)
    {
        gen_warning('page', $filename);
    }
    // check if the user has access to this action on this page
    elseif (isset($_GET['action']) AND !in_array($_GET['action'], $access_role[$filename]))
    {
        gen_warning('action', $filename . '.php?action=' . $_GET['action']);
    }*/

    //check if this coupon is from this manufacturer
    if ($_GET['cid'] > 0 and ($filename == FILENAME_COUPON_ADMIN or $filename == 'coupon_restrict') and $_SESSION['provider_id'] != checkcoupon($_GET['cid'])) {
        gen_warning('coupon', $_GET['cid'], 'caution');
    }

    //check if this sales information is from this manufacturer
    if (isset($_GET['cid']) and $filename == FILENAME_SALEMAKER_POPUP and strpos(custom_get_provider_cpath($_GET['cid']), custom_get_provider_cpath()) === false) {
        gen_warning('section', $filename . '.php?action=' . $_GET['action'] . ' - ' . CATEGORYD_ID . ': ' . $_GET['cid'] . ' ');
    }

    //check if the product it's from this manufacturer
    if ($_GET['products_filter'] > 0 and $_SESSION['provider_id'] != zen_get_products_manufacturers_id($_GET['products_filter'])) {
        gen_warning('product', $_GET['products_filter']);
    }

    if ($_GET['add_products_id'] > 0 and ($_SESSION['provider_id'] != zen_get_products_manufacturers_id($_GET['add_products_id']))) {
        gen_warning('product', $_GET['add_products_id']);
    }

    //check if the product it's from this manufacturer
    if ($_GET['pID'] > 0 and ($_SESSION['provider_id'] != zen_get_products_manufacturers_id($_GET['pID']))) {
        gen_warning('product', $_GET['pID']);
    }

    //check if this sales is from this manufacturer
    if (($_GET['sID'] > 0 and $filename == FILENAME_SALEMAKER) and ($_SESSION['provider_id'] != checksales($_GET['sID']))) {
        gen_warning('sales', $_GET['sID']);
    }
}


  function zen_get_product_url($zf_product_id)
  {
      global $db;
      $cPath = zen_get_product_path($zf_product_id);

      $sql = "select products_type from " . TABLE_PRODUCTS . " where products_id = '" . (int)$zf_product_id . "'";
      $zp_type = $db->Execute($sql);
      if ($zp_type->RecordCount() == 0) {
          $type_handler = 'product';
      } else {
          $zp_product_type = $zp_type->fields['products_type'];
          $sql = "select type_handler from " . TABLE_PRODUCT_TYPES . " where type_id = '" . (int)$zp_product_type . "'";
          $zp_handler = $db->Execute($sql);
          $type_handler = $zp_handler->fields['type_handler'];
      }

      return zen_href_link($type_handler, 'cPath=' . $cPath . '&product_type=' . $zp_type->fields['products_type'] . '&pID=' . $zf_product_id . '&action=new_product');
  }


if (!function_exists('zen_parse_url')) {
    function zen_parse_url($url, $element = 'array')
    {
        // Read the various elements of the URL, to use in auto-detection of admin foldername (basically a simplified parse_url equivalent which automatically supports ports and uncommon TLDs)
    $t1 = array();
    // scheme
    $s1 = explode('://', $url);
        $t1['scheme'] = $s1[0];
    // host
    $s2 = explode('/', trim($s1[1], '/'));
        $t1['host'] = $s2[0];
        array_shift($s2);
    // path/uri
    $t1['path'] = implode('/', $s2);
        $p1 = ($t1['path'] != '') ? '/' . $t1['path'] : '';

        switch ($element) {
        case 'path':
        case 'host':
        case 'scheme':
            return $t1[$element];
        case '/path':
            return $p1;
        case 'array':
        default:
            return $t1;
    }
    }
}
