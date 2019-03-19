<?php
/**
 * @package provider
 * @copyright Copyright 2003-2010 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version $Id: header.php 15825 2010-04-05 10:55:01Z drbyte $
 */
if (!defined('IS_ADMIN_FLAG')) {
  die('Illegal Access');
}

// Show Languages Dropdown for convenience only if main filename and directory exists
if ((basename($PHP_SELF) != FILENAME_DEFINE_LANGUAGE . '.php') and (basename($PHP_SELF) != FILENAME_PRODUCTS_OPTIONS_NAME . '.php') and empty($action)) {
  $languages_array = array();
  $languages = zen_get_languages();
  if (sizeof($languages) > 1) {
    //$languages_selected = $_GET['language'];
    $languages_selected = $_SESSION['language'];
    $missing_languages='';
    $count = 0;
    for ($i = 0, $n = sizeof($languages); $i < $n; $i++) {
      $test_directory= DIR_WS_LANGUAGES . $languages[$i]['directory'];
      $test_file= DIR_WS_LANGUAGES . $languages[$i]['directory'] . '.php';
      if ( file_exists($test_file) and file_exists($test_directory) ) {
        $count++;
        $languages_array[] = array('id' => $languages[$i]['code'],
                                 'text' => $languages[$i]['name']);
//        if ($languages[$i]['directory'] == $language) {
        if ($languages[$i]['directory'] == $_SESSION['language']) {
          $languages_selected = $languages[$i]['code'];
        }
      } else {
        $missing_languages .= ' ' . ucfirst($languages[$i]['directory']) . ' ' . $languages[$i]['name'];
      }
    }

// if languages in table do not match valid languages show error message
    if ($count != sizeof($languages)) {
      $messageStack->add('MISSING LANGUAGE FILES OR DIRECTORIES ...' . $missing_languages,'caution');
    }
    $hide_languages= false;
  } else {
    $hide_languages= true;
  } // more than one language
} else {
  $hide_languages= true;
} // hide when other language dropdown is used
?>
<!-- All HEADER_ definitions in the columns below are defined in includes/languages/english.php //-->
<table border="0" width="100%" cellspacing="0" cellpadding="0" class="header">
<?php
// special spacing for alt_nav.php
  if (basename($PHP_SELF) == 'alt_nav.php') {
?>
<tr><td><br /><br /></td></tr>
<?php } // alt_nav spacing ?>
  <tr>
    <td align="left" valign="top"><?php echo '<a href="' . zen_href_link(FILENAME_DEFAULT) . '">' . zen_image(DIR_WS_IMAGES . HEADER_LOGO_IMAGE, HEADER_ALT_TEXT) . '</a>'; ?></td>
    <td colspan="2" align="left"><table width="100%"><tr>
    </tr></table></td>
  </tr>
</table>
<table border="0" cellspacing="0" cellpadding="0" width="100%">
  <tr class="headerBar" height="20" width="100%">

    <td class="headerBarContent" align="left">
      <?php
      if (!$hide_languages) {
        echo zen_draw_form('languages', basename($PHP_SELF), '', 'get');
        echo DEFINE_LANGUAGE . '&nbsp;&nbsp;' . (sizeof($languages) > 1 ? zen_draw_pull_down_menu('language', $languages_array, $languages_selected, 'onChange="this.form.submit();"') : '');
        echo zen_hide_session_id();
        echo '</form>';
      } else {
        echo '&nbsp;';
      }
    ?>
    </td>
    <td class="headerBarContent" align="left"><b><?php echo date("r", time()) . 'GMT'  . '&nbsp;[' .  $_SERVER['REMOTE_ADDR'] . ' ]&nbsp;'; ?></b></td>
	<td class="headerBarContent" align="center"><?php echo $_SESSION['provider_name']; ?></td>
	<td class="headerBarContent" align="right"><?php echo '<a href="' . zen_href_link(FILENAME_DEFAULT, '', 'NONSSL') . '" class="headerLink">' . HEADER_TITLE_TOP . '</a>&nbsp;|&nbsp;<a href="' . zen_catalog_href_link('index&cPath=' . custom_get_provider_cpath()) . '" class="headerLink" target="_blank">' . HEADER_TITLE_ONLINE_CATALOG . '</a>&nbsp;|&nbsp;<a href="' . zen_href_link('help.php') . '" class="headerLink">' . HEADER_TITLE_HELP . '</a>&nbsp;|&nbsp;<a href="' . zen_href_link(FILENAME_LOGOFF) . '" class="headerLink">' . HEADER_TITLE_LOGOFF . '</a>&nbsp;'; ?></td>
  </tr>
</table>
<?php require(DIR_WS_INCLUDES . 'header_navigation.php'); ?>
