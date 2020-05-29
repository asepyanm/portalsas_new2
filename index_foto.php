<?php require_once('header.php'); ?>
<section class="blog_area section_padding bg_page">
    <div class="container" id="isi">
        <div class="section_tittle text-center">

            <h2>Indeks Foto</h2>
        </div>
        <?= getIndexFoto(); ?>
    </div>
</section>
<script>
    $(document).ready(function() {
        $('.foto').colorbox({
            rel: 'foto',
            transition: "fade",
            width: "75%",
            height: "75%"
        });
    });
</script>
<?php require_once('footer.php'); ?>