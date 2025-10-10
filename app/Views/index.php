<?= $this->include('templates/header'); ?>
          
    <!-- /section -->
    <div class="wrapper image-wrapper bg-image page-title-wrapper inverse-text" data-image-src="style/images/art/bg32.jpg">
      <div class="container inner text-center">
        <div class="space140"></div>
         <h1 class="display-1">Garbage2Go - We <span class="typer color-default" id="typer" data-delay="100" data-delim=":" data-words="remove junk:clear debris:dispose appliances:handle cleanouts:manage waste:recycle responsibly"></span><span class="cursor color-default" data-owner="typer"></span></h1>
         <p class="lead larger text-center mt-20">Professional Junk Removal Services | Residential Cleanouts | Commercial Waste Management | Construction Debris Removal | Appliance Disposal</p>
        <!-- <h1 class="display-1">We love to clear your junk <br class="d-none d-lg-block" />and make your space beautiful</h1> -->
        <div class="space20"></div>
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
              <li><i class="jam jam-check" style="background-color: #003366; color: white; border-radius: 50%; padding: 2px; font-size: 16px;"></i> <span style="font-size: 16px;"> Garbage2Go trucks from 2m³ to 77m³</span></li>
              <li><i class="jam jam-check" style="background-color: #003366; color: white; border-radius: 50%; padding: 2px; font-size: 16px;"></i> <span style="font-size: 16px;"> Up to 50% cheaper per cubic metre than our competitors</span></li>
              <li><i class="jam jam-check" style="background-color: #003366; color: white; border-radius: 50%; padding: 2px; font-size: 16px;"></i> <span style="font-size: 16px;"> We service most areas of Ontario including Toronto, Ottawa, Hamilton, London & surrounding regions</span></li>
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
              <div class="row" id="filePreview"></div>
            </div>
          </div></br>
              
              <form>
          <div class="row">
            <div class="col-md-6">
              <div class="mb-3">
          <label for="fullname" class="form-label">Full Name *</label>
          <input type="text" class="form-control" id="fullname" name="fullname" placeholder="Your Full Name" required>
              </div>
            </div>
            <div class="col-md-6">
              <div class="mb-3">
          <label for="email" class="form-label">Email Address *</label>
          <input type="email" class="form-control" id="email" name="email" placeholder="Your Email" required>
              </div>
            </div>
          </div>
          
          <div class="row">
            <div class="col-md-6">
              <div class="mb-3">
          <label for="phone" class="form-label">Phone Number *</label>
          <input type="tel" class="form-control" id="phone" name="phone" placeholder="Phone Number" required>
              </div>
            </div>
            <div class="col-md-6">
              <div class="mb-3">
          <label for="suburb" class="form-label">Suburb/City</label>
          <input type="text" class="form-control" id="suburb" name="suburb" placeholder="Suburb/City">
              </div>
            </div>
          </div>
          
          <div class="mb-3">
            <label for="address" class="form-label">Complete Address *</label>
            <textarea class="form-control" id="address" name="address" placeholder="Your Address" style="height: 80px" required></textarea>
          </div>
          
          <div class="mb-3">
            <label for="junk_description" class="form-label">Tell us about your Junk *</label>
            <textarea class="form-control" id="junk_description" name="junk_description" placeholder="Tell us about your junk" style="height: 120px" required></textarea>
          </div>

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
          
          <div class="text-center">
            <button type="submit" class="btn btn-l btn-default">Get Free Quote</button>
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
          <figure><img src="style/images/art/junk-removal-truck.png" alt="Garbage2Go Junk Removal Truck" /></figure>
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
        <div class="player" data-plyr-provider="youtube" data-plyr-embed-id="dQw4w9WgXcQ"></div>
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
        <a href="#portfolio" class="btn mb-0" style="background: #003366; color: white;">Get Free Quote</a>
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
    <div class="wrapper light-wrapper">
      <div class="container inner">
      <h2 class="section-title text-uppercase text-center mb-40 color-default">Service Areas</h2>
      <div class="grid-view">
        <div class="carousel owl-carousel" data-margin="30" data-dots="true" data-autoplay="false" data-autoplay-timeout="5000" data-responsive='{"0":{"items": "1"}, "768":{"items": "2"}, "992":{"items": "3"}}'>
        <div class="item">
          <figure class="overlay overlay1 rounded mb-30"><a href="#"> <img src="style/images/art/toronto-area.jpg" alt="Toronto Junk Removal" /></a>
          <figcaption>
            <h5 class="from-top mb-0">Learn More</h5>
          </figcaption>
          </figure>
          <div class="category"><a href="#" class="badge badge-pill" style="background: #003366; color: white;">GTA</a></div>
          <h2 class="post-title"><a href="#">Toronto & Greater Toronto Area Junk Removal Services</a></h2>
          <div class="meta mb-0"><span class="date"><i class="jam jam-map-marker" style="color: #003366;"></i>Toronto, Mississauga, Brampton</span></div>
        </div>
        <!-- /.item -->
        <div class="item">
          <figure class="overlay overlay1 rounded mb-30"><a href="#"> <img src="style/images/art/ottawa-area.jpg" alt="Ottawa Junk Removal" /></a>
          <figcaption>
            <h5 class="from-top mb-0">Learn More</h5>
          </figcaption>
          </figure>
          <div class="category"><a href="#" class="badge badge-pill" style="background: #00558b; color: white;">Capital</a></div>
          <h2 class="post-title"><a href="#">Ottawa & Surrounding Areas Waste Management</a></h2>
          <div class="meta mb-0"><span class="date"><i class="jam jam-map-marker" style="color: #003366;"></i>Ottawa, Gatineau, Kanata</span></div>
        </div>
        <!-- /.item -->
        <div class="item">
          <figure class="overlay overlay1 rounded mb-30"><a href="#"> <img src="style/images/art/hamilton-area.jpg" alt="Hamilton Junk Removal" /></a>
          <figcaption>
            <h5 class="from-top mb-0">Learn More</h5>
          </figcaption>
          </figure>
          <div class="category"><a href="#" class="badge badge-pill" style="background: #007bb8; color: white;">Hamilton</a></div>
          <h2 class="post-title"><a href="#">Hamilton & Burlington Junk Removal Services</a></h2>
          <div class="meta mb-0"><span class="date"><i class="jam jam-map-marker" style="color: #003366;"></i>Hamilton, Burlington, Oakville</span></div>
        </div>
        <!-- /.item -->
        <div class="item">
          <figure class="overlay overlay1 rounded mb-30"><a href="#"> <img src="style/images/art/london-area.jpg" alt="London Junk Removal" /></a>
          <figcaption>
            <h5 class="from-top mb-0">Learn More</h5>
          </figcaption>
          </figure>
          <div class="category"><a href="#" class="badge badge-pill" style="background: #0099e5; color: white;">Southwest</a></div>
          <h2 class="post-title"><a href="#">London Ontario Residential & Commercial Cleanouts</a></h2>
          <div class="meta mb-0"><span class="date"><i class="jam jam-map-marker" style="color: #003366;"></i>London, Kitchener, Windsor</span></div>
        </div>
        <!-- /.item -->
        </div>
        <!-- /.owl-carousel -->
      </div>
      <!-- /.grid-view -->
      </div>
      <!-- /.container -->
    </div>
    <!-- /.wrapper -->
  <?= $this->include('templates/footer'); ?>
    <!-- /.dark-wrapper -->