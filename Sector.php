<?php require_once('Connections/localhost.php'); ?>
<?php require_once('Connections/localhost.php'); ?>
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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT INTO Details (Sector_ID, Last_Entry_Date, Sector_Analysis_Title, Sector_Analysis_Text) VALUES (%s, %s, %s, %s)",
                       GetSQLValueString($_POST['Sector_ID'], "int"),
                       GetSQLValueString($_POST['Last_Entry_Date'], "date"),
                       GetSQLValueString($_POST['Sector_Analysis_Title'], "text"),
                       GetSQLValueString($_POST['Sector_Analysis_Text'], "text"));

  mysql_select_db($database_localhost, $localhost);
  $Result1 = mysql_query($insertSQL, $localhost) or die(mysql_error());
}

$colname_Sector = "-1";
if (isset($_GET['Sector_ID'])) {
  $colname_Sector = $_GET['Sector_ID'];
}
mysql_select_db($database_localhost, $localhost);
$query_Sector = sprintf("SELECT * FROM Sectors WHERE Sector_ID = %s", GetSQLValueString($colname_Sector, "int"));
$Sector = mysql_query($query_Sector, $localhost) or die(mysql_error());
$row_Sector = mysql_fetch_assoc($Sector);
$totalRows_Sector = mysql_num_rows($Sector);

$colname_Available_Stocks = "-1";
if (isset($_GET['Sector_ID'])) {
  $colname_Available_Stocks = $_GET['Sector_ID'];
}
mysql_select_db($database_localhost, $localhost);
$query_Available_Stocks = sprintf("SELECT * FROM Stocks WHERE Stocks.Sector_ID = %s ORDER BY Stocks.Stock_Name ASC", GetSQLValueString($colname_Available_Stocks, "int"));
$Available_Stocks = mysql_query($query_Available_Stocks, $localhost) or die(mysql_error());
$row_Available_Stocks = mysql_fetch_assoc($Available_Stocks);
$totalRows_Available_Stocks = mysql_num_rows($Available_Stocks);

$colname_Details = "-1";
if (isset($_GET['Sector_ID'])) {
  $colname_Details = $_GET['Sector_ID'];
}
mysql_select_db($database_localhost, $localhost);
$query_Details = sprintf("SELECT * FROM Details WHERE Sector_ID = %s", GetSQLValueString($colname_Details, "int"));
$Details = mysql_query($query_Details, $localhost) or die(mysql_error());
$row_Details = mysql_fetch_assoc($Details);
$totalRows_Details = mysql_num_rows($Details);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Sector : <?php echo $row_Sector['Sector_Name']; ?></title>
<!--[if IE]>
<style type="text/css"> 
/* place css fixes for all versions of IE in this conditional comment */
.twoColElsLtHdr #sidebar1 { padding-top: 30px; }
.twoColElsLtHdr #mainContent { zoom: 1; padding-top: 15px; }
/* the above proprietary zoom property gives IE the hasLayout it needs to avoid several bugs */
</style>
<![endif]-->
<link href="css/main.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/jquery-1.4.4.min.js"></script>
<script type="text/javascript" src="js/jquery-ui-1.8.7.custom.min.js"></script>
<link type="text/css" href="css/smoothness/jquery-ui-1.8.7.custom.css" rel="stylesheet" />	
<script src="SpryAssets/SpryTabbedPanels.js" type="text/javascript"></script>
<script src="SpryAssets/SpryAccordion.js" type="text/javascript"></script>
<script src="SpryAssets/SpryValidationTextField.js" type="text/javascript"></script>
<script type="text/javascript" src="js/tiny_mce/tiny_mce.js"></script>
<script type="text/javascript">
tinyMCE.init({

		// General options

		mode : "textareas",

		theme : "advanced",

		plugins : "pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,wordcount,advlist,autosave",



		// Theme options

		theme_advanced_buttons1 : "save,newdocument,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,styleselect,formatselect,fontselect,fontsizeselect",

		theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,help,code,|,insertdate,inserttime,preview,|,forecolor,backcolor",

		theme_advanced_buttons3 : "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,emotions,iespell,media,advhr,|,print,|,ltr,rtl,|,fullscreen",

		theme_advanced_buttons4 : "insertlayer,moveforward,movebackward,absolute,|,styleprops,|,cite,abbr,acronym,del,ins,attribs,|,visualchars,nonbreaking,template,pagebreak,restoredraft",

		theme_advanced_toolbar_location : "top",

		theme_advanced_toolbar_align : "left",

		theme_advanced_statusbar_location : "bottom",

		theme_advanced_resizing : true,



		// Example content CSS (should be your site CSS)

		content_css : "css/content.css",



		// Drop lists for link/image/media/template dialogs

		template_external_list_url : "lists/template_list.js",

		external_link_list_url : "lists/link_list.js",

		external_image_list_url : "lists/image_list.js",

		media_external_list_url : "lists/media_list.js",



		// Style formats

		style_formats : [

			{title : 'Bold text', inline : 'b'},

			{title : 'Red text', inline : 'span', styles : {color : '#ff0000'}},

			{title : 'Red header', block : 'h1', styles : {color : '#ff0000'}},

			{title : 'Example 1', inline : 'span', classes : 'example1'},

			{title : 'Example 2', inline : 'span', classes : 'example2'},

			{title : 'Table styles'},

			{title : 'Table row 1', selector : 'tr', classes : 'tablerow1'}

		],



		// Replace values for the template plugin

		template_replace_values : {

			username : "Some User",

			staffid : "991234"

		}

	});


</script>


<script>
$(function() {
		$( ".datepicker" ).datepicker({ dateFormat: 'yy-mm-dd' , showButtonPanel: true });
	});
function MM_openBrWindow(theURL,winName,features) { //v2.0
  window.open(theURL,winName,features);
}
</script>
<link href="SpryAssets/SpryTabbedPanels.css" rel="stylesheet" type="text/css" />
<link href="SpryAssets/SpryAccordion.css" rel="stylesheet" type="text/css" />
<link href="SpryAssets/SpryValidationTextField.css" rel="stylesheet" type="text/css" />
</head>

<body class="twoColElsLtHdr">

<div id="container">
  <div id="header">
    <h1>Sector : <?php echo $row_Sector['Sector_Name']; ?></h1>
    <p><a onclick="history.go(-1);return true;"><u>Back</u></a> <a href="index.php">Home</a></p>
  <!-- end #header --></div>
  <div id="mainContent-full"> 
  <div id="TabbedPanels1" class="TabbedPanels">
    <ul class="TabbedPanelsTabGroup">
      <li class="TabbedPanelsTab" tabindex="0">Stocks</li>
      <li class="TabbedPanelsTab" tabindex="0">Details</li>
    </ul>
    <div class="TabbedPanelsContentGroup">
      <div class="TabbedPanelsContent">
        <table width="100%" border="1" align="center">
          <tr>
            <td width="25%">Stock</td>
            <td width="24%">In charge</td>
            <td width="27%">In portfolio ?</td>
            <td width="24%">Rating</td>
          </tr>
          <?php do { ?>
            <tr>
              <td><a href="Stock.php?Stock_ID=<?php echo $row_Available_Stocks['Stock_ID']; ?>"> <?php echo $row_Available_Stocks['Stock_Name']; ?>&nbsp; </a></td>
              <td><?php echo $row_Available_Stocks['Initiales']; ?>&nbsp; </td>
              <td><?php echo $row_Available_Stocks['Is_In_Portfolio']; ?>&nbsp; </td>
              <td><?php echo $row_Available_Stocks['Rating']; ?>&nbsp; </td>
            </tr>
            <?php } while ($row_Available_Stocks = mysql_fetch_assoc($Available_Stocks)); ?>
        </table>
        <br />
      </div>
      <div class="TabbedPanelsContent">
        <div id="Accordion1" class="Accordion" tabindex="0">
          <div class="AccordionPanel">
            <div class="AccordionPanelTab">All details</div>
            <div class="AccordionPanelContent">
              <table width="100%" border="1" align="center">
                <tr>
                  <td width="20%">Date</td>
                  <td width="35%">Sector analysis title</td>
                  <td width="45%">Sector analysis</td>
                </tr>
                <?php do { ?>
                  <tr>
                    <td><?php echo $row_Details['Last_Entry_Date']; ?>&nbsp;</td>
                    <td><a href="#" onclick="MM_openBrWindow('Edit_Detail.php?Detail_ID=<?php echo $row_Details['Detail_ID']; ?>','','scrollbars=yes,resizable=yes,width=800,height=500')"> <?php echo $row_Details['Sector_Analysis_Title']; ?>&nbsp; </a></td>
                    <td><?php echo $row_Details['Sector_Analysis_Text']; ?>&nbsp; </td>
                  </tr>
                  <?php } while ($row_Details = mysql_fetch_assoc($Details)); ?>
              </table>
            </div>
          </div>
          <div class="AccordionPanel">
            <div class="AccordionPanelTab">Add new</div>
            <div class="AccordionPanelContent">
              <form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1">
                <table width="100%" align="center">
                  <tr valign="baseline">
                    <td width="29%" align="right" nowrap="nowrap">Date</td>
                    <td width="71%"><span id="sprytextfield1">
                      <input type="text" name="Last_Entry_Date" class="datepicker" value="" size="32" />
                      <span class="textfieldInvalidFormatMsg">Invalid format. Should be yyyy-mm-dd</span></span></td>
                  </tr>
                  <tr valign="baseline">
                    <td nowrap="nowrap" align="right">Sector analysis title</td>
                    <td><input type="text" name="Sector_Analysis_Title" value="" size="80" /></td>
                  </tr>
                  <tr valign="baseline">
                    <td nowrap="nowrap" align="right" valign="top">Sector analysis content</td>
                    <td><textarea name="Sector_Analysis_Text" cols="80" rows="10"></textarea></td>
                  </tr>
                  <tr valign="baseline">
                    <td nowrap="nowrap" align="right">&nbsp;</td>
                    <td><input type="submit" value="Insert record" /></td>
                  </tr>
                </table>
                <input type="hidden" name="Sector_ID" value="<?php echo $row_Sector['Sector_ID']; ?>" />
                <input type="hidden" name="MM_insert" value="form1" />
              </form>
              <p>&nbsp;</p>
            </div>
          </div>
        </div>
      
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
var Accordion1 = new Spry.Widget.Accordion("Accordion1");
var sprytextfield1 = new Spry.Widget.ValidationTextField("sprytextfield1", "date", {isRequired:false, format:"yyyy-mm-dd", hint:"yyyy-mm-dd", validateOn:["blur"], useCharacterMasking:true});
//-->
</script>
</body>
</html>
<?php
mysql_free_result($Sector);


mysql_free_result($Available_Stocks);

mysql_free_result($Details);
?>
