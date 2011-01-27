
/*
* InstantSferyx for WebApps - immediate integration plugin for the any WebApp
* this is an easy to integrate wrapper for the Sferyx JSyndrome HTMLEditor Applet http://www.sferyx.com
*/

var SferyxEditor = function( instanceName, width, height, toolbarSet, value )
{
	this.InstanceName	= instanceName ;
	this.Width		= width			|| '100%' ;
	this.Height		= height		|| '400' ;
	this.ToolbarSet		= toolbarSet	|| '' ;
	this.Value		= value			|| '' ;
	
} 


SferyxEditor.prototype.ReplaceTextarea = function()
{

    var _info = navigator.userAgent;
    var _ie = (_info.indexOf("MSIE") > 0 && _info.indexOf("Win") > 0 && _info.indexOf("Windows 3.1") < 0);
    
  
     
    var appletJarName='editor/HTMLEditorAppletPro.jar';
     
	  
   			var fieldName=document.getElementById(this.InstanceName).name;
   			
   			var appletString=''+
   			'<applet code="sferyx.administration.editors.HTMLEditor" archive="'+appletJarName+'" style="width:'+this.Width+';height:'+this.Height+';" width="'+this.Width+'" height="'+this.Height+'" name="sfrx_htmleditor" id="'+this.InstanceName+'">'+
			'To start Sferyx HTML Editor applet Java Plug-in 1.4 is required. Get it here: http://java.sun.com/products/plugin/1.4/plugin-install.html'+
			'<PARAM NAME ="supressRemoteFileDialog" VALUE="false">'+
			'<PARAM NAME ="supressLocalFileDialog" VALUE="false">'+
			'<PARAM NAME ="initialURLEncodedContent" VALUE="'+escape(document.getElementById(this.InstanceName).value)+'">'+
		       //       '<PARAM NAME ="uploadContentAsMultipartFormData" VALUE="true">'+
                       //	'<PARAM NAME ="saveURL" VALUE="http://yourhost_here">'+
                       //	'<PARAM NAME ="generateUniqueImageFilenames" VALUE="true">'+ 
                       //	'<PARAM NAME ="useFixedFileNameNamingRule" VALUE="NewFile">'+ 
                       //       '<PARAM NAME = "uploadedObjectsTranslationPath" VALUE="http://yourhost_here/images">'+
                        '<PARAM NAME="useFlowToolbarLayout" VALUE="true">'+
                        '<PARAM name="mainMenuVisible"  value="false">'+
			'<PARAM name="statusbarVisible"  value="false">'+
			'<PARAM name="sourceEditorVisible"  value="false">'+
			'<PARAM name="previewVisible"  value="false">'+
			'<PARAM name="toolbarItemsToRemove"  value="'+this.ToolbarSet+'">'+
			'</applet>'; 
			
			
	if(_ie)
	{ 
	document.getElementById(this.InstanceName).outerHTML=appletString;
	}
	else
	{
			
			var myTextField = document.getElementById(this.InstanceName);
			var r = myTextField.ownerDocument.createRange();
			r.setStartBefore(myTextField);
			var df = r.createContextualFragment(appletString);
		 	myTextField.parentNode.replaceChild(df, myTextField);   
	}
		 
	
}  



SferyxEditor.prototype.ReplaceEditor = function()
{

	var htmlEditor = document.getElementById(this.InstanceName);
	var currentContent=htmlEditor.getContent();
	var fieldString='<input type="hidden" value="" id="'+this.InstanceName+'">';


var _info = navigator.userAgent;
var _ie = (_info.indexOf("MSIE") > 0 && _info.indexOf("Win") > 0 && _info.indexOf("Windows 3.1") < 0);
    
if(_ie)
	{ 
            document.getElementById(this.InstanceName).outerHTML=fieldString;
	}
	else
	{
	    var r = htmlEditor.ownerDocument.createRange();
	    r.setStartBefore(htmlEditor);
	    var df = r.createContextualFragment(fieldString);
	    htmlEditor.parentNode.replaceChild(df, htmlEditor);
	}

	var textField = document.getElementById(this.InstanceName);
	textField.value=currentContent;

}

