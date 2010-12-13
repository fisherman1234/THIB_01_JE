<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<!-- TemplateBeginEditable name="doctitle" -->
<title>Stock</title>
<!-- TemplateEndEditable -->
<!--[if IE]>
<style type="text/css"> 
/* place css fixes for all versions of IE in this conditional comment */
.twoColElsLtHdr #sidebar1 { padding-top: 30px; }
.twoColElsLtHdr #mainContent { zoom: 1; padding-top: 15px; }
/* the above proprietary zoom property gives IE the hasLayout it needs to avoid several bugs */
</style>
<![endif]-->
<link href="file:///Macintosh HD/Applications/MAMP/css/main.css" rel="stylesheet" type="text/css" /><
<link type="text/css" href="file:///Macintosh HD/Applications/MAMP/css/smoothness/jquery-ui-1.8.7.custom.css" rel="stylesheet" />	
<script type="text/javascript" src="file:///Macintosh HD/Applications/MAMP/js/jquery-1.4.4.min.js"></script>
<script type="text/javascript" src="file:///Macintosh HD/Applications/MAMP/js/jquery-ui-1.8.7.custom.min.js"></script>
<script> 
$(function() {
		$( ".datepicker" ).datepicker({ dateFormat: 'yy-mm-dd' , showButtonPanel: true });
	});
	</script>
<!-- TemplateBeginEditable name="head" -->
<!-- TemplateEndEditable -->
</head>

<body class="twoColElsLtHdr">

<div id="container">
  <div id="header">
    <h1>Stock : <?php echo $row_Sector['Stock_Name']; ?> - Sector : <?php echo $row_Sector['Sector_Name']; ?></h1>
  <!-- end #header --></div>
  <div id="mainContent-full"></div>
  
  <!-- This clearing element should immediately follow the #mainContent div in order to force the #container div to contain all child floats --><br class="clearfloat" />
   <div id="footer">
    <p>BDL Capital Management - 2010</p>
  <!-- end #footer --></div>
<!-- end #container --></div>

</body>
</html>