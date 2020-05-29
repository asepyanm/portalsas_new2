<?php require_once('header.php');
$c = $_REQUEST['c'];
?>



<!--================Blog Area =================-->
<section class="blog_area single-post-area section_padding bg_page">
   <div class="container">
      <div class="row">
         <div class="col-lg-12 posts-list">
            <div class="single-post">

               <div class="blog_details">

                  <?php require_once('bit_content/' . $c . '.php'); ?>

               </div>
            </div>
         </div>
      </div>

   </div>
   </div>
</section>




<?php require_once('footer.php'); ?>