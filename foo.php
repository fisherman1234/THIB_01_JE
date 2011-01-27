<?php require_once('Connections/localhost.php'); ?><?php
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

$maxRows_DetailRS1 = 10;
$pageNum_DetailRS1 = 0;
if (isset($_GET['pageNum_DetailRS1'])) {
  $pageNum_DetailRS1 = $_GET['pageNum_DetailRS1'];
}
$startRow_DetailRS1 = $pageNum_DetailRS1 * $maxRows_DetailRS1;

$colname_DetailRS1 = "-1";
if (isset($_GET['recordID'])) {
  $colname_DetailRS1 = $_GET['recordID'];
}
mysql_select_db($database_localhost, $localhost);
$query_DetailRS1 = sprintf("SELECT * FROM Stocks  WHERE Stock_ID = %s", GetSQLValueString($colname_DetailRS1, "int"));
$query_limit_DetailRS1 = sprintf("%s LIMIT %d, %d", $query_DetailRS1, $startRow_DetailRS1, $maxRows_DetailRS1);
$DetailRS1 = mysql_query($query_limit_DetailRS1, $localhost) or die(mysql_error());
$row_DetailRS1 = mysql_fetch_assoc($DetailRS1);

if (isset($_GET['totalRows_DetailRS1'])) {
  $totalRows_DetailRS1 = $_GET['totalRows_DetailRS1'];
} else {
  $all_DetailRS1 = mysql_query($query_DetailRS1);
  $totalRows_DetailRS1 = mysql_num_rows($all_DetailRS1);
}
$totalPages_DetailRS1 = ceil($totalRows_DetailRS1/$maxRows_DetailRS1)-1;

$colname_DetailRS2 = "-1";
if (isset($_GET['recordID'])) {
  $colname_DetailRS2 = $_GET['recordID'];
}
mysql_select_db($database_localhost, $localhost);
$query_DetailRS2 = sprintf("SELECT * FROM Stocks  WHERE Stock_ID = %s", GetSQLValueString($colname_DetailRS2, "int"));
$DetailRS2 = mysql_query($query_DetailRS2, $localhost) or die(mysql_error());
$row_DetailRS2 = mysql_fetch_assoc($DetailRS2);
$totalRows_DetailRS2 = mysql_num_rows($DetailRS2);

$colname_DetailRS3 = "-1";
if (isset($_GET['recordID'])) {
  $colname_DetailRS3 = $_GET['recordID'];
}
mysql_select_db($database_localhost, $localhost);
$query_DetailRS3 = sprintf("SELECT * FROM Meetings_Results, Stocks  WHERE Meeting_ID = %s", GetSQLValueString($colname_DetailRS3, "int"));
$DetailRS3 = mysql_query($query_DetailRS3, $localhost) or die(mysql_error());
$row_DetailRS3 = mysql_fetch_assoc($DetailRS3);
$totalRows_DetailRS3 = mysql_num_rows($DetailRS3);

$colname_DetailRS4 = "-1";
if (isset($_GET['recordID'])) {
  $colname_DetailRS4 = $_GET['recordID'];
}
mysql_select_db($database_localhost, $localhost);
$query_DetailRS4 = sprintf("SELECT * FROM Details, Sectors   WHERE Detail_ID = %s", GetSQLValueString($colname_DetailRS4, "int"));
$DetailRS4 = mysql_query($query_DetailRS4, $localhost) or die(mysql_error());
$row_DetailRS4 = mysql_fetch_assoc($DetailRS4);
$totalRows_DetailRS4 = mysql_num_rows($DetailRS4);

$colname_DetailRS5 = "-1";
if (isset($_GET['recordID'])) {
  $colname_DetailRS5 = $_GET['recordID'];
}
mysql_select_db($database_localhost, $localhost);
$query_DetailRS5 = sprintf("SELECT * FROM Contacts, Stocks  WHERE Stock_ID = %s", GetSQLValueString($colname_DetailRS5, "int"));
$DetailRS5 = mysql_query($query_DetailRS5, $localhost) or die(mysql_error());
$row_DetailRS5 = mysql_fetch_assoc($DetailRS5);
$totalRows_DetailRS5 = mysql_num_rows($DetailRS5);

$colname_DetailRS6 = "-1";
if (isset($_GET['recordID'])) {
  $colname_DetailRS6 = $_GET['recordID'];
}
mysql_select_db($database_localhost, $localhost);
$query_DetailRS6 = sprintf("SELECT * FROM Stocks, BDL_Discussions  WHERE Discussion_ID = %s", GetSQLValueString($colname_DetailRS6, "int"));
$DetailRS6 = mysql_query($query_DetailRS6, $localhost) or die(mysql_error());
$row_DetailRS6 = mysql_fetch_assoc($DetailRS6);
$totalRows_DetailRS6 = mysql_num_rows($DetailRS6);
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Untitled Document</title>
</head>

<body>
<table border="1" align="center">
  <tr>
    <td>Stock_ID</td>
    <td><?php echo $row_DetailRS1['Stock_ID']; ?></td>
  </tr>
  <tr>
    <td>Stock_Name</td>
    <td><?php echo $row_DetailRS1['Stock_Name']; ?></td>
  </tr>
  <tr>
    <td>Sector_ID</td>
    <td><?php echo $row_DetailRS1['Sector_ID']; ?></td>
  </tr>
  <tr>
    <td>In_Charge</td>
    <td><?php echo $row_DetailRS1['In_Charge']; ?></td>
  </tr>
  <tr>
    <td>Environment</td>
    <td><?php echo $row_DetailRS1['Environment']; ?></td>
  </tr>
  <tr>
    <td>Business_Description</td>
    <td><?php echo $row_DetailRS1['Business_Description']; ?></td>
  </tr>
  <tr>
    <td>Competition</td>
    <td><?php echo $row_DetailRS1['Competition']; ?></td>
  </tr>
  <tr>
    <td>Management</td>
    <td><?php echo $row_DetailRS1['Management']; ?></td>
  </tr>
  <tr>
    <td>Is_In_Portfolio</td>
    <td><?php echo $row_DetailRS1['Is_In_Portfolio']; ?></td>
  </tr>
  <tr>
    <td>Valorisation</td>
    <td><?php echo $row_DetailRS1['Valorisation']; ?></td>
  </tr>
  <tr>
    <td>Investment_Case</td>
    <td><?php echo $row_DetailRS1['Investment_Case']; ?></td>
  </tr>
  <tr>
    <td>Investment_Risks_Macro</td>
    <td><?php echo $row_DetailRS1['Investment_Risks_Macro']; ?></td>
  </tr>
  <tr>
    <td>Investment_Risks_Micro</td>
    <td><?php echo $row_DetailRS1['Investment_Risks_Micro']; ?></td>
  </tr>
  <tr>
    <td>Rating</td>
    <td><?php echo $row_DetailRS1['Rating']; ?></td>
  </tr>
  <tr>
    <td>Ticker</td>
    <td><?php echo $row_DetailRS1['Ticker']; ?></td>
  </tr>
  <tr>
    <td>Last_Modified</td>
    <td><?php echo $row_DetailRS1['Last_Modified']; ?></td>
  </tr>
  <tr>
    <td>Flagged</td>
    <td><?php echo $row_DetailRS1['Flagged']; ?></td>
  </tr>
  <tr>
    <td>Flag_Date</td>
    <td><?php echo $row_DetailRS1['Flag_Date']; ?></td>
  </tr>
</table>
<table border="1" align="center">
  <tr>
    <td>Stock_ID</td>
    <td><?php echo $row_DetailRS2['Stock_ID']; ?></td>
  </tr>
  <tr>
    <td>Stock_Name</td>
    <td><?php echo $row_DetailRS2['Stock_Name']; ?></td>
  </tr>
  <tr>
    <td>Sector_ID</td>
    <td><?php echo $row_DetailRS2['Sector_ID']; ?></td>
  </tr>
  <tr>
    <td>In_Charge</td>
    <td><?php echo $row_DetailRS2['In_Charge']; ?></td>
  </tr>
  <tr>
    <td>Environment</td>
    <td><?php echo $row_DetailRS2['Environment']; ?></td>
  </tr>
  <tr>
    <td>Business_Description</td>
    <td><?php echo $row_DetailRS2['Business_Description']; ?></td>
  </tr>
  <tr>
    <td>Competition</td>
    <td><?php echo $row_DetailRS2['Competition']; ?></td>
  </tr>
  <tr>
    <td>Management</td>
    <td><?php echo $row_DetailRS2['Management']; ?></td>
  </tr>
  <tr>
    <td>Is_In_Portfolio</td>
    <td><?php echo $row_DetailRS2['Is_In_Portfolio']; ?></td>
  </tr>
  <tr>
    <td>Valorisation</td>
    <td><?php echo $row_DetailRS2['Valorisation']; ?></td>
  </tr>
  <tr>
    <td>Investment_Case</td>
    <td><?php echo $row_DetailRS2['Investment_Case']; ?></td>
  </tr>
  <tr>
    <td>Investment_Risks_Macro</td>
    <td><?php echo $row_DetailRS2['Investment_Risks_Macro']; ?></td>
  </tr>
  <tr>
    <td>Investment_Risks_Micro</td>
    <td><?php echo $row_DetailRS2['Investment_Risks_Micro']; ?></td>
  </tr>
  <tr>
    <td>Rating</td>
    <td><?php echo $row_DetailRS2['Rating']; ?></td>
  </tr>
  <tr>
    <td>Ticker</td>
    <td><?php echo $row_DetailRS2['Ticker']; ?></td>
  </tr>
  <tr>
    <td>Last_Modified</td>
    <td><?php echo $row_DetailRS2['Last_Modified']; ?></td>
  </tr>
  <tr>
    <td>Flagged</td>
    <td><?php echo $row_DetailRS2['Flagged']; ?></td>
  </tr>
  <tr>
    <td>Flag_Date</td>
    <td><?php echo $row_DetailRS2['Flag_Date']; ?></td>
  </tr>
</table>
<table border="1" align="center">
  <tr>
    <td>Meeting_ID</td>
    <td><?php echo $row_DetailRS3['Meeting_ID']; ?></td>
  </tr>
  <tr>
    <td>Stock_ID</td>
    <td><?php echo $row_DetailRS3['Stock_ID']; ?></td>
  </tr>
  <tr>
    <td>Meeting_Type</td>
    <td><?php echo $row_DetailRS3['Meeting_Type']; ?></td>
  </tr>
  <tr>
    <td>Meeting_Date</td>
    <td><?php echo $row_DetailRS3['Meeting_Date']; ?></td>
  </tr>
  <tr>
    <td>Meeting_Contact</td>
    <td><?php echo $row_DetailRS3['Meeting_Contact']; ?></td>
  </tr>
  <tr>
    <td>BDL_Contact</td>
    <td><?php echo $row_DetailRS3['BDL_Contact']; ?></td>
  </tr>
  <tr>
    <td>Meeting_Notes</td>
    <td><?php echo $row_DetailRS3['Meeting_Notes']; ?></td>
  </tr>
  <tr>
    <td>Meeting_Conclusions</td>
    <td><?php echo $row_DetailRS3['Meeting_Conclusions']; ?></td>
  </tr>
  <tr>
    <td>Stock_ID</td>
    <td><?php echo $row_DetailRS3['Stock_ID']; ?></td>
  </tr>
  <tr>
    <td>Stock_Name</td>
    <td><?php echo $row_DetailRS3['Stock_Name']; ?></td>
  </tr>
  <tr>
    <td>Sector_ID</td>
    <td><?php echo $row_DetailRS3['Sector_ID']; ?></td>
  </tr>
  <tr>
    <td>In_Charge</td>
    <td><?php echo $row_DetailRS3['In_Charge']; ?></td>
  </tr>
  <tr>
    <td>Environment</td>
    <td><?php echo $row_DetailRS3['Environment']; ?></td>
  </tr>
  <tr>
    <td>Business_Description</td>
    <td><?php echo $row_DetailRS3['Business_Description']; ?></td>
  </tr>
  <tr>
    <td>Competition</td>
    <td><?php echo $row_DetailRS3['Competition']; ?></td>
  </tr>
  <tr>
    <td>Management</td>
    <td><?php echo $row_DetailRS3['Management']; ?></td>
  </tr>
  <tr>
    <td>Is_In_Portfolio</td>
    <td><?php echo $row_DetailRS3['Is_In_Portfolio']; ?></td>
  </tr>
  <tr>
    <td>Valorisation</td>
    <td><?php echo $row_DetailRS3['Valorisation']; ?></td>
  </tr>
  <tr>
    <td>Investment_Case</td>
    <td><?php echo $row_DetailRS3['Investment_Case']; ?></td>
  </tr>
  <tr>
    <td>Investment_Risks_Macro</td>
    <td><?php echo $row_DetailRS3['Investment_Risks_Macro']; ?></td>
  </tr>
  <tr>
    <td>Investment_Risks_Micro</td>
    <td><?php echo $row_DetailRS3['Investment_Risks_Micro']; ?></td>
  </tr>
  <tr>
    <td>Rating</td>
    <td><?php echo $row_DetailRS3['Rating']; ?></td>
  </tr>
  <tr>
    <td>Ticker</td>
    <td><?php echo $row_DetailRS3['Ticker']; ?></td>
  </tr>
  <tr>
    <td>Last_Modified</td>
    <td><?php echo $row_DetailRS3['Last_Modified']; ?></td>
  </tr>
  <tr>
    <td>Flagged</td>
    <td><?php echo $row_DetailRS3['Flagged']; ?></td>
  </tr>
  <tr>
    <td>Flag_Date</td>
    <td><?php echo $row_DetailRS3['Flag_Date']; ?></td>
  </tr>
</table>
<table border="1" align="center">
  <tr>
    <td>Detail_ID</td>
    <td><?php echo $row_DetailRS4['Detail_ID']; ?></td>
  </tr>
  <tr>
    <td>Sector_ID</td>
    <td><?php echo $row_DetailRS4['Sector_ID']; ?></td>
  </tr>
  <tr>
    <td>Last_Entry_Date</td>
    <td><?php echo $row_DetailRS4['Last_Entry_Date']; ?></td>
  </tr>
  <tr>
    <td>Sector_Analysis_Title</td>
    <td><?php echo $row_DetailRS4['Sector_Analysis_Title']; ?></td>
  </tr>
  <tr>
    <td>Sector_Analysis_Text</td>
    <td><?php echo $row_DetailRS4['Sector_Analysis_Text']; ?></td>
  </tr>
  <tr>
    <td>Sector_ID</td>
    <td><?php echo $row_DetailRS4['Sector_ID']; ?></td>
  </tr>
  <tr>
    <td>Sector_Name</td>
    <td><?php echo $row_DetailRS4['Sector_Name']; ?></td>
  </tr>
</table>
<table border="1" align="center">
  <tr>
    <td>Contact_ID</td>
    <td><?php echo $row_DetailRS5['Contact_ID']; ?></td>
  </tr>
  <tr>
    <td>Stock_ID</td>
    <td><?php echo $row_DetailRS5['Stock_ID']; ?></td>
  </tr>
  <tr>
    <td>Job_Title</td>
    <td><?php echo $row_DetailRS5['Job_Title']; ?></td>
  </tr>
  <tr>
    <td>Name</td>
    <td><?php echo $row_DetailRS5['Name']; ?></td>
  </tr>
  <tr>
    <td>Title</td>
    <td><?php echo $row_DetailRS5['Title']; ?></td>
  </tr>
  <tr>
    <td>Email</td>
    <td><?php echo $row_DetailRS5['Email']; ?></td>
  </tr>
  <tr>
    <td>Telephone</td>
    <td><?php echo $row_DetailRS5['Telephone']; ?></td>
  </tr>
  <tr>
    <td>Stock_ID</td>
    <td><?php echo $row_DetailRS5['Stock_ID']; ?></td>
  </tr>
  <tr>
    <td>Stock_Name</td>
    <td><?php echo $row_DetailRS5['Stock_Name']; ?></td>
  </tr>
  <tr>
    <td>Sector_ID</td>
    <td><?php echo $row_DetailRS5['Sector_ID']; ?></td>
  </tr>
  <tr>
    <td>In_Charge</td>
    <td><?php echo $row_DetailRS5['In_Charge']; ?></td>
  </tr>
  <tr>
    <td>Environment</td>
    <td><?php echo $row_DetailRS5['Environment']; ?></td>
  </tr>
  <tr>
    <td>Business_Description</td>
    <td><?php echo $row_DetailRS5['Business_Description']; ?></td>
  </tr>
  <tr>
    <td>Competition</td>
    <td><?php echo $row_DetailRS5['Competition']; ?></td>
  </tr>
  <tr>
    <td>Management</td>
    <td><?php echo $row_DetailRS5['Management']; ?></td>
  </tr>
  <tr>
    <td>Is_In_Portfolio</td>
    <td><?php echo $row_DetailRS5['Is_In_Portfolio']; ?></td>
  </tr>
  <tr>
    <td>Valorisation</td>
    <td><?php echo $row_DetailRS5['Valorisation']; ?></td>
  </tr>
  <tr>
    <td>Investment_Case</td>
    <td><?php echo $row_DetailRS5['Investment_Case']; ?></td>
  </tr>
  <tr>
    <td>Investment_Risks_Macro</td>
    <td><?php echo $row_DetailRS5['Investment_Risks_Macro']; ?></td>
  </tr>
  <tr>
    <td>Investment_Risks_Micro</td>
    <td><?php echo $row_DetailRS5['Investment_Risks_Micro']; ?></td>
  </tr>
  <tr>
    <td>Rating</td>
    <td><?php echo $row_DetailRS5['Rating']; ?></td>
  </tr>
  <tr>
    <td>Ticker</td>
    <td><?php echo $row_DetailRS5['Ticker']; ?></td>
  </tr>
  <tr>
    <td>Last_Modified</td>
    <td><?php echo $row_DetailRS5['Last_Modified']; ?></td>
  </tr>
  <tr>
    <td>Flagged</td>
    <td><?php echo $row_DetailRS5['Flagged']; ?></td>
  </tr>
  <tr>
    <td>Flag_Date</td>
    <td><?php echo $row_DetailRS5['Flag_Date']; ?></td>
  </tr>
</table>
<table border="1" align="center">
  <tr>
    <td>Stock_ID</td>
    <td><?php echo $row_DetailRS6['Stock_ID']; ?></td>
  </tr>
  <tr>
    <td>Stock_Name</td>
    <td><?php echo $row_DetailRS6['Stock_Name']; ?></td>
  </tr>
  <tr>
    <td>Sector_ID</td>
    <td><?php echo $row_DetailRS6['Sector_ID']; ?></td>
  </tr>
  <tr>
    <td>In_Charge</td>
    <td><?php echo $row_DetailRS6['In_Charge']; ?></td>
  </tr>
  <tr>
    <td>Environment</td>
    <td><?php echo $row_DetailRS6['Environment']; ?></td>
  </tr>
  <tr>
    <td>Business_Description</td>
    <td><?php echo $row_DetailRS6['Business_Description']; ?></td>
  </tr>
  <tr>
    <td>Competition</td>
    <td><?php echo $row_DetailRS6['Competition']; ?></td>
  </tr>
  <tr>
    <td>Management</td>
    <td><?php echo $row_DetailRS6['Management']; ?></td>
  </tr>
  <tr>
    <td>Is_In_Portfolio</td>
    <td><?php echo $row_DetailRS6['Is_In_Portfolio']; ?></td>
  </tr>
  <tr>
    <td>Valorisation</td>
    <td><?php echo $row_DetailRS6['Valorisation']; ?></td>
  </tr>
  <tr>
    <td>Investment_Case</td>
    <td><?php echo $row_DetailRS6['Investment_Case']; ?></td>
  </tr>
  <tr>
    <td>Investment_Risks_Macro</td>
    <td><?php echo $row_DetailRS6['Investment_Risks_Macro']; ?></td>
  </tr>
  <tr>
    <td>Investment_Risks_Micro</td>
    <td><?php echo $row_DetailRS6['Investment_Risks_Micro']; ?></td>
  </tr>
  <tr>
    <td>Rating</td>
    <td><?php echo $row_DetailRS6['Rating']; ?></td>
  </tr>
  <tr>
    <td>Ticker</td>
    <td><?php echo $row_DetailRS6['Ticker']; ?></td>
  </tr>
  <tr>
    <td>Last_Modified</td>
    <td><?php echo $row_DetailRS6['Last_Modified']; ?></td>
  </tr>
  <tr>
    <td>Flagged</td>
    <td><?php echo $row_DetailRS6['Flagged']; ?></td>
  </tr>
  <tr>
    <td>Flag_Date</td>
    <td><?php echo $row_DetailRS6['Flag_Date']; ?></td>
  </tr>
  <tr>
    <td>Discussion_ID</td>
    <td><?php echo $row_DetailRS6['Discussion_ID']; ?></td>
  </tr>
  <tr>
    <td>Discussion_Date</td>
    <td><?php echo $row_DetailRS6['Discussion_Date']; ?></td>
  </tr>
  <tr>
    <td>Stock_ID</td>
    <td><?php echo $row_DetailRS6['Stock_ID']; ?></td>
  </tr>
  <tr>
    <td>View_BDL</td>
    <td><?php echo $row_DetailRS6['View_BDL']; ?></td>
  </tr>
  <tr>
    <td>Alert_BDL</td>
    <td><?php echo $row_DetailRS6['Alert_BDL']; ?></td>
  </tr>
  <tr>
    <td>Alert_Date</td>
    <td><?php echo $row_DetailRS6['Alert_Date']; ?></td>
  </tr>
  <tr>
    <td>Stock_Price</td>
    <td><?php echo $row_DetailRS6['Stock_Price']; ?></td>
  </tr>
  <tr>
    <td>Position_BDL</td>
    <td><?php echo $row_DetailRS6['Position_BDL']; ?></td>
  </tr>
</table>






</body>
</html><?php
mysql_free_result($DetailRS1);

mysql_free_result($DetailRS2);

mysql_free_result($DetailRS3);

mysql_free_result($DetailRS4);

mysql_free_result($DetailRS5);

mysql_free_result($DetailRS6);
?>