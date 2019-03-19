<?php
// only check products if requested - this may slow down the processing of the manufacturers sidebox
  $manufacturer_sidebox_query = "select *
                            from " . TABLE_MANUFACTURERS . " m
                            order by manufacturers_name";

  $manufacturer_sidebox = $db->Execute($manufacturer_sidebox_query);

  if ($manufacturer_sidebox->RecordCount()>0) {
    $manufacturer_sidebox_array = array();

    while (!$manufacturer_sidebox->EOF) {
      $manufacturer_sidebox_name = ((strlen($manufacturer_sidebox->fields['manufacturers_name']) > MAX_DISPLAY_MANUFACTURER_NAME_LEN) ? substr($manufacturer_sidebox->fields['manufacturers_name'], 0, MAX_DISPLAY_MANUFACTURER_NAME_LEN) . '..' : $manufacturer_sidebox->fields['manufacturers_name']);
	  $manufacturer_sidebox_image = $manufacturer_sidebox->fields['manufacturers_image'];
      $manufacturer_sidebox_array[] =
		array('id' => $manufacturer_sidebox->fields['manufacturers_id'],
			  'text' => $manufacturer_sidebox_name);
      $manufacturer_sidebox->MoveNext();
    }
      require($template->get_template_dir('tpl_sales_page.php',DIR_WS_TEMPLATE, $current_page_base,'sideboxes'). '/tpl_sales_page.php');

    $title = '<label>' . BOX_INFORMATION_MANUFACTURES_SALES . '</label>';
    $title_link = false;
    require($template->get_template_dir($column_box_default, DIR_WS_TEMPLATE, $current_page_base,'common') . '/' . $column_box_default);
  }