<?php
/**
 * @package admin
 * @copyright Copyright 2003-2007 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version $Id: providers.php 6131 2007-04-08 06:56:51Z drbyte $
 */

require('includes/application_top.php');

$action = (isset($_GET['a']) ? $_GET['a'] : '');
if (zen_not_null($action)) {
	switch ($action) {
		case 'resetLog':
			$db->Execute("DELETE FROM " . TABLE_PROVIDER_LOGIN_LOG);
			$messageStack->add_session(RESET_PROVIDER_ACTIVITY_LOG_DELETED, 'success');
			zen_redirect(zen_href_link(FILENAME_PROVIDERS_LOGIN));
			break;
	}
}
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo HEADING_TITLE; ?></title>
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
  }
  // -->
</script>
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
            <td class="pageHeading" align="right"><?php echo '<a href="' . zen_href_link(FILENAME_PROVIDERS_LOGIN, 'a=resetLog') . '" onclick="return confirm(\''.RESET_PROVIDER_ACTIVITY_LOG_CONFIRM.'\')">' . zen_image_button('button_reset.gif', RESET_PROVIDER_ACTIVITY_LOG) . '</a>'; ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent" width="2%"><?php echo TABLE_HEADING_PROVIDER_ID; ?></td>
                <td class="dataTableHeadingContent" width="15%"><?php echo TABLE_HEADING_PROVIDER_NAME; ?></td>
                <td class="dataTableHeadingContent" width="10%"><?php echo TABLE_HEADING_PROVIDER_PASSWORD; ?></td>
                <td class="dataTableHeadingContent" width="10%"><?php echo TABLE_HEADING_PROVIDER_LOGIN; ?></td>
                <td class="dataTableHeadingContent" width="10%"><?php echo TABLE_HEADING_PROVIDER_DATE; ?></td>
                <td class="dataTableHeadingContent" width="30%"><?php echo TABLE_HEADING_PROVIDER_MESSAGE; ?></td>
                <td class="dataTableHeadingContent" width="10%"><?php echo TABLE_HEADING_PROVIDER_IP; ?></td>
              </tr>
<?php
$providerslogin_query_raw = "select * from " . TABLE_PROVIDER_LOGIN_LOG . " order by date DESC";
$providerslogin_split = new splitPageResults($_GET['page'], 100, $providerslogin_query_raw, $providerslogin_query_numrows);
$providerslogin = $db->Execute($providerslogin_query_raw);
while (!$providerslogin->EOF) {
?>
			  <tr class="dataTableRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)">
                <td class="dataTableContent"><?php echo $providerslogin->fields['provider_id']; ?></td>
                <td class="dataTableContent"><?php echo $providerslogin->fields['provider_name']; ?></td>
                <td class="dataTableContent"><?php echo $providerslogin->fields['provider_password']; ?></td>
                <td class="dataTableContent"><?php echo $providerslogin->fields['provider_login']; ?></td>
                <td class="dataTableContent"><?php echo $providerslogin->fields['date']; ?></td>
                <td class="dataTableContent"><?php echo $providerslogin->fields['log_message']; ?></td>
                <td class="dataTableContent"><?php echo $providerslogin->fields['ip_address']; ?></td>
              </tr>
<?php
$providerslogin->MoveNext();
}
?>
              <tr>
                <td colspan="7"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr>
                    <td class="smallText" valign="top"><?php echo $providerslogin_split->display_count($providerslogin_query_numrows, 100, $_GET['page'], TEXT_DISPLAY_NUMBER_OF_LOGINS); ?></td>
                    <td class="smallText" align="right"><?php echo $providerslogin_split->display_links($providerslogin_query_numrows, 100, MAX_DISPLAY_PAGE_LINKS, $_GET['page']); ?></td>
                  </tr>
                </table></td>
              </tr>
<?php
if (empty($action) AND !$manufacturer_id) {
?>
              <tr>
                <td align="right" colspan="7" class="smallText"></td>
              </tr>
<?php
}
?>
            </table></td>
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