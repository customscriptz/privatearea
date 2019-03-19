<?php
/**
 * Custom Scriptz | http://customscriptz.com
 *
 * @package page
 * @copyright Copyright 2003-2006 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 */
require(DIR_WS_MODULES . zen_get_module_directory('require_languages.php'));

if (empty($_GET['salesId']) AND (int)$_GET['salesId'] <= 0)
{
	$salesText = TEXT_SALES_INTRO . '<br /><br />';
	$sales = $db->Execute("SELECT m.manufacturers_name AS manufacturers_name, m2.manufacturers_id AS manufacturers_id FROM " . TABLE_MANUFACTURERS_INFO . " m2 JOIN " . TABLE_MANUFACTURERS . " m ON (m2.manufacturers_id = m.manufacturers_id) WHERE m2.manufacturers_sales <> '' AND m2.languages_id = " . (int)$_SESSION['languages_id']);

	if ($sales->RecordCount() > 0)
	{
		while (!$sales->EOF)
		{
			$salesText .= '<center><a href="' . zen_href_link(FILENAME_PROVIDERS_SALES, 'salesId=' . $sales->fields['manufacturers_id']) . '">' . $sales->fields['manufacturers_name'] . '</a></center><br />';
			$sales->MoveNext();
		}
	}
	else
	{
		$salesText = TEXT_NO_SALES;
	}
}
elseif (!empty($_GET['salesId']) AND (int)$_GET['salesId'] > 0)
{
	$sales = $db->Execute("SELECT m.manufacturers_name AS manufacturers_name, m2.manufacturers_sales AS manufacturers_sales FROM " . TABLE_MANUFACTURERS_INFO . " m2 JOIN " . TABLE_MANUFACTURERS . " m ON (m2.manufacturers_id = m.manufacturers_id) WHERE m2.manufacturers_id = '" . (int)$_GET['salesId'] . "' AND m2.languages_id = " . (int)$_SESSION['languages_id']);

	if ($sales->RecordCount() > 0)
	{
		$sales_text = trim($sales->fields['manufacturers_sales']);
		if (empty($sales_text)) $sales_text = TEXT_NO_SALES_FOR_THIS_LANGUAGE;
		$salesText = '<h2>' . $sales->fields['manufacturers_name'] . '</h2>' . trim($sales_text) . '<br />';
	}
	else
	{
		zen_redirect(zen_href_link(FILENAME_PROVIDERS_SALES));
	}
}

$breadcrumb->add(NAVBAR_TITLE);