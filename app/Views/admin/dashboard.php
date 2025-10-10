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
                  <div class="col-3">
                    <small class="text-muted">Contacted: <strong><?= $quoteStats['contacted'] ?></strong></small>
                  </div>
                  <div class="col-3">
                    <small class="text-muted">Quoted: <strong><?= $quoteStats['quoted'] ?></strong></small>
                  </div>
                  <div class="col-3">
                    <small class="text-muted">Completed: <strong><?= $quoteStats['completed'] ?></strong></small>
                  </div>
                  <div class="col-3">
                    <a href="<?= site_url('admin/quotes') ?>" class="btn btn-sm btn-outline-purple">Manage</a>
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
                    <a href="<?= site_url('admin/contacts') ?>" class="btn btn-sm btn-outline-green">Manage</a>
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
                              <a href="<?= site_url('admin/quote/' . $quote['id']) ?>" 
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
                              <a href="<?= site_url('admin/contact/' . $contact['id']) ?>" 
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

<?= $this->include('admin/footer'); ?>