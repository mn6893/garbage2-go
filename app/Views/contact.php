<?= $this->include('templates/header_pages'); ?>
    
    <div class="wrapper image-wrapper bg-image page-title-wrapper inverse-text" data-image-src="style/images/art/bg3.jpg">
      <div class="container inner text-center">
        <div class="space90"></div>
        <h1 class="page-title">Contact Us</h1>
        <p class="lead">Aenean lacinia bibendum nulla sed consectetur</p>
      </div>
      <!-- /.container -->
    </div>
    <!-- /.wrapper -->
    <div class="wrapper light-wrapper">
      <div class="container inner">
        <h2 class="section-title">Get in Touch</h2>
        <p class="lead larger">Have any questions? Reach out to us from our contact form and we will get back to you shortly.</p>
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
                  <div class="col-md-12">
                    <div class="form-group">
                      <textarea id="form_message" name="message" class="form-control" placeholder="Message *" rows="4" required="required" data-error="Message is required."></textarea>
                      <div class="help-block with-errors"></div>
                    </div>
                  </div>
                  <div class="col-md-12">
                    <input type="submit" class="btn btn-send" value="Send message">
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
                <h6 class="mb-5">Address</h6>
                <address>Moonshine St. 14/05 Light City, <br class="d-none d-md-block" />London, United Kingdom</address>
              </div>
            </div>
            <div class="d-flex flex-row">
              <div>
                <div class="icon color-default fs-34 mr-25"> <i class="icofont-telephone"></i> </div>
              </div>
              <div>
                <h6 class="mb-5">Phone</h6>
                <p>00 (123) 456 78 90 <br class="d-none d-md-block" />00 (987) 654 32 10</p>
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
    <?= $this->include('templates/footer'); ?>