<?php
/**
 * @copyright Custom Scriptz
 * @copyright Copyright 2003-2010 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 */

define('TABLE_HEADING_PROVIDERS', 'PrivateArea - Providers');
define('TABLE_HEADING_PROVIDERS_COMMISSION', 'Sales Commission');
define('TABLE_HEADING_PROVIDERS_LAST_LOGIN', 'Last Login');
define('TABLE_HEADING_PROVIDERS_LOGINS', 'Logins');
define('TABLE_HEADING_ACTION', 'Action');

define('TEXT_NEVER', 'Never');
define('TEXT_NOLOGIN', 'None');

define('HEADING_TITLE_PROVIDERS', TABLE_HEADING_PROVIDERS);
define('TEXT_DISPLAY_NUMBER_OF_PROVIDERS', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> providers)');

define('TEXT_HEADING_NEW_PROVIDER', 'New Provider');
define('TEXT_HEADING_EDIT_PROVIDER', 'Edit Provider');
define('TEXT_HEADING_DELETE_PROVIDER', 'Delete Provider');

define('TEXT_DATE_ADDED', 'Date Added:');
define('TEXT_LAST_MODIFIED', 'Last Modified:');
define('TEXT_PRODUCTS', 'Products:');
define('TEXT_PRODUCTS_IMAGE_DIR', 'Upload to directory:');
define('TEXT_IMAGE_NONEXISTENT', 'No Image');
define('TEXT_PROVIDERS_IMAGE_MANUAL', '<strong>Or, select an existing image file from server, filename:</strong>');

define('TEXT_NEW_PROVIDER_INTRO', 'Please fill out the following information for the new provider');
define('TEXT_EDIT_PROVIDER_INTRO', 'Please make any necessary changes');

define('TEXT_PROVIDERS_NAME', 'Provider Name:');
define('TEXT_PROVIDERS_COMMISSION', 'Sales Commission:<br /><em>* Only for <a href="https://customscriptz.com/cart.php?a=add&pid=6" target="_blank">Sales Report + Payroll</a> owners</em>');
define('TEXT_PROVIDERS_DISCOUNT_PAYMENT_FEE', 'Discount Payment Fee:');
define('TEXT_PROVIDERS_EMAIL', 'Provider Email:');
define('TEXT_PROVIDERS_PAYPAL_EMAIL', 'Provider PayPal Email:');
define('TEXT_PROVIDERS_PAYPAL_CURRENCY', 'PayPal Currency:<br /><dfn>This will be the currency used when paying the commission. Can be changed at any time.</dfn>');
define('TEXT_PROVIDERS_LOGIN', 'Provider Login:');
define('TEXT_PROVIDERS_PASSWORD', 'Provider Password: (min: ' . ENTRY_PASSWORD_MIN_LENGTH . ' chars)');
define('TEXT_PROVIDERS_CONFIRM_PASSWORD', 'Password Confirmation:');
define('TEXT_PROVIDERS_DOWNLOAD_DIR', 'Provider Download Dir:');
define('TEXT_PROVIDERS_DOWNLOAD_DIR_MANUAL', 'If you want to create a new download dir, enter the dir name below:<br /><em>All lowercase, without spaces and without special characteres</em>');
define('TEXT_PROVIDERS_IMAGE_DIR', 'Provider Image Dir:');
define('TEXT_PROVIDERS_IMAGE_DIR_MANUAL', 'If you want to create a new image dir, enter the dir name below:<br /><em>All lowercase, without spaces and without special characteres</em>');
define('TEXT_PROVIDERS_SEND_LOGIN', 'Send Login Details:');
define('TEXT_PROVIDERS_SEND_LOGIN_DETAILS', 'Send login details by email.');
define('TEXT_PROVIDERS_CATEGORY', 'Main Category:');
define('TEXT_PROVIDERS_METATAGS_CHARS', 'Maximum Meta Tag Characters:');
define('TEXT_PROVIDERS_METATAGS', 'Allow Meta Tags:');
define('TEXT_PROVIDERS_YES', 'Yes');
define('TEXT_PROVIDERS_NO', 'No');
define('TEXT_PROVIDERS_ACCESS', '<strong>Provider Access:</strong>');
define('TEXT_PROVIDERS_IMAGE', 'Provider Image:');
define('TEXT_PROVIDERS_URL', 'Provider URL:');
define('TEXT_PROVIDERS_TOUS', 'Provider Terms of Use:');
define('BOX_TOOLS_MY_PROFILE', 'My Profile');
define('BOX_TOOLS_FREEGIFTS', 'Free Gifts');
define('BOX_COUPON_RESTRICT', 'Coupon Restrict');
define('BOX_REPORTS_SALES_REPORT', 'Sales Report');
define('BOX_TOOLS_IMAGE_HANDLER', 'Image Handler');
define('BOX_MULTI_CATEGORY', 'Multiple Categories Link Manager');
define('BOX_CATALOG_ADVANCED_XSELL_PRODUCTS', 'Advanced Cross-Sell');
define('BOX_CATALOG_CATEGORIES_ATTRIBUTES_DOWNLOADS_MANAGER', 'Downloads Manager');
define('TEXT_LOGIN_AS_PROVIDER', 'Login as This Provider');

define('TEXT_DELETE_INTRO', 'Are you sure you want to delete this provider?');
define('TEXT_DELETE_IMAGE', 'Delete provider image?');
define('TEXT_DELETE_PRODUCTS', 'Delete products from this provider? (including product reviews, products on special, upcoming products)');
define('TEXT_DELETE_WARNING_PRODUCTS', '<b>WARNING:</b> There are %s products still linked to this provider!');

define('ERROR_DIRECTORY_NOT_WRITEABLE', 'Error: I can not write to this directory. Please set the right user permissions on: %s');
define('ERROR_DIRECTORY_DOES_NOT_EXIST', 'Error: Directory does not exist: %s');

define('ERROR_PROVIDER_NAME_EXIST', 'One provider called <strong>%s</strong> already exist, please choose another name.');
define('ERROR_PROVIDER_EMAIL_EXIST', 'The email address <strong>%s</strong> already exist, please choose another email.');
define('ERROR_PROVIDER_LOGIN_EXIST', 'One provider with the login <strong>%s</strong> already exist, please choose another login.');
define('ERROR_PROVIDER_NAME_EMPTY', 'The provider name cannot be left blank.');
define('ERROR_PROVIDER_LOGIN_EMPTY', 'The provider login cannot be left blank.');
define('ERROR_PROVIDER_EMAIL_EMPTY', 'The provider email cannot be left blank.');
define('ERROR_PROVIDER_PASSWORD_EMPTY', 'The provider password cannot be left blank.');
define('ERROR_PROVIDER_CREATING_DIR', 'I could not create the dir <strong>%s</strong>, please make sure that the directory doesn\'t exist and that you have sufficient rights. You may also create the dir manually using your FTP software.');

define('ENTRY_PASSWORD_NEW_ERROR', 'The Password must contain a minimum of ' . ENTRY_PASSWORD_MIN_LENGTH . ' characters.');
define('ENTRY_PASSWORD_NEW_ERROR_NOT_MATCHING', 'The Password Confirmation must match your Password.');
define('ENTRY_PASSWORD_ERROR_PASSWORD_EMPTY', 'The Password field cannot be left blank.');

define('PROVIDER_SAVED_SUCCESSFULLY', 'Provider <strong>%s</strong> saved successfully!');

define('TEXT_MAIL_LOGIN_DATA_SBJ', 'Your Login details at ' . STORE_NAME);
define('TEXT_MAIL_LOGIN_DATA_MSG', "Congratulations %s, you are now part of our Designers team.\n\nYour new login details to access your own custom area:\nUsername: %s\nPassword: %s\n\nYou can log in at: %s\n\nBest regards,\n" . STORE_NAME . " Team\n\n");

//permissions
define('PROVIDER_DIR_CREATED', 'I have created the directory <strong>%s</strong> automatically for you.');
define('PROVIDER_DIR_NOT_CREATED', 'I tried to created the directory <strong>%s</strong> automatically for you, but failed. Please, do it using your ftp software.');

// sales report
define('TEXT_INFO_USE_GROUP_COMMISSION', 'Use commission group when calculating the commission (<a href="http://customscriptz.com/wiki/Sales_Report_%2B_Payroll#Commission_Groups" target="_blank">read more</a>)<br /><em>* Only for <a href="https://customscriptz.com/cart.php?a=add&pid=6" target="_blank">Sales Report + Payroll</a> owners</em>');
define('TABLE_HEADING_COMMISSION_GROUP', 'Use Commission Group %');