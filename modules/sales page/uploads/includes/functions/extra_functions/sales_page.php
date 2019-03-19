<?php
function zen_get_providers_sales($manufacturers_id, $language_id = '')
{
	global $db;

	if ($language_id == '')
		$language_id = $_SESSION['languages_id'];

	$sales = $db->Execute("SELECT manufacturers_sales FROM " . TABLE_MANUFACTURERS_INFO . " WHERE manufacturers_id = '" . (int)$manufacturers_id . "' AND languages_id = '" . (int)$language_id . "'");

	return $sales->fields['manufacturers_sales'];
}