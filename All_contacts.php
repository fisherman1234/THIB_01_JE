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
$query_all_contacts = "SELECT * FROM Contacts, Stocks WHERE Stocks.Stock_ID=Contacts.Stock_ID";
$all_contacts = mysql_query($query_all_contacts, $localhost) or die(mysql_error());
$row_all_contacts = mysql_fetch_assoc($all_contacts);
$totalRows_all_contacts = mysql_num_rows($all_contacts);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Browse contacts</title>
<link href="css/oneColElsCtrHdr.css" rel="stylesheet" type="text/css" />
</head>

<body class="oneColElsCtrHdr">

<div id="container">
  <div id="header">
    <h1>Contacts</h1>
  <!-- end #header --></div>
  <div id="mainContent">
    <p>&nbsp;</p>
    <table width="100%" border="1" align="center">
      <tr>
        <td width="19%">Stock</td>
        <td width="17%">Job title</td>
        <td width="14%">Name</td>
        <td width="14%">Title</td>
        <td width="14%">Email</td>
        <td width="22%">Telephone</td>
      </tr>
      <?php do { ?>
        <tr>
          <td><a href="Stock.php?Stock_ID=<?php echo $row_all_contacts['Stock_ID']; ?>"><?php echo $row_all_contacts['Stock_Name']; ?></a></td>
          <td> <?php echo $row_all_contacts['Job_Title']; ?>&nbsp;</td>
          <td><?php echo $row_all_contacts['Name']; ?>&nbsp; </td>
          <td><?php echo $row_all_contacts['Title']; ?>&nbsp; </td>
          <td><?php echo $row_all_contacts['Email']; ?>&nbsp; </td>
          <td><?php echo $row_all_contacts['Telephone']; ?>&nbsp; </td>
        </tr>
        <?php } while ($row_all_contacts = mysql_fetch_assoc($all_contacts)); ?>
    </table>
    <br />
    <?php echo $totalRows_all_contacts ?> Records Total

    <!-- end #mainContent --></div>
  <div id="footer">
    <p>Footer</p>
  <!-- end #footer --></div>
<!-- end #container --></div>
</body>
</html>
<?php
mysql_free_result($all_contacts);
?>
