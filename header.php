<?php
session_start();
include_once("bit_config.php");

// if (1 == 1) {
//     $param = 'onLoad="init();"';
// } else {
//     $param = '';
// }

?>
<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title><?php echo $bit_app["title"] ?></title>
    <link rel="shortcut icon" href="<?php echo $bit_app["path_url"]; ?>bit_images/favicon1.png" type="image/x-icon" />
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="<?php echo $bit_app["path_url"]; ?>bit_css/bootstrap.min.css">
    <!-- animate CSS -->
    <link rel="stylesheet" href="<?php echo $bit_app["path_url"]; ?>bit_css/animate.css">
    <!-- owl carousel CSS -->
    <link rel="stylesheet" href="<?php echo $bit_app["path_url"]; ?>bit_css/owl.carousel.min.css">
    <!-- themify CSS -->
    <link rel="stylesheet" href="<?php echo $bit_app["path_url"]; ?>bit_css/themify-icons.css">
    <!-- flaticon CSS -->
    <link rel="stylesheet" href="<?php echo $bit_app["path_url"]; ?>bit_css/flaticon.css">
    <!-- font awesome CSS -->
    <link rel="stylesheet" href="<?php echo $bit_app["path_url"]; ?>bit_css/magnific-popup.css">
    <link rel="stylesheet" href="<?php echo $bit_app["path_url"]; ?>bit_css/fontawesome-free/css/all.min.css">
    <!-- swiper CSS -->
    <link rel="stylesheet" href="<?php echo $bit_app["path_url"]; ?>bit_css/slick.css">
    <!-- colorbox -->
    <link rel="stylesheet" href="<?php echo $bit_app["path_url"]; ?>bit_css/colorbox.css">
    <!-- pagination -->
    <link rel="stylesheet" href="<?php echo $bit_app["path_url"]; ?>bit_css/simplePagination.css">
    <!-- media element CSS -->
    <link rel="stylesheet" href="<?php echo $bit_app["path_url"]; ?>mediaelement-master/build/mediaelementplayer.min.css">
    <!-- jquery ui -->
    <link rel="stylesheet" href="<?php echo $bit_app["path_url"]; ?>bit_css/jquery-ui.css">
    <!-- style CSS -->
    <link rel="stylesheet" href="<?php echo $bit_app["path_url"]; ?>bit_css/style.css">
    <link rel="stylesheet" href="<?php echo $bit_app["path_url"]; ?>bit_css/toggle-switch.css">
    <!-- font -->
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Gentium+Book+Basic&display=swap" rel="stylesheet">
    <!-- jquery -->
    <script src="bit_js/jquery-1.12.1.min.js"></script>
    <script src="bit_js/jquery.colorbox-min.js"></script>
    <script src="bit_js/pagination.min.js"></script>
    <!-- jquery ui -->
    <script src="bit_js/jquery-ui.min.js"></script>

    <style>
        @font-face {
            font-family: 'norwesterregular';
            src: url('bit_css/fonts/norwester/norwester-webfont.woff2') format('woff2'),
                url('bit_css/fonts/norwester/norwester-webfont.woff') format('woff');
            font-weight: normal;
            font-style: normal;
        }

        @font-face {
            font-family: 'impact';
            src: url('bit_css/fonts/impact.woff') format('woff');
            font-weight: normal;
            font-style: normal;
        }

        #sasis {
            font-family: 'impact';
            font-size: 50px;
            margin-bottom: 0px;
        }

        #safety {
            font-family: 'Gentium Book Basic', serif;
            font-size: 15px;
            font-weight: bold;
            font-style: italic;
            color: #19c282;
            text-shadow: 0.2px 0.2px 0.5px #bdbdbd;
        }

        #tools {
            font-family: Calibri;
            line-height: normal;
            margin-bottom: 15px;
        }
    </style>


</head>

<body <?php echo $param; ?>>
    <?php
    $ora = new clsMysql;
    $ora->logon($bit_app["user_db"], $bit_app["pass_db"]);

    checklogin();
    $dtUser = getUserDB();
    ?>

    <header class="main_menu home_menu">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-12">
                    <nav class="navbar navbar-expand-lg navbar-light">
                        <a class="navbar-brand" href="index.html"> <img src="bit_css/img/telkom1.png" alt="logo" width="100"> </a>
                        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                            <span class="navbar-toggler-icon"></span>
                        </button>

                        <div class="collapse navbar-collapse main-menu-item justify-content-end" id="navbarSupportedContent">
                            <ul class="navbar-nav align-items-center">

                                <!--
                                <li class="nav-item active">
                                    <a class="nav-link" href="<?php echo $bit_app["path_url"]; ?>">Home</a>
                                </li>
								//-->

                                <?php require_once("bit_third/menu/menu_data.php"); ?>

                                <!--
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" href="blog.html" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        Aplikasi
                                    </a>
                                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                        <a class="dropdown-item" href="https://localhost/smk3Ci/" target="_blank">SMK3</a>
                                        <a class="dropdown-item" href="https://localhost/telkom-safetycare/" target="_blank">Safety Care</a>
										<a class="dropdown-item" href="https://localhost/telkom-vandalisme/" target="_blank">Vandalisme</a>
										<a class="dropdown-item" href="https://localhost/assets-protection/" target="_blank">Asset Protection</a>
                                    </div>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="blog.html">Informasi</a>
                                </li>
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" href="#">Kebijakan</a>
                                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                        <a class="dropdown-item" href="https://localhost/smk3Ci/" target="_blank">SMK3</a>
                                        <ul>
                                            <li><a class="nav-link">test menu</a></li>
                                        </ul>
                                    </div>
                                </li>                                
                                //-->

                                <?php
                                if (getUserID())
                                    getUserInfo();
                                ?>


                                <li class="d-none d-lg-block">
                                </li>

                            </ul>
                        </div>
                    </nav>
                </div>
            </div>
        </div>
    </header>