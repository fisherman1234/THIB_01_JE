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
function search_results($text_string)
{
	  	$search_string = htmlentities(utf8_decode($_GET['Search']));
		$words = explode(' ', $search_string);
		$clean_text_string = strip_tags($text_string);
		foreach ( $words as $word )
			{
				$result = strpos(strtolower($clean_text_string), strtolower($word));
				if ($result <> 0)
				{
				break 1;
				}
			}
		return $result;
	
	}
	
function highlightWords($string, $words)
 {
	 $words = explode(' ', $words);
    foreach ( $words as $word )
    {
        $string = str_ireplace($word, '<span STYLE="background-color: rgb(255,255,0)">'.$word.'</span>', $string);
    }
    /*** return the highlighted string ***/
    return $string;
 }

function highlight($c,$q){ 
$q=explode(' ',str_replace(array('','\\','+','*','?','[','^',']','$','(',')','{','}','=','!','<','>','|',':','#','-','_'),'',$q));
for($i=0;$i<sizeOf($q);$i++) 
    $c=preg_replace("/($q[$i])(?![^<]*>)/i","<span class=\"highlight\">\${1}</span>",$c);
return $c;}

function excerpt($text, $radius = 100, $ending = "...") { 

		$text=strip_tags($text);
		$phrase=htmlentities(utf8_decode($_GET['Search']));
         $phraseLen = strlen($phrase); 
       if ($radius < $phraseLen) { 
             $radius = $phraseLen; 
         } 

         $phrases = explode (' ',$phrase);

         foreach ($phrases as $phrase) {
                 $pos = strpos(strtolower($text), strtolower($phrase)); 
                 if ($pos > -1) break;
         }

         $startPos = 0; 
         if ($pos > $radius) { 
             $startPos = $pos - $radius; 
         } 

         $textLen = strlen($text); 

         $endPos = $pos + $phraseLen + $radius; 
         if ($endPos >= $textLen) { 
             $endPos = $textLen; 
         } 

         $excerpt = substr($text, $startPos, $endPos - $startPos); 
         if ($startPos != 0) { 
             $excerpt = substr_replace($excerpt, $ending, 0, $phraseLen); 
         } 

         if ($endPos != $textLen) { 
             $excerpt = substr_replace($excerpt, $ending, -$phraseLen); 
         } 
		
        return highlightWords($excerpt,$phrase); 
		
   }
?>
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

$colname_Stock_Results = "-1";
if (isset($_GET['Search'])) {
  $colname_Stock_Results = $_GET['Search'];
}
mysql_select_db($database_localhost, $localhost);
$query_Stock_Results = sprintf("SELECT * FROM Stocks WHERE MATCH (Stock_Name,Environment,Business_Description,Competition,Management,Investment_Case,Investment_Risks_Macro,Investment_Risks_Micro) AGAINST (%s)", GetSQLValueString($colname_Stock_Results, "text"));
$Stock_Results = mysql_query($query_Stock_Results, $localhost) or die(mysql_error());
$row_Stock_Results = mysql_fetch_assoc($Stock_Results);
$totalRows_Stock_Results = mysql_num_rows($Stock_Results);

$colname_Meetings_Results = "-1";
if (isset($_GET['Search'])) {
  $colname_Meetings_Results = htmlentities($_GET['Search']);
}
mysql_select_db($database_localhost, $localhost);
$query_Meetings_Results = sprintf("SELECT * FROM Meetings_Results, Stocks WHERE MATCH (Meetings_Results.Meeting_Contact, Meetings_Results.Meeting_Notes, Meetings_Results.Meeting_Conclusions) AGAINST (%s) AND Stocks.Stock_ID=Meetings_Results.Stock_ID", GetSQLValueString($colname_Meetings_Results, "text"));
$Meetings_Results = mysql_query($query_Meetings_Results, $localhost) or die(mysql_error());
$row_Meetings_Results = mysql_fetch_assoc($Meetings_Results);
$totalRows_Meetings_Results = mysql_num_rows($Meetings_Results);

$colname_Details_Results = "-1";
if (isset($_GET['Search'])) {
  $colname_Details_Results = htmlentities($_GET['Search']);
}
mysql_select_db($database_localhost, $localhost);
$query_Details_Results = sprintf("SELECT * FROM Details, Sectors WHERE MATCH (Details.Sector_Analysis_Title, Details.Sector_Analysis_Text) AGAINST (%s) AND Details.Sector_ID=Sectors.Sector_ID", GetSQLValueString($colname_Details_Results, "text"));
$Details_Results = mysql_query($query_Details_Results, $localhost) or die(mysql_error());
$row_Details_Results = mysql_fetch_assoc($Details_Results);
$totalRows_Details_Results = mysql_num_rows($Details_Results);

$colname_Contact_Result = "-1";
if (isset($_GET['Search'])) {
  $colname_Contact_Result = htmlentities($_GET['Search']);
}
mysql_select_db($database_localhost, $localhost);
$query_Contact_Result = sprintf("SELECT * FROM Contacts, Stocks WHERE MATCH (Contacts.Job_Title, Contacts.Name, Contacts.Title, Contacts.Email, Contacts.Telephone) AGAINST (%s) AND Contacts.Stock_ID=Stocks.Stock_ID", GetSQLValueString($colname_Contact_Result, "text"));
$Contact_Result = mysql_query($query_Contact_Result, $localhost) or die(mysql_error());
$row_Contact_Result = mysql_fetch_assoc($Contact_Result);
$totalRows_Contact_Result = mysql_num_rows($Contact_Result);

$colname_Discussions_Results = "-1";
if (isset($_GET['Search'])) {
  $colname_Discussions_Results = htmlentities($_GET['Search']);
}
mysql_select_db($database_localhost, $localhost);
$query_Discussions_Results = sprintf("SELECT * FROM Stocks, BDL_Discussions WHERE MATCH (BDL_Discussions.View_BDL, BDL_Discussions.Position_BDL) AGAINST (%s) AND BDL_Discussions.Stock_ID=Stocks.Stock_ID", GetSQLValueString($colname_Discussions_Results, "text"));
$Discussions_Results = mysql_query($query_Discussions_Results, $localhost) or die(mysql_error());
$row_Discussions_Results = mysql_fetch_assoc($Discussions_Results);
$totalRows_Discussions_Results = mysql_num_rows($Discussions_Results);

$currentPage = $_SERVER["PHP_SELF"];

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
<title>Search</title>
<link href="css/oneColElsCtrHdr.css" rel="stylesheet" type="text/css" />
</head>

<body class="oneColElsCtrHdr">

<div id="container">
  <div id="header">
    <h1>Search results</h1>
    <p><a href="index.php">Home</a></p>
  <!-- end #header --></div>
  <div id="mainContent">
    <form id="form1" name="form1" method="get" action="Search.php">
      <label>Search
        <input name="Search" type="text" id="Search" value="<?php echo $_GET['Search']; ?>" size="50" />
      </label>
      <label>
        <input type="submit" name="Submit" id="Submit" value="Submit" />
      </label>
    </form>
    <hr />
	<?php if ($totalRows_Stock_Results > 0) { // Show if recordset not empty ?> 
	
    <p>Stocks    </p>
    <table width="100%" border="1" align="center">
      <?php do { ?>
        <tr>
          <td><p><a href="Stock.php?Stock_ID=<?php echo $row_Stock_Results['Stock_ID']; ?>"> <?php echo $row_Stock_Results['Stock_Name']; ?>&nbsp; </a></p>
            <table width="100%" border="1">
            
            	<?php if (search_results($row_Stock_Results['Environment']) <> 0) { // Show if recordset not empty ?> 

            
              <tr>
                <th width="20%" scope="row">Environment</th>
                <td><a href="Stock.php?Stock_ID=<?php echo  excerpt($row_Stock_Results['Stock_ID']); ?>&tab1=1"><?php echo  excerpt($row_Stock_Results['Environment']); ?></a></td>
              </tr>
                    <?php } // Show if recordset not empty ?>
     		
			<?php if (search_results($row_Stock_Results['Business_Description']) <> 0) { // Show if recordset not empty ?> 


              <tr>
                <th width="20%" scope="row">Business description</th>
                <td><a href="Stock.php?Stock_ID=<?php echo  excerpt($row_Stock_Results['Stock_ID']); ?>&tab1=1&tab2=1"><?php echo excerpt($row_Stock_Results['Business_Description']); ?></a></td>
              </tr>
           
           <?php } // Show if recordset not empty ?>
           			<?php if (search_results($row_Stock_Results['Competition']) <> 0) { // Show if recordset not empty ?> 

              <tr>
                <th width="20%" scope="row">Competition</th>
                <td><a href="Stock.php?Stock_ID=<?php echo  excerpt($row_Stock_Results['Stock_ID']); ?>&tab1=1&tab2=2"><?php echo excerpt($row_Stock_Results['Competition']); ?></a></td>
              </tr>
              
            <?php } // Show if recordset not empty ?>
           			<?php if (search_results($row_Stock_Results['Management']) <> 0) { // Show if recordset not empty ?> 

           
              <tr>
                <th width="20%" scope="row">Management</th>
                <td><a href="Stock.php?Stock_ID=<?php echo  excerpt($row_Stock_Results['Stock_ID']); ?>&tab1=1&tab2=3"><?php echo excerpt($row_Stock_Results['Management']); ?></a></td>
              </tr>
            
            <?php } // Show if recordset not empty ?>
           			<?php if (search_results($row_Stock_Results['Investment_Case']) <> 0) { // Show if recordset not empty ?>   
            
              <tr>
                <th width="20%" scope="row">Investment Case</th>
                <td><a href="Stock.php?Stock_ID=<?php echo  excerpt($row_Stock_Results['Stock_ID']); ?>&tab1=2"><?php echo excerpt($row_Stock_Results['Investment_Case']); ?></a></td>
              </tr>
              
              <?php } // Show if recordset not empty ?>
           			<?php if (search_results($row_Stock_Results['Investment_Risks_Macro']) <> 0) { // Show if recordset not empty ?>   
            
              
              <tr>
                <th width="20%" scope="row">Risks macro</th>
                <td><a href="Stock.php?Stock_ID=<?php echo  excerpt($row_Stock_Results['Stock_ID']); ?>&tab1=2&tab3=2"><?php echo excerpt($row_Stock_Results['Investment_Risks_Macro']); ?></a></td>
              </tr>
              
               <?php } // Show if recordset not empty ?>
           			<?php if (search_results($row_Stock_Results['Investment_Risks_Micro']) <> 0) { // Show if recordset not empty ?>   
              
              <tr>
                <th width="20%" scope="row">Risks micro</th>
                <td><a href="Stock.php?Stock_ID=<?php echo  excerpt($row_Stock_Results['Stock_ID']); ?>&tab1=2&tab3=2"><?php echo excerpt($row_Stock_Results['Investment_Risks_Micro']); ?></a></td>
              </tr>
               
                <?php } // Show if recordset not empty ?>

            </table>
            </td>
        </tr>
        <?php } while ($row_Stock_Results = mysql_fetch_assoc($Stock_Results)); ?>
    </table>
      <?php } // Show if recordset not empty ?>

	<?php if ($totalRows_Meetings_Results > 0) { // Show if recordset not empty ?> 
    <p>Meetings/Results    </p>
    <table width="100%" border="1" align="center">
      <tr>
        <td>Meeting type</td>
        <td width="30%">Stock</td>
        <td width="50%">Abstract</td>
      </tr>
      <?php do { ?>
        <tr>
          <td> <?php echo $row_Meetings_Results['Meeting_Type']; ?></td>
          <td width="30%"><a href="Stock.php?Stock_ID=<?php echo $row_Meetings_Results['Stock_ID']; ?>"><?php echo $row_Meetings_Results['Stock_Name']; ?></a></td>
          <td width="50%"><a href="EditMeeting.php?Meeting_ID=<?php echo $row_Meetings_Results['Meeting_ID']; ?>"><?php echo excerpt($row_Meetings_Results['Meeting_Notes']); ?> // <?php echo excerpt($row_Meetings_Results['Meeting_Conclusions']); ?></a></td>
        </tr>
        <?php } while ($row_Meetings_Results = mysql_fetch_assoc($Meetings_Results)); ?>
    </table>
          <?php } // Show if recordset not empty ?>

    <?php if ($totalRows_Details_Results > 0) { // Show if recordset not empty ?> 
    <p>Analysis    </p>
    <table width="100%" border="1" align="center">
      <tr>
        <td>Title</td>
        <td width="15%">Date</td>
        <td width="50%">Abstract</td>
      </tr>
      <?php do { ?>
        <tr>
          <td><a href="Edit_Detail.php?Detail_ID=<?php echo $row_Details_Results['Detail_ID']; ?>"> <?php echo $row_Details_Results['Sector_Analysis_Title']; ?>&nbsp; </a></td>
          <td width="15%"><?php echo changedateusfr($row_Details_Results['Last_Entry_Date']); ?>&nbsp; </td>
          <td width="50%"><?php echo excerpt($row_Details_Results['Sector_Analysis_Text']); ?></td>
        </tr>
        <?php } while ($row_Details_Results = mysql_fetch_assoc($Details_Results)); ?>
    </table>
              <?php } // Show if recordset not empty ?>

    <?php if ($totalRows_Contact_Result > 0) { // Show if recordset not empty ?> 
    <p>Contacts    </p>
    <table width="100%" border="1" align="center">
      <tr>
        <td width="43%">Contact</td>
        <td width="57%">Stock</td>
      </tr>
      <?php do { ?>
        <tr>
          <td><?php echo $row_Contact_Result['Name']; ?>&nbsp; </td>
          <td><a href="Stock.php?Stock_ID=<?php echo $row_Contact_Result['Stock_ID']; ?>"> <?php echo $row_Contact_Result['Stock_Name']; ?>&nbsp; </a></td>
        </tr>
        <?php } while ($row_Contact_Result = mysql_fetch_assoc($Contact_Result)); ?>
    </table>
                  <?php } // Show if recordset not empty ?>

   	
    <?php if ($totalRows_Discussions_Results > 0) { // Show if recordset not empty ?> 
    <p>BDL Discussions&nbsp;    </p>
    <table width="100%" border="1" align="center">
      <tr>
        <td>Date</td>
        <td width="30%">Stock</td>
        <td width="50%">Abstract</td>
      </tr>
      <?php do { ?>
        <tr>
          <td><?php echo changedateusfr($row_Discussions_Results['Discussion_Date']); ?></td>
          <td width="30%"><a href="Stock.php?Stock_ID=<?php echo $row_Discussions_Results['Stock_ID']; ?>"> <?php echo $row_Discussions_Results['Stock_Name']; ?></a> </td>
          <td width="50%"><a href="EditDiscussion.php?Discussion_ID=<?php echo $row_Discussions_Results['Discussion_ID']; ?>"> <?php echo excerpt($row_Discussions_Results['View_BDL']); ?></a></td>
        </tr>
        <?php } while ($row_Discussions_Results = mysql_fetch_assoc($Discussions_Results)); ?>
    </table>
                      <?php } // Show if recordset not empty ?>

    <p>&nbsp;</p>
  </div>
<div id="footer">
     <p style="font-size:12px;">BDL Capital Management - 2010</p>
  <!-- end #footer --></div>
<!-- end #container --></div>
</body>
</html>
<?php
mysql_free_result($Stock_Results);

mysql_free_result($Meetings_Results);

mysql_free_result($Details_Results);

mysql_free_result($Contact_Result);

mysql_free_result($Discussions_Results);
?>
