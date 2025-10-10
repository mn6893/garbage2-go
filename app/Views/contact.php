<?= $this->include('templates/header_pages'); ?>
    
    <div class="wrapper image-wrapper bg-image page-title-wrapper inverse-text" data-image-src="style/images/art/bg3.jpg">
      <div class="container inner text-center">
        <div class="space90"></div>
        <h1 class="page-title">Contact Garbage2Go</h1>
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
        <div class="row">
          <div class="col-lg-7">
            <form id="contact-form" class="fields-white" method="post" action="contact/contact.php">
              <div class="messages"></div>
              <div class="controls">
                <div class="form-row">
                  <div class="col-lg-12 col-xl-6">
                    <div class="form-group">
                      <input id="form_name" type="text" name="name" class="form-control" placeholder="First Name *" required="required" data-error="First Name is required.">
                      <div class="help-block with-errors"></div>
                    </div>
                  </div>
                  <div class="col-lg-12 col-xl-6">
                    <div class="form-group">
                      <input id="form_lastname" type="text" name="surname" class="form-control" placeholder="Last Name *" required="required" data-error="Last Name is required.">
                      <div class="help-block with-errors"></div>
                    </div>
                  </div>
                </div>
                <div class="form-row">
                  <div class="col-lg-12 col-xl-6">
                    <div class="form-group">
                      <input id="form_email" type="email" name="email" class="form-control" placeholder="Email *" required="required" data-error="Valid email is required.">
                      <div class="help-block with-errors"></div>
                    </div>
                  </div>
                  <div class="col-lg-12 col-xl-6">
                    <div class="form-group">
                      <input id="form_phone" type="tel" name="phone" class="form-control" placeholder="Phone">
                      <div class="help-block with-errors"></div>
                    </div>
                  </div>
                </div>
                <div class="form-row">
                  <div class="col-lg-12 col-xl-6">
                    <div class="form-group">
                      <input id="form_location" type="text" name="location" class="form-control" placeholder="Service Location (City, Province)">
                      <div class="help-block with-errors"></div>
                    </div>
                  </div>
                  <div class="col-lg-12 col-xl-6">
                    <div class="form-group">
                      <select id="form_service" name="service_type" class="form-control">
                        <option value="">Select Service Type</option>
                        <option value="household">Household Junk Removal</option>
                        <option value="commercial">Commercial Cleanout</option>
                        <option value="construction">Construction Debris</option>
                        <option value="estate">Estate Cleanout</option>
                        <option value="furniture">Furniture Removal</option>
                        <option value="appliance">Appliance Removal</option>
                        <option value="yard">Yard Waste</option>
                        <option value="emergency">Emergency Service</option>
                        <option value="other">Other</option>
                      </select>
                      <div class="help-block with-errors"></div>
                    </div>
                  </div>
                </div>
                <div class="form-row">
                  <div class="col-md-12">
                    <div class="form-group">
                      <textarea id="form_message" name="message" class="form-control" placeholder="Describe your junk removal needs (type of items, quantity, access details) *" rows="4" required="required" data-error="Message is required."></textarea>
                      <div class="help-block with-errors"></div>
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
                <address>1250 Bay Street, Suite 200 <br class="d-none d-md-block" />Toronto, ON M5R 2A4, Canada</address>
              </div>
            </div>
            <div class="d-flex flex-row">
              <div>
                <div class="icon color-default fs-34 mr-25"> <i class="icofont-telephone"></i> </div>
              </div>
              <div>
                <h6 class="mb-5">Phone</h6>
                <p>+1 (416) 555-JUNK <br class="d-none d-md-block" />+1 (800) 742-2463</p>
              </div>
            </div>
            <div class="d-flex flex-row">
              <div>
                <div class="icon color-default fs-34 mr-25"> <i class="icofont-mail-box"></i> </div>
              </div>
              <div>
                <h6 class="mb-5">E-mail</h6>
                <p><a href="mailto:info@garbage2go.com" class="nocolor">info@garbage2go.com</a> <br class="d-none d-md-block" /><a href="mailto:support@garbage2go.com" class="nocolor">support@garbage2go.com</a></p>
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
              <a href="tel:+14165555865" class="btn btn-sm btn-outline-default">+1 (416) 555-JUNK</a>
            </div>
          </div>
          
          <div class="col-lg-4 col-md-6">
            <div class="text-center">
              <div class="icon color-default fs-48 mb-20">
                <i class="icofont-envelope"></i>
              </div>
              <h5>Email Us</h5>
              <p>Send us details about your project and we'll respond within 2 hours.</p>
              <a href="mailto:info@garbage2go.com" class="btn btn-sm btn-outline-default">info@garbage2go.com</a>
            </div>
          </div>

          <div class="col-lg-4 col-md-6">
            <div class="text-center">
              <div class="icon color-default fs-48 mb-20">
                <i class="icofont-calendar"></i>
              </div>
              <h5>Book Online</h5>
              <p>Schedule your pickup at your convenience through our online system.</p>
              <a href="/" class="btn btn-sm btn-outline-default">Book Now</a>
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