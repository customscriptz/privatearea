<?php
/**
 * @package admin
 * @copyright Copyright 2003-2007 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version $Id: catalog_dhtml.php 6050 2007-03-24 03:20:50Z ajeh $
 */
if (!defined('IS_ADMIN_FLAG')) {
  die('Illegal Access');
}
  $za_contents = array();
  $za_heading = array('text' => BOX_HEADING_CATALOG, 'link' => zen_href_link(FILENAME_ALT_NAV, '', 'NONSSL'));

  $options = array( array( 'page' => FILENAME_CATEGORIES, 'box' => BOX_CATALOG_CATEGORIES_PRODUCTS),
          array( 'page' => FILENAME_PRODUCTS_PRICE_MANAGER, 'box' => BOX_CATALOG_PRODUCTS_PRICE_MANAGER),
          array( 'page' => FILENAME_ATTRIBUTES_CONTROLLER, 'box' => BOX_CATALOG_CATEGORIES_ATTRIBUTES_CONTROLLER),
          array( 'page' => FILENAME_SPECIALS, 'box' => BOX_CATALOG_SPECIALS),
          array( 'page' => FILENAME_FEATURED, 'box' => BOX_CATALOG_FEATURED),
          array( 'page' => FILENAME_SALEMAKER, 'box' => BOX_CATALOG_SALEMAKER),
          array( 'page' => FILENAME_DOWNLOADS_MANAGER, 'box' => BOX_CATALOG_CATEGORIES_ATTRIBUTES_DOWNLOADS_MANAGER),
          );

  foreach ($options as $key => $value) {
	  if (!checkAccess($value['page']))
		  continue;
	$za_contents[] = array('text' => $value['box'], 'link' => zen_href_link($value['page'], '', 'NONSSL'));
  }
		  
  if ($za_dir = @dir(DIR_WS_BOXES . 'extra_boxes')) {
    while ($zv_file = $za_dir->read()) {
      if (preg_match('/catalog_dhtml.php$/', $zv_file)) {
        require(DIR_WS_BOXES . 'extra_boxes/' . $zv_file);
      }
    }
    $za_dir->close();
  }

  echo zen_draw_admin_box($za_heading, $za_contents);