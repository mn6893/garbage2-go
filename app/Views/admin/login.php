<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="shortcut icon" href="<?= base_url('style/images/favicon.png') ?>">
  <title>Admin Login - GarbageToGo</title>
  <link rel="stylesheet" type="text/css" href="<?= base_url('style/css/bootstrap.min.css') ?>">
  <link rel="stylesheet" type="text/css" href="<?= base_url('style/css/plugins.css') ?>">
  <link rel="stylesheet" type="text/css" href="<?= base_url('style/type/type.css') ?>">
  <link rel="stylesheet" type="text/css" href="<?= base_url('style/css/style.css') ?>">
  <link rel="stylesheet" type="text/css" href="<?= base_url('style/css/color/purple.css') ?>">
</head>
<body>
  <div class="content-wrapper">
    <div class="wrapper light-wrapper">
      <div class="container">
        <div class="row justify-content-center">
          <div class="col-lg-6 col-xl-5">
            <div class="card mt-80 mb-80">
              <div class="card-body p-40">
                <div class="text-center mb-30">
                  <h2 class="color-default">Admin Login</h2>
                  <p class="lead">Access GarbageToGo Admin Panel</p>
                </div>
                
                <?php if (session()->getFlashdata('error')): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <?= session()->getFlashdata('error') ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>
                
                <?php if (session()->getFlashdata('success')): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <?= session()->getFlashdata('success') ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>
                
                <?php if (service('request')->getGet('timeout')): ?>
                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                        Your session has expired. Please login again.
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>
                
                <form method="post" action="<?= base_url('admin/authenticate') ?>">
                  <?= csrf_field() ?>
                  
                  <div class="form-group mb-20">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" class="form-control" id="username" name="username" 
                           placeholder="Enter username" required autofocus>
                  </div>
                  
                  <div class="form-group mb-30">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" name="password" 
                           placeholder="Enter password" required>
                  </div>
                  
                  <div class="text-center">
                    <button type="submit" class="btn btn-default btn-block">
                      <i class="icofont-lock mr-5"></i> Login to Admin Panel
                    </button>
                  </div>
                </form>
                
                <div class="text-center mt-30">
                  <small class="text-muted">
                    <i class="icofont-shield mr-5"></i>
                    Secure Admin Access Only
                  </small>
                </div>
                
                <div class="text-center mt-20">
                  <a href="<?= base_url('/') ?>" class="btn btn-sm btn-outline-default">
                    <i class="icofont-arrow-left mr-5"></i> Back to Website
                  </a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  
  <script src="<?= base_url('style/js/jquery.min.js') ?>"></script>
  <script src="<?= base_url('style/js/bootstrap.min.js') ?>"></script>
  <script src="<?= base_url('style/js/plugins.js') ?>"></script>
  <script src="<?= base_url('style/js/scripts.js') ?>"></script>
</body>
</html>