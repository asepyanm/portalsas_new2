<!--::header part start::-->
<?php require_once('header.php'); ?>
<!-- Header part end-->

<!-- banner part start-->
<section class="banner_part">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 col-xl-6">
                <div class="banner_text">
                    <div class="banner_text_iner">
                        <h5>SIAP...!!! SIGAP...!!! MENANG...!!!</h5>
                        <h1 id="sasis">SAS<span style="font-size: 50px;">is<span></h1>
                        <p>Tools Pengelolaan Security & Safety Perusahaan "Sistem Manajemen K3" / "Asset Protection" / "Safety Care" / "Vandalisme"</p>

                        <?php appSAS(); ?>

                        <br><br><br><br>

                        <div class="col-lg-10">
                            <?php
                            if (!getUserID())
                                login();
                            ?>

                        </div>

                    </div>

                </div>
            </div>
        </div>
    </div>
</section>
<!-- banner part start-->


<!-- feature_part start-->
<section class="feature_part">
    <div class="container">
        <div class="row">
            <div class="col-sm-6 col-xl-3 align-self-center">
                <div class="single_feature_text ">
                    <h2>Manajemen <br> SAS</h2>
                    <!--<p>Set have great you male grass yielding an yielding first their you're
                            have called the abundantly fruit were man </p>
                        <a href="#" class="btn_1">Read More</a>-->
                </div>
            </div>

            <?php echo ManajemenSAS(); ?>
        </div>
    </div>
</section>
<!-- upcoming_event part start-->

<!--::review_part start::-->
<section class="special_cource padding_top">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-5">
                <div class="section_tittle text-center">
                    <p>Berita</p>
                    <h2>Seputar SAS</h2>
                </div>
            </div>
        </div>
        <div class="row">
            <?php echo categoryTopNews(4); ?>
        </div>
    </div>
</section>
<!--::blog_part end::-->

<!-- learning part start-->
<!-- <section class="advance_feature learning_part">
    <div class="container">
        <div class="row align-items-sm-center align-items-xl-stretch">
            <div class="col-md-12 col-lg-12">
                <div class="learning_member_text text-center"> -->
<!-- <h5></h5> //-->
<!-- <h2>Arahan Manajemen</h2>
                    <div class="row"> -->
<!-- <?php echo categoryTopNews(3); ?> -->
<!-- </div>
                </div>
            </div>
        </div>
    </div>
</section> -->
<!-- learning part end-->


<!--::foto gallery start::-->
<!-- <section class="testimonial_part section_padding"> -->
<!-- <section class="section_padding"> -->
<!-- <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-5">
                <div class="section_tittle text-center"> -->
<!-- <p>tesimonials</p> //-->
<!-- <h2>Gallery Foto</h2>
                </div>
            </div>
        </div>

        <div class="row">

            <div class="col-lg-12">

                <div class="textimonial_iner owl-carousel"> -->

<!--  galleryFoto(); -->

<!--
                        <div class="testimonial_slider">
                            <div class="row">
                                <div class="col-lg-8 col-xl-4 col-sm-8 align-self-center">
                                    <div class="testimonial_slider_img">
                                        <img src="https://localhost/portalsas_new/doc/mainsas/img/testimonial_img_1.png" alt="#">
                                    </div>
                                </div>
                                <div class="col-lg-8 col-xl-4 col-sm-8 align-self-center">
                                    <div class="testimonial_slider_img">
                                        <img src="https://localhost/portalsas_new/doc/mainsas/img/testimonial_img_1.png" alt="#">
                                    </div>
                                </div>
                                <div class="col-lg-8 col-xl-4 col-sm-8 align-self-center">
                                    <div class="testimonial_slider_img">
                                        <img src="https://localhost/portalsas_new/doc/mainsas/img/testimonial_img_1.png" alt="#">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="testimonial_slider">
                            <div class="row">
                                <div class="col-lg-8 col-xl-4 col-sm-8 align-self-center">
                                    <div class="testimonial_slider_img">
                                        <img src="https://localhost/portalsas_new/doc/mainsas/img/testimonial_img_1.png" alt="#">
                                    </div>
                                </div>
                                <div class="col-lg-8 col-xl-4 col-sm-8 align-self-center">
                                    <div class="testimonial_slider_img">
                                        <img src="https://localhost/portalsas_new/doc/mainsas/img/testimonial_img_1.png" alt="#">
                                    </div>
                                </div>
                                <div class="col-lg-8 col-xl-4 col-sm-8 align-self-center">
                                    <div class="testimonial_slider_img">
                                        <img src="https://localhost/portalsas_new/doc/mainsas/img/testimonial_img_1.png" alt="#">
                                    </div>
                                </div>
                            </div>
                        </div>
                        //-->
<!-- </div>
                <br>
                <center><a href="index_foto.php" class="btn_4">Indeks Foto</a></center>


            </div>


        </div>

    </div>
</section> -->
<!--::foto gallery end::-->

<!--::video start::-->
<section class="testimonial_part section_padding">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-5">
                <div class="section_tittle text-center">

                    <h2>Gallery Video</h2>
                </div>
            </div>
        </div>

        <div class="row">

            <div class="col-lg-12">

                <div class="textimonial_iner owl-carousel">

                    <?php echo galleryVideo(); ?>

                </div>

                <br>
                <center><a href="index_video.php" class="btn_4">Indeks Video</a></center>

            </div>


        </div>

    </div>
</section>
<!--::video end::-->


<!-- footer part start-->
<!-- <footer class="footer-area">
    <div class="container"> -->
<!--
            <div class="row justify-content-between">
                <div class="col-sm-6 col-md-4 col-xl-3">
                    <div class="single-footer-widget footer_1">
                        <a href="index.html"> <img src="img/logo.png" alt=""> </a>
                        <p>But when shot real her. Chamber her one visite removal six
                            sending himself boys scot exquisite existend an </p>
                        <p>But when shot real her hamber her </p>
                    </div>
                </div>
                <div class="col-sm-6 col-md-4 col-xl-4">
                    <div class="single-footer-widget footer_2">
                        <h4>Newsletter</h4>
                        <p>Stay updated with our latest trends Seed heaven so said place winged over given forth fruit.
                        </p>
                        <form action="#">
                            <div class="form-group">
                                <div class="input-group mb-3">
                                    <input type="text" class="form-control" placeholder='Enter email address'
                                        onfocus="this.placeholder = ''"
                                        onblur="this.placeholder = 'Enter email address'">
                                    <div class="input-group-append">
                                        <button class="btn btn_1" type="button"><i class="ti-angle-right"></i></button>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <div class="social_icon">
                            <a href="#"> <i class="ti-facebook"></i> </a>
                            <a href="#"> <i class="ti-twitter-alt"></i> </a>
                            <a href="#"> <i class="ti-instagram"></i> </a>
                            <a href="#"> <i class="ti-skype"></i> </a>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-sm-6 col-md-4">
                    <div class="single-footer-widget footer_2">
                        <h4>Contact us</h4>
                        <div class="contact_info">
                            <p><span> Address :</span> Hath of it fly signs bear be one blessed after </p>
                            <p><span> Phone :</span> +2 36 265 (8060)</p>
                            <p><span> Email : </span>info@colorlib.com </p>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="copyright_part_text text-center">
                        <div class="row">
                            <div class="col-lg-12">
                                <p class="footer-text m-0">Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. 
Copyright &copy;<script>document.write(new Date().getFullYear());</script> All rights reserved | This template is made with <i class="ti-heart" aria-hidden="true"></i> by <a href="https://colorlib.com" target="_blank">Colorlib</a>
Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            //-->
<!-- </div>
</footer> -->
<!-- footer part end-->


<?php require_once('footer.php'); ?>