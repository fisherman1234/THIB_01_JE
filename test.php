<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Sector </title>
<!--[if IE]>
<style type="text/css"> 
/* place css fixes for all versions of IE in this conditional comment */
.twoColElsLtHdr #sidebar1 { padding-top: 30px; }
.twoColElsLtHdr #mainContent { zoom: 1; padding-top: 15px; }
/* the above proprietary zoom property gives IE the hasLayout it needs to avoid several bugs */
</style>
<![endif]-->

<script type="text/javascript" src="editor/editor.js"></script>

<script>
 
var sferyxEditor12=new SferyxEditor("Sector_Analysis_Text", "600", "400");

function launchEditor() { 
							sferyxEditor12.ReplaceTextarea();}

function replaceEditor() { sferyxEditor12.ReplaceEditor();}
					</script>

</script>
</head>

<body  onload="launchEditor()">


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
                    <td nowrap="nowrap" align="right" valign="top"><p>Sector analysis content</p>
                      <p>
                        <label>
                          <input type="button" name="z" id="z" value="Button" onclick="launchEditor()" />
                        </label>
                        <label>
                          <input type="button" name="z2" id="z2" value="Replace" onclick="replaceEditor()" />
                        </label>
                      </p></td>
                    <td><textarea id="Sector_Analysis_Text">test</textarea></td>
                    
    
                  </tr>
                  <tr valign="baseline">
                    <td nowrap="nowrap" align="right">&nbsp;</td>
                    <td><input type="submit" value="Insert record" /></td>
                  </tr>
                </table>
                <input type="hidden" name="Sector_ID" value="<?php echo $row_Sector['Sector_ID']; ?>" />
                <input type="hidden" name="MM_insert" value="form1" />
              </form>
              
</body>
</html>
