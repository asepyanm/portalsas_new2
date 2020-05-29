<?php
session_start();
include_once('../bit_config.php');
if (getUserLevel() == 1) {
  #alert('Anda tidak berhak mengakses menu Administrator !');
  echo "<script>window.location.href='../'</script>";
  exit;
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Frameset//EN" "http://www.w3.org/TR/html4/frameset.dtd">
<html>

<head>
  <title><? echo $bit_app["title"] ?></title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <link rel="stylesheet" type="text/css" href="<? echo $bit_app["path_url"] ?>/bit_css/standart.css">
  <link href="css/standart.css" rel="stylesheet" type="text/css">
  <link rel="stylesheet" href="css/template.css" type="text/css" />
  <link rel="stylesheet" href="css/theme.css" type="text/css" />
  <script language="JavaScript" src="mod/JSCookMenu.js" type="text/javascript"></script>
  <script language="JavaScript" src="mod/ThemeOffice/theme.js" type="text/javascript"></script>
  <script language="JavaScript" src="mod/mambojavascript.js" type="text/javascript"></script>
  <link rel="shortcut icon" href="<? echo $bit_app["path_url"]; ?>bit_images/favicon1.png" type="image/x-icon" />

  <link rel="stylesheet" href="../bit_css/lte/bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
  <link rel="stylesheet" href="../bit_css/lte/dist/css/AdminLTE.min.css">
  <link rel="stylesheet" href="../bit_css/lte/dist/css/skins/_all-skins.min.css">
  <link rel="stylesheet" href="../bit_css/lte/plugins/iCheck/flat/blue.css">
  <link rel="stylesheet" href="../bit_css/lte/plugins/morris/morris.css">
  <link rel="stylesheet" href="../bit_css/lte/plugins/jvectormap/jquery-jvectormap-1.2.2.css">
  <link rel="stylesheet" href="../bit_css/lte/plugins/datepicker/datepicker3.css">
  <link rel="stylesheet" href="../bit_css/lte/plugins/daterangepicker/daterangepiker.css">
  <link rel="stylesheet" href="../bit_css/lte/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css">

  <link type="text/css" media="screen" rel="stylesheet" href="../bit_third/colorbox/colorbox/example1/colorbox.css" />
</head>

<body class="hold-transition skin-blue sidebar-mini">
  <?php
  $eF = getForm($_GET["form"]);
  ?>
  <div class="wrapper">
    <header class="main-header">
      <a href="index2.html" class="logo">
        <span class="logo-mini"><b>A</b>LT</span>
        <span class="logo-lg"><b>Control Panel</b></span>
      </a>
      <nav class="navbar navbar-static-top">
        <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
          <span class="sr-only">Toggle navigation</span>
        </a>
        <div class="navbar-custom-menu">
          <ul class="nav navbar-nav">
            <li class="dropdown user user-menu">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                <!-- <img src="{theme_path}images/amy_jones.jpg" class="user-image" alt="User Image">//-->
                <span class="hidden-xs">
                  Loker : <?= ucfirst(getUserLoker()); ?>
                </span>
              </a>
              <ul class="dropdown-menu">
                <li class="user-header">
                  <!-- <img src="{theme_path}images/amy_jones.jpg" class="img-circle" alt="User Image">//-->
                </li>
                <li class="user-footer">
                  <div class="pull-right">
                    <a href="logout.php" class="btn btn-default btn-flat">Sign out</a>
                  </div>
                </li>
              </ul>
            </li>
            <li>
              <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
            </li>
          </ul>
        </div>
      </nav>
    </header>
    <aside class="main-sidebar">
      <section class="sidebar">
        <div class="user-panel">
          <div class="pull-left image">
            <img src="<? echo $bit_app["path_url"]; ?>/bit_css/lte/dist/img/avatar5.png" class="img-circle" alt="User Image">
          </div>
          <div class="pull-left info">
            <?= getUserName(); ?>
            <p style="font-size: 13px; margin-top:5px;" class="text-gray"><?php if (getUserLevel() == 4) : ?>
                Admin
              <?php elseif (getUserLevel() == 2) : ?>
                Author
              <?php elseif (getUserLevel() == 3) : ?>
                Publisher
              <?php endif; ?></p>



          </div>
        </div>

        <!--
        <form action="#" method="get" class="sidebar-form">
        <div class="input-group">
          <input type="text" name="q" class="form-control" placeholder="Search...">
              <span class="input-group-btn">
                <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i>
                </button>
              </span>
        </div>
      </form>
      //-->
        <ul class="sidebar-menu">
          <li class="header">MAIN NAVIGATION</li>
          <li><a href="<? echo $bit_app["path_url"]; ?>" target="_blank"><i class="fa fa-book"></i> <span>View Site</span></a></li>
          <li><a href="?form=content.welcome"><i class="fa fa-files-o"></i> <span>Home</span></a></li>
          <?php if (getUserLevel() == 4) : ?>
            <li><a href="?form=content.catList"><i class="fa fa-laptop"></i> <span>Kategori News</span></a></li>
            <li><a href="?form=content.contentList"><i class="fa fa-laptop"></i> <span>News</span></a></li>
            <li><a href="?form=content.manajemenSas"><i class="fa fa-user"></i> <span>Manajemen SAS</span></a></li>>
            <li><a href="?form=content.menuList"><i class="fa fa-book"></i> <span>Menu</span></a></li>
            <li><a href="?form=content.publisherNews"><i class="fa fa-book"></i> <span>Flow</span></a></li>
            <li><a href="?form=content.kebijakanList"><i class="fa fa-book"></i> <span>Peraturan Perundang Undangan</span></a></li>
            <!-- <li><a href="?form=content.pengumumanList"><i class="fa fa-files-o"></i> <span>Running Text</span></a></li>//-->
            <!-- <li><a href="?form=content.fotList"><i class="fa fa-files-o"></i> <span>Gallery Foto</span></a></li> -->
            <li><a href="?form=content.vidList"><i class="fa fa-film"></i> <span>Movie</span></a></li>
            <li><a href="?form=content.telp1"><i class="fa fa-book"></i> <span>No Telp. Personil SAS<span></a></li>
            <li><a href="?form=content.telp2"><i class="fa fa-book"></i> <span>No Telp. Pejabat Telkom</span></a></li>
            <li><a href="?form=content.telp3"><i class="fa fa-book"></i> <span>No. Telp. Penting</span></a></li>
            <li><a href="?form=content.userList"><i class="fa fa-book"></i> <span>Daftar User</span></a></li>
          <?php endif; ?>
        </ul>
      </section>
    </aside>
    <div class="content-wrapper">
      <section class="content-header">
        <h1>
          Control Panel
        </h1>
      </section>
      <section class="content">
        <div class="row">
          <section class="col-lg-12 connectedSortable">
            <div class="box box-success">

              <?php //include('mod/mod_fullmenu.php') 
              ?>

              <?php include_once($eF); ?>

            </div>
          </section>
        </div>
      </section>
    </div>
    <footer class="main-footer">
      <div class="pull-right hidden-xs">
        <b>Version</b> 2.0
      </div>
      <strong>Copyright &copy; 2019 Portal SAS<a href="http://almsaeedstudio.com"> </a>
    </footer>
  </div>

  <!--
<body leftmargin="0" rightmarin="0" topmargin="0">
<? $eF = getForm($_GET["form"]); ?>
<center>
<table width="800" height="100%" cellpadding="0" cellspacing="0" border="0">
	<tr>
		<td align="left" width="1%"><img src="../bit_images/telkom.jpg"></td>
		<td align="left" valign="bottom"><? include('mod/mod_fullmenu.php') ?></td>
	</tr>
	<tr>
		<td colspan="2" height="2" class="ThemeOfficeMenuItem" align="center"></td>
	</tr>
	<tr>
		<td colspan="2" valign="top" align="left"><? include_once($eF) ?></td>
	</tr>
	<tr>
		<td height="2" colspan="2" class="ThemeOfficeMenuItem" align="center"></td>
	</tr>
	<tr>
		<td height="7" colspan="2" align="center"><? echo "Copyright @Telkom 2010" ?></td>
	</tr>
</table>	
</center>
//-->

  <script>
    function showColorbox(url) {
      $.fn.colorbox({
        iframe: true,
        width: '90%',
        height: '90%',
        innerWidth: '90%',
        inline: false,
        scrolling: true,
        href: url
      });
    }
  </script>

  <script src="../bit_css/lte/plugins/jQuery/jquery-2.2.3.min.js"></script>
  <script src="https://code.jquery.com/ui/1.11.4/jquery-ui.min.js"></script>
  <script>
    $.widget.bridge('uibutton', $.ui.button);
  </script>
  <script src="../bit_css/lte/bootstrap/js/bootstrap.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
  <script src="../bit_css/lte/plugins/morris/morris.min.js"></script>
  <script src="../bit_css/lte/plugins/sparkline/jquery.sparkline.min.js"></script>
  <script src="../bit_css/lte/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"></script>
  <script src="../bit_css/lte/plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script>
  <script src="../bit_css/lte/plugins/knob/jquery.knob.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.11.2/moment.min.js"></script>
  <script src="../bit_css/lte/plugins/daterangepicker/daterangepicker.js"></script>
  <script src="../bit_css/lte/plugins/datepicker/bootstrap-datepicker.js"></script>
  <script src="../bit_css/lte/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script>
  <script src="../bit_css/lte/plugins/slimScroll/jquery.slimscroll.min.js"></script>
  <script src="../bit_css/lte/plugins/fastclick/fastclick.js"></script>
  <script src="../bit_css/lte/dist/js/app.min.js"></script>
  <script src="../bit_css/lte/dist/js/pages/dashboard.js"></script>
  <script src="../bit_css/lte/dist/js/demo.js"></script>


  <script type="text/javascript" src="../bit_third/colorbox/colorbox/example1/1.3.2/jquery.min.js"></script>
  <script type="text/javascript" src="../bit_third/colorbox/colorbox/colorbox/jquery.colorbox.js"></script>


  <!--
<script src="../bit_css/timeline/js/modernizr.js"></script>
<script src="../bit_css/timeline/js/main.js"></script>      
//-->
</body>

</html>