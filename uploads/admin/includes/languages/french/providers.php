<?php
/**
 * @copyright Custom Scriptz
 * @copyright Copyright 2003-2010 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 */

define('TABLE_HEADING_PROVIDERS', 'PrivateArea - Fournisseurs');
define('TABLE_HEADING_PROVIDERS_COMMISSION', 'Commission de Vente');
define('TABLE_HEADING_PROVIDERS_LAST_LOGIN', 'Derni&egrave;re Connexion');
define('TABLE_HEADING_PROVIDERS_LOGINS', '');
define('TABLE_HEADING_ACTION', 'Action');

define('TEXT_NEVER', 'Jamais');
define('TEXT_NOLOGIN', 'Aucun');

define('HEADING_TITLE_PROVIDERS', '');
define('TEXT_DISPLAY_NUMBER_OF_PROVIDERS', 'Visualisation <b>%d</b> &agrave; <b>%d</b> (de <b>%d</b> fournisseurs)');

define('TEXT_HEADING_NEW_PROVIDER', 'Nouveau');
define('TEXT_HEADING_EDIT_PROVIDER', '&Eacute;diter Fournisseurs');
define('TEXT_HEADING_DELETE_PROVIDER', '');

define('TEXT_DATE_ADDED', 'Date d\'ajout:');
define('TEXT_LAST_MODIFIED', 'Derni&egrave;re modification:');
define('TEXT_PRODUCTS', 'Produits:');
define('TEXT_PRODUCTS_IMAGE_DIR', 'T&eacute;l&eacute;chargez le r&eacute;pertoire:');
define('TEXT_IMAGE_NONEXISTENT', 'Pas d\'image');
define('TEXT_PROVIDERS_IMAGE_MANUAL', '<strong>Vous pouvez &eacute;galement s&eacute;lectionner un fichier image existant &agrave; partir du serveur, nom de fichier:</strong>');

define('TEXT_NEW_PROVIDER_INTRO', 'S\'il vous pla&icirc;t remplir les informations suivantes pour le nouveau fournisseur');
define('TEXT_EDIT_PROVIDER_INTRO', 'S\'il vous pla&icirc;t apporter les modifications n&eacute;cessaires');

define('TEXT_PROVIDERS_NAME', 'Nom du Fournisseur:');
define('TEXT_PROVIDERS_COMMISSION', 'Commission de Vente:');
define('TEXT_PROVIDERS_EMAIL', '');
define('TEXT_PROVIDERS_PAYPAL_EMAIL', '');
define('TEXT_PROVIDERS_PAYPAL_CURRENCY', 'Monnaie PayPal: <br /> <dfn>Ce sera la monnaie utilis&eacute;e lors du paiement de la commission. Peuvent &ecirc;tre modifi&eacute;s &agrave; tout moment.</dfn>');
define('TEXT_PROVIDERS_LOGIN', 'Fournisseur Nom d\'Utilisateur:');
define('TEXT_PROVIDERS_PASSWORD', 'Mot de passe fournisseur: (min: \'. ENTRY_PASSWORD_MIN_LENGTH . \' Caract&egrave;res)');
define('TEXT_PROVIDERS_CONFIRM_PASSWORD', 'Confirmation de votre mot de passe:');
define('TEXT_PROVIDERS_DOWNLOAD_DIR', 'Fournisseur T&eacute;l&eacute;charger Dir:');
define('TEXT_PROVIDERS_DOWNLOAD_DIR_MANUAL', 'If you want to create a new download dir, enter the dir name below:<br /><em>All lowercase, without spaces and without special characteres</em>');
define('TEXT_PROVIDERS_IMAGE_DIR', 'Provider Image Dir:');
define('TEXT_PROVIDERS_IMAGE_DIR', 'Provider Image Dir:');
define('TEXT_PROVIDERS_SEND_LOGIN', 'Envoyer D&eacute;tails de connexion:');
define('TEXT_PROVIDERS_SEND_LOGIN_DETAILS', 'Envoyer les informations de connexion par e-mail.');
define('TEXT_PROVIDERS_CATEGORY', 'Cat&eacute;gorie principale:');
define('TEXT_PROVIDERS_METATAGS_CHARS', 'Caract&egrave;res maximum Meta Tag:');
define('TEXT_PROVIDERS_METATAGS', 'Laissez Meta Tags:');
define('TEXT_PROVIDERS_YES', 'Oui');
define('TEXT_PROVIDERS_NO', 'No');
define('TEXT_PROVIDERS_ACCESS', '<strong>L\'acc&egrave;s aux fournisseurs:</ strong>');
define('TEXT_PROVIDERS_IMAGE', 'L\'image du fournisseur:');
define('TEXT_PROVIDERS_URL', 'URL du fournisseur:');
define('TEXT_PROVIDERS_TOUS', 'Conditions d\'utilisation fournisseur:');
define('BOX_TOOLS_MY_PROFILE', 'Mon profil');
define('BOX_TOOLS_FREEGIFTS', 'Free Gifts');
define('BOX_COUPON_RESTRICT', 'Restreindre coupon');
define('BOX_REPORTS_SALES_REPORT', 'Rapport des ventes');
define('BOX_TOOLS_IMAGE_HANDLER', 'Image Handler');
define('BOX_MULTI_CATEGORY', 'Multiple Categories Link Manager');
define('BOX_CATALOG_ADVANCED_XSELL_PRODUCTS', 'Advanced Cross-Sell');
define('BOX_CATALOG_CATEGORIES_ATTRIBUTES_DOWNLOADS_MANAGER', 'Downloads Manager');
define('TEXT_LOGIN_AS_PROVIDER', 'Connectez-vous en tant que fournisseur de ce');

define('TEXT_DELETE_INTRO', '&Ecirc;tes-vous s&ucirc;r de vouloir supprimer ce fournisseur?');
define('TEXT_DELETE_IMAGE', 'Supprimer l\'image fournisseur?');
define('TEXT_DELETE_PRODUCTS', 'Supprimer des produits de ce fournisseur? (y compris les revues de produits, produits sp&eacute;ciaux, sur les produits &agrave; venir)');
define('TEXT_DELETE_WARNING_PRODUCTS', '<b>AVERTISSEMENT:</b> Il ya %s de produits est toujours li&eacute; &agrave; ce fournisseur!');

define('ERROR_DIRECTORY_NOT_WRITEABLE', 'Erreur: je ne peux pas &eacute;crire dans ce r&eacute;pertoire. S\'il vous pla&icirc;t d&eacute;finir les bonnes permissions sur: %s');
define('ERROR_DIRECTORY_DOES_NOT_EXIST', '');

define('ERROR_PROVIDER_NAME_EXIST', 'Un fournisseur appel&eacute; <strong>%s</ strong> existe d&eacute;j&agrave;, s\'il vous pla&icirc;t choisir un autre nom.');
define('ERROR_PROVIDER_EMAIL_EXIST', 'L\'adresse email <strong>%s</ strong> existe d&eacute;j&agrave;, s\'il vous pla&icirc;t choisir un autre email.');
define('ERROR_PROVIDER_LOGIN_EXIST', 'Un fournisseur avec la connexion <strong>%s</ strong> existe d&eacute;j&agrave;, s\'il vous pla&icirc;t choisir une autre connexion.');
define('ERROR_PROVIDER_NAME_EMPTY', 'Le nom du fournisseur ne peut &ecirc;tre laiss&eacute;e en blanc.');
define('ERROR_PROVIDER_LOGIN_EMPTY', 'La connexion fournisseur ne peut pas &ecirc;tre laiss&eacute; en blanc.');
define('ERROR_PROVIDER_EMAIL_EMPTY', 'The provider email cannot be left blank.');
define('ERROR_PROVIDER_PASSWORD_EMPTY', 'Le mot de passe fournisseur ne peut pas &ecirc;tre laiss&eacute; en blanc.');
define('ERROR_PROVIDER_CREATING_DIR', 'I could not create the dir <strong>%s</strong>, please make sure that the directory doesn\'t exist and that you have sufficient rights. You may also create the dir manually using your FTP software.');

define('ENTRY_PASSWORD_NEW_ERROR', 'Le mot de passe doit contenir un minimum de \'. ENTRY_PASSWORD_MIN_LENGTH. \' Caract&egrave;res.');
define('ENTRY_PASSWORD_NEW_ERROR_NOT_MATCHING', 'La Confirmation mot de passe doit correspondre &agrave; votre mot de passe.');
define('ENTRY_PASSWORD_ERROR_PASSWORD_EMPTY', 'Le champ Mot de passe ne peut pas &ecirc;tre laiss&eacute; en blanc.');

define('PROVIDER_SAVED_SUCCESSFULLY', 'Fournisseur de <strong>%s</ strong> enregistr&eacute; avec succ&egrave;s!');

define('TEXT_MAIL_LOGIN_DATA_SBJ', '');
define('TEXT_MAIL_LOGIN_DATA_MSG', 'F&eacute;licitations %s, vous faites maintenant partie de notre &eacute;quipe de concepteurs.
&nbsp;
Vos informations de connexion de nouveaux pour acc&eacute;der &agrave; votre espace personnalis&eacute;:
Nom d\'utilisateur: %s
Mot de passe: %s
&nbsp;
Vous pouvez vous connecter &agrave;: %s
&nbsp;
Cordialement,
'.STORE_NAME.' Team
');
//permissions
define('PROVIDER_DIR_CREATED', 'J\'ai cr&eacute;&eacute; le r&eacute;pertoire <strong>%s</ strong> automatiquement pour vous.');
define('PROVIDER_DIR_NOT_CREATED', 'J\'ai essay&eacute; de cr&eacute;er le r&eacute;pertoire <strong>%s</ strong> automatiquement pour vous, mais en vain. S\'il vous pla&icirc;t, faites-le en utilisant votre logiciel FTP.');
// sales report
define('TEXT_INFO_USE_GROUP_COMMISSION', 'Use commission group when calculating the commission (<a href="http://customscriptz.com/wiki/Sales_Report_%2B_Payroll#Commission_Groups" target="_blank">read more</a>)<br /><em>* Only for <a href="https://customscriptz.com/cart.php?a=add&pid=6" target="_blank">Sales Report + Payroll</a> owners</em>');
define('TABLE_HEADING_COMMISSION_GROUP', 'Use Commission Group %');