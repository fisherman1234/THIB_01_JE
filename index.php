<?php require_once('Connections/localhost.php'); ?>
<?php
//initialize the session
if (!isset($_SESSION)) {
  session_start();
}

// ** Logout the current user. **
$logoutAction = $_SERVER['PHP_SELF']."?doLogout=true";
if ((isset($_SERVER['QUERY_STRING'])) && ($_SERVER['QUERY_STRING'] != "")){
  $logoutAction .="&". htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_GET['doLogout'])) &&($_GET['doLogout']=="true")){
  //to fully log out a visitor we need to clear the session varialbles
  $_SESSION['MM_Username'] = NULL;
  $_SESSION['MM_UserGroup'] = NULL;
  $_SESSION['PrevUrl'] = NULL;
  unset($_SESSION['MM_Username']);
  unset($_SESSION['MM_UserGroup']);
  unset($_SESSION['PrevUrl']);
	
  $logoutGoTo = "log_in.php";
  if ($logoutGoTo) {
    header("Location: $logoutGoTo");
    exit;
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

$maxRows_MyLastStocks = 10;
$pageNum_MyLastStocks = 0;
if (isset($_GET['pageNum_MyLastStocks'])) {
  $pageNum_MyLastStocks = $_GET['pageNum_MyLastStocks'];
}
$startRow_MyLastStocks = $pageNum_MyLastStocks * $maxRows_MyLastStocks;

$colname_MyLastStocks = "-1";
if (isset($_SESSION['MM_UserID'])) {
  $colname_MyLastStocks = $_SESSION['MM_UserID'];
}
mysql_select_db($database_localhost, $localhost);
$query_MyLastStocks = sprintf("SELECT * FROM Stocks WHERE In_Charge = %s ORDER BY Last_Modified DESC", GetSQLValueString($colname_MyLastStocks, "int"));
$query_limit_MyLastStocks = sprintf("%s LIMIT %d, %d", $query_MyLastStocks, $startRow_MyLastStocks, $maxRows_MyLastStocks);
$MyLastStocks = mysql_query($query_limit_MyLastStocks, $localhost) or die(mysql_error());
$row_MyLastStocks = mysql_fetch_assoc($MyLastStocks);

if (isset($_GET['totalRows_MyLastStocks'])) {
  $totalRows_MyLastStocks = $_GET['totalRows_MyLastStocks'];
} else {
  $all_MyLastStocks = mysql_query($query_MyLastStocks);
  $totalRows_MyLastStocks = mysql_num_rows($all_MyLastStocks);
}
$totalPages_MyLastStocks = ceil($totalRows_MyLastStocks/$maxRows_MyLastStocks)-1;

$queryString_MyLastStocks = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_MyLastStocks") == false && 
        stristr($param, "totalRows_MyLastStocks") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_MyLastStocks = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_MyLastStocks = sprintf("&totalRows_MyLastStocks=%d%s", $totalRows_MyLastStocks, $queryString_MyLastStocks);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Home</title>
<link href="css/main.css" rel="stylesheet" type="text/css" />
<link href="css/oneColElsCtrHdr.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/jquery-1.4.4.min.js"></script>
<script type="text/javascript" src="js/jquery-ui-1.8.7.custom.min.js"></script>
<link type="text/css" href="css/smoothness/jquery-ui-1.8.7.custom.css" rel="stylesheet" />
<script>
$(function() {
  		
            $("#stock").autocomplete({
                source: "Stocks_Search.php",
                minLength: 2,
                select: function(event, ui) {
                    $('#stock_id').val(ui.item.id);
					location.href = 'Stock.php?Stock_ID='+ui.item.id;

                }
            });
			
			 $("#sector").autocomplete({
                source: "Sectors_Search.php",
                minLength: 1,
                select: function(event, ui) {
                    $('#stock_id').val(ui.item.id);
					location.href = 'Sector.php?Sector_ID='+ui.item.id;

                }
            });
 

        });
        </script>


<style type="text/css">
<!--
.oneColElsCtrHdr #container #mainContent form .ui-widget {
	font-size: 0.9em;
}
-->
</style>
</head>

<body class="oneColElsCtrHdr">

<div id="container">
  <div id="header">
    <h1>Home</h1>
  <!-- end #header --></div>
  <div id="mainContent">
  <p align="right"><a href="<?php echo $logoutAction ?>">Log out</a></p>
<table width="100%" border="0">
      <tr>
        <td colspan="3" rowspan="2" valign="top"><h3>Find a stock </h3>
          <form   method="post" actions="">
            <p class="ui-widget">
              <label for="stock">Stock : </label>
              <input type="text" id="stock"  name="stock" />
              <input type="hidden" id="stock_id" name="stock_id" />
              <br />Click on the suggested name to access its form.</p>
          </form>
          <h3>Find a sector </h3>
          <form id="form1" name="form1" method="post" action="">
            <p class="ui-widget">
              <label for="stock">Sector : </label>
              <input type="text" id="sector"  name="sector" />
              <input type="hidden" id="sector_id" name="sector_id" />
              <br />Click on the suggested name to access its form. </p>
          </form>
          <h2>&nbsp;</h2>
        <h4><a href="Administration.php">Administration</a></h4></td>
        <td width="50%">
        <h3>My lasts stocks </h3>
        <div id="Small_text">
          <table width="100%" border="1" align="center">
            <tr>
              <td width="55%">Stock</td>
              <td width="45%">Rating</td>
              </tr>
            <?php do { ?>
              <tr>
                <td><a href="Stock.php?Stock_ID=<?php echo $row_MyLastStocks['Stock_ID']; ?>"> <?php echo $row_MyLastStocks['Stock_Name']; ?>&nbsp; </a></td>
                <td><?php echo $row_MyLastStocks['Rating']; ?>&nbsp; </td>
                </tr>
              <?php } while ($row_MyLastStocks = mysql_fetch_assoc($MyLastStocks)); ?>
        </table></div>
        <p><a href="MyStocks.php">My stocks</a>  // <a href="MyFlaggedStocks.php">My flagged stocks</a></p>
        </td>
      </tr>
      <tr>
        <td width="50%" valign="top"><h3>Queries</h3>
          <ul>
            <li><a href="Portfolio.php">Portfolio</a></li>
            <li>Flagged stocks : <a href="Flagged.php?flagged=1">Yes</a> // <a href="Flagged.php?Flagged=0">No</a></li>
            <li>BDL Position : <a href="Position.php?Position_BDL=Neutral">Neutral</a> // <a href="Position.php?Position_BDL=Long">Long</a> // <a href="Position.php?Position_BDL=Short">Short</a></li>
            <li>Ratings: <a href="Ratings.php?Rating=Good business">Good business</a> // <a href="Ratings.php?Rating=Bad%20business">Bad business</a> // <a href="Ratings.php?Rating=Neutral">Neutral</a></li>
          </ul>
        <p>&nbsp;</p></td>
      </tr>
      <tr>
        <td colspan="3" valign="top">&nbsp;</td>
        <td valign="top">&nbsp;</td>
      </tr>
    </table>
    <h2>&nbsp;</h2>
<p>&nbsp;</p>
	<!-- end #mainContent --></div>
  <div id="footer">
    <p>&nbsp;</p>
  <!-- end #footer --></div>
<!-- end #container --></div>
</body>
</html>
<?php
mysql_free_result($MyLastStocks);
?>
