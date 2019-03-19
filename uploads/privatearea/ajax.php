<?php
require('includes/application_top.php');

$return['error'] = false;
$return['response'] = '';
$key = $_SESSION['securityToken'];
if ($_GET['key'] == $key)
{
	if ($_GET['action'] == 'OptionsValues' && (int)$_GET['id'] > 0)
	{
		$options = $db->Execute("SELECT pov.products_options_values_id, pov.products_options_values_name, povpo.products_options_id
								FROM " . TABLE_PRODUCTS_OPTIONS_VALUES . " pov
								JOIN " . TABLE_PRODUCTS_OPTIONS_VALUES_TO_PRODUCTS_OPTIONS . " povpo ON (pov.products_options_values_id = povpo.products_options_values_id)
								WHERE pov.language_id = " . $_SESSION['languages_id'] . " AND povpo.products_options_id = " . (int)$_GET['id'] . "
								ORDER BY pov.products_options_values_sort_order, pov.products_options_values_name");
								
								
		$return['response'] = '<select id="option_' . $_GET['id'] . '" name="values_id" class="values_id" size="15">' . "\n";
		while(!$options->EOF)
		{
			$return['response'] .= '<option name="' . $options->fields['products_options_values_name'] . '" value="' . $options->fields['products_options_values_id'] . '">' . $options->fields['products_options_values_name'] . '</option>' . "\n";
		
			$options->MoveNext();
		}

		$return['response'] .= '</select>' . "\n";
	}
	else if ($_GET['action'] == 'InsertAttributes' && (int)$_GET['id'] > 0)
	{
		$check = $db->Execute("SELECT * FROM " . TABLE_PRODUCTS_ATTRIBUTES . " WHERE products_id = '" . (int)$_GET['id'] . "' AND options_id = '" . (int)$_REQUEST['options_id'] . "' AND options_values_id = '" . (int)$_REQUEST['values_id'] . "'");
		
		if (!$check->fields['products_attributes_id'])
		{
			if (zen_validate_options_to_options_value($_REQUEST['options_id'], $_REQUEST['values_id']))
			{
				$db->Execute("insert into " . TABLE_PRODUCTS_ATTRIBUTES . " (products_attributes_id, products_id, options_id, options_values_id)
							  values (0,
									  '" . (int)$_GET['id'] . "',
									  '" . (int)$_REQUEST['options_id'] . "',
									  '" . (int)$_REQUEST['values_id'] . "'
									  )");

				if (DOWNLOAD_ENABLED == 'true') {
				  $products_attributes_id = $db->Insert_ID();

				  $provider = $db->Execute("SELECT manufacturers_dir FROM " . TABLE_MANUFACTURERS . " WHERE manufacturers_id = " . $_SESSION['provider_id']);
				  $products_attributes_filename = ($provider->fields['manufacturers_dir'] ? $provider->fields['manufacturers_dir'] . '/' : '') . zen_db_prepare_input($_REQUEST['filename']);
				  $products_attributes_maxdays = (int)$_REQUEST['maxdays'];
				  $products_attributes_maxcount = ((int)$_REQUEST['maxcount'] <= 0 ? DOWNLOAD_MAX_COUNT : $_REQUEST['maxcount']);

	//die( 'I am adding ' . strlen($_POST['products_attributes_filename']) . ' vs ' . strlen(trim($_POST['products_attributes_filename'])) . ' vs ' . strlen(zen_db_prepare_input($_POST['products_attributes_filename'])) . ' vs ' . strlen(zen_db_input($products_attributes_filename)) );
				  if (zen_not_null($products_attributes_filename)) {
					$db->Execute("insert into " . TABLE_PRODUCTS_ATTRIBUTES_DOWNLOAD . "
								  (products_attributes_id, products_attributes_filename, products_attributes_maxdays, products_attributes_maxcount)
								  values (" . (int)$products_attributes_id . ",
										  '" . zen_db_input($products_attributes_filename) . "',
										  '" . zen_db_input($products_attributes_maxdays) . "',
										  '" . zen_db_input($products_attributes_maxcount) . "')");
				  }
				  
				  $attributes = $db->Execute("SELECT CONCAT(po.products_options_name, ' ', povpo.products_options_values_name) AS attribute_name, pa.*, pad.*
								FROM " . TABLE_PRODUCTS_ATTRIBUTES . " pa
								JOIN " . TABLE_PRODUCTS_OPTIONS . " po ON (pa.options_id = po.products_options_id)
								JOIN " . TABLE_PRODUCTS_OPTIONS_VALUES . " povpo ON (pa.options_values_id = povpo.products_options_values_id)
								JOIN " . TABLE_PRODUCTS_ATTRIBUTES_DOWNLOAD . " pad ON (pa.products_attributes_id = pad.products_attributes_id)
								WHERE pa.products_id = " . (int)$_GET['id'] . " AND po.language_id = " . $_SESSION['languages_id'] . " AND povpo.language_id = po.language_id
								ORDER BY attribute_name");
				  
				  while(!$attributes->EOF)
				  {
					if (!file_exists(DIR_FS_DOWNLOAD . $attributes->fields['products_attributes_filename']) ) {
						$valid = zen_image(DIR_WS_IMAGES . 'icon_status_red.gif');
					} else {
						$valid = zen_image(DIR_WS_IMAGES . 'icon_status_green.gif');
					}
  
					$return['response'] .= '
					  <tr id="attribute-' . $attributes->fields['products_attributes_id'] . '">
						<td>' . $attributes->fields['attribute_name'] . '</td>
						<td>' . $attributes->fields['products_attributes_filename'] . '</td>
						<td align="center">' . $valid . '</td>
						<td align="center">' . $attributes->fields['products_attributes_maxdays'] . '</td>
						<td align="center">' . $attributes->fields['products_attributes_maxcount'] . '</td>
						<td align="center"><button class="delete" onclick="confirmDelete(' . $attributes->fields['products_attributes_id'] . '); return false;">Delete</button></td>
					  </tr>' . "\n";
					  
					  $attributes->MoveNext();
				  }
				}
			}
			else
			{
				$return['error'] = true;
				$return['response'] = ATTRIBUTE_WARNING_INVALID_MATCH_UPDATE . ' - ' . zen_options_name($_REQUEST['options_id']) . ' : ' . zen_values_name($_REQUEST['values_id']);
			}
		}
		else
		{
			$return['error'] = true;
			$return['response'] = 'This product already have that attribute combination.';
		}
		die(json_encode($return));
	}
	else if ($_GET['action'] == 'DeleteAttribute' && (int)$_GET['id'] > 0)
	{
		$check = $db->Execute("SELECT products_id FROM " . TABLE_PRODUCTS . " WHERE products_id = " . (int)$_GET['pID'] . " AND manufacturers_id = " . $_SESSION['provider_id']);
		if ($check->fields['products_id'])
		{
			$db->Execute("delete from " . TABLE_PRODUCTS_ATTRIBUTES . "
						  where products_attributes_id = '" . (int)$_GET['id'] . "'");

			$db->Execute("delete from " . TABLE_PRODUCTS_ATTRIBUTES_DOWNLOAD . "
						  where products_attributes_id = '" . (int)$_GET['id'] . "'");
		}
	}
	else if ($_GET['action'] == 'ReloadFileList')
	{
		function read_all_files($root = '.', $default = '') {
			if( file_exists($root) ) {
				$files = scandir($root);
				$root = $root . '/';
				natcasesort($files);
				if( count($files) > 2 ) { /* The 2 accounts for . and .. */
					// All dirs
					foreach( $files as $file ) {
						if( file_exists($root . $file) && $file != '.' && $file != '..' && is_dir($root . $file) && $file[0] != '.') {
							$allfiles[] = read_all_files($root . $file, $default);
						}
					}
					
					// All files
					foreach( $files as $file ) {
						if( file_exists($root . $file) && $file != '.' && $file != '..' && !is_dir($root . $file) && $file[0] != '.' ) {
							$ext = preg_replace('/^.*\./', '', $file);
							$allfiles[] = htmlentities(str_replace($default, '', $root) . $file);
						}
					}
				}
			}
			
			return $allfiles;
		}

		function listfiles($files) {
			foreach ($files as $file)
			{
				if (is_array($file))
				{
					$html .= listfiles($file);
				}
				else if (trim($file))
				{
					$file = substr($file, 1);
					$html .= '<option value="' . $file . '">' . $file . '</option>' . "\n";
				}
			}
			
			return $html;
		}
	
	
		$provider = $db->Execute("SELECT manufacturers_dir FROM " . TABLE_MANUFACTURERS . " WHERE manufacturers_id = " . $_SESSION['provider_id']);
		$dir = DIR_FS_DOWNLOAD . $provider->fields['manufacturers_dir'];
		
		$files = read_all_files($dir, $dir);
		// Open a known directory, and proceed to read its contents
		if (count($files) > 0) {		
			$return['response'] = listfiles($files);
		}
		else
		{
			$return['response'] = '<option value="">Incorrect Dir</option>' . "\n";	
		}
	}

	echo $return['response'];
}
die();