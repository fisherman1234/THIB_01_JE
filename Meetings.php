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

$colname_DetailRS1 = "-1";
if (isset($_GET['recordID'])) {
  $colname_DetailRS1 = $_GET['recordID'];
}
mysql_select_db($database_localhost, $localhost);
$query_DetailRS1 = sprintf("SELECT * FROM Meetings_Results  WHERE Meeting_ID = %s", GetSQLValueString($colname_DetailRS1, "int"));
$DetailRS1 = mysql_query($query_DetailRS1, $localhost) or die(mysql_error());
$row_DetailRS1 = mysql_fetch_assoc($DetailRS1);
$totalRows_DetailRS1 = mysql_num_rows($DetailRS1);

$colname_DetailRS2 = "-1";
if (isset($_GET['recordID'])) {
  $colname_DetailRS2 = $_GET['recordID'];
}
mysql_select_db($database_localhost, $localhost);
$query_DetailRS2 = sprintf("SELECT * FROM Meetings_Results  WHERE Meeting_ID = %s", GetSQLValueString($colname_DetailRS2, "int"));
$DetailRS2 = mysql_query($query_DetailRS2, $localhost) or die(mysql_error());
$row_DetailRS2 = mysql_fetch_assoc($DetailRS2);
$totalRows_DetailRS2 = mysql_num_rows($DetailRS2);

$colname_DetailRS3 = "-1";
if (isset($_GET['recordID'])) {
  $colname_DetailRS3 = $_GET['recordID'];
}
mysql_select_db($database_localhost, $localhost);
$query_DetailRS3 = sprintf("SELECT * FROM Meetings_Results  WHERE Meeting_ID = %s", GetSQLValueString($colname_DetailRS3, "int"));
$DetailRS3 = mysql_query($query_DetailRS3, $localhost) or die(mysql_error());
$row_DetailRS3 = mysql_fetch_assoc($DetailRS3);
$totalRows_DetailRS3 = mysql_num_rows($DetailRS3);
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Untitled Document</title>
</head>

<body>

<table border="1" align="center">
  <tr>
    <td>Meeting_ID</td>
    <td><?php echo $row_DetailRS1['Meeting_ID']; ?></td>
  </tr>
  <tr>
    <td>Stock_ID</td>
    <td><?php echo $row_DetailRS1['Stock_ID']; ?></td>
  </tr>
  <tr>
    <td>Meeting_Type</td>
    <td><?php echo $row_DetailRS1['Meeting_Type']; ?></td>
  </tr>
  <tr>
    <td>Meeting_Date</td>
    <td><?php echo $row_DetailRS1['Meeting_Date']; ?></td>
  </tr>
  <tr>
    <td>Meeting_Contact</td>
    <td><?php echo $row_DetailRS1['Meeting_Contact']; ?></td>
  </tr>
  <tr>
    <td>BDL_Contact</td>
    <td><?php echo $row_DetailRS1['BDL_Contact']; ?></td>
  </tr>
  <tr>
    <td>Meeting_Notes</td>
    <td><?php echo $row_DetailRS1['Meeting_Notes']; ?></td>
  </tr>
  <tr>
    <td>Meeting_Conclusions</td>
    <td><?php echo $row_DetailRS1['Meeting_Conclusions']; ?></td>
  </tr>
</table>
<table border="1" align="center">
  <tr>
    <td>Meeting_ID</td>
    <td><?php echo $row_DetailRS2['Meeting_ID']; ?></td>
  </tr>
  <tr>
    <td>Stock_ID</td>
    <td><?php echo $row_DetailRS2['Stock_ID']; ?></td>
  </tr>
  <tr>
    <td>Meeting_Type</td>
    <td><?php echo $row_DetailRS2['Meeting_Type']; ?></td>
  </tr>
  <tr>
    <td>Meeting_Date</td>
    <td><?php echo $row_DetailRS2['Meeting_Date']; ?></td>
  </tr>
  <tr>
    <td>Meeting_Contact</td>
    <td><?php echo $row_DetailRS2['Meeting_Contact']; ?></td>
  </tr>
  <tr>
    <td>BDL_Contact</td>
    <td><?php echo $row_DetailRS2['BDL_Contact']; ?></td>
  </tr>
  <tr>
    <td>Meeting_Notes</td>
    <td><?php echo $row_DetailRS2['Meeting_Notes']; ?></td>
  </tr>
  <tr>
    <td>Meeting_Conclusions</td>
    <td><?php echo $row_DetailRS2['Meeting_Conclusions']; ?></td>
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
</table>


</body>
</html><?php
mysql_free_result($DetailRS1);

mysql_free_result($DetailRS2);

mysql_free_result($DetailRS3);
?>