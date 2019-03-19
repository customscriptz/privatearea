<?php
function zen_get_manufacturers_tous($manufacturers_id, $fn_language_id) {
	global $db;
	$sql = "select manufacturers_tous
					   from " . TABLE_MANUFACTURERS_INFO . "
					   where manufacturers_id = '" . (int)$manufacturers_id . "'
					   and languages_id = '" . $fn_language_id . "'";

	$tous = $db->Execute($sql);

	return $tous->fields['manufacturers_tous'];
}

function zen_privatearea_sanitize($z){
    $z = strtolower($z);
    $z = preg_replace('/[^a-z0-9 -]+/', '', $z);
    $z = str_replace(' ', '-', $z);
    return trim($z, '-');
}