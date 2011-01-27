<?php require_once('Connections/localhost.php'); ?>
<?php
if (!isset($_SESSION)) {
  session_start();
}
$MM_authorizedUsers = "";
$MM_donotCheckaccess = "true";

// *** Restrict Access To Page: Grant or deny access to this page
function isAuthorized($strUsers, $strGroups, $UserName, $UserGroup) { 
  // For security, start by assuming the visitor is NOT authorized. 
  $isValid = False; 

  // When a visitor has logged into this site, the Session variable MM_Username set equal to their username. 
  // Therefore, we know that a user is NOT logged in if that Session variable is blank. 
  if (!empty($UserName)) { 
    // Besides being logged in, you may restrict access to only certain users based on an ID established when they login. 
    // Parse the strings into arrays. 
    $arrUsers = Explode(",", $strUsers); 
    $arrGroups = Explode(",", $strGroups); 
    if (in_array($UserName, $arrUsers)) { 
      $isValid = true; 
    } 
    // Or, you may restrict access to only certain users based on their username. 
    if (in_array($UserGroup, $arrGroups)) { 
      $isValid = true; 
    } 
    if (($strUsers == "") && true) { 
      $isValid = true; 
    } 
  } 
  return $isValid; 
}

$MM_restrictGoTo = "log_in.php";
if (!((isset($_SESSION['MM_Username'])) && (isAuthorized("",$MM_authorizedUsers, $_SESSION['MM_Username'], $_SESSION['MM_UserGroup'])))) {   
  $MM_qsChar = "?";
  $MM_referrer = $_SERVER['PHP_SELF'];
  if (strpos($MM_restrictGoTo, "?")) $MM_qsChar = "&";
  if (isset($QUERY_STRING) && strlen($QUERY_STRING) > 0) 
  $MM_referrer .= "?" . $QUERY_STRING;
  $MM_restrictGoTo = $MM_restrictGoTo. $MM_qsChar . "accesscheck=" . urlencode($MM_referrer);
  header("Location: ". $MM_restrictGoTo); 
  exit;
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

$colname_MyFlaggedStocks = "-1";
if (isset($_SESSION['MM_UserID'])) {
  $colname_MyFlaggedStocks = $_SESSION['MM_UserID'];
}
mysql_select_db($database_localhost, $localhost);
$query_MyFlaggedStocks = sprintf("SELECT * FROM Stocks, Sectors WHERE Stocks.In_Charge = %s AND Stocks.Flagged = 1 AND Sectors.Sector_ID =Stocks.Sector_ID AND (TO_DAYS(Stocks.Flag_Date) - TO_DAYS(LOCALTIME) = 0) ORDER BY Stocks.Flag_Date DESC, Stock_Name ASC", GetSQLValueString($colname_MyFlaggedStocks, "int"));
$MyFlaggedStocks = mysql_query($query_MyFlaggedStocks, $localhost) or die(mysql_error());
$row_MyFlaggedStocks = mysql_fetch_assoc($MyFlaggedStocks);
$totalRows_MyFlaggedStocks = mysql_num_rows($MyFlaggedStocks);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Stocks of the day</title>
<script>
$(function() {
		$( ".datepicker" ).datepicker({ dateFormat: 'yy-mm-dd' , showButtonPanel: true });
	});
function MM_openBrWindow(theURL,winName,features) { //v2.0
  window.open(theURL,winName,features);
}
</script> 
</head>

<body >
<p>Stocks of the day</p>
<table width="100%" border="1" align="center">
  <tr>
    <td width="100%">Stock</td>
  </tr>
  <?php do { ?>
    <tr>
      <td><a href="#" onclick="MM_openBrWindow('Stock.php?Stock_ID=<?php echo $row_MyFlaggedStocks['Stock_ID']; ?>','','scrollbars=yes,width=1100,height=600')"><?php echo $row_MyFlaggedStocks['Stock_Name']; ?></a></td>
    </tr>
    <?php } while ($row_MyFlaggedStocks = mysql_fetch_assoc($MyFlaggedStocks)); ?>
</table>
</body>
</html>
<?php
mysql_free_result($MyFlaggedStocks);
?>
