<?php
  require('includes/application_top.php');

  $checkfields = $db->metaColumns(TABLE_MANUFACTURERS_INFO);
  if (!$checkfields['MANUFACTURERS_SALES']->type)
  {
	$db->Execute("ALTER TABLE " . TABLE_MANUFACTURERS_INFO . " ADD manufacturers_sales text NOT NULL default ''");
  }

  $action = (isset($_GET['action']) ? $_GET['action'] : '');

  if (zen_not_null($action)) {
    switch ($action) {
      case 'save':
        $providers_id = zen_db_prepare_input($_GET['pID']);

        $languages = zen_get_languages();
        for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
          $language_id = $languages[$i]['id'];
		  $providers_sales_array = $_POST['providers_sales'];
          if ($_POST['providers_sales'] == '<br />') $providers_sales_array = '';

          $sql_data_array = array('manufacturers_sales' => zen_db_prepare_input($providers_sales_array[$language_id]));
          zen_db_perform(TABLE_MANUFACTURERS_INFO, $sql_data_array, 'update', "manufacturers_id = '" . (int)$providers_id . "' and languages_id = '" . (int)$language_id . "'");
        }

		$messageStack->add_session(TEXT_PROVIDERS_SALES_PAGE_CONTENT_SAVED, 'success');
        zen_redirect(zen_href_link(FILENAME_PROVIDERS_SALES, (isset($_GET['page']) ? 'page=' . $_GET['page'] . '&' : '') . 'pID=' . $providers_id));
        break;
    }
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
            echo TEXT_EDITOR_INFO . zen_draw_form('set_editor_form', FILENAME_PROVIDERS_SALES, '', 'get') . '&nbsp;&nbsp;' . zen_draw_pull_down_menu('reset_editor', $editors_pulldown, $current_editor_key, 'onChange="this.form.submit();"') . zen_hide_session_id() .
                  zen_draw_hidden_field('action', 'set_editor') .
            '</form>';

            echo zen_draw_separator('pixel_trans.gif', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_PROVIDERS; ?></td>
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</td>
              </tr>
<?php
  $providers_query_raw = "select manufacturers_id, manufacturers_name, manufacturers_image, date_added, last_modified from " . TABLE_MANUFACTURERS . " order by manufacturers_name";
  $providers_split = new splitPageResults($_GET['page'], MAX_DISPLAY_SEARCH_RESULTS, $providers_query_raw, $providers_query_numrows);
  $providers = $db->Execute($providers_query_raw);
  while (!$providers->EOF) {
    if ((!isset($_GET['pID']) || (isset($_GET['pID']) && ($_GET['pID'] == $providers->fields['manufacturers_id']))) && !isset($pInfo) && (substr($action, 0, 3) != 'new')) {
      $pInfo = new objectInfo($providers->fields);
    }

    if (isset($pInfo) && is_object($pInfo) && ($providers->fields['manufacturers_id'] == $pInfo->manufacturers_id)) {
      echo '              <tr id="defaultSelected" class="dataTableRowSelected" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . zen_href_link(FILENAME_PROVIDERS_SALES, 'page=' . $_GET['page'] . '&pID=' . $providers->fields['manufacturers_id'] . '&action=edit') . '\'">' . "\n";
    } else {
      echo '              <tr class="dataTableRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . zen_href_link(FILENAME_PROVIDERS_SALES, 'page=' . $_GET['page'] . '&pID=' . $providers->fields['manufacturers_id'] . '&action=edit') . '\'">' . "\n";
    }

?>
                <td class="dataTableContent"><?php echo $providers->fields['manufacturers_name']; ?></td>
                <td class="dataTableContent" align="right">
                  <?php echo '<a href="' . zen_href_link(FILENAME_PROVIDERS_SALES, 'page=' . $_GET['page'] . '&pID=' . $providers->fields['manufacturers_id'] . '&action=edit') . '">' . zen_image(DIR_WS_IMAGES . 'icon_edit.gif', ICON_EDIT) . '</a>'; ?>
                  <?php if (isset($pInfo) && is_object($pInfo) && ($providers->fields['manufacturers_id'] == $pInfo->manufacturers_id)) { echo zen_image(DIR_WS_IMAGES . 'icon_arrow_right.gif', ''); } else { echo '<a href="' . zen_href_link(FILENAME_PROVIDERS_SALES, zen_get_all_get_params(array('pID')) . 'pID=' . $providers->fields['manufacturers_id']) . '">' . zen_image(DIR_WS_IMAGES . 'icon_info.gif', IMAGE_ICON_INFO) . '</a>'; } ?>
                </td>
              </tr>
<?php
    $providers->MoveNext();
  }
?>
              <tr>
                <td colspan="2"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr>
                    <td class="smallText" valign="top"><?php echo $providers_split->display_count($providers_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, $_GET['page'], TEXT_DISPLAY_NUMBER_OF_MANUFACTURERS); ?></td>
                    <td class="smallText" align="right"><?php echo $providers_split->display_links($providers_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $_GET['page']); ?></td>
                  </tr>
                </table></td>
              </tr>
<?php
  if (empty($action)) {
?>
              <tr>
                <td align="right" colspan="2" class="smallText"><?php echo '<a href="' . zen_href_link(FILENAME_PROVIDERS_SALES, 'page=' . $_GET['page'] . '&pID=' . $pInfo->manufacturers_id . '&action=new') . '">' . zen_image_button('button_insert.gif', IMAGE_INSERT) . '</a>'; ?></td>
              </tr>
<?php
  }
?>
            </table></td>
<?php
  $heading = array();
  $contents = array();

  switch ($action) {
    case 'edit':
      $heading[] = array('text' => '<b>' . TEXT_HEADING_EDIT_MANUFACTURER . '</b>');

	  $provider_sales = '';

      $languages = zen_get_languages();
      for ($i = 0, $n = sizeof($languages); $i < $n; $i++) {
	    $provider_sales .= '<br />' . zen_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']) . '&nbsp;<br />';
		  if ($_SESSION['html_editor_preference_status']=='FCKEDITOR') {
				$oFCKeditor = new FCKeditor('providers_sales[' . $languages[$i]['id']  . ']') ;
				$oFCKeditor->Value = zen_get_providers_sales($pInfo->manufacturers_id, $languages[$i]['id']);
				$oFCKeditor->Width  = '97%' ;
				$oFCKeditor->Height = '500' ;
				$output = $oFCKeditor->CreateHtml() ;
			$provider_sales .= $output;
		  } else {
			$provider_sales .= zen_draw_textarea_field('providers_sales[' . $languages[$i]['id'] . ']', 'soft', '1000', '20', zen_get_providers_sales($pInfo->manufacturers_id, $languages[$i]['id']));
		  }
	  }

      $contents = array('form' => zen_draw_form('providers', FILENAME_PROVIDERS_SALES, 'page=' . $_GET['page'] . '&pID=' . $pInfo->manufacturers_id . '&action=save', 'post'));
      $contents[] = array('text' => TEXT_EDIT_INTRO);
      $contents[] = array('text' => '<br />' . TEXT_PROVIDERS_SALES_PAGE_CONTENT . '<br />' . $provider_sales);
      $contents[] = array('align' => 'center', 'text' => '<br>' . zen_image_submit('button_save.gif', IMAGE_SAVE) . ' <a href="' . zen_href_link(FILENAME_PROVIDERS_SALES, 'page=' . $_GET['page'] . '&pID=' . $pInfo->manufacturers_id) . '">' . zen_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
      break;
    default:
      if (isset($pInfo) && is_object($pInfo)) {
        $heading[] = array('text' => '<b>' . $pInfo->manufacturers_name . '</b>');

        $contents[] = array('align' => 'center', 'text' => '<a href="' . zen_href_link(FILENAME_PROVIDERS_SALES, 'page=' . $_GET['page'] . '&pID=' . $pInfo->manufacturers_id . '&action=edit') . '">' . zen_image_button('button_edit.gif', IMAGE_EDIT) . '</a>');
        if (zen_not_null($pInfo->last_modified)) $contents[] = array('text' => TEXT_LAST_MODIFIED . ' ' . zen_date_short($pInfo->last_modified));
      }
      break;
  }

  if ( (zen_not_null($heading)) && (zen_not_null($contents)) ) {
  	$width = '25%';

  	if ($_GET['action'] == 'edit') $width = '50%';

    echo '            <td width="'.$width.'" valign="top">' . "\n";

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
<br>
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
