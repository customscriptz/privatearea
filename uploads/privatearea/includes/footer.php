<?php
/**
 * @package admin
 * @copyright Copyright 2003-2009 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version $Id: footer.php 14876 2009-11-21 05:18:06Z drbyte $
 */

// check and display zen cart version and history version in footer
  $current_sinfo = ' v' . PROJECT_VERSION_MAJOR . '.' . PROJECT_VERSION_MINOR . '/';
  $check_hist_query = "SELECT * from " . TABLE_PROJECT_VERSION . " WHERE project_version_key = 'Zen-Cart Database' ORDER BY project_version_date_applied DESC LIMIT 1";
  $check_hist_details = $db->Execute($check_hist_query);
  if (!$check_hist_details->EOF) {
    $current_sinfo .=  'v' . $check_hist_details->fields['project_version_major'] . '.' . $check_hist_details->fields['project_version_minor'];
    if (zen_not_null($check_hist_details->fields['project_version_patch1'])) $current_sinfo .= '&nbsp;&nbsp;Patch: ' . $check_hist_details->fields['project_version_patch1'];
  }
  
  $get_version = $db->Execute("SELECT module_version FROM " . TABLE_CUSTOM_SCRIPTZ . " WHERE module_id = 'privatearea'");
  
?>
<table border="0" width="100%" cellspacing="10" cellpadding="10">
  <tr>
    <td align="center" class="smallText" height="100" valign="bottom">
		<a href="http://www.zen-cart.com" target="_blank"><img src="images/small_zen_logo.gif" alt="Zen Cart:: the art of e-commerce" border="0"></a>&nbsp;&nbsp;&nbsp;
		<a href="http://customscriptz.com" target="_blank"><img src="https://customscriptz.com/logo-mini.jpg" alt="Custom Scriptz" title="Custom Scriptz" border="0"></a>
		<br /><br />
		E-Commerce Engine Copyright &copy; 2003-<?php echo date('Y'); ?> <a href="http://www.zen-cart.com" target="_blank">Zen Cart&trade;</a> <?php echo '<a href="' . zen_href_link(FILENAME_SERVER_INFO) . '">' . $current_sinfo . '</a>'; ?><br />
		PrivateArea Module <?php echo $get_version->fields['module_version']; ?> by <a href="http://customscriptz.com" target="_blank">Custom Scriptz</a></td>
  </tr>
</table>