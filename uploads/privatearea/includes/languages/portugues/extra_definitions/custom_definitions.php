<?php
/**
 * @copyright Custom Scriptz
 * @copyright Copyright 2003-2010 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 */

//index.php
define('TEXT_NOTHING_TO_DISPLAY', 'Nothing to display');
define('BOX_TITLE_PRODUCTS_SOLD', 'Your Last %s Products Sold');
define('BOX_TITLE_FREE_PRODUCTS_SOLD', 'Your Last %s Free Products Sold');
define('BOX_TITLE_MOST_SOLD_PRODUCTS', 'Your %s Best-Selling Products');
define('TEXT_STATS_PRODUCTS_QUANTITY', 'Quantity Sold:');

//tinymce
define('EDITOR_TINYMCE', 'TinyMCE');

//categories.php
define('TEXT_SELECT_OPTION_NAME', 'Please, select a option name.');
define('TEXT_SELECT_OPTION_VALUE', 'Please, select a option value.');
define('TEXT_LEGEND_MOVED', 'Product Moved from this Category<br />Click to Restore');
define('IMAGE_ICON_MOVED', 'Product has been moved from this category - Click to restore');
define('ICON_PRODUCTS_ADD_SPECIALS', 'Add Product Specials Price');
define('ICON_PRODUCTS_EDIT_SPECIALS', 'Edit Product Specials Price');
define('TEXT_LEGEND_STATUS', 'Product Status<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;On&nbsp;&nbsp;Off&nbsp;&nbsp;Queue');
define('TEXT_LEGEND_SPECIALS', 'Special Price<br />Add Edit');
define('TEXT_LEGEND_ATTRIBUTES', 'Attributes<br />Add Edit');
define('TEXT_LEGEND_PRODUCTS', 'Products<br />Edit Delete Copy');
define('TEXT_LEGEND_IMAGE_HANDLER', 'Image Handler<br />Add/Edit Images');
define('TEXT_MISSING_ATTRIBUTES', 'The product <strong>%s</strong> is missing it\'s attributes. <a href="%s">Click here to edit</a>.');

//coupon_restrict.php
define('TEXT_COUPON_RESTRICT_TIP', 'If you want to allow this coupon to be used by all products on your category, make the Allow dot green on your category. If you want to allow this coupon available only to certain products on your category, make the Deny dot green on your category and insert the products manually.');

//custom_functions.php
define('NONE', 'None');

//help.php
define('HEADER_TITLE_HELP', 'Help');
define('HEADING_HELP', 'What can I do here?');
define('TEXT_HELP_WHAT_I_CAN_DO', '<br/>You can do everything that a Manufacturer needs to do, including:<br/><br/>
<div style="margin-left: 15px;">
- Manage your inventory.<br />
- Avoid accessing products from other providers, while they cannot access yours, either.<br />
- Create discount coupons for your products.<br />
- Send your coupons to your customers via email.<br />
- Create sales and special prices for your products.<br />
- Edit your product previews (depends on third party modules installed by your store administrator).<br />
- Run your own Sales Report (depends on third party modules installed by your store administrator).<br />
- Manage your download files.<br /><br/>
...and much more!<br /><br />

This module has been developed by <a href="http://customscriptz.com/referer.php?ref=' . urlencode(STORE_NAME) . '" target="_blank">Custom Scriptz</a>.  Please feel free to <a href="http://customscriptz.com/referer.php?ref=' . urlencode(STORE_NAME) . '" target="_blank">contact us</a> if you are interested in purchasing this module.  Please contact your store administrator if you just have questions about how to use it.  Thank you.  :)
</div>');

//product.php
define('TEXT_SET_NEW_ATTRIBUTES', 'You may now set the product attributes.');
define('TEXT_ATTRIBUTES_CONTROLLER_WARNING', 'Please, use the new attributes manager. <a href="https://customscriptz.com/tutorials/zencart/attributes-manager.php" target="_blank">Watch the Tutorial</a>');

//login.php
define('TEXT_PANEL_NOTICE', 'Notice');
define('TEXT_PROVIDER_PANEL_DOWN', 'The Provider Panel is currently down for maintenance.<br />');
define('TEXT_PROVIDER_PANEL_DOWNTIME', '<br />The Admin / Webmaster has enabled maintenance at ');
define('TEXT_PROVIDER_PANEL_REFRESH_MSG', '<br />This page will auto-refresh in 60 seconds.');
define('LOG_ERROR_SECURITY_TOKEN', 'Invalid Security Token.');
define('LOG_ERROR_WRONG_LOGIN', 'Wrong login entered.');
define('LOG_ERROR_WRONG_PASSWORD', 'Wrong password entered.');
define('LOG_LOGGED_IN', 'Logged in Successfully.');
define('ERROR_NO_MANUFACTURER_CATEGORY', '<br /><strong style="color: red;">You don\'t have a category ID set on your profile. Ask the administrator to set one for you.</strong><br />');

//messagestack forbidden messages
define('NOT_ALLOWED_ACTION', 'You are not authorized to perform the following action: <strong>%s</strong>. If you believe this is a mistake, please contact your site administrator for assistance.');
define('NOT_ALLOWED_CATEGORY', 'You are not allowed to access categories from someone else.');
define('NOT_ALLOWED_COUPON', 'This coupon is not yours or it\'s invalid.');
define('NOT_ALLOWED_PAGE', 'You are not allowed to access the page: <strong>%s</strong>. If you believe this is a mistake, please contact your site administrator for assistance.');
define('NOT_ALLOWED_PRODUCT', 'You are not allowed to access products from someone else.');
define('NOT_ALLOWED_SECTION', 'You have tried to access an area that belongs to someone else.');

//warning messages
define('FORBIDDEN_SUBJ', STORE_NAME . ': %s - Forbidden Action');
define('FORBIDDEN_MSG', '%s tried to perform the following action:');
define('FORBIDDEN_ACTION', '- Action Not Allowed %s');
define('FORBIDDEN_CATEGORY', '- Access Category %s');
define('FORBIDDEN_COUPON', '- Access Coupon ID %s');
define('FORBIDDEN_PAGE', '- Page Not Allowed: %s');
define('FORBIDDEN_PRODUCT', '- Access Product ID %s');
define('FORBIDDEN_SECTION', '- Execute Action %s');
define('FORBIDDEN_SALES', '- Access Sales ID %s');
define('CATEGORYD_ID', 'Category ID');
define('MANUFACTURER_NO_CATEGORY', 'The provider %s does not have a Category ID.');

//my_profile.php
define('TEXT_IMAGE_NONEXISTENT', 'No Image Exists');
define('BOX_TOOLS_MY_PROFILE', 'My Profile');
define('HEADING_MY_PROFILE', BOX_TOOLS_MY_PROFILE);
define('YOUR_NAME', 'Your Name');
define('YOUR_EMAIL', 'Your Email');
define('YOUR_PAYPAL_EMAIL', 'Your PayPal Email');
define('YOUR_LOGIN', 'Your Login');
define('YOUR_PASSWORD', 'Your Password (leave blank to keep the current password)');
define('YOUR_PASSWORD_CONFIRMATION', 'Your Password Confirmation');
define('YOUR_LAST_ACTIVITIES', 'Your X Last Activities to Show on the Front Page (max: 50)');
define('YOUR_PRODUCTS_PER_PAGE', 'Products/Categories to see per page (setting above 100 might cause slow load on products page)');
define('YOUR_PRODUCTS_DISABLED', 'Show Disabled Products');
define('YOUR_IMAGE', 'Your Image');
define('YOUR_URL', 'Your URL');
define('YOUR_TOUS', 'Your Terms of Use (leave blank to display nothing)');
define('ENTRY_PASSWORD_NEW_ERROR', 'The password must contain a minimum of ' . ENTRY_PASSWORD_MIN_LENGTH . ' characters.');
define('ENTRY_PASSWORD_NEW_ERROR_NOT_MATCHING', 'The Password Confirmation must match your Password.');
define('ENTRY_PASSWORD_ERROR_PASSWORD_EMPTY', 'The Password field cannot be left blank.');
define('PROFILE_SAVED_SUCCESSFULLY', 'Your Profile has been updated successfully!');
define('LOGOFF_PROVIDER', 'Because you have changed your email or login name, you will have to login again. <a href="%s">Click here</a> to do it now or just click the Logoff link.');
define('PROVIDER_NAME_EXIST', 'Name <strong>%s</strong> already exists, please choose another.');
define('PROVIDER_NAME_EMPTY', 'Your name cannot be left blank.');
define('LOGIN_EXIST', 'Login <strong>%s</strong> already exists, please choose another.');
define('LOGIN_EMPTY', 'Login name cannot be left blank.');
define('EMAIL_EXIST', 'Email address <strong>%s</strong> already exists, please choose another.');
define('EMAIL_EMPTY', 'Email address cannot be left blank.');
define('TEXT_MAIL_LOGIN_DATA_SBJ', 'Your Login details for ' . STORE_NAME);
define('TEXT_MAIL_LOGIN_DATA_MSG', "Congratulations %s, you are now officially a part of our team!\n\nYour new login details to access your own private area:\nUsername: %s\nPassword: %s\n\nYou can log in here: %s\n\nBest regards,\n" . STORE_NAME . " Team");

//product queue related
define('TEXT_PRODUCT_ON_QUEUE', 'This Product is on Queue');
define('IMAGE_ICON_STATUS_QUEUE', 'Product is On Queue');
define('TEXT_SBJ_NEW_PRODUCT_ADDED', 'New Product Added: ');
define('TEXT_MSG_NEW_PRODUCT_ADDED', "Hi, %s\n
%s, has just added a new product called %s and it\'s waiting on queue.\n
To release/check this product:
Go to your Admin Panel -> Tools -> Products on Queue.
");

define('EDITOR_CKEDITOR', 'CKEditor');
define('TEXT_PROVIDER_CONTROL_DENIED_MESSAGE', '<h1>You don\'t have sufficient rights to access the requested resource or page.<br />If you thing that this is a error, contact your store administrator.</h1>');