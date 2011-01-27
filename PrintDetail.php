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

$colname_Current_Detail = "-1";
if (isset($_GET['Detail_ID'])) {
  $colname_Current_Detail = $_GET['Detail_ID'];
}
mysql_select_db($database_localhost, $localhost);
$query_Current_Detail = sprintf("SELECT * FROM Details, Sectors WHERE Details.Detail_ID = %s AND Details.Sector_ID=Sectors.Sector_ID", GetSQLValueString($colname_Current_Detail, "int"));
$Current_Detail = mysql_query($query_Current_Detail, $localhost) or die(mysql_error());
$row_Current_Detail = mysql_fetch_assoc($Current_Detail);
$totalRows_Current_Detail = mysql_num_rows($Current_Detail);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Detail <?php echo $_GET[Detail_ID]; ?></title>
</head>

<body>
<h1><?php echo $row_Current_Detail['Sector_Analysis_Title']; ?></h1>
<table width="100%" border="1">
  <tr>
    <td width="10%">Detail ref </td>
    <td><?php echo $row_Current_Detail['Detail_ID']; ?></td>
  </tr>
  <tr>
    <td width="10%">Sector</td>
    <td><?php echo $row_Current_Detail['Sector_Name']; ?></td>
  </tr>
  <tr>
    <td width="10%">Date</td>
    <td><?php echo changedateusfr($row_Current_Detail['Last_Entry_Date']); ?></td>
  </tr>
  <tr>
    <td width="10%">Title</td>
    <td><?php echo $row_Current_Detail['Sector_Analysis_Title']; ?></td>
  </tr>
  <tr>
    <td width="10%">Analysis </td>
    <td><?php echo $row_Current_Detail['Sector_Analysis_Text']; ?></td>
  </tr>
</table>
<p>&nbsp;</p>
</body>
</html>
<?php
mysql_free_result($Current_Detail);
?>
