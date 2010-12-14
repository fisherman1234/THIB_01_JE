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
$query_All_Users = "SELECT * FROM Users";
$All_Users = mysql_query($query_All_Users, $localhost) or die(mysql_error());
$row_All_Users = mysql_fetch_assoc($All_Users);
$totalRows_All_Users = mysql_num_rows($All_Users);
?>
<?php
// *** Validate request to login to this site.
if (!isset($_SESSION)) {
  session_start();
}

$loginFormAction = $_SERVER['PHP_SELF'];
if (isset($_GET['accesscheck'])) {
  $_SESSION['PrevUrl'] = $_GET['accesscheck'];
}

if (isset($_POST['User'])) {
  $loginUsername=$_POST['User'];
  $password=$_POST['User'];
  $MM_fldUserAuthorization = "Id";
  $MM_redirectLoginSuccess = "index.php";
  $MM_redirectLoginFailed = "log_in.php";
  $MM_redirecttoReferrer = true;
  mysql_select_db($database_localhost, $localhost);
  	
  $LoginRS__query=sprintf("SELECT Nom, Nom, Id FROM Users WHERE Nom=%s AND Nom=%s",
  GetSQLValueString($loginUsername, "text"), GetSQLValueString($password, "text")); 
   
  $LoginRS = mysql_query($LoginRS__query, $localhost) or die(mysql_error());
  $loginFoundUser = mysql_num_rows($LoginRS);
  if ($loginFoundUser) {
    
    $loginStrGroup  = mysql_result($LoginRS,0,'Id');
    
    //declare two session variables and assign them
    $_SESSION['MM_Username'] = $loginUsername;
    $_SESSION['MM_UserID'] = $loginStrGroup;	      

    if (isset($_SESSION['PrevUrl']) && true) {
      $MM_redirectLoginSuccess = $_SESSION['PrevUrl'];	
    }
    header("Location: " . $MM_redirectLoginSuccess );
  }
  else {
    header("Location: ". $MM_redirectLoginFailed );
  }
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Log in</title>
<link href="css/oneColElsCtrHdr.css" rel="stylesheet" type="text/css" />
</head>

<body class="oneColElsCtrHdr">

<div id="container">
  <div id="header">
    <h1>Log in</h1>
  <!-- end #header --></div>
  <div id="mainContent">
    <form id="form1" name="form1" method="POST" action="<?php echo $loginFormAction; ?>">
      <label>
        <div align="center">
          <p>&nbsp;</p>
          <p>User
            <select name="User" id="User">
              <?php
do {  
?>
              <option value="<?php echo $row_All_Users['Nom']?>"><?php echo $row_All_Users['Nom']?></option>
              <?php
} while ($row_All_Users = mysql_fetch_assoc($All_Users));
  $rows = mysql_num_rows($All_Users);
  if($rows > 0) {
      mysql_data_seek($All_Users, 0);
	  $row_All_Users = mysql_fetch_assoc($All_Users);
  }
?>
            </select>
            <label><br />
              <br />
              <input type="submit" name="Submit" id="Submit" value="Submit" />
            </label>
          </p>
</div>
      </label>
    </form>
    <h1>&nbsp;</h1>
	<!-- end #mainContent --></div>
  <div id="footer">
    <p>Footer</p>
  <!-- end #footer --></div>
<!-- end #container --></div>
</body>
</html>
<?php
mysql_free_result($All_Users);
?>
