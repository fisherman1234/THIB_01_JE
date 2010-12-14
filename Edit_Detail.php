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
  $updateSQL = sprintf("UPDATE Details SET Sector_ID=%s, Last_Entry_Date=%s, Sector_Analysis_Title=%s, Sector_Analysis_Text=%s WHERE Detail_ID=%s",
                       GetSQLValueString($_POST['Sector_ID'], "int"),
                       GetSQLValueString($_POST['Last_Entry_Date'], "date"),
                       GetSQLValueString($_POST['Sector_Analysis_Title'], "text"),
                       GetSQLValueString($_POST['Sector_Analysis_Text'], "text"),
                       GetSQLValueString($_POST['Detail_ID'], "int"));

  mysql_select_db($database_localhost, $localhost);
  $Result1 = mysql_query($updateSQL, $localhost) or die(mysql_error());
}

$colname_Current_Detail = "-1";
if (isset($_GET['Detail_ID'])) {
  $colname_Current_Detail = $_GET['Detail_ID'];
}
mysql_select_db($database_localhost, $localhost);
$query_Current_Detail = sprintf("SELECT * FROM Details WHERE Detail_ID = %s", GetSQLValueString($colname_Current_Detail, "int"));
$Current_Detail = mysql_query($query_Current_Detail, $localhost) or die(mysql_error());
$row_Current_Detail = mysql_fetch_assoc($Current_Detail);
$totalRows_Current_Detail = mysql_num_rows($Current_Detail);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Untitled Document</title>
<link href="css/main.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/jquery-1.4.4.min.js"></script>
<script type="text/javascript" src="js/jquery-ui-1.8.7.custom.min.js"></script>
<link type="text/css" href="css/smoothness/jquery-ui-1.8.7.custom.css" rel="stylesheet" />
<link href="css/oneColElsCtrHdr.css" rel="stylesheet" type="text/css" />
<script>
$(function() {
		$( ".datepicker" ).datepicker({ dateFormat: 'yy-mm-dd' , showButtonPanel: true });
	});
function MM_callJS(jsStr) { //v2.0
  return eval(jsStr)
}
</script>
<style type="text/css">
<!--
.oneColElsCtrHdr #container #mainContent #form1 table tr td {
	font-size: 10pt;
}
-->
</style>
<script src="SpryAssets/SpryValidationTextField.js" type="text/javascript"></script>
<link href="SpryAssets/SpryValidationTextField.css" rel="stylesheet" type="text/css" />
</head>

<body class="oneColElsCtrHdr">

<div id="container">
  <div id="header">
    <h1><?php echo $row_Current_Detail['Sector_Analysis_Title']; ?></h1>
    
  <!-- end #header --></div>
  <div id="mainContent">
    <form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1">
      <table width="100%" align="center">
        <tr valign="baseline">
          <td width="29%" align="right" nowrap="nowrap">Date</td>
          <td width="71%"><span id="sprytextfield1">
          <input type="text" name="Last_Entry_Date" class="datepicker" value="<?php echo htmlentities($row_Current_Detail['Last_Entry_Date'], ENT_COMPAT, 'UTF-8'); ?>" size="32" />
<span class="textfieldInvalidFormatMsg">Invalid format.Should be yyyy-mm-dd</span></span></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Sector analysis title</td>
          <td><input type="text" name="Sector_Analysis_Title" value="<?php echo htmlentities($row_Current_Detail['Sector_Analysis_Title'], ENT_COMPAT, 'UTF-8'); ?>" size="70" /></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right" valign="top">Sector analysis content</td>
          <td><textarea name="Sector_Analysis_Text" cols="70" rows="15"><?php echo htmlentities($row_Current_Detail['Sector_Analysis_Text'], ENT_COMPAT, 'UTF-8'); ?></textarea></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">&nbsp;</td>
          <td><input type="submit" value="Update analysis" /><input name="Button" type="button" onclick="MM_callJS('self.close ()')" value="Close" /></td>
        </tr>
      </table>
      <input type="hidden" name="Detail_ID" value="<?php echo $row_Current_Detail['Detail_ID']; ?>" />
      <input type="hidden" name="Sector_ID" value="<?php echo htmlentities($row_Current_Detail['Sector_ID'], ENT_COMPAT, 'UTF-8'); ?>" />
      <input type="hidden" name="MM_update" value="form1" />
      <input type="hidden" name="Detail_ID" value="<?php echo $row_Current_Detail['Detail_ID']; ?>" />
    </form>
    <p>
      <!-- end #mainContent -->
    </p></div>
  <!-- end #container -->
</div>
<script type="text/javascript">
<!--
var sprytextfield1 = new Spry.Widget.ValidationTextField("sprytextfield1", "date", {format:"yyyy-mm-dd", hint:"yyyy-mm-dd", isRequired:false, useCharacterMasking:true});
//-->
</script>
</body>
</html>
<?php
mysql_free_result($Current_Detail);
?>
