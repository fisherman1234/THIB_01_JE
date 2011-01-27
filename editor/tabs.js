    var tabLinks = new Array();
    var contentDivs = new Array();
    var editorReferences=new Array();

        var sferyxEditor1=new SferyxEditor("about_area");
	var sferyxEditor2=new SferyxEditor("advantages_area");
	var sferyxEditor3=new SferyxEditor("usage_area");

	editorReferences["about"]=sferyxEditor1;
	editorReferences["advantages"]=sferyxEditor2;
	editorReferences["usage"]=sferyxEditor3;


	var lastSelected;


    function init()
    {

      var tabListItems = document.getElementById('tabs').childNodes;

      for ( var i = 0; i < tabListItems.length; i++ )
      {
        if ( tabListItems[i].nodeName == "LI" )
        {
          var tabLink = getFirstChildWithTagName( tabListItems[i], 'A' );
          var id = getHash( tabLink.getAttribute('href') );
          tabLinks[id] = tabLink;
          contentDivs[id] = document.getElementById( id );
        }
      }

   
      var i = 0;

      for ( var id in tabLinks )
      {
        tabLinks[id].onclick = showTab;
        tabLinks[id].onfocus = function() { this.blur(); };
        if ( i == 0 ) tabLinks[id].className = 'selected';
        i++;
      }

     
      var i = 0;

      for ( var id in contentDivs )
      {
        if ( i != 0 )
        {
        	
        	contentDivs[id].className = 'tabContent hide';

        }
        else
        {
            editorReferences[id].ReplaceTextarea();
            lastSelected=id;
        }

        i++;
      }
    }




    function showTab()
    {
      var selectedId = getHash( this.getAttribute('href') );

     
      for ( var id in contentDivs )
      {
        if ( id == selectedId )
        {

         	  editorReferences[id].ReplaceTextarea();
         	  
          	  tabLinks[id].className = 'selected';
 		  contentDivs[id].className = 'tabContent';
        }
        else
        {

       				 if(id==lastSelected)
					  {
					 		editorReferences[id].ReplaceEditor();
					   }


			  tabLinks[id].className = '';
			  contentDivs[id].className = 'tabContent hide';


        }
      }


		lastSelected=selectedId;

     
      return false;
    }




    function getFirstChildWithTagName( element, tagName ) {
      for ( var i = 0; i < element.childNodes.length; i++ ) {
        if ( element.childNodes[i].nodeName == tagName ) return element.childNodes[i];
      }
    }

    function getHash( url ) {
      var hashPos = url.lastIndexOf ( '#' );
      return url.substring( hashPos + 1 );
    }
