<?php
/**
 * @package admin
 * @copyright Copyright 2003-2010 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version $Id: collect_info.php 17947 2010-10-13 20:29:41Z drbyte $
 */
if (!defined('IS_ADMIN_FLAG')) {
  die('Illegal Access');
}
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

$checkfields = $db->metaColumns(TABLE_PRODUCTS);
if (!$checkfields['PRODUCTS_KEYWORDS']->type)
{
	$db->Execute("ALTER TABLE " . TABLE_PRODUCTS . " ADD products_keywords VARCHAR(100) NULL DEFAULT NULL");
}

if ($_SESSION['provider_id']) $manufacturers_id = $_SESSION['provider_id'];

$parameters = array('products_name' => '',
                    'products_description' => '',
                    'products_url' => '',
                    'products_id' => '',
                    'products_quantity' => DEFAULT_PRODUCTS_QTY,
                    'products_model' => '',
                    'products_image' => '',
                    'products_price' => '',
                    'products_virtual' => DEFAULT_PRODUCT_PRODUCTS_VIRTUAL,
                    'products_weight' => '',
                    'products_date_added' => '',
                    'products_last_modified' => '',
                    'products_date_available' => '',
                    'products_status' => '',
                    'products_tax_class_id' => DEFAULT_PRODUCT_TAX_CLASS_ID,
                    'manufacturers_id' => $manufacturers_id,
                    'products_quantity_order_min' => '',
                    'products_quantity_order_units' => '',
                    'products_priced_by_attribute' => '',
                    'product_is_free' => '',
                    'product_is_call' => '',
                    'products_quantity_mixed' => '',
                    'product_is_always_free_shipping' => '',
                    'products_qty_box_status' => PRODUCTS_QTY_BOX_STATUS,
                    'products_quantity_order_max' => DEFAULT_PRODUCTS_QTY_MAX,
                    'products_sort_order' => '0',
                    'products_discount_type' => '0',
                    'products_discount_type_from' => '0',
                    'products_price_sorter' => '0',
                    'master_categories_id' => '',
                    'products_keywords' => '',
                    );

$pInfo = new objectInfo($parameters);

if (isset($_GET['pID']) && empty($_POST)) {
	$product = $db->Execute("select pd.products_name, pd.products_description, pd.products_url,
                                      p.products_id, p.products_quantity, p.products_model,
                                      p.products_image, p.products_price, p.products_virtual, p.products_weight,
                                      p.products_date_added, p.products_last_modified,
                                      date_format(p.products_date_available, '%Y-%m-%d') as
                                      products_date_available, p.products_status, p.products_tax_class_id,
                                      p.manufacturers_id,
                                      p.products_quantity_order_min, p.products_quantity_order_units, p.products_priced_by_attribute,
                                      p.product_is_free, p.product_is_call, p.products_quantity_mixed,
                                      p.product_is_always_free_shipping, p.products_qty_box_status, p.products_quantity_order_max,
                                      p.products_sort_order,
                                      p.products_discount_type, p.products_discount_type_from,
                                      p.products_price_sorter, p.master_categories_id, p.products_keywords, p.product_on_queue
                              from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd
                              where p.products_id = '" . (int)$_GET['pID'] . "'
                              and p.products_id = pd.products_id
                              and pd.language_id = '" . (int)$_SESSION['languages_id'] . "'");

      $pInfo->objectInfo($product->fields);
    } elseif (zen_not_null($_POST)) {
      $pInfo->objectInfo($_POST);
      $products_name = $_POST['products_name'];
      $products_description = $_POST['products_description'];
      $products_url = $_POST['products_url'];
    }

if ($_SESSION['provider_id'])
{
	$manufacturers = $db->Execute("select manufacturers_id, manufacturers_name
									   from " . TABLE_MANUFACTURERS . " where manufacturers_id = '". $_SESSION['provider_id'] ."' order by manufacturers_name");

	$manufacturers_array[] = array('id' => $manufacturers->fields['manufacturers_id'],
								   'text' => $manufacturers->fields['manufacturers_name']);
}
else
{
	$manufacturers_array = array(array('id' => '', 'text' => TEXT_NONE));
	$manufacturers = $db->Execute("select manufacturers_id, manufacturers_name
	                                   from " . TABLE_MANUFACTURERS . " order by manufacturers_name");
	while (!$manufacturers->EOF) {
		$manufacturers_array[] = array('id' => $manufacturers->fields['manufacturers_id'],
		                               'text' => $manufacturers->fields['manufacturers_name']);
		$manufacturers->MoveNext();
	}
}



    $tax_class_array = array(array('id' => '0', 'text' => TEXT_NONE));
    $tax_class = $db->Execute("select tax_class_id, tax_class_title
                                     from " . TABLE_TAX_CLASS . " order by tax_class_title");
    while (!$tax_class->EOF) {
      $tax_class_array[] = array('id' => $tax_class->fields['tax_class_id'],
                                 'text' => $tax_class->fields['tax_class_title']);
      $tax_class->MoveNext();
    }

    $languages = zen_get_languages();

    if (!isset($pInfo->products_status)) $pInfo->products_status = '1';
    switch ($pInfo->products_status) {
      case '0': $in_status = false; $out_status = true; break;
      case '1':
      default: $in_status = true; $out_status = false;
        break;
    }
// set to out of stock if categories_status is off and new product or existing products_status is off
    if (zen_get_categories_status($current_category_id) == '0' and $pInfo->products_status != '1') {
      $pInfo->products_status = 0;
      $in_status = false;
      $out_status = true;
    }

// Virtual Products
    if (!isset($pInfo->products_virtual)) $pInfo->products_virtual = PRODUCTS_VIRTUAL_DEFAULT;
    switch ($pInfo->products_virtual) {
      case '0': $is_virtual = false; $not_virtual = true; break;
      case '1': $is_virtual = true; $not_virtual = false; break;
      default: $is_virtual = false; $not_virtual = true;
    }
// Always Free Shipping
    if (!isset($pInfo->product_is_always_free_shipping)) $pInfo->product_is_always_free_shipping = DEFAULT_PRODUCT_PRODUCTS_IS_ALWAYS_FREE_SHIPPING;
    switch ($pInfo->product_is_always_free_shipping) {
      case '0': $is_product_is_always_free_shipping = false; $not_product_is_always_free_shipping = true; $special_product_is_always_free_shipping = false; break;
      case '1': $is_product_is_always_free_shipping = true; $not_product_is_always_free_shipping = false; $special_product_is_always_free_shipping = false; break;
      case '2': $is_product_is_always_free_shipping = false; $not_product_is_always_free_shipping = false; $special_product_is_always_free_shipping = true; break;
      default: $is_product_is_always_free_shipping = false; $not_product_is_always_free_shipping = true; $special_product_is_always_free_shipping = false;
    }
// products_qty_box_status shows
    if (!isset($pInfo->products_qty_box_status)) $pInfo->products_qty_box_status = PRODUCTS_QTY_BOX_STATUS;
    switch ($pInfo->products_qty_box_status) {
      case '0': $is_products_qty_box_status = false; $not_products_qty_box_status = true; break;
      case '1': $is_products_qty_box_status = true; $not_products_qty_box_status = false; break;
      default: $is_products_qty_box_status = true; $not_products_qty_box_status = false;
    }
// Product is Priced by Attributes
    if (!isset($pInfo->products_priced_by_attribute)) $pInfo->products_priced_by_attribute = '0';
    switch ($pInfo->products_priced_by_attribute) {
      case '0': $is_products_priced_by_attribute = false; $not_products_priced_by_attribute = true; break;
      case '1': $is_products_priced_by_attribute = true; $not_products_priced_by_attribute = false; break;
      default: $is_products_priced_by_attribute = false; $not_products_priced_by_attribute = true;
    }
// Product is Free
    if (!isset($pInfo->product_is_free)) $pInfo->product_is_free = '0';
    switch ($pInfo->product_is_free) {
      case '0': $in_product_is_free = false; $out_product_is_free = true; break;
      case '1': $in_product_is_free = true; $out_product_is_free = false; break;
      default: $in_product_is_free = false; $out_product_is_free = true;
    }
// Product is Call for price
    if (!isset($pInfo->product_is_call)) $pInfo->product_is_call = '0';
    switch ($pInfo->product_is_call) {
      case '0': $in_product_is_call = false; $out_product_is_call = true; break;
      case '1': $in_product_is_call = true; $out_product_is_call = false; break;
      default: $in_product_is_call = false; $out_product_is_call = true;
    }
// Products can be purchased with mixed attributes retail
    if (!isset($pInfo->products_quantity_mixed)) $pInfo->products_quantity_mixed = '0';
    switch ($pInfo->products_quantity_mixed) {
      case '0': $in_products_quantity_mixed = false; $out_products_quantity_mixed = true; break;
      case '1': $in_products_quantity_mixed = true; $out_products_quantity_mixed = false; break;
      default: $in_products_quantity_mixed = true; $out_products_quantity_mixed = false;
    }

// set image overwrite
  $on_overwrite = true;
  $off_overwrite = false;
// set image delete
  $on_image_delete = false;
  $off_image_delete = true;
?>
<link rel="stylesheet" type="text/css" href="includes/javascript/spiffyCal/spiffyCal_v2_1.css">
<script language="JavaScript" src="includes/javascript/spiffyCal/spiffyCal_v2_1.js"></script>
<script language="javascript"><!--
  var dateAvailable = new ctlSpiffyCalendarBox("dateAvailable", "new_product", "products_date_available","btnDate1","<?php echo $pInfo->products_date_available; ?>",scBTNMODE_CUSTOMBLUE);
//--></script>
<script language="javascript"><!--
function toggle(divid) {
	var div = document.getElementById(divid);
	if (div.style.display == 'none')
	{
		div.style.display = 'block';
	}
	else
	{
		div.style.display = 'none';
	}
}
//-->
</script>
<script language="javascript"><!--
var tax_rates = new Array();
<?php
    for ($i=0, $n=sizeof($tax_class_array); $i<$n; $i++) {
      if ($tax_class_array[$i]['id'] > 0) {
        echo 'tax_rates["' . $tax_class_array[$i]['id'] . '"] = ' . zen_get_tax_rate_value($tax_class_array[$i]['id']) . ';' . "\n";
      }
    }
?>

function doRound(x, places) {
  return Math.round(x * Math.pow(10, places)) / Math.pow(10, places);
}

function getTaxRate() {
  var selected_value = document.forms["new_product"].products_tax_class_id.selectedIndex;
  var parameterVal = document.forms["new_product"].products_tax_class_id[selected_value].value;

  if ( (parameterVal > 0) && (tax_rates[parameterVal] > 0) ) {
    return tax_rates[parameterVal];
  } else {
    return 0;
  }
}

function updateGross() {
  var taxRate = getTaxRate();
  var grossValue = document.forms["new_product"].products_price.value;

  if (taxRate > 0) {
    grossValue = grossValue * ((taxRate / 100) + 1);
  }

  document.forms["new_product"].products_price_gross.value = doRound(grossValue, 4);
}

function updateNet() {
  var taxRate = getTaxRate();
  var netValue = document.forms["new_product"].products_price_gross.value;

  if (taxRate > 0) {
    netValue = netValue / ((taxRate / 100) + 1);
  }

  document.forms["new_product"].products_price.value = doRound(netValue, 4);
}
//--></script>
    <?php
//  echo $type_admin_handler;
echo zen_draw_form('new_product', $type_admin_handler , 'cPath=' . $cPath . (isset($_GET['product_type']) ? '&product_type=' . $_GET['product_type'] : '') . (isset($_GET['pID']) ? '&pID=' . $_GET['pID'] : '') . '&action=new_product_preview' . (isset($_GET['page']) ? '&page=' . $_GET['page'] : '') . ( (isset($_GET['search']) && !empty($_GET['search'])) ? '&search=' . $_GET['search'] : '') . ( (isset($_POST['search']) && !empty($_POST['search']) && empty($_GET['search'])) ? '&search=' . $_POST['search'] : ''), 'post', 'enctype="multipart/form-data"');
    
// hidden fields not changeable on products page
echo zen_draw_hidden_field('master_categories_id', $pInfo->master_categories_id);
echo zen_draw_hidden_field('products_discount_type', $pInfo->products_discount_type);
echo zen_draw_hidden_field('products_discount_type_from', $pInfo->products_discount_type_from);
echo zen_draw_hidden_field('products_price_sorter', $pInfo->products_price_sorter);
?>
    <table border="0" width="100%" cellspacing="0" cellpadding="2">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo sprintf(TEXT_NEW_PRODUCT, zen_get_category_name($current_category_id, $_SESSION['languages_id'])); ?></td>
            <td class="pageHeading" align="right"><?php echo zen_draw_separator('pixel_trans.gif', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo zen_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>
      <tr>
        <td class="main" align="right"><?php echo zen_draw_hidden_field('products_date_added', (zen_not_null($pInfo->products_date_added) ? $pInfo->products_date_added : date('Y-m-d'))) . zen_image_submit('button_preview.gif', IMAGE_PREVIEW) . '&nbsp;&nbsp;<a href="' . zen_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . (isset($_GET['pID']) ? '&pID=' . $_GET['pID'] : '') . (isset($_GET['page']) ? '&page=' . $_GET['page'] : '') . ( (isset($_GET['search']) && !empty($_GET['search'])) ? '&search=' . $_GET['search'] : '') . ( (isset($_POST['search']) && !empty($_POST['search']) && empty($_GET['search'])) ? '&search=' . $_POST['search'] : '')) . '">' . zen_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>'; ?></td>
      </tr>
      <tr>
        <td><table border="0" cellspacing="0" cellpadding="2">
<?php
// show when product is linked
if (zen_get_product_is_linked($_GET['pID']) == 'true' and $_GET['pID'] > 0) {
?>
          <tr>
            <td class="main"><?php echo TEXT_MASTER_CATEGORIES_ID; ?></td>
            <td class="main">
              <?php
                // echo zen_draw_pull_down_menu('products_tax_class_id', $tax_class_array, $pInfo->products_tax_class_id);
                echo zen_image(DIR_WS_IMAGES . 'icon_yellow_on.gif', IMAGE_ICON_LINKED) . '&nbsp;&nbsp;';
                echo zen_draw_pull_down_menu('master_category', zen_get_master_categories_pulldown($_GET['pID']), $pInfo->master_categories_id); ?>
            </td>
          </tr>
<?php } else { ?>
          <tr>
            <td class="main"><?php echo TEXT_MASTER_CATEGORIES_ID; ?></td>
            <td class="main"><?php echo TEXT_INFO_ID . ($_GET['pID'] > 0 ? $pInfo->master_categories_id  . ' ' . zen_get_category_name($pInfo->master_categories_id, $_SESSION['languages_id']) : $current_category_id  . ' ' . zen_get_category_name($current_category_id, $_SESSION['languages_id'])); ?></td>
          </tr>
<?php } ?>
          <tr>
            <td colspan="2" class="main"><?php echo TEXT_INFO_MASTER_CATEGORIES_ID; ?></td>
          </tr>
          <tr>
            <td colspan="2"><?php echo zen_draw_separator('pixel_trans.gif', '100%', '2'); ?></td>
          </tr>
          <tr>
            <td colspan="2"><?php echo zen_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
          <tr>
            <td colspan="2"><?php echo zen_draw_separator('pixel_black.gif', '100%', '3'); ?></td>
          </tr>
          <tr>
            <td colspan="2"><?php echo zen_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>

          <tr class="productBar">
            <td class="main" style="color: #FFFFFF; font-weight: bold;">PRODUCT INFORMATION:</td>
			</tr>
			<tr>
            <td colspan="2"><?php echo zen_draw_separator('pixel_trans.gif', '100%', '2'); ?></td>
          </tr>
          <tr>
            <td colspan="2" class="main" align="center"><?php echo (zen_get_categories_status($current_category_id) == '0' ? TEXT_CATEGORIES_STATUS_INFO_OFF : '') . ($out_status == true ? ' ' . TEXT_PRODUCTS_STATUS_INFO_OFF : ''); ?></td>
          <tr>

          <tr>
            <td class="main"><?php echo TEXT_PRODUCTS_STATUS; ?></td>
			<?php if ($pInfo->product_on_queue == 1) { ?>
			<td class="main"><?php echo ' ' . TEXT_PRODUCT_ON_QUEUE;?>
			<?php } else { ?>
            <td class="main"><?php echo zen_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . zen_draw_radio_field('products_status', '1', $in_status) . '&nbsp;' . TEXT_PRODUCT_AVAILABLE . '&nbsp;' . zen_draw_radio_field('products_status', '0', $out_status) . '&nbsp;' . TEXT_PRODUCT_NOT_AVAILABLE; ?></td>
			<?php } ?>
          </tr>
<?php
for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
?>
          <tr>
            <td class="main"><?php if ($i == 0) echo TEXT_PRODUCTS_NAME; ?></td>
            <td class="main"><?php echo zen_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']) . '&nbsp;' . zen_draw_input_field('products_name[' . $languages[$i]['id'] . ']', (isset($products_name[$languages[$i]['id']]) ? stripslashes($products_name[$languages[$i]['id']]) : zen_get_products_name($pInfo->products_id, $languages[$i]['id'])), zen_set_field_length(TABLE_PRODUCTS_DESCRIPTION, 'products_name')); ?></td>
          </tr>
<?php
}
?>
          <tr>
            <td class="main"><?php echo TEXT_PRODUCTS_MODEL; ?></td>
            <td class="main"><?php echo zen_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . zen_draw_input_field('products_model', $pInfo->products_model, zen_set_field_length(TABLE_PRODUCTS, 'products_model')); ?></td>
          </tr>
          <tr>
            <td class="main"><?php echo TEXT_PRODUCTS_MANUFACTURER; ?></td>
            <?php if ($_SESSION['provider_id'])
            {
            ?>
            <td class="main"><?php echo zen_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . $_SESSION['provider_name']; ?></td>
			<?php
}
else
{
?>
			<td class="main"><?php echo zen_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . zen_draw_pull_down_menu('manufacturers_id', $manufacturers_array, $pInfo->manufacturers_id); ?></td>
				<?php
}
?>
          </tr>
          <tr>
            <td class="main"><?php echo TEXT_PRODUCTS_KEYWORDS; ?></td>
            <td class="main"><?php echo zen_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . zen_draw_input_field('products_keywords', $pInfo->products_keywords); ?></td>
          </tr>
		  <tr>
            <td class="main"><?php echo TEXT_PRODUCT_IS_FREE; ?></td>
            <td class="main"><?php echo zen_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . zen_draw_radio_field('product_is_free', '1', ($in_product_is_free==1)) . '&nbsp;' . TEXT_YES . '&nbsp;&nbsp;' . zen_draw_radio_field('product_is_free', '0', ($in_product_is_free==0)) . '&nbsp;' . TEXT_NO . ' ' . ($pInfo->product_is_free == 1 ? '<span class="errorText">' . TEXT_PRODUCTS_IS_FREE_EDIT . '</span>' : ''); ?></td>
          </tr>
          <tr>
            <td class="main"><?php echo TEXT_PRODUCTS_TAX_CLASS; ?></td>
            <td class="main"><?php echo zen_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . zen_draw_pull_down_menu('products_tax_class_id', $tax_class_array, $pInfo->products_tax_class_id, 'onchange="updateGross()"'); ?></td>
          </tr>
          <tr>
            <td class="main"><?php echo TEXT_PRODUCTS_PRICE_NET; ?></td>
            <td class="main"><?php echo zen_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . zen_draw_input_field('products_price', $pInfo->products_price, 'onKeyUp="updateGross()"'); ?></td>
          </tr>
          <tr>
            <td class="main"><?php echo TEXT_PRODUCTS_PRICE_GROSS; ?></td>
            <td class="main"><?php echo zen_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . zen_draw_input_field('products_price_gross', $pInfo->products_price, 'OnKeyUp="updateNet()"'); ?></td>
          </tr>
<?php
for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
?>
          <tr>
            <td class="main" valign="top"><?php if ($i == 0) echo TEXT_PRODUCTS_DESCRIPTION; ?></td>
            <td colspan="2"><table border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td class="main" width="25" valign="top"><?php echo zen_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']); ?>&nbsp;</td>
                <td class="main" width="100%">
        <?php
		echo zen_draw_textarea_field('products_description[' . $languages[$i]['id'] . ']', 'soft', '100%', '20', (isset($products_description[$languages[$i]['id']])) ? stripslashes($products_description[$languages[$i]['id']]) : zen_get_products_description($pInfo->products_id, $languages[$i]['id'])); //,'id="'.'products_description' . $languages[$i]['id'] . '"');
		?>
        </td>
              </tr>
            </table></td>
          </tr>
<?php
    }
?>
          <tr>
            <td colspan="2"><?php echo zen_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
<?php
  $dir = @dir(DIR_FS_CATALOG_IMAGES);
  $dir_info[] = array('id' => '', 'text' => "Main Directory");
  while ($file = $dir->read()) {
    if (is_dir(DIR_FS_CATALOG_IMAGES . $file) && strtoupper($file) != 'CVS' && $file != "." && $file != "..") {
      $dir_info[] = array('id' => $file . '/', 'text' => $file);
    }
  }
  $dir->close();
  sort($dir_info);
  
  $default_directory = substr( $pInfo->products_image, 0,strpos( $pInfo->products_image, '/')+1);
  if (empty($default_directory))
	$default_directory = $provider->fields['manufacturers_image_dir'] . '/';
?>
		</table></td>
          </tr>
          <tr class="productBar">
            <td class="main" style="color: #FFFFFF; font-weight: bold;">PRODUCT IMAGE INFORMATION:</td>
		  </tr>
          <tr>
            <td colspan="2"><?php echo zen_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>

          <tr>
            <td class="main" colspan="2"><table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td class="main"><?php echo TEXT_PRODUCTS_IMAGE; ?></td>
                <td class="main"><?php echo zen_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . zen_draw_file_field('products_image') . '&nbsp;' . ($pInfo->products_image !='' ? TEXT_IMAGE_CURRENT . $pInfo->products_image : TEXT_IMAGE_CURRENT . '&nbsp;' . NONE) . zen_draw_hidden_field('products_previous_image', $pInfo->products_image); ?></td>
                <td valign = "center" class="main"><?php echo TEXT_PRODUCTS_IMAGE_DIR; ?>&nbsp;<?php echo zen_draw_pull_down_menu('img_dir', $dir_info, $default_directory); ?></td>
						  </tr>
              <tr>
                <td class="main"><?php echo zen_draw_separator('pixel_trans.gif', '24', '15'); ?></td>
                <td class="main" valign="top"><?php echo TEXT_IMAGES_DELETE . ' ' . zen_draw_radio_field('image_delete', '0', $off_image_delete) . '&nbsp;' . TABLE_HEADING_NO . ' ' . zen_draw_radio_field('image_delete', '1', $on_image_delete) . '&nbsp;' . TABLE_HEADING_YES; ?></td>
	  	    	  </tr>

              <tr>
                <td class="main"><?php echo zen_draw_separator('pixel_trans.gif', '24', '15'); ?></td>
                <td colspan="3" class="main" valign="top"><?php echo TEXT_IMAGES_OVERWRITE  . ' ' . zen_draw_radio_field('overwrite', '0', $off_overwrite) . '&nbsp;' . TABLE_HEADING_NO . ' ' . zen_draw_radio_field('overwrite', '1', $on_overwrite) . '&nbsp;' . TABLE_HEADING_YES; ?>
                  <?php echo '<br />' . TEXT_PRODUCTS_IMAGE_MANUAL . '&nbsp;' . zen_draw_input_field('products_image_manual'); ?></td>
              </tr>
            </table></td>
          </tr>
          <tr>
            <td colspan="2"><?php echo zen_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
          <tr>
            <td colspan="2"><?php echo zen_draw_separator('pixel_black.gif', '100%', '3'); ?></td>
          </tr>
          <tr>
            <td colspan="2"><?php echo zen_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
          <tr class="productBar">
            <td class="main" style="color: #FFFFFF; font-weight: bold;">PRODUCT ATTRIBUTES:</td>
		  </tr>
          <tr>
            <td colspan="2"><?php echo zen_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
<?php if((int)$_GET['pID'] > 0) { ?>
<script type="text/javascript">
function activateLoading(status)
{
	if (status == true)
	{
		$('.loading').fadeIn('fast', function() {
			return true;
		});
	}
	else
	{
		$('.loading').fadeOut('fast', function() {
			return true;
		});
	}
}
function confirmDelete(id) {
	var response = confirm('Are you sure you want to delete?');
	if (response == true)
	{
		$('#attribute-' + id).animate({ opacity: 0.25 }, 1);
		$('#attribute-' + id).find('.delete').attr('disabled', 'disabled');
		activateLoading(true);
		
		$.ajax({
			url: 'ajax.php?action=DeleteAttribute&pID=<?php echo $_GET['pID']; ?>&id=' + id + '&key=<?php echo $_SESSION['securityToken']; ?>',
			success: function() {
				$('#attribute-' + id).fadeOut('fast', function() {
					activateLoading(false);
					$(this).remove();
				});
			}
		});
	}
	
	return false;
}

$(function() {
	$('.delete').live('click', function(e) {
		e.preventDefault();
	});
	
	$('.values_id').live('click', function() {
		var option = $('.options_id').val();
		var value = $(this).val();
		if (option != '' && option != undefined && value != '' && value != undefined) {
			activateLoading(false);
			$('.confirm').show();
		}
	});
	
	$('.options_id').live('click', function() {
	
		$('.confirm').hide();
		$('.optionsval').fadeOut('fast', function() {
			activateLoading(true);
		});

		var id = $(this).val();
		$.ajax({
			url: 'ajax.php?action=OptionsValues&id=' + id + '&key=<?php echo $_SESSION['securityToken']; ?>',
			dataType: 'html',
			success: function(html) {
				activateLoading(false);
				
				$('.optionsval').html(html);
				$('.optionsval').fadeIn('normal', function(){
					$('.optionsval').css('display', 'inline');
				});
			}
		});
	});
	
	$('#insert').live('click', function() {
		var option = $('.options_id').val();
		var value = $('.values_id').val();
		var file = $('.filename').val();
		var maxd = $('.maxdays').val();
		var maxc = $('.maxcount').val();
		
		$('.confirm').fadeOut('fast');
		activateLoading(true);
		
		if (option == '' || value == '' || file == '' || file == null)
		{
			activateLoading(false);
			alert('Please, choose a option, value and a filename');
			$('.confirm').fadeIn('fast');
		}
		else
		{
			$.ajax({
				type: 'POST',
				data: { options_id: option, values_id: value, filename: file, maxcount: maxc, maxdays: maxd},
				url: 'ajax.php?action=InsertAttributes&id=<?php echo $_GET['pID']; ?>&key=<?php echo $_SESSION['securityToken']; ?>',
				dataType: 'json',
				success: function(response) {
					if (response.error == true)
					{
						alert(response.response);
						$('.values_id').val('');
					}
					else
					{
						$('.values_id').val('');
						$('#attributesList').fadeOut('fast', function() {
							$('#attributesList tbody').html(response.response);
							$(this).fadeIn();
						});
					}
					activateLoading(false);
				}
			});
		}
	});
	
	$('#reloadFileList').live('click', function() {
		activateLoading(true);
		
		$.ajax({
			url: 'ajax.php?action=ReloadFileList&key=<?php echo $_SESSION['securityToken']; ?>',
			dataType: 'html',
			success: function(html) {
			
				$('.filename').fadeOut('fast', function() {
					$('.filename').html(html);
					$(this).fadeIn('fast');
				});
				
				activateLoading(false);
			}
		});
	});
});
</script>
          <tr>
            <td class="main" colspan="2">
				<div class="attribute">
					<div class="list">
						<table border="1" width="100%" id="attributesList">
							<thead style="font-weight:bold">
								<tr>
									<td>Attribute</td>
									<td>Filename</td>
									<td align="center">Valid</td>
									<td align="center">Max Days</td>
									<td align="center">Max Download</td>
									<td align="center">Action</td>
								</tr>
							</thead>
							<tbody>
							<?php
							
$attributes = $db->Execute("SELECT CONCAT(po.products_options_name, ' ', povpo.products_options_values_name) AS attribute_name, pad.*, pa.*
							FROM " . TABLE_PRODUCTS_ATTRIBUTES . " pa
							JOIN " . TABLE_PRODUCTS_OPTIONS . " po ON (pa.options_id = po.products_options_id)
							JOIN " . TABLE_PRODUCTS_OPTIONS_VALUES . " povpo ON (pa.options_values_id = povpo.products_options_values_id)
							LEFT JOIN " . TABLE_PRODUCTS_ATTRIBUTES_DOWNLOAD . " pad ON (pa.products_attributes_id = pad.products_attributes_id)
							WHERE pa.products_id = " . (int)$_GET['pID'] . " AND po.language_id = " . $_SESSION['languages_id'] . " AND povpo.language_id = po.language_id
							ORDER BY attribute_name");
			  
							  while(!$attributes->EOF)
							  {
								if (!file_exists(DIR_FS_DOWNLOAD . $attributes->fields['products_attributes_filename']) || $attributes->fields['products_attributes_filename'] == '') {
									$valid = zen_image(DIR_WS_IMAGES . 'icon_status_red.gif');
								} else {
									$valid = zen_image(DIR_WS_IMAGES . 'icon_status_green.gif');
								}
								
								echo '
								  <tr id="attribute-' . $attributes->fields['products_attributes_id'] . '">
									<td>' . $attributes->fields['attribute_name'] . '</td>
									<td>' . ($attributes->fields['products_attributes_filename'] ? $attributes->fields['products_attributes_filename'] : 'No Filename') . '</td>
									<td align="center">' . $valid . '</td>
									<td align="center">' . ($attributes->fields['products_attributes_maxdays'] ? $attributes->fields['products_attributes_maxdays'] : '&nbsp;') . '</td>
									<td align="center">' . ($attributes->fields['products_attributes_maxcount'] ? $attributes->fields['products_attributes_maxcount'] : '&nbsp;') . '</td>
									<td align="center"><button class="delete" onclick="confirmDelete(' . $attributes->fields['products_attributes_id'] . '); return false;">Delete</button></td>
								  </tr>' . "\n";
								  
								  $attributes->MoveNext();
							  }
							?>
							</tbody>
						</table>
						<br />
					</div>
					<table cellpadding="0" cellspacing="2">
						<thead>
							<tr>
								<td colspan="4">
									Expiry days: (0 = unlimited): <input type="text" name="maxdays" class="maxdays" size="5" value="<?php echo DOWNLOAD_MAX_DAYS; ?>" />&nbsp;&nbsp;&nbsp;
									Maximum download count: <input type="text" name="maxcount" class="maxcount" size="5" value="<?php echo DOWNLOAD_MAX_COUNT; ?>" />
								</td>
							</tr>
							<tr>
								<td colspan="4">&nbsp;</td>
							</tr>
							<tr>
								<td>
								<?php if (trim($provider->fields['manufacturers_dir'])) { ?>
								<a href="#" id="reloadFileList" onclick="return false">[reload file list]</a>
								<?php } ?>
								</td>
								<td>
									Option Name
								</td>
								<td colspan="2">
									Option Value
								</td>
							</tr>
						<thead>
						<tbody>
							<tr>
								<td>
								<?php
								if (trim($provider->fields['manufacturers_dir']))
								{
								?>
									<select name="filename" class="filename" size="15">
									<?php
										$dir = DIR_FS_DOWNLOAD . $provider->fields['manufacturers_dir'];
										echo listfiles(read_all_files($dir, $dir));
									?>
									</select>
								<?php
									} else {
								?>
								Filename: <input type="text" size="50" name="filename" class="filename" /><br />
								<dfn>
									Ask your administrator to set your download dir,<br />so you can select the filename from a dropdown.
								</dfn>
								<?php
									}
								?>
								</td>
								<td>
						<select name="options_id" class="options_id" size="15">
						<?

	//iii 031103 added to get results from database option type query
	  $products_options_types_list = array();
	//  $products_options_type_array = $db->Execute("select products_options_types_id, products_options_types_name from " . TABLE_PRODUCTS_OPTIONS_TYPES . " where language_id='" . $_SESSION['languages_id'] . "' order by products_options_types_id");
	  $products_options_type_array = $db->Execute("select products_options_types_id, products_options_types_name from " . TABLE_PRODUCTS_OPTIONS_TYPES . " order by products_options_types_id");
	  while (!$products_options_type_array->EOF) {
		$products_options_types_list[$products_options_type_array->fields['products_options_types_id']] = $products_options_type_array->fields['products_options_types_name'];
		$products_options_type_array->MoveNext();
	  }
	  
	function draw_optiontype_pulldown($name, $default = '') {
	  global $products_options_types_list;
	  $values = array();
	  foreach ($products_options_types_list as $id => $text) {
		$values[] = array('id' => $id, 'text' => $text);
	  }
	  return zen_draw_pull_down_menu($name, $values, $default);
	}

	function translate_type_to_name($opt_type) {
		global $products_options_types_list;
		return $products_options_types_list[$opt_type];
	}
						
							$options_values = $db->Execute("select * from " . TABLE_PRODUCTS_OPTIONS . "
										where language_id = '" . $_SESSION['languages_id'] . "'
										order by products_options_sort_order, products_options_name");

							while (!$options_values->EOF) {
							  echo '              <option name="' . $options_values->fields['products_options_name'] . '" value="' . $options_values->fields['products_options_id'] . '">' . $options_values->fields['products_options_name'] . '&nbsp;&nbsp;&nbsp;[' . translate_type_to_name($options_values->fields['products_options_type']) . ']' . ($show_name_numbers ? ' &nbsp; [ #' . $options_values->fields['products_options_id'] . ' ] ' : '' ) . '</option>' . "\n";
							  $options_values->MoveNext();
							}									
						?>
									</select>
								</td>
								<td>
									<div class="optionsval"></div>
								</td>
								<td valign="top">
									<div class="confirm" style="display: none;">
										<button id="insert" name="insert" onclick="return false">Insert</button>
									</div>
									<img src="images/ajax-loader.gif" class="loading" alt="" style="display:none" />
								</td>
							</tr>
						</tbody>
					</table>
				</div>

			</td>
          </tr>
<?php } else { ?>
			<tr>
            <td class="main" colspan="2">
			You must save the product first.
			</td>
          </tr>
<?php } ?>
          <tr>
            <td colspan="2"><?php echo zen_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
          <tr>
            <td colspan="2"><?php echo zen_draw_separator('pixel_black.gif', '100%', '3'); ?></td>
          </tr>
          <tr>
            <td colspan="2"><?php echo zen_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
          <tr>
            <td class="main" colspan="2">
		<table border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td colspan="2">
			<fieldset>
				<legend>Additional Information</legend>
				<input type="button" name="button" value="Click here to show/hide additional information for this product" onClick="toggle('addinfo')" />
			</fieldset>
			</td>
          </tr>
		  </table>
		  </td>
		  </tr>
          <tr>
            <td class="main" colspan="2"><div id="addinfo" style="display: none;">
		<table border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td colspan="2"><?php echo zen_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
		  <tr>
            <td colspan="2"><?php echo zen_draw_separator('pixel_black.gif', '100%', '3'); ?></td>
          </tr>
          <tr>
            <td colspan="2"><?php echo zen_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
          <tr class="productBar">
            <td class="main" style="color: #FFFFFF; font-weight: bold;">ADDITIONAL PRODUCT INFORMATION:</td>
			</tr>
          <tr>
            <td colspan="2"><?php echo zen_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
<?php
    for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
?>
          <tr>
            <td class="main"><?php if ($i == 0) echo TEXT_PRODUCTS_URL . '<br /><small>' . TEXT_PRODUCTS_URL_WITHOUT_HTTP . '</small>'; ?></td>
            <td class="main"><?php echo zen_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']) . '&nbsp;' . zen_draw_input_field('products_url[' . $languages[$i]['id'] . ']', (isset($products_url[$languages[$i]['id']]) ? $products_url[$languages[$i]['id']] : zen_get_products_url($pInfo->products_id, $languages[$i]['id'])), zen_set_field_length(TABLE_PRODUCTS_DESCRIPTION, 'products_url')); ?></td>
          </tr>
<?php
    }
?>
          <tr>
            <td colspan="2"><?php echo zen_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
          <tr>
            <td class="main"><?php echo TEXT_PRODUCTS_QUANTITY; ?></td>
            <td class="main"><?php echo zen_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . zen_draw_input_field('products_quantity', $pInfo->products_quantity); ?></td>
          </tr>
          <tr>
            <td class="main"><?php echo TEXT_PRODUCTS_DATE_AVAILABLE; ?><br /><small>(YYYY-MM-DD)</small></td>
            <td class="main"><?php echo zen_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;'; ?><script language="javascript">dateAvailable.writeControl(); dateAvailable.dateFormat="yyyy-MM-dd";</script></td>
          </tr>
          <tr>
            <td class="main"><?php echo TEXT_PRODUCT_IS_CALL; ?></td>
            <td class="main"><?php echo zen_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . zen_draw_radio_field('product_is_call', '1', ($in_product_is_call==1)) . '&nbsp;' . TEXT_YES . '&nbsp;&nbsp;' . zen_draw_radio_field('product_is_call', '0', ($in_product_is_call==0)) . '&nbsp;' . TEXT_NO . ' ' . ($pInfo->product_is_call == 1 ? '<span class="errorText">' . TEXT_PRODUCTS_IS_CALL_EDIT . '</span>' : ''); ?></td>
          </tr>
          <tr>
            <td class="main"><?php echo TEXT_PRODUCTS_PRICED_BY_ATTRIBUTES; ?></td>
            <td class="main"><?php echo zen_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . zen_draw_radio_field('products_priced_by_attribute', '1', $is_products_priced_by_attribute) . '&nbsp;' . TEXT_PRODUCT_IS_PRICED_BY_ATTRIBUTE . '&nbsp;&nbsp;' . zen_draw_radio_field('products_priced_by_attribute', '0', $not_products_priced_by_attribute) . '&nbsp;' . TEXT_PRODUCT_NOT_PRICED_BY_ATTRIBUTE . ' ' . ($pInfo->products_priced_by_attribute == 1 ? '<span class="errorText">' . TEXT_PRODUCTS_PRICED_BY_ATTRIBUTES_EDIT . '</span>' : ''); ?></td>
          </tr>
          <tr>
            <td colspan="2"><?php echo zen_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
          <tr>
            <td colspan="2"><?php echo zen_draw_separator('pixel_black.gif', '100%', '3'); ?></td>
          </tr>
          <tr>
            <td colspan="2"><?php echo zen_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
          <tr class="productBar">
            <td class="main" style="color: #FFFFFF; font-weight: bold;">PRODUCT SHIPPING INFORMATION:</td>
			</tr>
          <tr>
            <td colspan="2"><?php echo zen_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
          <tr>
            <td class="main"><?php echo TEXT_PRODUCTS_WEIGHT; ?></td>
            <td class="main"><?php echo zen_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . zen_draw_input_field('products_weight', $pInfo->products_weight); ?></td>
          </tr>
          <tr>
            <td class="main"><?php echo TEXT_PRODUCTS_VIRTUAL; ?></td>
            <td class="main"><?php echo zen_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . zen_draw_radio_field('products_virtual', '1', $is_virtual) . '&nbsp;' . TEXT_PRODUCT_IS_VIRTUAL . '&nbsp;' . zen_draw_radio_field('products_virtual', '0', $not_virtual) . '&nbsp;' . TEXT_PRODUCT_NOT_VIRTUAL . ' ' . ($pInfo->products_virtual == 1 ? '<br /><span class="errorText">' . TEXT_VIRTUAL_EDIT . '</span>' : ''); ?></td>
          </tr>
          <tr>
            <td class="main" valign="top"><?php echo TEXT_PRODUCTS_IS_ALWAYS_FREE_SHIPPING; ?></td>
            <td class="main" valign="top"><?php echo zen_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . zen_draw_radio_field('product_is_always_free_shipping', '1', $is_product_is_always_free_shipping) . '&nbsp;' . TEXT_PRODUCT_IS_ALWAYS_FREE_SHIPPING . '&nbsp;' . zen_draw_radio_field('product_is_always_free_shipping', '0', $not_product_is_always_free_shipping) . '&nbsp;' . TEXT_PRODUCT_NOT_ALWAYS_FREE_SHIPPING  . '<br />' . zen_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . zen_draw_radio_field('product_is_always_free_shipping', '2', $special_product_is_always_free_shipping) . '&nbsp;' . TEXT_PRODUCT_SPECIAL_ALWAYS_FREE_SHIPPING . ' ' . ($pInfo->product_is_always_free_shipping == 1 ? '<br /><span class="errorText">' . TEXT_FREE_SHIPPING_EDIT . '</span>' : ''); ?></td>
          </tr>
          <tr>
            <td colspan="2"><?php echo zen_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          <tr>
            <td colspan="2"><?php echo zen_draw_separator('pixel_black.gif', '100%', '3'); ?></td>
          </tr>
          <tr>
            <td colspan="2"><?php echo zen_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
			</tr>
          <tr class="productBar">
            <td class="main" style="color: #FFFFFF; font-weight: bold;">PRODUCT QUANTITY INFORMATION:</td>
			</tr>
          <tr>
            <td colspan="2"><?php echo zen_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
          <tr>
            <td class="main"><?php echo TEXT_PRODUCTS_SORT_ORDER; ?></td>
            <td class="main"><?php echo zen_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . zen_draw_input_field('products_sort_order', $pInfo->products_sort_order); ?></td>
          </tr>
          <tr>
            <td class="main"><?php echo TEXT_PRODUCTS_QTY_BOX_STATUS; ?></td>
            <td class="main"><?php echo zen_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . zen_draw_radio_field('products_qty_box_status', '1', $is_products_qty_box_status) . '&nbsp;' . TEXT_PRODUCTS_QTY_BOX_STATUS_ON . '&nbsp;' . zen_draw_radio_field('products_qty_box_status', '0', $not_products_qty_box_status) . '&nbsp;' . TEXT_PRODUCTS_QTY_BOX_STATUS_OFF . ' ' . ($pInfo->products_qty_box_status == 0 ? '<br /><span class="errorText">' . TEXT_PRODUCTS_QTY_BOX_STATUS_EDIT . '</span>' : ''); ?></td>
          </tr>
          <tr>
            <td class="main"><?php echo TEXT_PRODUCTS_QUANTITY_MIN_RETAIL; ?></td>
            <td class="main"><?php echo zen_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . zen_draw_input_field('products_quantity_order_min', ($pInfo->products_quantity_order_min == 0 ? 1 : $pInfo->products_quantity_order_min)); ?></td>
          </tr>
          <tr>
            <td class="main"><?php echo TEXT_PRODUCTS_QUANTITY_MAX_RETAIL; ?></td>
            <td class="main"><?php echo zen_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . zen_draw_input_field('products_quantity_order_max', $pInfo->products_quantity_order_max); ?>&nbsp;&nbsp;<?php echo TEXT_PRODUCTS_QUANTITY_MAX_RETAIL_EDIT; ?></td>
          </tr>
          <tr>
            <td class="main"><?php echo TEXT_PRODUCTS_QUANTITY_UNITS_RETAIL; ?></td>
            <td class="main"><?php echo zen_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . zen_draw_input_field('products_quantity_order_units', ($pInfo->products_quantity_order_units == 0 ? 1 : $pInfo->products_quantity_order_units)); ?></td>
          </tr>
          <tr>
            <td class="main"><?php echo TEXT_PRODUCTS_MIXED; ?></td>
            <td class="main"><?php echo zen_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . zen_draw_radio_field('products_quantity_mixed', '1', $in_products_quantity_mixed) . '&nbsp;' . TEXT_YES . '&nbsp;&nbsp;' . zen_draw_radio_field('products_quantity_mixed', '0', $out_products_quantity_mixed) . '&nbsp;' . TEXT_NO; ?></td>
          </tr>

          <tr>
            <td colspan="2"><?php echo zen_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
          <tr>
            <td colspan="2"><?php echo zen_draw_separator('pixel_black.gif', '100%', '3'); ?></td>
          </tr>
          <tr>
            <td colspan="2"><?php echo zen_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
        </table></div></td>
      </tr>
      <tr>
        <td>
<script language="javascript"><!--
updateGross();
//--></script><?php echo zen_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>
      <tr>
        <td class="main" align="right"><?php echo zen_draw_hidden_field('products_date_added', (zen_not_null($pInfo->products_date_added) ? $pInfo->products_date_added : date('Y-m-d'))) . zen_image_submit('button_preview.gif', IMAGE_PREVIEW) . '&nbsp;&nbsp;<a href="' . zen_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . (isset($_GET['pID']) ? '&pID=' . $_GET['pID'] : '') . (isset($_GET['page']) ? '&page=' . $_GET['page'] : '') . ( (isset($_GET['search']) && !empty($_GET['search'])) ? '&search=' . $_GET['search'] : '') . ( (isset($_POST['search']) && !empty($_POST['search']) && empty($_GET['search'])) ? '&search=' . $_POST['search'] : '')) . '">' . zen_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>'; ?></td>
      </tr>
    </table></form>