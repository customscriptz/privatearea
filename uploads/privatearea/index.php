<?php
/**
 * @package admin
 * @copyright Copyright 2003-2009 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version $Id: index.php 14970 2009-11-29 00:56:42Z drbyte $
 */
  $version_check_index=true;
  require('includes/application_top.php');
  if (file_exists('install.php')) zen_redirect(zen_href_link('install.php', '', 'SSL'));

	$products_query_raw = "select p.products_id, pd.products_name, pd.products_viewed, l.name, p.master_categories_id
	  						 from " . TABLE_PRODUCTS . " p
	  						 join " . TABLE_PRODUCTS_DESCRIPTION . " pd on (p.products_id = pd.products_id)
	  						 join " . TABLE_LANGUAGES . " l on (l.languages_id = pd.language_id)
	  						 left join " . TABLE_PRODUCTS_ATTRIBUTES . " pa on (p.products_id = pa.products_id)
	  						 where (pa.products_id <= 0 OR pa.products_id is null) and p.manufacturers_id = '" . $_SESSION['provider_id'] . "' and p.products_status = 1
	  						 order by pd.products_viewed DESC";
	$products = $db->Execute($products_query_raw);
	while (!$products->EOF) {

		$messageStack->add(sprintf(TEXT_MISSING_ATTRIBUTES, $products->fields['products_name'], zen_get_product_url($products->fields['products_id'])));

		$products->MoveNext();
	}

  require(DIR_WS_CLASSES . 'currencies.php');
  $currencies = new currencies();

  if ($messageStack->size > 0) echo $messageStack->output();

  //products to show on Products Sold box
  $sql = "SELECT
			manufacturers_last_activities
		  FROM " . TABLE_MANUFACTURERS . " op
		  WHERE manufacturers_id = '" . $_SESSION['provider_id'] . "'";
  $last_activities = $db->Execute($sql);
  $manufacturers_last_activities = $last_activities->fields['manufacturers_last_activities'];

  $languages = zen_get_languages();
  $languages_array = array();
  $languages_selected = DEFAULT_LANGUAGE;
  for ($i = 0, $n = sizeof($languages); $i < $n; $i++) {
    $languages_array[] = array('id' => $languages[$i]['code'],
                               'text' => $languages[$i]['name']);
    if ($languages[$i]['directory'] == $_SESSION['language']) {
      $languages_selected = $languages[$i]['code'];
    }
  }
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<meta name="robot" content="noindex, nofollow" />
<script language="JavaScript" src="includes/menu.js" type="text/JavaScript"></script>
<link href="includes/stylesheet.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css" href="includes/cssjsmenuhover.css" media="all" id="hoverJS" />
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
<body onLoad="init()">
<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->
<div id="colone">
 <div class="reportBox">
   <div class="header"><?php echo sprintf(BOX_TITLE_PRODUCTS_SOLD, $manufacturers_last_activities); ?> </div>
  <?php   $products_sold = '';
  $sql = "SELECT
			op.products_id AS products_id,
			op.products_name AS products_name,
			op.final_price AS final_price,
			o.date_purchased AS date_purchased,
			o.currency AS currency,
			o.currency_value AS currency_value
		  FROM " . TABLE_ORDERS_PRODUCTS . " op
		  LEFT JOIN " . TABLE_PRODUCTS . " p ON (op.products_id = p.products_id)
		  LEFT JOIN " . TABLE_ORDERS . " o ON (op.orders_id = o.orders_id)
		  WHERE p.manufacturers_id = '" . $_SESSION['provider_id'] . "'
		  AND op.final_price > 0
		  AND o.orders_status >= ".PRIVATEAREA_MIN_ORDER_STATUS_FOR_STATISTICS."
		  ORDER BY o.date_purchased DESC
		  LIMIT $manufacturers_last_activities";
  $products_sold = $db->Execute($sql);

  while (!$products_sold->EOF) {
	if (strlen($products_sold->fields['products_name']) > 42) $products_sold->fields['products_name'] = substr_replace($products_sold->fields['products_name'], '...', 42);
	echo '              <div class="row"><span class="left"><strong>' . $products_sold->fields['products_name'] . '</strong></span><span class="center">' . $currencies->format($products_sold->fields['final_price'], true, $products_sold->fields['currency'], $products_sold->fields['currency_value']) . '</span><span class="rigth">' . "\n";
    echo zen_date_short($products_sold->fields['date_purchased']);
    echo '              </span></div>' . "\n";

	$products_sold->MoveNext();
  }

  if (!$products_sold->RecordCount())
  {
  	echo '<div class="row"><span class="left"><strong>' . TEXT_NOTHING_TO_DISPLAY . '</strong></span><br /></div>' . "\n";
  }
?>
  </div>
</div>
<div id="coltwo">
 <div class="reportBox">
   <div class="header"><?php echo sprintf(BOX_TITLE_FREE_PRODUCTS_SOLD, $manufacturers_last_activities); ?> </div>
  <?php   $products_sold = '';
  $sql = "SELECT
			op.products_id AS products_id,
			op.products_name AS products_name,
			op.final_price AS final_price,
			o.date_purchased AS date_purchased,
			o.currency AS currency,
			o.currency_value AS currency_value
		  FROM " . TABLE_ORDERS_PRODUCTS . " op
		  LEFT JOIN " . TABLE_PRODUCTS . " p ON (op.products_id = p.products_id)
		  LEFT JOIN " . TABLE_ORDERS . " o ON (op.orders_id = o.orders_id)
		  WHERE p.manufacturers_id = '" . $_SESSION['provider_id'] . "'
		  AND op.final_price = 0
		  AND o.orders_status >= ".PRIVATEAREA_MIN_ORDER_STATUS_FOR_STATISTICS."
		  ORDER BY o.date_purchased DESC
		  LIMIT $manufacturers_last_activities";
  $products_sold = $db->Execute($sql);

  while (!$products_sold->EOF) {
	if (strlen($products_sold->fields['products_name']) > 42) $products_sold->fields['products_name'] = substr_replace($products_sold->fields['products_name'], '...', 42);
	echo '              <div class="row"><span class="left"><strong>' . $products_sold->fields['products_name'] . '</strong></span><span class="center">' . $currencies->format($products_sold->fields['final_price'], true, $products_sold->fields['currency'], $products_sold->fields['currency_value']) . '</span><span class="rigth">' . "\n";
    echo zen_date_short($products_sold->fields['date_purchased']);
    echo '              </span></div>' . "\n";

	$products_sold->MoveNext();
  }

  if (!$products_sold->RecordCount())
  {
  	echo '<div class="row"><span class="left"><strong>' . TEXT_NOTHING_TO_DISPLAY . '</strong></span><br /></div>' . "\n";
  }
?>
  </div>
</div>
<div id="colthree">
 <div class="reportBox">
   <div class="header"><?php echo sprintf(BOX_TITLE_MOST_SOLD_PRODUCTS, $manufacturers_last_activities); ?> </div>
  <?php   $products_sold = '';
  $sql = "SELECT
  			count(*) as quantity,
			op.products_id AS products_id,
			op.products_name AS products_name,
			sum(op.final_price) AS total,
			o.date_purchased AS date_purchased,
			o.currency AS currency,
			o.currency_value AS currency_value
		  FROM " . TABLE_ORDERS_PRODUCTS . " op
		  LEFT JOIN " . TABLE_PRODUCTS . " p ON (op.products_id = p.products_id)
		  LEFT JOIN " . TABLE_ORDERS . " o ON (op.orders_id = o.orders_id)
		  WHERE p.manufacturers_id = '" . $_SESSION['provider_id'] . "'
		  AND o.orders_status >= ".PRIVATEAREA_MIN_ORDER_STATUS_FOR_STATISTICS."
		  GROUP BY op.products_id
		  ORDER BY total DESC
		  LIMIT $manufacturers_last_activities";
  $products_sold = $db->Execute($sql);

  while (!$products_sold->EOF) {
  	if (strlen($products_sold->fields['products_name']) > 42) $products_sold->fields['products_name'] = substr_replace($products_sold->fields['products_name'], '...', 42);
	echo '              <div class="row"><span class="left"><strong>' . $products_sold->fields['products_name'] . '</strong></span><span class="center">' . $currencies->format($products_sold->fields['total']) . '</span><br />' . TEXT_STATS_PRODUCTS_QUANTITY . ' <span class="rigth">' . "\n";
    echo $products_sold->fields['quantity'];
    echo '              </span></div>' . "\n";

	$products_sold->MoveNext();
  }

  if (!$products_sold->RecordCount())
  {
  	echo '<div class="row"><span class="left"><strong>' . TEXT_NOTHING_TO_DISPLAY . '</strong></span><br /></div>' . "\n";
  }
?>
</div>
</div>
<!-- The following copyright announcement is in compliance
to section 2c of the GNU General Public License, and
thus can not be removed, or can only be modified
appropriately.

Please leave this comment intact together with the
following copyright announcement. //-->

<div class="copyrightrow"><a href="http://www.zen-cart.com" target="_blank"><img src="images/small_zen_logo.gif" alt="Zen Cart:: the art of e-commerce" border="0" /></a><br /><br />E-Commerce Engine Copyright &copy; 2003-<?php echo date('Y'); ?> <a href="http://www.zen-cart.com" target="_blank">Zen Cart&trade;</a></div><div class="warrantyrow"><br /><br />Zen Cart is derived from: Copyright &copy; 2003 osCommerce<br />This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY;<br />without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE<br />and is redistributable under the <a href="http://www.zen-cart.com/license/2_0.txt" target="_blank">GNU General Public License</a><br />
</div>
</body>
</html>
<?php require('includes/application_bottom.php'); ?>