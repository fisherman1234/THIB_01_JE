<?php require_once('Connections/localhost.php'); ?>
<?php
if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }

  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}
}

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  $updateSQL = sprintf("UPDATE BDL_Discussions SET Discussion_Date=%s, View_BDL=%s, Stock_Price=%s, Position_BDL=%s WHERE Discussion_ID=%s",
                       GetSQLValueString($_POST['Discussion_Date'], "date"),
                       GetSQLValueString($_POST['View_BDL'], "text"),
                       GetSQLValueString($_POST['Stock_Price'], "text"),
                       GetSQLValueString($_POST['Position_BDL'], "text"),
                       GetSQLValueString($_POST['Discussion_ID'], "int"));

  mysql_select_db($database_localhost, $localhost);
  $Result1 = mysql_query($updateSQL, $localhost) or die(mysql_error());
}

$colname_current_discussion = "-1";
if (isset($_GET['Discussion_ID'])) {
  $colname_current_discussion = $_GET['Discussion_ID'];
}
mysql_select_db($database_localhost, $localhost);
$query_current_discussion = sprintf("SELECT * FROM BDL_Discussions WHERE Discussion_ID = %s", GetSQLValueString($colname_current_discussion, "int"));
$current_discussion = mysql_query($query_current_discussion, $localhost) or die(mysql_error());
$row_current_discussion = mysql_fetch_assoc($current_discussion);
$totalRows_current_discussion = mysql_num_rows($current_discussion);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Update discussion</title>
<link href="css/oneColElsCtrHdr.css" rel="stylesheet" type="text/css" />
<link type="text/css" href="css/smoothness/jquery-ui-1.8.7.custom.css" rel="stylesheet" />	
<script type="text/javascript" src="js/jquery-1.4.4.min.js"></script>
<script type="text/javascript" src="js/jquery-ui-1.8.7.custom.min.js"></script>
<script type="text/javascript" src="js/tiny_mce/tiny_mce.js"></script>
<script type="text/javascript">
tinyMCE.init({

		// General options

		mode : "textareas",

		theme : "advanced",

		plugins : "pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,wordcount,advlist,autosave",



		// Theme options

		theme_advanced_buttons1 : "save,newdocument,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,styleselect,formatselect,fontselect,fontsizeselect",

		theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,help,code,|,insertdate,inserttime,preview,|,forecolor,backcolor",

		theme_advanced_buttons3 : "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,emotions,iespell,media,advhr,|,print,|,ltr,rtl,|,fullscreen",

		theme_advanced_buttons4 : "insertlayer,moveforward,movebackward,absolute,|,styleprops,|,cite,abbr,acronym,del,ins,attribs,|,visualchars,nonbreaking,template,pagebreak,restoredraft",

		theme_advanced_toolbar_location : "top",

		theme_advanced_toolbar_align : "left",

		theme_advanced_statusbar_location : "bottom",

		theme_advanced_resizing : true,



		// Example content CSS (should be your site CSS)

		content_css : "css/content.css",



		// Drop lists for link/image/media/template dialogs

		template_external_list_url : "lists/template_list.js",

		external_link_list_url : "lists/link_list.js",

		external_image_list_url : "lists/image_list.js",

		media_external_list_url : "lists/media_list.js",



		// Style formats

		style_formats : [

			{title : 'Bold text', inline : 'b'},

			{title : 'Red text', inline : 'span', styles : {color : '#ff0000'}},

			{title : 'Red header', block : 'h1', styles : {color : '#ff0000'}},

			{title : 'Example 1', inline : 'span', classes : 'example1'},

			{title : 'Example 2', inline : 'span', classes : 'example2'},

			{title : 'Table styles'},

			{title : 'Table row 1', selector : 'tr', classes : 'tablerow1'}

		],



		// Replace values for the template plugin

		template_replace_values : {

			username : "Some User",

			staffid : "991234"

		}

	});


</script>
<script>
$(function() {
		$( ".datepicker" ).datepicker({ dateFormat: 'yy-mm-dd' , showButtonPanel: true });
	});
function MM_openBrWindow(theURL,winName,features) { //v2.0
  window.open(theURL,winName,features);
}

function MM_callJS(jsStr) { //v2.0
  return eval(jsStr)
}
//-->
</script>
</head>

<body class="oneColElsCtrHdr">

<div class="full">
  <div id="header">
    <h1>Edit discussion</h1>
    
  <!-- end #header --></div>
  <div id="mainContent">
    <form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1">
      <table width="100%" align="center">
        <tr valign="baseline">
          <td width="21%" align="right" nowrap="nowrap">Discussion date</td>
          <td width="79%"><input type="text" name="Discussion_Date" value="<?php echo htmlentities($row_current_discussion['Discussion_Date'], ENT_COMPAT, 'UTF-8'); ?>" size="32" /></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right" valign="top">BDL View</td>
          <td><textarea name="View_BDL" cols="60" rows="10"><?php echo htmlentities($row_current_discussion['View_BDL'], ENT_COMPAT, 'UTF-8'); ?></textarea></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Stock price</td>
          <td><input type="text" name="Stock_Price" value="<?php echo htmlentities($row_current_discussion['Stock_Price'], ENT_COMPAT, 'UTF-8'); ?>" size="32" /></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">BDL position</td>
          <td><select name="Position_BDL">
            <option value="Long" <?php if (!(strcmp("Long", htmlentities($row_current_discussion['Position_BDL'], ENT_COMPAT, 'UTF-8')))) {echo "SELECTED";} ?>>Long</option>
            <option value="Short" <?php if (!(strcmp("Short", htmlentities($row_current_discussion['Position_BDL'], ENT_COMPAT, 'UTF-8')))) {echo "SELECTED";} ?>>Short</option>
            <option value="Normal" <?php if (!(strcmp("Neutral", htmlentities($row_current_discussion['Position_BDL'], ENT_COMPAT, 'UTF-8')))) {echo "SELECTED";} ?>>Neutral</option>
          </select></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">&nbsp;</td>
          <td><input type="submit" value="Update record" /><input type="submit" onclick="MM_callJS('self.close();')" value="Close" /></td>
        </tr>
      </table>
      <input type="hidden" name="MM_update" value="form1" />
      <input type="hidden" name="Discussion_ID" value="<?php echo $row_current_discussion['Discussion_ID']; ?>" />
    </form>
    <p>&nbsp;</p>
<!-- end #mainContent --></div>
  <div id="footer">
    <p>Footer</p>
  <!-- end #footer --></div>
<!-- end #container --></div>
</body>
</html>
<?php
mysql_free_result($current_discussion);
?>
