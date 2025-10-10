<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="shortcut icon" href="<?= base_url('style/images/favicon.png') ?>">
  <title><?= isset($title) ? $title . ' - ' : '' ?>Admin Panel - GarbageToGo</title>
  <link rel="stylesheet" type="text/css" href="<?= base_url('style/css/bootstrap.min.css') ?>">
  <link rel="stylesheet" type="text/css" href="<?= base_url('style/css/plugins.css') ?>">
  <link rel="stylesheet" type="text/css" href="<?= base_url('style/type/type.css') ?>">
  <link rel="stylesheet" type="text/css" href="<?= base_url('style/css/style.css') ?>">
  <link rel="stylesheet" type="text/css" href="<?= base_url('style/css/color/purple.css') ?>">
</head>
<body>
  <div class="content-wrapper">
    <!-- Admin Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light border-bottom">
      <div class="container-fluid">
        <a class="navbar-brand" href="<?= site_url('admin/dashboard') ?>">
          <i class="icofont-dashboard mr-10"></i>
          <strong>GarbageToGo Admin</strong>
        </a>
        
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#adminNav">
          <span class="navbar-toggler-icon"></span>
        </button>
        
        <div class="collapse navbar-collapse" id="adminNav">
          <ul class="navbar-nav me-auto">
            <li class="nav-item">
              <a class="nav-link <?= (current_url() == site_url('admin/dashboard')) ? 'active' : '' ?>" 
                 href="<?= site_url('admin/dashboard') ?>">
                <i class="icofont-dashboard mr-5"></i> Dashboard
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link <?= (strpos(current_url(), 'admin/quotes') !== false) ? 'active' : '' ?>" 
                 href="<?= site_url('admin/quotes') ?>">
                <i class="icofont-file-document mr-5"></i> Quotes
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link <?= (strpos(current_url(), 'admin/contacts') !== false) ? 'active' : '' ?>" 
                 href="<?= site_url('admin/contacts') ?>">
                <i class="icofont-envelope mr-5"></i> Contacts
              </a>
            </li>
          </ul>
          
          <ul class="navbar-nav">
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" href="#" id="adminDropdown" role="button" data-bs-toggle="dropdown">
                <i class="icofont-user-alt-4 mr-5"></i> <?= session()->get('admin_username') ?>
              </a>
              <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="<?= site_url('/') ?>" target="_blank">
                  <i class="icofont-external-link mr-5"></i> View Website
                </a></li>
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item" href="<?= site_url('admin/logout') ?>">
                  <i class="icofont-logout mr-5"></i> Logout
                </a></li>
              </ul>
            </li>
          </ul>
        </div>
      </div>
    </nav>