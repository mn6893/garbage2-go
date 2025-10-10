  </div>
  <!-- /.content-wrapper -->
  
  <!-- Footer -->
  <footer class="bg-light border-top mt-auto py-3">
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-6">
          <p class="mb-0 text-muted">
            <small>&copy; <?= date('Y') ?> GarbageToGo Admin Panel. All rights reserved.</small>
          </p>
        </div>
        <div class="col-md-6 text-end">
          <p class="mb-0 text-muted">
            <small>
              <i class="icofont-clock-time mr-5"></i>
              Last login: <?= date('M j, Y g:i A', session()->get('admin_login_time')) ?>
            </small>
          </p>
        </div>
      </div>
    </div>
  </footer>
  
  <script src="<?= base_url('style/js/jquery.min.js') ?>"></script>
  <script src="<?= base_url('style/js/bootstrap.min.js') ?>"></script>
  <script src="<?= base_url('style/js/plugins.js') ?>"></script>
  <script src="<?= base_url('style/js/scripts.js') ?>"></script>
  
  <!-- Admin specific scripts -->
  <script>
    // Auto-refresh dashboard every 5 minutes
    if (window.location.pathname.includes('admin/dashboard')) {
      setTimeout(() => {
        window.location.reload();
      }, 300000); // 5 minutes
    }
    
    // Confirm status changes
    $('.status-update').on('change', function() {
      if (confirm('Are you sure you want to update this status?')) {
        $(this).closest('form').submit();
      } else {
        $(this).val($(this).data('original'));
      }
    });
    
    // Store original values for status selects
    $('.status-update').each(function() {
      $(this).data('original', $(this).val());
    });
  </script>
</body>
</html>