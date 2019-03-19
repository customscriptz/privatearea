<?php
  require('includes/application_top.php');
  
  $sql = "UPDATE " . TABLE_PRODUCTS . " SET product_on_queue = 0 WHERE products_status = 1";
  $db->Execute($sql);

  $action = (isset($_GET['action']) ? $_GET['action'] : '');

  if (zen_not_null($action)) {
    switch ($action) {
      case 'release_confirm':
      	$pID = (isset($_GET['pID']) ? $_GET['pID'] : '');
      	if ($pID > 0)
      	{
			$sql = "UPDATE " . TABLE_PRODUCTS . " SET product_on_queue = 0, products_status = 1, products_date_available = '" . $_POST['products_date_available'] . "' ";

			if (CS_UPDATE_ADDED_DATE == 'True')
			{
				$sql .= ", products_date_added = now(), products_last_modified = now() ";
			}

      		$sql .= "WHERE products_id = '" . (int)$pID . "'";

      		$db->Execute($sql);

			$manufacturer = $db->Execute("SELECT m.manufacturers_name, m.manufacturers_email FROM " . TABLE_PRODUCTS . " p JOIN " . TABLE_MANUFACTURERS . " m ON (p.manufacturers_id = m.manufacturers_id) WHERE p.products_id = '" . (int)$pID . "'");

      		if ($manufacturer->RecordCount() > 0)
      		{
      			if (!function_exists(zen_get_info_page))
      			{
					function zen_get_info_page($zf_product_id) {
						global $db;
						$sql = "select products_type from " . TABLE_PRODUCTS . " where products_id = '" . (int)$zf_product_id . "'";
						$zp_type = $db->Execute($sql);
						if ($zp_type->RecordCount() == 0) {
							return 'product_info';
						} else {
							$zp_product_type = $zp_type->fields['products_type'];
							$sql = "select type_handler from " . TABLE_PRODUCT_TYPES . " where type_id = '" . (int)$zp_product_type . "'";
							$zp_handler = $db->Execute($sql);
							return $zp_handler->fields['type_handler'] . '_info';
						}
					}
      			}

				$link = HTTP_SERVER . DIR_WS_CATALOG . 'index.php?main_page=' . zen_get_info_page($pID) . '&cPath=' . zen_get_product_path($pID, 'override') . '&products_id=' . $pID;
				$subject = sprintf(TEXT_SBJ_PRODUCT_RELEASED, urldecode($_GET['pName']));
      			$message = sprintf(TEXT_MSG_PRODUCT_RELEASED, $manufacturer->fields['manufacturers_name'], urldecode($_GET['pName']), STORE_NAME, $link);
      			$messageHTML = sprintf(TEXT_MSG_PRODUCT_RELEASED, '<strong>' . $manufacturer->fields['manufacturers_name'] . '</strong>', '<strong>' . urldecode($_GET['pName']) . '</strong>', '<strong>' . STORE_NAME . '</strong>', $link);

				if (!empty($manufacturer->fields['manufacturers_email']))
					zen_mail($manufacturer->fields['manufacturers_name'], $manufacturer->fields['manufacturers_email'], $subject, $message, STORE_NAME, EMAIL_FROM, array('EMAIL_MESSAGE_HTML'=>nl2br($messageHTML)), 'debug');
			}

			$messageStack->add_session(sprintf(TEXT_PRODUCT_X_RELEASED_SUCCESSFULLY, urldecode($_GET['pName'])), 'success');
      	}
      	zen_redirect(zen_href_link(FILENAME_PRODUCT_QUEUE, 'page=' . $_GET['page']));
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
<link rel="stylesheet" type="text/css" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.16/themes/base/jquery-ui.css">
<script language="javascript" src="includes/menu.js"></script>
<script language="javascript" src="includes/general.js"></script>
<script language="JavaScript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.4/jquery.min.js"></script>
<script language="JavaScript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.16/jquery-ui.min.js"></script>
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
  }
  // -->
</script>
<script>
$(function() {
	$( "#datepicker" ).datepicker({ dateFormat: 'yy-mm-dd' });
});
</script>
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
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
            <td class="pageHeading" align="right"><?php echo zen_draw_separator('pixel_trans.gif', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_PRODUCT_NAME; ?></td>
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_MANUFACTURER; ?></td>
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_DATE_ADDED; ?></td>
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_DATE_MODIFIED; ?></td>
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</td>
              </tr>
<?php
  $products_query_raw = "SELECT p.products_id, p.products_date_added, p.products_last_modified, pd.products_name, m.manufacturers_name, p.products_date_available FROM " . TABLE_PRODUCTS . " p JOIN " . TABLE_PRODUCTS_DESCRIPTION . " pd ON (p.products_id = pd.products_id) JOIN " . TABLE_MANUFACTURERS . " m ON (p.manufacturers_id = m.manufacturers_id) WHERE p.product_on_queue = 1 AND p.products_status = 0 AND pd.language_id = '" . $_SESSION['languages_id'] . "' ORDER BY p.products_date_added DESC, pd.products_name";
  $products_split = new splitPageResults($_GET['page'], MAX_DISPLAY_SEARCH_RESULTS, $products_query_raw, $product_query_numrows);
  $products = $db->Execute($products_query_raw);
  while (!$products->EOF) {
    if ((!isset($_GET['pID']) || (isset($_GET['pID']) && ($_GET['pID'] == $products->fields['products_id']))) && !isset($pInfo) && (substr($action, 0, 3) != 'new')) {
      $pInfo = new objectInfo($products->fields);
    }

    if (isset($pInfo) && is_object($pInfo) && ($products->fields['products_id'] == $pInfo->products_id) ) {
      echo '              <tr id="defaultSelected" class="dataTableRowSelected" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . zen_href_link(FILENAME_PRODUCT_QUEUE, 'page=' . $_GET['page'] . '&pID=' . $pInfo->products_id . '&action=check_product') . '\'">' . "\n";
    } else {
      echo '              <tr class="dataTableRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . zen_href_link(FILENAME_PRODUCT_QUEUE, 'page=' . $_GET['page'] . '&pID=' . $products->fields['products_id'] . '&action=check_product') . '\'">' . "\n";
    }
      echo '                <td class="dataTableContent">' . $products->fields['products_name'] . '</td>' . "\n";
?>
                <td class="dataTableContent"><?php echo $products->fields['manufacturers_name']; ?></td>
                <td class="dataTableContent"><?php echo $products->fields['products_date_added']; ?></td>
                <td class="dataTableContent"><?php echo $products->fields['products_last_modified']; ?></td>
                <td class="dataTableContent" align="right"><?php if (isset($pInfo) && is_object($pInfo) && ($products->fields['products_id'] == $pInfo->products_id) ) { echo zen_image(DIR_WS_IMAGES . 'icon_arrow_right.gif'); } else { echo '<a href="' . zen_href_link(FILENAME_PRODUCT_QUEUE, 'page=' . $_GET['page'] . '&pID=' . $products->fields['products_id']) . '">' . zen_image(DIR_WS_IMAGES . 'icon_info.gif', IMAGE_ICON_INFO) . '</a>'; } ?>&nbsp;</td>
              </tr>
<?php
    $products->MoveNext();
  }
?>
              <tr>
                <td colspan="4"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr>
                    <td class="smallText" valign="top"><?php echo $products_split->display_count($product_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, $_GET['page'], TEXT_DISPLAY_NUMBER_OF_PRODUCTS); ?></td>
                    <td class="smallText" align="right"><?php echo $products_split->display_links($product_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $_GET['page']); ?></td>
                  </tr>
                </table></td>
              </tr>
            </table></td>
<?php
  $heading = array();
  $contents = array();

switch ($action) {
	case 'release_product':
		$heading[] = array('text' => '<b>' . sprintf(TEXT_INFO_HEADING_PRODUCT_RELEASE, $pInfo->products_name) . '</b>');

		$contents[] = array('text' => sprintf(TEXT_INFO_RELEASE_PRODUCT, $pInfo->products_name));
		$contents[] = array('text' => zen_draw_form('release_product', FILENAME_PRODUCT_QUEUE, 'page=' . $_GET['page'] . '&pID=' . $pInfo->products_id . '&pName=' . urlencode($pInfo->products_name) . '&action=release_confirm'));
		$contents[] = array('text' => '<br />' . TEXT_PRODUCTS_DATE_AVAILABLE . ' <small>(YYYY-MM-DD)</small><br /><input type="text" id="datepicker" value="' . substr($pInfo->products_date_available, 0, -9) . '" size="12" name="products_date_available" />');
		$contents[] = array('align' => 'center', 'text' => '<br />' . zen_image_submit('button_confirm_red.gif', IMAGE_RELEASE_PRODUCT) . ' <a href="' . zen_href_link(FILENAME_CATEGORIES, 'cPath=' . zen_get_product_path($pInfo->products_id, 'override') . '&pID=' . $pInfo->products_id) . '" target="_blank">' . zen_image_button('button_edit.gif', IMAGE_EDIT) . '</a></form>');
		break;
	case 'check_product':
	default:
		$heading[] = array('text' => '<b>' . sprintf(TEXT_INFO_HEADING_PRODUCT_ACTIONS, $pInfo->products_name) . '</b>');

		$contents[] = array('text' => TEXT_INFO_PRODUCT_ACTIONS);
		$contents[] = array('align' => 'center', 'text' => '<br />' . '<a href="' . zen_href_link(FILENAME_PRODUCT_QUEUE, 'page=' . $_GET['page'] . '&pID=' . $pInfo->products_id . '&action=release_product') . '">' . zen_image_button('button_release.gif', IMAGE_RELEASE_PRODUCT) . '</a> <a href="' . zen_href_link(FILENAME_CATEGORIES, 'cPath=' . zen_get_product_path($pInfo->products_id, 'override') . '&pID=' . $pInfo->products_id) . '" target="_blank">' . zen_image_button('button_edit.gif', IMAGE_EDIT) . '</a>');
		break;
 }

  if ((zen_not_null($heading)) && (zen_not_null($contents)) && (zen_not_null($pInfo->products_id))) {
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
<br>
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>