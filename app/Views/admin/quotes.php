<?= $this->include('admin/header'); ?>

<div class="wrapper light-wrapper">
  <div class="container-fluid">
    <div class="row">
      <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-30">
          <h2 class="section-title mb-0">Quote Management</h2>
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="<?= site_url('admin/dashboard') ?>">Dashboard</a></li>
              <li class="breadcrumb-item active">Quotes</li>
            </ol>
          </nav>
        </div>
        
        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?= session()->getFlashdata('success') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
        
        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?= session()->getFlashdata('error') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
        
        <!-- Filters -->
        <div class="card mb-20">
          <div class="card-body">
            <form method="get" class="row align-items-end">
              <div class="col-md-3">
                <label class="form-label">Filter by Status</label>
                <select name="status" class="form-control">
                  <option value="">All Statuses</option>
                  <option value="pending" <?= $currentStatus === 'pending' ? 'selected' : '' ?>>Pending</option>
                  <option value="contacted" <?= $currentStatus === 'contacted' ? 'selected' : '' ?>>Contacted</option>
                  <option value="quoted" <?= $currentStatus === 'quoted' ? 'selected' : '' ?>>Quoted</option>
                  <option value="accepted" <?= $currentStatus === 'accepted' ? 'selected' : '' ?>>Accepted</option>
                  <option value="rejected" <?= $currentStatus === 'rejected' ? 'selected' : '' ?>>Rejected</option>
                  <option value="completed" <?= $currentStatus === 'completed' ? 'selected' : '' ?>>Completed</option>
                </select>
              </div>
              <div class="col-md-4">
                <label class="form-label">Search</label>
                <input type="text" name="search" class="form-control" 
                       placeholder="Search by name, email, phone, or address" 
                       value="<?= esc($searchTerm) ?>">
              </div>
              <div class="col-md-3">
                <button type="submit" class="btn btn-purple">
                  <i class="icofont-search mr-5"></i> Filter
                </button>
                <a href="<?= site_url('admin/quotes') ?>" class="btn btn-outline-default">
                  <i class="icofont-refresh mr-5"></i> Reset
                </a>
              </div>
              <div class="col-md-2 text-end">
                <small class="text-muted"><?= count($quotes) ?> quotes found</small>
              </div>
            </form>
          </div>
        </div>
        
        <!-- Quotes Table -->
        <div class="card">
          <div class="card-body">
            <?php if (empty($quotes)): ?>
                <div class="text-center py-40">
                  <i class="icofont-file-document fs-48 color-muted mb-15"></i>
                  <h5>No quotes found</h5>
                  <p class="text-muted">No quotes match your current filters.</p>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                  <table class="table">
                    <thead>
                      <tr>
                        <th>ID</th>
                        <th>Customer Details</th>
                        <th>Address</th>
                        <th>Description</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th>Actions</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php foreach ($quotes as $quote): ?>
                      <tr>
                        <td><strong>#<?= $quote['id'] ?></strong></td>
                        <td>
                          <strong><?= esc($quote['name']) ?></strong><br>
                          <small class="text-muted">
                            <i class="icofont-envelope mr-5"></i><?= esc($quote['email']) ?><br>
                            <i class="icofont-phone mr-5"></i><?= esc($quote['phone']) ?>
                            <?php if (!empty($quote['city'])): ?>
                              <br><i class="icofont-location-pin mr-5"></i><?= esc($quote['city']) ?>
                            <?php endif; ?>
                          </small>
                        </td>
                        <td>
                          <small><?= esc(substr($quote['address'], 0, 50)) ?><?= strlen($quote['address']) > 50 ? '...' : '' ?></small>
                        </td>
                        <td>
                          <small><?= esc(substr($quote['description'], 0, 60)) ?><?= strlen($quote['description']) > 60 ? '...' : '' ?></small>
                        </td>
                        <td>
                          <form method="post" action="<?= site_url('admin/quote/update-status') ?>" class="d-inline">
                            <?= csrf_field() ?>
                            <input type="hidden" name="id" value="<?= $quote['id'] ?>">
                            <select name="status" class="form-control form-control-sm status-update" onchange="this.form.submit()">
                              <option value="pending" <?= $quote['status'] === 'pending' ? 'selected' : '' ?>>Pending</option>
                              <option value="contacted" <?= $quote['status'] === 'contacted' ? 'selected' : '' ?>>Contacted</option>
                              <option value="quoted" <?= $quote['status'] === 'quoted' ? 'selected' : '' ?>>Quoted</option>
                              <option value="accepted" <?= $quote['status'] === 'accepted' ? 'selected' : '' ?>>Accepted</option>
                              <option value="rejected" <?= $quote['status'] === 'rejected' ? 'selected' : '' ?>>Rejected</option>
                              <option value="completed" <?= $quote['status'] === 'completed' ? 'selected' : '' ?>>Completed</option>
                            </select>
                          </form>
                        </td>
                        <td>
                          <small>
                            <?= date('M j, Y', strtotime($quote['created_at'])) ?><br>
                            <?= date('g:i A', strtotime($quote['created_at'])) ?>
                          </small>
                        </td>
                        <td>
                          <a href="<?= site_url('admin/quote/' . $quote['id']) ?>" 
                             class="btn btn-sm btn-outline-default" title="View Details">
                            <i class="icofont-eye"></i>
                          </a>
                          <a href="mailto:<?= esc($quote['email']) ?>?subject=Quote Request #<?= $quote['id'] ?>" 
                             class="btn btn-sm btn-outline-purple" title="Send Email">
                            <i class="icofont-envelope"></i>
                          </a>
                          <a href="tel:<?= esc($quote['phone']) ?>" 
                             class="btn btn-sm btn-outline-green" title="Call Customer">
                            <i class="icofont-phone"></i>
                          </a>
                        </td>
                      </tr>
                      <?php endforeach; ?>
                    </tbody>
                  </table>
                </div>
            <?php endif; ?>
          </div>
        </div>
        
      </div>
    </div>
  </div>
</div>

<?= $this->include('admin/footer'); ?>