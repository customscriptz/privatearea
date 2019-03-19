<?php
/**
 * @copyright Custom Scriptz
 * @copyright Copyright 2003-2010 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 */

require('includes/application_top.php');

if (!file_exists('images/logo.gif')) @copy('extras/logo/logo.gif', 'images/logo.gif');
if (!file_exists('includes/cssjsmenuhover.css')) @copy('extras/styles/cssjsmenuhover.css', 'includes/cssjsmenuhover.css');
if (!file_exists('includes/index.css')) @copy('extras/styles/index.css', 'includes/index.css');
if (!file_exists('includes/menu.css')) @copy('extras/styles/menu.css', 'includes/menu.css');
if (!file_exists('includes/nde-basic.css')) @copy('extras/styles/nde-basic.css', 'includes/nde-basic.css');
if (!file_exists('includes/stylesheet.css')) @copy('extras/styles/stylesheet.css', 'includes/stylesheet.css');
if (!file_exists('includes/stylesheet_print.css')) @copy('extras/styles/stylesheet_print.css', 'includes/stylesheet_print.css');

$message = false;
if (isset($_POST['submit'])) {
	$providers_login = zen_db_prepare_input($_POST['providers_login']);
	$providers_password = zen_db_prepare_input($_POST['providers_password']);

	$sql = "SELECT * FROM " . TABLE_MANUFACTURERS . " WHERE manufacturers_login = '" . zen_db_input($providers_login) . "'";

	$result = $db->Execute($sql);
	if ((!isset($_SESSION['securityToken']) || !isset($_POST['securityToken'])) || ($_SESSION['securityToken'] !== $_POST['securityToken'])) {
		$message = true;
		$pass_message = ERROR_SECURITY_ERROR;
		$log_message = LOG_ERROR_SECURITY_TOKEN;
	}
	if (!($providers_login == $result->fields['manufacturers_login'])) {
		$message = true;
		$pass_message = ERROR_WRONG_LOGIN;
		$log_message = LOG_ERROR_WRONG_LOGIN;
	}
	if (!zen_validate_password($providers_password, $result->fields['manufacturers_password'])) {
		$message = true;
		$pass_message = ERROR_WRONG_LOGIN;
		$log_message = LOG_ERROR_WRONG_PASSWORD;
	}
	if ($message == false)
	{
		if (!$result->fields['category_id'] || $result->fields['category_id'] == 0)
		{
			$message = true;
			//gen_warning('nocategory', $manufacturer_name);
			$pass_message = ERROR_NO_MANUFACTURER_CATEGORY;
		}
	}

	if ($log_message) provider_log_login($log_message, $providers_login, $providers_password);

	if ($message == false)
	{
		$_SESSION['provider_id'] = $result->fields['manufacturers_id'];
		$_SESSION['provider_name'] = $result->fields['manufacturers_name'];
		$_SESSION['provider_email'] = $result->fields['manufacturers_email'];
		$_SESSION['provider_category'] = $result->fields['category_id'];
		$_SESSION['provider_cpath'] = custom_get_provider_cpath();
		$_SESSION['allow_meta_tags'] = $result->fields['metatags'];
		$_SESSION['meta_tags_chars'] = $result->fields['metatags_chars'];
		$_SESSION['provider_products_per_page'] = $result->fields['manufacturers_products_per_page'];
		$_SESSION['provider_show_disabled_products'] = $result->fields['manufacturers_show_disabled_products'];

		if (SESSION_RECREATE == 'True')
		{
			zen_session_recreate();
		}

		$log_message = LOG_LOGGED_IN;
		provider_log_login($log_message, $providers_login, '', $result->fields['manufacturers_id'], $result->fields['manufacturers_name']);

        $db->Execute("UPDATE " . TABLE_MANUFACTURERS . " SET manufacturers_last_login = '" . date('Y-m-d H:i:s') . "', manufacturers_login_times = manufacturers_login_times + 1 WHERE manufacturers_id = '" . (int)$result->fields['manufacturers_id'] . "'");
		zen_redirect(zen_href_link(FILENAME_DEFAULT, '', 'SSL'));
	}
} else if ($_GET['action'] == 'login_by_pass') {
	$sql = "SELECT * FROM " . TABLE_MANUFACTURERS . " WHERE manufacturers_login = '" . zen_db_input($_GET['login']) . "' and manufacturers_password = '" . zen_db_input($_GET['password']) . "'";

	$result = $db->Execute($sql);

	if ($result->RecordCount() == 1)
	{
		$_SESSION['provider_id'] = $result->fields['manufacturers_id'];
		$_SESSION['provider_name'] = $result->fields['manufacturers_name'];
		$_SESSION['provider_email'] = $result->fields['manufacturers_email'];
		$_SESSION['provider_category'] = $result->fields['category_id'];
		$_SESSION['provider_cpath'] = custom_get_provider_cpath();
		$_SESSION['allow_meta_tags'] = $result->fields['metatags'];
		$_SESSION['meta_tags_chars'] = $result->fields['metatags_chars'];
		$_SESSION['provider_products_per_page'] = $result->fields['manufacturers_products_per_page'];
		$_SESSION['provider_show_disabled_products'] = $result->fields['manufacturers_show_disabled_products'];
	
		if (SESSION_RECREATE == 'True')
		{
			zen_session_recreate();
		}
		
		zen_redirect(zen_href_link(FILENAME_DEFAULT, '', 'SSL'));
	}
	else
	{
		$pass_message = ERROR_WRONG_LOGIN;
	}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<link href="includes/stylesheet.css" rel="stylesheet" type="text/css" />
<?php if (PROVIDER_PANEL_STATUS == 'Disabled') { ?>
<meta http-equiv="refresh" content="60" />
<?php } ?>
</head>
<?php if (PROVIDER_PANEL_STATUS == 'Disabled') { ?>
<body id="login">
<?php @include('custom_header.htm'); ?>
  <fieldset style="width: 600px;">
    <legend><?php echo TEXT_PANEL_NOTICE; ?></legend>
	<?php

		$sql = "SELECT last_modified from " . TABLE_CONFIGURATION . "
		          WHERE configuration_key = 'PROVIDER_PANEL_STATUS'";

		$maintenance_on_at_time = $db->Execute($sql);

	?>
    <h3><?php
    		echo PROVIDER_PANEL_MSG != '' ? PROVIDER_PANEL_MSG : TEXT_PROVIDER_PANEL_DOWN;
    		if ($maintenance_on_at_time->fields['last_modified']) echo TEXT_PROVIDER_PANEL_DOWNTIME . $maintenance_on_at_time->fields['last_modified'];
    		echo TEXT_PROVIDER_PANEL_REFRESH_MSG;
    	?>
	</h3>
  </fieldset>
</form>
<?php } else { ?>
<body id="login" onload="document.getElementById('providers_login').focus()">
<?php @include('custom_header.htm'); ?>
<form name="login" action="<?php echo zen_href_link(FILENAME_LOGIN, '', 'SSL'); ?>" method="post">
  <fieldset>
    <legend><?php echo HEADING_TITLE; ?></legend>
    <label class="loginLabel" for="providers_login"><?php echo TEXT_MANUFACTURER_LOGIN; ?></label>
<input style="float: left" type="text" id="providers_login" name="providers_login" value="<?php echo zen_output_string($providers_login); ?>" />
<br class="clearBoth" />
    <label  class="loginLabel" for="providers_password"><?php echo TEXT_MANUFACTURER_PASSWORD; ?></label>
<input style="float: left" type="password" id="providers_password" name="providers_password" value="<?php echo zen_output_string($providers_password); ?>" />
<br class="clearBoth" />
    <?php if ($pass_message) echo '<br /><strong>' . $pass_message . '</strong><br />'; ?>
    <input type="hidden" name="securityToken" value="<?php echo $_SESSION['securityToken']; ?>">
    <input type="submit" name="submit" class="button" value="Login" />
    <?php echo '<a class="style3" href="' . zen_href_link(FILENAME_PASSWORD_FORGOTTEN, '', 'SSL') . '">' . TEXT_PASSWORD_FORGOTTEN . '</a>'; ?>
  </fieldset>
</form>
<?php } ?>
<br /><br /><div class="style2">PrivateArea Module by <a href="http://customscriptz.com" target="_blank">Custom Scriptz</a>
<?php @include('custom_footer.htm'); ?></div>
</body>
</html>
<?php require('includes/application_bottom.php'); ?>