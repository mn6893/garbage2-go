<?= $this->include('templates/header_pages') ?>

<!-- Gallery Specific Styles -->
<link rel="stylesheet" type="text/css" href="<?= base_url('style/css/gallery.css') ?>">

<!-- Gallery Hero Section -->
<div class="wrapper bg-dark">
  <div class="container inner text-center">
    <div class="space90"></div>
    <span class="badge bg-primary text-white px-4 py-2 rounded-pill mb-4 fs-sm">
      <i class="uil uil-image me-1"></i> Before & After Showcase
    </span>
    <h1 class="page-title text-light mb-3">Our Work Gallery</h1>
    <p class="lead text-light mb-4">See the amazing transformations we've accomplished for our clients across Canada</p>
    <a href="#gallery" class="btn btn-primary rounded-pill px-5">Explore Gallery</a>
  </div>
  <!-- /.container -->
  <div class="space140"></div>
</div>
<!-- /.wrapper -->

<!-- Gallery Filter Section -->
<section id="gallery" class="wrapper bg-white">
  <div class="container py-14 py-md-16">
    <div class="row">
      <div class="col-lg-8 mx-auto">
        <h2 class="display-4 mb-3 text-center text-dark">Project Gallery</h2>
        <p class="lead text-center text-muted mb-10">Browse through our completed projects and see the incredible transformations we've achieved.</p>
      </div>
    </div>
    
    <!-- Filter Buttons -->
    <div class="text-center mb-8">
      <div class="filter-pills d-inline-flex flex-wrap justify-content-center gap-2" role="tablist" aria-label="Gallery filters">
        <button type="button" class="gallery-filter filter-pill active" data-filter="all" aria-pressed="true">All Projects</button>
        <button type="button" class="gallery-filter filter-pill" data-filter="garage">Garage</button>
        <button type="button" class="gallery-filter filter-pill" data-filter="basement">Basement</button>
        <button type="button" class="gallery-filter filter-pill" data-filter="commercial">Commercial</button>
        <button type="button" class="gallery-filter filter-pill" data-filter="outdoor">Outdoor</button>
        <button type="button" class="gallery-filter filter-pill" data-filter="renovation">Renovation</button>
        <button type="button" class="gallery-filter filter-pill" data-filter="estate">Estate</button>
      </div>
    </div>

    <!-- Gallery Grid -->
    <div class="row gallery-container" id="gallery-container">
      <?php foreach ($galleryItems as $item): ?>
      <div class="col-md-6 col-lg-4 mb-6 gallery-item" data-category="<?= $item['category'] ?>">
        <div class="card shadow-lg">
          <div class="before-after-container">
            <!-- Category badge overlay -->
            
            <div class="before-after-slider" data-before="<?= base_url('style/images/' . $item['before_image']) ?>" data-after="<?= base_url('style/images/' . $item['after_image']) ?>">
              <div class="before-image">
                <img src="<?= base_url('style/images/' . $item['before_image']) ?>" alt="Before: <?= esc($item['title']) ?>" class="img-fluid">
                <div class="image-label before-label">BEFORE</div>
              </div>
              <div class="after-image">
                <img src="<?= base_url('style/images/' . $item['after_image']) ?>" alt="After: <?= esc($item['title']) ?>" class="img-fluid">
                <div class="image-label after-label">AFTER</div>
              </div>
              <div class="slider-handle">
                <div class="slider-button"></div>
              </div>
              <div class="slider-line"></div>
            </div>
          </div>
          <div class="card-body">
            <h5 class="card-title mb-1"><?= esc($item['title']) ?></h5>
            <p class="card-text text-muted small mb-3"><i class="uil uil-map-marker me-1"></i><?= esc($item['location']) ?></p>
            <p class="card-text mb-4"><?= esc($item['description']) ?></p>
            <div class="text-center">
              <button class="btn btn-primary btn-sm rounded-pill px-4 py-2 shadow-sm enlarge-image" 
                      data-before="<?= base_url('style/images/' . $item['before_image']) ?>" 
                      data-after="<?= base_url('style/images/' . $item['after_image']) ?>"
                      data-title="<?= esc($item['title']) ?>">
                <i class="uil uil-search-plus me-2"></i> View Full Size
              </button>
            </div>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- Lightbox Modal -->
<div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-centered">
    <div class="modal-content bg-dark">
      <div class="modal-header border-0">
        <h5 class="modal-title text-white" id="imageModalLabel">Image Comparison</h5>
        <button type="button" class="btn-close btn-close-white rounded-circle" data-dismiss="modal" aria-label="Close" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">×</button>
      </div>
      <div class="modal-body p-0">
        <div class="modal-before-after-container">
          <div class="modal-before-after-slider">
            <div class="modal-before-image">
              <img id="modal-before-img" src="" alt="Before" class="img-fluid">
              <div class="image-label before-label">BEFORE</div>
            </div>
            <div class="modal-after-image">
              <img id="modal-after-img" src="" alt="After" class="img-fluid">
              <div class="image-label after-label">AFTER</div>
            </div>
            <div class="modal-slider-handle">
              <div class="slider-button"></div>
            </div>
            <div class="modal-slider-line"></div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- CTA Section -->
<section class="wrapper bg-dark">
    <div class="container py-16 py-md-18">
        <div class="row justify-content-center">
            <div class="col-lg-10 col-xl-8">
                <div class="text-center">
                    <h2 class="display-3 mb-4 text-white font-weight-bold">Transform Your Space Today</h2>
                    <p class="fs-lg mb-6 text-white opacity-90 mx-auto" style="max-width: 600px;">
                        Experience professional junk removal and space transformation services. 
                        Our expert team delivers exceptional results with guaranteed satisfaction.
                    </p>
                    <div class="d-flex flex-column flex-sm-row gap-3 justify-content-center align-items-center">
                        <a href="<?= base_url('quote') ?>" class="btn btn-white btn-lg rounded-pill px-8 py-3 shadow-lg">
                            <i class="uil uil-calculator me-2"></i>Get Free Estimate
                        </a>
                        <a href="<?= base_url('contact') ?>" class="btn btn-outline-white btn-lg rounded-pill px-8 py-3">
                            <i class="uil uil-phone me-2"></i>Schedule Consultation
                        </a>
                    </div>
                    <div class="mt-6">
                        <div class="row text-center text-white">
                            <div class="col-4">
                                <div class="fs-sm opacity-80">Response Time</div>
                                <div class="fw-bold">24 Hours</div>
                            </div>
                            <div class="col-4">
                                <div class="fs-sm opacity-80">Satisfaction</div>
                                <div class="fw-bold">100% Guaranteed</div>
                            </div>
                            <div class="col-4">
                                <div class="fs-sm opacity-80">Experience</div>
                                <div class="fw-bold">10+ Years</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Gallery JavaScript -->
<script src="<?= base_url('style/js/gallery.js') ?>"></script>

<?= $this->include('templates/footer') ?>