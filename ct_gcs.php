<?php 
/*
Plugin Name: CTGoogle Search
Plugin URI: http://www.muneef.in
Description: Google Search Custom
Author: Muneef Hameed
Version: 0.01
Author URI: http://www.muneef.in
*/

class CTGoogleSearch {

    /**
    * @constructor
    */
    function CTGoogleSearch_Plugin() {
        $this->set_hooks();
    }

    /**
     * Set actions and filters
     * @return void
     */
    function set_hooks() {
        add_action( 'parse_request', array( $this, 'parse_request' ) );
        add_action('template_redirect', array('CTGoogleSearch', 'template_redirect'), 1);
    }

    /**
     * A sample action
     * @return WP_Query
     */
    function parse_request( $wp ) {
        return $wp;
    }
    
    public static function template_redirect()
	{
		// not a search page; don't do anything and return
		if ( stripos($_SERVER['REQUEST_URI'], '/?s=') === FALSE && stripos($_SERVER['REQUEST_URI'], '/search/') === FALSE)
		{
			return;
		}
		
		wp_register_style('CTGoogle_Style', WP_PLUGIN_URL . '/ct_gcs/css/ctsearch.css');
    	wp_enqueue_style('CTGoogle_Style');
    	
    	wp_register_script('CTGoogle_API','https://www.google.com/jsapi');
    	wp_register_script('CTGoogle_Script', WP_PLUGIN_URL . '/ct_gcs/js/ct_search.js');
    	wp_enqueue_script('CTGoogle_API');
    	wp_enqueue_script('CTGoogle_Script');
    	
    	//add_action ('init',Array('CTGoogleSearch', 'lbIncludes'));
		add_action('wp_title', array('CTGoogleSearch', 'get_title'));
		add_action('wp_head', Array('CTGoogleSearch', 'search_css'));
		add_action('wp_head', Array('CTGoogleSearch', 'search_js'));
		
		
    	
    	
		get_header();
		echo '<div class="container">';
		echo '<div class="content-area ">';
		echo '<section id="leftCol" class="eightcol">';
		echo '<h2>Search Results for :'.$_GET['s'].'</h2>';
		echo ' <div id="results"></div>';
		
		echo '</section>';
		
		self::results_template();
		echo '</div>';
		echo get_sidebar();
		echo '</div>'; 
		
		echo '<form action="" id="searchform" method="get"><input type="text" id="s" name="s" class="search_box" placeholder="Search the blog" value=""><input type="submit" style="display:none;" value="Search" id="searchsubmit"></form>';

		get_footer();
		exit;
	}
	
	public static function lbIncludes()  {
    	wp_register_style('CTGoogle_Style', WP_PLUGIN_URL . '/css/ctsearch.css');
    	wp_enqueue_style('CTGoogle_Style');
    	
    	wp_register_script('CTGoogle_Script', WP_PLUGIN_URL . '/js/ct_search.js');
    	wp_enqueue_script('CTGoogle_Script');
	}


	public static function get_title(){
		if (isset($_GET['q']))
		{
			// change status code to 200 OK since /search/ returns status code 404
			@header("HTTP/1.1 200 OK",1);
			@header("Status: 200 OK", 1);
			return $_GET['q'].' -';
		}
	}
	
	public static function search_js(){
		?>
		<script type="text/javascript">
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

        // Execute an initial search
		customSearchControl.execute('<?php print $_GET['s'];?>');
		customSearchControl.setResultSetSize(google.search.Search.FILTERED_CSE_RESULTSET);
      },
    true);
    	</script>
    
    <?php
		
	}
	
	public static function search_css()
	{
		echo '<style type="text/css">';
		//echo '#cse-search-results iframe { width:600px; margin:20px; }';
		echo '</style>';
	
	}
	
	public static function results_template(){
	
	?>
	<div style="display:none">

    <!-- Create 48px x 48px thumbnails.-->
    <div id="mysite_thumbnail">
      <div data-if="Vars.thumbnail" class="gs-image-box gs-web-image-box">
        <a class="gs-image" data-attr="{href:url, target:target}">
          <img class="gs-image" data-attr="{src:thumbnail.src, width:48, height: 48}"/>
        </a>
      </div>
    </div>

    <!-- Return the unescaped result URL.-->
    <div id="mysite_webResult">
      <div class="gs-webResult gs-result"
        data-vars="{longUrl:function() {
          var i = unescapedUrl.indexOf(visibleUrl);
          return i < 1 ? visibleUrl : unescapedUrl.substring(i)+' →';}}">
	<table>
          <tr>
            <td valign="top">
              <div data-if="Vars.richSnippet" data-attr="0"
                data-body="render('thumbnail',richSnippet,{url:unescapedUrl,target:target})"></div>
            </td>
            <td valign="top">

              <!-- Append results within the table cell.-->
              <div class="gs-title">
                <a class="gs-title" data-attr="{href:unescapedUrl,target:target}"
                  data-body="html(title)"></a>
              </div>
              <div class="gs-snippet" data-body="html(content)"></div>
              <div class="gs-visibleUrl gs-visibleUrl-short" data-body="longUrl()"></div>
              <div style="color:#676767" data-if="Vars.richSnippet && Vars.richSnippet.document">

                <!-- Insert an icon denoting the result file type.-->
                <img data-attr="{src:Vars.richSnippet.document.filetypeImage}">

                <!-- Insert the author name for this Scribd post.-->
                By <span data-body="Vars.richSnippet.document.author"></span>  - 

                <!-- Insert the number of pages in this Scribd result.-->
                <span data-body="Vars.richSnippet.document.pageCount"></span> pages - 
                <span><a href="#">Continue Reading →</a></span> - 
                <!-- Insert the number of times this Scribd post has been viewed.-->
                <span data-body="Vars.richSnippet.document.viewCount"></span> views
                
                <!-- Insert the last modified date-->
                 - last modified  <span data-body="Vars.richSnippet.document.timeAgo"></span>
              </div>

              <!-- Render results.-->
              <div data-if="Vars.richSnippet && Vars.richSnippet.action" class="gs-actions"
                data-body="render('action',richSnippet,{url:unescapedUrl,target:target})"></div>

            </td>
          </tr>
        </table>
        </div>
    </div>
	<?php
	}

}

$WP_Sample_Plugin = new CTGoogleSearch();
$WP_Sample_Plugin->set_hooks();

// vim: ft=php expandtab
