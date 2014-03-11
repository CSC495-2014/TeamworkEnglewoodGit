
/* mainTabbedInterface.js
   
   This javascript creates new closable tabs with editable panels
*/


$(function() 
{
    var tabTemplate = "<li><a href='#{href}'>#{label}</a> <span class='ui-icon ui-icon-close' role='presentation'>Remove Tab</span></li>",
        tabCounter = 1,								//count number of tabs
        tabTrack = new Array();
    var	tabs = $( "#tabs" ).tabs();
    var edited = new Array();					    //an array holds editing status of files 

	// actual addTab function: adds new tab by passing the filepath and content of files
	function addTab(filePath, tabContent)
	{
	    // If tab already exist in the list, return
		if(tabTrack.indexOf(filePath) != -1){		//if filePath is not found in tabTrack array, it will return -1
			return;
		}		
		//else - create a new tab
		var tabTitle = window.basename(filePath);
		var label = tabTitle || "Untitled",
			id = "tabs-" + tabCounter,
			li = $( tabTemplate.replace( /#\{href\}/g, "#" + id ).replace( /#\{label\}/g, label ) ),
			tabContentHtml = tabContent || "";
		tabTrack[tabCounter] = filePath;			//track opened file

		tabs.find( ".ui-tabs-nav" ).append( li );
		tabs.append( "<div id='" + id + "'><p>" + tabContentHtml + "</p></div>" );
		tabs.tabs( "refresh" );	
		tabs.tabs( "option", "active", -1 );		//A negative value selects panels going backward from the last panel.
		tabCounter++;
		document.getElementById(id).name = label;	//set the name of tabs
		var editor = ace.edit(id);   				//editor format
		edited[tabCounter] = false;								
		editor.getSession().on('change', function(e){
    		edited[tabs.tabs("option","active")+1] = true;
		});
	}
    window.addTab = addTab;							//set addTab as a global function 
 
    

    // get path of current opened tab
    function getPath()
    {
    	return tabTrack[tabs.tabs("option","active")+1];
    }
    window.getTabPath = getPath;

    // get content of current opened tab
    function getContent()
    {	
    	var editor = ace.edit("tabs-"+ (tabs.tabs("option","active") + 1));
    	return editor.getValue();
    }
    window.getTabContent = getContent;

    

	// close icon: removing the tab on click
	tabs.delegate( "span.ui-icon-close", "click", function() 
	{
		//To check if files have been changed
		if(edited[tabs.tabs("option","active")+1]){
			var confirmBtn = confirm("The file has changed. Are you sure you want to close?");
			if(confirmBtn!=true)
			{
				return;
			}
		}
		var panelId = $( this ).closest( "li" ).remove().attr( "aria-controls" );
		$( "#" + panelId ).remove();
		tabTrack[panelId.charAt(panelId.length-1)]='';		//remove file from tabTrack array
		tabs.tabs( "refresh" );
	});
	tabs.bind( "keyup", function( event ) 
	{
		if ( event.altKey && event.keyCode === $.ui.keyCode.BACKSPACE ) 
		{
			var panelId = tabs.find( ".ui-tabs-active" ).remove().attr( "aria-controls" );
			$( "#" + panelId ).remove();
			tabs.tabs( "refresh" );
		}
	});
	
});