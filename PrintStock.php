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

$colname_Current_Stock = "-1";
if (isset($_GET['Stock_ID'])) {
  $colname_Current_Stock = $_GET['Stock_ID'];
}
mysql_select_db($database_localhost, $localhost);
$query_Current_Stock = sprintf("SELECT * FROM Stocks, Sectors, Users  WHERE Stocks.Stock_ID = %s AND Stocks.Sector_ID=Sectors.Sector_ID AND Users.Id=Stocks.In_Charge", GetSQLValueString($colname_Current_Stock, "int"));
$Current_Stock = mysql_query($query_Current_Stock, $localhost) or die(mysql_error());
$row_Current_Stock = mysql_fetch_assoc($Current_Stock);
$totalRows_Current_Stock = mysql_num_rows($Current_Stock);


$colname_Meeting_results = "-1";
if (isset($_GET['Stock_ID'])) {
  $colname_Meeting_results = $_GET['Stock_ID'];
}
mysql_select_db($database_localhost, $localhost);
$query_Meeting_results = sprintf("SELECT * FROM Meetings_Results, Users WHERE Meetings_Results.Stock_ID = %s AND Meetings_Results.BDL_Contact=Users.Id ORDER BY Meetings_Results.Meeting_Date DESC", GetSQLValueString($colname_Meeting_results, "int"));
$Meeting_results = mysql_query($query_Meeting_results, $localhost) or die(mysql_error());
$row_Meeting_results = mysql_fetch_assoc($Meeting_results);
$totalRows_Meeting_results = mysql_num_rows($Meeting_results);

$colname_Contacts = "-1";
if (isset($_GET['Stock_ID'])) {
  $colname_Contacts = $_GET['Stock_ID'];
}
mysql_select_db($database_localhost, $localhost);
$query_Contacts = sprintf("SELECT * FROM Contacts WHERE Stock_ID = %s", GetSQLValueString($colname_Contacts, "int"));
$Contacts = mysql_query($query_Contacts, $localhost) or die(mysql_error());
$row_Contacts = mysql_fetch_assoc($Contacts);
$totalRows_Contacts = mysql_num_rows($Contacts);

$colname_Related_Discussions = "-1";
if (isset($_GET['Stock_ID'])) {
  $colname_Related_Discussions = $_GET['Stock_ID'];
}
mysql_select_db($database_localhost, $localhost);
$query_Related_Discussions = sprintf("SELECT * FROM BDL_Discussions WHERE Stock_ID = %s ORDER BY Discussion_Date DESC", GetSQLValueString($colname_Related_Discussions, "int"));
$Related_Discussions = mysql_query($query_Related_Discussions, $localhost) or die(mysql_error());
$row_Related_Discussions = mysql_fetch_assoc($Related_Discussions);
$totalRows_Related_Discussions = mysql_num_rows($Related_Discussions);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Stock : <?php echo $row_Current_Stock['Stock_Name']; ?></title>
</head>

<body>
<h1>Stock : <?php echo $row_Current_Stock['Stock_Name']; ?>
 - Sector 
<?php echo $row_Current_Stock['Sector_Name']; ?>
</h1>
<h3>Stock overview</h3>
<table width="100%" border="1">
  <tr>
    <td width="15%">Ticker</td>
    <td><?php echo $row_Current_Stock['Ticker']; ?></td>
  </tr>
  <tr>
    <td width="15%">In charge</td>
    <td><?php echo $row_Current_Stock['Nom']; ?></td>
  </tr>
  <tr>
    <td width="15%">Flagged</td>
    <td><?php echo $row_Current_Stock['Flagged']; ?></td>
  </tr>
  <tr>
    <td width="15%">Flag date</td>
    <td><?php echo $row_Current_Stock['Flag_Date']; ?></td>
  </tr>
  <tr>
    <td width="15%">In portfolio</td>
    <td><?php echo $row_Current_Stock['Is_In_Portfolio']; ?></td>
  </tr>
</table>
<h3>Analysis</h3>
<table width="100%" border="1">
  <tr>
    <td width="15%">Environment</td>
    <td><?php echo $row_Current_Stock['Environment']; ?></td>
  </tr>
  <tr>
    <td width="15%">Business description</td>
    <td><?php echo $row_Current_Stock['Business_Description']; ?></td>
  </tr>
  <tr>
    <td width="15%">Competition</td>
    <td><?php echo $row_Current_Stock['Competition']; ?></td>
  </tr>
  <tr>
    <td width="15%">Managemetn</td>
    <td><?php echo $row_Current_Stock['Management']; ?></td>
  </tr>
</table>
<h3>Investment case</h3>
<table width="100%" border="1">
  <tr>
    <td width="15%">Investment case</td>
    <td><?php echo $row_Current_Stock['Investment_Case']; ?></td>
  </tr>
  <tr>
    <td width="15%">Investment risk macro</td>
    <td><?php echo $row_Current_Stock['Investment_Risks_Macro']; ?></td>
  </tr>
  <tr>
    <td width="15%">Investment risk micro</td>
    <td><?php echo $row_Current_Stock['Investment_Risks_Micro']; ?></td>
  </tr>
  <tr>
    <td width="15%">Rating</td>
    <td><?php echo $row_Current_Stock['Rating']; ?></td>
  </tr>
</table>
<h3>Discussions</h3>
<?php do { ?>
  
  <table width="100%" border="1">
    <tr>
      <td width="15%">Discussion date</td>
      <td><?php echo $row_Related_Discussions['Discussion_Date']; ?></td>
    </tr>
    <tr>
      <td width="15%">Stock price</td>
      <td><?php echo $row_Related_Discussions['Stock_Price']; ?></td>
    </tr>
    <tr>
      <td width="15%">BDL position</td>
      <td><?php echo $row_Related_Discussions['Position_BDL']; ?></td>
    </tr>
    <tr>
      <td width="15%">BDL view</td>
      <td><?php echo $row_Related_Discussions['View_BDL']; ?></td>
    </tr>
  </table>
  <p>&nbsp;</p>
  <?php } while ($row_Related_Discussions = mysql_fetch_assoc($Related_Discussions)); ?>
  <h3>Meeting results</h3>
<?php do { ?>
  
  <table width="100%" border="1">
    <tr>
      <td width="15%">Type</td>
      <td><?php echo $row_Meeting_results['Meeting_Type']; ?></td>
    </tr>
    <tr>
      <td width="15%">Date</td>
      <td><?php echo $row_Meeting_results['Meeting_Date']; ?></td>
    </tr>
    <tr>
      <td width="15%">Meeting contact</td>
      <td><?php echo $row_Meeting_results['Meeting_Contact']; ?></td>
    </tr>
    <tr>
      <td width="15%">BDL contact</td>
      <td><?php echo $row_Meeting_results['Nom']; ?></td>
    </tr>
    <tr>
      <td width="15%">Notes</td>
      <td><?php echo $row_Meeting_results['Meeting_Notes']; ?></td>
    </tr>
    <tr>
      <td width="15%">Conclusions</td>
      <td><?php echo $row_Meeting_results['Meeting_Conclusions']; ?></td>
    </tr>
  </table>
  <p>&nbsp;</p>
  <?php } while ($row_Meeting_results = mysql_fetch_assoc($Meeting_results)); ?>
<h3>Contacts</h3>
<?php do { ?>
  <table width="100%" border="1">
    <tr>
      <td width="15%">Name</td>
      <td><?php echo $row_Contacts['Name']; ?></td>
    </tr>
    <tr>
      <td width="15%">Title</td>
      <td><?php echo $row_Contacts['Job_Title']; ?></td>
    </tr>
    <tr>
      <td width="15%">Position</td>
      <td><?php echo $row_Contacts['Title']; ?></td>
    </tr>
    <tr>
      <td width="15%">Email</td>
      <td><?php echo $row_Contacts['Email']; ?></td>
    </tr>
    <tr>
      <td width="15%">Telephone</td>
      <td><?php echo $row_Contacts['Telephone']; ?></td>
    </tr>
  </table>
  <p>&nbsp;</p>
  <?php } while ($row_Contacts = mysql_fetch_assoc($Contacts)); ?>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
</html>
<?php
mysql_free_result($Current_Stock);

mysql_free_result($Related_Discussions);

mysql_free_result($Meeting_results);

mysql_free_result($Contacts);
?>
