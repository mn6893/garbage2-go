<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="shortcut icon" href="<?= base_url('style/images/favicon.png') ?>">
  <title><?= isset($title) ? $title : 'Snowlake' ?></title>
  <link rel="stylesheet" type="text/css" href="<?= base_url('style/css/bootstrap.min.css') ?>">
  <link rel="stylesheet" type="text/css" href="<?= base_url('style/css/plugins.css') ?>">
  <link rel="stylesheet" type="text/css" href="<?= base_url('style/revolution/css/settings.css') ?>">
  <link rel="stylesheet" type="text/css" href="<?= base_url('style/revolution/css/layers.css') ?>">
  <link rel="stylesheet" type="text/css" href="<?= base_url('style/revolution/css/navigation.css') ?>">
  <link rel="stylesheet" type="text/css" href="<?= base_url('style/type/type.css') ?>">
  <link rel="stylesheet" type="text/css" href="<?= base_url('style/css/style.css') ?>">
  <link rel="stylesheet" type="text/css" href="<?= base_url('style/css/color/purple.css') ?>">
</head>
<body>
  <div class="content-wrapper">
    <nav class="navbar absolute transparent inverse-text navbar-hover-opacity navbar-expand-lg">
      <div class="container">
        <div class="navbar-brand">
          <a href="<?= base_url() ?>">
            <span class="logo-text color-default" style="font-size: 2rem; font-weight: bold; font-style: italic;">
              garbage<span style="color: #003366; font-weight: bold; font-style: italic;">2go</span>
            </span>
          </a>
        </div>
        <div class="navbar-other ml-auto order-lg-3">
          <ul class="navbar-nav flex-row align-items-center" data-sm-skip="true">
            <li class="nav-item">
              <div class="navbar-hamburger d-lg-none d-xl-none ml-auto"><button class="hamburger animate plain" data-toggle="offcanvas-nav"><span></span></button></div>
            </li>
            <li class="nav-item"><button class="plain" data-toggle="offcanvas-info"><i class="jam jam-info"></i></button></li>
          </ul>
          <!-- /.navbar-nav -->
        </div>
        <!-- /.navbar-other -->
        <div class="navbar-collapse offcanvas-nav">
          <div class="offcanvas-header d-lg-none d-xl-none">
            <a href="<?= base_url() ?>"><img src="#" srcset="<?= base_url('style/images/logo-light.png') ?> 1x, <?= base_url('style/images/logo-light@2x.png') ?> 2x" alt="" /></a>
            <button class="plain offcanvas-close offcanvas-nav-close"><i class="jam jam-close"></i></button>
          </div>
          <ul class="navbar-nav ml-auto">
            <li class="nav-item"><a class="nav-link" href="<?= base_url() ?>">Home</a></li>
            <li class="nav-item"><a class="nav-link" href="<?= base_url('about') ?>">About</a></li>
            <li class="nav-item"><a class="nav-link" href="<?= base_url('services') ?>">Services</a></li>
            <li class="nav-item"><a class="nav-link" href="<?= base_url('contact') ?>">Contact</a></li>
          </ul>
          <!-- /.navbar-nav -->
        </div>
        <!-- /.navbar-collapse -->
      </div>
      <!-- /.container -->
    </nav>
    <!-- /.navbar -->
    <div class="offcanvas-info inverse-text">
      <button class="plain offcanvas-close offcanvas-info-close"><i class="jam jam-close"></i></button>
      <a href="<?= base_url() ?>"><img src="#" srcset="<?= base_url('style/images/logo-light.png') ?> 1x, <?= base_url('style/images/logo-light@2x.png') ?> 2x" alt="" /></a>
      <div class="space30"></div>
      <p>We specialize in web & mobile design and development.</p>
      <div class="widget">
        <h5 class="widget-title">Contact Info</h5>
        <address>Moonshine St. 14/05 <br class="d-none d-lg-block" />Light City, London</address>
        <a href="mailto:first.last@email.com">info@email.com</a><br /> +00 (123) 456 78 90
      </div>
      <!-- /.widget -->
      <div class="widget">
        <h5 class="widget-title">Learn More</h5>
        <ul class="list-unstyled">
          <li><a href="#" class="sublink">Our Story</a></li>
          <li><a href="#" class="sublink">Terms of Use</a></li>
          <li><a href="#" class="sublink">Privacy Policy</a></li>
          <li><a href="#" class="sublink">Contact Us</a></li>
        </ul>
      </div>
      <!-- /.widget -->
    </div>
    <!-- /.offcanvas-info -->