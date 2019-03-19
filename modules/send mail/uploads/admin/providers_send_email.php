<?php
/**
 * @package admin
 * @copyright Copyright 2003-2007 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version $Id: mail.php 7197 2007-10-06 20:35:52Z drbyte $
 */

  require('includes/application_top.php');
  $action = (isset($_GET['action']) ? $_GET['action'] : '');
  
  if ($action == 'set_editor') {
    // Reset will be done by init_html_editor.php. Now we simply redirect to refresh page properly.
    $action='';
    zen_redirect(zen_href_link(FILENAME_PROVIDERS_SEND_EMAIL));
  }
  
  if ($action == 'send_email_to_provider') {
    if ($_POST['providers']) {
      $mail_sent_to = zen_db_prepare_input($_POST['providers']);
    }
  
    // error message if no email address
    if (empty($mail_sent_to)) {
      $messageStack->add_session(TEXT_ERROR_NONE_SELECTED, 'error');
      $_GET['action']='';
      zen_redirect(zen_href_link(FILENAME_PROVIDERS_SEND_EMAIL));
    }
  
    $from = zen_db_prepare_input($_POST['from']);
    $subject = zen_db_prepare_input($_POST['subject']);
    $message = zen_db_prepare_input($_POST['message']);
    $html_msg['EMAIL_MESSAGE_HTML'] = zen_db_prepare_input($_POST['message_html']);
  
  
	for($i=0; $i < count($_POST['providers']); $i++) {
		$mail = $db->Execute('SELECT manufacturers_name, manufacturers_email FROM ' . TABLE_MANUFACTURERS .' WHERE manufacturers_id='.(int)$_POST['providers'][$i]);
		if ($mail->fields['manufacturers_email'])
		{
			$messageStack->add_session(sprintf(TEXT_NOTICE_EMAIL_SENT, $mail->fields['manufacturers_name'], $mail->fields['manufacturers_email']), 'success');
			zen_mail($mail->fields['manufacturers_name'], $mail->fields['manufacturers_email'], $subject, $message, STORE_NAME, $from, $html_msg, 'default');
		}
	}
	
    zen_redirect(zen_href_link(FILENAME_PROVIDERS_SEND_EMAIL));
  }

?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<link rel="stylesheet" type="text/css" href="includes/cssjsmenuhover.css" media="all" id="hoverJS">
<script language="javascript" src="includes/menu.js"></script>
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
  if (typeof _editor_url == "string") HTMLArea.replace('message_html');
}
checked = true;
  function checkUncheckAll () {
	if (checked == false){checked = true}else{checked = false}
	for (var i = 0; i < document.getElementById('mail').elements.length; i++) {
	  document.getElementById('mail').elements[i].checked = checked;
	}
  }
// -->
</script>
<?php if ($editor_handler != '') include ($editor_handler); ?>
</head>
<body onLoad="init()">
<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->

<!-- body //-->
<table border="0" width="100%" cellspacing="2" cellpadding="2">
  <tr>
    <!-- body_text //-->
    <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="0">
      <tr>
      <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
        <tr>
        <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
        <td class="pageHeading" align="right"><?php echo zen_draw_separator('pixel_trans.gif', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
        <td class="main">
<?php
  // toggle switch for editor
  echo TEXT_EDITOR_INFO . zen_draw_form('set_editor_form', FILENAME_PROVIDERS_SEND_EMAIL, '', 'get') . '&nbsp;&nbsp;' . zen_draw_pull_down_menu('reset_editor', $editors_pulldown, $current_editor_key, 'onChange="this.form.submit();"') .
  zen_hide_session_id() .
  zen_draw_hidden_field('action', 'set_editor') .
  '</form>';
?>
        </td>
      </tr>
      </table></td>
    </tr>
    <tr>
      <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
            <tr><?php echo zen_draw_form('mail', FILENAME_PROVIDERS_SEND_EMAIL,'action=send_email_to_provider', 'post', 'id="mail" enctype="multipart/form-data"'); ?>
              <td><table border="0" cellpadding="0" cellspacing="2">
            <tr>
              <td colspan="2"><?php echo zen_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
            </tr>
            <tr>
              <td class="main" style="vertical-align: top;"><?php echo TEXT_CHOOSE_PROVIDERS; ?></td>
              <td>
				<a href="javascript:checkUncheckAll()"><?php echo TEXT_CHECK_UNCHECK; ?></a><br />
				<?php
					$providers = $db->Execute('SELECT * FROM ' . TABLE_MANUFACTURERS . ' WHERE manufacturers_email <> "" ORDER BY manufacturers_name');
					$i = 0;
					while(!$providers->EOF) {
						echo '<label for="'.$providers->fields['manufacturers_id'].'">' . zen_draw_checkbox_field('providers[]', $providers->fields['manufacturers_id'], true, '', 'id="'.$providers->fields['manufacturers_id'].'"') . $providers->fields['manufacturers_name'] . ' (' . $providers->fields['manufacturers_email'] . ')</label><br />';
						
						$i++;
						$providers->MoveNext();
					}
					
					if ($providers->RecordCount() == 0) echo TEXT_ERROR_NO_PROVIDERS_WITH_EMAIL;
				?>
			  </td>
            </tr>
            <tr>
              <td colspan="2"><?php echo zen_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
            </tr>
            <tr>
              <td class="main"><?php echo TEXT_FROM; ?></td>
              <td><?php echo zen_draw_input_field('from', EMAIL_FROM, 'size="50"'); ?></td>
            </tr>
            <tr>
              <td colspan="2"><?php echo zen_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
            </tr>
            <tr>
              <td class="main"><?php echo TEXT_SUBJECT; ?></td>
              <td><?php echo zen_draw_input_field('subject', $_POST['subject'], 'size="50"'); ?></td>
            </tr>
            <tr>
              <td colspan="2"><?php echo zen_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
            </tr>
            <tr>
              <td valign="top" class="main"><?php echo TEXT_HTML_MESSAGE; ?></td>
              <td class="main" width="750">
<?php if (EMAIL_USE_HTML != 'true') echo TEXT_WARNING_HTML_DISABLED; ?>
<?php  if (EMAIL_USE_HTML == 'true') {
    if ($_SESSION['html_editor_preference_status']=="FCKEDITOR") {
      $oFCKeditor = new FCKeditor('message_html') ;
      $oFCKeditor->Value = stripslashes($_POST['message_html']) ;
      $oFCKeditor->Width  = '97%' ;
      $oFCKeditor->Height = '350' ;
//    $oFCKeditor->Create() ;
      $output = $oFCKeditor->CreateHtml() ; echo $output;
    } else { // using HTMLAREA or just raw "source"
      echo zen_draw_textarea_field('message_html', 'soft', '100%', '25', stripslashes($_POST['message_html']), 'id="message_html"');
    } ?>
              </td>
            </tr>
            <tr>
              <td colspan="2"><?php echo zen_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
            </tr>
            <tr>
              <td valign="top" class="main"><?php echo TEXT_TEXT_MESSAGE; ?></td>
              <td><?php echo zen_draw_textarea_field('message', 'soft', '100%', '15', $_POST['message']); ?></td>
            </tr>
            <tr>
              <td colspan="2"><?php echo zen_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
            </tr>
<?php
  if (isset($_GET['origin'])) {
    $origin = $_GET['origin'];
  } else {
    $origin = FILENAME_DEFAULT;
  }
  if (isset($_GET['mode']) && $_GET['mode'] == 'SSL') {
    $mode = 'SSL';
  } else {
    $mode = 'NONSSL';
  }
?>
            <tr>
              <td colspan="2" align="right"><?php echo zen_image_submit('button_send.gif', IMAGE_SEND) . '&nbsp;' .
              '<a href="' . zen_href_link($origin, 'cID=' . zen_db_prepare_input($_GET['cID']), $mode) . '">' . zen_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>'; ?></td>
            </tr>
          </table></td>
        </form></tr>
<?php
}
?>
<!-- body_text_eof //-->
      </table></td>
    </tr>
  </table></td>
</tr>
</table>
<!-- body_eof //-->

<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
<br />
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>