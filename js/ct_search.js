// get search query
  	function gup( name ){
		name = name.replace(/[\[]/,"\\\[").replace(/[\]]/,"\\\]");  
		var regexS = "[\\?&]"+name+"=([^&#]*)";  
		var regex = new RegExp( regexS );  
		var results = regex.exec( window.location.href ); 
		if( results == null )    return "";  
			else    return results[1];
	}
		

    // Load the Search API
    google.load('search', '1',{language:'en'});

    // Set a callback to load the Custom Search Element when you page loads
    google.setOnLoadCallback(
      function(){
      
      	
        var customSearchControl = new google.search.CustomSearchControl('012870256574466173327:cdhmlazchn4');
        
        var drawOptions = new google.search.DrawOptions();
        drawOptions.setInput(document.getElementById('s'));
        customSearchControl.draw('results', drawOptions);
        

        // Use "mysite_" as a unique ID to override the default rendering.
        google.search.Csedr.addOverride("mysite_");
        
       	//var options= new google.search.DrawOptions();
      	//options.enableSearchboxOnly("google.html");

        // Draw the Custom Search Control in the div named "CSE"
        //customSearchControl.draw('cse');

        // Execute an initial search
		customSearchControl.execute('<?php print $_GET['s'];?>');
		customSearchControl.setResultSetSize(google.search.Search.FILTERED_CSE_RESULTSET);
		//	customSearchControl.setLinkTarget(google.search.Search.LINK_TARGET_SELF);
		//	customSearchControl.setResultSetSize(google.search.Search.SMALL_CSE_RESULTSET);
      },
    true);