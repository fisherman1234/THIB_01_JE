<?php require_once('Connections/localhost.php'); ?>
<?php
function changedateusfr($dateus) 
{ 
$datefr=$dateus{8}.$dateus{9}."-".$dateus{5}.$dateus{6}."-".$dateus{0}.$dateus{1}.$dateus{2}.$dateus{3}; 
return $datefr; 
} 

function changedatefrus($datefr) 
{ 
$dateus=$datefr{6}.$datefr{7}.$datefr{8}.$datefr{9}."-".$datefr{3}.$datefr{4}."-".$datefr{0}.$datefr{1}; 
return $dateus; 
} 
?>
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

$colname_Analysis = "-1";
if (isset($_GET['Sector_ID'])) {
  $colname_Analysis = $_GET['Sector_ID'];
}
mysql_select_db($database_localhost, $localhost);
$query_Analysis = sprintf("SELECT * FROM Details WHERE Sector_ID = %s", GetSQLValueString($colname_Analysis, "int"));
$Analysis = mysql_query($query_Analysis, $localhost) or die(mysql_error());
$row_Analysis = mysql_fetch_assoc($Analysis);
$totalRows_Analysis = mysql_num_rows($Analysis);

$colname_Related_Stocks = "-1";
if (isset($_GET['Sector_ID'])) {
  $colname_Related_Stocks = $_GET['Sector_ID'];
}
mysql_select_db($database_localhost, $localhost);
$query_Related_Stocks = sprintf("SELECT * FROM Stocks WHERE Sector_ID = %s", GetSQLValueString($colname_Related_Stocks, "int"));
$Related_Stocks = mysql_query($query_Related_Stocks, $localhost) or die(mysql_error());
$row_Related_Stocks = mysql_fetch_assoc($Related_Stocks);
$totalRows_Related_Stocks = mysql_num_rows($Related_Stocks);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Sector : <?php echo $row_Sector['Sector_Name']; ?></title>
</head>

<body>
<h1>Sector : <?php echo $row_Sector['Sector_Name']; ?></h1>
<h3>Available analysis </h3>
<?php do { ?>
  <table width="100%" border="1">
    <tr>
      <td width="15%">Date</td>
      <td><?php echo changedateusfr($row_Analysis['Last_Entry_Date']); ?></td>
    </tr>
    <tr>
      <td width="15%">Reference</td>
      <td><?php echo $row_Analysis['Detail_ID']; ?></td>
    </tr>
    <tr>
      <td width="15%">Title</td>
      <td><?php echo $row_Analysis['Sector_Analysis_Title']; ?></td>
    </tr>
    <tr>
      <td width="15%">Content</td>
      <td><?php echo $row_Analysis['Sector_Analysis_Text']; ?></td>
    </tr>
  </table>
  <br />
  <?php } while ($row_Analysis = mysql_fetch_assoc($Analysis)); ?>
<p>&nbsp;</p>
<h3>Related stocks</h3>
<table width="100%" border="1">
  <tr>
    <td width="50%">Stock</td>
    <td>Rating</td>
    <td>Flagged</td>
    <td>In portfolio ?</td>
  </tr>
  <?php do { ?>
    <tr>
      <td width="50%"><?php echo $row_Related_Stocks['Stock_Name']; ?></td>
      <td><?php echo $row_Related_Stocks['Rating']; ?></td>
      <td><?php echo $row_Related_Stocks['Flagged']; ?></td>
      <td><?php echo $row_Related_Stocks['Is_In_Portfolio']; ?></td>
    </tr>
    <?php } while ($row_Related_Stocks = mysql_fetch_assoc($Related_Stocks)); ?>
</table>
<p>&nbsp;</p>
</body>
</html>
<?php
mysql_free_result($Sector);

mysql_free_result($Analysis);

mysql_free_result($Related_Stocks);
?>
