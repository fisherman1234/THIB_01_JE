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

$colname_Meeting = "-1";
if (isset($_GET['Meeting_ID'])) {
  $colname_Meeting = $_GET['Meeting_ID'];
}
mysql_select_db($database_localhost, $localhost);
$query_Meeting = sprintf("SELECT * FROM Meetings_Results, Stocks, Users  WHERE Meetings_Results.Meeting_ID = %s AND Stocks.Stock_ID=Meetings_Results.Stock_ID AND Users.Id=Meetings_Results.BDL_Contact", GetSQLValueString($colname_Meeting, "int"));
$Meeting = mysql_query($query_Meeting, $localhost) or die(mysql_error());
$row_Meeting = mysql_fetch_assoc($Meeting);
$totalRows_Meeting = mysql_num_rows($Meeting);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Meeting</title>
</head>

<body>
<h1>Meeting results</h1>
<table width="100%" border="1">
  <tr>
    <td width="15%">Stock</td>
    <td><?php echo $row_Meeting['Stock_Name']; ?></td>
  </tr>
  <tr>
    <td width="15%">Date</td>
    <td><?php echo $row_Meeting['Meeting_Date']; ?></td>
  </tr>
  <tr>
    <td width="15%">Meeting type</td>
    <td><?php echo $row_Meeting['Meeting_Type']; ?></td>
  </tr>
  <tr>
    <td width="15%">BDL contact</td>
    <td><?php echo $row_Meeting['Nom']; ?></td>
  </tr>
  <tr>
    <td width="15%">Meeting contact</td>
    <td><?php echo $row_Meeting['Meeting_Contact']; ?></td>
  </tr>
  <tr>
    <td width="15%">Meeting notes</td>
    <td><?php echo $row_Meeting['Meeting_Notes']; ?></td>
  </tr>
  <tr>
    <td width="15%">Meeting conclusions</td>
    <td><?php echo $row_Meeting['Meeting_Conclusions']; ?></td>
  </tr>
</table>
<p>&nbsp;</p>
</body>
</html>
<?php
mysql_free_result($Meeting);
?>
