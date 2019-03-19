<?php
/**
 * @copyright Custom Scriptz
 * @copyright Copyright 2003-2010 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 */

function customScriptzCheckMenu()
{
    global $db;

    $project_version = $db->Execute("SELECT project_version_minor FROM " . TABLE_PROJECT_VERSION . " WHERE project_version_id = 1");
    if ((int) substr($project_version->fields['project_version_minor'], 0, 1) >= 5) {
        $menu = $db->Execute("SELECT * FROM " . TABLE_ADMIN_MENUS . " WHERE menu_key = 'customscriptz'");
        if ($menu->RecordCount() <= 0) {
            $db->Execute("REPLACE INTO " . TABLE_ADMIN_MENUS . " (menu_key, language_key, sort_order) VALUES ('customscriptz', 'BOX_HEADING_CUSTOMSCRIPTZ', '12')");
        }

        // PrivateArea
        $sort_order = 1;
        customScriptzInsertModulePage('privatearea_providers', 'BOX_TOOLS_PROVIDERS', 'FILENAME_PROVIDERS', $sort_order++);
        customScriptzInsertModulePage('privatearea_product_queue', 'BOX_TOOLS_PRODUCT_QUEUE', 'FILENAME_PRODUCT_QUEUE', $sort_order++);
        customScriptzInsertModulePage('privatearea_providers_login', 'BOX_TOOLS_PROVIDERS_LOGIN', 'FILENAME_PROVIDERS_LOGIN', $sort_order++);
        customScriptzInsertModulePage('privatearea_send_mail', 'BOX_TOOLS_PROVIDERS_SEND_EMAIL', 'FILENAME_PROVIDERS_SEND_EMAIL', $sort_order++);
        customScriptzInsertModulePage('privatearea_sales_page', 'BOX_TOOLS_PROVIDERS_SALES', 'FILENAME_PROVIDERS_SALES', $sort_order++);
        customScriptzInsertModulePage('privatearea_search_log_admin', 'BOX_TOOLS_SEARCH_LOG_ADMIN', 'FILENAME_SEARCH_LOG_ADMIN', $sort_order++);
        customScriptzInsertModulePage('privatearea_stats_search_log', 'BOX_REPORTS_SEARCH_LOG', 'FILENAME_STATS_SEARCH_LOG', $sort_order++);

        // Sales Report
        if (defined('BOX_REPORTS_SALES_REPORT_COMMISSION')) {
            customScriptzInsertModulePage('sales_report', 'BOX_REPORTS_SALES_REPORT', 'FILENAME_STATS_SALES_REPORT', $sort_order++);
            customScriptzInsertModulePage('sales_report_commission', 'BOX_REPORTS_SALES_REPORT_COMMISSION', 'FILENAME_STATS_SALES_REPORT_COMMISSION', $sort_order++);
            customScriptzInsertModulePage('sales_report_commission_group', 'BOX_REPORTS_SALES_REPORT_COMMISSION_GROUP', 'FILENAME_STATS_SALES_REPORT_COMMISSION_GROUP', $sort_order++);
        }

        // Email Orders
        customScriptzInsertModulePage('email_orders', 'BOX_CUSTOMERS_EMAIL_ORDERS', 'FILENAME_EMAIL_ORDERS', $sort_order++);
        customScriptzInsertModulePage('email_orders_edit_email', 'BOX_CUSTOMERS_EMAIL_ORDERS_EDIT_EMAIL', 'FILENAME_EMAIL_ORDERS_EDIT_EMAIL', $sort_order++);

        // Free Gifts
        customScriptzInsertModulePage('free_gifts', 'BOX_TOOLS_FREE_GIFTS', 'FILENAME_FREEGIFTS', $sort_order++);

        // Reset Customers Password
        customScriptzInsertModulePage('reset_customer_password', 'BOX_CUSTOMERS_RESET_CUSTOMER_PASSWORD', 'FILENAME_CUSTOMER_RESET_PASSWORD', $sort_order++);

        // PagSeguro
        customScriptzInsertModulePage('pagseguro', 'BOX_CUSTOMERS_PAGSEGURO', 'FILENAME_PAGSEGURO', $sort_order++);

        // Special Date
        customScriptzInsertModulePage('specialdate', 'BOX_TOOLS_SPECIAL_DATE', 'FILENAME_SPECIAL_DATE', $sort_order++);

        // Image Handler
        $check = $db->Execute("SELECT * FROM " . TABLE_ADMIN_PAGES . " WHERE page_key = 'image_handler' OR page_key = 'configImageHandler4'");
        if ($check->RecordCount() <= 0) {
            customScriptzInsertModulePage('image_handler', 'BOX_TOOLS_IMAGE_HANDLER', 'FILENAME_IMAGE_HANDLER', 50, 'tools');
        }

        $check1 = $db->Execute("SELECT * FROM " . TABLE_ADMIN_PAGES . " WHERE page_key = 'image_handler'");
        $check2 = $db->Execute("SELECT * FROM " . TABLE_ADMIN_PAGES . " WHERE page_key = 'configImageHandler4'");
        if ($check1->RecordCount() == 1 && $check2->RecordCount() == 1) {
            $db->Execute("DELETE FROM " . TABLE_ADMIN_PAGES . " WHERE page_key = 'image_handler'");
        }

        // Subscribe Designer
        customScriptzInsertModulePage('subscribe_designer', 'BOX_TOOLS_SUBSCRIBE_DESIGNERS', 'FILENAME_SUBSCRIBE_DESIGNER', $sort_order++);

    }
}

function customScriptzInsertModulePage($page_key, $language_key, $main_page, $sort_order = 1, $menu = 'customscriptz', $display_on_menu = 'Y')
{
    global $db;

    @ini_set('error_reporting', E_ALL & ~E_DEPRECATED & ~E_NOTICE);

    if (!@file_exists(DIR_FS_ADMIN . @constant($main_page) . '.php')) {
        return;
    }

    if (!defined('TABLE_ADMIN_PAGES')) {
        return;
    }

    $db->Execute("REPLACE INTO " . TABLE_ADMIN_PAGES . " (page_key, language_key, main_page, menu_key, display_on_menu, sort_order) VALUES ('" . $page_key . "', '" . $language_key . "', '" . $main_page . "', '" . $menu . "', '" . $display_on_menu . "', " . $sort_order . ")");

    if ($main_page == 'FILENAME_PROVIDERS') {
        $group_id = $db->Execute("SELECT configuration_group_id AS group_id FROM " . TABLE_CONFIGURATION_GROUP . " WHERE configuration_group_title = '" . BOX_CONFIGURATION_PRIVATEAREA . "'");
        $group_id = $group_id->fields['group_id'];
        if ($group_id > 0) {
            $db->Execute("REPLACE INTO " . TABLE_ADMIN_PAGES . " (page_key, language_key, main_page, page_params, menu_key, display_on_menu, sort_order) VALUES ('configPrivateArea', 'BOX_CONFIGURATION_PRIVATEAREA', 'FILENAME_CONFIGURATION', 'gID=" . $group_id . "', 'configuration', 'Y', 999)");
        }

    }

    if ($main_page == 'FILENAME_STATS_SALES_REPORT') {
        $group_id = $db->Execute("SELECT configuration_group_id AS group_id FROM " . TABLE_CONFIGURATION_GROUP . " WHERE configuration_group_title = '" . BOX_CONFIGURATION_SALESREPORT . "'");
        $group_id = $group_id->fields['group_id'];
        if ($group_id > 0) {
            $db->Execute("REPLACE INTO " . TABLE_ADMIN_PAGES . " (page_key, language_key, main_page, page_params, menu_key, display_on_menu, sort_order) VALUES ('configSalesReport', 'BOX_CONFIGURATION_SALESREPORT', 'FILENAME_CONFIGURATION', 'gID=" . $group_id . "', 'configuration', 'Y', 999)");
        }

    }

    if ($main_page == 'FILENAME_EMAIL_ORDERS') {
        $group_id = $db->Execute("SELECT configuration_group_id AS group_id FROM " . TABLE_CONFIGURATION_GROUP . " WHERE configuration_group_title = '" . BOX_CONFIGURATION_EMAILORDERS . "'");
        $group_id = $group_id->fields['group_id'];
        if ($group_id > 0) {
            $db->Execute("REPLACE INTO " . TABLE_ADMIN_PAGES . " (page_key, language_key, main_page, page_params, menu_key, display_on_menu, sort_order) VALUES ('configEmailOrders', 'BOX_CONFIGURATION_EMAILORDERS', 'FILENAME_CONFIGURATION', 'gID=" . $group_id . "', 'configuration', 'Y', 999)");
        }

    }

    if ($main_page == 'FILENAME_FREEGIFTS') {
        $group_id = $db->Execute("SELECT configuration_group_id AS group_id FROM " . TABLE_CONFIGURATION_GROUP . " WHERE configuration_group_title = '" . BOX_CONFIGURATION_FREEGIFTS . "'");
        $group_id = $group_id->fields['group_id'];
        if ($group_id > 0) {
            $db->Execute("REPLACE INTO " . TABLE_ADMIN_PAGES . " (page_key, language_key, main_page, page_params, menu_key, display_on_menu, sort_order) VALUES ('configFreeGifts', 'BOX_CONFIGURATION_FREEGIFTS', 'FILENAME_CONFIGURATION', 'gID=" . $group_id . "', 'configuration', 'Y', 999)");
        }

    }
}
