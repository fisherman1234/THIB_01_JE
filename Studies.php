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
$query_Studies = "SELECT * FROM Details, Sectors WHERE Sectors.Sector_ID=Details.Sector_ID ORDER BY Sectors.Sector_Name";
$Studies = mysql_query($query_Studies, $localhost) or die(mysql_error());
$row_Studies = mysql_fetch_assoc($Studies);
$totalRows_Studies = mysql_num_rows($Studies);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Studies</title>
<link href="css/oneColElsCtrHdr.css" rel="stylesheet" type="text/css" />
</head>

<body class="oneColElsCtrHdr">

<div id="container">
  <div id="header">
    <h1>Studies</h1>
    <p><a href="index.php">Home</a></p>
  <!-- end #header --></div>
  <div id="mainContent">
    <p>&nbsp;
      <table width="100%" border="1" align="center">
        <tr>
          <td width="28%">Sector</td>
          <td width="32%">Date</td>
          <td width="50%">Sector analysis title</td>
        </tr>
        <?php do { ?>
          <tr>
            <td><?php echo $row_Studies['Sector_Name']; ?>&nbsp; </td>
            <td><?php echo $row_Studies['Last_Entry_Date']; ?>&nbsp; </td>
            <td width="50%"><a href="PrintDetail.php?Detail_ID=<?php echo $row_Studies['Detail_ID']; ?>"> <?php echo $row_Studies['Sector_Analysis_Title']; ?>&nbsp; </a><a href="Edit_Detail.php?Detail_ID=<?php echo $row_Studies['Detail_ID']; ?>">(edit)</a></td>
          </tr>
          <?php } while ($row_Studies = mysql_fetch_assoc($Studies)); ?>
      </table>
      <br />
      <?php echo $totalRows_Studies ?> Records Total </p>
	<!-- end #mainContent --></div>
  <div id="footer">
 <p style="font-size:12px;">BDL Capital Management - 2010</p>  <!-- end #footer --></div>
<!-- end #container --></div>
</body>
</html>
<?php
mysql_free_result($Studies);
?>
