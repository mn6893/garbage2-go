<?= $this->include('admin/header'); ?>

<div class="wrapper light-wrapper">
  <div class="contai                  <div class="col-md-12 mb-20">
                    <h6>Service Type:</h6>
                    <p class="fs-16 fw-bold"><?= esc($contact['service_type'] ?? 'General Inquiry') ?></p>
                  </div>
                  <div class="col-md-12">
                    <h6>Message:</h6>
                    <div class="border rounded p-15 bg-light">
                      <p class="mb-0"><?= nl2br(esc($contact['message'])) ?></p>
                    </div>
                  </div>>
    <div class="row">
      <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-30">
          <h2 class="section-title mb-0">Contact Details #<?= $contact['id'] ?></h2>
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="<?= base_url('admin/dashboard') ?>">Dashboard</a></li>
              <li class="breadcrumb-item"><a href="<?= base_url('admin/contacts') ?>">Contacts</a></li>
              <li class="breadcrumb-item active">Contact #<?= $contact['id'] ?></li>
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
          <!-- Contact Information -->
          <div class="col-md-6">
            <div class="card mb-20">
              <div class="card-header">
                <h5 class="card-title mb-0">Contact Information</h5>
              </div>
              <div class="card-body">
                <div class="row">
                  <div class="col-6">
                    <p><strong>Name:</strong><br><?= esc($contact['name']) ?></p>
                  </div>
                  <div class="col-6">
                    <p><strong>Email:</strong><br>
                      <a href="mailto:<?= esc($contact['email']) ?>"><?= esc($contact['email']) ?></a>
                    </p>
                  </div>
                  <div class="col-12">
                    <p><strong>Phone:</strong><br>
                      <a href="tel:<?= esc($contact['phone']) ?>"><?= esc($contact['phone']) ?></a>
                    </p>
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
                <form method="post" action="<?= base_url('admin/contact/update-status') ?>">
                  <?= csrf_field() ?>
                  <input type="hidden" name="id" value="<?= $contact['id'] ?>">
                  
                  <div class="mb-15">
                    <label class="form-label">Current Status</label>
                    <select name="status" class="form-control">
                      <option value="new" <?= $contact['status'] === 'new' ? 'selected' : '' ?>>New</option>
                      <option value="replied" <?= $contact['status'] === 'replied' ? 'selected' : '' ?>>Replied</option>
                      <option value="resolved" <?= $contact['status'] === 'resolved' ? 'selected' : '' ?>>Resolved</option>
                    </select>
                  </div>
                  
                  <button type="submit" class="btn btn-purple">Update Status</button>
                </form>
                
                <hr>
                
                <p><strong>Submitted:</strong><br>
                  <?= date('F j, Y \a\t g:i A', strtotime($contact['created_at'])) ?>
                </p>
                
                <?php if ($contact['updated_at'] && $contact['updated_at'] !== $contact['created_at']): ?>
                <p><strong>Last Updated:</strong><br>
                  <?= date('F j, Y \a\t g:i A', strtotime($contact['updated_at'])) ?>
                </p>
                <?php endif; ?>
              </div>
            </div>
          </div>
        </div>
        
        <!-- Subject & Message -->
        <div class="row">
          <div class="col-md-12">
            <div class="card mb-20">
              <div class="card-header">
                <h5 class="card-title mb-0">Message Details</h5>
              </div>
              <div class="card-body">
                <div class="row">
                  <div class="col-md-12 mb-20">
                    <h6>Subject:</h6>
                    <p class="fs-16 fw-bold"><?= esc($contact['subject']) ?></p>
                  </div>
                  <div class="col-md-12">
                    <h6>Message:</h6>
                    <div class="border rounded p-15 bg-light">
                      <p class="mb-0"><?= nl2br(esc($contact['message'])) ?></p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        
        <!-- Quick Reply Section -->
        <div class="row">
          <div class="col-md-12">
            <div class="card mb-20">
              <div class="card-header">
                <h5 class="card-title mb-0">Quick Reply</h5>
              </div>
              <div class="card-body">
                <form>
                  <div class="row">
                    <div class="col-md-12 mb-15">
                      <label class="form-label">Reply Template</label>
                      <select class="form-control" id="replyTemplate" onchange="loadTemplate()">
                        <option value="">Choose a template...</option>
                        <option value="thank_you">Thank You</option>
                        <option value="more_info">Request More Information</option>
                        <option value="quote_follow">Quote Follow-up</option>
                        <option value="resolved">Issue Resolved</option>
                      </select>
                    </div>
                    <div class="col-md-12 mb-15">
                      <label class="form-label">Subject</label>
                      <input type="text" class="form-control" id="replySubject" 
                             value="Re: <?= esc($contact['service_type'] ?? 'Your Inquiry') ?>">
                    </div>
                    <div class="col-md-12 mb-15">
                      <label class="form-label">Message</label>
                      <textarea class="form-control" rows="6" id="replyMessage"
                                placeholder="Type your reply here...">Dear <?= esc($contact['name']) ?>,

Thank you for contacting us.

Best regards,
Junk Collection Team</textarea>
                    </div>
                    <div class="col-md-12">
                      <button type="button" class="btn btn-purple" onclick="sendReply()">
                        <i class="icofont-envelope mr-5"></i> Send Reply
                      </button>
                      <button type="button" class="btn btn-outline-default" onclick="openEmailClient()">
                        <i class="icofont-external-link mr-5"></i> Open in Email Client
                      </button>
                    </div>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
        
        <!-- Action Buttons -->
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-body text-center">
                <a href="<?= base_url('admin/contacts') ?>" class="btn btn-outline-default">
                  <i class="icofont-arrow-left mr-5"></i> Back to Contacts
                </a>
                <a href="tel:<?= esc($contact['phone']) ?>" class="btn btn-green">
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

<script>
const templates = {
    thank_you: `Dear <?= esc($contact['name']) ?>,

Thank you for contacting us. We have received your message and will get back to you shortly.

Best regards,
Junk Collection Team`,

    more_info: `Dear <?= esc($contact['name']) ?>,

Thank you for your inquiry. To better assist you, could you please provide us with some additional information?

We look forward to hearing from you.

Best regards,
Junk Collection Team`,

    quote_follow: `Dear <?= esc($contact['name']) ?>,

Thank you for your interest in our services. We would be happy to provide you with a quote for your junk removal needs.

Please feel free to contact us to schedule an appointment.

Best regards,
Junk Collection Team`,

    resolved: `Dear <?= esc($contact['name']) ?>,

We're glad we could help resolve your inquiry. If you have any other questions or need our services in the future, please don't hesitate to contact us.

Thank you for choosing our services.

Best regards,
Junk Collection Team`
};

function loadTemplate() {
    const select = document.getElementById('replyTemplate');
    const messageArea = document.getElementById('replyMessage');
    
    if (select.value && templates[select.value]) {
        messageArea.value = templates[select.value];
    }
}

function sendReply() {
    // This would implement AJAX to send the reply
    alert('Reply functionality would be implemented here');
}

function openEmailClient() {
    const subject = encodeURIComponent(document.getElementById('replySubject').value);
    const body = encodeURIComponent(document.getElementById('replyMessage').value);
    const email = '<?= esc($contact['email']) ?>';
    
    window.open(`mailto:${email}?subject=${subject}&body=${body}`);
}
</script>

<?= $this->include('admin/footer'); ?>