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

mysql_select_db($database_localhost, $localhost);
$query_In_Portfolio = "SELECT * FROM Stocks, Sectors WHERE Stocks.Is_In_Portfolio=1 AND Sectors.Sector_ID=Stocks.Sector_ID ORDER BY Stocks.Rating, Stocks.Stock_Name";
$In_Portfolio = mysql_query($query_In_Portfolio, $localhost) or die(mysql_error());
$row_In_Portfolio = mysql_fetch_assoc($In_Portfolio);
$totalRows_In_Portfolio = mysql_num_rows($In_Portfolio);

mysql_select_db($database_localhost, $localhost);
$query_Not_In_Portfolio = "SELECT * FROM Stocks, Sectors WHERE Stocks.Is_In_Portfolio=0 AND Sectors.Sector_ID=Stocks.Sector_ID ORDER BY Stocks.Rating, Stocks.Stock_Name";
$Not_In_Portfolio = mysql_query($query_Not_In_Portfolio, $localhost) or die(mysql_error());
$row_Not_In_Portfolio = mysql_fetch_assoc($Not_In_Portfolio);
$totalRows_Not_In_Portfolio = mysql_num_rows($Not_In_Portfolio);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Portfolio</title>
<link href="css/oneColElsCtrHdr.css" rel="stylesheet" type="text/css" />
</head>

<body class="oneColElsCtrHdr">

<div id="container">
  <div id="header">
    <h1>Portfolio</h1>
    <p><a href="index.php">Home</a></p>
  <!-- end #header --></div>
  <div id="mainContent">
    <h1>In portfolio&nbsp;    </h1>
    <table width="100%" border="1" align="center">
      <tr>
        <td width="35%">Stock</td>
        <td>Flagged</td>
        <td width="30%">Sector</td>
        <td>Stock rating</td>
      </tr>
      <?php do { ?>
        <tr>
          <td width="35%"><a href="Stock.php?Stock_ID=<?php echo $row_In_Portfolio['Stock_ID']; ?>"> <?php echo $row_In_Portfolio['Stock_Name']; ?>&nbsp; </a></td>
          <td><?php echo $row_In_Portfolio['Flagged']; ?>&nbsp; </td>
          <td width="30%"><?php echo $row_In_Portfolio['Sector_Name']; ?>&nbsp; </td>
          <td><?php echo $row_In_Portfolio['Rating']; ?>&nbsp; </td>
        </tr>
        <?php } while ($row_In_Portfolio = mysql_fetch_assoc($In_Portfolio)); ?>
    </table>
    <br />
    <?php echo $totalRows_In_Portfolio ?> Records Total
    </p>
    <h2>Not in portfolio    </h2>
    <table width="100%" border="1" align="center">
      <tr>
        <td width="35%">Stock</td>
        <td>Flagged</td>
        <td width="30%">Sector</td>
        <td>Stock rating</td>
      </tr>
      <?php do { ?>
        <tr>
          <td width="35%"><a href="Stock.php?Stock_ID=<?php echo $row_Not_In_Portfolio['Stock_ID']; ?>"> <?php echo $row_Not_In_Portfolio['Stock_Name']; ?>&nbsp; </a></td>
          <td><?php echo $row_Not_In_Portfolio['Flagged']; ?>&nbsp; </td>
          <td width="30%"><?php echo $row_Not_In_Portfolio['Sector_Name']; ?>&nbsp; </td>
          <td><?php echo $row_Not_In_Portfolio['Rating']; ?>&nbsp; </td>
        </tr>
        <?php } while ($row_Not_In_Portfolio = mysql_fetch_assoc($Not_In_Portfolio)); ?>
    </table>
    <br />
    <?php echo $totalRows_Not_In_Portfolio ?> Records Total
    </p>
<p>
  <!-- end #mainContent -->
</p></div>
  <div id="footer">
    <p>Footer</p>
  <!-- end #footer --></div>
<!-- end #container --></div>
</body>
</html>
<?php
mysql_free_result($In_Portfolio);

mysql_free_result($Not_In_Portfolio);
?>
