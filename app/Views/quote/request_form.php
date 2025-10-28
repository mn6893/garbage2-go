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

        <!-- AJAX Response Messages -->
        <div id="responseMessage" style="display: none;"></div>
        
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
                    <div class="d-flex justify-content-between align-items-center mb-15">
                      <small class="text-muted">Uploaded Images:</small>
                      <button type="button" class="btn btn-sm btn-outline-danger" onclick="clearAllFiles()">
                        <i class="jam jam-trash"></i> Clear All
                      </button>
                    </div>
                    <div class="row" id="filePreview"></div>
                  </div>
                </div>
              </div>

            <form id="quote-form" class="fields-white" method="post" action="<?= base_url('quote/submit') ?>" enctype="multipart/form-data">
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
                  <div class="col-lg-12 col-xl-6">
                    <div class="form-group">
                      <label for="form_preferred_date">Preferred Date *</label>
                      <input id="form_preferred_date" type="date" name="preferred_date" class="form-control"
                             value="<?= old('preferred_date', isset($input['preferred_date']) ? esc($input['preferred_date']) : '') ?>"
                             min="<?= date('Y-m-d') ?>" required>
                      <?php if (isset($validation) && $validation->hasError('preferred_date')): ?>
                          <div class="text-danger small mt-1"><?= $validation->getError('preferred_date') ?></div>
                      <?php endif; ?>
                    </div>
                  </div>
                  <div class="col-lg-12 col-xl-6">
                    <div class="form-group">
                      <label for="form_preferred_time">Preferred Time Window *</label>
                      <select id="form_preferred_time" name="preferred_time" class="form-control" required>
                        <option value="">Select a time window</option>
                        <option value="8:00 AM - 11:00 AM" <?= old('preferred_time', isset($input['preferred_time']) ? $input['preferred_time'] : '') == '8:00 AM - 11:00 AM' ? 'selected' : '' ?>>8:00 AM - 11:00 AM</option>
                        <option value="11:00 AM - 2:00 PM" <?= old('preferred_time', isset($input['preferred_time']) ? $input['preferred_time'] : '') == '11:00 AM - 2:00 PM' ? 'selected' : '' ?>>11:00 AM - 2:00 PM</option>
                        <option value="2:00 PM - 5:00 PM" <?= old('preferred_time', isset($input['preferred_time']) ? $input['preferred_time'] : '') == '2:00 PM - 5:00 PM' ? 'selected' : '' ?>>2:00 PM - 5:00 PM</option>
                        <option value="5:00 PM - 8:00 PM" <?= old('preferred_time', isset($input['preferred_time']) ? $input['preferred_time'] : '') == '5:00 PM - 8:00 PM' ? 'selected' : '' ?>>5:00 PM - 8:00 PM</option>
                      </select>
                      <?php if (isset($validation) && $validation->hasError('preferred_time')): ?>
                          <div class="text-danger small mt-1"><?= $validation->getError('preferred_time') ?></div>
                      <?php endif; ?>
                    </div>
                  </div>
                </div>

                <div class="form-row">
                  <div class="col-md-12">
                    <div class="form-group">
                      <label for="form_address">Complete Address *</label>
                      <textarea id="form_address" name="address" class="form-control"
                                placeholder="Start typing your address..."
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
                    <button type="submit" class="btn btn-l btn-default" id="submitBtn">
                      <span id="submitText">Get Free Quote</span>
                      <span id="loadingSpinner" style="display: none;">
                        <i class="fa fa-spinner fa-spin"></i> Processing...
                      </span>
                    </button>
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
        
        <!-- Loading Overlay -->
        <div id="loadingOverlay" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.7); z-index: 9999; color: white;">
          <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); text-align: center;">
            <div class="spinner-border text-light mb-3" role="status" style="width: 3rem; height: 3rem;">
              <span class="sr-only">Loading...</span>
            </div>
            <h4>Processing Your Quote Request...</h4>
            <p>Please wait while we process your submission</p>
          </div>
        </div>

        <!-- Google Maps Places API -->
        <script src="https://maps.googleapis.com/maps/api/js?key=<?= getenv('GOOGLE_MAPS_API_KEY') ?: 'YOUR_GOOGLE_MAPS_API_KEY' ?>&libraries=places&callback=initAutocomplete" async defer></script>

        <script>
        // Initialize Google Places Autocomplete
        function initAutocomplete() {
            const addressField = document.getElementById('form_address');
            const cityField = document.getElementById('form_city');

            if (addressField && typeof google !== 'undefined') {
                const autocomplete = new google.maps.places.Autocomplete(addressField, {
                    componentRestrictions: { country: 'ca' }, // Restrict to Canada
                    fields: ['address_components', 'formatted_address', 'geometry'],
                    types: ['address']
                });

                autocomplete.addListener('place_changed', function() {
                    const place = autocomplete.getPlace();

                    if (!place.geometry) {
                        return;
                    }

                    // Set the full formatted address
                    addressField.value = place.formatted_address;

                    // Extract city from address components
                    if (place.address_components) {
                        for (let component of place.address_components) {
                            if (component.types.includes('locality')) {
                                cityField.value = component.long_name;
                                break;
                            } else if (component.types.includes('administrative_area_level_3')) {
                                cityField.value = component.long_name;
                            }
                        }
                    }
                });
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            const uploadArea = document.querySelector('.upload-area');
            const fileInput = document.getElementById('junk_images');
            const filePreview = document.getElementById('filePreview');
            const uploadedFiles = document.getElementById('uploadedFiles');
            let selectedFiles = []; // Array to track selected files
            let supportsDataTransfer = false;

            // Check if DataTransfer is supported
            try {
                new DataTransfer();
                supportsDataTransfer = true;
            } catch (e) {
                console.log('DataTransfer not supported, using fallback method');
            }

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
              addFiles(files);
            });

            fileInput.addEventListener('change', function() {
              addFiles(this.files);
            });

            function addFiles(files) {
              Array.from(files).forEach(file => {
                if (file.type.startsWith('image/')) {
                  if (file.size > 5 * 1024 * 1024) { // 5MB limit
                    alert(`File "${file.name}" is too large. Maximum size is 5MB.`);
                    return;
                  }
                  
                  // Check if file is already added
                  const exists = selectedFiles.some(f => f.name === file.name && f.size === file.size);
                  if (!exists) {
                    selectedFiles.push(file);
                  } else {
                    console.log(`File "${file.name}" is already selected.`);
                  }
                } else {
                  alert(`File "${file.name}" is not a valid image file.`);
                }
              });
              updateFileInput();
              renderPreviews();
            }

            function removeFile(index) {
              selectedFiles.splice(index, 1);
              updateFileInput();
              renderPreviews();
            }

            function updateFileInput() {
              if (selectedFiles.length === 0) {
                fileInput.value = '';
                return;
              }

              if (supportsDataTransfer) {
                try {
                  // Create new DataTransfer object to update file input
                  const dt = new DataTransfer();
                  selectedFiles.forEach(file => {
                    dt.items.add(file);
                  });
                  fileInput.files = dt.files;
                } catch (error) {
                  console.error('Error updating file input:', error);
                  supportsDataTransfer = false;
                }
              }
              
              if (!supportsDataTransfer) {
                // For browsers that don't support DataTransfer, we'll handle this on form submit
                console.log('Using fallback file handling for', selectedFiles.length, 'files');
              }
            }

            function renderPreviews() {
              filePreview.innerHTML = '';
              if (selectedFiles.length > 0) {
                uploadedFiles.style.display = 'block';
                selectedFiles.forEach((file, index) => {
                  const reader = new FileReader();
                  reader.onload = function(e) {
                    const col = document.createElement('div');
                    col.className = 'col-md-3 mb-15';
                    col.innerHTML = `
                      <div class="position-relative">
                        <img src="${e.target.result}" class="img-fluid rounded shadow-sm" style="height: 100px; width: 100%; object-fit: cover;">
                        <button type="button" class="btn btn-sm btn-danger position-absolute" style="top: 5px; right: 5px; width: 25px; height: 25px; padding: 0; font-size: 12px;" onclick="removeFileAtIndex(${index})">&times;</button>
                        <small class="d-block text-center mt-5 text-truncate">${file.name}</small>
                      </div>
                    `;
                    filePreview.appendChild(col);
                  };
                  reader.readAsDataURL(file);
                });
              } else {
                uploadedFiles.style.display = 'none';
              }
            }

            // Handle form submission for browsers without DataTransfer support
            document.getElementById('quote-form').addEventListener('submit', function(e) {
              e.preventDefault(); // Always prevent default for AJAX
              
              // Show loading state
              showLoading();
              
              let formData;
              
              if (!supportsDataTransfer && selectedFiles.length > 0) {
                // Create new FormData and append files manually
                formData = new FormData(this);
                
                // Remove existing file entries
                formData.delete('junk_images[]');
                
                // Add selected files
                selectedFiles.forEach(file => {
                  formData.append('junk_images[]', file);
                });
              } else {
                // Use regular FormData
                formData = new FormData(this);
              }
              
              // Add AJAX header
              fetch(this.action, {
                method: 'POST',
                body: formData,
                headers: {
                  'X-Requested-With': 'XMLHttpRequest'
                }
              })
              .then(response => response.json())
              .then(data => {
                hideLoading();
                
                if (data.success) {
                  showMessage(data.message, 'success');
                  // Reset form after successful submission
                  document.getElementById('quote-form').reset();
                  selectedFiles = [];
                  updateFileInput();
                  renderPreviews();
                  
                  // Scroll to top to show message
                  window.scrollTo({ top: 0, behavior: 'smooth' });
                } else {
                  showMessage(data.message || 'An error occurred while submitting your request.', 'error');
                }
              })
              .catch(error => {
                console.error('Error:', error);
                hideLoading();
                showMessage('An error occurred while submitting the form. Please try again.', 'error');
              });
            });
            
            function showLoading() {
              document.getElementById('loadingOverlay').style.display = 'block';
              document.getElementById('submitText').style.display = 'none';
              document.getElementById('loadingSpinner').style.display = 'inline';
              document.getElementById('submitBtn').disabled = true;
            }
            
            function hideLoading() {
              document.getElementById('loadingOverlay').style.display = 'none';
              document.getElementById('submitText').style.display = 'inline';
              document.getElementById('loadingSpinner').style.display = 'none';
              document.getElementById('submitBtn').disabled = false;
            }
            
            function showMessage(message, type) {
              const messageDiv = document.getElementById('responseMessage');
              const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
              
              messageDiv.innerHTML = `
                <div class="alert ${alertClass} alert-dismissible fade show" role="alert">
                  ${message}
                  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
              `;
              messageDiv.style.display = 'block';
              
              // Auto-hide success messages after 5 seconds
              if (type === 'success') {
                setTimeout(() => {
                  messageDiv.style.display = 'none';
                }, 5000);
              }
            }

            // Make functions global so they can be called from onclick
            window.removeFileAtIndex = function(index) {
              removeFile(index);
            };

            // Clear all files function
            window.clearAllFiles = function() {
              selectedFiles = [];
              updateFileInput();
              renderPreviews();
            };
        });
        </script>
      </div>
      <!-- /.container -->
    </div>
    <!-- /.wrapper -->

<?= $this->include('templates/footer'); ?>