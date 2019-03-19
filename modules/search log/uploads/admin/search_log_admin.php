<?php
// Search Log v2.0
// Written By C.J.Pinder (c) 2007
// Portions Copyright 2003-2007 Zen Cart Development Team
// Portions Copyright 2003 osCommerce
//
// This source file is subject to version 2.0 of the GPL license, 
// that is bundled with this package in the file LICENSE, and is
// available through the world-wide-web at the following url:
// http://www.zen-cart.com/license/2_0.txt
// If you did not receive a copy of the zen-cart license and are unable
// to obtain it through the world-wide-web, please send a note to
// license@zen-cart.com so we can mail you a copy immediately.    

  require('includes/application_top.php');

  function export_search_log()
  {
    $filename = "searchlog";
    header("Pragma: cache");
    header("Content-Type: text/comma-separated-values");
    header("Content-Disposition: attachment; filename=" . urlencode($filename) . ".csv");

	$sql = "select search_time, search_term, search_results from " . TABLE_SEARCH_LOG . " order by search_time desc";
	$search_data = mysql_query($sql);

	echo '"'.TABLE_HEADING_DATE . '","' . TABLE_HEADING_SEARCH_TERM . '","' . TABLE_HEADING_RESULTS . '"' . "\n";
	
	while($row = mysql_fetch_row($search_data))
	{
		echo $row[0].',';
        $phrase = str_replace('"', '""', $row[1]);
        echo '"' . $phrase . '",';
		echo $row[2]."\n";
    }
  }
  
  $action = (isset($_GET['action']) ? $_GET['action'] : '');

  switch($action)
  {
	case 'clear_search_log':
		$db->Execute("DELETE FROM ".TABLE_SEARCH_LOG." WHERE search_time < DATE_SUB(curdate(), INTERVAL ".(int)$_POST['days']." DAY)");
		$db->Execute("optimize table " . TABLE_SEARCH_LOG);
		$messageStack->add_session(SUCCESS_CLEAN_SEARCH_LOG, 'success');
		zen_redirect(zen_href_link(FILENAME_SEARCH_LOG_ADMIN));
		break;
	
	case 'export_search_log':
		export_search_log();
		break;
  }

if ($action != 'export_search_log'){
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
    <td width="100%" valign="top">
<!-- body_text //-->
	  <table border="0" width="100%" cellspacing="0" cellpadding="0">
        <tr>
          <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
          <td class="pageHeading" align="right"><?php echo zen_draw_separator('pixel_trans.gif', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
        </tr>

        <tr>
          <td colspan="2"><br />
		    <form name="clear_search_log" action="<?php echo zen_href_link(FILENAME_SEARCH_LOG_ADMIN, 'action=clear_search_log', 'NONSSL'); ?>" method="post">
		      <table border="0" cellspacing="0" cellpadding="2">
                <tr>
                  <td class="main" align="left" valign="top"><?php printf (TEXT_DELETE_OLD_RECORDS, zen_draw_input_field('days','7','size="4"')); ?></td>
                  <td class="main" align="right" valign="middle"><?php echo zen_image_submit('button_delete.gif', IMAGE_DELETE); ?></td>
                </tr>
              </table>
		    </form>
		  </td>
        </tr>

		<tr>
          <td colspan="2"><br />
		    <form name="export_search_log" action="<?php echo zen_href_link(FILENAME_SEARCH_LOG_ADMIN, 'action=export_search_log', 'NONSSL'); ?>" method="post">
		      <table border="0" cellspacing="0" cellpadding="2">
                <tr>
                  <td class="main" align="left" valign="top"><?php echo TEXT_EXPORT_SEARCH_LOG; ?></td>
                  <td class="main" align="right" valign="middle"><?php echo zen_image_submit('button_save.gif', IMAGE_SAVE); ?></td>
                </tr>
              </table>
		    </form>
		  </td>
        </tr>

      </table>
<!-- body_text_eof //-->
	</td>
  </tr>
</table>	  
<!-- body_eof //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
</body>
</html>
<?php } ?>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>