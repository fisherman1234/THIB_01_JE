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

$maxRows_Rating = 1000;
$pageNum_Rating = 0;
if (isset($_GET['pageNum_Rating'])) {
  $pageNum_Rating = $_GET['pageNum_Rating'];
}
$startRow_Rating = $pageNum_Rating * $maxRows_Rating;

$colname_Rating = "0";
if (isset($_GET['Position_BDL'])) {
  $colname_Rating = $_GET['Position_BDL'];
}
mysql_select_db($database_localhost, $localhost);
$query_Rating = sprintf("SELECT * FROM (SELECT * FROM Stocks RIGHT JOIN (Select Stock_ID AS Stock_ID2, Position_BDL, max(Discussion_Date)  From BDL_Discussions GROUP BY Stock_ID) AS a ON Stocks.Stock_ID=a.Stock_ID2) AS b, Sectors  WHERE b.Position_BDL= %s AND b.Sector_ID=Sectors.Sector_ID ORDER BY b.Stock_Name", GetSQLValueString($colname_Rating, "text"));
$query_limit_Rating = sprintf("%s LIMIT %d, %d", $query_Rating, $startRow_Rating, $maxRows_Rating);
$Rating = mysql_query($query_limit_Rating, $localhost) or die(mysql_error());
$row_Rating = mysql_fetch_assoc($Rating);

if (isset($_GET['totalRows_Rating'])) {
  $totalRows_Rating = $_GET['totalRows_Rating'];
} else {
  $all_Rating = mysql_query($query_Rating);
  $totalRows_Rating = mysql_num_rows($all_Rating);
}
$totalPages_Rating = ceil($totalRows_Rating/$maxRows_Rating)-1;

$queryString_Rating = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_Rating") == false && 
        stristr($param, "totalRows_Rating") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_Rating = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_Rating = sprintf("&totalRows_Rating=%d%s", $totalRows_Rating, $queryString_Rating);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Position : <?php echo $_GET['Position_BDL']; ?></title>
<link href="css/oneColElsCtrHdr.css" rel="stylesheet" type="text/css" />
</head>

<body class="oneColElsCtrHdr">

<div id="container">
  <div id="header">
    <h1>Last position : <?php echo $_GET['Position_BDL']; ?></h1>
    <p><a href="index.php">Home</a></p>
  <!-- end #header --></div>
  <div id="mainContent">
    <p>
    <table width="100%" border="1" align="center">
      <tr>
        <td width="40%">Stock name</td>
        <td>In portfolio</td>
        <td>Rating</td>
        <td width="30%">Sector </td>
      </tr>
      <?php do { ?>
        <tr>
          <td width="40%"><a href="Stock.php?Stock_ID=<?php echo $row_Rating['Stock_ID']; ?>"> <?php echo $row_Rating['Stock_Name']; ?>&nbsp; </a></td>
          <td><?php echo $row_Rating['Is_In_Portfolio']; ?>&nbsp; </td>
          <td><?php echo $row_Rating['Rating']; ?>&nbsp; </td>
          <td width="30%"><?php echo $row_Rating['Sector_Name']; ?>&nbsp; </td>
        </tr>
        <?php } while ($row_Rating = mysql_fetch_assoc($Rating)); ?>
    </table>
    <br />
    <table border="0">
      <tr>
        <td><?php if ($pageNum_Rating > 0) { // Show if not first page ?>
            <a href="<?php printf("%s?pageNum_Rating=%d%s", $currentPage, 0, $queryString_Rating); ?>">First</a>
            <?php } // Show if not first page ?></td>
        <td><?php if ($pageNum_Rating > 0) { // Show if not first page ?>
            <a href="<?php printf("%s?pageNum_Rating=%d%s", $currentPage, max(0, $pageNum_Rating - 1), $queryString_Rating); ?>">Previous</a>
            <?php } // Show if not first page ?></td>
        <td><?php if ($pageNum_Rating < $totalPages_Rating) { // Show if not last page ?>
            <a href="<?php printf("%s?pageNum_Rating=%d%s", $currentPage, min($totalPages_Rating, $pageNum_Rating + 1), $queryString_Rating); ?>">Next</a>
            <?php } // Show if not last page ?></td>
        <td><?php if ($pageNum_Rating < $totalPages_Rating) { // Show if not last page ?>
            <a href="<?php printf("%s?pageNum_Rating=%d%s", $currentPage, $totalPages_Rating, $queryString_Rating); ?>">Last</a>
            <?php } // Show if not last page ?></td>
      </tr>
    </table>
Records <?php echo ($startRow_Rating + 1) ?> to <?php echo min($startRow_Rating + $maxRows_Rating, $totalRows_Rating) ?> of <?php echo $totalRows_Rating ?>
</p>
    
  <!-- end #mainContent --></div>
  <div id="footer">
    <p>Footer</p>
  <!-- end #footer --></div>
<!-- end #container --></div>
</body>
</html>
<?php
mysql_free_result($Rating);
?>
