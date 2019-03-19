<?php
/**
 * @package admin
 * @copyright Copyright 2003-2010 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version $Id: attributes_controller.php 17888 2010-10-08 21:06:31Z wilt $
 */

  require('includes/application_top.php');
  
  $messageStack->add(TEXT_ATTRIBUTES_CONTROLLER_WARNING, 'caution');
  echo $messageStack->output();
  /*
  
  // troubleshooting/debug of option name/value IDs:
  $show_name_numbers = true;
  $show_value_numbers = true;

  // verify option names, values, products
  $chk_option_names = $db->Execute("select * from " . TABLE_PRODUCTS_OPTIONS . " where language_id='" . $_SESSION['languages_id'] . "' limit 1");
  if ($chk_option_names->RecordCount() < 1) {
    $messageStack->add_session(ERROR_DEFINE_OPTION_NAMES, 'caution');
    zen_redirect(zen_href_link(FILENAME_OPTIONS_NAME_MANAGER));
  }
  $chk_option_values = $db->Execute("select * from " . TABLE_PRODUCTS_OPTIONS_VALUES . " where language_id='" . $_SESSION['languages_id'] . "' limit 1");
  if ($chk_option_values->RecordCount() < 1) {
    $messageStack->add_session(ERROR_DEFINE_OPTION_VALUES, 'caution');
    zen_redirect(zen_href_link(FILENAME_OPTIONS_VALUES_MANAGER));
  }
  $chk_products = $db->Execute("select * from " . TABLE_PRODUCTS . " limit 1");
  if ($chk_products->RecordCount() < 1) {
    $messageStack->add_session(ERROR_DEFINE_PRODUCTS, 'caution');
    zen_redirect(zen_href_link(FILENAME_CATEGORIES));
  }

  require(DIR_WS_CLASSES . 'currencies.php');
  $currencies = new currencies();

  $languages = zen_get_languages();

  $action = (isset($_GET['action']) ? $_GET['action'] : '');

  $products_filter = (isset($_GET['products_filter']) ? $_GET['products_filter'] : $products_filter);

  $current_category_id = (isset($_GET['current_category_id']) ? $_GET['current_category_id'] : $current_category_id);

  if ($action == 'new_cat') {
    $current_category_id = (isset($_GET['current_category_id']) ? $_GET['current_category_id'] : $current_category_id);
    $sql =     "select ptc.*
    from " . TABLE_PRODUCTS_TO_CATEGORIES . " ptc
    left join " . TABLE_PRODUCTS_DESCRIPTION . " pd
    on ptc.products_id = pd.products_id
    and pd.language_id = '" . (int)$_SESSION['languages_id'] . "'
    where ptc.categories_id='" . $current_category_id . "'
    order by pd.products_name";
    $new_product_query = $db->Execute($sql);
    $products_filter = $new_product_query->fields['products_id'];
    zen_redirect(zen_href_link(FILENAME_ATTRIBUTES_CONTROLLER, 'products_filter=' . $products_filter . '&current_category_id=' . $current_category_id));
  }

// set categories and products if not set
  if ($products_filter == '' and $current_category_id != '') {
    $sql =     "select ptc.*
    from " . TABLE_PRODUCTS_TO_CATEGORIES . " ptc
    left join " . TABLE_PRODUCTS_DESCRIPTION . " pd
    on ptc.products_id = pd.products_id
    and pd.language_id = '" . (int)$_SESSION['languages_id'] . "'
    where ptc.categories_id='" . $current_category_id . "'
    order by pd.products_name";
    $new_product_query = $db->Execute($sql);
    $products_filter = $new_product_query->fields['products_id'];
    if ($products_filter != '' AND !$action) {
      zen_redirect(zen_href_link(FILENAME_ATTRIBUTES_CONTROLLER, 'products_filter=' . $products_filter . '&current_category_id=' . $current_category_id));
    }
  } else {
    if ($products_filter == '' and $current_category_id == '') {
      $reset_categories_id = zen_get_category_tree('', '', '0', '', '', true);
      $current_category_id = $reset_categories_id[0]['id'];
      $sql = "select ptc.*
      from " . TABLE_PRODUCTS_TO_CATEGORIES . " ptc
      left join " . TABLE_PRODUCTS_DESCRIPTION . " pd
      on ptc.products_id = pd.products_id and pd.language_id = '" . (int)$_SESSION['languages_id'] . "'
      where ptc.categories_id='" . $current_category_id . "'
      order by pd.products_name";
      $new_product_query = $db->Execute($sql);
      $products_filter = $new_product_query->fields['products_id'];
      $_GET['products_filter'] = $products_filter;
    }
  }

  require(DIR_WS_MODULES . FILENAME_PREV_NEXT);

  if (zen_not_null($action)) {
    $_SESSION['page_info'] = '';
    if (isset($_GET['option_page'])) $_SESSION['page_info'] .= 'option_page=' . $_GET['option_page'] . '&';
    if (isset($_GET['value_page'])) $_SESSION['page_info'] .= 'value_page=' . $_GET['value_page'] . '&';
    if (isset($_GET['attribute_page'])) $_SESSION['page_info'] .= 'attribute_page=' . $_GET['attribute_page'] . '&';
    if (isset($_GET['products_filter'])) $_SESSION['page_info'] .= 'products_filter=' . $_GET['products_filter'] . '&';
    if (isset($_GET['current_category_id'])) $_SESSION['page_info'] .= 'current_category_id=' . $_GET['current_category_id'] . '&';

    if (zen_not_null($_SESSION['page_info'])) {
      $_SESSION['page_info'] = substr($_SESSION['page_info'], 0, -1);
    }

    switch ($action) {
/////////////////////////////////////////
//// BOF OF FLAGS
      case 'set_flag_attributes_display_only':
        $action='';
        $new_flag= $db->Execute("select products_attributes_id, products_id, attributes_display_only from " . TABLE_PRODUCTS_ATTRIBUTES . " where products_id='" . $_GET['products_filter'] . "' and products_attributes_id='" . $_GET['attributes_id'] . "'");
        if ($new_flag->fields['attributes_display_only'] == '0') {
          $db->Execute("update " . TABLE_PRODUCTS_ATTRIBUTES . " set attributes_display_only='1' where products_id='" . $_GET['products_filter'] . "' and products_attributes_id='" . $_GET['attributes_id'] . "'");
        } else {
          $db->Execute("update " . TABLE_PRODUCTS_ATTRIBUTES . " set attributes_display_only='0' where products_id='" . $_GET['products_filter'] . "' and products_attributes_id='" . $_GET['attributes_id'] . "'");
        }
        zen_redirect(zen_href_link(FILENAME_ATTRIBUTES_CONTROLLER, $_SESSION['page_info'] . '&products_filter=' . $_GET['products_filter'] . '&current_category_id=' . $_GET['current_category_id']));
        break;

      case 'set_flag_product_attribute_is_free':
        $action='';
        $new_flag= $db->Execute("select products_attributes_id, products_id, product_attribute_is_free from " . TABLE_PRODUCTS_ATTRIBUTES . " where products_id='" . $_GET['products_filter'] . "' and products_attributes_id='" . $_GET['attributes_id'] . "'");
        if ($new_flag->fields['product_attribute_is_free'] == '0') {
          $db->Execute("update " . TABLE_PRODUCTS_ATTRIBUTES . " set product_attribute_is_free='1' where products_id='" . $_GET['products_filter'] . "' and products_attributes_id='" . $_GET['attributes_id'] . "'");
        } else {
          $db->Execute("update " . TABLE_PRODUCTS_ATTRIBUTES . " set product_attribute_is_free='0' where products_id='" . $_GET['products_filter'] . "' and products_attributes_id='" . $_GET['attributes_id'] . "'");
        }
        zen_redirect(zen_href_link(FILENAME_ATTRIBUTES_CONTROLLER, $_SESSION['page_info'] . '&products_filter=' . $_GET['products_filter'] . '&current_category_id=' . $_GET['current_category_id']));
        break;

      case 'set_flag_attributes_default':
        $action='';
        $new_flag= $db->Execute("select products_attributes_id, products_id, attributes_default from " . TABLE_PRODUCTS_ATTRIBUTES . " where products_id='" . $_GET['products_filter'] . "' and products_attributes_id='" . $_GET['attributes_id'] . "'");
        if ($new_flag->fields['attributes_default'] == '0') {
          $db->Execute("update " . TABLE_PRODUCTS_ATTRIBUTES . " set attributes_default='1' where products_id='" . $_GET['products_filter'] . "' and products_attributes_id='" . $_GET['attributes_id'] . "'");
        } else {
          $db->Execute("update " . TABLE_PRODUCTS_ATTRIBUTES . " set attributes_default='0' where products_id='" . $_GET['products_filter'] . "' and products_attributes_id='" . $_GET['attributes_id'] . "'");
        }
        zen_redirect(zen_href_link(FILENAME_ATTRIBUTES_CONTROLLER, $_SESSION['page_info'] . '&products_filter=' . $_GET['products_filter'] . '&current_category_id=' . $_GET['current_category_id']));
        break;

      case 'set_flag_attributes_discounted':
        $action='';
        $new_flag= $db->Execute("select products_attributes_id, products_id, attributes_discounted from " . TABLE_PRODUCTS_ATTRIBUTES . " where products_id='" . $_GET['products_filter'] . "' and products_attributes_id='" . $_GET['attributes_id'] . "'");
        if ($new_flag->fields['attributes_discounted'] == '0') {
          $db->Execute("update " . TABLE_PRODUCTS_ATTRIBUTES . " set attributes_discounted='1' where products_id='" . $_GET['products_filter'] . "' and products_attributes_id='" . $_GET['attributes_id'] . "'");
        } else {
          $db->Execute("update " . TABLE_PRODUCTS_ATTRIBUTES . " set attributes_discounted='0' where products_id='" . $_GET['products_filter'] . "' and products_attributes_id='" . $_GET['attributes_id'] . "'");
        }

        // reset products_price_sorter for searches etc.
        zen_update_products_price_sorter($_GET['products_filter']);

        zen_redirect(zen_href_link(FILENAME_ATTRIBUTES_CONTROLLER, $_SESSION['page_info'] . '&products_filter=' . $_GET['products_filter'] . '&current_category_id=' . $_GET['current_category_id']));
        break;

      case 'set_flag_attributes_price_base_included':
        $action='';
        $new_flag= $db->Execute("select products_attributes_id, products_id, attributes_price_base_included from " . TABLE_PRODUCTS_ATTRIBUTES . " where products_id='" . $_GET['products_filter'] . "' and products_attributes_id='" . $_GET['attributes_id'] . "'");
        if ($new_flag->fields['attributes_price_base_included'] == '0') {
          $db->Execute("update " . TABLE_PRODUCTS_ATTRIBUTES . " set attributes_price_base_included='1' where products_id='" . $_GET['products_filter'] . "' and products_attributes_id='" . $_GET['attributes_id'] . "'");
        } else {
          $db->Execute("update " . TABLE_PRODUCTS_ATTRIBUTES . " set attributes_price_base_included='0' where products_id='" . $_GET['products_filter'] . "' and products_attributes_id='" . $_GET['attributes_id'] . "'");
        }

        // reset products_price_sorter for searches etc.
        zen_update_products_price_sorter($_GET['products_filter']);

        zen_redirect(zen_href_link(FILENAME_ATTRIBUTES_CONTROLLER, $_SESSION['page_info'] . '&products_filter=' . $_GET['products_filter'] . '&current_category_id=' . $_GET['current_category_id']));
        break;

      case 'set_flag_attributes_required':
        $action='';
        $new_flag= $db->Execute("select products_attributes_id, products_id, attributes_required from " . TABLE_PRODUCTS_ATTRIBUTES . " where products_id='" . $_GET['products_filter'] . "' and products_attributes_id='" . $_GET['attributes_id'] . "'");
        if ($new_flag->fields['attributes_required'] == '0') {
          $db->Execute("update " . TABLE_PRODUCTS_ATTRIBUTES . " set attributes_required='1' where products_id='" . $_GET['products_filter'] . "' and products_attributes_id='" . $_GET['attributes_id'] . "'");
        } else {
          $db->Execute("update " . TABLE_PRODUCTS_ATTRIBUTES . " set attributes_required='0' where products_id='" . $_GET['products_filter'] . "' and products_attributes_id='" . $_GET['attributes_id'] . "'");
        }
        zen_redirect(zen_href_link(FILENAME_ATTRIBUTES_CONTROLLER, $_SESSION['page_info'] . '&products_filter=' . $_GET['products_filter'] . '&current_category_id=' . $_GET['current_category_id']));
        break;

//// EOF OF FLAGS
/////////////////////////////////////////

      case 'set_products_filter':
        $_GET['products_filter'] = $_POST['products_filter'];
        $_GET['current_category_id'] = $_POST['current_category_id'];
        $action='';
        zen_redirect(zen_href_link(FILENAME_ATTRIBUTES_CONTROLLER, $_SESSION['page_info'] . '&products_filter=' . $_GET['products_filter'] . '&current_category_id=' . $_GET['current_category_id']));
        break;
// update by product
      case ('update_product'):
        if (!zen_has_product_attributes($products_filter, 'false')) {
          $messageStack->add_session(SUCCESS_PRODUCT_UPDATE_SORT_NONE . $products_filter . ' ' . zen_get_products_name($products_filter, $_SESSION['languages_id']), 'error');
        } else {
          $messageStack->add_session(SUCCESS_PRODUCT_UPDATE_SORT . $products_filter . ' ' . zen_get_products_name($products_filter, $_SESSION['languages_id']), 'success');
          zen_update_attributes_products_option_values_sort_order($products_filter);
        }
        $action='';
        zen_redirect(zen_href_link(FILENAME_ATTRIBUTES_CONTROLLER, 'products_filter=' . $products_filter . '&current_category_id=' . $_GET['current_category_id']));
        break;
      case 'add_product_attributes':
        $current_image_name = '';
        for ($i=0; $i<sizeof($_POST['values_id']); $i++) {
// check for duplicate and block them
          $check_duplicate = $db->Execute("select * from " . TABLE_PRODUCTS_ATTRIBUTES . "
                                           where products_id ='" . $_POST['products_id'] . "'
                                           and options_id = '" . $_POST['options_id'] . "'
                                           and options_values_id = '" . $_POST['values_id'][$i] . "'");
          if ($check_duplicate->RecordCount() > 0) {
            // do not add duplicates -- give a warning
            $messageStack->add_session(ATTRIBUTE_WARNING_DUPLICATE . ' - ' . zen_options_name($_POST['options_id']) . ' : ' . zen_values_name($_POST['values_id'][$i]), 'error');
          } else {
// For TEXT and FILE option types, ignore option value entered by administrator and use PRODUCTS_OPTIONS_VALUES_TEXT instead.
            $products_options_array = $db->Execute("select products_options_type from " . TABLE_PRODUCTS_OPTIONS . " where products_options_id = '" . $_POST['options_id'] . "'");
            $values_id = zen_db_prepare_input((($products_options_array->fields['products_options_type'] == PRODUCTS_OPTIONS_TYPE_TEXT) or ($products_options_array->fields['products_options_type'] == PRODUCTS_OPTIONS_TYPE_FILE)) ? PRODUCTS_OPTIONS_VALUES_TEXT_ID : $_POST['values_id'][$i]);

            $products_id = zen_db_prepare_input($_POST['products_id']);
            $options_id = zen_db_prepare_input($_POST['options_id']);
//            $values_id = zen_db_prepare_input($_POST['values_id'][$i]);
            $value_price = zen_db_prepare_input($_POST['value_price']);
            $price_prefix = zen_db_prepare_input($_POST['price_prefix']);

            $products_options_sort_order = zen_db_prepare_input($_POST['products_options_sort_order']);

// modified options sort order to use default if not otherwise set
            if (zen_not_null($_POST['products_options_sort_order'])) {
              $products_options_sort_order = zen_db_prepare_input($_POST['products_options_sort_order']);
            } else {
              $sort_order_query = $db->Execute("select products_options_values_sort_order from " . TABLE_PRODUCTS_OPTIONS_VALUES . " where products_options_values_id = '" . $_POST['values_id'][$i] . "'");
              $products_options_sort_order = $sort_order_query->fields['products_options_values_sort_order'];
            } // end if (zen_not_null($_POST['products_options_sort_order'])

// end modification for sort order

            $product_attribute_is_free = zen_db_prepare_input($_POST['product_attribute_is_free']);
            $products_attributes_weight = zen_db_prepare_input($_POST['products_attributes_weight']);
            $products_attributes_weight_prefix = zen_db_prepare_input($_POST['products_attributes_weight_prefix']);
            $attributes_display_only = zen_db_prepare_input($_POST['attributes_display_only']);
            $attributes_default = zen_db_prepare_input($_POST['attributes_default']);
            $attributes_discounted = zen_db_prepare_input($_POST['attributes_discounted']);
            $attributes_price_base_included = zen_db_prepare_input($_POST['attributes_price_base_included']);

            $attributes_price_onetime = zen_db_prepare_input($_POST['attributes_price_onetime']);
            $attributes_price_factor = zen_db_prepare_input($_POST['attributes_price_factor']);
            $attributes_price_factor_offset = zen_db_prepare_input($_POST['attributes_price_factor_offset']);
            $attributes_price_factor_onetime = zen_db_prepare_input($_POST['attributes_price_factor_onetime']);
            $attributes_price_factor_onetime_offset = zen_db_prepare_input($_POST['attributes_price_factor_onetime_offset']);
            $attributes_qty_prices = zen_db_prepare_input($_POST['attributes_qty_prices']);
            $attributes_qty_prices_onetime = zen_db_prepare_input($_POST['attributes_qty_prices_onetime']);

            $attributes_price_words = zen_db_prepare_input($_POST['attributes_price_words']);
            $attributes_price_words_free = zen_db_prepare_input($_POST['attributes_price_words_free']);
            $attributes_price_letters = zen_db_prepare_input($_POST['attributes_price_letters']);
            $attributes_price_letters_free = zen_db_prepare_input($_POST['attributes_price_letters_free']);
            $attributes_required = zen_db_prepare_input($_POST['attributes_required']);

// add - update as record exists
// attributes images
// when set to none remove from database
// only processes image once for multiple selection of options_values_id
            if ($i == 0) {
              if (isset($_POST['attributes_image']) && zen_not_null($_POST['attributes_image']) && ($_POST['attributes_image'] != 'none')) {
                $attributes_image = zen_db_prepare_input($_POST['attributes_image']);
              } else {
                $attributes_image = '';
              }

              $attributes_image = new upload('attributes_image');
              $attributes_image->set_destination(DIR_FS_CATALOG_IMAGES . $_POST['img_dir']);
              if ($attributes_image->parse() && $attributes_image->save($_POST['overwrite'])) {
                $attributes_image_name = $_POST['img_dir'] . $attributes_image->filename;
              } else {
                $attributes_image_name = (isset($_POST['attributes_previous_image']) ? $_POST['attributes_previous_image'] : '');
              }
              $current_image_name = $attributes_image_name;
            } else {
              $attributes_image_name = $current_image_name;
            }

            $db->Execute("insert into " . TABLE_PRODUCTS_ATTRIBUTES . " (products_attributes_id, products_id, options_id, options_values_id, options_values_price, price_prefix, products_options_sort_order, product_attribute_is_free, products_attributes_weight, products_attributes_weight_prefix, attributes_display_only, attributes_default, attributes_discounted, attributes_image, attributes_price_base_included, attributes_price_onetime, attributes_price_factor, attributes_price_factor_offset, attributes_price_factor_onetime, attributes_price_factor_onetime_offset, attributes_qty_prices, attributes_qty_prices_onetime, attributes_price_words, attributes_price_words_free, attributes_price_letters, attributes_price_letters_free, attributes_required)
                          values (0,
                                  '" . (int)$products_id . "',
                                  '" . (int)$options_id . "',
                                  '" . (int)$values_id . "',
                                  '" . (float)zen_db_input($value_price) . "',
                                  '" . zen_db_input($price_prefix) . "',
                                  '" . (int)zen_db_input($products_options_sort_order) . "',
                                  '" . (int)zen_db_input($product_attribute_is_free) . "',
                                  '" . (float)zen_db_input($products_attributes_weight) . "',
                                  '" . zen_db_input($products_attributes_weight_prefix) . "',
                                  '" . (int)zen_db_input($attributes_display_only) . "',
                                  '" . (int)zen_db_input($attributes_default) . "',
                                  '" . (int)zen_db_input($attributes_discounted) . "',
                                  '" . zen_db_input($attributes_image_name) . "',
                                  '" . (int)zen_db_input($attributes_price_base_included) . "',
                                  '" . (float)zen_db_input($attributes_price_onetime) . "',
                                  '" . (float)zen_db_input($attributes_price_factor) . "',
                                  '" . (float)zen_db_input($attributes_price_factor_offset) . "',
                                  '" . (float)zen_db_input($attributes_price_factor_onetime) . "',
                                  '" . (float)zen_db_input($attributes_price_factor_onetime_offset) . "',
                                  '" . zen_db_input($attributes_qty_prices) . "',
                                  '" . zen_db_input($attributes_qty_prices_onetime) . "',
                                  '" . (float)zen_db_input($attributes_price_words) . "',
                                  '" . (int)zen_db_input($attributes_price_words_free) . "',
                                  '" . (float)zen_db_input($attributes_price_letters) . "',
                                  '" . (int)zen_db_input($attributes_price_letters_free) . "',
                                  '" . (int)zen_db_input($attributes_required) . "')");

            if (DOWNLOAD_ENABLED == 'true') {
              $products_attributes_id = $db->Insert_ID();

              $products_attributes_filename = zen_db_prepare_input($_POST['products_attributes_filename']);
              $products_attributes_maxdays = (int)zen_db_prepare_input($_POST['products_attributes_maxdays']);
              $products_attributes_maxcount = (int)zen_db_prepare_input($_POST['products_attributes_maxcount']);

//die( 'I am adding ' . strlen($_POST['products_attributes_filename']) . ' vs ' . strlen(trim($_POST['products_attributes_filename'])) . ' vs ' . strlen(zen_db_prepare_input($_POST['products_attributes_filename'])) . ' vs ' . strlen(zen_db_input($products_attributes_filename)) );
              if (zen_not_null($products_attributes_filename)) {
                $db->Execute("insert into " . TABLE_PRODUCTS_ATTRIBUTES_DOWNLOAD . "
                              (products_attributes_id, products_attributes_filename, products_attributes_maxdays, products_attributes_maxcount)
                              values (" . (int)$products_attributes_id . ",
                                      '" . zen_db_input($products_attributes_filename) . "',
                                      '" . zen_db_input($products_attributes_maxdays) . "',
                                      '" . zen_db_input($products_attributes_maxcount) . "')");
              }
            }
          }
        }

        // reset products_price_sorter for searches etc.
        zen_update_products_price_sorter($_POST['products_id']);

        zen_redirect(zen_href_link(FILENAME_ATTRIBUTES_CONTROLLER, $_SESSION['page_info'] . '&products_filter=' . $_POST['products_id'] . '&current_category_id=' . $_POST['current_category_id']));
        break;
      case 'update_product_attribute':
        $check_duplicate = $db->Execute("select * from " . TABLE_PRODUCTS_ATTRIBUTES . "
                                         where products_id ='" . $_POST['products_id'] . "'
                                         and options_id = '" . $_POST['options_id'] . "'
                                         and options_values_id = '" . $_POST['values_id'] . "'
                                         and products_attributes_id != '" . $_POST['attribute_id'] . "'");

        if ($check_duplicate->RecordCount() > 0) {
          // do not add duplicates give a warning
          $messageStack->add_session(ATTRIBUTE_WARNING_DUPLICATE_UPDATE . ' - ' . zen_options_name($_POST['options_id']) . ' : ' . zen_values_name($_POST['values_id']), 'error');
        } else {
          // Validate options_id and options_value_id
          if (!zen_validate_options_to_options_value($_POST['options_id'], $_POST['values_id'])) {
            // do not add invalid match
            $messageStack->add_session(ATTRIBUTE_WARNING_INVALID_MATCH_UPDATE . ' - ' . zen_options_name($_POST['options_id']) . ' : ' . zen_values_name($_POST['values_id']), 'error');
          } else {
            // add the new attribute
// iii 030811 added:  Enforce rule that TEXT and FILE Options use value PRODUCTS_OPTIONS_VALUES_TEXT_ID
        $products_options_query = $db->Execute("select products_options_type from " . TABLE_PRODUCTS_OPTIONS . " where products_options_id = '" . $_POST['options_id'] . "'");
        switch ($products_options_array->fields['products_options_type']) {
          case PRODUCTS_OPTIONS_TYPE_TEXT:
          case PRODUCTS_OPTIONS_TYPE_FILE:
            $values_id = PRODUCTS_OPTIONS_VALUES_TEXT_ID;
            break;
          default:
          $values_id = zen_db_prepare_input($_POST['values_id']);
        }
// iii 030811 added END

            $products_id = zen_db_prepare_input($_POST['products_id']);
            $options_id = zen_db_prepare_input($_POST['options_id']);
//            $values_id = zen_db_prepare_input($_POST['values_id']);
            $value_price = zen_db_prepare_input($_POST['value_price']);
            $price_prefix = zen_db_prepare_input($_POST['price_prefix']);

            $products_options_sort_order = zen_db_prepare_input($_POST['products_options_sort_order']);
            $product_attribute_is_free = zen_db_prepare_input($_POST['product_attribute_is_free']);
            $products_attributes_weight = zen_db_prepare_input($_POST['products_attributes_weight']);
            $products_attributes_weight_prefix = zen_db_prepare_input($_POST['products_attributes_weight_prefix']);
            $attributes_display_only = zen_db_prepare_input($_POST['attributes_display_only']);
            $attributes_default = zen_db_prepare_input($_POST['attributes_default']);
            $attributes_discounted = zen_db_prepare_input($_POST['attributes_discounted']);
            $attributes_price_base_included = zen_db_prepare_input($_POST['attributes_price_base_included']);

            $attributes_price_onetime = zen_db_prepare_input($_POST['attributes_price_onetime']);
            $attributes_price_factor = zen_db_prepare_input($_POST['attributes_price_factor']);
            $attributes_price_factor_offset = zen_db_prepare_input($_POST['attributes_price_factor_offset']);
            $attributes_price_factor_onetime = zen_db_prepare_input($_POST['attributes_price_factor_onetime']);
            $attributes_price_factor_onetime_offset = zen_db_prepare_input($_POST['attributes_price_factor_onetime_offset']);
            $attributes_qty_prices = zen_db_prepare_input($_POST['attributes_qty_prices']);
            $attributes_qty_prices_onetime = zen_db_prepare_input($_POST['attributes_qty_prices_onetime']);

            $attributes_price_words = zen_db_prepare_input($_POST['attributes_price_words']);
            $attributes_price_words_free = zen_db_prepare_input($_POST['attributes_price_words_free']);
            $attributes_price_letters = zen_db_prepare_input($_POST['attributes_price_letters']);
            $attributes_price_letters_free = zen_db_prepare_input($_POST['attributes_price_letters_free']);
            $attributes_required = zen_db_prepare_input($_POST['attributes_required']);

            $attribute_id = zen_db_prepare_input($_POST['attribute_id']);

// edit
// attributes images
// when set to none remove from database
          if (isset($_POST['attributes_image']) && zen_not_null($_POST['attributes_image']) && ($_POST['attributes_image'] != 'none')) {
            $attributes_image = zen_db_prepare_input($_POST['attributes_image']);
            $attributes_image_none = false;
          } else {
            $attributes_image = '';
            $attributes_image_none = true;
          }

          $attributes_image = new upload('attributes_image');
          $attributes_image->set_destination(DIR_FS_CATALOG_IMAGES . $_POST['img_dir']);
          if ($attributes_image->parse() && $attributes_image->save($_POST['overwrite'])) {
            $attributes_image_name = ($attributes_image->filename != 'none' ? ($_POST['img_dir'] . $attributes_image->filename) : '');
          } else {
            $attributes_image_name = ((isset($_POST['attributes_previous_image']) and $_POST['attributes_image'] != 'none') ? $_POST['attributes_previous_image'] : '');
          }

if ($_POST['image_delete'] == 1) {
  $attributes_image_name = '';
}
// turned off until working
          $db->Execute("update " . TABLE_PRODUCTS_ATTRIBUTES . "
                        set attributes_image = '" .  $attributes_image_name . "'
                        where products_attributes_id = '" . (int)$attribute_id . "'");

            $db->Execute("update " . TABLE_PRODUCTS_ATTRIBUTES . "
                          set products_id = '" . (int)$products_id . "',
                              options_id = '" . (int)$options_id . "',
                              options_values_id = '" . (int)$values_id . "',
                              options_values_price = '" . zen_db_input($value_price) . "',
                              price_prefix = '" . zen_db_input($price_prefix) . "',
                              products_options_sort_order = '" . zen_db_input($products_options_sort_order) . "',
                              product_attribute_is_free = '" . zen_db_input($product_attribute_is_free) . "',
                              products_attributes_weight = '" . zen_db_input($products_attributes_weight) . "',
                              products_attributes_weight_prefix = '" . zen_db_input($products_attributes_weight_prefix) . "',
                              attributes_display_only = '" . zen_db_input($attributes_display_only) . "',
                              attributes_default = '" . zen_db_input($attributes_default) . "',
                              attributes_discounted = '" . zen_db_input($attributes_discounted) . "',
                              attributes_price_base_included = '" . zen_db_input($attributes_price_base_included) . "',
                              attributes_price_onetime = '" . zen_db_input($attributes_price_onetime) . "',
                              attributes_price_factor = '" . zen_db_input($attributes_price_factor) . "',
                              attributes_price_factor_offset = '" . zen_db_input($attributes_price_factor_offset) . "',
                              attributes_price_factor_onetime = '" . zen_db_input($attributes_price_factor_onetime) . "',
                              attributes_price_factor_onetime_offset = '" . zen_db_input($attributes_price_factor_onetime_offset) . "',
                              attributes_qty_prices = '" . zen_db_input($attributes_qty_prices) . "',
                              attributes_qty_prices_onetime = '" . zen_db_input($attributes_qty_prices_onetime) . "',
                              attributes_price_words = '" . zen_db_input($attributes_price_words) . "',
                              attributes_price_words_free = '" . zen_db_input($attributes_price_words_free) . "',
                              attributes_price_letters = '" . zen_db_input($attributes_price_letters) . "',
                              attributes_price_letters_free = '" . zen_db_input($attributes_price_letters_free) . "',
                              attributes_required = '" . zen_db_input($attributes_required) . "'
                          where products_attributes_id = '" . (int)$attribute_id . "'");

            if (DOWNLOAD_ENABLED == 'true') {
              $products_attributes_filename = zen_db_prepare_input($_POST['products_attributes_filename']);
              $products_attributes_maxdays = zen_db_prepare_input($_POST['products_attributes_maxdays']);
              $products_attributes_maxcount = zen_db_prepare_input($_POST['products_attributes_maxcount']);

              if (zen_not_null($products_attributes_filename)) {
                $db->Execute("replace into " . TABLE_PRODUCTS_ATTRIBUTES_DOWNLOAD . "
                              set products_attributes_id = '" . (int)$attribute_id . "',
                                  products_attributes_filename = '" . zen_db_input($products_attributes_filename) . "',
                                  products_attributes_maxdays = '" . zen_db_input($products_attributes_maxdays) . "',
                                  products_attributes_maxcount = '" . zen_db_input($products_attributes_maxcount) . "'");
              }
            }
          }
        }

        // reset products_price_sorter for searches etc.
        zen_update_products_price_sorter($_POST['products_id']);

        zen_redirect(zen_href_link(FILENAME_ATTRIBUTES_CONTROLLER, $_SESSION['page_info'] . '&current_category_id=' . $_POST['current_category_id']));
        break;
      case 'delete_attribute':
        // demo active test
        if (zen_admin_demo()) {
          $_GET['action']= '';
          $messageStack->add_session(ERROR_ADMIN_DEMO, 'caution');
          zen_redirect(zen_href_link(FILENAME_ATTRIBUTES_CONTROLLER, $_SESSION['page_info'] . '&current_category_id=' . $_POST['current_category_id']));
        }
        $attribute_id = zen_db_prepare_input($_GET['attribute_id']);

        $db->Execute("delete from " . TABLE_PRODUCTS_ATTRIBUTES . "
                      where products_attributes_id = '" . (int)$attribute_id . "'");

// added for DOWNLOAD_ENABLED. Always try to remove attributes, even if downloads are no longer enabled
        $db->Execute("delete from " . TABLE_PRODUCTS_ATTRIBUTES_DOWNLOAD . "
                      where products_attributes_id = '" . (int)$attribute_id . "'");

        // reset products_price_sorter for searches etc.
        zen_update_products_price_sorter($products_filter);

//        zen_redirect(zen_href_link(FILENAME_ATTRIBUTES_CONTROLLER, $_SESSION['page_info']));
        zen_redirect(zen_href_link(FILENAME_ATTRIBUTES_CONTROLLER, $_SESSION['page_info'] . '&current_category_id=' . $current_category_id));
        break;
// delete all attributes
      case 'delete_all_attributes':
        zen_delete_products_attributes($_POST['products_filter']);
        $messageStack->add_session(SUCCESS_ATTRIBUTES_DELETED . ' ID#' . $products_filter, 'success');
        $action='';
        $products_filter = $_POST['products_filter'];

        // reset products_price_sorter for searches etc.
        zen_update_products_price_sorter($products_filter);

        zen_redirect(zen_href_link(FILENAME_ATTRIBUTES_CONTROLLER, 'products_filter=' . $products_filter . '&current_category_id=' . $_POST['current_category_id']));
        break;

      case 'delete_option_name_values':
        $delete_attributes_options_id = $db->Execute("select * from " . TABLE_PRODUCTS_ATTRIBUTES . " where products_id='" . $_POST['products_filter'] . "' and options_id='" . $_POST['products_options_id_all'] . "'");
        while (!$delete_attributes_options_id->EOF) {
// remove any attached downloads
          $remove_downloads = $db->Execute("delete from " . TABLE_PRODUCTS_ATTRIBUTES_DOWNLOAD . " where products_attributes_id= '" . $delete_attributes_options_id->fields['products_attributes_id'] . "'");
// remove all option values
          $delete_attributes_options_id_values = $db->Execute("delete from " . TABLE_PRODUCTS_ATTRIBUTES . " where products_id='" . $_POST['products_filter'] . "' and options_id='" . $_POST['products_options_id_all'] . "'");
          $delete_attributes_options_id->MoveNext();
        }

        $action='';
        $products_filter = $_POST['products_filter'];
        $messageStack->add_session(SUCCESS_ATTRIBUTES_DELETED_OPTION_NAME_VALUES. ' ID#' . zen_options_name($_POST['products_options_id_all']), 'success');

        // reset products_price_sorter for searches etc.
        zen_update_products_price_sorter($products_filter);

        zen_redirect(zen_href_link(FILENAME_ATTRIBUTES_CONTROLLER, 'products_filter=' . $products_filter . '&current_category_id=' . $_POST['current_category_id']));
        break;


// attributes copy to product
    case 'update_attributes_copy_to_product':
      $copy_attributes_delete_first = ($_POST['copy_attributes'] == 'copy_attributes_delete' ? '1' : '0');
      $copy_attributes_duplicates_skipped = ($_POST['copy_attributes'] == 'copy_attributes_ignore' ? '1' : '0');
      $copy_attributes_duplicates_overwrite = ($_POST['copy_attributes'] == 'copy_attributes_update' ? '1' : '0');
      zen_copy_products_attributes($_POST['products_filter'], $_POST['products_update_id']);
      $_GET['action']= '';
      $products_filter = $_POST['products_update_id'];
      zen_redirect(zen_href_link(FILENAME_ATTRIBUTES_CONTROLLER, 'products_filter=' . $products_filter . '&current_category_id=' . $_POST['current_category_id']));
      break;

// attributes copy to category
    case 'update_attributes_copy_to_category':
      $copy_attributes_delete_first = ($_POST['copy_attributes'] == 'copy_attributes_delete' ? '1' : '0');
      $copy_attributes_duplicates_skipped = ($_POST['copy_attributes'] == 'copy_attributes_ignore' ? '1' : '0');
      $copy_attributes_duplicates_overwrite = ($_POST['copy_attributes'] == 'copy_attributes_update' ? '1' : '0');
      if ($_POST['categories_update_id'] == '') {
        $messageStack->add_session(WARNING_PRODUCT_COPY_TO_CATEGORY_NONE . ' ID#' . $_POST['products_filter'], 'warning');
      } else {
        $copy_to_category = $db->Execute("select products_id from " . TABLE_PRODUCTS_TO_CATEGORIES . " where categories_id='" . $_POST['categories_update_id'] . "'");
        while (!$copy_to_category->EOF) {
          zen_copy_products_attributes($_POST['products_filter'], $copy_to_category->fields['products_id']);
          $copy_to_category->MoveNext();
        }
      }
      $_GET['action']= '';
      $products_filter = $_POST['products_filter'];
      zen_redirect(zen_href_link(FILENAME_ATTRIBUTES_CONTROLLER, 'products_filter=' . $products_filter . '&current_category_id=' . $_POST['current_category_id']));
      break;

    }
  }

//iii 031103 added to get results from database option type query
  $products_options_types_list = array();
//  $products_options_type_array = $db->Execute("select products_options_types_id, products_options_types_name from " . TABLE_PRODUCTS_OPTIONS_TYPES . " where language_id='" . $_SESSION['languages_id'] . "' order by products_options_types_id");
  $products_options_type_array = $db->Execute("select products_options_types_id, products_options_types_name from " . TABLE_PRODUCTS_OPTIONS_TYPES . " order by products_options_types_id");
  while (!$products_options_type_array->EOF) {
    $products_options_types_list[$products_options_type_array->fields['products_options_types_id']] = $products_options_type_array->fields['products_options_types_name'];
    $products_options_type_array->MoveNext();
  }

//CLR 030312 add function to draw pulldown list of option types
// Draw a pulldown for Option Types
//iii 031103 modified to use results of database option type query from above
function draw_optiontype_pulldown($name, $default = '') {
  global $products_options_types_list;
  $values = array();
  foreach ($products_options_types_list as $id => $text) {
    $values[] = array('id' => $id, 'text' => $text);
  }
  return zen_draw_pull_down_menu($name, $values, $default);
}

//CLR 030312 add function to translate type_id to name
// Translate option_type_values to english string
//iii 031103 modified to use results of database option type query from above
function translate_type_to_name($opt_type) {
  global $products_options_types_list;
  return $products_options_types_list[$opt_type];
}

  function zen_js_option_values_list($selectedName, $fieldName) {
    global $db, $show_value_numbers;
    $attributes_sql = "SELECT povpo.products_options_id, povpo.products_options_values_id, po.products_options_name, po.products_options_sort_order,
                       pov.products_options_values_name, pov.products_options_values_sort_order
                       FROM " . TABLE_PRODUCTS_OPTIONS_VALUES_TO_PRODUCTS_OPTIONS . " povpo, " . TABLE_PRODUCTS_OPTIONS . " po, " . TABLE_PRODUCTS_OPTIONS_VALUES . " pov
                       WHERE povpo.products_options_id = po.products_options_id
                       AND povpo.products_options_values_id = pov.products_options_values_id
                       AND pov.language_id = po.language_id
                       AND po.language_id = " . $_SESSION['languages_id'] . "
                       ORDER BY po.products_options_id, po.products_options_name, pov.products_options_values_name";

//           "
//           ORDER BY po.products_options_name, pov.products_options_values_sort_order";

    $attributes = $db->Execute($attributes_sql);

    $counter = 1;
    $val_count = 0;
    $value_string = '  // Build conditional Option Values Lists' . "\n";
    $last_option_processed = null;
    while (!$attributes->EOF) {
      $products_options_values_name = str_replace('-', '\-', $attributes->fields['products_options_values_name']);
      $products_options_values_name = str_replace('(', '\(', $products_options_values_name);
      $products_options_values_name = str_replace(')', '\)', $products_options_values_name);
      $products_options_values_name = str_replace('"', '\"', $products_options_values_name);
      $products_options_values_name = str_replace('&quot;', '\"', $products_options_values_name);
      $products_options_values_name = str_replace('&frac12;', '1/2', $products_options_values_name);

      if ($counter == 1) {
        $value_string .= '  if (' . $selectedName . ' == "' . $attributes->fields['products_options_id'] . '") {' . "\n";
      } elseif ($last_option_processed != $attributes->fields['products_options_id']) {
        $value_string .= '  } else if (' . $selectedName . ' == "' . $attributes->fields['products_options_id'] . '") {' . "\n";
        $val_count = 0;
      }

      $value_string .= '    ' . $fieldName . '.options[' . $val_count . '] = new Option("' . $products_options_values_name . ($attributes->fields['products_options_values_id'] == 0 ? '/UPLOAD FILE' : '') . ($show_value_numbers ? ' [ #' . $attributes->fields['products_options_values_id'] . ' ] ' : '') . '", "' . $attributes->fields['products_options_values_id'] . '");' . "\n";

      $last_option_processed = $attributes->fields['products_options_id'];;
      $val_count++;
      $counter++;
      $attributes->MoveNext();
    }
    if ($counter > 1) {
      $value_string .= '  }' . "\n";
    }
    return $value_string;
  }*/

?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<link rel="stylesheet" type="text/css" href="includes/cssjsmenuhover.css" media="all" id="hoverJS">
<script language="javascript" src="includes/menu.js"></script>
<script language="javascript" src="includes/general.js"></script>
<script language="javascript"><!--
function go_option() {
  if (document.option_order_by.selected.options[document.option_order_by.selected.selectedIndex].value != "none") {
    location = "<?php echo zen_href_link(FILENAME_ATTRIBUTES_CONTROLLER, 'option_page=' . ($_GET['option_page'] ? $_GET['option_page'] : 1)); ?>&option_order_by="+document.option_order_by.selected.options[document.option_order_by.selected.selectedIndex].value;
  }
}
function popupWindow(url) {
  window.open(url,'popupWindow','toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=no,resizable=yes,copyhistory=no,width=600,height=460,screenX=150,screenY=150,top=150,left=150')
}
//--></script>
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

  function checkValue()
  {
	  var theField = document.getElementById("OptionValue");
	  if (document.attributes.options_id.selectedIndex == -1)
	  {
		  alert("<?php echo TEXT_SELECT_OPTION_NAME; ?>");
		  return false;
	  }
	  else if (theField.selectedIndex == -1)
	  {
		  alert("<?php echo TEXT_SELECT_OPTION_VALUE; ?>");
		  return false;
	  }
	  return true;
  }
  // -->
</script>
</head>
<!-- <body onload="init()"> -->
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF" onload="init()">
<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->

<!-- body_text_eof //-->
<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
