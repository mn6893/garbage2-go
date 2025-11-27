<?= $this->include('templates/header_pages'); ?>
    
    <div class="wrapper image-wrapper bg-image page-title-wrapper inverse-text" data-image-src="style/images/art/bg3.jpg">
      <div class="container inner text-center">
        <div class="space90"></div>
        <h1 class="page-title">Contact GarbageToGo</h1>
        <p class="lead">Ready to schedule your waste removal service? We're here to help!</p>
      </div>
      <!-- /.container -->
    </div>
    <!-- /.wrapper -->
    <div class="wrapper light-wrapper">
      <div class="container inner">
        <h2 class="section-title">Get Your Free Quote Today</h2>
        <p class="lead larger">Need garbage removal services? Contact us for a free estimate and let us handle your waste management needs efficiently and affordably.</p>
        <div class="space40"></div>
        
        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert" id="contact-success">
                <i class="icofont-check-circled mr-10"></i>
                <?= session()->getFlashdata('success') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>
        
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
          <div class="col-lg-7">
            <form id="contact-form" class="fields-white" method="post" action="<?= base_url('contact/submit') ?>">
              <?= csrf_field() ?>
              <div class="controls">
                <div class="form-row">
                  <div class="col-lg-12 col-xl-6">
                    <div class="form-group">
                      <input id="form_name" type="text" name="name" class="form-control" 
                             placeholder="First Name *" 
                             value="<?= old('name', isset($input['name']) ? esc($input['name']) : '') ?>" 
                             required>
                      <?php if (isset($validation) && $validation->hasError('name')): ?>
                          <div class="text-danger small mt-1"><?= $validation->getError('name') ?></div>
                      <?php endif; ?>
                    </div>
                  </div>
                  <div class="col-lg-12 col-xl-6">
                    <div class="form-group">
                      <input id="form_lastname" type="text" name="surname" class="form-control" 
                             placeholder="Last Name *" 
                             value="<?= old('surname', isset($input['surname']) ? esc($input['surname']) : '') ?>" 
                             required>
                      <?php if (isset($validation) && $validation->hasError('surname')): ?>
                          <div class="text-danger small mt-1"><?= $validation->getError('surname') ?></div>
                      <?php endif; ?>
                    </div>
                  </div>
                </div>
                <div class="form-row">
                  <div class="col-lg-12 col-xl-6">
                    <div class="form-group">
                      <input id="form_email" type="email" name="email" class="form-control" 
                             placeholder="Email *" 
                             value="<?= old('email', isset($input['email']) ? esc($input['email']) : '') ?>" 
                             required>
                      <?php if (isset($validation) && $validation->hasError('email')): ?>
                          <div class="text-danger small mt-1"><?= $validation->getError('email') ?></div>
                      <?php endif; ?>
                    </div>
                  </div>
                  <div class="col-lg-12 col-xl-6">
                    <div class="form-group">
                      <input id="form_phone" type="tel" name="phone" class="form-control" 
                             placeholder="Phone" 
                             value="<?= old('phone', isset($input['phone']) ? esc($input['phone']) : '') ?>">
                      <?php if (isset($validation) && $validation->hasError('phone')): ?>
                          <div class="text-danger small mt-1"><?= $validation->getError('phone') ?></div>
                      <?php endif; ?>
                    </div>
                  </div>
                </div>
                <div class="form-row">
                  <div class="col-lg-12 col-xl-6">
                    <div class="form-group">
                      <input id="form_location" type="text" name="location" class="form-control" 
                             placeholder="Service Location (City, Province)" 
                             value="<?= old('location', isset($input['location']) ? esc($input['location']) : '') ?>">
                      <?php if (isset($validation) && $validation->hasError('location')): ?>
                          <div class="text-danger small mt-1"><?= $validation->getError('location') ?></div>
                      <?php endif; ?>
                    </div>
                  </div>
                  <div class="col-lg-12 col-xl-6">
                    <div class="form-group">
                      <select id="form_service" name="service_type" class="form-control">
                        <option value="">Select Service Type</option>
                        <option value="household" <?= old('service_type', isset($input['service_type']) ? $input['service_type'] : '') === 'household' ? 'selected' : '' ?>>Household Junk Removal</option>
                        <option value="commercial" <?= old('service_type', isset($input['service_type']) ? $input['service_type'] : '') === 'commercial' ? 'selected' : '' ?>>Commercial Cleanout</option>
                        <option value="construction" <?= old('service_type', isset($input['service_type']) ? $input['service_type'] : '') === 'construction' ? 'selected' : '' ?>>Construction Debris</option>
                        <option value="estate" <?= old('service_type', isset($input['service_type']) ? $input['service_type'] : '') === 'estate' ? 'selected' : '' ?>>Estate Cleanout</option>
                        <option value="furniture" <?= old('service_type', isset($input['service_type']) ? $input['service_type'] : '') === 'furniture' ? 'selected' : '' ?>>Furniture Removal</option>
                        <option value="appliance" <?= old('service_type', isset($input['service_type']) ? $input['service_type'] : '') === 'appliance' ? 'selected' : '' ?>>Appliance Removal</option>
                        <option value="yard" <?= old('service_type', isset($input['service_type']) ? $input['service_type'] : '') === 'yard' ? 'selected' : '' ?>>Yard Waste</option>
                        <option value="emergency" <?= old('service_type', isset($input['service_type']) ? $input['service_type'] : '') === 'emergency' ? 'selected' : '' ?>>Emergency Service</option>
                        <option value="other" <?= old('service_type', isset($input['service_type']) ? $input['service_type'] : '') === 'other' ? 'selected' : '' ?>>Other</option>
                      </select>
                      <?php if (isset($validation) && $validation->hasError('service_type')): ?>
                          <div class="text-danger small mt-1"><?= $validation->getError('service_type') ?></div>
                      <?php endif; ?>
                    </div>
                  </div>
                </div>
                <div class="form-row">
                  <div class="col-md-12">
                    <div class="form-group">
                      <textarea id="form_message" name="message" class="form-control" 
                                placeholder="Describe your junk removal needs (type of items, quantity, access details) *" 
                                rows="4" required><?= old('message', isset($input['message']) ? esc($input['message']) : '') ?></textarea>
                      <?php if (isset($validation) && $validation->hasError('message')): ?>
                          <div class="text-danger small mt-1"><?= $validation->getError('message') ?></div>
                      <?php endif; ?>
                    </div>
                  </div>
                  <div class="col-md-12">
                    <input type="submit" class="btn btn-send" value="Get Free Quote">
                  </div>
                </div>
                <div class="form-row">
                  <div class="col-md-12">
                    <p class="text-muted"><strong>*</strong> These fields are required.</p>
                  </div>
                </div>
              </div>
            </form>
            <!-- /form -->
          </div>
          <!--/column -->
          <div class="space30 d-none d-md-block d-lg-none"></div>
          <div class="col-lg-4 offset-lg-1">
            <div class="d-flex flex-row">
              <div>
                <div class="icon color-default fs-34 mr-25"> <i class="icofont-location-pin"></i> </div>
              </div>
              <div>
                <h6 class="mb-5">Headquarters</h6>
                <address>1201-15 Cougar Court<br />Scarborough, ON M1J 3E4<br />Canada</address>
              </div>
            </div>
            <div class="d-flex flex-row">
              <div>
                <div class="icon color-default fs-34 mr-25"> <i class="icofont-telephone"></i> </div>
              </div>
              <div>
                <h6 class="mb-5">Phone</h6>
                <p>+1 (647) 913-8775</p>
              </div>
            </div>
            <div class="d-flex flex-row">
              <div>
                <div class="icon color-default fs-34 mr-25"> <i class="icofont-mail-box"></i> </div>
              </div>
              <div>
                <h6 class="mb-5">E-mail</h6>
                <p><a href="mailto:info@garbagetogo.ca" class="nocolor">info@garbagetogo.ca</a> <br class="d-none d-md-block" /><a href="mailto:garbage2go.ca@gmail.com" class="nocolor">garbage2go.ca@gmail.com</a></p>
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
    
    <!-- Quick Contact Options -->
    <div class="wrapper grey-wrapper">
      <div class="container inner">
        <div class="row">
          <div class="col-md-12 text-center">
            <h3 class="section-title">Quick Contact Options</h3>
            <p class="lead">Choose the most convenient way to reach us</p>
            <div class="space30"></div>
          </div>
        </div>
        
        <div class="row">
          <div class="col-lg-4 col-md-6">
            <div class="text-center">
              <div class="icon color-default fs-48 mb-20">
                <i class="icofont-phone"></i>
              </div>
              <h5>Call Us</h5>
              <p>Speak directly with our team for immediate assistance and quotes.</p>
              <a href="tel:+16479138775" class="btn btn-sm btn-outline-default">+1 (647) 913-8775</a>
            </div>
          </div>
          
          <div class="col-lg-4 col-md-6">
            <div class="text-center">
              <div class="icon color-default fs-48 mb-20">
                <i class="icofont-envelope"></i>
              </div>
              <h5>Email Us</h5>
              <p>Send us details about your project and we'll respond within 2 hours.</p>
              <a href="mailto:info@garbagetogo.ca" class="btn btn-sm btn-outline-default">info@garbagetogo.ca</a>
            </div>
          </div>

          <div class="col-lg-4 col-md-6">
            <div class="text-center">
              <div class="icon color-default fs-48 mb-20">
                <i class="icofont-calendar"></i>
              </div>
              <h5>Book Online</h5>
              <p>Schedule your pickup at your convenience through our online system.</p>
              <a href="<?= base_url('quote') ?>" class="btn btn-sm btn-outline-default">Book Now</a>
            </div>
          </div>
        </div>
      </div>
    </div>
    
    <!-- Service Types Section -->
    <div class="wrapper gray-wrapper">
      <div class="container inner">
        <div class="row">
          <div class="col-md-12 text-center">
            <h3 class="section-title">What We Remove</h3>
            <p class="lead">Professional removal services for all types of waste</p>
            <div class="space30"></div>
          </div>
        </div>
        
        <div class="row">
          <div class="col-lg-4 col-md-6">
            <div class="card">
              <div class="card-body text-center">
                <div class="icon color-default fs-36 mb-15">
                  <i class="icofont-home"></i>
                </div>
                <h6>Household Items</h6>
                <p class="mb-0">Furniture, appliances, electronics, mattresses, and general household clutter.</p>
              </div>
            </div>
          </div>
          
          <div class="col-lg-4 col-md-6">
            <div class="card">
              <div class="card-body text-center">
                <div class="icon color-default fs-36 mb-15">
                  <i class="icofont-building"></i>
                </div>
                <h6>Commercial Waste</h6>
                <p class="mb-0">Office furniture, equipment, renovation debris, and commercial cleanouts.</p>
              </div>
            </div>
          </div>
          
          <div class="col-lg-4 col-md-6">
            <div class="card">
              <div class="card-body text-center">
                <div class="icon color-default fs-36 mb-15">
                  <i class="icofont-hammer"></i>
                </div>
                <h6>Construction Debris</h6>
                <p class="mb-0">Drywall, lumber, tiles, fixtures, and other renovation materials.</p>
              </div>
            </div>
          </div>
          
          <div class="col-lg-4 col-md-6">
            <div class="card">
              <div class="card-body text-center">
                <div class="icon color-default fs-36 mb-15">
                  <i class="icofont-plant"></i>
                </div>
                <h6>Yard Waste</h6>
                <p class="mb-0">Branches, leaves, grass clippings, and other organic garden waste.</p>
              </div>
            </div>
          </div>
          
          <div class="col-lg-4 col-md-6">
            <div class="card">
              <div class="card-body text-center">
                <div class="icon color-default fs-36 mb-15">
                  <i class="icofont-ui-delete"></i>
                </div>
                <h6>Estate Cleanouts</h6>
                <p class="mb-0">Complete property cleanouts with respectful handling of personal items.</p>
              </div>
            </div>
          </div>
          
          <div class="col-lg-4 col-md-6">
            <div class="card">
              <div class="card-body text-center">
                <div class="icon color-default fs-36 mb-15">
                  <i class="icofont-recycle"></i>
                </div>
                <h6>Recycling Services</h6>
                <p class="mb-0">Eco-friendly disposal with sorting and recycling of applicable materials.</p>
              </div>
            </div>
          </div>
        </div>
        
        <div class="row mt-40">
          <div class="col-md-12 text-center">
            <div class="alert alert-icon alert-success" role="alert">
              <i class="icofont-leaf"></i>
              <div class="alert-content">
                <h6>Eco-Friendly Commitment</h6>
                <p class="mb-0">We donate usable items and recycle up to 80% of collected materials, keeping waste out of landfills.</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    
    <!-- /.wrapper -->
    <?= $this->include('templates/footer'); ?>