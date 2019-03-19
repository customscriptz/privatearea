<?php
/**
 * @copyright Custom Scriptz
 * @copyright Copyright 2003-2010 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 */

 require 'includes/application_top.php';
 
 $module = [];
 $module['id'] = 'privatearea';
 $module['name'] = 'PrivateArea';
 $module['version'] = '3.2';
 $module['release_date'] = '2015-01-16';
 
/* Run update routine */
if ($_GET['action'] == 'updateDB') {
    customScriptzCheckMenu();

    $checkfields = $db->metaColumns(TABLE_MANUFACTURERS_INFO);

    if (!$checkfields['MANUFACTURERS_TOUS']->type) {
        $db->Execute("ALTER TABLE " . TABLE_MANUFACTURERS_INFO . " ADD manufacturers_tous text NOT NULL default ''");
    }

    $checkfields = $db->metaColumns(TABLE_MANUFACTURERS);

    if (!$checkfields['MANUFACTURERS_LOGIN']->type) {
        $db->Execute("ALTER TABLE " . TABLE_MANUFACTURERS . " ADD manufacturers_login varchar(20) NOT NULL default ''");
    }

    if (!$checkfields['MANUFACTURERS_LAST_ACTIVITIES']->type) {
        $db->Execute("ALTER TABLE " . TABLE_MANUFACTURERS . " ADD manufacturers_last_activities tinyint(2) NOT NULL default '10'");
    }

    if (!$checkfields['MANUFACTURERS_PASSWORD']->type) {
        $db->Execute("ALTER TABLE " . TABLE_MANUFACTURERS . " ADD manufacturers_password varchar(96) NOT NULL default ''");
    }

    if (!$checkfields['MANUFACTURERS_EMAIL']->type) {
        $db->Execute("ALTER TABLE " . TABLE_MANUFACTURERS . " ADD manufacturers_email varchar(96) NOT NULL default ''");
    }

    if (!$checkfields['MANUFACTURERS_PAYPAL_EMAIL']->type) {
        $db->Execute("ALTER TABLE " . TABLE_MANUFACTURERS . " ADD manufacturers_paypal_email varchar(96) NOT NULL default ''");
    }

    if (!$checkfields['MANUFACTURERS_PAYPAL_CURRENCY']->type) {
        $db->Execute("ALTER TABLE " . TABLE_MANUFACTURERS . " ADD manufacturers_paypal_currency char(5) NOT NULL default 'USD'");
    }

    if (!$checkfields['MANUFACTURERS_COMMISSION']->type) {
        $db->Execute("ALTER TABLE " . TABLE_MANUFACTURERS . " ADD manufacturers_commission decimal(15,4) NOT NULL default '0.0000'");
    }

    if (!$checkfields['MANUFACTURERS_DISCOUNT_PAYMENT_FEE']->type) {
        $db->Execute("ALTER TABLE " . TABLE_MANUFACTURERS . " ADD manufacturers_discount_payment_fee tinyint(1) NOT NULL default 1");
    }

    if (!$checkfields['MANUFACTURERS_IMAGE_DIR']->type) {
        $db->Execute("ALTER TABLE " . TABLE_MANUFACTURERS . " ADD manufacturers_image_dir varchar(50) NULL default ''");
    }

    if (!$checkfields['CATEGORY_ID']->type) {
        $db->Execute("ALTER TABLE " . TABLE_MANUFACTURERS . " ADD category_id int(11) NOT NULL default 0");
    }

    if (!$checkfields['METATAGS']->type) {
        $db->Execute("ALTER TABLE " . TABLE_MANUFACTURERS . " ADD metatags tinyint(1) NOT NULL default 1");
    }

    if (!$checkfields['METATAGS_CHARS']->type) {
        $db->Execute("ALTER TABLE " . TABLE_MANUFACTURERS . " ADD metatags_chars char(10) NOT NULL default 500");
    }

    if (!$checkfields['MANUFACTURERS_COMMISSION_GROUP_BASED']->type) {
        $db->Execute("ALTER TABLE " . TABLE_MANUFACTURERS . " ADD manufacturers_commission_group_based tinyint(1) NOT NULL default 0");
    }

    if (!$checkfields['MANUFACTURERS_ACCESSCONTROL']->type) {
        $db->Execute("ALTER TABLE " . TABLE_MANUFACTURERS . " ADD manufacturers_accesscontrol TEXT NOT NULL");
        $db->Execute("UPDATE " . TABLE_MANUFACTURERS . ' SET manufacturers_accesscontrol = \'a:15:{i:0;s:14:"xsell_advanced";i:1;s:21:"attributes_controller";i:2;s:17:"downloads_manager";i:3;s:10:"categories";i:4;s:8:"featured";i:5;s:22:"products_price_manager";i:6;s:9:"salemaker";i:7;s:8:"specials";i:8;s:22:"products_to_categories";i:9;s:12:"coupon_admin";i:10;s:15:"coupon_restrict";i:11;s:18:"stats_sales_report";i:12;s:13:"image_handler";i:13;s:10:"my_profile";i:14;s:9:"freegifts";}\'');
    }

    if (!$checkfields['MANUFACTURERS_LAST_LOGIN']->type) {
        $db->Execute("ALTER TABLE " . TABLE_MANUFACTURERS . " ADD manufacturers_last_login datetime NOT NULL default '0001-01-01 00:00:00'");
    }

    if (!$checkfields['MANUFACTURERS_LOGIN_TIMES']->type) {
        $db->Execute("ALTER TABLE " . TABLE_MANUFACTURERS . " ADD manufacturers_login_times int(11) NOT NULL default '0'");
    }

    if (!$checkfields['MANUFACTURERS_PRODUCTS_PER_PAGE']->type) {
        $db->Execute("ALTER TABLE " . TABLE_MANUFACTURERS . " ADD manufacturers_products_per_page tinyint(3) NOT NULL default '35'");
    }

    if (!$checkfields['MANUFACTURERS_SHOW_DISABLED_PRODUCTS']->type) {
        $db->Execute("ALTER TABLE " . TABLE_MANUFACTURERS . " ADD manufacturers_show_disabled_products tinyint(1) NOT NULL default '1'");
    }

    $checkfields = $db->metaColumns(TABLE_ORDERS_PRODUCTS);
    if (!$checkfields['MANUFACTURERS_DIR']->type) {
        $db->Execute("ALTER TABLE " . TABLE_ORDERS_PRODUCTS . " ADD manufacturers_dir varchar(50) NOT NULL default ''");
    }

    $checkfields = $db->metaColumns(TABLE_PRODUCTS);
    if (!$checkfields['ORIGINAL_CAT']->type) {
        $db->Execute("ALTER TABLE " . TABLE_PRODUCTS . " ADD original_cat int(11) NOT NULL default 0");
    }

    if (!$checkfields['MOVED_TO_CAT']->type) {
        $db->Execute("ALTER TABLE " . TABLE_PRODUCTS . " ADD moved_to_cat int(11) NOT NULL default 0");
    }

    if (!$checkfields['PRODUCT_ON_QUEUE']->type) {
        $db->Execute("ALTER TABLE " . TABLE_PRODUCTS . " ADD product_on_queue int(11) NOT NULL default 0");
    }

    $checkfields = $db->metaColumns(TABLE_COUPONS);
    if (!$checkfields['MANUFACTURERS_ID']->type) {
        $db->Execute("ALTER TABLE " . TABLE_COUPONS . " ADD manufacturers_id int(11) NOT NULL default 0");
    }

    $db->Execute("CREATE TABLE IF NOT EXISTS " . DB_PREFIX . "provider_activity_log (
			log_id int(15) NOT NULL AUTO_INCREMENT,
			access_date datetime NOT NULL DEFAULT '0001-01-01 00:00:00',
			provider_id int(11) NOT NULL DEFAULT '0',
			page_accessed varchar(80) NOT NULL DEFAULT '',
			page_parameters text,
			ip_address varchar(15) NOT NULL DEFAULT '',
			PRIMARY KEY (log_id),
			KEY idx_page_accessed_zen (page_accessed),
			KEY idx_access_date_zen (access_date),
			KEY idx_ip_zen (ip_address)
		  ) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=0 ;");

    $db->Execute("CREATE TABLE IF NOT EXISTS " . DB_PREFIX . "provider_login_log (
			log_id int(15) NOT NULL AUTO_INCREMENT,
			provider_id int(11) NOT NULL DEFAULT '0',
			provider_name varchar(80) NOT NULL DEFAULT 'none',
			provider_password varchar(80) NOT NULL DEFAULT 'none',
			provider_login varchar(80) NOT NULL DEFAULT 'none',
			date datetime NOT NULL DEFAULT '0001-01-01 00:00:00',
			log_message varchar(255) NOT NULL DEFAULT '',
			ip_address varchar(15) NOT NULL DEFAULT '',
			PRIMARY KEY (log_id)
		  ) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=0 ;");

    $db->Execute("CREATE TABLE IF NOT EXISTS " . DB_PREFIX . "provider_accesscontrol (
		  accesscontrol_id int(11) NOT NULL AUTO_INCREMENT,
		  accesscontrol_title varchar(100) NOT NULL,
		  accesscontrol_master tinyint(1) NOT NULL,
		  accesscontrol_parent int(3) NOT NULL,
		  accesscontrol_filename varchar(50) NOT NULL,
		  PRIMARY KEY (accesscontrol_id),
		  UNIQUE KEY accesscontrol_title (accesscontrol_title)
		) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=17;");

    $db->Execute("REPLACE INTO " . DB_PREFIX . "provider_accesscontrol (accesscontrol_id, accesscontrol_title, accesscontrol_master, accesscontrol_parent, accesscontrol_filename) VALUES
			(1, 'BOX_HEADING_CATALOG', 1, 0, 'menu-catalog'),
			(2, 'BOX_CATALOG_CATEGORIES_PRODUCTS', 0, 1, 'categories'),
			(3, 'BOX_CATALOG_CATEGORIES_ATTRIBUTES_CONTROLLER', 0, 1, 'attributes_controller'),
			(4, 'BOX_CATALOG_SPECIALS', 0, 1, 'specials'),
			(5, 'BOX_CATALOG_PRODUCTS_PRICE_MANAGER', 0, 1, 'products_price_manager'),
			(6, 'BOX_CATALOG_SALEMAKER', 0, 1, 'salemaker'),
			(7, 'BOX_HEADING_REPORTS', 1, 0, 'menu-reports'),
			(8, 'BOX_REPORTS_SALES_REPORT', 0, 7, 'stats_sales_report'),
			(9, 'BOX_HEADING_TOOLS', 1, 0, 'menu-tools'),
			(10, 'BOX_TOOLS_MY_PROFILE', 0, 9, 'my_profile'),
			(11, 'BOX_TOOLS_IMAGE_HANDLER', 0, 9, 'image_handler'),
			(12, 'BOX_HEADING_GV_ADMIN', 1, 0, 'menu-gvadmin'),
			(13, 'BOX_COUPON_ADMIN', 0, 12, 'coupon_admin'),
			(14, 'BOX_COUPON_RESTRICT', 0, 12, 'coupon_restrict'),
			(15, 'BOX_CATALOG_FEATURED', 0, 1, 'featured'),
			(16, 'BOX_MULTI_CATEGORY', 0, 1, 'products_to_categories'),
			(17, 'BOX_CATALOG_ADVANCED_XSELL_PRODUCTS', 0, 1, 'xsell_advanced'),
			(18, 'BOX_CATALOG_CATEGORIES_ATTRIBUTES_DOWNLOADS_MANAGER', 0, 1, 'downloads_manager'),
			(19, 'BOX_TOOLS_FREEGIFTS', 0, 9, 'freegifts');
			");

    $checkfields = $db->metaColumns(TABLE_SALEMAKER_SALES);
    if (!$checkfields['MANUFACTURERS_ID']->type) {
        $db->Execute("ALTER TABLE " . TABLE_SALEMAKER_SALES . " ADD manufacturers_id int(11) NOT NULL default 0");
    }

    $db->Execute("UPDATE " . TABLE_CONFIGURATION_GROUP . " SET configuration_group_title = 'PrivateArea' WHERE configuration_group_title = 'Custom Zen'");

    $group_id = $db->Execute("SELECT configuration_group_id AS group_id FROM " . TABLE_CONFIGURATION_GROUP . " WHERE configuration_group_title = 'PrivateArea'");
    if ($group_id->RecordCount() == 0) {
        $group_id_query = $db->Execute("SELECT configuration_group_id AS group_id FROM " . TABLE_CONFIGURATION_GROUP . " ORDER BY configuration_group_id DESC");
        $group_id       = $group_id_query->fields['group_id'] + 1;
        $setting        = $db->Execute("INSERT INTO " . TABLE_CONFIGURATION_GROUP . " (configuration_group_id, configuration_group_title, configuration_group_description, sort_order, visible) VALUES ('" . $group_id . "', 'PrivateArea', 'PrivateArea Settings', '" . $group_id . "', '1')");
        $group_id       = $db->insert_ID();
    } else {
        $group_id = $group_id->fields['group_id'];
    }

    $sort_order = 300;
    $configs    = $db->Execute("SELECT * FROM " . TABLE_CONFIGURATION . " WHERE configuration_group_id = '" . $group_id . "'");

    $PROVIDER_PANEL_STATUS                       = 'Enabled';
    $PROVIDER_PANEL_MSG                          = '';
    $DEFAULT_PRODUCTS_QTY                        = 1;
    $PRODUCTS_ON_QUEUE                           = 'False';
    $DEFAULT_PRODUCTS_QTY_MAX                    = 1;
    $CS_UPDATE_ADDED_DATE                        = 'False';
    $PRIVATEAREA_PATH                            = 'privatearea';
    $PRIVATEAREA_MIN_ORDER_STATUS_FOR_STATISTICS = '0';
    $SALES_REPORT_CAN_CHOOSE_ORDER_STATUS        = 'True';

    while (!$configs->EOF) {

        if ($configs->fields['configuration_key'] == 'PROVIDER_PANEL_STATUS') {
            $PROVIDER_PANEL_STATUS = $configs->fields['configuration_value'] == 'Disabled' ? 'Disabled' : 'Enabled';
        }

        if ($configs->fields['configuration_key'] == 'PROVIDER_PANEL_MSG') {
            $PROVIDER_PANEL_MSG = $configs->fields['configuration_value'] != '' ? zen_db_input(addslashes($configs->fields['configuration_value'])) : '';
        }

        if ($configs->fields['configuration_key'] == 'DEFAULT_PRODUCTS_QTY') {
            $DEFAULT_PRODUCTS_QTY = $configs->fields['configuration_value'] > 1 ? $configs->fields['configuration_value'] : 1;
        }

        if ($configs->fields['configuration_key'] == 'PRODUCTS_ON_QUEUE') {
            $PRODUCTS_ON_QUEUE = $configs->fields['configuration_value'] == 'True' ? 'True' : 'False';
        }

        if ($configs->fields['configuration_key'] == 'DEFAULT_PRODUCTS_QTY_MAX') {
            $DEFAULT_PRODUCTS_QTY_MAX = $configs->fields['configuration_value'] == 0 ? 0 : 1;
        }

        if ($configs->fields['configuration_key'] == 'CS_UPDATE_ADDED_DATE') {
            $CS_UPDATE_ADDED_DATE = $configs->fields['configuration_value'] == 'True' ? 'True' : 'False';
        }

        if ($configs->fields['configuration_key'] == 'PRIVATEAREA_PATH') {
            $PRIVATEAREA_PATH = $configs->fields['configuration_value'] != '' ? $configs->fields['configuration_value'] : '';
        }

        if ($configs->fields['configuration_key'] == 'PRIVATEAREA_MIN_ORDER_STATUS_FOR_STATISTICS') {
            $PRIVATEAREA_MIN_ORDER_STATUS_FOR_STATISTICS = $configs->fields['configuration_value'] != '' ? $configs->fields['configuration_value'] : '0';
        }

        if ($configs->fields['configuration_key'] == 'SALES_REPORT_CAN_CHANGE_ORDER_STATUS') {
            $SALES_REPORT_CAN_CHOOSE_ORDER_STATUS = $configs->fields['configuration_value'] != '' ? $configs->fields['configuration_value'] : 'True';
        }

        if ($configs->fields['configuration_key'] == 'PRIVATEAREA_SECRET_KEY') {
            $PRIVATEAREA_SECRET_KEY = $configs->fields['configuration_value'] != '' ? $configs->fields['configuration_value'] : time();
        }

        $configs->MoveNext();
    }

    $db->Execute("REPLACE INTO " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Provider Panel Status', 'PROVIDER_PANEL_STATUS', '" . $PROVIDER_PANEL_STATUS . "', 'Set if the Provider Panel should be enabled or disabled.', '" . $group_id . "', " . $sort_order++ . ", 'zen_cfg_select_option(array(\'Enabled\', \'Disabled\'), ', now())");
    $db->Execute("REPLACE INTO " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Provider Panel Maintenance Message', 'PROVIDER_PANEL_MSG', '" . $PROVIDER_PANEL_MSG . "', 'Enter your personal maintenance message when Provier Panel is disabled. You can use HTML. Leave blank to use the default.', '" . $group_id . "', " . $sort_order++ . ", 'zen_cfg_textarea(', now())");
    $db->Execute("REPLACE INTO " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Default Products Quantity', 'DEFAULT_PRODUCTS_QTY', '" . $DEFAULT_PRODUCTS_QTY . "', 'Set the default product quantity for New products.', '" . $group_id . "', " . $sort_order++ . ", NULL, now())");
    $db->Execute("REPLACE INTO " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Put Products on Queue', 'PRODUCTS_ON_QUEUE', '" . $PRODUCTS_ON_QUEUE . "', 'Set if the products should be wait on a queue till the store admin release it.', '" . $group_id . "', " . $sort_order++ . ", 'zen_cfg_select_option(array(\'True\', \'False\'), ',  now())");
    $db->Execute("REPLACE INTO " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Product Qty Maximum', 'DEFAULT_PRODUCTS_QTY_MAX', '" . $DEFAULT_PRODUCTS_QTY_MAX . "', 'Set the maximum product quantity for New products.<br />0 = Unlimited<br />1 = No Qty Boxes', '" . $group_id . "', " . $sort_order++ . ", 'zen_cfg_select_option(array(\'0\', \'1\'), ',  now())");
    $db->Execute("REPLACE INTO " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Update Products Date Added', 'CS_UPDATE_ADDED_DATE', '" . $CS_UPDATE_ADDED_DATE . "', 'When you release products that are on queue, you may want to update the added date.', '" . $group_id . "', " . $sort_order++ . ", 'zen_cfg_select_option(array(\'True\', \'False\'), ',  now())");
    $db->Execute("REPLACE INTO " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('PrivateArea Dir Name', 'PRIVATEAREA_PATH', '" . $PRIVATEAREA_PATH . "', 'If you changed the PrivateArea dir name, please, set the new name here. e.g. designer-admin, designer-area.', '" . $group_id . "', " . $sort_order++ . ", NULL, now())");
    $db->Execute("REPLACE INTO " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) VALUES ('Minimum Order Status to Count in the Statistics', 'PRIVATEAREA_MIN_ORDER_STATUS_FOR_STATISTICS', '" . $PRIVATEAREA_MIN_ORDER_STATUS_FOR_STATISTICS . "', 'Change this setting to the minimum order status to count the statistics on the home page of the PrivateArea.', '" . $group_id . "', " . $sort_order++ . ", 'zen_get_order_status_name', 'zen_cfg_pull_down_order_statuses(', now())");
    $db->Execute("REPLACE INTO " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Sales Report - Can Choose Order Status', 'SALES_REPORT_CAN_CHOOSE_ORDER_STATUS', '" . $SALES_REPORT_CAN_CHOOSE_ORDER_STATUS . "', 'Setting this to True, will allow the PrivateArea users to choose the Order Status.', '" . $group_id . "', " . $sort_order++ . ", 'zen_cfg_select_option(array(\'True\', \'False\'), ',  now())");
    $db->Execute("REPLACE INTO " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Secret Key', 'PRIVATEAREA_SECRET_KEY', '" . $PRIVATEAREA_SECRET_KEY . "', 'Used to display the license manager on PrivateArea side.', '" . $group_id . "', " . $sort_order++ . ", NULL,  now())");
}

$checkfields = $db->metaColumns(TABLE_MANUFACTURERS_INFO);

if (!$checkfields['MANUFACTURERS_TOUS']->type) {
	zen_redirect(zen_href_link(FILENAME_PROVIDERS, 'action=updateDB'));
}

$login_link  = HTTP_SERVER . DIR_WS_CATALOG . PRIVATEAREA_PATH;
$checkfields = $db->metaColumns(TABLE_MANUFACTURERS);

if (!$checkfields['MANUFACTURERS_DIR']->type) {
    $db->Execute("ALTER TABLE " . TABLE_MANUFACTURERS . " ADD manufacturers_dir varchar(50) NOT NULL default ''");
}

if (!$checkfields['MANUFACTURERS_IMAGE_DIR']->type) {
    $db->Execute("ALTER TABLE " . TABLE_MANUFACTURERS . " ADD manufacturers_image_dir varchar(50) NOT NULL default ''");
}

$action = (isset($_GET['action']) ? $_GET['action'] : '');
if (zen_not_null($action)) {
    switch ($action) {
        case 'insert':
        case 'save':
            if (isset($_GET['pID'])) {
                $manufacturers_id = zen_db_prepare_input($_GET['pID']);
            }

            $manufacturers_name                   = zen_db_prepare_input($_POST['manufacturers_name']);
            $manufacturers_email                  = zen_db_prepare_input($_POST['manufacturers_email']);
            $manufacturers_paypal_email           = zen_db_prepare_input($_POST['manufacturers_paypal_email']);
            $manufacturers_paypal_currency        = zen_db_prepare_input($_POST['manufacturers_paypal_currency']);
            $manufacturers_login                  = zen_db_prepare_input(str_replace(' ', '', $_POST['manufacturers_login']));
            $manufacturers_password               = zen_db_prepare_input($_POST['manufacturers_password']);
            $manufacturers_password_confirmation  = zen_db_prepare_input($_POST['manufacturers_password_confirmation']);
            $manufacturers_download_dir           = zen_db_prepare_input($_POST['manufacturers_download_dir']);
            $manufacturers_download_dir_manual    = zen_privatearea_sanitize(zen_db_prepare_input($_POST['manufacturers_download_dir_manual']));
            $manufacturers_image_dir              = zen_db_prepare_input($_POST['manufacturers_image_dir']);
            $manufacturers_image_dir_manual       = zen_privatearea_sanitize(zen_db_prepare_input($_POST['manufacturers_image_dir_manual']));
            $manufacturers_commission             = zen_db_prepare_input($_POST['manufacturers_commission']);
            $manufacturers_discount_payment_fee   = (int) $_POST['manufacturers_discount_payment_fee'];
            $_POST['manufacturers_accesscontrol'] = serialize($_POST['manufacturers_accesscontrol']);
            $manufacturers_accesscontrol          = zen_db_prepare_input($_POST['manufacturers_accesscontrol']);
            $manufacturers_commission_group_based = zen_db_prepare_input($_POST['manufacturers_commission_group_based']);
            $category_id                          = zen_db_prepare_input($_POST['category_id']);
            $metatags                             = (int) $_POST['manufacturers_metatags'];
            $metatags_chars                       = (int) $_POST['manufacturers_metatags_chars'];

            //check if this name already exist
            $sql = "SELECT manufacturers_name FROM " . TABLE_MANUFACTURERS . " WHERE manufacturers_name = '" . zen_db_input($manufacturers_name) . "'";
            if ($action == 'save') {
                $sql = "SELECT manufacturers_name FROM " . TABLE_MANUFACTURERS . " WHERE manufacturers_name = '" . zen_db_input($manufacturers_name) . "' AND manufacturers_id <> '" . $manufacturers_id . "'";
            }
            $check = $db->Execute($sql);
            if ($check->RecordCount() > 0) {
                $messageStack->add(sprintf(ERROR_PROVIDER_NAME_EXIST, $manufacturers_name), 'error');
                $error = true;
            }

            //check if this email already exist
            $sql = "SELECT manufacturers_email FROM " . TABLE_MANUFACTURERS . " WHERE manufacturers_email = '" . zen_db_input($manufacturers_email) . "'";
            if ($action == 'save') {
                $sql = "SELECT manufacturers_email FROM " . TABLE_MANUFACTURERS . " WHERE manufacturers_email = '" . zen_db_input($manufacturers_email) . "' AND manufacturers_id <> '" . $manufacturers_id . "'";
            }
            $check = $db->Execute($sql);
            if ($check->RecordCount() > 0) {
                $messageStack->add(sprintf(ERROR_PROVIDER_EMAIL_EXIST, $manufacturers_email), 'error');
                $error = true;
            }

            //check if this login already exist
            $sql = "SELECT manufacturers_login FROM " . TABLE_MANUFACTURERS . " WHERE manufacturers_login = '" . zen_db_input($manufacturers_login) . "'";
            if ($action == 'save') {
                $sql = "SELECT manufacturers_login FROM " . TABLE_MANUFACTURERS . " WHERE manufacturers_login = '" . zen_db_input($manufacturers_login) . "' AND manufacturers_id <> '" . $manufacturers_id . "'";
            }
            $check = $db->Execute($sql);
            if ($check->RecordCount() > 0 and !empty($manufacturers_login)) {
                $messageStack->add(sprintf(ERROR_PROVIDER_LOGIN_EXIST, $manufacturers_login), 'error');
                $error = true;
            }

            //check if provider name has not left blank
            if (empty($manufacturers_name)) {
                $messageStack->add(ERROR_PROVIDER_NAME_EMPTY, 'error');
                $error = true;
            }

            //check if the login name has not left blank
            if (empty($manufacturers_login)) {
                $messageStack->add(ERROR_PROVIDER_LOGIN_EMPTY, 'error');
                $error = true;
            }

            //check if the email has not left blank
            if (empty($manufacturers_email)) {
                $messageStack->add(ERROR_PROVIDER_EMAIL_EMPTY, 'error');
                $error = true;
            }

            //check if the manufacturers_password is not different from the confirmation and if it have the minimum length
            if (!empty($manufacturers_password) or !empty($manufacturers_password_confirmation)) {
                if (strlen($manufacturers_password) < ENTRY_PASSWORD_MIN_LENGTH) {
                    $messageStack->add(ENTRY_PASSWORD_NEW_ERROR, 'error');
                    $error = true;
                } elseif ($manufacturers_password != $manufacturers_password_confirmation) {
                    $messageStack->add(ENTRY_PASSWORD_NEW_ERROR_NOT_MATCHING, 'error');
                    $error = true;
                }
            }

            //check if this is the first insert, if yes, don't let the password field blank
            if (empty($manufacturers_password) and $action == 'insert') {
                $messageStack->add(ERROR_PROVIDER_PASSWORD_EMPTY, 'error');
                $error = true;
            }

            if (!empty($manufacturers_download_dir_manual)) {
                $manufacturers_download_dir = $manufacturers_download_dir_manual;
                $dir                        = DIR_FS_DOWNLOAD . $manufacturers_download_dir;
                if (!@mkdir($dir, 0777)) {
                    $messageStack->add(sprintf(ERROR_PROVIDER_CREATING_DIR, $dir), 'error');
                    $error = true;
                }
            }

            if (!empty($manufacturers_image_dir_manual)) {
                $manufacturers_image_dir = $manufacturers_image_dir_manual;
                $dir                     = DIR_FS_CATALOG_IMAGES . $manufacturers_image_dir;
                if (!@mkdir($dir, 0777)) {
                    $messageStack->add(sprintf(ERROR_PROVIDER_CREATING_DIR, $dir), 'error');
                    $error = true;
                }
            }

            if ($error) {
                $action = $_GET['previous'];
            }

            if (!$error) {
                if ($action == 'insert') {
                    $sql_data_array = array(
                        'manufacturers_login'                  => $manufacturers_login,
                        'manufacturers_name'                   => $manufacturers_name,
                        'manufacturers_email'                  => $manufacturers_email,
                        'manufacturers_paypal_email'           => $manufacturers_paypal_email,
                        'manufacturers_paypal_currency'        => $manufacturers_paypal_currency,
                        'manufacturers_password'               => privatearea_encrypt_password($manufacturers_password),
                        'manufacturers_commission'             => $manufacturers_commission,
                        'manufacturers_discount_payment_fee'   => $manufacturers_discount_payment_fee,
                        'manufacturers_dir'                    => $manufacturers_download_dir,
                        'manufacturers_image_dir'              => $manufacturers_image_dir,
                        'manufacturers_accesscontrol'          => $manufacturers_accesscontrol,
                        'manufacturers_commission_group_based' => (int) $manufacturers_commission_group_based,
                        'category_id'                          => (int) $category_id,
                        'metatags'                             => (int) $metatags,
                        'metatags_chars'                       => (int) $metatags_chars,
                        'date_added'                           => 'now()',
                    );

                    zen_db_perform(TABLE_MANUFACTURERS, $sql_data_array);
                    $manufacturers_id = zen_db_insert_id();
                } elseif ($action == 'save') {
                    $sql_data_array = array();

                    if ($manufacturers_login) {
                        $sql_data_array['manufacturers_login'] = $manufacturers_login;
                    }

                    if ($manufacturers_name) {
                        $sql_data_array['manufacturers_name'] = $manufacturers_name;
                    }

                    if ($manufacturers_email) {
                        $sql_data_array['manufacturers_email'] = $manufacturers_email;
                    }

                    $sql_data_array['manufacturers_paypal_email']    = $manufacturers_paypal_email;
                    $sql_data_array['manufacturers_paypal_currency'] = $manufacturers_paypal_currency;
                    if ($manufacturers_password) {
                        $sql_data_array['manufacturers_password'] = privatearea_encrypt_password($manufacturers_password);
                    }

                    $sql_data_array['manufacturers_commission']             = $manufacturers_commission;
                    $sql_data_array['manufacturers_discount_payment_fee']   = $manufacturers_discount_payment_fee;
                    $sql_data_array['manufacturers_dir']                    = $manufacturers_download_dir;
                    $sql_data_array['manufacturers_image_dir']              = $manufacturers_image_dir;
                    $sql_data_array['manufacturers_accesscontrol']          = $manufacturers_accesscontrol;
                    $sql_data_array['manufacturers_commission_group_based'] = (int) $manufacturers_commission_group_based;
                    if ($category_id) {
                        $sql_data_array['category_id'] = $category_id;
                    }

                    $sql_data_array['metatags']       = $metatags;
                    $sql_data_array['metatags_chars'] = (int) $metatags_chars;

                    zen_db_perform(TABLE_MANUFACTURERS, $sql_data_array, 'update', "manufacturers_id = '" . (int) $manufacturers_id . "'");
                }

                if ($_POST['send_login']) {
                    $subject                        = TEXT_MAIL_LOGIN_DATA_SBJ;
                    $message                        = sprintf(TEXT_MAIL_LOGIN_DATA_MSG, $manufacturers_name, $manufacturers_login, $manufacturers_password, $login_link);
                    $html_msg['EMAIL_MESSAGE_HTML'] = nl2br($message);
                    zen_mail($manufacturers_name, $manufacturers_email, $subject, $message, STORE_NAME, EMAIL_FROM, $html_msg, 'debug');
                }

                if ($_POST['manufacturers_image_manual'] != '') {
                    // add image manually
                    $manufacturers_image_name = $_POST['img_dir'] . $_POST['manufacturers_image_manual'];
                    $db->Execute("update " . TABLE_MANUFACTURERS . "
						  set manufacturers_image = '" . $manufacturers_image_name . "'
						  where manufacturers_id = '" . (int) $manufacturers_id . "'");
                } else {
                    $manufacturers_image = new upload('manufacturers_image');
                    $manufacturers_image->set_destination(DIR_FS_CATALOG_IMAGES . $_POST['img_dir']);
                    if ($manufacturers_image->parse() && $manufacturers_image->save()) {
                        // remove image from database if none
                        if ($manufacturers_image->filename != 'none') {
                            $db->Execute("update " . TABLE_MANUFACTURERS . "
							  set manufacturers_image = '" . $_POST['img_dir'] . $manufacturers_image->filename . "'
							  where manufacturers_id = '" . (int) $manufacturers_id . "'");
                        } else {
                            $db->Execute("update " . TABLE_MANUFACTURERS . "
							  set manufacturers_image = ''
							  where manufacturers_id = '" . (int) $manufacturers_id . "'");
                        }
                    }
                }

                $languages = zen_get_languages();
                for ($i = 0, $n = sizeof($languages); $i < $n; $i++) {
                    $language_id        = $languages[$i]['id'];
                    $manufacturers_url  = $_POST['manufacturers_url'][$language_id];
                    $manufacturers_tous = $_POST['manufacturers_tous'][$language_id];

                    $db->Execute("REPLACE INTO " . TABLE_MANUFACTURERS_INFO . "
								(
									manufacturers_id,
									languages_id,
									manufacturers_url,
									manufacturers_tous
								) VALUES (
									'" . (int) $manufacturers_id . "',
									'" . (int) $language_id . "',
									'" . zen_db_input($manufacturers_url) . "',
									'" . zen_db_input($manufacturers_tous) . "'
								)
				  ");
                }

                $messageStack->add_session(sprintf(PROVIDER_SAVED_SUCCESSFULLY, $manufacturers_name), 'success');
                zen_redirect(zen_href_link(FILENAME_PROVIDERS, (isset($_GET['page']) ? 'page=' . $_GET['page'] . '&' : '') . 'pID=' . $manufacturers_id));
            }
            break;
        case 'deleteconfirm':
            $manufacturers_id = zen_db_prepare_input($_GET['pID']);

            if (isset($_POST['delete_image']) && ($_POST['delete_image'] == 'on')) {
                $manufacturer = $db->Execute("select manufacturers_image
										from " . TABLE_MANUFACTURERS . "
										where manufacturers_id = '" . (int) $manufacturers_id . "'");

                $image_location = DIR_FS_CATALOG_IMAGES . $manufacturer->fields['manufacturers_image'];

                if (file_exists($image_location) && !is_dir($image_location)) {
                    @unlink($image_location);
                }

            }

            $db->Execute("delete from " . TABLE_MANUFACTURERS . "
					  where manufacturers_id = '" . (int) $manufacturers_id . "'");
            $db->Execute("delete from " . TABLE_MANUFACTURERS_INFO . "
					  where manufacturers_id = '" . (int) $manufacturers_id . "'");
            if (isset($_POST['delete_products']) && ($_POST['delete_products'] == 'on')) {
                $products = $db->Execute("select products_id
									from " . TABLE_PRODUCTS . "
									where manufacturers_id = '" . (int) $manufacturers_id . "'");

                while (!$products->EOF) {
                    zen_remove_product($products->fields['products_id']);
                    $products->MoveNext();
                }
            } else {
                $db->Execute("update " . TABLE_PRODUCTS . "
						set manufacturers_id = ''
						where manufacturers_id = '" . (int) $manufacturers_id . "'");
            }

            zen_redirect(zen_href_link(FILENAME_PROVIDERS, 'page=' . $_GET['page']));
            break;
        case 'set_editor':
            zen_redirect(zen_href_link(FILENAME_PROVIDERS, 'page=' . $_GET['page']));
            break;
    }

    $downloaddirs   = array();
    $downloaddirs[] = array('id' => '', 'text' => TEXT_NONE);

    function get_download_dirs($path)
    {
        global $downloaddirs;

        $downloadir = scandir($path);
        foreach ($downloadir as $dir) {
            if ($dir == '.' || $dir == '..') {
                continue;
            }

            $pathdir = $path . $dir;
            if (is_dir($pathdir)) {
                $pathdir        = str_replace(DIR_FS_DOWNLOAD, '', $pathdir);
                $downloaddirs[] = array('id' => $pathdir, 'text' => $pathdir);
                get_download_dirs(DIR_FS_DOWNLOAD . $pathdir . '/');
            }
        }
    }

    get_download_dirs(DIR_FS_DOWNLOAD);

    $imagedir    = scandir(DIR_FS_CATALOG_IMAGES);
    $imagedirs   = array();
    $imagedirs[] = array('id' => '', 'text' => TEXT_NONE);
    foreach ($imagedir as $dir) {
        if ($dir == '.' || $dir == '..') {
            continue;
        }

        if (is_dir(DIR_FS_CATALOG_IMAGES . $dir)) {
            $imagedirs[] = array('id' => $dir, 'text' => $dir);
        }

    }
}
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="X-UA-Compatible" content="IE=Edge,chrome=1">
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo HEADING_TITLE_PROVIDERS; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<link rel="stylesheet" type="text/css" href="includes/cssjsmenuhover.css" media="all" id="hoverJS">
<script language="javascript" src="includes/menu.js"></script>
<script language="javascript" src="includes/general.js"></script>
<script language="javascript" src="//cdnjs.cloudflare.com/ajax/libs/ckeditor/4.2/ckeditor.js"></script>
<script type="text/javascript">
  <!--
  function init()
  {
	cssjsmenu('navbar');
	if (document.getElementById)
	{
	  var kill = document.getElementById('hoverJS');
	  kill.disabled = true;
	}
	if (typeof _editor_url == "string") {
		var config = new HTMLArea.Config();
		config.width = '600px';
		config.height = '300px';

		HTMLArea.replaceAll(config);
	}
  }
  // -->
</script>
<style>

ul.accesscontrol {
	list-style-type: none;
	padding: 0;
	margin: 0 0 5px 0;
}
ul.accesscontrol ul, ul.accesscontrol ul ul, ul.accesscontrol ul ul ul {
	list-style-type: none;
}
ul.accesscontrol li {
	background: none;
	color: #444;
	font-size: 11px;
	padding: 0;
}
ul.accesscontrol li label {
	margin-left: 5px;
}
ul.accesscontrol li ul {
	margin: 0 0 10px 15px;
	padding: 0;
}
ul.accesscontrol li ul ul {
	margin: 7px 0 5px 15px;
	padding: 0;
}
</style>
<?php if ($editor_handler != '') {
    include $editor_handler;
}
?>
</head>
<body onLoad="init()">
<!-- header //-->
<?php require DIR_WS_INCLUDES . 'header.php';?>
<!-- header_eof //-->

<!-- body //-->
<table border="0" width="100%" cellspacing="2" cellpadding="2">
  <tr>
<!-- body_text //-->
	<td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
	  <tr>
		<td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
		  <tr>
			<td class="pageHeading"><?php echo HEADING_TITLE_PROVIDERS; ?></td>
			<td class="pageHeading" align="right"><?php
// toggle switch for editor
if ($_GET['action'] == '') {
    echo TEXT_EDITOR_INFO . zen_draw_form('set_editor_form', FILENAME_PROVIDERS, '', 'get') . '&nbsp;&nbsp;' . zen_draw_pull_down_menu('reset_editor', $editors_pulldown, $current_editor_key, 'onChange="this.form.submit();"') . zen_hide_session_id() .
    zen_draw_hidden_field('page', $_GET['page']) .
    zen_draw_hidden_field('action', 'set_editor') .
        '</form>';
}
echo zen_draw_separator('pixel_trans.gif', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT);?></td>
		  </tr>
		</table></td>
	  </tr>
	  <tr>
		<td><table border="0" width="100%" cellspacing="0" cellpadding="0">
		  <tr>
			<td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
			  <tr class="dataTableHeadingRow">
				<td class="dataTableHeadingContent"><?php echo TABLE_HEADING_PROVIDERS; ?></td>
				<td class="dataTableHeadingContent"><?php echo TABLE_HEADING_PROVIDERS_COMMISSION; ?></td>
				<td class="dataTableHeadingContent"><?php echo TABLE_HEADING_PROVIDERS_LAST_LOGIN; ?></td>
				<td class="dataTableHeadingContent"><?php echo TABLE_HEADING_PROVIDERS_LOGINS; ?></td>
				<td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</td>
			  </tr>
<?php
$manufacturers_query_raw = "select * from " . TABLE_MANUFACTURERS . " m order by m.manufacturers_name";
$manufacturers_split     = new splitPageResults($_GET['page'], 100, $manufacturers_query_raw, $manufacturers_query_numrows);
$providers               = $db->Execute($manufacturers_query_raw);
while (!$providers->EOF) {
    if ((!isset($_GET['pID']) || (isset($_GET['pID']) && ($_GET['pID'] == $providers->fields['manufacturers_id']))) && !isset($pInfo) && (substr($action, 0, 3) != 'new')) {
        $manufacturer_products = $db->Execute("select count(*) as products_count
											 from " . TABLE_PRODUCTS . "
											 where manufacturers_id = '" . (int) $providers->fields['manufacturers_id'] . "'");

        $pInfo_array = array_merge($providers->fields, $manufacturer_products->fields);
        $pInfo       = new objectInfo($pInfo_array);

        foreach ($pInfo as $field => $value) {
            if (isset($_POST[$field])) {
                $pInfo->$field = $_POST[$field];
            }

        }
    }

    if (isset($pInfo) && is_object($pInfo) && ($providers->fields['manufacturers_id'] == $pInfo->manufacturers_id)) {
        echo '              <tr id="defaultSelected" class="dataTableRowSelected" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . zen_href_link(FILENAME_PROVIDERS, 'page=' . $_GET['page'] . '&pID=' . $providers->fields['manufacturers_id'] . '&action=edit') . '\'">' . "\n";
    } else {
        echo '              <tr class="dataTableRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . zen_href_link(FILENAME_PROVIDERS, 'page=' . $_GET['page'] . '&pID=' . $providers->fields['manufacturers_id'] . '&action=edit') . '\'">' . "\n";
    }
    ?>
				<td class="dataTableContent"><?php echo $providers->fields['manufacturers_name']; ?></td>
				<td class="dataTableContent"><?php echo $providers->fields['manufacturers_commission']; ?>%</td>
				<td class="dataTableContent"><?php echo ($providers->fields['manufacturers_last_login'] == '0001-01-01 00:00:00' ? TEXT_NEVER : $providers->fields['manufacturers_last_login']); ?></td>
				<td class="dataTableContent"><?php echo ($providers->fields['manufacturers_login_times'] == '0' ? TEXT_NOLOGIN : $providers->fields['manufacturers_login_times']); ?></td>
				<td class="dataTableContent" align="right">
				  <?php echo '<a href="' . zen_href_link(FILENAME_PROVIDERS, (isset($_GET['page']) ? 'page=' . $_GET['page'] . '&' : '') . 'pID=' . $providers->fields['manufacturers_id'] . '&action=edit') . '">' . zen_image(DIR_WS_IMAGES . 'icon_edit.gif', ICON_EDIT) . '</a>'; ?>
				  <?php echo '<a href="' . zen_href_link(FILENAME_PROVIDERS, (isset($_GET['page']) ? 'page=' . $_GET['page'] . '&' : '') . 'pID=' . $providers->fields['manufacturers_id'] . '&action=delete') . '">' . zen_image(DIR_WS_IMAGES . 'icon_delete.gif', ICON_DELETE) . '</a>'; ?>
				  <?php if (isset($pInfo) && is_object($pInfo) && ($providers->fields['manufacturers_id'] == $pInfo->manufacturers_id)) {echo zen_image(DIR_WS_IMAGES . 'icon_arrow_right.gif', '');} else {echo '<a href="' . zen_href_link(FILENAME_PROVIDERS, (isset($_GET['page']) ? 'page=' . $_GET['page'] . '&' : '') . 'pID=' . $providers->fields['manufacturers_id']) . '">' . zen_image(DIR_WS_IMAGES . 'icon_info.gif', IMAGE_ICON_INFO) . '</a>';}?>
				</td>
			  </tr>
<?php
$providers->MoveNext();
}
?>
			  <tr>
				<td colspan="2"><table border="0" width="100%" cellspacing="0" cellpadding="2">
				  <tr>
					<td class="smallText" valign="top"><?php echo $manufacturers_split->display_count($manufacturers_query_numrows, 100, $_GET['page'], TEXT_DISPLAY_NUMBER_OF_PROVIDERS); ?></td>
					<td class="smallText" align="right"><?php echo $manufacturers_split->display_links($manufacturers_query_numrows, 100, MAX_DISPLAY_PAGE_LINKS, $_GET['page']); ?></td>
				  </tr>
				</table></td>
			  </tr>
<?php
if (empty($action) and !$manufacturer_id) {
    ?>
			  <tr>
				<td align="right" colspan="2" class="smallText"><?php echo '<a href="' . zen_href_link(FILENAME_PROVIDERS, 'page=' . $_GET['page'] . '&action=new') . '">' . zen_image_button('button_insert.gif', IMAGE_INSERT) . '</a>'; ?></td>
			  </tr>
<?php
}
?>
			</table></td>
<?php
$heading  = array();
$contents = array();

$metatags_yes = true;
$metatags_no  = false;

if ($pInfo->metatags == "0") {
    $metatags_yes = false;
    $metatags_no  = true;
}

// generate access control checkboxes
$accesscontrol = $db->Execute("SELECT accesscontrol_id, accesscontrol_title, accesscontrol_filename FROM " . TABLE_PROVIDER_ACCESSCONTROL . " WHERE accesscontrol_master = 1 ORDER BY accesscontrol_title");
$accessgranted = array();

if (isset($_GET['pID'])) {
    $accessgranted = unserialize($pInfo->manufacturers_accesscontrol);
}

while (!$accesscontrol->EOF) {
    $controls .= '
			<li>
				<label><strong>' . constant($accesscontrol->fields['accesscontrol_title']) . '</strong></label>
				<ul>';
    $subcontrol = $db->Execute("SELECT accesscontrol_id, accesscontrol_title, accesscontrol_filename FROM " . TABLE_PROVIDER_ACCESSCONTROL . " WHERE accesscontrol_parent = " . $accesscontrol->fields['accesscontrol_id'] . " ORDER BY accesscontrol_title");
    while (!$subcontrol->EOF) {
        $checked = '';
        if (!$_GET['pID'] || count($accessgranted) <= 0) {
            $checked = 'checked="checked" ';
        }

        if (isset($_GET['pID']) && count($accessgranted) > 0) {
            if (@in_array($subcontrol->fields['accesscontrol_filename'], $accessgranted)) {
                $checked = 'checked="checked" ';
            }
        }
        $controls .= '
			<li>
				<input type="checkbox" id="' . $subcontrol->fields['accesscontrol_id'] . '" name="manufacturers_accesscontrol[]" value="' . $subcontrol->fields['accesscontrol_filename'] . '" ' . $checked . '/>
				<label for="' . $subcontrol->fields['accesscontrol_id'] . '">' . constant($subcontrol->fields['accesscontrol_title']) . '</label>
			</li>';
        $subcontrol->MoveNext();
    }
    $controls .= '
				</ul>
			</li>
			';
    $accesscontrol->MoveNext();
}

$accesscheckbox = '
		<ul id="accesslevel" class="accesscontrol">
			' . $controls . '
		</ul>
		';

if (!isset($currencies) && !is_object($currencies)) {
    require 'includes/classes/currencies.php';
    $currencies = new currencies();
}

if (isset($currencies) && is_object($currencies)) {

    reset($currencies->currencies);
    $currencies_array = array();
    while (list($key, $value) = each($currencies->currencies)) {
        $paypal_currencies[] = array('id' => $key, 'text' => $value['title']);
    }
}

switch ($action) {
    case 'new':
        $heading[] = array('text' => '<b>' . TEXT_HEADING_NEW_PROVIDER . '</b>');

        $contents = array('form' => zen_draw_form('providers', FILENAME_PROVIDERS, 'action=insert&previous=' . (isset($_GET['previous']) ? $_GET['previous'] : $_GET['action']), 'post', 'enctype="multipart/form-data"'));

        $contents[] = array('text' => TEXT_NEW_PROVIDER_INTRO);
        $contents[] = array('text' => '<br />' . TEXT_PROVIDERS_NAME . '<br />' . zen_draw_input_field('manufacturers_name', $_POST['manufacturers_name'], zen_set_field_length(TABLE_MANUFACTURERS, 'manufacturers_name'), true));
        $contents[] = array('text' => '<br />' . TEXT_PROVIDERS_COMMISSION . '<br />' . zen_draw_input_field('manufacturers_commission', $_POST['manufacturers_commission'], zen_set_field_length(TABLE_MANUFACTURERS, 'manufacturers_commission'), true));
        $contents[] = array('text' => '<br />' . zen_draw_checkbox_field('manufacturers_commission_group_based', '1', $_POST[''], '', 'id="commission-group"') . ' <label for="commission-group">' . TEXT_INFO_USE_GROUP_COMMISSION . '</label>');
        $contents[] = array('text' => '<br />' . TEXT_PROVIDERS_DISCOUNT_PAYMENT_FEE . '<br />' . zen_draw_checkbox_field('manufacturers_discount_payment_fee', '1', $_POST[''], '', 'id="discount-payment-fee"') . ' <label for="discount-payment-fee">' . TEXT_YES . '</label>');
        $contents[] = array('text' => '<br />' . TEXT_PROVIDERS_EMAIL . '<br />' . zen_draw_input_field('manufacturers_email', $_POST[''], zen_set_field_length(TABLE_MANUFACTURERS, 'manufacturers_email', $max = 30), true));
        $contents[] = array('text' => '<br />' . TEXT_PROVIDERS_PAYPAL_EMAIL . '<br />' . zen_draw_input_field('manufacturers_paypal_email', $_POST[''], zen_set_field_length(TABLE_MANUFACTURERS, 'manufacturers_paypal_email', $max = 30), true));
        $contents[] = array('text' => '<br />' . TEXT_PROVIDERS_PAYPAL_CURRENCY . '<br />' . zen_draw_pull_down_menu('manufacturers_paypal_currency', $paypal_currencies, $_POST['']));
        $contents[] = array('text' => '<br />' . TEXT_PROVIDERS_LOGIN . '<br />' . zen_draw_input_field('manufacturers_login', $_POST[''], zen_set_field_length(TABLE_MANUFACTURERS, 'manufacturers_login', $max = 30), true));
        $contents[] = array('text' => '<br />' . TEXT_PROVIDERS_PASSWORD . '<br />' . zen_draw_password_field('manufacturers_password', '', true));
        $contents[] = array('text' => '<br />' . TEXT_PROVIDERS_CONFIRM_PASSWORD . '<br />' . zen_draw_password_field('manufacturers_password_confirmation', '', true));
        $contents[] = array('text' => '<br />' . TEXT_PROVIDERS_DOWNLOAD_DIR . '<br />' . zen_draw_pull_down_menu('manufacturers_download_dir', $downloaddirs, $_POST['']));
        $contents[] = array('text' => '<br />' . TEXT_PROVIDERS_SEND_LOGIN . '<br />' . zen_draw_checkbox_field('send_login', '1', $_POST[''], '', 'id="login-details"') . ' <label for="login-details">' . TEXT_PROVIDERS_SEND_LOGIN_DETAILS . '</label>');
        $contents[] = array('text' => '<br />' . TEXT_PROVIDERS_CATEGORY . '<br />' . zen_draw_pull_down_menu('category_id', zen_get_category_tree(), 0, '', true));
        $contents[] = array('text' => '<br />' . TEXT_PROVIDERS_METATAGS_CHARS . '<br />' . zen_draw_input_field('manufacturers_metatags_chars', 300, $_POST['']));
        $contents[] = array('text' => '<br />' . TEXT_PROVIDERS_METATAGS . '<br />' . zen_draw_radio_field('manufacturers_metatags', '1', $_POST[''], '', 'id="metatags_yes"') . '<label for="metatags_yes">' . TEXT_PROVIDERS_YES . '</label>&nbsp;' . zen_draw_radio_field('manufacturers_metatags', '0', $_POST[''], '', 'id="metatags_no"') . '<label for="metatags_no">' . TEXT_PROVIDERS_NO . '</label>');
        $contents[] = array('text' => '<br />' . TEXT_PROVIDERS_ACCESS . '<br />' . $accesscheckbox);
        $contents[] = array('text' => '<br />' . TEXT_PROVIDERS_IMAGE . '<br />' . zen_draw_file_field('manufacturers_image', $_POST['manufacturers_image']));
        $dir        = @dir(DIR_FS_CATALOG_IMAGES);
        $dir_info[] = array('id' => '', 'text' => "Main Directory");
        while ($file = $dir->read()) {
            if (is_dir(DIR_FS_CATALOG_IMAGES . $file) && strtoupper($file) != 'CVS' && $file != "." && $file != "..") {
                $dir_info[] = array('id' => $file . '/', 'text' => $file);
            }
        }
        $dir->close();
        sort($dir_info);
        $default_directory = 'manufacturers/';

        $contents[] = array('text' => '<BR />' . TEXT_PRODUCTS_IMAGE_DIR . zen_draw_pull_down_menu('img_dir', $dir_info, $default_directory));

        $contents[] = array('text' => '<br />' . TEXT_PROVIDERS_IMAGE_MANUAL . '&nbsp;' . zen_draw_input_field('manufacturers_image_manual'));

        $manufacturer_inputs_string = '';
        $languages                  = zen_get_languages();
        for ($i = 0, $n = sizeof($languages); $i < $n; $i++) {
            $manufacturer_inputs_string .= '<br />' . zen_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']) . '&nbsp;' . zen_draw_input_field('manufacturers_url[' . $languages[$i]['id'] . ']', '', zen_set_field_length(TABLE_MANUFACTURERS_INFO, 'manufacturers_url'));
        }

        $contents[]        = array('text' => '<br />' . TEXT_PROVIDERS_URL . $manufacturer_inputs_string);
        $manufacturer_tous = '';
        for ($i = 0, $n = sizeof($languages); $i < $n; $i++) {
            $manufacturer_tous .= '<br />' . zen_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']) . '&nbsp;';
            $manufacturer_tous .= zen_draw_textarea_field('manufacturers_tous[' . $languages[$i]['id'] . ']', 'soft', '100%', '20', zen_get_manufacturers_tous($providers->fields['manufacturers_id'], $languages[$i]['id']));
        }

        $contents[] = array('text' => '<br />' . TEXT_PROVIDERS_TOUS . $manufacturer_tous);
        $contents[] = array('align' => 'center', 'text' => '<br />' . zen_image_submit('button_save.gif', IMAGE_SAVE) . ' <a href="' . zen_href_link(FILENAME_PROVIDERS, 'page=' . $_GET['page'] . '&pID=' . $_GET['pID']) . '">' . zen_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
        break;
    case 'edit':
        $heading[] = array('text' => '<b>' . TEXT_HEADING_EDIT_PROVIDER . '</b>');

        $payment_fee = ($pInfo->manufacturers_discount_payment_fee == 1 ? true : false);

        if (!$pInfo->manufacturers_login) {
            $login_details = true;
        }

        $contents   = array('form' => zen_draw_form('providers', FILENAME_PROVIDERS, 'page=' . $_GET['page'] . '&pID=' . $pInfo->manufacturers_id . '&action=save&previous=' . (isset($_GET['previous']) ? $_GET['previous'] : $_GET['action']), 'post', 'enctype="multipart/form-data"'));
        $contents[] = array('text' => TEXT_EDIT_PROVIDER_INTRO);
        $contents[] = array('text' => '<br /><strong>' . TEXT_PROVIDERS_NAME . '</strong><br />' . zen_draw_input_field('manufacturers_name', $pInfo->manufacturers_name, zen_set_field_length(TABLE_MANUFACTURERS, 'manufacturers_name'), true));
        $contents[] = array('text' => '<br /><strong>' . TEXT_PROVIDERS_COMMISSION . '</strong><br />' . zen_draw_input_field('manufacturers_commission', $pInfo->manufacturers_commission, zen_set_field_length(TABLE_MANUFACTURERS, 'manufacturers_commission', $max = 30), true));
        $contents[] = array('text' => '<br /><strong>' . zen_draw_checkbox_field('manufacturers_commission_group_based', '1', ($pInfo->manufacturers_commission_group_based ? true : false), '', 'id="commission-group"') . ' <label for="commission-group">' . TEXT_INFO_USE_GROUP_COMMISSION . '</label>');
        $contents[] = array('text' => '<br /><strong>' . TEXT_PROVIDERS_DISCOUNT_PAYMENT_FEE . '</strong><br />' . zen_draw_checkbox_field('manufacturers_discount_payment_fee', '1', $payment_fee, '', 'id="discount-payment-fee"') . ' <label for="discount-payment-fee">' . TEXT_YES . '</label>');
        $contents[] = array('text' => '<br /><strong>' . TEXT_PROVIDERS_EMAIL . '</strong><br />' . zen_draw_input_field('manufacturers_email', $pInfo->manufacturers_email, zen_set_field_length(TABLE_MANUFACTURERS, 'manufacturers_email', $max = 30), true));
        $contents[] = array('text' => '<br /><strong>' . TEXT_PROVIDERS_PAYPAL_EMAIL . '</strong><br />' . zen_draw_input_field('manufacturers_paypal_email', $pInfo->manufacturers_paypal_email, zen_set_field_length(TABLE_MANUFACTURERS, 'manufacturers_paypal_email', $max = 30), true));
        $contents[] = array('text' => '<br /><strong>' . TEXT_PROVIDERS_PAYPAL_CURRENCY . '</strong><br />' . zen_draw_pull_down_menu('manufacturers_paypal_currency', $paypal_currencies, $pInfo->manufacturers_paypal_currency));
        $contents[] = array('text' => '<br /><strong>' . TEXT_PROVIDERS_LOGIN . '</strong><br />' . zen_draw_input_field('manufacturers_login', $pInfo->manufacturers_login, zen_set_field_length(TABLE_MANUFACTURERS, 'manufacturers_login', $max = 30), true));
        $contents[] = array('text' => '<br /><strong>' . TEXT_PROVIDERS_PASSWORD . '</strong><br />' . zen_draw_password_field('manufacturers_password', '', true));
        $contents[] = array('text' => '<br /><strong>' . TEXT_PROVIDERS_CONFIRM_PASSWORD . '</strong><br />' . zen_draw_password_field('manufacturers_password_confirmation', '', true));
        $contents[] = array('text' => '<br /><strong>' . TEXT_PROVIDERS_DOWNLOAD_DIR . '</strong><br />' . zen_draw_pull_down_menu('manufacturers_download_dir', $downloaddirs, $pInfo->manufacturers_dir));
        $contents[] = array('text' => '<br /><strong>' . TEXT_PROVIDERS_DOWNLOAD_DIR_MANUAL . '</strong><br />' . zen_draw_input_field('manufacturers_download_dir_manual', $_POST['manufacturers_download_dir_manual']));
        $contents[] = array('text' => '<br /><strong>' . TEXT_PROVIDERS_IMAGE_DIR . '</strong><br />' . zen_draw_pull_down_menu('manufacturers_image_dir', $imagedirs, $pInfo->manufacturers_image_dir));
        $contents[] = array('text' => '<br /><strong>' . TEXT_PROVIDERS_IMAGE_DIR_MANUAL . '</strong><br />' . zen_draw_input_field('manufacturers_image_dir_manual', $_POST['manufacturers_image_dir_manual']));
        $contents[] = array('text' => '<br /><strong>' . TEXT_PROVIDERS_SEND_LOGIN . '</strong><br />' . zen_draw_checkbox_field('send_login', '1', $login_details, '', 'id="login-details"') . ' <label for="login-details">' . TEXT_PROVIDERS_SEND_LOGIN_DETAILS . '</label>');
        $contents[] = array('text' => '<br /><strong>' . TEXT_PROVIDERS_CATEGORY . '</strong><br />' . zen_draw_pull_down_menu('category_id', zen_get_category_tree(), $pInfo->category_id, '', true));
        $contents[] = array('text' => '<br /><strong>' . TEXT_PROVIDERS_METATAGS_CHARS . '</strong><br />' . zen_draw_input_field('manufacturers_metatags_chars', $pInfo->metatags_chars));
        $contents[] = array('text' => '<br /><strong>' . TEXT_PROVIDERS_METATAGS . '</strong><br />' . zen_draw_radio_field('manufacturers_metatags', '1', $metatags_yes, '', 'id="metatags_yes"') . '<label for="metatags_yes">' . TEXT_PROVIDERS_YES . '</label>&nbsp;' . zen_draw_radio_field('manufacturers_metatags', '0', $metatags_no, '', 'id="metatags_no"') . '<label for="metatags_no">' . TEXT_PROVIDERS_NO . '</label>');
        $contents[] = array('text' => '<br /><strong>' . TEXT_PROVIDERS_ACCESS . '</strong><br />' . $accesscheckbox);
        $contents[] = array('text' => '<br /><strong>' . TEXT_PROVIDERS_IMAGE . '</strong><br />' . zen_draw_file_field('manufacturers_image') . '<br />' . $pInfo->manufacturers_image);
        $dir        = @dir(DIR_FS_CATALOG_IMAGES);
        $dir_info[] = array('id' => '', 'text' => "Main Directory");
        while ($file = $dir->read()) {
            if (is_dir(DIR_FS_CATALOG_IMAGES . $file) && strtoupper($file) != 'CVS' && $file != "." && $file != "..") {
                $dir_info[] = array('id' => $file . '/', 'text' => $file);
            }
        }
        $dir->close();
        sort($dir_info);
        $default_directory = substr($pInfo->manufacturers_image, 0, strpos($pInfo->manufacturers_image, '/') + 1);

        $contents[] = array('text' => '<br /><strong>' . TEXT_PRODUCTS_IMAGE_DIR . '</strong>' . zen_draw_pull_down_menu('img_dir', $dir_info, $default_directory));

        $contents[] = array('text' => '<br /><strong>' . TEXT_PROVIDERS_IMAGE_MANUAL . '</strong>&nbsp;' . zen_draw_input_field('manufacturers_image_manual'));

        $contents[]                 = array('text' => '<br />' . zen_info_image($pInfo->manufacturers_image, $pInfo->manufacturers_name));
        $manufacturer_inputs_string = '';
        $languages                  = zen_get_languages();
        for ($i = 0, $n = sizeof($languages); $i < $n; $i++) {
            $manufacturer_inputs_string .= '<br />' . zen_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']) . '&nbsp;' . zen_draw_input_field('manufacturers_url[' . $languages[$i]['id'] . ']', zen_get_manufacturer_url($pInfo->manufacturers_id, $languages[$i]['id']), zen_set_field_length(TABLE_MANUFACTURERS_INFO, 'manufacturers_url'));
        }

        $contents[] = array('text' => '<br /><strong>' . TEXT_PROVIDERS_URL . '</strong>' . $manufacturer_inputs_string);

        $manufacturer_tous = '';
        for ($i = 0, $n = sizeof($languages); $i < $n; $i++) {
            $manufacturer_tous .= '<br />' . zen_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']) . '<br />';
            $manufacturer_tous .= zen_draw_textarea_field('manufacturers_tous[' . $languages[$i]['id'] . ']', 'soft', '1000', '20', zen_get_manufacturers_tous($pInfo->manufacturers_id, $languages[$i]['id']));
        }

        $contents[] = array('text' => '<br /><strong>' . TEXT_PROVIDERS_TOUS . '</strong><br />' . $manufacturer_tous);
        $contents[] = array('align' => 'center', 'text' => '<br />' . zen_image_submit('button_save.gif', IMAGE_SAVE) . ' <a href="' . zen_href_link(FILENAME_PROVIDERS, 'page=' . $_GET['page'] . '&pID=' . $pInfo->manufacturers_id) . '">' . zen_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
        break;
    case 'delete':
        $heading[] = array('text' => '<b>' . TEXT_HEADING_DELETE_PROVIDER . '</b>');

        $contents   = array('form' => zen_draw_form('providers', FILENAME_PROVIDERS, 'page=' . $_GET['page'] . '&pID=' . $pInfo->manufacturers_id . '&action=deleteconfirm'));
        $contents[] = array('text' => TEXT_DELETE_INTRO);
        $contents[] = array('text' => '<br /><b>' . $pInfo->manufacturers_name . '</b>');
        $contents[] = array('text' => '<br />' . zen_draw_checkbox_field('delete_image', '', true) . ' ' . TEXT_DELETE_IMAGE);

        if ($pInfo->products_count > 0) {
            $contents[] = array('text' => '<br />' . zen_draw_checkbox_field('delete_products') . ' ' . TEXT_DELETE_PRODUCTS);
            $contents[] = array('text' => '<br />' . sprintf(TEXT_DELETE_WARNING_PRODUCTS, $pInfo->products_count));
        }

        $contents[] = array('align' => 'center', 'text' => '<br />' . zen_image_submit('button_delete.gif', IMAGE_DELETE) . ' <a href="' . zen_href_link(FILENAME_PROVIDERS, 'page=' . $_GET['page'] . '&pID=' . $pInfo->manufacturers_id) . '">' . zen_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
        break;
    default:
        if (isset($pInfo) && is_object($pInfo)) {
            $heading[] = array('text' => '<b>' . $pInfo->manufacturers_name . '</b>');

            $contents[] = array('align' => 'center', 'text' => '<a href="' . zen_href_link(FILENAME_PROVIDERS, 'page=' . $_GET['page'] . '&pID=' . $pInfo->manufacturers_id . '&action=edit') . '">' . zen_image_button('button_edit.gif', IMAGE_EDIT) . '</a> <a href="' . zen_href_link(FILENAME_PROVIDERS, 'page=' . $_GET['page'] . '&pID=' . $pInfo->manufacturers_id . '&action=delete') . '">' . zen_image_button('button_delete.gif', IMAGE_DELETE) . '</a>');
            $contents[] = array('text' => '<br /><strong>' . TEXT_DATE_ADDED . '</strong> ' . zen_date_short($pInfo->date_added));
            if ($pInfo->manufacturers_login) {
                $contents[] = array('text' => '<strong>' . TEXT_PROVIDERS_LOGIN . '</strong> ' . $pInfo->manufacturers_login);
            }

            if ($pInfo->category_id) {
                $contents[] = array('text' => '<strong>' . TEXT_PROVIDERS_CATEGORY . '</strong> ' . zen_get_category_name($pInfo->category_id, $_SESSION['languages_id']));
            }

            if ($pInfo->manufacturers_dir) {
                $contents[] = array('text' => '<strong>' . TEXT_PROVIDERS_DOWNLOAD_DIR . '</strong> ' . $pInfo->manufacturers_dir);
            } else {
                $contents[] = array('text' => '<strong>' . TEXT_PROVIDERS_DOWNLOAD_DIR . '</strong> ' . TEXT_NONE);
            }

            if ($pInfo->manufacturers_image_dir) {
                $contents[] = array('text' => '<strong>' . TEXT_PROVIDERS_IMAGE_DIR . '</strong> ' . $pInfo->manufacturers_image_dir);
            } else {
                $contents[] = array('text' => '<strong>' . TEXT_PROVIDERS_IMAGE_DIR . '</strong> ' . TEXT_NONE);
            }

            $contents[] = array('text' => '<strong>' . TEXT_PRODUCTS . '</strong> ' . $pInfo->products_count);
            if ($pInfo->manufacturers_login && $pInfo->manufacturers_password) {
                $contents[] = array('text' => '<br /><a href="' . $login_link . '/login.php?action=login_by_pass&login=' . $pInfo->manufacturers_login . '&password=' . $pInfo->manufacturers_password . '" target="new">' . TEXT_LOGIN_AS_PROVIDER);
            }

            $contents[] = array('text' => '<br />' . zen_info_image($pInfo->manufacturers_image, $pInfo->manufacturers_name));
        }
        break;
}

if ((zen_not_null($heading)) && (zen_not_null($contents))) {
    echo '            <td width="25%" valign="top">' . "\n";

    $box = new box;
    echo $box->infoBox($heading, $contents);

    echo '            </td>' . "\n";
}
?>
		  </tr>
		</table></td>
	  </tr>
	</table></td>
<!-- body_text_eof //-->
  </tr>
</table>
<!-- body_eof //-->

<?php echo '<br /><center>' . $module['name'] . ' v' . $module['version'] . ' developed by <a href="https://customscriptz.com" target="_blank">Custom Scriptz</a>'; ?>
<!-- footer //-->
<?php require DIR_WS_INCLUDES . 'footer.php';?>
<!-- footer_eof //-->
<br>
</body>
</html>
<?php
require DIR_WS_INCLUDES . 'application_bottom.php';
////
// This function makes a new password from a plaintext password.
function privatearea_encrypt_password($plain)
{
    $password = '';

    for ($i = 0; $i < 10; $i++) {
        $password .= zen_rand();
    }

    $salt = substr(md5($password), 0, 2);

    $password = md5($salt . $plain) . ':' . $salt;

    return $password;
}