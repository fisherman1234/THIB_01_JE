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

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  $updateSQL = sprintf("UPDATE Meetings_Results SET Meeting_Type=%s, Meeting_Date=%s, Meeting_Contact=%s, BDL_Contact=%s, Meeting_Notes=%s, Meeting_Conclusions=%s WHERE Meeting_ID=%s",
                       GetSQLValueString($_POST['Meeting_Type'], "text"),
                       GetSQLValueString($_POST['Meeting_Date'], "date"),
                       GetSQLValueString($_POST['Meeting_Contact'], "text"),
                       GetSQLValueString($_POST['BDL_Contact'], "int"),
                       GetSQLValueString($_POST['Meeting_Notes'], "text"),
                       GetSQLValueString($_POST['Meeting_Conclusions'], "text"),
                       GetSQLValueString($_POST['Meeting_ID'], "int"));

  mysql_select_db($database_localhost, $localhost);
  $Result1 = mysql_query($updateSQL, $localhost) or die(mysql_error());
}

$colname_current_meeting = "-1";
if (isset($_GET['Meeting_ID'])) {
  $colname_current_meeting = $_GET['Meeting_ID'];
}
mysql_select_db($database_localhost, $localhost);
$query_current_meeting = sprintf("SELECT * FROM Meetings_Results WHERE Meeting_ID = %s", GetSQLValueString($colname_current_meeting, "int"));
$current_meeting = mysql_query($query_current_meeting, $localhost) or die(mysql_error());
$row_current_meeting = mysql_fetch_assoc($current_meeting);
$totalRows_current_meeting = mysql_num_rows($current_meeting);

mysql_select_db($database_localhost, $localhost);
$query_All_Users = "SELECT * FROM Users";
$All_Users = mysql_query($query_All_Users, $localhost) or die(mysql_error());
$row_All_Users = mysql_fetch_assoc($All_Users);
$totalRows_All_Users = mysql_num_rows($All_Users);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Update meeting</title>
<link href="css/oneColElsCtrHdr.css" rel="stylesheet" type="text/css" />
<link type="text/css" href="css/smoothness/jquery-ui-1.8.7.custom.css" rel="stylesheet" />	
<script type="text/javascript" src="js/jquery-1.4.4.min.js"></script>
<script type="text/javascript" src="js/jquery-ui-1.8.7.custom.min.js"></script>
<script src="SpryAssets/SpryValidationTextField.js" type="text/javascript"></script>
<script language="javascript" type="text/javascript" src="ajaxfilemanager/jscripts/tiny_mce/tiny_mce.js"></script>


	<script language="javascript" type="text/javascript">

		tinyMCE.init({

			mode : "textareas",

			elements : "ajaxfilemanager",

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


			file_browser_callback : "ajaxfilemanager",

			paste_use_dialog : false,

			

		});



		function ajaxfilemanager(field_name, url, type, win) {

			var ajaxfilemanagerurl = "/ajaxfilemanager/jscripts/tiny_mce/plugins/ajaxfilemanager/ajaxfilemanager.php";

			switch (type) {

				case "image":

					break;

				case "media":

					break;

				case "flash": 

					break;

				case "file":

					break;

				default:

					return false;

			}

            tinyMCE.activeEditor.windowManager.open({

                url: "/ajaxfilemanager/jscripts/tiny_mce/plugins/ajaxfilemanager/ajaxfilemanager.php",

                width: 782,

                height: 440,

                inline : "yes",

                close_previous : "no"

            },{

                window : win,

                input : field_name

            });

            

/*            return false;			

			var fileBrowserWindow = new Array();

			fileBrowserWindow["file"] = ajaxfilemanagerurl;

			fileBrowserWindow["title"] = "Ajax File Manager";

			fileBrowserWindow["width"] = "782";

			fileBrowserWindow["height"] = "440";

			fileBrowserWindow["close_previous"] = "no";

			tinyMCE.openWindow(fileBrowserWindow, {

			  window : win,

			  input : field_name,

			  resizable : "yes",

			  inline : "yes",

			  editor_id : tinyMCE.getWindowArg("editor_id")

			});

			

			return false;*/

		}

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
<link href="SpryAssets/SpryValidationTextField.css" rel="stylesheet" type="text/css" />
</head>

<body class="oneColElsCtrHdr">

<div class="full">
  <div id="header">
    <h1>Edit meeting</h1>
    
  <!-- end #header --></div>
  <div id="mainContent">
    <p><a href="PrintMeeting.php?Meeting_ID=<?php echo $_GET[Meeting_ID]; ?>">Print</a></p>
    <form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1">
      <table width="100%" align="center">
        <tr valign="baseline">
          <td width="27%" align="right" nowrap="nowrap">Meeting type</td>
          <td width="73%" valign="baseline"><table>
            <tr>
              <td><input type="radio" name="Meeting_Type" value="Management meeting" <?php if (!(strcmp(htmlentities($row_current_meeting['Meeting_Type'], ENT_COMPAT, 'UTF-8'),"Management meeting"))) {echo "checked=\"checked\"";} ?> />
                Management meeting</td>
            </tr>
            <tr>
              <td><input type="radio" name="Meeting_Type" value="Analyst meeting" <?php if (!(strcmp(htmlentities($row_current_meeting['Meeting_Type'], ENT_COMPAT, 'UTF-8'),"Analyst meeting"))) {echo "checked=\"checked\"";} ?> />
                Analyst meeting</td>
            </tr>
            <tr>
              <td><input type="radio" name="Meeting_Type" value="Expert meeting" <?php if (!(strcmp(htmlentities($row_current_meeting['Meeting_Type'], ENT_COMPAT, 'UTF-8'),"Expert meeting"))) {echo "checked=\"checked\"";} ?> />
                Expert meeting</td>
            </tr>
            <tr>
              <td><input type="radio" name="Meeting_Type" value="Results" <?php if (!(strcmp(htmlentities($row_current_meeting['Meeting_Type'], ENT_COMPAT, 'UTF-8'),"Results"))) {echo "checked=\"checked\"";} ?> />
                Results</td>
            </tr>
          </table></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Meeting date</td>
          <td><span id="sprytextfield1">
          <input type="text" name="Meeting_Date" class="datepicker" value="<?php echo htmlentities($row_current_meeting['Meeting_Date'], ENT_COMPAT, 'UTF-8'); ?>" size="32" />
<span class="textfieldInvalidFormatMsg">Invalid format. Should be yyyy-mm-dd</span></span></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Meeting contact</td>
          <td><input type="text" name="Meeting_Contact" value="<?php echo htmlentities($row_current_meeting['Meeting_Contact'], ENT_COMPAT, 'UTF-8'); ?>" size="32" /></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">BDL contact</td>
          <td><select name="BDL_Contact">
            <?php 
do {  
?>
            <option value="<?php echo $row_All_Users['Id']?>" <?php if (!(strcmp($row_All_Users['Id'], htmlentities($row_current_meeting['BDL_Contact'], ENT_COMPAT, 'UTF-8')))) {echo "SELECTED";} ?>><?php echo $row_All_Users['Initiales']?></option>
            <?php
} while ($row_All_Users = mysql_fetch_assoc($All_Users));
?>
          </select></td>
        </tr>
        <tr> </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right" valign="top">Meeting notes</td>
          <td><textarea name="Meeting_Notes" cols="80" rows="10"><?php echo htmlentities($row_current_meeting['Meeting_Notes'], ENT_COMPAT, 'UTF-8'); ?></textarea></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right" valign="top">Meeting conclusions</td>
          <td><textarea name="Meeting_Conclusions" cols="80" rows="10"><?php echo htmlentities($row_current_meeting['Meeting_Conclusions'], ENT_COMPAT, 'UTF-8'); ?></textarea></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="left"><a href="PrintMeeting.php?Meeting_ID=<?php echo $_GET[Meeting_ID]; ?>"></a></td>
          <td><input type="submit" value="Update record" />
          <input name="Submit" type="submit" onclick="MM_callJS('self.close()')" value="Close" /></td>
        </tr>
      </table>
      <input type="hidden" name="MM_update" value="form1" />
      <input type="hidden" name="Meeting_ID" value="<?php echo $row_current_meeting['Meeting_ID']; ?>" />
    </form>
    <p>&nbsp;</p>
<p>&nbsp;</p>
    <p>&nbsp;</p>
<!-- end #mainContent --></div>
  <div id="footer">
    <p>Footer</p>
  <!-- end #footer --></div>
<!-- end #container --></div>
<script type="text/javascript">
<!--
var sprytextfield1 = new Spry.Widget.ValidationTextField("sprytextfield1", "date", {format:"yyyy-mm-dd", isRequired:false});
//-->
</script>
</body>
</html>
<?php
mysql_free_result($current_meeting);

mysql_free_result($All_Users);
?>
