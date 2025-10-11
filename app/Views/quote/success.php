<?= $this->include('templates/header_pages'); ?>
    
    <div class="wrapper image-wrapper bg-image page-title-wrapper inverse-text" data-image-src="<?= base_url('style/images/art/bg3.jpg') ?>">
      <div class="container inner text-center">
        <div class="space90"></div>
        <h1 class="page-title">Quote Request Submitted</h1>
        <p class="lead">Thank you for choosing our junk removal services!</p>
      </div>
      <!-- /.container -->
    </div>
    <!-- /.wrapper -->
    
    <div class="wrapper light-wrapper">
      <div class="container inner">
        <div class="row">
          <div class="col-lg-8 mx-auto text-center">
            
            <?php if (session()->getFlashdata('success')): ?>
                <div class="alert alert-success alert-lg" role="alert">
                    <div class="icon fs-48 mb-20">
                        <i class="icofont-check-circled color-green"></i>
                    </div>
                    <h3 class="mb-15">Quote Request Received!</h3>
                    <p class="lead"><?= session()->getFlashdata('success') ?></p>
                </div>
            <?php else: ?>
                <div class="alert alert-success alert-lg" role="alert">
                    <div class="icon fs-48 mb-20">
                        <i class="icofont-check-circled color-green"></i>
                    </div>
                    <h3 class="mb-15">Quote Request Received!</h3>
                    <p class="lead">Your quote request has been submitted successfully! We will contact you soon.</p>
                </div>
            <?php endif; ?>
            
            <div class="space30"></div>
            
            <div class="row">
              <div class="col-md-4">
                <div class="text-center mb-30">
                  <div class="icon color-default fs-38 mb-15">
                    <i class="icofont-clock-time"></i>
                  </div>
                  <h5>Quick Response</h5>
                  <p class="mb-0">We'll contact you within 24 hours with your free quote</p>
                </div>
              </div>
              <div class="col-md-4">
                <div class="text-center mb-30">
                  <div class="icon color-default fs-38 mb-15">
                    <i class="icofont-money"></i>
                  </div>
                  <h5>Free Estimate</h5>
                  <p class="mb-0">No hidden fees - transparent pricing for all services</p>
                </div>
              </div>
              <div class="col-md-4">
                <div class="text-center mb-30">
                  <div class="icon color-default fs-38 mb-15">
                    <i class="icofont-calendar"></i>
                  </div>
                  <h5>Flexible Scheduling</h5>
                  <p class="mb-0">Same-day and next-day service available</p>
                </div>
              </div>
            </div>
            
            <div class="space40"></div>
            
            <div class="box bg-light p-30">
              <h4 class="mb-15">What Happens Next?</h4>
              <div class="row text-left">
                <div class="col-md-6">
                  <p class="mb-15"><span class="badge badge-circle bg-purple text-white mr-15">1</span><strong>Review:</strong> Our team will review your request details</p>
                  <p class="mb-15"><span class="badge badge-circle bg-purple text-white mr-15">2</span><strong>Contact:</strong> We'll call you to discuss your needs and schedule</p>
                </div>
                <div class="col-md-6">
                  <p class="mb-15"><span class="badge badge-circle bg-purple text-white mr-15">3</span><strong>Quote:</strong> Receive your free, no-obligation estimate</p>
                  <p class="mb-15"><span class="badge badge-circle bg-purple text-white mr-15">4</span><strong>Service:</strong> Book your junk removal appointment</p>
                </div>
              </div>
            </div>
            
            <div class="space40"></div>
            
            <div class="text-center">
              <h4 class="mb-15">Need to Speak with Us Now?</h4>
              <p class="mb-20">For immediate assistance or urgent requests, call us directly:</p>
              <p class="h4 mb-20">
                <i class="icofont-phone mr-10"></i> 
                <a href="tel:+14165555865" class="color-purple">+1 (416) 555-JUNK</a>
              </p>
              <p class="text-muted">Available 7 days a week, 8 AM - 8 PM</p>
            </div>
            
            <div class="space40"></div>
            
            <div class="text-center">
              <a href="<?= base_url('/') ?>" class="btn btn-purple mr-15">Return to Home</a>
              <a href="<?= base_url('quote') ?>" class="btn btn-outline-purple">Submit Another Quote</a>
            </div>
            
          </div>
          <!--/column -->
        </div>
        <!--/.row -->
      </div>
      <!-- /.container -->
    </div>
    <!-- /.wrapper -->
    
    <!-- Contact Section -->
    <div class="wrapper grey-wrapper">
      <div class="container inner">
        <div class="row">
          <div class="col-md-12 text-center">
            <h3 class="section-title mb-30">Still Have Questions?</h3>
            <div class="row">
              <div class="col-md-4">
                <div class="text-center">
                  <div class="icon color-default fs-38 mb-15">
                    <i class="icofont-mail-box"></i>
                  </div>
                  <h6>Email Us</h6>
                  <p><a href="mailto:info@garbagetogo.com" class="nocolor">info@garbagetogo.com</a></p>
                </div>
              </div>
              <div class="col-md-4">
                <div class="text-center">
                  <div class="icon color-default fs-38 mb-15">
                    <i class="icofont-live-support"></i>
                  </div>
                  <h6>Live Chat</h6>
                  <p>Available on our website</p>
                </div>
              </div>
              <div class="col-md-4">
                <div class="text-center">
                  <div class="icon color-default fs-38 mb-15">
                    <i class="icofont-location-pin"></i>
                  </div>
                  <h6>Visit Us</h6>
                  <p>1250 Bay Street, Suite 200<br>Toronto, ON M5R 2A4</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

<?= $this->include('templates/footer'); ?>