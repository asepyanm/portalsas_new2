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
<section class="blog_area single-post-area section_padding bg_page">
	<div class="container">
		<div class="row">
			<div class="col-lg-8 posts-list">
				<div class="single-post">
					<!--<div class="feature-img">
                     <img class="img-fluid" src="img/blog/single_blog_1.png" alt="">
				  </div>
				  //-->
					<div class="blog_details">

						<div id="dtShow"></div>

					</div>
				</div>
				<div class="navigation-top">
					<div class="d-sm-flex justify-content-between text-center">


					</div>
					<!-- <div class="navigation-area">
						<div class="row">
							<div class="col-lg-6 col-md-6 col-12 nav-left flex-row d-flex justify-content-start align-items-center">
								<div class="thumb">
									<a href="#">
										<img class="img-fluid" src="img/post/preview.png">
									</a>
								</div>
								<div class="arrow">
									<a href="#">
										<span class="lnr text-white ti-arrow-left"></span>
									</a>
								</div>

							</div>

						</div>
					</div> -->
				</div>
			</div>

			<div class="col-md-4">
				<br>
				<h3 style="border-bottom: 2px solid gray;width:50%;">Berita Terbaru</h3>
				<hr>
				<?= getNewsTerbaru(); ?>

				<center><a href="index_news.php" class="btn_4"><span class="text-white">Indeks Berita</span></a></center>
			</div>

		</div>
		<div class="col-lg-4">
			<div class="blog_right_sidebar">

			</div>
		</div>
	</div>
	</div>
</section>
<!--================Blog Area end =================-->



<?php require_once('footer.php'); ?>