<?php
//
/**
 * @package admin
 * @copyright Copyright 2003-2006 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version $Id: move_product_confirm.php 3009 2006-02-11 15:41:10Z wilt $
 */
if (!defined('IS_ADMIN_FLAG')) {
  die('Illegal Access');
}

		$products_id = zen_db_prepare_input($_REQUEST['products_id']);
		$new_parent_id = zen_db_prepare_input($_REQUEST['move_to_category_id']);

		if ($_REQUEST['do'] == 'restore')
		{
			$productDetails = $db->Execute("select original_cat, moved_to_cat from " . TABLE_PRODUCTS . "
		                        			where products_id = '" . (int)$products_id . "'");
			$new_parent_id = $productDetails->fields['original_cat'];
			$current_category_id = $productDetails->fields['moved_to_cat'];

			$db->Execute("update " . TABLE_PRODUCTS . "
		                        set original_cat = 0, moved_to_cat = 0
		                        where products_id = '" . (int)$products_id . "'");
		}
		else
		{
			$db->Execute("update " . TABLE_PRODUCTS . "
		                  	set original_cat = '" . (int)$current_category_id . "',
								moved_to_cat = '" . (int)$new_parent_id . "'
                        		where products_id = '" . (int)$products_id . "'");
		}


        $duplicate_check = $db->Execute("select count(*) as total
                                        from " . TABLE_PRODUCTS_TO_CATEGORIES . "
                                        where products_id = '" . (int)$products_id . "'
                                        and categories_id = '" . (int)$new_parent_id . "'");

        if ($duplicate_check->fields['total'] < 1) {
          $db->Execute("update " . TABLE_PRODUCTS_TO_CATEGORIES . "
                        set categories_id = '" . (int)$new_parent_id . "'
                        where products_id = '" . (int)$products_id . "'
                        and categories_id = '" . (int)$current_category_id . "'");

          // reset master_categories_id if moved from original master category
          $check_master = $db->Execute("select products_id, master_categories_id from " . TABLE_PRODUCTS . " where products_id='" .  (int)$products_id . "'");
          if ($check_master->fields['master_categories_id'] == (int)$current_category_id) {
            $db->Execute("update " . TABLE_PRODUCTS . "
                          set master_categories_id='" . (int)$new_parent_id . "'
                          where products_id = '" . (int)$products_id . "'");
          }

          // reset products_price_sorter for searches etc.
          zen_update_products_price_sorter((int)$products_id);
        } else {
          $messageStack->add_session(ERROR_CANNOT_MOVE_PRODUCT_TO_CATEGORY_SELF, 'error');
        }

        zen_redirect(zen_href_link(FILENAME_CATEGORIES, 'cPath=' . $new_parent_id . '&pID=' . $products_id . (isset($_GET['page']) ? '&page=' . $_GET['page'] : '')));
?>