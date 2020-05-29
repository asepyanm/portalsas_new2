<?php require_once('header.php'); ?>
<section class="blog_area section_padding bg_page">
    <div class="container" id="isi">
        <div class="section_tittle text-center">

            <h2>Indeks Video</h2>
        </div>
        <?= getIndexVideo(); ?>
    </div>
</section>
<script>
    $(document).ready(function() {

        $('.video').colorbox({
            rel: 'video',
            iframe: true,
            transition: 'fade',
            width: "75%",
            height: "75%",
        });

    });
</script>
<?php require_once('footer.php'); ?>