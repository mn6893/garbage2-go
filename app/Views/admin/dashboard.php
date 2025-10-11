<?= $this->include('admin/header'); ?>

<div class="wrapper light-wrapper">
  <div class="container-fluid">
    <div class="row">
      <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-30">
          <h2 class="section-title mb-0">Admin Dashboard</h2>
          <small class="text-muted">Welcome back, <?= session()->get('admin_username') ?>!</small>
        </div>
        
        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?= session()->getFlashdata('success') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
        
        <!-- Statistics Cards -->
        <div class="row mb-40">
          <!-- Quote Statistics -->
          <div class="col-lg-6">
            <div class="card">
              <div class="card-header bg-purple text-white">
                <h5 class="mb-0"><i class="icofont-file-document mr-10"></i>Quote Management</h5>
              </div>
              <div class="card-body">
                <div class="row text-center">
                  <div class="col-4">
                    <div class="p-15">
                      <h3 class="color-purple"><?= $quoteStats['total'] ?></h3>
                      <p class="mb-0">Total Quotes</p>
                    </div>
                  </div>
                  <div class="col-4">
                    <div class="p-15">
                      <h3 class="color-orange"><?= $quoteStats['pending'] ?></h3>
                      <p class="mb-0">Pending</p>
                    </div>
                  </div>
                  <div class="col-4">
                    <div class="p-15">
                      <h3 class="color-green"><?= $quoteStats['today'] ?></h3>
                      <p class="mb-0">Today</p>
                    </div>
                  </div>
                </div>
                <div class="row text-center border-top pt-15">
                  <div class="col-2">
                    <small class="text-muted">Pending: <strong><?= $quoteStats['pending'] ?></strong></small>
                  </div>
                  <div class="col-2">
                    <small class="text-muted">AI Processing: <strong><?= $quoteStats['ai_processing'] ?></strong></small>
                  </div>
                  <div class="col-2">
                    <small class="text-muted">AI Quoted: <strong><?= $quoteStats['ai_quoted'] ?></strong></small>
                  </div>
                  <div class="col-2">
                    <small class="text-muted">Contacted: <strong><?= $quoteStats['contacted'] ?></strong></small>
                  </div>
                  <div class="col-2">
                    <small class="text-muted">Completed: <strong><?= $quoteStats['completed'] ?></strong></small>
                  </div>
                  <div class="col-2">
                    <a href="<?= base_url('admin/quotes') ?>" class="btn btn-sm btn-outline-purple">Manage</a>
                  </div>
                </div>
              </div>
            </div>
          </div>
          
          <!-- Contact Statistics -->
          <div class="col-lg-6">
            <div class="card">
              <div class="card-header bg-green text-white">
                <h5 class="mb-0"><i class="icofont-envelope mr-10"></i>Contact Management</h5>
              </div>
              <div class="card-body">
                <div class="row text-center">
                  <div class="col-4">
                    <div class="p-15">
                      <h3 class="color-green"><?= $contactStats['total'] ?></h3>
                      <p class="mb-0">Total Contacts</p>
                    </div>
                  </div>
                  <div class="col-4">
                    <div class="p-15">
                      <h3 class="color-orange"><?= $contactStats['new'] ?></h3>
                      <p class="mb-0">New</p>
                    </div>
                  </div>
                  <div class="col-4">
                    <div class="p-15">
                      <h3 class="color-blue"><?= $contactStats['today'] ?></h3>
                      <p class="mb-0">Today</p>
                    </div>
                  </div>
                </div>
                <div class="row text-center border-top pt-15">
                  <div class="col-3">
                    <small class="text-muted">Replied: <strong><?= $contactStats['replied'] ?></strong></small>
                  </div>
                  <div class="col-3">
                    <small class="text-muted">Resolved: <strong><?= $contactStats['resolved'] ?></strong></small>
                  </div>
                  <div class="col-3">
                    <!-- Empty for spacing -->
                  </div>
                  <div class="col-3">
                    <a href="<?= base_url('admin/contacts') ?>" class="btn btn-sm btn-outline-green">Manage</a>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        
        <!-- Email Statistics -->
        <div class="row mb-40">
          <div class="col-12">
            <div class="card">
              <div class="card-header bg-warning text-white">
                <h5 class="mb-0"><i class="icofont-email mr-10"></i>Email Status Management</h5>
              </div>
              <div class="card-body">
                <div class="row text-center">
                  <div class="col-md-2">
                    <div class="p-15">
                      <h3 class="color-green"><?= $emailStats['customer_success'] ?></h3>
                      <p class="mb-0">Customer Emails Sent</p>
                    </div>
                  </div>
                  <div class="col-md-2">
                    <div class="p-15">
                      <h3 class="color-red"><?= $emailStats['customer_failed'] ?></h3>
                      <p class="mb-0">Customer Failed</p>
                    </div>
                  </div>
                  <div class="col-md-2">
                    <div class="p-15">
                      <h3 class="color-green"><?= $emailStats['admin_success'] ?></h3>
                      <p class="mb-0">Admin Emails Sent</p>
                    </div>
                  </div>
                  <div class="col-md-2">
                    <div class="p-15">
                      <h3 class="color-red"><?= $emailStats['admin_failed'] ?></h3>
                      <p class="mb-0">Admin Failed</p>
                    </div>
                  </div>
                  <div class="col-md-2">
                    <div class="p-15">
                      <h3 class="color-orange"><?= $emailStats['retry_needed'] ?></h3>
                      <p class="mb-0">Need Retry</p>
                    </div>
                  </div>
                  <div class="col-md-2">
                    <div class="p-15">
                      <?php if ($emailStats['retry_needed'] > 0): ?>
                        <button type="button" class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#bulkRetryModal">
                          Retry Failed
                        </button>
                      <?php else: ?>
                        <span class="badge bg-success">All Good!</span>
                      <?php endif; ?>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        
        <!-- Recent Activity -->
        <div class="row">
          <!-- Recent Quotes -->
          <div class="col-lg-6">
            <div class="card">
              <div class="card-header">
                <h6 class="mb-0">Recent Quotes</h6>
              </div>
              <div class="card-body">
                <?php if (empty($recentQuotes)): ?>
                    <p class="text-muted text-center py-20">No quotes submitted yet.</p>
                <?php else: ?>
                    <div class="table-responsive">
                      <table class="table table-sm">
                        <thead>
                          <tr>
                            <th>Name</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th>Action</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php foreach ($recentQuotes as $quote): ?>
                          <tr>
                            <td>
                              <strong><?= esc($quote['name']) ?></strong><br>
                              <small class="text-muted"><?= esc($quote['email']) ?></small>
                            </td>
                            <td>
                              <span class="badge badge-<?= $quote['status'] === 'pending' ? 'warning' : ($quote['status'] === 'completed' ? 'success' : 'info') ?>">
                                <?= ucfirst($quote['status']) ?>
                              </span>
                            </td>
                            <td>
                              <small><?= date('M j, Y', strtotime($quote['created_at'])) ?></small>
                            </td>
                            <td>
                              <a href="<?= base_url('admin/quote/' . $quote['id']) ?>" 
                                 class="btn btn-sm btn-outline-default">View</a>
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
          
          <!-- Recent Contacts -->
          <div class="col-lg-6">
            <div class="card">
              <div class="card-header">
                <h6 class="mb-0">Recent Contacts</h6>
              </div>
              <div class="card-body">
                <?php if (empty($recentContacts)): ?>
                    <p class="text-muted text-center py-20">No contacts submitted yet.</p>
                <?php else: ?>
                    <div class="table-responsive">
                      <table class="table table-sm">
                        <thead>
                          <tr>
                            <th>Name</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th>Action</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php foreach ($recentContacts as $contact): ?>
                          <tr>
                            <td>
                              <strong><?= esc($contact['name']) ?> <?= esc($contact['surname']) ?></strong><br>
                              <small class="text-muted"><?= esc($contact['email']) ?></small>
                            </td>
                            <td>
                              <span class="badge badge-<?= $contact['status'] === 'new' ? 'warning' : ($contact['status'] === 'resolved' ? 'success' : 'info') ?>">
                                <?= ucfirst($contact['status']) ?>
                              </span>
                            </td>
                            <td>
                              <small><?= date('M j, Y', strtotime($contact['created_at'])) ?></small>
                            </td>
                            <td>
                              <a href="<?= base_url('admin/contact/' . $contact['id']) ?>" 
                                 class="btn btn-sm btn-outline-default">View</a>
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
  </div>
</div>

<!-- Bulk Email Retry Modal -->
<div class="modal fade" id="bulkRetryModal" tabindex="-1" aria-labelledby="bulkRetryModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="bulkRetryModalLabel">Bulk Retry Failed Emails</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="<?= base_url('admin/bulk-retry-emails') ?>" method="post">
        <div class="modal-body">
          <div class="mb-3">
            <label for="email_type" class="form-label">Email Type to Retry</label>
            <select class="form-select" id="email_type" name="email_type" required>
              <option value="both">Both Customer & Admin</option>
              <option value="customer">Customer Emails Only</option>
              <option value="admin">Admin Emails Only</option>
            </select>
          </div>
          <div class="mb-3">
            <label for="max_attempts" class="form-label">Maximum Retry Attempts</label>
            <select class="form-select" id="max_attempts" name="max_attempts">
              <option value="3">3 attempts</option>
              <option value="5">5 attempts</option>
              <option value="10">10 attempts</option>
            </select>
            <div class="form-text">Only quotes below this attempt threshold will be retried.</div>
          </div>
          <div class="alert alert-info">
            <strong>Note:</strong> This will start a background process to retry failed emails. Check back in a few minutes for results.
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-warning">Start Retry Process</button>
        </div>
      </form>
    </div>
  </div>
</div>

<?= $this->include('admin/footer'); ?>