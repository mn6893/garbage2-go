<?= $this->include('templates/header'); ?>
          
    <!-- /section -->
    <div class="wrapper image-wrapper bg-image page-title-wrapper inverse-text" data-image-src="style/images/art/bg32.jpg">
      <div class="container inner text-center">
        <div class="space140"></div>
         <h1 class="display-1">Garbage2Go - We <span class="typer color-default" id="typer" data-delay="100" data-delim=":" data-words="remove junk:clear debris:dispose appliances:handle cleanouts:manage waste:recycle responsibly"></span><span class="cursor color-default" data-owner="typer"></span></h1>
         <p class="lead larger text-center mt-20">Professional Junk Removal Services | Residential Cleanouts | Commercial Waste Management | Construction Debris Removal | Appliance Disposal</p>
        <h1 class="display-1">We love to clear your junk <br class="d-none d-lg-block" />and make your space beautiful</h1>
        <div class="space20"></div>
        <a href="#portfolio" class="btn btn-l btn-default scroll" style="color: #003366 !important;">Request a Quote </a>
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
              <figure><img src="style/images/art/about7.png" alt="" /></figure>
              <div class="row counter counter-s position-absolute" style="top: 45%; right: 8%;">
                <div class="col-md-10 text-center">
                  <div class="box bg-white shadow">
                    <h3><span class="value">450</span>+</h3>
                    <p>Completed <br class="d-md-none" />Projects</p>
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
            <h2 class="section-title text-uppercase mb-20">What We Do?</h2>
            <p class="lead larger">We specialize in web, mobile, brand & product design. We love to turn ideas into beautiful things.</p>
            <div class="space20"></div>
            <ul class="progress-list">
              <li>
                <p>Web Development</p>
                <div class="progressbar line pink" data-value="100"></div>
              </li>
              <li>
                <p>Product Design</p>
                <div class="progressbar line blue" data-value="80"></div>
              </li>
              <li>
                <p>Identity & Branding</p>
                <div class="progressbar line green" data-value="85"></div>
              </li>
              <li>
                <p>Mobile Development</p>
                <div class="progressbar line purple" data-value="90"></div>
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
            <div class="box bg-pink shadow inverse-text">
              <div class="icon color-white fs-42 mb-20"> <i class="icofont-code"></i> </div>
              <h5>Web Development</h5>
              <p>Nulla vitae elit libero, a pharetra augue. Donec id elit non mi porta gravida.</p>
            </div>
            <!--/.box -->
          </div>
          <!--/column -->
          <div class="col-md-6 col-lg-3">
            <div class="box bg-blue shadow inverse-text">
              <div class="icon color-white fs-42 mb-20"> <i class="icofont-magic-alt"></i> </div>
              <h5>Product Design</h5>
              <p>Nulla vitae elit libero, a pharetra augue. Donec id elit non mi porta gravida.</p>
            </div>
            <!--/.box -->
          </div>
          <!--/column -->
          <div class="space30 d-none d-md-block d-lg-none"></div>
          <div class="col-md-6 col-lg-3">
            <div class="box bg-green shadow inverse-text">
              <div class="icon color-white fs-42 mb-20"> <i class="icofont-paint"></i> </div>
              <h5>Identity & Branding</h5>
              <p>Nulla vitae elit libero, a pharetra augue. Donec id elit non mi porta gravida.</p>
            </div>
            <!--/.box -->
          </div>
          <!--/column -->
          <div class="col-md-6 col-lg-3">
            <div class="box bg-purple shadow inverse-text">
              <div class="icon color-white fs-42 mb-20"> <i class="icofont-smart-phone"></i> </div>
              <h5>Mobile Development</h5>
              <p>Nulla vitae elit libero, a pharetra augue. Donec id elit non mi porta gravida.</p>
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
            <div class="player" data-plyr-provider="vimeo" data-plyr-embed-id="165101721"></div>
          </div>
          <!--/column -->
          <div class="space30 d-none d-md-block d-lg-none"></div>
          <div class="col-lg-4 pl-50 pl-md-15">
            <h2 class="section-title text-uppercase mb-20">Our Process</h2>
            <p>Curabitur nec orci orci. Ut fringilla nisl quis consectetur ultrices. Morbi mattis nisl vitae fringilla volutpat. Ut molestie metus vitae imperdiet. Maecenas ornare ut lectus ac volutpat. Vestibulum erat massa finibus.</p>
            <ul class="icon-list bullet-default">
              <li><i class="jam jam-arrow-right"></i>Aenean eu leo quam ornare.</li>
              <li><i class="jam jam-arrow-right"></i>Nullam quis risus eget mollis.</li>
              <li><i class="jam jam-arrow-right"></i>Donec elit non mi porta gravida.</li>
              <li><i class="jam jam-arrow-right"></i>Fusce dapibus cursus commodo.</li>
            </ul>
            <div class="space10"></div>
            <a href="#" class="btn mb-0">More Details</a>
          </div>
          <!--/column -->
        </div>
        <!--/.row -->
        <div class="space80"></div>
        <div class="row gutter-60 gutter-md-30">
          <div class="col-lg-4">
            <div class="d-flex flex-row">
              <div>
                <span class="icon icon-bg icon-bg-default color-default mr-25"><span class="number">1</span></span>
              </div>
              <div>
                <h5>Collect Ideas</h5>
                <p class="mb-0">Cum sociis natoque penatibus et magnis dis parturient montes nascetur.</p>
              </div>
            </div>
          </div>
          <!--/column -->
          <div class="space30 d-none d-md-block d-lg-none"></div>
          <div class="col-lg-4">
            <div class="d-flex flex-row">
              <div>
                <span class="icon icon-bg icon-bg-default color-default mr-25"><span class="number">2</span></span>
              </div>
              <div>
                <h5>Data Analysis</h5>
                <p class="mb-0">Aenean lacinia bibendum nulla sed consectetur. Nulla vitae elit libero.</p>
              </div>
            </div>
          </div>
          <!--/column -->
          <div class="space30 d-none d-md-block d-lg-none"></div>
          <div class="col-lg-4">
            <div class="d-flex flex-row">
              <div>
                <span class="icon icon-bg icon-bg-default color-default mr-25"><span class="number">3</span></span>
              </div>
              <div>
                <h5>Magic Touch</h5>
                <p class="mb-0">Pellentesque ornare sem lacinia quam venenatis vestibulum. Integer posuere.</p>
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
    <div class="wrapper gray-wrapper">
      <div class="container inner">
        <div class="row align-items-center">
          <div class="col-lg-6 order-lg-2">
            <div>
              <figure><img src="style/images/art/about9.png" alt="" /></figure>
              <div class="row counter counter-s position-absolute" style="top: 10%; left: 25%;">
                <div class="col-md-10 text-center">
                  <div class="box bg-white shadow">
                    <h3><span class="value">500</span>+</h3>
                    <p>Happy <br class="d-md-none" />Customers</p>
                  </div>
                  <!--/.box -->
                </div>
                <!--/column -->
              </div>
              <!--/.row -->
            </div>
          </div>
          <!--/column -->
          <div class="space20 d-md-none"></div>
          <div class="space50 d-none d-md-block d-lg-none"></div>
          <div class="col-lg-6 pr-60 pr-md-15">
            <div class="basic-slider owl-carousel gap-small text-center" data-margin="30">
              <div class="item">
                <blockquote class="icon larger">
                  <p>"Sed posuere consectetur est at lobortis. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis mollis, est non commodo luctus, nisi erat."</p>
                  <div class="blockquote-details justify-content-center">
                    <div class="img-blob blob1"><img src="style/images/art/t5.jpg" alt="" /></div>
                    <div class="info">
                      <h6 class="mb-0">Cornelia Gibson</h6>
                      <span class="meta mb-0">Financial Analyst</span>
                    </div>
                  </div>
                </blockquote>
              </div>
              <!-- /.item -->
              <div class="item">
                <blockquote class="icon larger">
                  <p>"Vivamus sagittis lacus vel augue laoreet rutrum faucibus dolor auctor. Vestibulum id ligula porta felis euismod semper. Cras justo odio, dapibus."</p>
                  <div class="blockquote-details justify-content-center">
                    <div class="img-blob blob1"><img src="style/images/art/t2.jpg" alt="" /></div>
                    <div class="info">
                      <h6 class="mb-0">Coriss Ambady</h6>
                      <span class="meta mb-0">Marketing Specialist</span>
                    </div>
                  </div>
                </blockquote>
              </div>
              <!-- /.item -->
              <div class="item">
                <blockquote class="icon larger">
                  <p>"Duis mollis, est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem nec elit. Cras justo odio, dapibus ac facilisis in, egestas eget."</p>
                  <div class="blockquote-details justify-content-center">
                    <div class="img-blob blob1"><img src="style/images/art/t3.jpg" alt="" /></div>
                    <div class="info">
                      <h6 class="mb-0">Barclay Widerski</h6>
                      <span class="meta mb-0">Sales Manager</span>
                    </div>
                  </div>
                </blockquote>
              </div>
              <!-- /.item -->
            </div>
            <!-- /.owl-carousel -->
          </div>
          <!--/column -->
        </div>
        <!--/.row -->
        <div class="space80"></div>
        <div class="carousel owl-carousel clients" data-margin="30" data-loop="true" data-dots="false" data-autoplay="true" data-autoplay-timeout="3000" data-responsive='{"0":{"items": "2"}, "768":{"items": "4"}, "992":{"items": "5"}, "1140":{"items": "6"}}'>
          <div class="item pl-15 pr-15"><img src="style/images/art/z1.svg" alt="" /></div>
          <div class="item pl-15 pr-15"><img src="style/images/art/z2.svg" alt="" /></div>
          <div class="item pl-15 pr-15"><img src="style/images/art/z3.svg" alt="" /></div>
          <div class="item pl-15 pr-15"><img src="style/images/art/z4.svg" alt="" /></div>
          <div class="item pl-15 pr-15"><img src="style/images/art/z5.svg" alt="" /></div>
          <div class="item pl-15 pr-15"><img src="style/images/art/z6.svg" alt="" /></div>
          <div class="item pl-15 pr-15"><img src="style/images/art/z7.svg" alt="" /></div>
          <div class="item pl-15 pr-15"><img src="style/images/art/z8.svg" alt="" /></div>
        </div>
        <!-- /.owl-carousel -->
      </div>
      <!-- /.container -->
    </div>
    <!-- /.wrapper -->
    <div class="wrapper light-wrapper">
      <div class="container inner">
        <h2 class="section-title text-uppercase text-center mb-40">Our Journal</h2>
        <div class="grid-view">
          <div class="carousel owl-carousel" data-margin="30" data-dots="true" data-autoplay="false" data-autoplay-timeout="5000" data-responsive='{"0":{"items": "1"}, "768":{"items": "2"}, "992":{"items": "3"}}'>
            <div class="item">
              <figure class="overlay overlay1 rounded mb-30"><a href="#"> <img src="style/images/art/b1.jpg" alt="" /></a>
                <figcaption>
                  <h5 class="from-top mb-0">Read More</h5>
                </figcaption>
              </figure>
              <div class="category"><a href="#" class="badge badge-pill bg-purple">Concept</a></div>
              <h2 class="post-title"><a href="blog-post.html">Ligula tristique quis risus eget urna mollis ornare porttitor</a></h2>
              <div class="meta mb-0"><span class="date"><i class="jam jam-clock"></i>5 Jul 2018</span><span class="comments"><i class="jam jam-message-alt"></i><a href="#">3 Comments</a></span></div>
            </div>
            <!-- /.item -->
            <div class="item">
              <figure class="overlay overlay1 rounded mb-30"><a href="#"> <img src="style/images/art/b2.jpg" alt="" /></a>
                <figcaption>
                  <h5 class="from-top mb-0">Read More</h5>
                </figcaption>
              </figure>
              <div class="category"><a href="#" class="badge badge-pill bg-meander">Business</a></div>
              <h2 class="post-title"><a href="blog-post.html">Nullam id dolor elit id nibh pharetra augue venenatis</a></h2>
              <div class="meta mb-0"><span class="date"><i class="jam jam-clock"></i>18 Jun 2018</span><span class="comments"><i class="jam jam-message-alt"></i><a href="#">5 Comments</a></span></div>
            </div>
            <!-- /.item -->
            <div class="item">
              <figure class="overlay overlay1 rounded mb-30"><a href="#"> <img src="style/images/art/b3.jpg" alt="" /></a>
                <figcaption>
                  <h5 class="from-top mb-0">Read More</h5>
                </figcaption>
              </figure>
              <div class="category"><a href="#" class="badge badge-pill bg-pink">Design</a></div>
              <h2 class="post-title"><a href="blog-post.html">Ultricies fusce porta elit pharetra augue faucibus</a></h2>
              <div class="meta mb-0"><span class="date"><i class="jam jam-clock"></i>14 May 2018</span><span class="comments"><i class="jam jam-message-alt"></i><a href="#">7 Comments</a></span></div>
            </div>
            <!-- /.item -->
            <div class="item">
              <figure class="overlay overlay1 rounded mb-30"><a href="#"> <img src="style/images/art/b4.jpg" alt="" /></a>
                <figcaption>
                  <h5 class="from-top mb-0">Read More</h5>
                </figcaption>
              </figure>
              <div class="category"><a href="#" class="badge badge-pill bg-violet">Ideas</a></div>
              <h2 class="post-title"><a href="blog-post.html">Morbi leo risus porta eget metus est non commodolacus</a></h2>
              <div class="meta mb-0"><span class="date"><i class="jam jam-clock"></i>9 Apr 2018</span><span class="comments"><i class="jam jam-message-alt"></i><a href="#">4 Comments</a></span></div>
            </div>
            <!-- /.item -->
            <div class="item">
              <figure class="overlay overlay1 rounded mb-30"><a href="#"> <img src="style/images/art/b5.jpg" alt="" /></a>
                <figcaption>
                  <h5 class="from-top mb-0">Read More</h5>
                </figcaption>
              </figure>
              <div class="category"><a href="#" class="badge badge-pill bg-green">Workspace</a></div>
              <h2 class="post-title"><a href="blog-post.html">Mollis adipiscing lorem quis mollis eget lacinia faucibus</a></h2>
              <div class="meta mb-0"><span class="date"><i class="jam jam-clock"></i>23 Feb 2018</span><span class="comments"><i class="jam jam-message-alt"></i><a href="#">8 Comments</a></span></div>
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