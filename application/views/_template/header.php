<!DOCTYPE html>
<html lang="en">

<head>
  <title>FAST PRINT INDONESIA | <?= $title ?></title>
  <!-- Meta -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="description" content="FAST PRINT INDONESIA" />
  <meta name="keywords" content="" />
  <meta name="author" content="Meingga Project">

  <meta http-equiv='cache-control' content='no-cache'>
  <meta http-equiv='expires' content='0'>
  <meta http-equiv='pragma' content='no-cache'>
  <!-- Favicon icon -->
  <link rel="icon" href="https://fastprint.co.id/cdn/shop/t/3/assets/favicon.png?v=81420275038906957561489805554" type="image/x-icon">
  <!-- Google font-->
  <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600" rel="stylesheet">

  <!-- Required Fremwork -->
  <link rel="stylesheet" type="text/css" href="<?= base_url(); ?>assets/css/bootstrap.min.css">

  <!-- themify-icons line icon -->
  <link rel="stylesheet" type="text/css" href="<?= base_url() ?>assets/css/themify-icons/themify-icons.css">
  <link rel="stylesheet" type="text/css" href="<?= base_url(); ?>assets/css/font-awesome/css/font-awesome.min.css">

  <!-- Style.css -->
  <link rel="stylesheet" type="text/css" href="<?= base_url() ?>assets/css/style.css">
  <link rel="stylesheet" type="text/css" href="<?= base_url() ?>assets/css/jquery.mCustomScrollbar.css">
  <link rel="stylesheet" type="text/css" href="<?= base_url() ?>assets/css/datatables.min.css" />

  <link rel="stylesheet" type="text/css" href="<?= base_url() ?>assets/css/app.css">
</head>

<body>

  <body>
    <!-- Pre-loader start -->
    <div class="theme-loader">
      <div class="loader-track">
        <div class="loader-bar"></div>
      </div>
    </div>
    <!-- Pre-loader end -->

    <div id="pcoded" class="pcoded">
      <div class="pcoded-overlay-box"></div>
      <div class="pcoded-container navbar-wrapper">

        <nav class="navbar header-navbar pcoded-header">
          <div class="navbar-wrapper">
            <div class="navbar-logo">
              <a class="mobile-menu" id="mobile-collapse" href="#!">
                <i class="ti-menu" style="color:#000"></i>
              </a>
              <a href="<?= base_url() ?>" class="mx-auto d-none d-lg-block" style="width: 80%;">
                <img class="img-fluid" src="https://fastprint.co.id/cdn/shop/t/3/assets/logo.png?v=37021879728213879011522638925" alt="Theme-Logo" />
              </a>
            </div>

            <div class="navbar-container container-fluid">
              <ul class="nav-left">
                <li>
                  <div class="sidebar_toggle"><a href="javascript:void(0)"><i class="ti-menu" style="color:#000"></i></a></div>
                </li>
              </ul>

              <ul class="nav-right">
                <li style="color: #fff; font-size: 16px;">
                  <span id="date-now"></span>
                </li>
              </ul>
            </div>
          </div>
        </nav>