<?php
/**
 * Manufacturers Select Sidebox as List Template
 *
 * @package templateSystem
 * @copyright Copyright 2003-2005 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version $Id: tpl_manufacturers_select.php 2007-07-08 kuroi
 */
  $content = '';
  $content .= '<div id="' . str_replace('_', '-', $box_id . 'Content') . '" class="sideBoxContent">' . "\n";
  $content .= '<ul>' . "\n";
  for ($i=0;$i<sizeof($manufacturer_sidebox_array);$i++) {
    if ($manufacturer_sidebox_array[$i]['text'] != '' AND trim(zen_get_providers_sales($manufacturer_sidebox_array[$i]['id'])) != '')
    {
      $content .= '<li><a class="designerName" href="' . zen_href_link(FILENAME_PROVIDERS_SALES, 'salesId=' . $manufacturer_sidebox_array[$i]['id']) . '">';
      $content .= $manufacturer_sidebox_array[$i]['text'];
      $content .= '</a></li>' . "\n";
    }
  }
  $content .= "</ul>\n</div>\n";
?>