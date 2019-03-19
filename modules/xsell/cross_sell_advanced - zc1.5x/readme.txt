Zen Cart Cross Sell Plus Advanced Sell Combo v1.1
Version Release Date: 2/29/2012
Publisher: PRO-Webs, Inc
Released for Zen Cart v 1.5.0 (V 12-30-2011)


This installation is considered a intermediate level Zen Cart module installation.


If you need installation, please visit our store here:
http://pro-webs.net/store/index.php?main_page=product_info&cPath=10_17&products_id=217


This cross sell module is the easiest to use, has a graphical interface to 
quickly find products to cross sell and is very light weight. 6 cross sell 
products can set specifically set for each of your Zen Cart product pages.

Additionally added in this module is advanced cross sell for even more ease of 
use by allowing you to setup 6 cross sells in the same screen using 
products numbers.


INSTALLATION INSTRUCTIONS
++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

1. FIRST MAKE A FULL BACKUP OF YOUR WEBSITE'S FILES AND DATABASE!


2. Rename the /admin/ directory to match your own admin directory folder name. 


3. Using the Admin->Tools->Install SQL Patch, run the included SQL file (products_xsell.sql)


4. Upload the admin directory module files to your admin directory.
   ** You will need to refresh your admin interface to load the new menu item.


5. Upload all supplied files in their approporiate folder structure in /includes/, 
       
                 
6. Open includes/languages/english/product_info.php 
   or includes/languages/english/YOUR_TEMPLATE/product_info.php 
   and define the language you would like to use in the following example format.
   
             define('TEXT_XSELL_PRODUCTS', 'We Also Recommend :');
        
        or
        
             define('TEXT_XSELL_PRODUCTS', 'Related Products');
        
   just above the final ?> and save it into 
   includes/languages/english/YOUR_TEMPLATE/product_info.php 

    ***Repeat step 6 for each of the other product types you intend to 
         use (ie: product_music_info, document_general_info, etc).
   
   
7.  Edit your product-info template file 
   (includes/templates/templates_default/templates/tpl_product_info_display.php) 
    and insert the following code at the point where you wish the Cross-Sell box 
    to appear. Usually best at the end of the file:


      <?php
      require($template->get_template_dir('tpl_modules_xsell_products.php', DIR_WS_TEMPLATE, $current_page_base,'templates'). '/' . 'tpl_modules_xsell_products.php');
       ?>

   ***Repeat step 7 for any other product types for which you wish to 
      enable cross-sell display. (ie: tpl_product_music_info_display.php, etc) 
       
   

      

INFORMATION
++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
Core file Edits: None
Template Override Changes Yes: product_info.php & tpl_product_info_display.php
Database Changes: Yes, adds additional table products_xsell to store the cross sells


UNINSTALL
++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
Remove the package files and file edits in overrides. Removal of the database 
entry is not required to remove this module from use.


USING CROSS SELL
++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
Adding Cross-Sell details:
  Standard Cross Sell Catalog->Cross Sell Products 
     -Pick a product to add products to. Hit edit, add products. Hit Save.
  Advanced Cross Sell Catalog->Advanced Cross-Sell
     - Fill in Product Model and the Product Models you wish to offer, save.

Configuring how many Cross-Sell items are displayed:
  Admin->Configuration->MINimum Values->Display Cross-Sell Products   (Enter the Min number of items required to display list)
  Admin->Configuration->MAXimum Values->Display Cross-Sell Products   (Enter the Max number of cross-sell items to show. 0 to disable site-wide)
  Admin->Product Info->Cross-Sell Products Columns per Row (Enter the number of Cross-Sell items to show per row)
  Admin->Product Info->Cross-Sell - Display Prices (Select whether to display prices in the list of cross-sell products)


SUPPORT
++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
Please use this thread for support
http://www.zen-cart.com/forum/showthread.php?t=193123


VERSION HISTORY
++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
based Cross Sell Advanced by Absolute & Cross Sell by Merlin / DrByte both for 
Zen Cart 1.3.7

1.0 Zen Cart 1.5.0 combo installation creation by PRO-Webs 2-26-2012 

1.1
    Update SQL upgrade remove, syntax issue
    Resolved misnamed admin_delete.gif
    Fixed language error
    Fixed confusing instructions
    Some housecleaning as well


Copyright (c) 2012 PRO-Webs, Inc.