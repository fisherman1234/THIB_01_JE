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

$currentPage = $_SERVER["PHP_SELF"];

$maxRows_MyStocks = 20;
$pageNum_MyStocks = 0;
if (isset($_GET['pageNum_MyStocks'])) {
  $pageNum_MyStocks = $_GET['pageNum_MyStocks'];
}
$startRow_MyStocks = $pageNum_MyStocks * $maxRows_MyStocks;

$colname_MyStocks = "-1";
if (isset($_SESSION['MM_UserID'])) {
  $colname_MyStocks = $_SESSION['MM_UserID'];
}
mysql_select_db($database_localhost, $localhost);
$query_MyStocks = sprintf("SELECT * FROM Stocks, Sectors WHERE Stocks.In_Charge = %s AND Sectors.Sector_ID=Stocks.Sector_ID ORDER BY Stock_Name ASC", GetSQLValueString($colname_MyStocks, "int"));
$query_limit_MyStocks = sprintf("%s LIMIT %d, %d", $query_MyStocks, $startRow_MyStocks, $maxRows_MyStocks);
$MyStocks = mysql_query($query_limit_MyStocks, $localhost) or die(mysql_error());
$row_MyStocks = mysql_fetch_assoc($MyStocks);

if (isset($_GET['totalRows_MyStocks'])) {
  $totalRows_MyStocks = $_GET['totalRows_MyStocks'];
} else {
  $all_MyStocks = mysql_query($query_MyStocks);
  $totalRows_MyStocks = mysql_num_rows($all_MyStocks);
}
$totalPages_MyStocks = ceil($totalRows_MyStocks/$maxRows_MyStocks)-1;

$queryString_MyStocks = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_MyStocks") == false && 
        stristr($param, "totalRows_MyStocks") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_MyStocks = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_MyStocks = sprintf("&totalRows_MyStocks=%d%s", $totalRows_MyStocks, $queryString_MyStocks);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>My stocks</title>
<link href="css/oneColElsCtrHdr.css" rel="stylesheet" type="text/css" />
</head>

<body class="oneColElsCtrHdr">

<div id="container">
  <div id="header">
    <h1>My stocks</h1>
    <p><a href="index.php">Home</a></p>
  <!-- end #header --></div>
  <div id="mainContent">
    <br />
      <table width="100%" border="1" align="center">
        <tr>
          <td width="40%">Stock name</td>
          <td>In portfolio</td>
          <td>Rating</td>
          <td width="30%">Sector name</td>
        </tr>
        <?php do { ?>
          <tr>
            <td width="40%"><a href="Stock.php?Stock_ID=<?php echo $row_MyStocks['Stock_ID']; ?>"> <?php echo $row_MyStocks['Stock_Name']; ?>&nbsp; </a></td>
            <td><?php echo $row_MyStocks['Is_In_Portfolio']; ?>&nbsp; </td>
            <td><?php echo $row_MyStocks['Rating']; ?>&nbsp; </td>
            <td width="30%"><?php echo $row_MyStocks['Sector_Name']; ?>&nbsp; </td>
        </tr>
          <?php } while ($row_MyStocks = mysql_fetch_assoc($MyStocks)); ?>
      </table>
      <br />
      <table border="0">
        <tr>
          <td><?php if ($pageNum_MyStocks > 0) { // Show if not first page ?>
              <a href="<?php printf("%s?pageNum_MyStocks=%d%s", $currentPage, 0, $queryString_MyStocks); ?>">First</a>
              <?php } // Show if not first page ?></td>
          <td><?php if ($pageNum_MyStocks > 0) { // Show if not first page ?>
              <a href="<?php printf("%s?pageNum_MyStocks=%d%s", $currentPage, max(0, $pageNum_MyStocks - 1), $queryString_MyStocks); ?>">Previous</a>
              <?php } // Show if not first page ?></td>
          <td><?php if ($pageNum_MyStocks < $totalPages_MyStocks) { // Show if not last page ?>
              <a href="<?php printf("%s?pageNum_MyStocks=%d%s", $currentPage, min($totalPages_MyStocks, $pageNum_MyStocks + 1), $queryString_MyStocks); ?>">Next</a>
              <?php } // Show if not last page ?></td>
          <td><?php if ($pageNum_MyStocks < $totalPages_MyStocks) { // Show if not last page ?>
              <a href="<?php printf("%s?pageNum_MyStocks=%d%s", $currentPage, $totalPages_MyStocks, $queryString_MyStocks); ?>">Last</a>
              <?php } // Show if not last page ?></td>
        </tr>
      </table>
Records <?php echo ($startRow_MyStocks + 1) ?> to <?php echo min($startRow_MyStocks + $maxRows_MyStocks, $totalRows_MyStocks) ?> of <?php echo $totalRows_MyStocks ?> 
	<!-- end #mainContent --></div>
  <div id="footer">
 <p style="font-size:12px;">BDL Capital Management - 2010</p>  <!-- end #footer --></div>
<!-- end #container --></div>
</body>
</html>
<?php
mysql_free_result($MyStocks);
?>
