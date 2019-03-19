<?php
  require('includes/application_top.php');
  if ($messageStack->size > 0) echo $messageStack->output();

  $checkfields = $db->metaColumns(TABLE_MANUFACTURERS_INFO);
  if (!$checkfields['MANUFACTURERS_SALES']->type)
  {
	$db->Execute("ALTER TABLE " . TABLE_MANUFACTURERS_INFO . " ADD manufacturers_sales text NOT NULL default ''");
  }

  $action = (isset($_GET['action']) ? $_GET['action'] : '');

if (zen_not_null($action) AND $action == 'set_editor') {
	zen_redirect(zen_href_link(FILENAME_MY_SALES));
}

if (zen_not_null($action) AND $action == 'save') {
	$providers_id = (int)$_SESSION['provider_id'];
	$messageStack->add_session(SALES_SAVED_SUCCESSFULLY, 'success');

	$sql_data_array = array();
	$languages = zen_get_languages();
	for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
		$language_id = $languages[$i]['id'];

		$sql_data_array = array();
		$sql_data_array['manufacturers_sales'] = zen_db_prepare_input($_POST['providers_sales'][$language_id]);

		zen_db_perform(TABLE_MANUFACTURERS_INFO, $sql_data_array, 'update', "manufacturers_id = '" . (int)$providers_id . "' and languages_id = '" . (int)$language_id . "'");
	}

	zen_redirect(zen_href_link(FILENAME_MY_SALES));
	break;
}
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
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
<body onload="init()">
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
            <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
            <td class="pageHeading" align="right"><?php
            // toggle switch for editor
            echo TEXT_EDITOR_INFO . zen_draw_form('set_editor_form', FILENAME_MY_SALES, '', 'get') . '&nbsp;&nbsp;' . zen_draw_pull_down_menu('reset_editor', $editors_pulldown, $current_editor_key, 'onChange="this.form.submit();"') . zen_hide_session_id() .
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
$heading = array();
$contents = array();

$heading[] = array('text' => '<b>' . HEADING_MY_SALES . '</b>');
$contents = array('form' => zen_draw_form('providers', FILENAME_MY_SALES, 'action=save', 'post'));

function zen_get_providers_sales($manufacturers_id, $language_id)
{
	global $db;

	$sales = $db->Execute("SELECT manufacturers_sales FROM " . TABLE_MANUFACTURERS_INFO . " WHERE manufacturers_id = '" . (int)$manufacturers_id . "' AND languages_id = '" . (int)$language_id . "'");
	return $sales->fields['manufacturers_sales'];
}

$manufacturer_sales = '';
for ($i = 0, $n = sizeof($languages); $i < $n; $i++) {
	$manufacturer_sales .= '<br />' . zen_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']) . '&nbsp;';

	if ($_SESSION['html_editor_preference_status_privatearea']=='FCKEDITOR') {
		$oFCKeditor = new FCKeditor('providers_sales[' . $languages[$i]['id']  . ']') ;
		$oFCKeditor->Value = zen_get_providers_sales($_SESSION['provider_id'], $languages[$i]['id']);
		$oFCKeditor->Width  = '97%' ;
		$oFCKeditor->Height = '200' ;
		$output = $oFCKeditor->CreateHtml() ;
		$manufacturer_sales .= '<br />' . $output;
	} else {
		$manufacturer_sales .= '<br />' . zen_draw_textarea_field('providers_sales[' . $languages[$i]['id'] . ']', 'soft', '100%', '30', zen_get_providers_sales($_SESSION['provider_id'], $languages[$i]['id']));
	}
}

$contents[] = array('text' => '<br />' . YOUR_SALES_PAGE_CONTENT . $manufacturer_sales);
$contents[] = array('align' => 'center', 'text' => '<br />' . zen_image_submit('button_save.gif', IMAGE_SAVE) . ' <a href="' . zen_href_link(FILENAME_MY_SALES, '&providerID=' . $providers->fields['manufacturers_id']) . '">' . zen_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');


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