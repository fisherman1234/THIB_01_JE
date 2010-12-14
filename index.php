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
    <h2>Find a stock </h2>
    <form   method="post" actions="">

	<p class="ui-widget"><label for="stock">Stock : </label>
    <input type="text" id="stock"  name="stock" />
    <input type="hidden" id="stock_id" name="stock_id" /> 
    
	Click on the suggested name to access its form.</form>
    
    
    
    
<h2>Find a sector  </h2>
<form id="form1" name="form1" method="post" action="">
<p class="ui-widget"><label for="stock">Sector : </label>
    <input type="text" id="sector"  name="sector" />
    <input type="hidden" id="sector_id" name="sector_id" /> 
    
	Click on the suggested name to access its form.
</form>
<h2>&nbsp;</h2>
<h4><a href="Administration.php">Administration</a></h4>
<p>&nbsp;</p>
	<!-- end #mainContent --></div>
  <div id="footer">
    <p>Footer</p>
  <!-- end #footer --></div>
<!-- end #container --></div>
</body>
</html>