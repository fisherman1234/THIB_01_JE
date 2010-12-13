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

$colname_Sector = "-1";
if (isset($_GET['Sector_ID'])) {
  $colname_Sector = $_GET['Sector_ID'];
}
mysql_select_db($database_localhost, $localhost);
$query_Sector = sprintf("SELECT * FROM Sectors WHERE Sector_ID = %s", GetSQLValueString($colname_Sector, "int"));
$Sector = mysql_query($query_Sector, $localhost) or die(mysql_error());
$row_Sector = mysql_fetch_assoc($Sector);
$totalRows_Sector = mysql_num_rows($Sector);

$colname_Available_Stocks = "-1";
if (isset($_GET['Sector_ID'])) {
  $colname_Available_Stocks = $_GET['Sector_ID'];
}
mysql_select_db($database_localhost, $localhost);
$query_Available_Stocks = sprintf("SELECT * FROM Stocks WHERE Sector_ID = %s", GetSQLValueString($colname_Available_Stocks, "int"));
$Available_Stocks = mysql_query($query_Available_Stocks, $localhost) or die(mysql_error());
$row_Available_Stocks = mysql_fetch_assoc($Available_Stocks);
$totalRows_Available_Stocks = mysql_num_rows($Available_Stocks);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Stock</title>
<!--[if IE]>
<style type="text/css"> 
/* place css fixes for all versions of IE in this conditional comment */
.twoColElsLtHdr #sidebar1 { padding-top: 30px; }
.twoColElsLtHdr #mainContent { zoom: 1; padding-top: 15px; }
/* the above proprietary zoom property gives IE the hasLayout it needs to avoid several bugs */
</style>
<![endif]-->
<link href="/css/main.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="/js/jquery-1.4.4.min.js"></script>
<script type="text/javascript" src="/js/jquery-ui-1.8.7.custom.min.js"></script>
<script src="SpryAssets/SpryTabbedPanels.js" type="text/javascript"></script>
<script> 
$(function() {
		$( ".datepicker" ).datepicker({ dateFormat: 'yy-mm-dd' , showButtonPanel: true });
	});
	</script>
<link href="SpryAssets/SpryTabbedPanels.css" rel="stylesheet" type="text/css" />
</head>

<body class="twoColElsLtHdr">

<div id="container">
  <div id="header">
    <h1>Sector : <?php echo $row_Sector['Sector_Name']; ?></h1>
  <!-- end #header --></div>
  <div id="mainContent-full">
  <div id="TabbedPanels1" class="TabbedPanels">
    <ul class="TabbedPanelsTabGroup">
      <li class="TabbedPanelsTab" tabindex="0">Stocks</li>
      <li class="TabbedPanelsTab" tabindex="0">Details</li>
    </ul>
    <div class="TabbedPanelsContentGroup">
      <div class="TabbedPanelsContent">
        <table width="100%" border="1" align="center">
          <tr>
            <td width="25%">Stock</td>
            <td width="24%">In charge</td>
            <td width="27%">In portfolio ?</td>
            <td width="24%">Rating</td>
          </tr>
          <?php do { ?>
            <tr>
              <td><a href="Stock.php?Stock_ID=<?php echo $row_Available_Stocks['Stock_ID']; ?>"> <?php echo $row_Available_Stocks['Stock_Name']; ?>&nbsp; </a></td>
              <td><?php echo $row_Available_Stocks['In_Charge']; ?>&nbsp; </td>
              <td><?php echo $row_Available_Stocks['Is_In_Portfolio']; ?>&nbsp; </td>
              <td><?php echo $row_Available_Stocks['Rating']; ?>&nbsp; </td>
            </tr>
            <?php } while ($row_Available_Stocks = mysql_fetch_assoc($Available_Stocks)); ?>
        </table>
        <br />
        <?php echo $totalRows_Available_Stocks ?> Records Total  Content 1</div>
      <div class="TabbedPanelsContent">Content 2</div>
    </div>
  </div>
  <p>&nbsp;</p>
  </div>
  
  <!-- This clearing element should immediately follow the #mainContent div in order to force the #container div to contain all child floats --><br class="clearfloat" />
   <div id="footer">
    <p>BDL Capital Management - 2010</p>
  <!-- end #footer --></div>
<!-- end #container --></div>
<script type="text/javascript">
<!--
var TabbedPanels1 = new Spry.Widget.TabbedPanels("TabbedPanels1");
//-->
</script>
</body>
</html>
<?php
mysql_free_result($Sector);

mysql_free_result($Available_Stocks);
?>
