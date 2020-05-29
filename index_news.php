<?php require_once('header.php');

/*
    session_start();
	include_once("bit_config.php");
	
	$ora=new clsMysql;
	$ora->logon($bit_app["user_db"],$bit_app["pass_db"]);
	*/
if ($_GET["cat"]) {
    $whereCat = "and category_id=" . $_GET["cat"];
    $cat = $_GET["cat"];
}

if (!$_GET["id"]) {
    $qry = "select max(id) from m_contents where publish_flag=1 $whereCat";
    $rsID = $ora->sql_fetch($qry, $bit_app["db"]);
    $_GET["id"] = $rsID->value[1][1];
}
?>


<script language="javascript">
    function dtSearch() {
        var str = $("#frSearch").serialize();
        $.ajax({
            type: "POST",
            url: "bit_content/contents/list.php",
            data: str,
            success: function(msg) {
                $("#dtList").html(msg);
            }
        });
    }

    function dtComment(id) {
        var str = $("#frContent").serialize();
        $.ajax({
            type: "POST",
            url: "bit_content/contents/comment.php?id=" + id,
            data: str,
            success: function(msg) {
                $("#dtComment").html(msg);
            }
        });
    }

    function dtRate(id) {
        var str = $("#frRate").serialize();
        $.ajax({
            type: "POST",
            url: "bit_content/contents/rate.php?id=" + id,
            data: str,
            success: function(msg) {
                $("#dtRate").html(msg);
            }
        });
    }

    function dtList(cat) {
        var str = $("#frList").serialize();
        $.ajax({
            type: "POST",
            url: "bit_content/contents/list.php?cat=" + cat,
            data: str,
            success: function(msg) {
                $("#dtList").html(msg);
            }
        });
    }

    function dtShow(id) {
        var str = $("#frShow").serialize();
        $.ajax({
            type: "POST",
            url: "bit_content/contents/show.php?id=" + id,
            data: str,
            success: function(msg) {
                $("#dtShow").html(msg);
            },
            complete: function(msg) {},
            beforeSend: function(msg) {
                //
            },
            error: function(msg) {
                //
            }

        });
    }


    function init() {

        dtShow(<? echo $_GET["id"] ?>);
        /*
        dtComment(<? echo $_GET["id"] ?>);
        dtRate(<? echo $_GET["id"] ?>);
        dtList(<? echo format($cat) ?>);
        */
    }
</script>





<!--================Blog Area =================-->
<section class="blog_area section_padding bg_page">
    <div class="container">
        <div class="section_tittle text-center">

            <h2>Indeks Berita</h2>
        </div>
        <div class="row">
            <div class="col-lg-8 col-lg-8 posts-list">
                <div class="navigation-top">
                    <div class="d-sm-flex justify-content-between text-center">


                    </div>
                    <div class="navigation-area">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-12">
                                <br>
                                <div class="card">
                                    <div class="card-body">
                                        <form action="" method="POST">
                                            <div class="input-group">
                                                <input type="text" name="cariNews" id="cariNews" class="form-control">
                                                <div class="input-group-append">
                                                    <button class="btn btn-outline-danger" type="submit" name="btnCariNews" id="btnCariNews">Cari</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <br>
                                <?php if (isset($_POST['cariNews'])) {
                                    $filter = $_POST['cariNews'];
                                } else {
                                    $filter = null;
                                } ?>

                                <?= getIndexNews($filter); ?>

                            </div>

                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-md-4">
                <br>
                <h3 style="border-bottom: 2px solid gray;width:50%;">Berita Terbaru</h3>
                <hr>
                <?= getNewsTerbaru(); ?>
            </div>

        </div>
        <div class="col-lg-4">
            <div class="blog_right_sidebar">
                <!-- <aside class="single_sidebar_widget search_widget">

				</aside>

				<aside class="single_sidebar_widget popular_post_widget">

				</aside>
				<aside class="single_sidebar_widget tag_cloud_widget">

				</aside>
				<aside class="single_sidebar_widget instagram_feeds">

				</aside>
				<aside class="single_sidebar_widget newsletter_widget">

				</aside> -->
            </div>
        </div>
    </div>
    </div>
</section>
<!--================Blog Area end =================-->
<script>
    $(document).ready(function() {

        $('#btnCariNews').on('click', function() {
            inputanCari = "ggwp";
            $.ajax({
                type: "GET",
                url: "index_news.php",
                data: inputanCari,
                success: function() {

                }

            });
        });
    });
</script>
<?php require_once('footer.php'); ?>