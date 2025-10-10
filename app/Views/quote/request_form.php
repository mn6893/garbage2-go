<?= $this->include('templates/header_pages'); ?>
    
    <div class="wrapper image-wrapper bg-image page-title-wrapper inverse-text" data-image-src="<?= base_url('style/images/art/bg3.jpg') ?>">
      <div class="container inner text-center">
        <div class="space90"></div>
        <h1 class="page-title">Request a Quote</h1>
        <p class="lead">Get your free estimate for professional junk removal services</p>
      </div>
      <!-- /.container -->
    </div>
    <!-- /.wrapper -->
    
    <div class="wrapper light-wrapper">
      <div class="container inner">
        <h2 class="section-title">Get Your Free Quote Today</h2>
        <p class="lead larger">Tell us about your junk removal needs and we'll provide you with a free, no-obligation quote. Our professional team is ready to help you reclaim your space!</p>
        <div class="space40"></div>
        
        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?= session()->getFlashdata('error') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>
        
        <?php if (isset($validation)): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <h5>Please correct the following errors:</h5>
                <ul class="mb-0">
                    <?php foreach ($validation->getErrors() as $error): ?>
                        <li><?= esc($error) ?></li>
                    <?php endforeach; ?>
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>
        
        <div class="row">
          <div class="col-lg-10 mx-auto">
            <div class="box bg-white shadow p-40">
              <!-- Information List -->
              <div class="row mb-40">
                <div class="col-lg-12">
                  <ul class="icon-list bullet-bg bullet-bg-dark">
                      <li><i class="jam jam-check" style="background-color: #003366; color: white; border-radius: 50%; padding: 2px; font-size: 16px;"></i> <span style="font-size: 16px;"> We do all the Loading, Lifting, Moving & Clearing</span></li>
                      <li><i class="jam jam-check" style="background-color: #003366; color: white; border-radius: 50%; padding: 2px; font-size: 16px;"></i> <span style="font-size: 16px;"> From single mattress to 40 feet container load</span></li>
                      <li><i class="jam jam-check" style="background-color: #003366; color: white; border-radius: 50%; padding: 2px; font-size: 16px;"></i> <span style="font-size: 16px;"> Up to 50% cheaper than our competitors</span></li>
                    <li><i class="jam jam-check" style="background-color: #003366; color: white; border-radius: 50%; padding: 2px; font-size: 16px;"></i> <span style="font-size: 16px;"> We service most areas of Ontario including GTA, Ottawa, Hamilton, London & surrounding regions</span></li>
                  </ul>
                </div>
              </div>
              
              <!-- File Upload Section -->
              <div class="mb-40">
                <h5 class="mb-20">Upload Pictures of Your Junk (Optional)</h5>
                <div class="upload-area border-2 border-dashed rounded p-40 text-center position-relative" style="border-color: #e6e6e6ff; border-style: dotted; background: #fafafa; transition: all 0.3s ease;">
                  <input type="file" class="file-input position-absolute w-100 h-100" id="junk_images" name="junk_images[]" multiple accept="image/*" style="opacity: 0; cursor: pointer; top: 0; left: 0;" form="quote-form">
                  <div class="upload-content">
                    <div class="upload-icon mb-20">
                      <i class="jam jam-upload" style="font-size: 48px; color: #00EC01;"></i>
                    </div>
                    <h6 class="mb-10">Drag & Drop your images here</h6>
                    <p class="text-muted mb-15">or <span class="text-primary" style="cursor: pointer;">browse files</span></p>
                    <small class="text-muted">Supports: JPG, JPEG, PNG (Max 5MB each)</small>
                  </div>
                  <div class="uploaded-files mt-20" id="uploadedFiles" style="display: none;">
                    <div class="row" id="filePreview"></div>
                  </div>
                </div>
              </div>

            <form id="quote-form" class="fields-white" method="post" action="<?= site_url('quote/submit') ?>" enctype="multipart/form-data">
              <?= csrf_field() ?>
              <div class="controls">
                <div class="form-row">
                  <div class="col-lg-12 col-xl-6">
                    <div class="form-group">
                      <label for="form_name">Full Name *</label>
                      <input id="form_name" type="text" name="name" class="form-control" 
                             placeholder="Enter your full name" 
                             value="<?= old('name', isset($input['name']) ? esc($input['name']) : '') ?>" 
                             required>
                      <?php if (isset($validation) && $validation->hasError('name')): ?>
                          <div class="text-danger small mt-1"><?= $validation->getError('name') ?></div>
                      <?php endif; ?>
                    </div>
                  </div>
                  <div class="col-lg-12 col-xl-6">
                    <div class="form-group">
                      <label for="form_email">Email Address *</label>
                      <input id="form_email" type="email" name="email" class="form-control" 
                             placeholder="Enter your email address" 
                             value="<?= old('email', isset($input['email']) ? esc($input['email']) : '') ?>" 
                             required>
                      <?php if (isset($validation) && $validation->hasError('email')): ?>
                          <div class="text-danger small mt-1"><?= $validation->getError('email') ?></div>
                      <?php endif; ?>
                    </div>
                  </div>
                </div>
                
                <div class="form-row">
                  <div class="col-lg-12 col-xl-6">
                    <div class="form-group">
                      <label for="form_phone">Phone Number *</label>
                      <input id="form_phone" type="tel" name="phone" class="form-control" 
                             placeholder="Enter your phone number" 
                             value="<?= old('phone', isset($input['phone']) ? esc($input['phone']) : '') ?>" 
                             required>
                      <?php if (isset($validation) && $validation->hasError('phone')): ?>
                          <div class="text-danger small mt-1"><?= $validation->getError('phone') ?></div>
                      <?php endif; ?>
                    </div>
                  </div>
                  <div class="col-lg-12 col-xl-6">
                    <div class="form-group">
                      <label for="form_city">Suburb/City</label>
                      <input id="form_city" type="text" name="city" class="form-control" 
                             placeholder="Enter suburb or city" 
                             value="<?= old('city', isset($input['city']) ? esc($input['city']) : '') ?>">
                    </div>
                  </div>
                </div>
                
                <div class="form-row">
                  <div class="col-md-12">
                    <div class="form-group">
                      <label for="form_address">Complete Address *</label>
                      <textarea id="form_address" name="address" class="form-control" 
                                placeholder="Enter the complete address where service is needed" 
                                rows="2" required><?= old('address', isset($input['address']) ? esc($input['address']) : '') ?></textarea>
                      <?php if (isset($validation) && $validation->hasError('address')): ?>
                          <div class="text-danger small mt-1"><?= $validation->getError('address') ?></div>
                      <?php endif; ?>
                    </div>
                  </div>
                </div>
                
                <div class="form-row">
                  <div class="col-md-12">
                    <div class="form-group">
                      <label for="form_description">Tell us about your Junk *</label>
                      <textarea id="form_description" name="description" class="form-control" 
                                placeholder="Please describe the items to be removed, approximate quantity, and any special access requirements (stairs, narrow hallways, etc.)" 
                                rows="4" required><?= old('description', isset($input['description']) ? esc($input['description']) : '') ?></textarea>
                      <?php if (isset($validation) && $validation->hasError('description')): ?>
                          <div class="text-danger small mt-1"><?= $validation->getError('description') ?></div>
                      <?php endif; ?>
                    </div>
                  </div>
                </div>
                
                <div class="form-row">
                  <div class="col-md-12 text-center">
                    <button type="submit" class="btn btn-l btn-default">Get Free Quote</button>
                  </div>
                </div>
                
                <div class="form-row">
                  <div class="col-md-12">
                    <p class="text-muted mt-3 text-center"><strong>*</strong> These fields are required. We'll contact you within 24 hours with your free quote.</p>
                  </div>
                </div>
              </div>
            </form>
            <!-- /form -->
            </div>
          </div>
          <!--/column -->
        </div>
        <!--/.row -->
        
        <script>
        document.addEventListener('DOMContentLoaded', function() {
            const uploadArea = document.querySelector('.upload-area');
            const fileInput = document.getElementById('junk_images');
            const filePreview = document.getElementById('filePreview');
            const uploadedFiles = document.getElementById('uploadedFiles');

            // Drag and drop events
            uploadArea.addEventListener('dragover', function(e) {
              e.preventDefault();
              uploadArea.style.borderColor = '#007bff';
              uploadArea.style.background = '#f8f9ff';
            });

            uploadArea.addEventListener('dragleave', function(e) {
              e.preventDefault();
              uploadArea.style.borderColor = '#e5e5e5';
              uploadArea.style.background = '#fafafa';
            });

            uploadArea.addEventListener('drop', function(e) {
              e.preventDefault();
              uploadArea.style.borderColor = '#e5e5e5';
              uploadArea.style.background = '#fafafa';
              const files = e.dataTransfer.files;
              handleFiles(files);
            });

            fileInput.addEventListener('change', function() {
              handleFiles(this.files);
            });

            function handleFiles(files) {
              filePreview.innerHTML = '';
              if (files.length > 0) {
                  uploadedFiles.style.display = 'block';
                  Array.from(files).forEach(file => {
                if (file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                  const col = document.createElement('div');
                  col.className = 'col-md-3 mb-15';
                  col.innerHTML = `
                      <div class="position-relative">
                    <img src="${e.target.result}" class="img-fluid rounded shadow-sm" style="height: 100px; width: 100%; object-fit: cover;">
                    <button type="button" class="btn btn-sm btn-danger position-absolute" style="top: 5px; right: 5px; width: 25px; height: 25px; padding: 0; font-size: 12px;" onclick="this.closest('.col-md-3').remove();">&times;</button>
                    <small class="d-block text-center mt-5 text-truncate">${file.name}</small>
                      </div>
                  `;
                  filePreview.appendChild(col);
                    };
                    reader.readAsDataURL(file);
                }
                  });
              } else {
                  uploadedFiles.style.display = 'none';
              }
            }
        });
        </script>
      </div>
      <!-- /.container -->
    </div>
    <!-- /.wrapper -->

<?= $this->include('templates/footer'); ?>