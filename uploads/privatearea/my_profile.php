<?php
/**
 * @package admin
 * @copyright Copyright 2003-2007 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version $Id: providers.php 6131 2007-04-08 06:56:51Z drbyte $
 */

require('includes/application_top.php');
if ($messageStack->size > 0) echo $messageStack->output();

$login_link = HTTP_SERVER . DIR_WS_HTTPS_CATALOG . PROVIDER_DIR;

$action = (isset($_GET['action']) ? $_GET['action'] : '');

if ($action == 'logoff') unset($_SESSION['provider_id']);

if (zen_not_null($action) AND $action == 'set_editor') {
	zen_redirect(zen_href_link(FILENAME_MY_PROFILE));
}

if (zen_not_null($action) AND $action == 'save') {
	$providers_id = (int)$_SESSION['provider_id'];
	$providers_name = zen_db_prepare_input(addslashes($_POST['providers_name']));
	$providers_email = zen_db_prepare_input(addslashes($_POST['providers_email']));
	$providers_paypal_email = zen_db_prepare_input(addslashes($_POST['providers_paypal_email']));
	$providers_login = zen_db_prepare_input(addslashes(str_replace(' ', '', $_POST['providers_login'])));
	$providers_password = zen_db_prepare_input(addslashes($_POST['providers_password']));
	$providers_password_confirmation = zen_db_prepare_input(addslashes($_POST['providers_password_confirmation']));
	$providers_last_activities = (int)$_POST['providers_last_activities'];
	$providers_products_per_page = (int)$_POST['providers_products_per_page'];
	$providers_show_disabled_products = (int)$_POST['providers_show_disabled_products'];

	$sql = "SELECT manufacturers_name FROM " . TABLE_MANUFACTURERS . " WHERE manufacturers_name = '" . $providers_name . "' AND manufacturers_id <> '" . $providers_id . "'";
	$check = $db->Execute($sql);
	if ($check->RecordCount() > 0) {
		$messageStack->add_session(sprintf(PROVIDER_NAME_EXIST, $providers_name), 'error');
	}

	$sql = "SELECT manufacturers_login FROM " . TABLE_MANUFACTURERS . " WHERE manufacturers_login = '" . $providers_login . "' AND manufacturers_id <> '" . $providers_id . "'";
	$check = $db->Execute($sql);
	if ($check->RecordCount() > 0) {
		$messageStack->add_session(sprintf(LOGIN_EXIST, $providers_login), 'error');
	}

	$sql = "SELECT manufacturers_email FROM " . TABLE_MANUFACTURERS . " WHERE manufacturers_email = '" . $providers_email . "' AND manufacturers_id <> '" . $providers_id . "'";
	$check = $db->Execute($sql);
	if ($check->RecordCount() > 0) {
		$messageStack->add_session(sprintf(EMAIL_EXIST, $providers_email), 'error');
	}

	if (!$providers_name) {
		$messageStack->add_session(PROVIDER_NAME_EMPTY, 'error');
	}
	elseif (empty($providers_login))
	{
		$messageStack->add_session(LOGIN_EMPTY, 'error');
	}
	elseif (!$providers_email) {
		$messageStack->add_session(EMAIL_EMPTY, 'error');
	}
	elseif (!empty($providers_password) OR !empty($providers_password_confirmation))
	{
		if (strlen($providers_password) < ENTRY_PASSWORD_MIN_LENGTH)
		{
			$messageStack->add_session(ENTRY_PASSWORD_NEW_ERROR, 'error');
		}
		elseif ($providers_password != $providers_password_confirmation)
		{
			$messageStack->add_session(ENTRY_PASSWORD_NEW_ERROR_NOT_MATCHING, 'error');
		}
	}

	//if there is any error, redirect to my profile page and save no data
	if($_SESSION['messageToStack']) zen_redirect(zen_href_link(FILENAME_MY_PROFILE, 'providerID=' . $providers_id . '&action=edit'));
	$messageStack->add_session(PROFILE_SAVED_SUCCESSFULLY, 'success');

	$sql_data_array = array();
	$sql_data_array['manufacturers_login'] = $providers_login;
	$sql_data_array['manufacturers_name'] = stripslashes($providers_name);
	$sql_data_array['manufacturers_email'] = $providers_email;
	$sql_data_array['manufacturers_paypal_email'] = $providers_paypal_email;
	if ($providers_password) $sql_data_array['manufacturers_password'] = zen_encrypt_password($providers_password);
	$sql_data_array['manufacturers_last_activities'] = ($providers_last_activities > 50 ? 50 : $providers_last_activities);
	if ($providers_products_per_page < 1) $providers_products_per_page = 1;
	$sql_data_array['manufacturers_products_per_page'] = $providers_products_per_page;
	$sql_data_array['manufacturers_show_disabled_products'] = $providers_show_disabled_products;

	$_SESSION['provider_name'] = stripslashes($providers_name);
	$_SESSION['provider_products_per_page'] = $providers_products_per_page;
	$_SESSION['provider_show_disabled_products'] = $providers_show_disabled_products;

	zen_db_perform(TABLE_MANUFACTURERS, $sql_data_array, 'update', "manufacturers_id = '" . (int)$providers_id . "'");

	$providers_image = new upload('providers_image');
	$providers_image->set_destination(DIR_FS_CATALOG_IMAGES . $_POST['img_dir']);
	if ( $providers_image->parse() &&  $providers_image->save()) {
		// remove image from database if none
		if ($providers_image->filename != 'none') {
			$db->Execute("update " . TABLE_MANUFACTURERS . "
				  set manufacturers_image = '" .  $_POST['img_dir'] . $providers_image->filename . "'
				  where manufacturers_id = '" . (int)$providers_id . "'");
		} else {
			$db->Execute("update " . TABLE_MANUFACTURERS . "
				  set manufacturers_image = ''
				  where manufacturers_id = '" . (int)$providers_id . "'");
		}
	}

	$languages = zen_get_languages();
	for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
		$language_id = $languages[$i]['id'];

		$sql_data_array = array();
		$sql_data_array['manufacturers_url'] = zen_db_prepare_input($_POST['providers_url'][$language_id]);
		$sql_data_array['manufacturers_tous'] = zen_db_prepare_input($_POST['providers_tous'][$language_id]);

		zen_db_perform(TABLE_MANUFACTURERS_INFO, $sql_data_array, 'update', "manufacturers_id = '" . (int)$providers_id . "' and languages_id = '" . (int)$language_id . "'");
	}

	if ((stripslashes($providers_name) != $_SESSION['provider_name']) OR ($providers_email != $_SESSION['provider_email']))
	{
		$param = 'action=logoff';
		$messageStack->add_session(sprintf(LOGOFF_PROVIDER, zen_href_link(FILENAME_LOGIN, '', 'SSL')), 'caution');
	}

	zen_redirect(zen_href_link(FILENAME_MY_PROFILE, $param));
	break;
}
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="X-UA-Compatible" content="IE=Edge,chrome=1">
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<link rel="stylesheet" type="text/css" href="includes/cssjsmenuhover.css" media="all" id="hoverJS">
<script language="javascript" src="includes/menu.js"></script>
<script language="javascript" src="includes/general.js"></script>
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
		config.width = '780px';
		config.height = '400px';

		HTMLArea.replaceAll(config);
	}
  }
  // -->
</script>
<?php if ($editor_handler != '') include ($editor_handler); ?>
</head>
<body onLoad="init()">
<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->

<!-- body //-->
<table border="0" width="100%" cellspacing="2" cellpadding="2">
  <tr>
<!-- body_text //-->
    <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo HEADING_MY_PROFILE; ?></td>
            <td class="pageHeading" align="right"><?php
            // toggle switch for editor
            echo TEXT_EDITOR_INFO . zen_draw_form('set_editor_form', FILENAME_MY_PROFILE, '', 'get') . '&nbsp;&nbsp;' . zen_draw_pull_down_menu('reset_editor', $editors_pulldown, $current_editor_key, 'onChange="this.form.submit();"') . zen_hide_session_id() .
                  zen_draw_hidden_field('action', 'set_editor') .
            '</form>';

            echo zen_draw_separator('pixel_trans.gif', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td valign="top">
			<table border="0" width="100%" cellspacing="0" cellpadding="2">
			  <tr class="dataTableHeadingRow">
<?php
$providers_query_raw = "select * from " . TABLE_MANUFACTURERS . " where manufacturers_id = '" . $_SESSION['provider_id'] . "'";
$providers = $db->Execute($providers_query_raw);

$heading = array();
$contents = array();

$heading[] = array('text' => '<b>' . HEADING_MY_PROFILE . '</b>');
$contents = array('form' => zen_draw_form('providers', FILENAME_MY_PROFILE, 'action=save', 'post', 'enctype="multipart/form-data"'));

$contents[] = array('text' => '<br />' . YOUR_NAME . '<br />' . zen_draw_input_field('providers_name', $providers->fields['manufacturers_name'], zen_set_field_length(TABLE_MANUFACTURERS, 'manufacturers_name'), true));
$contents[] = array('text' => '<br />' . YOUR_EMAIL . '<br />' . zen_draw_input_field('providers_email', $providers->fields['manufacturers_email'], zen_set_field_length(TABLE_MANUFACTURERS, 'manufacturers_email', $max=30), true));
$contents[] = array('text' => '<br />' . YOUR_PAYPAL_EMAIL . '<br />' . zen_draw_input_field('providers_paypal_email', $providers->fields['manufacturers_paypal_email'], zen_set_field_length(TABLE_MANUFACTURERS, 'manufacturers_paypal_email', $max=30), true));
$contents[] = array('text' => '<br />' . YOUR_LOGIN . '<br />' . zen_draw_input_field('providers_login', $providers->fields['manufacturers_login'], zen_set_field_length(TABLE_MANUFACTURERS, 'manufacturers_login', $max=30), true));
$contents[] = array('text' => '<br />' . YOUR_PASSWORD . '<br />' . zen_draw_password_field('providers_password', '', zen_set_field_length(TABLE_MANUFACTURERS, 'manufacturers_password', $max=20), false) );
$contents[] = array('text' => '<br />' . YOUR_PASSWORD_CONFIRMATION . '<br />' . zen_draw_password_field('providers_password_confirmation', '', zen_set_field_length(TABLE_MANUFACTURERS, 'manufacturers_password', $max=20), false));
$contents[] = array('text' => '<br />' . YOUR_LAST_ACTIVITIES . '<br />' . zen_draw_input_field('providers_last_activities', $providers->fields['manufacturers_last_activities'], zen_set_field_length(TABLE_MANUFACTURERS, 'manufacturers_last_activities', $max=2), true));
$contents[] = array('text' => '<br />' . YOUR_PRODUCTS_PER_PAGE . '<br />' . zen_draw_input_field('providers_products_per_page', $providers->fields['manufacturers_products_per_page'], zen_set_field_length(TABLE_MANUFACTURERS, 'manufacturers_products_per_page', $max=3), true));
$contents[] = array('text' => '<br />' . YOUR_PRODUCTS_DISABLED . '<br />' . zen_draw_checkbox_field('providers_show_disabled_products', '1', ($providers->fields['manufacturers_show_disabled_products'] ? true : false)));
$contents[] = array('text' => '<br />' . YOUR_IMAGE . '<br />' . zen_draw_file_field('providers_image') . '<br />' . $providers->fields['manufacturers_image']);

$contents[] = array('text' => '<br />' . zen_draw_hidden_field('img_dir', 'manufacturers/'));
$contents[] = array('text' => '<br />' . zen_info_image($providers->fields['manufacturers_image'], $providers->fields['manufacturers_name']));
$manufacturer_inputs_string = '';
$languages = zen_get_languages();
for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
	$manufacturer_inputs_string .= '<br />' . zen_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']) . '&nbsp;' . zen_draw_input_field('providers_url[' . $languages[$i]['id'] . ']', zen_get_manufacturer_url($providers->fields['manufacturers_id'], $languages[$i]['id']), zen_set_field_length(TABLE_MANUFACTURERS_INFO, 'manufacturers_url'));
}
$contents[] = array('text' => '<br />' . YOUR_URL . $manufacturer_inputs_string);

$manufacturer_tous = '';
for ($i = 0, $n = sizeof($languages); $i < $n; $i++) {
	$manufacturer_tous .= '<br />' . zen_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']) . '&nbsp;';

	if ($_SESSION['html_editor_preference_status_privatearea']=='FCKEDITOR') {
		$oFCKeditor = new FCKeditor('providers_tous[' . $languages[$i]['id']  . ']') ;
		$oFCKeditor->Value = zen_get_manufacturers_tous($providers->fields['manufacturers_id'], $languages[$i]['id']);
		$oFCKeditor->Width  = '97%' ;
		$oFCKeditor->Height = '200' ;
		$output = $oFCKeditor->CreateHtml() ;
		$manufacturer_tous .= '<br />' . $output;
	} else {
		$manufacturer_tous .= zen_draw_textarea_field('providers_tous[' . $languages[$i]['id'] . ']', 'soft', '100%', '20', zen_get_manufacturers_tous($providers->fields['manufacturers_id'], $languages[$i]['id']));
	}
}

$contents[] = array('text' => '<br />' . YOUR_TOUS . $manufacturer_tous);
$contents[] = array('align' => 'center', 'text' => '<br />' . zen_image_submit('button_save.gif', IMAGE_SAVE) . ' <a href="' . zen_href_link(FILENAME_MY_PROFILE, '&providerID=' . $providers->fields['manufacturers_id']) . '">' . zen_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');


if ( (zen_not_null($heading)) && (zen_not_null($contents)) ) {
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

<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
<br />
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>