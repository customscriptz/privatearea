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

if (empty($_GET['tousId']) AND (int)$_GET['tousId'] <= 0)
{
	$tousText = TEXT_TOUS_INTRO . '<br /><br />';
	$tous = $db->Execute("SELECT m.manufacturers_name AS manufacturers_name, m2.manufacturers_id AS manufacturers_id FROM " . TABLE_MANUFACTURERS_INFO . " m2 JOIN " . TABLE_MANUFACTURERS . " m ON (m2.manufacturers_id = m.manufacturers_id) WHERE m2.manufacturers_tous <> '' AND m2.languages_id = " . (int)$_SESSION['languages_id']);

	if ($tous->RecordCount() > 0)
	{
		while (!$tous->EOF)
		{
			$tousText .= '<center><a href="' . zen_href_link('terms_of_use', 'tousId=' . $tous->fields['manufacturers_id']) . '">' . $tous->fields['manufacturers_name'] . '</a></center><br />';
			$tous->MoveNext();
		}
	}
	else
	{
		$tousText = TEXT_NO_TOUS;
	}
}
elseif (!empty($_GET['tousId']) AND (int)$_GET['tousId'] > 0)
{
	$tous = $db->Execute("SELECT m.manufacturers_name AS manufacturers_name, m2.manufacturers_tous AS manufacturers_tous FROM " . TABLE_MANUFACTURERS_INFO . " m2 JOIN " . TABLE_MANUFACTURERS . " m ON (m2.manufacturers_id = m.manufacturers_id) WHERE m2.manufacturers_id = '" . (int)$_GET['tousId'] . "' AND m2.languages_id = " . (int)$_SESSION['languages_id']);
	
	if ($tous->RecordCount() > 0)
	{
		$tous_text = trim($tous->fields['manufacturers_tous']);
		if (empty($tous_text)) $tous_text = TEXT_NO_TOUS_FOR_THIS_LANGUAGE;
		$tousText = '<h2>' . $tous->fields['manufacturers_name'] . '</h2>' . $tous_text . '<br />';
	}
	else
	{
		zen_redirect(zen_href_link('terms_of_use'));
	}
}

$breadcrumb->add(NAVBAR_TITLE);