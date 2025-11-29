<!DOCTYPE html>
<html lang="en">
<head>
  <!-- Google tag (gtag.js) -->
  <script async src="https://www.googletagmanager.com/gtag/js?id=G-16C7V13M57"></script>
  <script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());
    gtag('config', 'G-16C7V13M57');
  </script>

  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="shortcut icon" href="<?= base_url('style/images/favicon.png') ?>">
  <title><?= isset($title) ? $title : 'GarbageToGo' ?></title>
  <link rel="stylesheet" type="text/css" href="<?= base_url('style/css/bootstrap.min.css') ?>">
  <link rel="stylesheet" type="text/css" href="<?= base_url('style/css/plugins.css') ?>">
  <link rel="stylesheet" type="text/css" href="<?= base_url('style/type/type.css') ?>">
  <link rel="stylesheet" type="text/css" href="<?= base_url('style/css/style.css') ?>">
  <link rel="stylesheet" type="text/css" href="<?= base_url('style/css/color/purple.css') ?>">
  <!-- Google tag (gtag.js) -->
  <script async src="https://www.googletagmanager.com/gtag/js?id=G-16C7V13M57"></script>
  <script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());
    gtag('config', 'G-16C7V13M57');
  </script>
  <style>
    .header-call-btn:hover {
      transform: translateY(-2px);
      box-shadow: 0 6px 20px rgba(40, 167, 69, 0.5) !important;
      color: #fff !important;
    }
  </style>
</head>
<body>
  <div class="content-wrapper">
    <nav class="navbar <?= isset($navbar_class) ? $navbar_class : 'navbar-fancy absolute nav-uppercase navbar-expand-lg flex-column' ?>">
      <div class="container">
        <div class="navbar-brand">
          <a href="<?= base_url() ?>">
        <img src="<?= base_url('style/images/logo.png') ?>" alt="GarbageToGo Logo" style="height: 60px; margin-right: -4px;" />
        <span class="logo-text" style="font-size: 1rem; font-weight: bold; font-style: italic; color: green;">
          Garbage<span style="color: #28a745; font-weight: bold; font-style: italic;">ToGo</span>
        </span>
      </a></div>
        <div class="navbar-other ml-auto order-lg-3">
          <ul class="navbar-nav flex-row align-items-center" data-sm-skip="true">
            <li class="nav-item">
              <div class="navbar-hamburger d-lg-none d-xl-none ml-auto"><button class="hamburger animate plain" data-toggle="offcanvas-nav"><span></span></button></div>
            </li>

            <li class="nav-item">
              <a href="tel:+16479138775" class="header-call-btn" style="display: inline-flex; align-items: center; gap: 8px; background: linear-gradient(135deg, #014144 0%, #00294a 100%); color: #fff; padding: 10px 20px; border-radius: 50px; font-weight: 600; font-size: 14px; text-decoration: none; box-shadow: 0 4px 15px rgba(40, 167, 69, 0.4); transition: all 0.3s ease;">
                <i class="jam jam-phone" style="font-size: 18px;"></i>
                <span class="d-none d-md-inline">Call Now</span>
              </a>
            </li>
          </ul>
          <!-- /.navbar-nav -->
        </div>
        <!-- /.navbar-other -->
        <div class="navbar-collapse offcanvas-nav">
          <div class="offcanvas-header d-lg-none d-xl-none">
            <a href="<?= base_url() ?>"><img src="#" srcset="<?= base_url('style/images/logo.png') ?> 1x, <?= base_url('style/images/logo.png') ?> 2x" alt="" /></a>
            <button class="plain offcanvas-close offcanvas-nav-close"><i class="jam jam-close"></i></button>
          </div>
          <ul class="navbar-nav ml-auto">
            <li class="nav-item"><a class="nav-link" href="#">Home</a></li>
            <li class="nav-item"><a class="nav-link" href="<?= base_url('about') ?>">About</a></li>
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" href="<?= base_url('services') ?>" data-toggle="dropdown">Services</a>
              <ul class="dropdown-menu">
              <li class="dropdown-item dropdown">
                <a class="dropdown-link dropdown-toggle" href="<?= base_url('services/household-junk-removal') ?>">Household Junk Removal</a>
                <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="<?= base_url('services/garage-junk-removal') ?>">Garage Junk Removal</a></li>
                <li><a class="dropdown-item" href="<?= base_url('services/backyard-clean-up-and-junk-removal') ?>">Backyard Clean-up</a></li>
                <li><a class="dropdown-item" href="<?= base_url('services/mattress-recycling-melbourne') ?>">Mattress Recycling</a></li>
                <li><a class="dropdown-item" href="<?= base_url('services/junk-removal-melbourne') ?>">Junk Removal</a></li>
                </ul>
              </li>
              <li class="dropdown-item dropdown">
                <a class="dropdown-link dropdown-toggle" href="<?= base_url('services/commercial-junk-removal') ?>">Commercial Junk Removal</a>
                <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="<?= base_url('services/real-estate-junk-removal') ?>">Real Estate Junk Removal</a></li>
                <li><a class="dropdown-item" href="<?= base_url('services/end-of-lease-junk-removal') ?>">End of Lease Junk Removal</a></li>
                <li><a class="dropdown-item" href="<?= base_url('services/office-junk-removal') ?>">Office Junk Removal</a></li>
                <li><a class="dropdown-item" href="<?= base_url('services/renovation-junk-removal') ?>">Renovation Junk Removal</a></li>
                <li><a class="dropdown-item" href="<?= base_url('services/retail-merchandise-junk-removal') ?>">Retail Merchandise Junk Removal</a></li>
                <li><a class="dropdown-item" href="<?= base_url('services/worksite-junk-removal') ?>">Worksite Junk Removal</a></li>
                </ul>
              </li>
              <li><a class="dropdown-item" href="<?= base_url('services/deceased-estate-junk-removal') ?>">Deceased Estate Junk Removal</a></li>
              <li><a class="dropdown-item" href="<?= base_url('services/green-waste-removal') ?>">Green Waste Removal</a></li>
              <li><a class="dropdown-item" href="<?= base_url('services/electronic-recycling') ?>">Electronic Recycling</a></li>
              </ul>
            </li>
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown">Locations</a>
              <ul class="dropdown-menu">
              <li><a class="dropdown-item" href="<?= base_url('junk-removal-toronto') ?>">Toronto</a></li>
              <li><a class="dropdown-item" href="<?= base_url('junk-removal-mississauga') ?>">Mississauga</a></li>
              <li><a class="dropdown-item" href="<?= base_url('junk-removal-brampton') ?>">Brampton</a></li>
              <li><a class="dropdown-item" href="<?= base_url('junk-removal-hamilton') ?>">Hamilton</a></li>
              <li><a class="dropdown-item" href="<?= base_url('junk-removal-london') ?>">London</a></li>
              <li><a class="dropdown-item" href="<?= base_url('junk-removal-markham') ?>">Markham</a></li>
              <li><a class="dropdown-item" href="<?= base_url('junk-removal-vaughan') ?>">Vaughan</a></li>
              <li><a class="dropdown-item" href="<?= base_url('junk-removal-kitchener') ?>">Kitchener</a></li>
              </ul>
            </li>
            <li class="nav-item"><a class="nav-link" href="<?= base_url('gallery') ?>">Gallery</a></li>
            <li class="nav-item"><a class="nav-link" href="<?= base_url('contact') ?>">Contact</a></li>
          </ul>
          <!-- /.navbar-nav -->
        </div>
        <!-- /.navbar-collapse -->
      </div>
      <!-- /.container -->
    </nav>
    <!-- /.navbar -->
   