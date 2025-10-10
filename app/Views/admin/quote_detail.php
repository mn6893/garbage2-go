<?= $this->include('admin/header'); ?>

<div class="wrapper light-wrapper">
  <div class="container-fluid">
    <div class="row">
      <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-30">
          <h2 class="section-title mb-0">Quote Details #<?= $quote['id'] ?></h2>
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="<?= site_url('admin/dashboard') ?>">Dashboard</a></li>
              <li class="breadcrumb-item"><a href="<?= site_url('admin/quotes') ?>">Quotes</a></li>
              <li class="breadcrumb-item active">Quote #<?= $quote['id'] ?></li>
            </ol>
          </nav>
        </div>
        
        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?= session()->getFlashdata('success') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
        
        <div class="row">
          <!-- Customer Information -->
          <div class="col-md-6">
            <div class="card mb-20">
              <div class="card-header">
                <h5 class="card-title mb-0">Customer Information</h5>
              </div>
              <div class="card-body">
                <div class="row">
                  <div class="col-6">
                    <p><strong>Name:</strong><br><?= esc($quote['name']) ?></p>
                  </div>
                  <div class="col-6">
                    <p><strong>Email:</strong><br>
                      <a href="mailto:<?= esc($quote['email']) ?>"><?= esc($quote['email']) ?></a>
                    </p>
                  </div>
                  <div class="col-6">
                    <p><strong>Phone:</strong><br>
                      <a href="tel:<?= esc($quote['phone']) ?>"><?= esc($quote['phone']) ?></a>
                    </p>
                  </div>
                  <div class="col-6">
                    <p><strong>City:</strong><br><?= esc($quote['city'] ?? 'Not provided') ?></p>
                  </div>
                </div>
              </div>
            </div>
          </div>
          
          <!-- Status & Timeline -->
          <div class="col-md-6">
            <div class="card mb-20">
              <div class="card-header">
                <h5 class="card-title mb-0">Status & Timeline</h5>
              </div>
              <div class="card-body">
                <form method="post" action="<?= site_url('admin/quote/update-status') ?>">
                  <?= csrf_field() ?>
                  <input type="hidden" name="id" value="<?= $quote['id'] ?>">
                  
                  <div class="mb-15">
                    <label class="form-label">Current Status</label>
                    <select name="status" class="form-control">
                      <option value="pending" <?= $quote['status'] === 'pending' ? 'selected' : '' ?>>Pending</option>
                      <option value="contacted" <?= $quote['status'] === 'contacted' ? 'selected' : '' ?>>Contacted</option>
                      <option value="quoted" <?= $quote['status'] === 'quoted' ? 'selected' : '' ?>>Quoted</option>
                      <option value="accepted" <?= $quote['status'] === 'accepted' ? 'selected' : '' ?>>Accepted</option>
                      <option value="rejected" <?= $quote['status'] === 'rejected' ? 'selected' : '' ?>>Rejected</option>
                      <option value="completed" <?= $quote['status'] === 'completed' ? 'selected' : '' ?>>Completed</option>
                    </select>
                  </div>
                  
                  <button type="submit" class="btn btn-purple">Update Status</button>
                </form>
                
                <hr>
                
                <p><strong>Submitted:</strong><br>
                  <?= date('F j, Y \a\t g:i A', strtotime($quote['created_at'])) ?>
                </p>
                
                <?php if ($quote['updated_at'] && $quote['updated_at'] !== $quote['created_at']): ?>
                <p><strong>Last Updated:</strong><br>
                  <?= date('F j, Y \a\t g:i A', strtotime($quote['updated_at'])) ?>
                </p>
                <?php endif; ?>
              </div>
            </div>
          </div>
        </div>
        
        <!-- Address & Description -->
        <div class="row">
          <div class="col-md-12">
            <div class="card mb-20">
              <div class="card-header">
                <h5 class="card-title mb-0">Service Details</h5>
              </div>
              <div class="card-body">
                <div class="row">
                  <div class="col-md-6">
                    <h6>Address:</h6>
                    <p><?= nl2br(esc($quote['address'])) ?></p>
                  </div>
                  <div class="col-md-6">
                    <h6>Description:</h6>
                    <p><?= nl2br(esc($quote['description'])) ?></p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        
        <!-- Images -->
        <?php if (!empty($quote['images'])): ?>
        <div class="row">
          <div class="col-md-12">
            <div class="card mb-20">
              <div class="card-header">
                <h5 class="card-title mb-0">Uploaded Images</h5>
              </div>
              <div class="card-body">
                <div class="row">
                  <?php 
                  $images = json_decode($quote['images'], true);
                  if (is_array($images)):
                    foreach ($images as $image): 
                      $imagePath = '/uploads/quotes/' . $image;
                  ?>
                    <div class="col-md-3 col-sm-6 mb-15">
                      <div class="image-container">
                        <img src="<?= $imagePath ?>" alt="Quote Image" class="img-fluid rounded" 
                             style="width: 100%; height: 200px; object-fit: cover; cursor: pointer;"
                             onclick="openImageModal('<?= $imagePath ?>')">
                        <div class="mt-10">
                          <a href="<?= $imagePath ?>" download class="btn btn-sm btn-outline-default">
                            <i class="icofont-download mr-5"></i> Download
                          </a>
                        </div>
                      </div>
                    </div>
                  <?php 
                    endforeach;
                  endif; 
                  ?>
                </div>
              </div>
            </div>
          </div>
        </div>
        <?php endif; ?>
        
        <!-- Action Buttons -->
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-body text-center">
                <a href="<?= site_url('admin/quotes') ?>" class="btn btn-outline-default">
                  <i class="icofont-arrow-left mr-5"></i> Back to Quotes
                </a>
                <a href="mailto:<?= esc($quote['email']) ?>?subject=Quote Request #<?= $quote['id'] ?>&body=Dear <?= esc($quote['name']) ?>,%0D%0A%0D%0AThank you for your quote request.%0D%0A%0D%0ABest regards,%0D%0AJunk Collection Team" 
                   class="btn btn-purple">
                  <i class="icofont-envelope mr-5"></i> Send Email
                </a>
                <a href="tel:<?= esc($quote['phone']) ?>" class="btn btn-green">
                  <i class="icofont-phone mr-5"></i> Call Customer
                </a>
              </div>
            </div>
          </div>
        </div>
        
      </div>
    </div>
  </div>
</div>

<!-- Image Modal -->
<div class="modal fade" id="imageModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Image Preview</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body text-center">
        <img id="modalImage" src="" alt="Full Size Image" class="img-fluid">
      </div>
    </div>
  </div>
</div>

<script>
function openImageModal(imageSrc) {
    document.getElementById('modalImage').src = imageSrc;
    new bootstrap.Modal(document.getElementById('imageModal')).show();
}
</script>

<?= $this->include('admin/footer'); ?>