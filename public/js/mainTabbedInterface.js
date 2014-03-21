
/* mainTabbedInterface.js
   
   This javascript creates new closable tabs with editable panels
*/


$(function() 
{
    var tabTemplate = "<li><a href='#{href}'>#{label}</a> <span class='ui-icon ui-icon-close' role='presentation'>Remove Tab</span></li>",
        tabCounter = 1,								//count number of tabs
        tabTrack = new Array();						//track filepath 
    var openedTabCtr = 1;							//numbers of tab opened on tabbed interface
    var idTrack = new Array();						//track opened tab ID
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
		tabTrack[openedTabCtr] = filePath;			//track opened file
		idTrack[openedTabCtr] = id;

		tabs.find( ".ui-tabs-nav" ).append( li );
		tabs.append( "<div id='" + id + "'><p>" + tabContentHtml + "</p></div>" );
		tabs.tabs( "refresh" );	
		tabs.tabs( "option", "active", -1 );		//A negative value selects panels going backward from the last panel

		document.getElementById(id).name = label;	//set the name of tabs
		var editor = ace.edit(id);   				//editor format
		editor.setTheme("ace/theme/eclipse");

		//set highlight format based on different files 
		//ace edior has lots of program language modes. The followings are just the most common ones 
		if(tabTitle.indexOf(".css") != -1)
		{
			editor.getSession().setMode("ace/mode/css");
		}
		else if(tabTitle.indexOf(".js") != -1)
		{
			editor.getSession().setMode("ace/mode/javascript");
		}
    	else if(tabTitle.indexOf(".html") != -1 || tabTitle.indexOf(".htm") != -1)
    	{
    		editor.getSession().setMode("ace/mode/html");
    	}
    	else if(tabTitle.indexOf(".java") != -1)
		{
			editor.getSession().setMode("ace/mode/java");
		}
		else if(tabTitle.indexOf(".json") != -1)
		{
			editor.getSession().setMode("ace/mode/json");
		}
		else if(tabTitle.indexOf(".tex") != -1)
		{
			editor.getSession().setMode("ace/mode/tex");
		}
		else if(tabTitle.indexOf(".xml") != -1)
		{
			editor.getSession().setMode("ace/mode/xml");
		}
		else 
		{
			editor.getSession().setMode("ace/mode/text");
		}	

		edited[openedTabCtr] = false;								
		editor.getSession().on('change', function(e){
    		edited[tabs.tabs("option","active")+1] = true;
		});

		tabCounter++;
		openedTabCtr++;
	}
    window.addTab = addTab;							//set addTab as a global function 
 
    // set saved file editing status to false 
    function setFileEdit()
    {
    	edited[tabs.tabs("option","active")+1] = false;
    }
    window.setFileEdit = setFileEdit;

    // get path of current opened tab
    function getPath()
    {
    	return tabTrack[tabs.tabs("option","active")+1];
    }
    window.getTabPath = getPath;

    // get content of current opened tab
    function getContent()
    {	
    	var editor = ace.edit(idTrack[tabs.tabs("option","active") + 1]);
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
		var rmID = tabs.tabs("option","active")+1;
		//update tabTrack/edited/idTrack array 
		for(rmID; rmID < tabTrack.length - 1; rmID ++)
		{
			tabTrack[rmID] = tabTrack[rmID+1];
			edited[rmID] = edited[rmID+1];
			idTrack[rmID] = idTrack[rmID+1];
		}

		//if the closed tab is the last one, then erase it. 
		if(rmID==tabTrack.length - 1)
		{
			tabTrack[rmID] = "";
		}
		tabs.tabs( "refresh" );
		openedTabCtr --;
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