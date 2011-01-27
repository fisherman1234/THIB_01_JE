<?php require_once('Connections/localhost.php'); ?>
<?php require_once('Connections/localhost.php'); ?>
<?php 

  // Original PHP code by Chirp Internet: www.chirp.com.au
  // Please acknowledge use of this code by including this header.

  function restoreTags($input)
  {
    $opened = array();

    // loop through opened and closed tags in order
    if(preg_match_all("/<(\/?[a-z]+)>?/i", $input, $matches)) {
      foreach($matches[1] as $tag) {
        if(preg_match("/^[a-z]+$/i", $tag, $regs)) {
          // a tag has been opened
          if(strtolower($regs[0]) != 'br') $opened[] = $regs[0];
        } elseif(preg_match("/^\/([a-z]+)$/i", $tag, $regs)) {
          // a tag has been closed
          unset($opened[array_pop(array_keys($opened, $regs[1]))]);
        }
      }
    }

    // close tags that are still open
    if($opened) {
      $tagstoclose = array_reverse($opened);
      foreach($tagstoclose as $tag) $input .= "</$tag>";
    }

    return $input;
  }

function myTruncate($string, $limit=80, $break=".", $pad="...")
{
  // return with no change if string is shorter than $limit
  if(strlen($string) <= $limit) return $string;

  // is $break present between $limit and the end of the string?
  if(false !== ($breakpoint = strpos($string, $break, $limit))) {
    if($breakpoint < strlen($string) - 1) {
      $string = substr($string, 0, $breakpoint) . $pad;
    }
  }
    
  return restoreTags($string);
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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form2")) {
  $updateSQL = sprintf("UPDATE Stocks SET Environment=%s, Competition=%s, Business_Description=%s, Management=%s WHERE Stock_ID=%s",
                       GetSQLValueString($_POST['Environment'], "text"),
                       GetSQLValueString($_POST['Competition'], "text"),
                       GetSQLValueString($_POST['Business_Description'], "text"),
                       GetSQLValueString($_POST['Management'], "text"),
                       GetSQLValueString($_POST['Stock_ID'], "int"));

  mysql_select_db($database_localhost, $localhost);
  $Result1 = mysql_query($updateSQL, $localhost) or die(mysql_error());

  $updateGoTo = "Stock.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form4")) {
  $updateSQL = sprintf("UPDATE Stocks SET Is_In_Portfolio=%s WHERE Stock_ID=%s",
                       GetSQLValueString($_POST['Is_In_Portfolio'], "int"),
                       GetSQLValueString($_POST['Stock_ID'], "int"));

  mysql_select_db($database_localhost, $localhost);
  $Result1 = mysql_query($updateSQL, $localhost) or die(mysql_error());
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form6")) {
  $updateSQL = sprintf("UPDATE Stocks SET Valorisation=%s WHERE Stock_ID=%s",
                       GetSQLValueString($_POST['Valorisation'], "text"),
                       GetSQLValueString($_POST['Stock_ID'], "int"));

  mysql_select_db($database_localhost, $localhost);
  $Result1 = mysql_query($updateSQL, $localhost) or die(mysql_error());
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form7")) {
  $updateSQL = sprintf("UPDATE Stocks SET Investment_Case=%s WHERE Stock_ID=%s",
                       GetSQLValueString($_POST['Investment_Case'], "text"),
                       GetSQLValueString($_POST['Stock_ID'], "int"));

  mysql_select_db($database_localhost, $localhost);
  $Result1 = mysql_query($updateSQL, $localhost) or die(mysql_error());
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form10")) {
  $updateSQL = sprintf("UPDATE Stocks SET Investment_Risks_Macro=%s, Investment_Risks_Micro=%s WHERE Stock_ID=%s",
                       GetSQLValueString($_POST['Investment_Risks_Macro'], "text"),
                       GetSQLValueString($_POST['Investment_Risks_Micro'], "text"),
                       GetSQLValueString($_POST['Stock_ID'], "int"));

  mysql_select_db($database_localhost, $localhost);
  $Result1 = mysql_query($updateSQL, $localhost) or die(mysql_error());
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form12")) {
  $updateSQL = sprintf("UPDATE Stocks SET Rating=%s WHERE Stock_ID=%s",
                       GetSQLValueString($_POST['Rating'], "text"),
                       GetSQLValueString($_POST['Stock_ID'], "int"));

  mysql_select_db($database_localhost, $localhost);
  $Result1 = mysql_query($updateSQL, $localhost) or die(mysql_error());
}

#if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form16")) {
#  $updateSQL = sprintf("UPDATE Stocks SET In_Charge=%s WHERE Stock_ID=%s",
#                       GetSQLValueString($_POST['In_Charge'], "int"),
#                       GetSQLValueString($_POST['Stock_ID'], "int"));
#
#  mysql_select_db($database_localhost, $localhost);
#  $Result1 = mysql_query($updateSQL, $localhost) or die(mysql_error());
#}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form17")) {
  $updateSQL = sprintf("UPDATE Stocks SET Sector_ID=%s, In_Charge=%s WHERE Stock_ID=%s",
                       GetSQLValueString($_POST['Sector_ID'], "int"),
                       GetSQLValueString($_POST['In_Charge'], "int"),
                       GetSQLValueString($_POST['Stock_ID'], "int"));

  mysql_select_db($database_localhost, $localhost);
  $Result1 = mysql_query($updateSQL, $localhost) or die(mysql_error());
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  $updateSQL = sprintf("UPDATE Stocks SET Environment=%s WHERE Stock_ID=%s",
                       GetSQLValueString($_POST['Environment'], "text"),
                       GetSQLValueString($_POST['Stock_ID'], "int"));

  mysql_select_db($database_localhost, $localhost);
  $Result1 = mysql_query($updateSQL, $localhost) or die(mysql_error());
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form2")) {
  $updateSQL = sprintf("UPDATE Stocks SET Business_Description=%s WHERE Stock_ID=%s",
                       GetSQLValueString($_POST['Business_Description'], "text"),
                       GetSQLValueString($_POST['Stock_ID'], "int"));

  mysql_select_db($database_localhost, $localhost);
  $Result1 = mysql_query($updateSQL, $localhost) or die(mysql_error());
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form13")) {
  $updateSQL = sprintf("UPDATE Stocks SET Competition=%s WHERE Stock_ID=%s",
                       GetSQLValueString($_POST['Competition'], "text"),
                       GetSQLValueString($_POST['Stock_ID'], "int"));

  mysql_select_db($database_localhost, $localhost);
  $Result1 = mysql_query($updateSQL, $localhost) or die(mysql_error());
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form14")) {
  $updateSQL = sprintf("UPDATE Stocks SET Management=%s WHERE Stock_ID=%s",
                       GetSQLValueString($_POST['Management'], "text"),
                       GetSQLValueString($_POST['Stock_ID'], "int"));

  mysql_select_db($database_localhost, $localhost);
  $Result1 = mysql_query($updateSQL, $localhost) or die(mysql_error());
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form16")) {
  $updateSQL = sprintf("UPDATE Stocks SET In_Charge=%s, Is_In_Portfolio=%s, Flagged=%s, Flag_Date=%s WHERE Stock_ID=%s",
                       GetSQLValueString($_POST['In_Charge'], "int"),
					   GetSQLValueString(isset($_POST['Is_In_Portfolio']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString(isset($_POST['Flagged']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString($_POST['Flag_Date'], "date"),
                       GetSQLValueString($_POST['Stock_ID'], "int"));

  mysql_select_db($database_localhost, $localhost);
  $Result1 = mysql_query($updateSQL, $localhost) or die(mysql_error());
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form17")) {
  $updateSQL = sprintf("UPDATE Stocks SET Sector_ID=%s, Stock_Name=%s, Ticker=%s WHERE Stock_ID=%s",
                       GetSQLValueString($_POST['Sector_ID'], "int"),
                       GetSQLValueString($_POST['Stock_Name'], "text"),
                       GetSQLValueString($_POST['Ticker'], "text"),
                       GetSQLValueString($_POST['Stock_ID'], "int"));

  mysql_select_db($database_localhost, $localhost);
  $Result1 = mysql_query($updateSQL, $localhost) or die(mysql_error());
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form18")) {
  $insertSQL = sprintf("INSERT INTO Meetings_Results (Meeting_ID, Stock_ID, Meeting_Type, Meeting_Date, Meeting_Contact, BDL_Contact, Meeting_Notes, Meeting_Conclusions) VALUES (%s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['Meeting_ID'], "int"),
                       GetSQLValueString($_POST['Stock_ID'], "int"),
                       GetSQLValueString($_POST['Meeting_Type'], "text"),
                       GetSQLValueString($_POST['Meeting_Date'], "date"),
                       GetSQLValueString($_POST['Meeting_Contact'], "text"),
                       GetSQLValueString($_POST['BDL_Contact'], "int"),
                       GetSQLValueString($_POST['Meeting_Notes'], "text"),
                       GetSQLValueString($_POST['Meeting_Conclusions'], "text"));

  mysql_select_db($database_localhost, $localhost);
  $Result1 = mysql_query($insertSQL, $localhost) or die(mysql_error());
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form13")) {
  $insertSQL = sprintf("INSERT INTO Details (Stock_ID, Last_Entry_Date, Sector_Analysis_Title, Sector_Analysis_Text) VALUES (%s, %s, %s, %s)",
                       GetSQLValueString($_POST['Stock_ID'], "int"),
                       GetSQLValueString($_POST['Last_Entry_Date'], "date"),
                       GetSQLValueString($_POST['Sector_Analysis_Title'], "text"),
                       GetSQLValueString($_POST['Sector_Analysis_Text'], "text"));

  mysql_select_db($database_localhost, $localhost);
  $Result1 = mysql_query($insertSQL, $localhost) or die(mysql_error());
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form11")) {
  $insertSQL = sprintf("INSERT INTO Contacts (Stock_ID, Job_Title, Name, Title, Email, Telephone) VALUES (%s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['Stock_ID'], "int"),
                       GetSQLValueString($_POST['Job_Title'], "text"),
                       GetSQLValueString($_POST['Name'], "text"),
                       GetSQLValueString($_POST['Title'], "text"),
                       GetSQLValueString($_POST['Email'], "text"),
                       GetSQLValueString($_POST['Telephone'], "text"));

  mysql_select_db($database_localhost, $localhost);
  $Result1 = mysql_query($insertSQL, $localhost) or die(mysql_error());
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form9")) {
  $insertSQL = sprintf("INSERT INTO Meetings_Results (Stock_ID, Meeting_Type, Meeting_Date, Meeting_Contact, BDL_Contact, Meeting_Notes, Meeting_Conclusions) VALUES (%s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['Stock_ID'], "int"),
                       GetSQLValueString($_POST['Meeting_Type'], "text"),
                       GetSQLValueString($_POST['Meeting_Date'], "date"),
                       GetSQLValueString($_POST['Meeting_Contact'], "text"),
                       GetSQLValueString($_POST['BDL_Contact'], "int"),
                       GetSQLValueString($_POST['Meeting_Notes'], "text"),
                       GetSQLValueString($_POST['Meeting_Conclusions'], "text"));

  mysql_select_db($database_localhost, $localhost);
  $Result1 = mysql_query($insertSQL, $localhost) or die(mysql_error());
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form8")) {
  $insertSQL = sprintf("INSERT INTO BDL_Discussions (Stock_ID, Discussion_Date, View_BDL, Alert_BDL, Alert_Date, Stock_Price, Position_BDL) VALUES (%s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['Stock_ID'], "text"),
                       GetSQLValueString($_POST['Discussion_Date'], "date"),
                       GetSQLValueString($_POST['View_BDL'], "text"),
                       GetSQLValueString(isset($_POST['Alert_BDL']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString($_POST['Alert_Date'], "date"),
                       GetSQLValueString($_POST['Stock_Price'], "double"),
                       GetSQLValueString($_POST['Position_BDL'], "text"));

  mysql_select_db($database_localhost, $localhost);
  $Result1 = mysql_query($insertSQL, $localhost) or die(mysql_error());
}

$colname_Sector = "-1";
if (isset($_GET['Stock_ID'])) {
  $colname_Sector = $_GET['Stock_ID'];
}
mysql_select_db($database_localhost, $localhost);
$query_Sector = sprintf("SELECT * FROM Sectors, Stocks WHERE Sectors.Sector_ID=Stocks.Sector_ID AND Stocks.Stock_ID=%s", GetSQLValueString($colname_Sector, "int"));
$Sector = mysql_query($query_Sector, $localhost) or die(mysql_error());
$row_Sector = mysql_fetch_assoc($Sector);
$totalRows_Sector = mysql_num_rows($Sector);

$maxRows_Discussions = 30;
$pageNum_Discussions = 0;
if (isset($_GET['pageNum_Discussions'])) {
  $pageNum_Discussions = $_GET['pageNum_Discussions'];
}
$startRow_Discussions = $pageNum_Discussions * $maxRows_Discussions;

$colname_Discussions = "-1";
if (isset($_GET['Stock_ID'])) {
  $colname_Discussions = $_GET['Stock_ID'];
}
mysql_select_db($database_localhost, $localhost);
$query_Discussions = sprintf("SELECT * FROM BDL_Discussions WHERE Stock_ID = %s ORDER BY BDL_Discussions.Discussion_Date", GetSQLValueString($colname_Discussions, "text"));
$query_limit_Discussions = sprintf("%s LIMIT %d, %d", $query_Discussions, $startRow_Discussions, $maxRows_Discussions);
$Discussions = mysql_query($query_limit_Discussions, $localhost) or die(mysql_error());
$row_Discussions = mysql_fetch_assoc($Discussions);

if (isset($_GET['totalRows_Discussions'])) {
  $totalRows_Discussions = $_GET['totalRows_Discussions'];
} else {
  $all_Discussions = mysql_query($query_Discussions);
  $totalRows_Discussions = mysql_num_rows($all_Discussions);
}
$totalPages_Discussions = ceil($totalRows_Discussions/$maxRows_Discussions)-1;

$colname_Meetins_Results = "-1";
if (isset($_GET['Stock_ID'])) {
  $colname_Meetins_Results = $_GET['Stock_ID'];
}
mysql_select_db($database_localhost, $localhost);
$query_Meetins_Results = sprintf("SELECT * FROM Meetings_Results, Users WHERE Stock_ID = %s AND Meetings_Results.BDL_Contact=Users.Id ORDER BY Meetings_Results.Meeting_Date", GetSQLValueString($colname_Meetins_Results, "int"));
$Meetins_Results = mysql_query($query_Meetins_Results, $localhost) or die(mysql_error());
$row_Meetins_Results = mysql_fetch_assoc($Meetins_Results);
$totalRows_Meetins_Results = mysql_num_rows($Meetins_Results);

mysql_select_db($database_localhost, $localhost);
$query_Users = "SELECT * FROM Users";
$Users = mysql_query($query_Users, $localhost) or die(mysql_error());
$row_Users = mysql_fetch_assoc($Users);
$totalRows_Users = mysql_num_rows($Users);

$colname_Contacts = "-1";
if (isset($_GET['Stock_ID'])) {
  $colname_Contacts = $_GET['Stock_ID'];
}
mysql_select_db($database_localhost, $localhost);
$query_Contacts = sprintf("SELECT * FROM Contacts WHERE Stock_ID = %s", GetSQLValueString($colname_Contacts, "int"));
$Contacts = mysql_query($query_Contacts, $localhost) or die(mysql_error());
$row_Contacts = mysql_fetch_assoc($Contacts);
$totalRows_Contacts = mysql_num_rows($Contacts);

mysql_select_db($database_localhost, $localhost);
$query_Sectors = "SELECT * FROM Sectors";
$Sectors = mysql_query($query_Sectors, $localhost) or die(mysql_error());
$row_Sectors = mysql_fetch_assoc($Sectors);
$totalRows_Sectors = mysql_num_rows($Sectors);

$colname_Detail = "-1";
if (isset($_GET['Stock_ID'])) {
  $colname_Detail = $_GET['Stock_ID'];
}
mysql_select_db($database_localhost, $localhost);
$query_Detail = sprintf("SELECT * FROM Details, Stocks WHERE Details.Sector_ID=Stocks.Sector_ID AND Stocks.Stock_ID=%s", GetSQLValueString($colname_Detail, "int"));
$Detail = mysql_query($query_Detail, $localhost) or die(mysql_error());
$row_Detail = mysql_fetch_assoc($Detail);
$totalRows_Detail = mysql_num_rows($Detail);

mysql_select_db($database_localhost, $localhost);
$query_All_Users = "SELECT * FROM Users";
$All_Users = mysql_query($query_All_Users, $localhost) or die(mysql_error());
$row_All_Users = mysql_fetch_assoc($All_Users);
$totalRows_All_Users = mysql_num_rows($All_Users);

mysql_select_db($database_localhost, $localhost);
$query_All_Users2 = "SELECT * FROM Users";
$All_Users2 = mysql_query($query_All_Users2, $localhost) or die(mysql_error());
$row_All_Users2 = mysql_fetch_assoc($All_Users2);
$totalRows_All_Users2 = mysql_num_rows($All_Users2);

$queryString_Discussions = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_Discussions") == false && 
        stristr($param, "totalRows_Discussions") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_Discussions = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_Discussions = sprintf("&totalRows_Discussions=%d%s", $totalRows_Discussions, $queryString_Discussions);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Stock : <?php echo $row_Sector['Stock_Name']; ?></title>
<!--[if IE]>
<style type="text/css"> 
/* place css fixes for all versions of IE in this conditional comment */
.twoColElsLtHdr #sidebar1 { padding-top: 30px; }
.twoColElsLtHdr #mainContent { zoom: 1; padding-top: 15px; }
/* the above proprietary zoom property gives IE the hasLayout it needs to avoid several bugs */
</style>
<![endif]-->
<link href="css/main.css" rel="stylesheet" type="text/css" />
<script src="SpryAssets/SpryTabbedPanels.js" type="text/javascript"></script>
<script src="SpryAssets/SpryCollapsiblePanel.js" type="text/javascript"></script>
<script src="SpryAssets/SpryAccordion.js" type="text/javascript"></script>
<script src="SpryAssets/SpryValidationTextField.js" type="text/javascript"></script>
<link href="SpryAssets/SpryTabbedPanels.css" rel="stylesheet" type="text/css" />
<link href="SpryAssets/SpryCollapsiblePanel.css" rel="stylesheet" type="text/css" />
<link href="SpryAssets/SpryAccordion.css" rel="stylesheet" type="text/css" />
<link href="SpryAssets/SpryValidationTextField.css" rel="stylesheet" type="text/css" />
<link type="text/css" href="css/smoothness/jquery-ui-1.8.7.custom.css" rel="stylesheet" />	
<script type="text/javascript" src="js/jquery-1.4.4.min.js"></script>
<script type="text/javascript" src="js/jquery-ui-1.8.7.custom.min.js"></script>

<script language="javascript" type="text/javascript" src="ajaxfilemanager/jscripts/tiny_mce/tiny_mce.js"></script>


	<script language="javascript" type="text/javascript">

		tinyMCE.init({

			mode : "textareas",

			elements : "ajaxfilemanager",

			theme : "advanced",

			plugins : "pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,wordcount,advlist,autosave",



		// Theme options

		theme_advanced_buttons1 : "save,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,styleselect,formatselect,fontselect,fontsizeselect",

		theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,bullets,numbest,|,link,unlink,anchor,image,cleanup,|,forecolor,backcolor,|,print,fullscreen, code",

		theme_advanced_buttons3 : "",

		theme_advanced_toolbar_location : "top",

		theme_advanced_toolbar_align : "left",

		theme_advanced_statusbar_location : "bottom",

		theme_advanced_resizing : true,


			file_browser_callback : "ajaxfilemanager",

			paste_use_dialog : false,

			

		});



		function ajaxfilemanager(field_name, url, type, win) {

			var ajaxfilemanagerurl = "ajaxfilemanager/jscripts/tiny_mce/plugins/ajaxfilemanager/ajaxfilemanager.php";

			switch (type) {

				case "image":

					break;

				case "media":

					break;

				case "flash": 

					break;

				case "file":

					break;

				default:

					return false;

			}

            tinyMCE.activeEditor.windowManager.open({

                url: "ajaxfilemanager/jscripts/tiny_mce/plugins/ajaxfilemanager/ajaxfilemanager.php",

                width: 782,

                height: 440,

                inline : "yes",

                close_previous : "no"

            },{

                window : win,

                input : field_name

            });

            

/*            return false;			

			var fileBrowserWindow = new Array();

			fileBrowserWindow["file"] = ajaxfilemanagerurl;

			fileBrowserWindow["title"] = "Ajax File Manager";

			fileBrowserWindow["width"] = "782";

			fileBrowserWindow["height"] = "440";

			fileBrowserWindow["close_previous"] = "no";

			tinyMCE.openWindow(fileBrowserWindow, {

			  window : win,

			  input : field_name,

			  resizable : "yes",

			  inline : "yes",

			  editor_id : tinyMCE.getWindowArg("editor_id")

			});

			

			return false;*/

		}

	</script>


<script>
$(function() {
		$( ".datepicker" ).datepicker({ dateFormat: 'yy-mm-dd' , showButtonPanel: true });
	});
function MM_openBrWindow(theURL,winName,features) { //v2.0
  window.open(theURL,winName,features);
}
</script> 
<style type="text/css">
<!--
back-button {
	text-decoration: underline;
	padding-bottom: 10px;
}
-->
</style></head>

<body class="twoColElsLtHdr">

<div id="container">
  <div id="header">
    <h1>Stock : <?php echo $row_Sector['Stock_Name']; ?> - Sector : <?php echo $row_Sector['Sector_Name']; ?></h1>
    <p><a onClick="history.go(-1);return true;"><u>Back</u></a>	<u><a href="index.php">Home</a></u> <a href="PrintStock.php?Stock_ID=<?php echo $_GET[Stock_ID]; ?>">Print</a></p>
  <!-- end #header --></div>
  <div id="mainContent-full">
    <div id="TabbedPanels1" class="TabbedPanels">
      <ul class="TabbedPanelsTabGroup">
        <li class="TabbedPanelsTab" tabindex="0">Overview</li>
        <li class="TabbedPanelsTab" tabindex="0">Analysis</li>
<li class="TabbedPanelsTab" tabindex="0">Investment case</li>
        <li class="TabbedPanelsTab" tabindex="0">Contact</li>
</ul>
      <div class="TabbedPanelsContentGroup">
        <div class="TabbedPanelsContent">
          <table width="100%" border="0">
            <tr>
              <td colspan="2"><form action="<?php echo $editFormAction; ?>" method="post" name="form16" id="form16">
                <table width="100%" align="center">
                  <tr valign="baseline">
                    <td width="23%" align="right" nowrap="nowrap">In charge</td>
                    <td width="77%"><select name="In_Charge">
                      <?php 
do {  
?>
                      <option value="<?php echo $row_All_Users['Id']?>" <?php if (!(strcmp($row_All_Users['Id'], htmlentities($row_Sector['In_Charge'], ENT_COMPAT, 'UTF-8')))) {echo "SELECTED";} ?>><?php echo $row_All_Users['Nom']?></option>
                      <?php
} while ($row_All_Users = mysql_fetch_assoc($All_Users));
?>
                      </select></td>
                  </tr>
                  <tr valign="baseline">
                    <td height="30" align="right" nowrap="nowrap">In portfolio</td>
                    <td><input type="checkbox" name="Is_In_Portfolio" value=""  <?php if (!(strcmp(htmlentities($row_Sector['Is_In_Portfolio'], ENT_COMPAT, 'UTF-8'),1))) {echo "checked=\"checked\"";} ?> /></td>
                  </tr>
                  <tr valign="baseline">
                    <td nowrap="nowrap" align="right">Flagged</td>
                    <td><input type="checkbox" name="Flagged" value=""  <?php if (!(strcmp(htmlentities($row_Sector['Flagged'], ENT_COMPAT, 'UTF-8'),1))) {echo "checked=\"checked\"";} ?> /></td>
                  </tr>
                  <tr valign="baseline">
                    <td nowrap="nowrap" align="right"><p>Flag date</p></td>
                    <td><span id="sprytextfield1">
                    <input type="text" class="datepicker" name="Flag_Date" value="<?php echo htmlentities($row_Sector['Flag_Date'], ENT_COMPAT, 'UTF-8'); ?>" size="32" />
<span class="textfieldInvalidFormatMsg">Invalid format. Should be yyyy-mm-dd</span></span></td>
                  </tr>
                  <tr valign="baseline">
                    <td nowrap="nowrap" align="right">&nbsp;</td>
                    <td><input type="submit" value="Update record" /></td>
                  </tr>
                </table>
                <input type="hidden" name="MM_update" value="form16" />
                <input type="hidden" name="Stock_ID" value="<?php echo $row_Sector['Stock_ID']; ?>" />
              </form></td>
              <td align="right" valign="top">&nbsp;</td>
              <td valign="top"><h4>&nbsp;Available analysis </h4>
                <table width="100%" border="1" align="center">
                <tr>
                  <td width="100%">Title</td>
                </tr>
                <?php do { ?>
                  <tr>
                    <td><a href="#" onclick="MM_openBrWindow('Edit_Detail.php?Detail_ID=<?php echo $row_Detail['Detail_ID']; ?>','','scrollbars=yes,resizable=yes,width=800,height=500')"> <?php echo $row_Detail['Sector_Analysis_Title']; ?></a></td>
                  </tr>
                  <?php } while ($row_Detail = mysql_fetch_assoc($Detail)); ?>
              </table></td>
            </tr>
            <tr>
              <td colspan="2"><h4>&nbsp;Stock properties
              </h4>
                <form action="<?php echo $editFormAction; ?>" method="post" name="form17" id="form17">
                  <table width="100%" align="center">
                    <tr valign="baseline">
                      <td width="25%" align="right" nowrap="nowrap">Sector</td>
                      <td width="75%"><select name="Sector_ID">
                        <?php 
do {  
?>
                        <option value="<?php echo $row_Sectors['Sector_ID']?>" <?php if (!(strcmp($row_Sectors['Sector_ID'], htmlentities($row_Sector['Sector_ID'], ENT_COMPAT, 'UTF-8')))) {echo "SELECTED";} ?>><?php echo $row_Sectors['Sector_Name']?></option>
                        <?php
} while ($row_Sectors = mysql_fetch_assoc($Sectors));
?>
                      </select></td>
                    </tr>
                    <tr> </tr>
                    <tr valign="baseline">
                      <td nowrap="nowrap" align="right">Stock name</td>
                      <td><input type="text" name="Stock_Name" value="<?php echo htmlentities($row_Sector['Stock_Name'], ENT_COMPAT, 'UTF-8'); ?>" size="45" /></td>
                    </tr>
                    <tr valign="baseline">
                      <td nowrap="nowrap" align="right">Ticker</td>
                      <td><input type="text" name="Ticker" value="<?php echo htmlentities($row_Sector['Ticker'], ENT_COMPAT, 'UTF-8'); ?>" size="45" /></td>
                    </tr>
                    <tr valign="baseline">
                      <td nowrap="nowrap" align="right">&nbsp;</td>
                      <td><input type="submit" value="Update record" /></td>
                    </tr>
                  </table>
                  <input type="hidden" name="MM_update" value="form17" />
                  <input type="hidden" name="Stock_ID" value="<?php echo $row_Sector['Stock_ID']; ?>" />
                </form>
              <p>&nbsp;</p></td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
          </table>
          <p>&nbsp;</p>
          <p>&nbsp;</p>
<p>&nbsp;</p>
          <p>&nbsp;</p>
        </div>
        <div class="TabbedPanelsContent">
          <div id="TabbedPanels3" class="TabbedPanels">
            <ul class="TabbedPanelsTabGroup">
              <li class="TabbedPanelsTab" tabindex="0">Environment</li>
              <li class="TabbedPanelsTab" tabindex="0">Business description</li>
              <li class="TabbedPanelsTab" tabindex="0">Competition</li>
              <li class="TabbedPanelsTab" tabindex="0">Management</li>
            </ul>
            <div class="TabbedPanelsContentGroup">
              <div class="TabbedPanelsContent">
                <form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1">
                  <table width="100%" align="center">
                    <tr valign="baseline">
                      <td width="20%" align="right" valign="top">Environment</td>
                      <td width="82%"><textarea name="Environment" cols="80" rows="40"><?php echo htmlentities($row_Sector['Environment'], ENT_COMPAT, 'UTF-8'); ?></textarea></td>
                    </tr>
                    <tr valign="baseline">
                      <td width="20%" align="right">&nbsp;</td>
                      <td><input type="submit" value="Update environment" /></td>
                    </tr>
                  </table>
                  <input type="hidden" name="MM_update" value="form1" />
                  <input type="hidden" name="Stock_ID" value="<?php echo $row_Sector['Stock_ID']; ?>" />
                </form>
              </div>
              <div class="TabbedPanelsContent">
                <form action="<?php echo $editFormAction; ?>" method="post" name="form2" id="form2">
                  <table width="100%" align="center">
                    <tr valign="baseline">
                      <td width="20%" align="right" valign="top">Business description</td>
                      <td width="79%"><textarea name="Business_Description" cols="80" rows="40"><?php echo htmlentities($row_Sector['Business_Description'], ENT_COMPAT, 'UTF-8'); ?></textarea></td>
                    </tr>
                    <tr valign="baseline">
                      <td nowrap="nowrap" align="right">&nbsp;</td>
                      <td><input type="submit" value="Update business description" /></td>
                    </tr>
                  </table>
                  <input type="hidden" name="MM_update" value="form2" />
                  <input type="hidden" name="Stock_ID" value="<?php echo $row_Sector['Stock_ID']; ?>" />
                </form>
                
              </div>
              <div class="TabbedPanelsContent">
               
                <form action="<?php echo $editFormAction; ?>" method="post" name="form13" id="form13">
                  <table width="100%" align="center">
                    <tr valign="baseline">
                      <td width="20%" align="right" valign="top">Competition</td>
                      <td width="83%"><textarea name="Competition" cols="80" rows="40"><?php echo htmlentities($row_Sector['Competition'], ENT_COMPAT, 'UTF-8'); ?></textarea></td>
                    </tr>
                    <tr valign="baseline">
                      <td nowrap="nowrap" align="right">&nbsp;</td>
                      <td><input type="submit" value="Update competition" /></td>
                    </tr>
                  </table>
                  <input type="hidden" name="MM_update" value="form13" />
                  <input type="hidden" name="Stock_ID" value="<?php echo $row_Sector['Stock_ID']; ?>" />
                </form>
                
              </div>
              <div class="TabbedPanelsContent">
                
                <form action="<?php echo $editFormAction; ?>" method="post" name="form14" id="form14">
                  <table width="100%" align="center">
                    <tr valign="baseline">
                      <td width="20%" align="right" valign="top">Management</td>
                      <td width="82%"><textarea name="Management" cols="80" rows="40"><?php echo htmlentities($row_Sector['Management'], ENT_COMPAT, 'UTF-8'); ?></textarea></td>
                    </tr>
                    <tr valign="baseline">
                      <td nowrap="nowrap" align="right">&nbsp;</td>
                      <td><input type="submit" value="Update record" /></td>
                    </tr>
                  </table>
                  <input type="hidden" name="MM_update" value="form14" />
                  <input type="hidden" name="Stock_ID" value="<?php echo $row_Sector['Stock_ID']; ?>" />
                </form>
                <p>&nbsp;</p>
</div>
            </div>
          </div>
          <p>&nbsp;</p>
        </div>
<div class="TabbedPanelsContent">
          <div id="TabbedPanels4" class="TabbedPanels">
            <ul class="TabbedPanelsTabGroup">
              <li class="TabbedPanelsTab" tabindex="0">Investment case</li>
              <li class="TabbedPanelsTab" tabindex="0">BDL Discussions</li>
<li class="TabbedPanelsTab" tabindex="0">Investment risks</li>
              <li class="TabbedPanelsTab" tabindex="0">Rating</li>
</ul>
            <div class="TabbedPanelsContentGroup">
              <div class="TabbedPanelsContent">
                <form action="<?php echo $editFormAction; ?>" method="post" name="form7" id="form7">
                  <table width="100%" align="center">
                    <tr valign="baseline">
                      <td width="18%" align="right" valign="top" nowrap="nowrap">Investment Case :</td>
                      <td width="56%"><textarea name="Investment_Case" cols="80" rows="40"><?php echo htmlentities($row_Sector['Investment_Case'], ENT_COMPAT, 'UTF-8'); ?></textarea></td>
                    </tr>
                    <tr>
                    	<td>&nbsp;</td>
                        <td><input type="submit" value="Update investment case" /></td>
                     </tr>
                  </table>
                  <p>
                    <input type="hidden" name="MM_update" value="form7" />
                    <input type="hidden" name="Stock_ID" value="<?php echo $row_Sector['Stock_ID']; ?>" />
                  </p>
             
                </form>
              
              </div>
              <div class="TabbedPanelsContent">
                <table width="100%" border="1" align="center">
                  <tr>
                    <td>Discussion date</td>
                    <td width="60%">BDL View</td>
                    <td>Stock price</td>
                    <td>BDL Position</td>
                  </tr>
                  <?php do { ?>
                  <tr>
                    <td><a href="#" onclick="MM_openBrWindow('EditDiscussion.php?Discussion_ID=<?php echo $row_Discussions['Discussion_ID']; ?>','','scrollbars=yes,width=900,height=500')"><?php echo $row_Discussions['Discussion_Date']; ?></a></td>
                    <td width="60%"><?php echo substr(strip_tags($row_Discussions['View_BDL']),0,200); ?>&nbsp; </td>
                    <td><?php echo $row_Discussions['Stock_Price']; ?>&nbsp; </td>
                    <td><?php echo $row_Discussions['Position_BDL']; ?>&nbsp; </td>
                  </tr>
                  <?php } while ($row_Discussions = mysql_fetch_assoc($Discussions)); ?>
                </table>
                <p></p>
                <div id="CollapsiblePanel2" class="CollapsiblePanel">
                  <div class="CollapsiblePanelTab" tabindex="0">Add a new discussion</div>
                  <div class="CollapsiblePanelContent">
                    <form action="<?php echo $editFormAction; ?>" method="post" name="form8" id="form15">
                      <table width="100%" align="center">
                        <tr valign="baseline">
                          <td width="30%" align="right" nowrap="nowrap">Discussion date</td>
                          <td width="70%"><span id="sprytextfield2">
                            <input type="text" name="Discussion_Date" class="datepicker" value="" size="32" />
                            <span class="textfieldInvalidFormatMsg">Invalid format (should be : yyyy-mm-dd)</span></span></td>
                        </tr>
                        <tr valign="baseline">
                          <td nowrap="nowrap" align="right">BDL View</td>
                          <td><textarea name="View_BDL" cols="80" rows="20"></textarea></td>
                        </tr>
                        <tr valign="baseline">
                          <td nowrap="nowrap" align="right">Stock price</td>
                          <td><input type="text" name="Stock_Price" value="" size="32" /></td>
                        </tr>
                        <tr valign="baseline">
                          <td nowrap="nowrap" align="right">BDL Position</td>
                          <td><select name="Position_BDL">
                            <option value="Neutral" <?php if (!(strcmp("Neutral", ""))) {echo "SELECTED";} ?>>Neutral</option>
                            <option value="Long" <?php if (!(strcmp("Long", ""))) {echo "SELECTED";} ?>>Long</option>
                            <option value="Short" <?php if (!(strcmp("Short", ""))) {echo "SELECTED";} ?>>Short</option>
                          </select></td>
                        </tr>
                        <tr valign="baseline">
                          <td nowrap="nowrap" align="right">&nbsp;</td>
                          <td><input type="submit" value="Insert discussion" /></td>
                        </tr>
                      </table>
                      <input type="hidden" name="Stock_ID" value="<?php echo $row_Sector['Stock_ID']; ?>" />
                      <input type="hidden" name="MM_insert" value="form8" />
                    </form>
                  </div>
                  <div class="CollapsiblePanelContent">
                    <form action="<?php echo $editFormAction; ?>" method="post" name="form8">
                      <table align="center">
                        <tr valign="baseline"> </tr>
                        <tr valign="baseline"> </tr>
                      </table>
                    </form>
                  </div>
                </div>
                <p>&nbsp;</p>
              </div>
<div class="TabbedPanelsContent">
                <form action="<?php echo $editFormAction; ?>" method="post" name="form10" id="form10">
                  <table width="100%" align="center">
                    <tr valign="baseline">
                      <td width="30%" align="right" valign="top" nowrap="nowrap">Investment risks : Macro</td>
                      <td width="70%"><textarea name="Investment_Risks_Macro" cols="80" rows="20"><?php echo htmlentities($row_Sector['Investment_Risks_Macro'], ENT_COMPAT, 'UTF-8'); ?></textarea></td>
                    </tr>
                    <tr valign="baseline">
                      <td nowrap="nowrap" align="right" valign="top">Investment risks : Micro</td>
                      <td><textarea name="Investment_Risks_Micro" cols="80" rows="20"><?php echo htmlentities($row_Sector['Investment_Risks_Micro'], ENT_COMPAT, 'UTF-8'); ?></textarea></td>
                    </tr>
                    <tr valign="baseline">
                      <td nowrap="nowrap" align="right">&nbsp;</td>
                      <td><input type="submit" value="Update investment risks" /></td>
                    </tr>
                  </table>
                  <input type="hidden" name="MM_update" value="form10" />
                  <input type="hidden" name="Stock_ID" value="<?php echo $row_Sector['Stock_ID']; ?>" />
                </form>
              </div>
              <div class="TabbedPanelsContent">
                <form action="<?php echo $editFormAction; ?>" method="post" name="form12" id="form12">
                  <table align="center">
                    <tr valign="baseline">
                      <td nowrap="nowrap" align="right">Rating</td>
                      <td valign="baseline"><table>
                        <tr>
                          <td><input type="radio" name="Rating" value="Good business" <?php if (!(strcmp(htmlentities($row_Sector['Rating'], ENT_COMPAT, 'UTF-8'),"Good business"))) {echo "checked=\"checked\"";} ?> />
                            Good business</td>
                        </tr>
                        <tr>
                          <td><input type="radio" name="Rating" value="Bad business" <?php if (!(strcmp(htmlentities($row_Sector['Rating'], ENT_COMPAT, 'UTF-8'),"Bad business"))) {echo "checked=\"checked\"";} ?> />
                            Bad business</td>
                        </tr>
                        <tr>
                          <td><input type="radio" name="Rating" value="Neutral" <?php if (!(strcmp(htmlentities($row_Sector['Rating'], ENT_COMPAT, 'UTF-8'),"Neutral"))) {echo "checked=\"checked\"";} ?> />
                            Neutral</td>
                        </tr>
                      </table></td>
                    </tr>
                    <tr valign="baseline">
                      <td nowrap="nowrap" align="right">&nbsp;</td>
                      <td><input type="submit" value="Update rating" /></td>
                    </tr>
                  </table>
                  <input type="hidden" name="MM_update" value="form12" />
                  <input type="hidden" name="Stock_ID" value="<?php echo $row_Sector['Stock_ID']; ?>" />
                </form>
              </div>
</div>
          </div>
          <p>&nbsp;</p>
</div>
        <div class="TabbedPanelsContent"> <br />
          <div id="TabbedPanels2" class="TabbedPanels">
            <ul class="TabbedPanelsTabGroup">
              <li class="TabbedPanelsTab" tabindex="0">Meetings/Results</li>
              <li class="TabbedPanelsTab" tabindex="0">Contacts</li>
            </ul>
            <div class="TabbedPanelsContentGroup">
              <div class="TabbedPanelsContent">
                <div id="Accordion1" class="Accordion" tabindex="0">
                  <div class="AccordionPanel">
                    <div class="AccordionPanelTab">All</div>
                    <div class="AccordionPanelContent">
                      <table width="100%" border="1" align="center">
                        <tr>
                          <td width="15%">Meeting Type</td>
                          <td width="10%">Meeting Date</td>
                          <td width="15%">Contact</td>
                          <td width="10%">BDL Contact</td>
                          <td width="25%">Notes</td>
                          <td width="25%">Conclusions</td>
                        </tr>
                        <?php do { ?>
                        <tr>
                          <td><?php echo $row_Meetins_Results['Meeting_Type']; ?>&nbsp;</td>
                          <td><a href="#" onclick="MM_openBrWindow('EditMeeting.php?Meeting_ID=<?php echo $row_Meetins_Results['Meeting_ID']; ?>','','scrollbars=yes,width=900,height=500')"><?php echo $row_Meetins_Results['Meeting_Date']; ?></a></td>
                          <td><?php echo $row_Meetins_Results['Meeting_Contact']; ?>&nbsp; </td>
                          <td><?php echo $row_Meetins_Results['Initiales']; ?>&nbsp; </td>
                          <td><?php echo substr(strip_tags($row_Meetins_Results['Meeting_Notes']),0,200); ?></td>
                          <td><?php echo substr(strip_tags($row_Meetins_Results['Meeting_Conclusions']),0,200); ?>&nbsp; </td>
                        </tr>
                        <?php } while ($row_Meetins_Results = mysql_fetch_assoc($Meetins_Results)); ?>
                      </table>
                    </div>
                  </div>
                  <div class="AccordionPanel">
                    <div class="AccordionPanelTab">New</div>
                    <div class="AccordionPanelContent">
                      <form action="<?php echo $editFormAction; ?>" method="post" name="form9" id="form9">
                        <table width="100%" align="center">
                          <tr valign="baseline">
                            <td width="27%" align="right" >Meeting type</td>
                            <td width="73%" valign="baseline"><table>
                              <tr>
                                <td><input type="radio" name="Meeting_Type" value="Management meeting" />
                                  Management meeting</td>
                              </tr>
                              <tr>
                                <td><input type="radio" name="Meeting_Type" value="Analyst meeting" />
                                  Analyst meeting</td>
                              </tr>
                              <tr>
                                <td><input type="radio" name="Meeting_Type" value="Expert meeting" />
                                  Expert meeting</td>
                              </tr>
                              <tr>
                                <td><input type="radio" name="Meeting_Type" value="Results" />
                                  Results</td>
                              </tr>
                            </table></td>
                          </tr>
                          <tr valign="baseline">
                            <td nowrap="nowrap" align="right">Meeting date:</td>
                            <td><span id="sprytextfield5">
                            <input type="text" name="Meeting_Date" class="datepicker" value="" size="32" />
                            <span class="textfieldInvalidFormatMsg">Invalid format. Should be yyyy-mm-dd</span><span class="textfieldRequiredMsg">A value is required.</span></span></td>
                          </tr>
                          <tr valign="baseline">
                            <td nowrap="nowrap" align="right">Meeting contact:</td>
                            <td><input type="text" name="Meeting_Contact" value="" size="32" /></td>
                          </tr>
                          <tr valign="baseline">
                            <td nowrap="nowrap" align="right">BDL Contact:</td>
                            <td><select name="BDL_Contact">
                              <?php 
do {  
?>
                              <option value="<?php echo $row_Users['Id']?>" ><?php echo $row_Users['Initiales']?></option>
                              <?php
} while ($row_Users = mysql_fetch_assoc($All_Users2));
?>
                            </select></td>
                          </tr>
                          <tr> </tr>
                          <tr valign="baseline">
                            <td nowrap="nowrap" align="right" valign="top">Meeting notes</td>
                            <td><textarea name="Meeting_Notes" cols="80" rows="20"></textarea></td>
                          </tr>
                          <tr valign="baseline">
                            <td nowrap="nowrap" align="right" valign="top">Meeting conclusions<br /><br /><input type="submit" value="Insert meeting" /></td>
                            <td><textarea name="Meeting_Conclusions" cols="80" rows="20"></textarea></td>
                          </tr>
                          <tr valign="baseline">
                            <td nowrap="nowrap" align="right">&nbsp;</td>
                            <td> </td>
                          </tr>
                        </table>
                        <input type="hidden" name="Stock_ID" value="<?php echo $row_Sector['Stock_ID']; ?>" />
                        <input type="hidden" name="MM_insert" value="form9" />
                      </form>
                    </div>
                  </div>
                </div>
                <p>&nbsp;</p>
              </div>
              <div class="TabbedPanelsContent">
                <div id="Accordion2" class="Accordion" tabindex="0">
                  <div class="AccordionPanel">
                    <div class="AccordionPanelTab">Contact list</div>
                    <div class="AccordionPanelContent">
                      <table width="100%" border="1" align="center">
                        <tr>
                          <td width="21%">Job title</td>
                          <td width="18%">Name</td>
                          <td width="17%">Position</td>
                          <td width="18%">Email</td>
                          <td width="26%">Telephone</td>
                        </tr>
                        <?php do { ?>
                        <tr>
                          <td><?php echo $row_Contacts['Job_Title']; ?>&nbsp;</td>
                          <td><a href="#" onclick="MM_openBrWindow('EditContact.php?Contact_ID=<?php echo $row_Contacts['Contact_ID']; ?>','','scrollbars=yes,width=900,height=500')"><?php echo $row_Contacts['Name']; ?></a></td>
                          <td><?php echo $row_Contacts['Title']; ?>&nbsp; </td>
                          <td><?php echo $row_Contacts['Email']; ?>&nbsp; </td>
                          <td><?php echo $row_Contacts['Telephone']; ?>&nbsp; </td>
                        </tr>
                        <?php } while ($row_Contacts = mysql_fetch_assoc($Contacts)); ?>
                      </table>
                      <br />
                    </div>
                  </div>
                  <div class="AccordionPanel">
                    <div class="AccordionPanelTab">New contact</div>
                    <div class="AccordionPanelContent">
                      <form action="<?php echo $editFormAction; ?>" method="post" name="form11" id="form11">
                        <table align="center">
                          <tr valign="baseline">
                            <td nowrap="nowrap" align="right">Job title:</td>
                            <td valign="baseline"><table>
                              <tr>
                                <td><input type="radio" name="Job_Title" value="IR" />
                                  IR</td>
                              </tr>
                              <tr>
                                <td><input type="radio" name="Job_Title" value="Sell side analyst" />
                                  Sell side analyst</td>
                              </tr>
                              <tr>
                                <td><input type="radio" name="Job_Title" value="Manager contact" />
                                  Manager contact</td>
                              </tr>
                              <tr>
                                <td><input type="radio" name="Job_Title" value="Expert" />
                                  Expert</td>
                              </tr>
                            </table></td>
                          </tr>
                          <tr valign="baseline">
                            <td nowrap="nowrap" align="right">Name</td>
                            <td><span id="sprytextfield6">
                              <input type="text" name="Name" value="" size="32" />
                            <span class="textfieldRequiredMsg">A value is required.</span></span></td>
                          </tr>
                          <tr valign="baseline">
                            <td nowrap="nowrap" align="right">Position</td>
                            <td><input type="text" name="Title" value="" size="32" /></td>
                          </tr>
                          <tr valign="baseline">
                            <td nowrap="nowrap" align="right">Email</td>
                            <td><span id="sprytextfield3">
                              <input type="text" name="Email" value="" size="32" />
                              <span class="textfieldInvalidFormatMsg">Invalid format.</span></span></td>
                          </tr>
                          <tr valign="baseline">
                            <td nowrap="nowrap" align="right">Telephone</td>
                            <td><span id="sprytextfield4">
                              <input type="text" name="Telephone" value="" size="32" />
</span></td>
                          </tr>
                          <tr valign="baseline">
                            <td nowrap="nowrap" align="right">&nbsp;</td>
                            <td><input type="submit" value="Insert record" /></td>
                          </tr>
                        </table>
                        <input type="hidden" name="Stock_ID" value="<?php echo $row_Sector['Stock_ID']; ?>" />
                        <input type="hidden" name="MM_insert" value="form11" />
                      </form>
                    </div>
                  </div>
                </div>
                <p>&nbsp;</p>
              </div>
            </div>
          </div>
          <p>&nbsp;</p>
        </div>
</div>
    </div>
    <p>&nbsp;</p>
</div>
	<!-- This clearing element should immediately follow the #mainContent div in order to force the #container div to contain all child floats --><br class="clearfloat" />
   <div id="footer">
    <p>BDL Capital Management - 2010</p>
  <!-- end #footer --></div>
<!-- end #container --></div>
<script type="text/javascript">
<!--
var TabbedPanels1 = new Spry.Widget.TabbedPanels("TabbedPanels1");
var TabbedPanels4 = new Spry.Widget.TabbedPanels("TabbedPanels4");
var CollapsiblePanel2 = new Spry.Widget.CollapsiblePanel("CollapsiblePanel2", {contentIsOpen:false});
var sprytextfield2 = new Spry.Widget.ValidationTextField("sprytextfield2", "date", {format:"yyyy-mm-dd", isRequired:false, validateOn:["blur"]});
var TabbedPanels2 = new Spry.Widget.TabbedPanels("TabbedPanels2");
var Accordion1 = new Spry.Widget.Accordion("Accordion1");
var Accordion2 = new Spry.Widget.Accordion("Accordion2");
var sprytextfield3 = new Spry.Widget.ValidationTextField("sprytextfield3", "email", {isRequired:false});
var sprytextfield4 = new Spry.Widget.ValidationTextField("sprytextfield4", "none", {hint:"+00.0.00.00.00.00", isRequired:false});
var sprytextfield5 = new Spry.Widget.ValidationTextField("sprytextfield5", "date", {format:"yyyy-mm-dd", validateOn:["blur"]});
var TabbedPanels3 = new Spry.Widget.TabbedPanels("TabbedPanels3");
var sprytextfield1 = new Spry.Widget.ValidationTextField("sprytextfield1", "date", {isRequired:false, format:"yyyy-mm-dd"});
var sprytextfield6 = new Spry.Widget.ValidationTextField("sprytextfield6");
//-->
</script>
</body>
</html>
<?php
mysql_free_result($Sectors);

mysql_free_result($Sector);

mysql_free_result($Discussions);

mysql_free_result($Meetins_Results);

mysql_free_result($Users);

mysql_free_result($Contacts);

mysql_free_result($Detail);

mysql_free_result($All_Users);

mysql_free_result($All_Users2);
?>
