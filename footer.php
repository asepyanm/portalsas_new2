
<footer style="background-color: #fce4ec;height:70px; margin-top:120px;">
    <c class="container" style="height: 100%;">
        <center style="padding-top:10px; align-content: center;">
            Copyright &copy; 2020 <a href="#"><b>Portal Sas</b></a>.
            All rights reserved.
        </center>
        </div>
</footer>

<!-- The Modal -->
<div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="z-index:9999;">
<div class="modal-dialog modal-xl">
    <div class="modal-content">
    <div class="modal-header">
        <h5 class="modal-title">GENERAL TERM OF SERVICE WEBSITE EMAIL TELKOM</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="modal-body">
    
        <embed src="<?php echo $bit_app["path_url"]; ?>user-guide/General Term of Service - Portal SAS (SASIS) v0.5-converted.pdf" 
    type="application/pdf" style="width:100%;height:500px;">
    </div>
    </div>
</div>
</div>

<!-- jquery plugins here-->
<!-- popper js -->
<script src="bit_js/popper.min.js"></script>
<!-- bootstrap js -->
<script src="bit_js/bootstrap.min.js"></script>
<!-- easing js -->

<script src="bit_js/jquery.magnific-popup.js"></script>
<!-- swiper js -->
<script src="bit_js/swiper.min.js"></script>
<!-- swiper js -->
<script src="bit_js/masonry.pkgd.js"></script>
<!-- particles js -->
<script src="bit_js/owl.carousel.min.js"></script>
<script src="bit_js/jquery.nice-select.min.js"></script>
<!-- swiper js -->
<script src="bit_js/slick.min.js"></script>
<script src="bit_js/jquery.counterup.min.js"></script>
<script src="bit_js/waypoints.min.js"></script>
<!-- media element js -->
<script src="mediaelement-master/build/mediaelement-and-player.js"></script>
<!-- custom js -->
<script src="bit_js/custom.js"></script>
<script>
    $(document).ready(function() {
        $('video').mediaelementplayer();

        $('#hidden_term').val('nok');
    $('#cTerm').on('change', function () {
        if ($(this).prop('checked')) {
            // $('.tgl_pemeriksaan').fadeIn('slow');
            $('#hidden_term').val('ok');
        } else {
            // $('.tgl_pemeriksaan').fadeOut('slow');
            $('#hidden_term').val('nok');
        }
    });



        $('.fotoUtama').colorbox({
            rel: 'foto',
            transition: "fade",
            width: "75%",
            height: "75%"
        });

        $('.videoUtama').colorbox({
            rel: 'video',
            iframe: true,
            transition: 'fade',
            width: "75%",
            height: "75%",
        });
    });
</script>

</body>

</html>
<?php $ora->logoff(); ?>