Unzip cross_sell_advanced - zc1.5x.zip and install following the docs.



After Cross Sell is installed and running upload the content of the uploads dir to the root of your shop.
Change the dir name from privatearea to whatever is your privatearea dir name if necessary.

Run the following query on the install sql patch:
REPLACE INTO provider_accesscontrol (accesscontrol_id, accesscontrol_title, accesscontrol_master, accesscontrol_parent, accesscontrol_filename) VALUES ('17', 'BOX_CATALOG_ADVANCED_XSELL_PRODUCTS', '0', '1', 'xsell_advanced');

After installing the module and making sure that you can access the page, run the following:
(change PREFIX to your DB prefix)
UPDATE products_xsell px SET provider_id = (SELECT manufacturers_id FROM PREFIXproducts p WHERE products_id = px.products_id);

http://customscriptz.com