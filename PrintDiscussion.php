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

$colname_Discussion = "-1";
if (isset($_GET['Discussion_ID'])) {
  $colname_Discussion = $_GET['Discussion_ID'];
}
mysql_select_db($database_localhost, $localhost);
$query_Discussion = sprintf("SELECT * FROM BDL_Discussions, Stocks WHERE BDL_Discussions.Discussion_ID = %s AND BDL_Discussions.Stock_ID=Stocks.Stock_ID", GetSQLValueString($colname_Discussion, "int"));
$Discussion = mysql_query($query_Discussion, $localhost) or die(mysql_error());
$row_Discussion = mysql_fetch_assoc($Discussion);
$totalRows_Discussion = mysql_num_rows($Discussion);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Discussion </title>
</head>

<body>
<h1>Discussion on <?php echo $row_Discussion['Stock_Name']; ?>
- Date : 
<?php echo $row_Discussion['Discussion_Date']; ?>
</h1>
<table width="100%" border="1">
  <tr>
    <td width="10%">BDL position</td>
    <td><?php echo $row_Discussion['Position_BDL']; ?></td>
  </tr>
  <tr>
    <td width="10%">Stock price</td>
    <td><?php echo $row_Discussion['Stock_Price']; ?></td>
  </tr>
  <tr>
    <td width="10%">BDL view</td>
    <td><?php echo $row_Discussion['View_BDL']; ?></td>
  </tr>
</table>
<p>&nbsp;</p>
</body>
</html>
<?php
mysql_free_result($Discussion);
?>
