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

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT INTO Users (Nom, Email, Initiales) VALUES (%s, %s, %s)",
                       GetSQLValueString($_POST['Nom'], "text"),
                       GetSQLValueString($_POST['Email'], "text"),
                       GetSQLValueString($_POST['Initiales'], "text"));

  mysql_select_db($database_localhost, $localhost);
  $Result1 = mysql_query($insertSQL, $localhost) or die(mysql_error());
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form2")) {
  $insertSQL = sprintf("INSERT INTO Stocks (Stock_Name) VALUES (%s)",
                       GetSQLValueString($_POST['Stock_Name'], "text"));

  mysql_select_db($database_localhost, $localhost);
  $Result1 = mysql_query($insertSQL, $localhost) or die(mysql_error());
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form3")) {
  $insertSQL = sprintf("INSERT INTO Sectors (Sector_Name) VALUES (%s)",
                       GetSQLValueString($_POST['Sector_Name'], "text"));

  mysql_select_db($database_localhost, $localhost);
  $Result1 = mysql_query($insertSQL, $localhost) or die(mysql_error());
}

mysql_select_db($database_localhost, $localhost);
$query_Users = "SELECT * FROM Users";
$Users = mysql_query($query_Users, $localhost) or die(mysql_error());
$row_Users = mysql_fetch_assoc($Users);
$totalRows_Users = mysql_num_rows($Users);

mysql_select_db($database_localhost, $localhost);
$query_Stocks = "SELECT * FROM Stocks";
$Stocks = mysql_query($query_Stocks, $localhost) or die(mysql_error());
$row_Stocks = mysql_fetch_assoc($Stocks);
$totalRows_Stocks = mysql_num_rows($Stocks);

mysql_select_db($database_localhost, $localhost);
$query_Sectors = "SELECT * FROM Sectors";
$Sectors = mysql_query($query_Sectors, $localhost) or die(mysql_error());
$row_Sectors = mysql_fetch_assoc($Sectors);
$totalRows_Sectors = mysql_num_rows($Sectors);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Administration</title>
<link href="css/main.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="/js/jquery-1.4.4.min.js"></script>
<script type="text/javascript" src="/js/jquery-ui-1.8.7.custom.min.js"></script>
<script src="SpryAssets/SpryTabbedPanels.js" type="text/javascript"></script>
<script src="SpryAssets/SpryAccordion.js" type="text/javascript"></script>
<link type="text/css" href="css/smoothness/jquery-ui-1.8.7.custom.css" rel="stylesheet" />
<link href="css/oneColElsCtrHdr.css" rel="stylesheet" type="text/css" />
<script>
$(function() {
		$( ".datepicker" ).datepicker({ dateFormat: 'yy-mm-dd' , showButtonPanel: true });
	});
function MM_callJS(jsStr) { //v2.0
  return eval(jsStr)
};
function MM_openBrWindow(theURL,winName,features) { //v2.0
  window.open(theURL,winName,features);
};
</script>
<style type="text/css">
<!--
.oneColElsCtrHdr #container #mainContent #form1 table tr td {
	font-size: 10pt;
}
-->
</style>

<link href="SpryAssets/SpryTabbedPanels.css" rel="stylesheet" type="text/css" />
<link href="SpryAssets/SpryAccordion.css" rel="stylesheet" type="text/css" />
</head>

<body class="oneColElsCtrHdr">

<div id="container">
  <div id="header">
    <h1>Administration</h1>
    
  <!-- end #header --></div>
  <div id="mainContent">
    <p>
      <!-- end #mainContent -->
  </p>
    <div id="TabbedPanels1" class="TabbedPanels">
      <ul class="TabbedPanelsTabGroup">
        <li class="TabbedPanelsTab" tabindex="0">Add user</li>
        <li class="TabbedPanelsTab" tabindex="0">Add stock</li>
        <li class="TabbedPanelsTab" tabindex="0">Add sector</li>
      </ul>
      <div class="TabbedPanelsContentGroup">
        <div class="TabbedPanelsContent">
          <div id="Accordion1" class="Accordion" tabindex="0">
            <div class="AccordionPanel">
              <div class="AccordionPanelTab">Users</div>
              <div class="AccordionPanelContent">
                <table width="100%" border="1" align="center">
                  <tr>
                    <td width="30%">Nom</td>
                    <td width="32%">Email</td>
                    <td width="38%">Initiales</td>
                  </tr>
                  <?php do { ?>
                  <tr>
                    <td><a href="#" onclick="MM_openBrWindow('Edit_user.php?recordID=<?php echo $row_Users['Id']; ?>','','scrollbars=yes,width=800,height=200')"> <?php echo $row_Users['Nom']; ?></a></td>
                    <td><?php echo $row_Users['Email']; ?>&nbsp; </td>
                    <td><?php echo $row_Users['Initiales']; ?>&nbsp; </td>
                  </tr>
                  <?php } while ($row_Users = mysql_fetch_assoc($Users)); ?>
                </table>
                <p>&nbsp;</p>
              </div>
            </div>
            <div class="AccordionPanel">
              <div class="AccordionPanelTab">Add new user</div>
              <div class="AccordionPanelContent">
                <form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1">
                  <table align="center">
                    <tr valign="baseline">
                      <td nowrap="nowrap" align="right">Name</td>
                      <td><input type="text" name="Nom" value="" size="32" /></td>
                    </tr>
                    <tr valign="baseline">
                      <td nowrap="nowrap" align="right">Email</td>
                      <td><input type="text" name="Email" value="" size="32" /></td>
                    </tr>
                    <tr valign="baseline">
                      <td nowrap="nowrap" align="right">Initials</td>
                      <td><input type="text" name="Initiales" value="" size="32" /></td>
                    </tr>
                    <tr valign="baseline">
                      <td nowrap="nowrap" align="right">&nbsp;</td>
                      <td><input type="submit" value="Insert record" /></td>
                    </tr>
                  </table>
                  <input type="hidden" name="MM_insert" value="form1" />
                </form>
                <p>&nbsp;</p>
              </div>
            </div>
          </div>
          <p>&nbsp;</p>
        </div>
        <div class="TabbedPanelsContent">
          <form action="<?php echo $editFormAction; ?>" method="post" name="form2" id="form2">
            <table align="center">
              <tr valign="baseline">
                <td nowrap="nowrap" align="right">Stock name</td>
                <td><input type="text" name="Stock_Name" value="" size="32" /></td>
              </tr>
              <tr valign="baseline">
                <td nowrap="nowrap" align="right">&nbsp;</td>
                <td><input type="submit" value="Insert stock" /></td>
              </tr>
            </table>
            <input type="hidden" name="MM_insert" value="form2" />
          </form>
          <p>&nbsp;</p>

        </div>
        <div class="TabbedPanelsContent">
          <form action="<?php echo $editFormAction; ?>" method="post" name="form3" id="form3">
          <table align="center">
              <tr valign="baseline">
                <td nowrap="nowrap" align="right">Sector name</td>
                <td><input type="text" name="Sector_Name" value="" size="32" /></td>
              </tr>
              <tr valign="baseline">
                <td nowrap="nowrap" align="right">&nbsp;</td>
                <td><input type="submit" value="Insert record" /></td>
              </tr>
            </table>
            <input type="hidden" name="MM_insert" value="form3" />
          </form>
          <p>&nbsp;</p>
        </div>
      </div>
    </div>
    <p>&nbsp;</p>
  </div>
  <!-- end #container -->
</div>

</script>
<script type="text/javascript">
<!--
var TabbedPanels1 = new Spry.Widget.TabbedPanels("TabbedPanels1");
var Accordion1 = new Spry.Widget.Accordion("Accordion1");
//-->
</script>
</body>
</html>
<?php
mysql_free_result($Users);

mysql_free_result($Stocks);

mysql_free_result($Sectors);
?>
