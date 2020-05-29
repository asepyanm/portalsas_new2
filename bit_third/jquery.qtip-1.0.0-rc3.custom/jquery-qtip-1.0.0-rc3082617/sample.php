<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Untitled Document</title>
</head>
<body>
<script type="text/javascript" src="jquery-1.3.2.min.js"></script>
<script type="text/javascript" src="jquery.qtip-1.0.0-rc3.min.js"></script>

<script type="text/javascript">
// Create the tooltips only on document load
$(document).ready(function() 
{
	alert('a');
   // Notice the use of the each() method to acquire access to each elements attributes
   $('#content a[tooltip]').each(function()
   {
      $(this).qtip({
         content: $(this).attr('tooltip'), // Use the tooltip attribute of the element for the content
         style: 'dark' // Give it a crea mstyle to make it stand out
      });
   });
});
</script>

<script type="text/javascript">
$(document).ready(function()
{
   $('a[rel="modal"]:first').qtip(
   {
      content: {
         title: {
            text: 'Modal qTip',
            button: 'Close'
         },
         text: 'Heres an example of a rather bizarre use for qTip... a tooltip as a <b>modal dialog</b>! <br /><br />' +
               'Much like the <a href="http://onehackoranother.com/projects/jquery/boxy/">Boxy</a> plugin, ' +
               'but if you\'re already using tooltips on your page... <i>why not utilise qTip<i> as a modal dailog instead?'
      },
      position: {
         target: $(document.body), // Position it via the document body...
         corner: 'center' // ...at the center of the viewport
      },
      show: {
         when: 'click', // Show it on click
         solo: true // And hide all other tooltips
      },
      hide: false,
      style: {
         width: { max: 350 },
         padding: '14px',
         border: {
            width: 9,
            radius: 9,
            color: '#666666'
         },
         name: 'light'
      },
      api: {
         beforeShow: function()
         {
            // Fade in the modal "blanket" using the defined show speed
            $('#qtip-blanket').fadeIn(this.options.show.effect.length);
         },
         beforeHide: function()
         {
            // Fade out the modal "blanket" using the defined hide speed
            $('#qtip-blanket').fadeOut(this.options.hide.effect.length);
         }
      }
   });

   // Create the modal backdrop on document load so all modal tooltips can use it
   $('<div id="qtip-blanket">')
      .css({
         position: 'absolute',
         top: $(document).scrollTop(), // Use document scrollTop so it's on-screen even if the window is scrolled
         left: 0,
         height: $(document).height(), // Span the full document height...
         width: '100%', // ...and full width

         opacity: 0.7, // Make it slightly transparent
         backgroundColor: 'black',
         zIndex: 5000  // Make sure the zIndex is below 6000 to keep it below tooltips!
      })
      .appendTo(document.body) // Append to the document body
      .hide(); // Hide it initially
});
</script>


<div id="content">
<div class="center">
   <h2>jQuery JavaScript Library</h2>
   <img class="left" src="http://upload.wikimedia.org/wikipedia/en/2/2f/Jquerylogo.png" alt="" />

   <p>jQuery is a lightweight JavaScript library that emphasizes interaction between JavaScript and HTML. It was released January 2006 at BarCamp <a href="http://en.wikipedia.org/wiki/New_York_City" tooltip="New York City">NYC</a> by John Resig. Dual licensed under the MIT License and the GNU General Public License, jQuery is free, open source software.</p>

   <p>Both <a href="http://en.wikipedia.org/wiki/Microsoft" tooltip="Owned by Bill Gates">Microsoft</a> and Nokia have announced plans to bundle jQuery on their platforms, Microsoft adopting it initially within Visual Studio[2] and use within Microsoft's ASP.NET AJAX framework and ASP.NET MVC Framework whilst Nokia will integrate it into their Web Run-Time platform.</p>
   
   <p>Just like CSS separates "display" characteristics from the HTML structure, <a href="http://jquery.com" tooltip="We recommend version 1.3 and above">jQuery</a> separates the "behavior" characteristics from the HTML structure. For example, instead of directly specifying the on-click event handler in the specification of a button element, a jQuery driven page would first identify the button element, and then modify its on-click event handler. This separation of behavior from structure is also referred to as the principle of Unobtrusive JavaScript.</p>

   <br />
   <p><b>Source:</b> Wikipedia</p>
</div>
</div>

<div id="content" class="modal">
<div class="center" style="text-align: center">
   <a href="#" rel="modal">Click here</a> to see a qTp modal dialog.

</div>

</body>
</html>
