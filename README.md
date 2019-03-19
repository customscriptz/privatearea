**PrivateArea** is a module for [Zen
Cart](http://www.zen-cart.com) where Manufacturers (also called
Designers by Scrapbooking Stores) can have it's own area where they can
**individually** manage Products, Coupons, Sales, Special prices and
more, withow accessing other Manufacturers areas.

*Copyright* 2003-2019 Zen Cart Development Team
*Copyright* Portions Copyright 2003 osCommerce

*Author* Diego Vieira - https://customscriptz.com

# System Requirements

[Zen Cart v1.3.8a or newer](http://www.zen-cart.com)  
[PHP 5 or newer](http://php.net)  
[MySQL 5 or newer](http://www.mysql.com)  

# Features

In **PrivateArea**, we refer to Manufacturers as Providers.

Each Provider is isolated using permissions where each Provider can only see their own area.

- Providers can manage their own inventory (products/category)
- Providers can create discount coupons for their own products
- Providers can send coupons for their customers via email
- Providers can setup sales and create specials prices for their own products
- Providers can edit their own products images (module required)
- Providers can run their own Sales Report (module required)
- Providers cannot access other providers products/category
- Providers can edit their Own Terms of Use that will be shown on the Store
- Admin can set the products to be on a queue before the products go live
- Admin can see who has logged in or incorrect logins
- Statistics panel on the index page - Best-Selling Products, Last Sold Products and Last Free Products Sold

and much more...


# Before Installing / Upgrading
- Thought our modules are exhausted tested, we do not guarantee that everything goes smoothly, so please, BACKUP YOUR DATABASE AND FILES before proceeding.

# Installation Instructions

- Open `uploads/privatearea/includes` and rename the file `dist-configure.php` to `configure.php`.
- Edit the file `configure.php` with [Notepad++](http://notepad-plus.sourceforge.net/) or with another editor. Don't open it with Word or Wordpad.
- Around line 13 and 14 you will notice these lines:

```
  define('STORE_DIR', '/shop/');
  define('ADMIN_DIR', '/shop/admin/');
```
- Where you see `shop`, change it to the dir that your store is installed (sometimes called store/boutique/zencart). If your store is on the root of your domain: `http://www.example.com/` instead of `http://www.example.com/shop` delete the name "shop" and leave the trailing slash. Same goes to the second setting. eg.:

``` 
  define('STORE_DIR', '/');
  define('ADMIN_DIR', '/admin/');
```

- If your store dir or admin has a different name change it accordly. eg.:

``` 
  define('STORE_DIR', '/store/');
  define('ADMIN_DIR', '/store/myshopadmincp/');
```

- Save and close this file.
- Optionally, if you have your own styles from your `admin` panel, copy the following files: cssjsmenuhover.css, index.css, menu.css, nde-basic.css, stylesheet.css and stylesheet_print.css from `admin/includes` to `privatearea/includes`.
- If you want to change the default logo, overwrite the file `privatearea/images/logo.gif` with your own.
- Upload all the contents of the dir `uploads` to the root of your store. Don't upload the `uploads` dir, only what's inside of it. There are no overwrites.
- Wait for the upload of all files to complete.
- Access http://www.your-domain.com/your-admin/providers.php?action=updateDB
- If your Zen Cart version is 1.3.x -> go to `Admin -> Tools -> PrivateArea - Providers`
- If your Zen Cart version is 1.5.x -> go to `Admin -> Custom Scriptz -> PrivateArea - Providers`
- Now you proceed with the tutorial below where you will set your environment up.

# Upgrade Instructions

- Upload all the contents of the dir `uploads` to the root of your store. Don't upload the `uploads` dir, only what's inside of it. There are no overwrites.
- Wait for the upload of all files to complete.
- Access http://www.your-domain.com/your-admin/providers.php?action=updateDB
- If your Zen Cart version is 1.3.x -> go to `Admin -> Tools -> PrivateArea - Providers`
- If your Zen Cart version is 1.5.x -> go to `Admin -> Custom Scriptz -> PrivateArea - Providers`
- You should see a notice saying that PrivateArea was updated. That's it.

# Settings

**Settings can be found at: Configuration -> PrivateArea**

- Mostly settings are self-explanatory and each has a description when you click on it. Just click the setting and look at the right panel to know what is it for.

# Tutorial

# Admin Panel

- If your Zen Cart version is equals to 1.3.x -> go to Admin -> Tools -> PrivateArea - Providers
- If your Zen Cart version is equals or above 1.5.x -> go to Admin -> Custom Scriptz -> PrivateArea - Providers

If you already have Manufacturers (a.k.a Designers) on your store, you
will see all your Manufacturers on this page, it's ok, because
PrivateArea is connected with the Manufacturers data. Keep in mind that
"Catalog -> Manufacturers" is the same as "Tools -> Providers".
**Deleting a provider will also delete the manufacturer and vice
versa**.

- Before you start creating/editing Providers, you must create a category separately for each Manufacturer (if haven't done so). A common structure is: One main category called "*Designers*" or "*Hidden*" (including the *, so it will be shown on the top of the categories) on the Top and set this category do disable, and inside of this main category, create the each Manufacturer category.

- You can follow the instructions below or watch this video tutorial: <http://www.youtube.com/watch?v=SpjJmAgppes>

- To create a new provider, just click the "Insert" button. To edit a provider, click on the (I) icon and click the "Edit" button on the right panel - also you can click the green (E) icon next to the provider name.
- Enter all required details about it. If you are editing a provider (after the first time you did), you don't need to enter the password field again, so it will not change the current. You can't have 2 providers with the same Name, Email or Login.
- **Provider Download Dir:** If you want your providers to be able to select the file for the attributes, enter here their dir name that is inside the download dir. eg.:

If you have a provider called "joe" and his dir is at
/home/domain/store/download/joe - set the field Provider Download Dir to
joe - no spaces or dash.

**Note:** Don't use special characters such as **"**, **&**, **%**,
**#** etc or spaces in login and password fields. Underscore (_),
numbers (0-9) and characters (a-z) are allowed.

- To save the provider click the "Save" button.
- When you insert a new provider or edit one with no login name, him/her will be able to receive a email message with his login and password and the URL where they can access their own private area. There is no validation for the email field, so if you mistype the email address, the provider will receive nothing and you need to check the "Send Login Details" checkbox to have it sent again. They can change their own password later on.
- Sample message that's sent to the provider with their login
details:

```
Congratulations "Provider Name", you are now officially a part of our team!
Your new login details to access your own private area: Username: "Provider Login" Password: "Provider Password"  You can log in here: http://www.example.com/shop/privatearea  Best regards, "Store Name"
```

- As you can guess, if your manufacturers was accessing your admin panel to manage their stuff, them they will not have to do it anymore.

**The next step is NOT necessary if your store has just been installed
or you don't have any admin besides you.**

- After creating your providers login you can go to the Admin Panel of your Store -> Tools -> Admin Settings and delete all logins that are not admins. DON'T delete the manufacturers from Catalog -> Manufacturers, just the logins from the Admin page that ARE NOT admins, but manufacturers. Also, don't delete yourself. You must keep at least one administrator account.

# Products on Queue

If you want new products added to your store to be on a queue before
they go live, you can activate the "Products on Queue" feature. This is
usefull if you want to review products to check for mistakes before they
go to live on your store.

- **How do I enable Products to be on Queue?**
- Go to the Admin Panel of your Store -> Configuration -> PrivateArea -> Set **Put Products on Queue** to True.

- **How this works?**
- After you set **Put Products on Queue** to True, every new product will be on a queue so you can check if you will release it or not. The product will not be available on the store front if you don't change his status. After the provider has been inserted the product the store owner will receive a email that one new product has been added. After the store owner release/activate the product, the provider will be notified and the product will go live on the store.

- **Where can I check all products that are on queue?**
- If your Zen Cart version is equals to 1.3.x -> go to Admin -> Tools -> PrivateArea - Products on Queue
- If your Zen Cart version is equals or above 1.5.x -> go to Admin -> Custom Scriptz -> PrivateArea - Products on Queue

- **Can I manually put a product on queue from the admin/provider side?**
- No, only new products will be on queue if at the insert time the **Put Products on Queue** setting was set to True.

# Providers Terms of Use

Your providers can edit their own "Terms of Use" by going to "My
Profile" page. You can also edit the Terms of Use for providers by going
to the "Admin Panel of your Store -> Tools -> PrivateArea -
Providers". Those that have a Terms of Use will be listed at
<http://www.example.com/shop/index.php?main_page=terms_of_use> You can
add this link to the Information sidebox or to the header of your store,
so your customers will be able to see the providers Terms of Use. To add
the link to the sidebox:

- Open the file "/shop/includes/modules/sideboxes/information.php" with [Notepad++](http://notepad-plus.sourceforge.net/) or Dreamweaver. Don't open it with Word or Wordpad - this will mess with the file.
- After:
`unset($information);`

- Add:
 `$information[] = '<a href="' . zen_href_link(FILENAME_TERMS_OF_USE) . '">' . BOX_INFORMATION_TERMS_OF_USE . '</a>';`

- Save and upload the modified file back. Then, you should see the link to the Terms of Use page on the information sidebox.

# FAQ

- **How do I change the dir name from "privatearea" to "whatever-i-want"?**
- Go to the Admin Panel of your Store -> Configuration -> PrivateArea -> Edit the setting "PrivateArea Dir Name" and change to whatever you want. Recommended names include: designer-admin, designer-area, etc. Don't use special characteres, also, after changing this setting, remember to rename the dir to match the name changed here. Also, when updating PrivateArea, you will need to rename the PrivateArea dir to match yours.

- **Where should my providers login to manage their stuff?**
- <http://www.example.com/shop/privatearea> or <http://www.example.com/privatearea> - This dependes where you uploaded the PrivateArea dir and if you didn't changed the PrivateArea dir name.

- **I'm a provider (user of PrivateArea), do I have support from [Custom Scriptz](Custom_Scriptz "wikilink")?**  
- You have no support from us. If you found a bug or have a suggestion, contact your shop administrator and him/her will forward your message to us or you in behave of him/her you can contact us.

- **How can I change the login page?**  
- At the root of the PrivateArea dir exist 2 files: "custom_footer.htm.new" and "custom_header.htm.new". Rename it to "custom_footer.htm" and "custom_header.htm". Open custom_header.htm with any HTML editor and edit his contents. Same with custom_footer.htm, upload it back to your site. This will prevent you to overwrite the files on future upgrades.

- **I want you to make the module/page [put here the module/page name] available to my providers, can you do that?**
- Maybe, depends on how the module has been developed or what page your talking about and how much are you willing to pay for us to integrate it on PrivateArea for you. By default no modules are integrated with PrivateArea, but you can buy some at our store including [Sales Report + Payroll](Sales_Report_+_Payroll "wikilink").

- **Can I modify the source code?**
- Yes you can, as long as you keep the credits from Zen Cart and Custom Scriptz.

- **In my store sometimes I have 1 product that has 2 manufactures (collabs), how can I work around with this?**
- There is no workaround for this, you must set the product to 1 manufacturer/provider only. You can maybe create one login to share with both providers.

# License

[GNU GPL license v2](LICENSE)


# Disclaimer
Please note: all tools/scripts in this repo are released for use "AS IS" without any warranties of any kind, including, but not limited to their installation, use, or performance. We disclaim any and all warranties, either express or implied, including but not limited to any warranty of noninfringement, merchantability, and/or fitness for a particular purpose. We do not warrant that the technology will meet your requirements, that the operation thereof will be uninterrupted or error-free, or that any errors will be corrected.

Any use of these scripts and tools is at your own risk. There is no guarantee that they have been through thorough testing in a comparable environment and we are not responsible for any damage or data loss incurred with their use.

You are responsible for reviewing and testing any scripts you run thoroughly before use in any non-testing environment.