<?= $this->include('admin/header'); ?>

<div class="wrapper light-wrapper">
  <div class="container-fluid">
    <div class="row">
      <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-30">
          <h2 class="section-title mb-0">Contact Management</h2>
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="<?= site_url('admin/dashboard') ?>">Dashboard</a></li>
              <li class="breadcrumb-item active">Contacts</li>
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
                  <option value="new" <?= $currentStatus === 'new' ? 'selected' : '' ?>>New</option>
                  <option value="replied" <?= $currentStatus === 'replied' ? 'selected' : '' ?>>Replied</option>
                  <option value="resolved" <?= $currentStatus === 'resolved' ? 'selected' : '' ?>>Resolved</option>
                </select>
              </div>
              <div class="col-md-4">
                <label class="form-label">Search</label>
                <input type="text" name="search" class="form-control" 
                       placeholder="Search by name, email, service type, or message" 
                       value="<?= esc($searchTerm) ?>">
              </div>
              <div class="col-md-3">
                <button type="submit" class="btn btn-purple">
                  <i class="icofont-search mr-5"></i> Filter
                </button>
                <a href="<?= site_url('admin/contacts') ?>" class="btn btn-outline-default">
                  <i class="icofont-refresh mr-5"></i> Reset
                </a>
              </div>
              <div class="col-md-2 text-end">
                <small class="text-muted"><?= count($contacts) ?> contacts found</small>
              </div>
            </form>
          </div>
        </div>
        
        <!-- Contacts Table -->
        <div class="card">
          <div class="card-body">
            <?php if (empty($contacts)): ?>
                <div class="text-center py-40">
                  <i class="icofont-envelope fs-48 color-muted mb-15"></i>
                  <h5>No contacts found</h5>
                  <p class="text-muted">No contacts match your current filters.</p>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                  <table class="table">
                    <thead>
                      <tr>
                        <th>ID</th>
                        <th>Contact Details</th>
                        <th>Service Type</th>
                        <th>Message</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th>Actions</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php foreach ($contacts as $contact): ?>
                      <tr>
                        <td><strong>#<?= $contact['id'] ?></strong></td>
                        <td>
                          <strong><?= esc($contact['name']) ?></strong><br>
                          <small class="text-muted">
                            <i class="icofont-envelope mr-5"></i><?= esc($contact['email']) ?><br>
                            <i class="icofont-phone mr-5"></i><?= esc($contact['phone']) ?>
                          </small>
                        </td>
                        <td>
                          <strong><?= esc(substr($contact['service_type'] ?? 'General Inquiry', 0, 30)) ?><?= strlen($contact['service_type'] ?? 'General Inquiry') > 30 ? '...' : '' ?></strong>
                        </td>
                        <td>
                          <small><?= esc(substr($contact['message'], 0, 60)) ?><?= strlen($contact['message']) > 60 ? '...' : '' ?></small>
                        </td>
                        <td>
                          <form method="post" action="<?= site_url('admin/contact/update-status') ?>" class="d-inline">
                            <?= csrf_field() ?>
                            <input type="hidden" name="id" value="<?= $contact['id'] ?>">
                            <select name="status" class="form-control form-control-sm status-update" onchange="this.form.submit()">
                              <option value="new" <?= $contact['status'] === 'new' ? 'selected' : '' ?>>New</option>
                              <option value="replied" <?= $contact['status'] === 'replied' ? 'selected' : '' ?>>Replied</option>
                              <option value="resolved" <?= $contact['status'] === 'resolved' ? 'selected' : '' ?>>Resolved</option>
                            </select>
                          </form>
                        </td>
                        <td>
                          <small>
                            <?= date('M j, Y', strtotime($contact['created_at'])) ?><br>
                            <?= date('g:i A', strtotime($contact['created_at'])) ?>
                          </small>
                        </td>
                        <td>
                          <a href="<?= site_url('admin/contact/' . $contact['id']) ?>" 
                             class="btn btn-sm btn-outline-default" title="View Details">
                            <i class="icofont-eye"></i>
                          </a>
                          <a href="mailto:<?= esc($contact['email']) ?>?subject=Re: <?= esc($contact['service_type'] ?? 'Your Inquiry') ?>" 
                             class="btn btn-sm btn-outline-purple" title="Reply via Email">
                            <i class="icofont-envelope"></i>
                          </a>
                          <a href="tel:<?= esc($contact['phone']) ?>" 
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