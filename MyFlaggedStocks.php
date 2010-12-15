<?php require_once('Connections/localhost.php'); ?>
<?php
function check_in_range($user_date)
{
  // check if flag will occur in the next 7 days
  if (($user_date > date('Y-m-d')) && ($user_date < date('Y-m-d',strtotime("+7 days"))))

  {
	return '<span style="background-color: orange;">'.$user_date.'</span>';
  }
  // show past flags
  else if ($user_date < date('Y-m-d'))
  {
	  return '<span style="background-color: red;">'.$user_date.'</span>';
  }
  else
  {
	  return '<span style="background-color: lime;">'.$user_date.'</span>';
  }

  
}
?>


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

$maxRows_MyFlaggedStocks = 20;
$pageNum_MyFlaggedStocks = 0;
if (isset($_GET['pageNum_MyFlaggedStocks'])) {
  $pageNum_MyFlaggedStocks = $_GET['pageNum_MyFlaggedStocks'];
}
$startRow_MyFlaggedStocks = $pageNum_MyFlaggedStocks * $maxRows_MyFlaggedStocks;

$colname_MyFlaggedStocks = "-1";
if (isset($_SESSION['MM_UserID'])) {
  $colname_MyFlaggedStocks = $_SESSION['MM_UserID'];
}
mysql_select_db($database_localhost, $localhost);
$query_MyFlaggedStocks = sprintf("SELECT * FROM Stocks, Sectors WHERE Stocks.In_Charge = %s AND Stocks.Flagged = 1 AND Sectors.Sector_ID =Stocks.Sector_ID ORDER BY Stocks.Flag_Date DESC, Stock_Name ASC", GetSQLValueString($colname_MyFlaggedStocks, "int"));
$query_limit_MyFlaggedStocks = sprintf("%s LIMIT %d, %d", $query_MyFlaggedStocks, $startRow_MyFlaggedStocks, $maxRows_MyFlaggedStocks);
$MyFlaggedStocks = mysql_query($query_limit_MyFlaggedStocks, $localhost) or die(mysql_error());
$row_MyFlaggedStocks = mysql_fetch_assoc($MyFlaggedStocks);

if (isset($_GET['totalRows_MyFlaggedStocks'])) {
  $totalRows_MyFlaggedStocks = $_GET['totalRows_MyFlaggedStocks'];
} else {
  $all_MyFlaggedStocks = mysql_query($query_MyFlaggedStocks);
  $totalRows_MyFlaggedStocks = mysql_num_rows($all_MyFlaggedStocks);
}
$totalPages_MyFlaggedStocks = ceil($totalRows_MyFlaggedStocks/$maxRows_MyFlaggedStocks)-1;

$queryString_MyFlaggedStocks = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_MyFlaggedStocks") == false && 
        stristr($param, "totalRows_MyFlaggedStocks") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_MyFlaggedStocks = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_MyFlaggedStocks = sprintf("&totalRows_MyFlaggedStocks=%d%s", $totalRows_MyFlaggedStocks, $queryString_MyFlaggedStocks);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>My flagged stocks</title>
<link href="css/oneColElsCtrHdr.css" rel="stylesheet" type="text/css" />
</head>

<body class="oneColElsCtrHdr">

<div id="container">
  <div id="header">
    <h1>My flagged stocks </h1>
    <p><a href="index.php">Home</a></p>
  <!-- end #header --></div>
  <div id="mainContent">
    <p>Color code : <span style="background-color: red;">Overdue flag</span> // <span style="background-color: orange;">Flag coming in the next 7 days</span> // <span style="background-color: lime;">Coming in more than 7 days</span>
    <table width="100%" border="1" align="center">
      <tr>
        <td width="40%">Stock name</td>
        <td>Flag date</td>
        <td>In portfolio</td>
        <td>Rating</td>
        <td width="30%">Sector </td>
      </tr>
      <?php do { ?>
        <tr>
          <td width="40%"><a href="Stock.php?Stock_ID=<?php echo $row_MyFlaggedStocks['Stock_ID']; ?>"> <?php echo $row_MyFlaggedStocks['Stock_Name']; ?>&nbsp; </a></td>
          <td><?php echo check_in_range($row_MyFlaggedStocks['Flag_Date']); ?></td>
          <td><?php echo $row_MyFlaggedStocks['Is_In_Portfolio']; ?>&nbsp; </td>
          <td><?php echo $row_MyFlaggedStocks['Rating']; ?>&nbsp; </td>
          <td width="30%"><?php echo $row_MyFlaggedStocks['Sector_Name']; ?>&nbsp; </td>
        </tr>
        <?php } while ($row_MyFlaggedStocks = mysql_fetch_assoc($MyFlaggedStocks)); ?>
    </table>
    <br />
    <table border="0">
      <tr>
        <td><?php if ($pageNum_MyFlaggedStocks > 0) { // Show if not first page ?>
            <a href="<?php printf("%s?pageNum_MyFlaggedStocks=%d%s", $currentPage, 0, $queryString_MyFlaggedStocks); ?>">First</a>
            <?php } // Show if not first page ?></td>
        <td><?php if ($pageNum_MyFlaggedStocks > 0) { // Show if not first page ?>
            <a href="<?php printf("%s?pageNum_MyFlaggedStocks=%d%s", $currentPage, max(0, $pageNum_MyFlaggedStocks - 1), $queryString_MyFlaggedStocks); ?>">Previous</a>
            <?php } // Show if not first page ?></td>
        <td><?php if ($pageNum_MyFlaggedStocks < $totalPages_MyFlaggedStocks) { // Show if not last page ?>
            <a href="<?php printf("%s?pageNum_MyFlaggedStocks=%d%s", $currentPage, min($totalPages_MyFlaggedStocks, $pageNum_MyFlaggedStocks + 1), $queryString_MyFlaggedStocks); ?>">Next</a>
            <?php } // Show if not last page ?></td>
        <td><?php if ($pageNum_MyFlaggedStocks < $totalPages_MyFlaggedStocks) { // Show if not last page ?>
            <a href="<?php printf("%s?pageNum_MyFlaggedStocks=%d%s", $currentPage, $totalPages_MyFlaggedStocks, $queryString_MyFlaggedStocks); ?>">Last</a>
            <?php } // Show if not last page ?></td>
      </tr>
    </table>
Records <?php echo ($startRow_MyFlaggedStocks + 1) ?> to <?php echo min($startRow_MyFlaggedStocks + $maxRows_MyFlaggedStocks, $totalRows_MyFlaggedStocks) ?> of <?php echo $totalRows_MyFlaggedStocks ?> foo
</p>
    
  <!-- end #mainContent --></div>
  <div id="footer">
 <p style="font-size:12px;">BDL Capital Management - 2010</p>  <!-- end #footer --></div>
<!-- end #container --></div>
</body>
</html>
<?php
mysql_free_result($MyFlaggedStocks);
?>
