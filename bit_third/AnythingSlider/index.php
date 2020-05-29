<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
    <meta http-equiv="Content-type" content="text/html; charset=utf-8" />
    
    <title>anythingSlider</title>
    
    <link rel="stylesheet" href="css/page.css" type="text/css" media="screen" />
    <link rel="stylesheet" href="css/slider.css" type="text/css" media="screen" />
    
    <script type="text/javascript" src="js/jquery.min.js"></script>
    <script type="text/javascript" src="js/jquery.easing.1.2.js"></script>
	<script src="js/jquery.anythingslider.js" type="text/javascript" charset="utf-8"></script>

    <script type="text/javascript">
    
        function formatText(index, panel) {
		  return index + "";
	    }
    
        $(function () {
        
            $('.anythingSlider').anythingSlider({
                easing: "easeInOutExpo",        // Anything other than "linear" or "swing" requires the easing plugin
                autoPlay: true,                 // This turns off the entire FUNCTIONALY, not just if it starts running or not.
                delay: 3000,                    // How long between slide transitions in AutoPlay mode
                startStopped: false,            // If autoPlay is on, this can force it to start stopped
                animationTime: 600,             // How long the slide transition takes
                hashTags: true,                 // Should links change the hashtag in the URL?
                buildNavigation: true,          // If true, builds and list of anchor links to link to each slide
        		pauseOnHover: true,             // If true, and autoPlay is enabled, the show will pause on hover
        		startText: "Go",             // Start text
		        stopText: "Stop",               // Stop text
		        navigationFormatter: formatText       // Details at the top of the file on this use (advanced use)
            });
            
            $("#slide-jump").click(function(){
                $('.anythingSlider').anythingSlider(6);
            });
            
        });
    </script>
</head>

<body>

    <?php include("../header.php"); ?>

    <div id="page-wrap">
    
        <a href="http://css-tricks.com/examples/AnythingSlider.zip" id="dl">Download v1.2</a>
    
        <h5>By <a href="http://css-tricks.com">Chris Coyier</a>, based upon lots of smart stuff by <a href="http://jqueryfordesigners.com/">Remy Sharp</a>,<br />
        significantly improved by <a href="http://pixelgraphics.us/">Douglas Neiner</a></h5>
    
        <h1>AnythingSlider</h1>
    
        <div class="anythingSlider">
        
          <div class="wrapper">
            <ul>
               <li>
                    <img src="images/slide-civil-1.jpg" alt="" />
               </li>
              <li>
                 
                 <div id="textSlide">
                 
                    <img src="images/251356.jpg" alt="tomato sandwich" style="float: right; margin: 0 0 2px 10px;" />
                    
                    <h3>Queenie's Killer Tomato Bagel Sandwich</h3>
                    
                    <h4>Ingredients</h4>

                    <ul>
                        <li>1 bagel, split and toasted</li>
                        <li>2 tablespoons cream cheese</li>
                        <li>1 roma (plum) tomatoes, thinly sliced</li>
                        <li>salt and pepper to taste</li>
                        <li>4 leaves fresh basil</li>
                    </ul>

                    
                 </div>
                 
              </li>
              <li>
                 <img src="images/slide-env-1.jpg" alt="" />
              </li>
              <li>
                 <img src="images/slide-civil-2.jpg" alt=""  />
              </li>
              <li>
                 <div id="quoteSlide">
                 
                    <blockquote>Life is conversational. Web design should be the same way. On the web, you&#8217;re talking to someone you&#8217;ve probably never met – so it&#8217;s important to be clear and precise. Thus, well structured navigation and content organization goes hand in hand with having a good conversation.</blockquote>
                    <p> - <a id='perma' href='http://quotesondesign.com/chikezie-ejiasi/'>Chikezie Ejiasi</a></p>
                 
                 </div>
              </li>
              <li>
                 <img src="images/slide-env-2.jpg" alt="" />
              </li>
            </ul>        
          </div>
          
        </div> <!-- END AnythingSlider -->

      
        <h2>Features</h2>
        <ul>
            <li>Slides are HTML Content (can be anything)</li>
            <li>Next Slide / Previous Slide Arrows</li>
            <li>Navigation tabs are built and added dynamically (any number of slides)</li>
            <li>Optional custom function for formatting navigation text</li>
            <li>Auto-playing (optional feature, can start playing or stopped)</li>
            <li>Each slide has a hashtag (can link directly to specific slides)</li>
            <li>Infinite/Continuous sliding (always slides in the direction you are going, even at "last" slide)</li>
            <li>Multiple sliders allowable per-page (hashtags only work on first)</li>     
            <li>Pauses autoPlay on hover (option)</li>
            <li>Link to specific slides from static text links (<a href="#" id="slide-jump">Slide 6</a>)</li>  
        </ul>
                
        
        <h2>Usage &amp; Options (defaults)</h2>
        <pre>$('.anythingSlider').anythingSlider({
        easing: "swing",                // Anything other than "linear" or "swing" requires the easing plugin
        autoPlay: true,                 // This turns off the entire FUNCTIONALY, not just if it starts running or not
        startStopped: false,            // If autoPlay is on, this can force it to start stopped
        delay: 3000,                    // How long between slide transitions in AutoPlay mode
        animationTime: 600,             // How long the slide transition takes
        hashTags: true,                 // Should links change the hashtag in the URL?
        buildNavigation: true,          // If true, builds and list of anchor links to link to each slide
        pauseOnHover: true,             // If true, and autoPlay is enabled, the show will pause on hover
        startText: "Start",             // Start text
        stopText: "Stop",               // Stop text
        navigationFormatter: null       // Details at the top of the file on this use (advanced use)
});</pre>

        <h3>Linking directly to slides</h3>
        <pre>$("#slide-jump").click(function(){
     $('.anythingSlider').anythingSlider(6);
});</pre>
        
        <h2>Changelog</h2>
        
		<h3>Version 1.2</h3>
		<ul>
			<li>Bug Fix: When autoPlay was set to false, any interaction with the control would cause a javascript error.</li>
		</ul>

        <h3>Version 1.1</h3>
        <ul>
            <li>Changed default easing to "swing" so didn't depend on any other plugins</li>
            <li>Removed extra junk (other plugins used for design, etc)</li>
            <li>Added Pause on Hover option</li>
            <li>Added options for passing in HTML for the start and stop button</li>
            <li>Added option to use custom function for formatting the titles of the navigation</li>
            <li>Added public interface for linking directly to certain slides</li>
        </ul>
        
        <h3>Version 1.0</h3>
        <ul>
            <li>First version</li>
        </ul>
    
    </div>
    
    <?php include("../footer.php"); ?>
        
</body>

</html>