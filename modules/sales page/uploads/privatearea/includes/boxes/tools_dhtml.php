<?php
/**
 * @package provider profiles
 * @copyright Copyright Custom Scriptz - 2009
 * @copyright Portions Copyright kuroi web design 2006-2007
 * @copyright Portions Copyright 2003-2007 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version $Id: tools_dhtml.php
 */
if (!defined('IS_ADMIN_FLAG')) {
  die('Illegal Access');
}

  $za_heading = array();
  $za_contents = array();

  $za_heading = array('text' => BOX_HEADING_TOOLS, 'link' => zen_href_link(FILENAME_ALT_NAV, '', 'NONSSL'));

  $options = array(array('box' => BOX_TOOLS_MY_PROFILE, 'page' => FILENAME_MY_PROFILE));

  foreach ($options as $key => $value) {
    $za_contents[] = array('text' => $value['box'], 'link' => zen_href_link($value['page'], '', 'NONSSL'));
  }

  if ($za_dir = @dir(DIR_WS_BOXES . 'extra_boxes')) {
    while ($zv_file = $za_dir->read()) {
      if (preg_match('/tools_dhtml.php$/', $zv_file)) {
        require(DIR_WS_BOXES . 'extra_boxes/' . $zv_file);
      }
    }
    $za_dir->close();
  }

  for ($i = 0; $i <= count($za_contents); $i++)
  {
  	if (!checkAccess(basename(str_replace('.php', '', $za_contents[$i]['link']))))
  	{
  		//unset($za_contents[$i]);
  	}
  }

  ?>
  <!-- tools //-->
  <?php
  echo zen_draw_admin_box($za_heading, $za_contents);
  ?>
  <!-- tools_eof //-->