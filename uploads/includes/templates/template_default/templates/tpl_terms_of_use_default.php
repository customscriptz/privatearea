<?php
/**
 * Custom Scriptz | http://customscriptz.com
 * 
 * @package templateSystem
 * @copyright Copyright 2003-2005 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 */
?>
<div class="centerColumn" id="shippingInfo">
<h1 id="shippingInfoHeading"><?php echo HEADING_TITLE; ?></h1>

<div>
<?php echo $tousText; ?>
</div>
<br />

<?php if ((int)$_GET['tousId'] > 0) echo '<div class="buttonRow back"><a href="' . zen_href_link('terms_of_use') . '">' . zen_image_button(BUTTON_IMAGE_BACK, BUTTON_BACK_ALT) . '</a></div>'; ?>
</div>
