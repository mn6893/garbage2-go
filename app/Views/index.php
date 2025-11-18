<?= $this->include('templates/header'); ?>
          
    <!-- /section -->
    <div class="wrapper image-wrapper bg-image page-title-wrapper inverse-text" data-image-src="style/images/art/slider-1.jpg">
      <div class="container inner text-center">
        <div class="space140"></div>
         <h1 class="display-1">GarbageToGo - We <span class="typer color-default" id="typer" data-delay="100" data-delim=":" data-words="remove junk:clear debris:dispose appliances:handle cleanouts:manage waste:recycle responsibly"></span><span class="cursor color-default" data-owner="typer"></span></h1>
         <p class="lead larger text-center mt-20">Professional Junk Removal Services | Residential Cleanouts | Commercial Waste Management | Construction Debris Removal | Appliance Disposal | Electronic Recycling</p>
        <!-- <h1 class="display-1">We love to clear your junk <br class="d-none d-lg-block" />and make your space beautiful</h1> -->
        <div class="space20"></div>
        <div class="mb-30">
          <p class="lead" style="font-weight: 600; color: rgba(255, 255, 255, 0.95); font-size: 1.3rem; margin: 0;">
            <i class="jam jam-recycle" style="color: #00EC01; margin-right: 8px;"></i>
            Our Last Option Will Be Landfill
          </p>
        </div>
        <a href="#portfolio" class="btn btn-l btn-default scroll">Request a Quote </a>
      </div>
      <!-- /.container -->
      <div class="divider"><svg xmlns="http://www.w3.org/2000/svg" class="fill-light-wrapper" preserveAspectRatio="none" viewBox="0 0 1070 20.98">
          <path d="M0,0V21H1070V0A6830.24,6830.24,0,0,1,0,0Z" /></svg></div>
    </div>
    <!-- /.wrapper -->
    <div class="wrapper light-wrapper" id="portfolio">
      <div class="container inner">
        <h1 class="section-title text-uppercase mb-20 text-center color-default" style="font-size: 1.5rem;">Request a Quote</h1>
        <p class="lead larger text-center">Get a free quote for your junk removal needs <br class="d-none d-lg-block" />anywhere in Canada.</p>
        <div class="space30"></div>
        
        <!-- Quote Request Form -->
        <div class="row justify-content-center">
          <div class="col-lg-12">
            <div class="box bg-white shadow p-40">
               <!-- Information List -->
              <div class="row mb-40">
          <div class="col-lg-12">
            <ul class="icon-list bullet-bg bullet-bg-dark">
                <li><i class="jam jam-check" style="background-color: #003366; color: white; border-radius: 50%; padding: 2px; font-size: 16px;"></i> <span style="font-size: 16px;"> We do all the Loading, Lifting, Moving & Clearing</span></li>
                <li><i class="jam jam-check" style="background-color: #003366; color: white; border-radius: 50%; padding: 2px; font-size: 16px;"></i> <span style="font-size: 16px;"> From single mattress to 40 feet container load</span></li>
                <li><i class="jam jam-check" style="background-color: #003366; color: white; border-radius: 50%; padding: 2px; font-size: 16px;"></i> <span style="font-size: 16px;"> Up to 50% cheaper than our competitors</span></li>
              <li><i class="jam jam-check" style="background-color: #003366; color: white; border-radius: 50%; padding: 2px; font-size: 16px;"></i> <span style="font-size: 16px;"> We service most areas of Ontario including GTA, Hamilton, London & surrounding regions</span></li>
            </ul>
          </div>
              </div>
              <!-- File Upload Section -->
              <div class="mb-40">
          <h5 class="mb-20">Upload Pictures of Your Junk (Optional)</h5>
          <div class="upload-area border-2 border-dashed rounded p-40 text-center position-relative" style="border-color: #e6e6e6ff; border-style: dotted; background: #fafafa; transition: all 0.3s ease;">
            <input type="file" class="file-input position-absolute w-100 h-100" id="junk_images" name="junk_images[]" multiple accept="image/*" style="opacity: 0; cursor: pointer; top: 0; left: 0;">
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
          </div></br>
              
              <form id="home-quote-form" method="post" action="<?= base_url('quote/submit') ?>" enctype="multipart/form-data">
          <?= csrf_field() ?>
          
          <!-- Success/Error Messages -->
          <div id="form-messages" style="display: none;"></div>
          
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
            <div class="col-md-6">
              <div class="mb-3">
          <label for="fullname" class="form-label">Full Name *</label>
          <input type="text" class="form-control" id="fullname" name="name" placeholder="Your Full Name" 
                 value="<?= old('name', isset($input['name']) ? esc($input['name']) : '') ?>" required>
          <?php if (isset($validation) && $validation->hasError('name')): ?>
              <div class="text-danger small mt-1"><?= $validation->getError('name') ?></div>
          <?php endif; ?>
              </div>
            </div>
            <div class="col-md-6">
              <div class="mb-3">
          <label for="email" class="form-label">Email Address *</label>
          <input type="email" class="form-control" id="email" name="email" placeholder="Your Email" 
                 value="<?= old('email', isset($input['email']) ? esc($input['email']) : '') ?>" required>
          <?php if (isset($validation) && $validation->hasError('email')): ?>
              <div class="text-danger small mt-1"><?= $validation->getError('email') ?></div>
          <?php endif; ?>
              </div>
            </div>
          </div>
          
          <div class="row">
            <div class="col-md-6">
              <div class="mb-3">
          <label for="phone" class="form-label">Phone Number *</label>
          <input type="tel" class="form-control" id="phone" name="phone" placeholder="Phone Number" 
                 value="<?= old('phone', isset($input['phone']) ? esc($input['phone']) : '') ?>" required>
          <?php if (isset($validation) && $validation->hasError('phone')): ?>
              <div class="text-danger small mt-1"><?= $validation->getError('phone') ?></div>
          <?php endif; ?>
              </div>
            </div>
            <div class="col-md-6">
              <div class="mb-3">
          <label for="suburb" class="form-label">Suburb/City</label>
          <input type="text" class="form-control" id="suburb" name="city" placeholder="Suburb/City"
                 value="<?= old('city', isset($input['city']) ? esc($input['city']) : '') ?>">
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-6">
              <div class="mb-3">
          <label for="preferred_date" class="form-label">Preferred Date *</label>
          <input type="date" class="form-control" id="preferred_date" name="preferred_date"
                 value="<?= old('preferred_date', isset($input['preferred_date']) ? esc($input['preferred_date']) : '') ?>"
                 min="<?= date('Y-m-d') ?>" required>
          <?php if (isset($validation) && $validation->hasError('preferred_date')): ?>
              <div class="text-danger small mt-1"><?= $validation->getError('preferred_date') ?></div>
          <?php endif; ?>
              </div>
            </div>
            <div class="col-md-6">
              <div class="mb-3">
          <label for="preferred_time" class="form-label">Preferred Time Window *</label>
          <select class="form-control" id="preferred_time" name="preferred_time" required>
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

          <div class="mb-3">
            <label for="address" class="form-label">Complete Address *</label>
            <textarea class="form-control" id="address" name="address" placeholder="Start typing your address..." style="height: 80px" required><?= old('address', isset($input['address']) ? esc($input['address']) : '') ?></textarea>
            <?php if (isset($validation) && $validation->hasError('address')): ?>
                <div class="text-danger small mt-1"><?= $validation->getError('address') ?></div>
            <?php endif; ?>
          </div>
          
          <div class="mb-3">
            <label for="junk_description" class="form-label">Tell us about your Junk *</label>
            <textarea class="form-control" id="junk_description" name="description" placeholder="Tell us about your junk" style="height: 120px" required><?= old('description', isset($input['description']) ? esc($input['description']) : '') ?></textarea>
            <?php if (isset($validation) && $validation->hasError('description')): ?>
                <div class="text-danger small mt-1"><?= $validation->getError('description') ?></div>
            <?php endif; ?>
          </div>

      <!-- Google Maps Places API -->
      <script src="https://maps.googleapis.com/maps/api/js?key=<?= getenv('GOOGLE_MAPS_API_KEY') ?: 'AIzaSyA6ApBEw4KoErt0Blz40Xg22_YEi5j559U' ?>&libraries=places&callback=initAutocomplete" async defer></script>

      <script>
      // Initialize Google Places Autocomplete
      function initAutocomplete() {
          const addressField = document.getElementById('address');
          const suburbField = document.getElementById('suburb');

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
                              suburbField.value = component.long_name;
                              break;
                          } else if (component.types.includes('administrative_area_level_3')) {
                              suburbField.value = component.long_name;
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
                  showMessage(`File "${file.name}" is too large. Maximum size is 5MB.`, 'danger');
                  return;
                }
                
                // Check if file is already added
                const exists = selectedFiles.some(f => f.name === file.name && f.size === file.size);
                if (!exists) {
                  selectedFiles.push(file);
                  console.log(`Added file: ${file.name} (${(file.size / 1024).toFixed(1)} KB)`);
                } else {
                  console.log(`File "${file.name}" is already selected.`);
                }
              } else {
                showMessage(`File "${file.name}" is not a valid image file. Please upload JPG, PNG, or GIF files.`, 'danger');
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

          // Handle form submission with AJAX
          document.getElementById('home-quote-form').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const submitBtn = document.getElementById('submit-btn');
            const submitText = submitBtn.querySelector('.submit-text');
            const loadingText = submitBtn.querySelector('.loading-text');
            
            // Show loading state
            submitBtn.disabled = true;
            submitText.style.display = 'none';
            loadingText.style.display = 'inline-block';
            
            // Clear previous messages
            hideMessage();
            
            // Create FormData
            const formData = new FormData(this);
            
            // Handle file uploads - remove existing and add selected files
            formData.delete('junk_images[]');
            selectedFiles.forEach(file => {
              formData.append('junk_images[]', file);
            });
            
            // Submit via fetch
            fetch(this.action, {
              method: 'POST',
              body: formData,
              headers: {
                'X-Requested-With': 'XMLHttpRequest'
              }
            })
            .then(response => {
              return response.json().catch(() => {
                if (response.ok) {
                  return { success: true, message: 'Quote submitted successfully!' };
                } else {
                  throw new Error('Server error occurred');
                }
              });
            })
            .then(data => {
              if (data.success) {
                showMessage(data.message || 'Your quote request has been submitted successfully! We will contact you soon.', 'success');
                // Reset form
                this.reset();
                selectedFiles = [];
                updateFileInput();
                renderPreviews();
              } else {
                showMessage(data.message || 'Please correct the errors and try again.', 'danger');
                if (data.errors) {
                  const errorList = Object.values(data.errors).join('<br>');
                  showMessage(errorList, 'danger');
                }
              }
            })
            .catch(error => {
              console.error('Error:', error);
              showMessage('An error occurred while submitting your request. Please try again.', 'danger');
            })
            .finally(() => {
              // Reset button state
              submitBtn.disabled = false;
              submitText.style.display = 'inline-block';
              loadingText.style.display = 'none';
            });
          });

          // Helper functions for showing messages
          function showMessage(message, type = 'info') {
            const messageDiv = document.getElementById('form-messages');
            messageDiv.className = `alert alert-${type} alert-dismissible fade show`;
            messageDiv.innerHTML = `
              ${message}
              <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            `;
            messageDiv.style.display = 'block';
            
            // Scroll to message
            messageDiv.scrollIntoView({ behavior: 'smooth', block: 'center' });
            
            // Auto-hide success messages after 5 seconds
            if (type === 'success') {
              setTimeout(() => {
                hideMessage();
              }, 5000);
            }
          }

          function hideMessage() {
            const messageDiv = document.getElementById('form-messages');
            messageDiv.style.display = 'none';
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
          
          <div class="text-center">
            <button type="submit" class="btn btn-l btn-default" id="submit-btn">
              <span class="submit-text">Get Free Quote</span>
              <span class="loading-text" style="display: none;">
                <i class="fas fa-spinner fa-spin"></i> Submitting...
              </span>
            </button>
          </div>
              </form>
            </div>
          </div>
        </div>
        <!-- /.Quote Request Form -->
        
        <div class="space40"></div>
        
        <!-- Junk Removal Points for Canada -->
        
      </div>
      <!-- /.container -->
    </div>
    <!-- /.wrapper -->
    <div class="wrapper gray-wrapper">
      <div class="container inner">
      <div class="row align-items-center">
        <div class="col-lg-6 order-lg-2 text-center">
        <div>
          <figure><img src="<?= base_url('style/images/art/about-index.jpg'); ?>" alt="GarbageToGo Junk Removal Truck" /></figure>
          <div class="row counter counter-s position-absolute" style="top: 45%; right: 8%;">
          <div class="col-md-10 text-center">
            <div class="box bg-white shadow">
            <h3><span class="value">2500</span>+</h3>
            <p>Junk Removals <br class="d-md-none" />Completed</p>
            </div>
            <!--/.box -->
          </div>
          <!--/column -->
          </div>
          <!--/.row -->
        </div>
        </div>
        <!--/column -->
        <div class="space30 d-none d-md-block d-lg-none"></div>
        <div class="col-lg-6 pr-60 pr-md-15">
        <h2 class="section-title text-uppercase mb-20 color-default">What We Remove?</h2>
        <p class="lead larger">We handle all types of junk removal across Canada. From residential cleanouts to commercial waste management, we've got you covered.</p>
        <div class="space20"></div>
        <ul class="progress-list">
          <li>
          <p>Furniture & Appliances</p>
          <div class="progressbar line" style="background: #003366;" data-value="100"></div>
          </li>
          <li>
          <p>Construction Debris</p>
          <div class="progressbar line" style="background: #00558b;" data-value="95"></div>
          </li>
          <li>
          <p>Household Cleanouts</p>
          <div class="progressbar line" style="background: #007bb8;" data-value="98"></div>
          </li>
          <li>
          <p>Office & Commercial Waste</p>
          <div class="progressbar line" style="background: #0099e5;" data-value="90"></div>
          </li>
        </ul>
        <!-- /.progress-list -->
        </div>
        <!--/column -->
      </div>
      <!--/.row -->
      <div class="space60"></div>
      <div class="row text-center">
        <div class="col-md-6 col-lg-3">
        <div class="box shadow inverse-text" style="background: #003366;">
          <div class="icon color-white fs-42 mb-20"> <i class="icofont-truck-alt"></i> </div>
          <h5>Furniture Removal</h5>
          <p>Old furniture, couches, beds, and appliances removed safely from your home or office.</p>
        </div>
        <!--/.box -->
        </div>
        <!--/column -->
        <div class="col-md-6 col-lg-3">
        <div class="box shadow inverse-text" style="background: #00558b;">
          <div class="icon color-white fs-42 mb-20"> <i class="icofont-building-alt"></i> </div>
          <h5>Construction Debris</h5>
          <p>Renovation waste, drywall, flooring, and construction materials cleared efficiently.</p>
        </div>
        <!--/.box -->
        </div>
        <!--/column -->
        <div class="space30 d-none d-md-block d-lg-none"></div>
        <div class="col-md-6 col-lg-3">
        <div class="box shadow inverse-text" style="background: #007bb8;">
          <div class="icon color-white fs-42 mb-20"> <i class="icofont-home"></i> </div>
          <h5>Estate Cleanouts</h5>
          <p>Complete property cleanouts for estates, foreclosures, and property management.</p>
        </div>
        <!--/.box -->
        </div>
        <!--/column -->
        <div class="col-md-6 col-lg-3">
        <div class="box shadow inverse-text" style="background: #0099e5;">
          <div class="icon color-white fs-42 mb-20"> <i class="icofont-recycle"></i> </div>
          <h5>Eco-Friendly Disposal</h5>
          <p>We recycle and donate items whenever possible to minimize environmental impact.</p>
        </div>
        <!--/.box -->
        </div>
        <!--/column -->
      </div>
      <!--/.row -->
      </div>
      <!-- /.container -->
    </div>
    <!-- /.wrapper -->
    <div class="wrapper light-wrapper">
      <div class="container inner">
      <div class="row">
      <div class="col-lg-8">
      <div class="box bg-white shadow p-40">
        <h2 class="section-title text-uppercase mb-30 color-default">Why Choose GarbageToGo?</h2>
        <div class="row">
        <div class="col-md-6 mb-30">
          <div class="d-flex flex-row align-items-start">
          <div class="icon color-default fs-38 mr-20"><i class="icofont-thumbs-up"></i></div>
          <div>
            <h5 class="mb-10">Professional Service</h5>
            <p class="mb-0">Our trained team handles your junk removal with care and professionalism.</p>
          </div>
          </div>
        </div>
        <div class="col-md-6 mb-30">
          <div class="d-flex flex-row align-items-start">
          <div class="icon color-default fs-38 mr-20"><i class="icofont-clock-time"></i></div>
          <div>
            <h5 class="mb-10">Same Day Service</h5>
            <p class="mb-0">Need it done today? We offer same-day junk removal services across Canada.</p>
          </div>
          </div>
        </div>
        <div class="col-md-6 mb-30">
          <div class="d-flex flex-row align-items-start">
          <div class="icon color-default fs-38 mr-20"><i class="icofont-price"></i></div>
          <div>
            <h5 class="mb-10">Transparent Pricing</h5>
            <p class="mb-0">No hidden fees. You'll know the exact cost before we start the job.</p>
          </div>
          </div>
        </div>
        <div class="col-md-6 mb-30">
          <div class="d-flex flex-row align-items-start">
          <div class="icon color-default fs-38 mr-20"><i class="icofont-leaf"></i></div>
          <div>
            <h5 class="mb-10">Eco-Conscious</h5>
            <p class="mb-0">We recycle and donate whenever possible. Landfill is our last option.</p>
          </div>
          </div>
        </div>
        </div>
      </div>
      </div>
      <!--/column -->
      <div class="space30 d-none d-md-block d-lg-none"></div>
      <div class="col-lg-4 pl-50 pl-md-15">
      <h2 class="section-title text-uppercase mb-20 color-default">Our Process</h2>
      <p>Simple, efficient, and hassle-free junk removal. We handle everything from scheduling to disposal, so you don't have to worry about a thing.</p>
      <ul class="icon-list bullet-default">
        <li><i class="jam jam-arrow-right" style="color: #003366;"></i>Schedule your free estimate online or by phone.</li>
        <li><i class="jam jam-arrow-right" style="color: #003366;"></i>We arrive on time with our professional crew.</li>
        <li><i class="jam jam-arrow-right" style="color: #003366;"></i>We do all the heavy lifting and loading.</li>
        <li><i class="jam jam-arrow-right" style="color: #003366;"></i>We clean up and dispose responsibly.</li>
      </ul>
      <div class="space10"></div>
      <a href="<?= base_url('quote') ?>" class="btn mb-0" style="background: #003366; color: white;">Get Free Quote</a>
      </div>
      <!--/column -->
      </div>
      <!--/.row -->
      <div class="space80"></div>
      <div class="row gutter-60 gutter-md-30">
      <div class="col-lg-4">
      <div class="d-flex flex-row">
        <div>
        <span class="icon icon-bg icon-bg-default color-default mr-25" style="background: #003366; color: white;"><span class="number">1</span></span>
        </div>
        <div>
        <h5>Book Online</h5>
        <p class="mb-0">Schedule your junk removal service online or call us for a free estimate.</p>
        </div>
      </div>
      </div>
      <!--/column -->
      <div class="space30 d-none d-md-block d-lg-none"></div>
      <div class="col-lg-4">
      <div class="d-flex flex-row">
        <div>
        <span class="icon icon-bg icon-bg-default color-default mr-25" style="background: #003366; color: white;"><span class="number">2</span></span>
        </div>
        <div>
        <h5>We Arrive & Remove</h5>
        <p class="mb-0">Our professional team arrives on time and handles all the heavy lifting.</p>
        </div>
      </div>
      </div>
      <!--/column -->
      <div class="space30 d-none d-md-block d-lg-none"></div>
      <div class="col-lg-4">
      <div class="d-flex flex-row">
        <div>
        <span class="icon icon-bg icon-bg-default color-default mr-25" style="background: #003366; color: white;"><span class="number">3</span></span>
        </div>
        <div>
        <h5>Clean & Dispose</h5>
        <p class="mb-0">We clean up after ourselves and dispose of your junk responsibly.</p>
        </div>
      </div>
      </div>
      <!--/column -->
      </div>
      <!--/.row -->
      </div>
      <!-- /.container -->
    </div>
    <!-- /.wrapper -->
   
  <?= $this->include('templates/footer'); ?>
    <!-- /.dark-wrapper -->