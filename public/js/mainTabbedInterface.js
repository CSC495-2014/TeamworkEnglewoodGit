
/* mainTabbedInterface.js
   
   This javascript creates new closable tabs with editable panels
*/


$(function() 
{
    var tabTemplate = "<li><a href='#{href}'>#{label}</a> <span class='ui-icon ui-icon-close' role='presentation'>Remove Tab</span></li>",
        tabCounter = 2;
    tabs = $( "#tabs" ).tabs();

	// actual addTab function: adds new tab by passing the name and content of files
	function addTab(tabTitle, tabContent)
	{
		var label = tabTitle || "Untitled",
			id = "tabs-" + tabCounter,
			li = $( tabTemplate.replace( /#\{href\}/g, "#" + id ).replace( /#\{label\}/g, label ) ),
			tabContentHtml = tabContent || "";

		tabs.find( ".ui-tabs-nav" ).append( li );
		tabs.append( "<div id='" + id + "'><p>" + tabContentHtml + "</p></div>" );
		tabs.tabs( "refresh" );
		tabs.tabs( "option", "active", -1 );
		tabCounter++;
		var editor = ace.edit(id);   //editor format
		document.getElementById(id).name = label;	//set the name of tabs
	}

    window.addTab = addTab;

	// close icon: removing the tab on click
	tabs.delegate( "span.ui-icon-close", "click", function() 
	{
		var panelId = $( this ).closest( "li" ).remove().attr( "aria-controls" );
		$( "#" + panelId ).remove();
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