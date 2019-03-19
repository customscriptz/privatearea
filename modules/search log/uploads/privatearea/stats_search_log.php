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
<!-- body_text //-->
    <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="0">
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
                <td class="dataTableHeadingContent" align="left" valign="top" width="20%">
					<?php echo (($_GET['list_order']=='searchdate' or $_GET['list_order']=='searchdate-desc') ? '<span class="SortOrderHeader">' . TABLE_HEADING_DATE . '</span>' : TABLE_HEADING_DATE); ?><br>
					<a href="<?php echo zen_href_link(basename($PHP_SELF) . '?list_order=searchdate', '', 'NONSSL'); ?>"><?php echo ($_GET['list_order']=='searchdate' ? '<span class="SortOrderHeader">Asc</span>' : '<span class="SortOrderHeaderLink">Asc</b>'); ?></a>&nbsp;
					<a href="<?php echo zen_href_link(basename($PHP_SELF) . '?list_order=searchdate-desc', '', 'NONSSL'); ?>"><?php echo ($_GET['list_order']=='searchdate-desc' ? '<span class="SortOrderHeader">Desc</span>' : '<span class="SortOrderHeaderLink">Desc</b>'); ?></a>
				</td>
                <td class="dataTableHeadingContent" align="left" valign="top">
					<?php echo (($_GET['list_order']=='searchterm' or $_GET['list_order']=='searchterm-desc') ? '<span class="SortOrderHeader">' . TABLE_HEADING_SEARCH_TERM . '</span>' : TABLE_HEADING_SEARCH_TERM); ?><br>
					<a href="<?php echo zen_href_link(basename($PHP_SELF) . '?list_order=searchterm', '', 'NONSSL'); ?>"><?php echo ($_GET['list_order']=='searchterm' ? '<span class="SortOrderHeader">Asc</span>' : '<span class="SortOrderHeaderLink">Asc</b>'); ?></a>&nbsp;
					<a href="<?php echo zen_href_link(basename($PHP_SELF) . '?list_order=searchterm-desc', '', 'NONSSL'); ?>"><?php echo ($_GET['list_order']=='searchterm-desc' ? '<span class="SortOrderHeader">Desc</span>' : '<span class="SortOrderHeaderLink">Desc</b>'); ?></a>
				</td>
                <td class="dataTableHeadingContent" width="20%">
					<?php echo (($_GET['list_order']=='searchresults' or $_GET['list_order']=='searchresults-desc') ? '<span class="SortOrderHeader">' . TABLE_HEADING_RESULTS . '</span>' : TABLE_HEADING_RESULTS); ?><br>
					<a href="<?php echo zen_href_link(basename($PHP_SELF) . '?list_order=searchresults', '', 'NONSSL'); ?>"><?php echo ($_GET['list_order']=='searchresults' ? '<span class="SortOrderHeader">Asc</span>' : '<span class="SortOrderHeaderLink">Asc</b>'); ?></a>&nbsp;
					<a href="<?php echo zen_href_link(basename($PHP_SELF) . '?list_order=searchresults-desc', '', 'NONSSL'); ?>"><?php echo ($_GET['list_order']=='searchresults-desc' ? '<span class="SortOrderHeader">Desc</span>' : '<span class="SortOrderHeaderLink">Desc</b>'); ?></a>
				</td>
              </tr>
<?php
    switch ($_GET['list_order'])
	{
		case "searchdate":
			$disp_order = "search_time";
			break;
		case "searchdate-desc":
			$disp_order = "search_time DESC";
			break;
		case "searchterm":
			$disp_order = "search_term, search_time DESC";
			break;
		case "searchterm-desc":
			$disp_order = "search_term DESC, search_time DESC";
			break;
		case "searchresults":
			$disp_order = "search_results, search_time DESC";
			break;
		case "searchresults-desc":
			$disp_order = "search_results DESC, search_time DESC";
			break;
		default:
			$disp_order = "search_time DESC";
			break;
	}
 
	$search_query_raw = "select search_results, search_term, search_time from " . TABLE_SEARCH_LOG . " order by " . $disp_order;
	$search_split = new splitPageResults($_GET['page'], MAX_DISPLAY_SEARCH_RESULTS_REPORTS, $search_query_raw, $search_query_numrows);
	$search_terms = $db->Execute($search_query_raw);
	while (!$search_terms->EOF)
	{
?>
              <tr class="dataTableRow">
                <td class="dataTableContent"><?php echo $search_terms->fields['search_time']; ?>&nbsp;&nbsp;</td>
                <td class="dataTableContent"><?php echo htmlspecialchars(stripslashes($search_terms->fields['search_term'])); ?>&nbsp;&nbsp;</td>
                <td class="dataTableContent"><?php echo $search_terms->fields['search_results']; ?></td>
              </tr>
<?php
		$search_terms->MoveNext();
    }
 ?>
            </table></td>
          </tr>
          <tr>
            <td colspan="3"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td class="smallText" valign="top"><?php echo $search_split->display_count($search_query_numrows, MAX_DISPLAY_SEARCH_RESULTS_REPORTS, $_GET['page'], TEXT_DISPLAY_NUMBER_OF_SEARCHES); ?></td>
                <td class="smallText" align="right"><?php echo $search_split->display_links($search_query_numrows, MAX_DISPLAY_SEARCH_RESULTS_REPORTS, MAX_DISPLAY_PAGE_LINKS, $_GET['page'], zen_get_all_get_params(array('page'))); ?></td>
			  </tr>
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
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>