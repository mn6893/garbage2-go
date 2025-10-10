<?= $this->include('templates/header_pages'); ?>

<!-- Service Hero Section -->
<div class="service-hero">
    <div class="container">
        <div class="row align-items-center min-vh-50">
            <div class="col-lg-6">
                <h1 class="service-hero-title">Commercial Junk Removal</h1>
                <p class="lead service-hero-subtitle">Professional junk removal services for businesses of all sizes</p>
                <div class="mt-4">
                    <a href="<?= base_url('contact') ?>" class="btn btn-primary btn-lg me-3">Get Free Quote</a>
                    <a href="tel:+1-800-GARBAGE" class="btn btn-secondary btn-lg">Call Now</a>
                </div>
            </div>
            <div class="col-lg-6">
                <img src="<?= base_url('style/images/services/commercial-junk.jpg') ?>" alt="Commercial Junk Removal" class="img-fluid service-image">
            </div>
        </div>
    </div>
</div>

<!-- Service Details Section -->
<div class="wrapper py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-8">
                <div class="service-content">
                    <h2 class="h3 mb-4">Professional Commercial Junk Removal Services</h2>
                    <p class="lead">Keep your business running smoothly with our reliable commercial junk removal services. We work around your schedule to minimize disruption to your operations.</p>
                    
                    <div class="service-features mb-5">
                        <h3 class="h4 mb-3">Commercial Items We Remove:</h3>
                        <ul class="list-unstyled">
                            <li>Office furniture and equipment</li>
                            <li>IT equipment and electronics</li>
                            <li>Filing cabinets and documents</li>
                            <li>Manufacturing waste and materials</li>
                            <li>Retail fixtures and displays</li>
                            <li>Restaurant equipment</li>
                            <li>Construction debris</li>
                            <li>Warehouse cleanouts</li>
                        </ul>
                    </div>

                    <div class="row mb-5">
                        <div class="col-md-6">
                            <div class="service-box">
                                <div class="icon text-center">
                                    <i class="icofont-building" style="font-size: 3rem; color: var(--color-primary);"></i>
                                </div>
                                <h4>Business-First Approach</h4>
                                <p>We understand business needs and work efficiently to minimize downtime and disruption to your operations.</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="service-box">
                                <div class="icon text-center">
                                    <i class="icofont-shield-alt" style="font-size: 3rem; color: var(--color-primary);"></i>
                                </div>
                                <h4>Fully Licensed & Insured</h4>
                                <p>Our commercial services are fully licensed and insured, providing peace of mind for your business.</p>
                            </div>
                        </div>
                    </div>

                    <div class="mb-5">
                        <h3 class="h4 mb-3">Industries We Serve</h3>
                        <div class="row">
                            <div class="col-md-6">
                                <ul class="icon-list bullet-default">
                                    <li><i class="jam jam-arrow-right"></i>Office buildings</li>
                                    <li><i class="jam jam-arrow-right"></i>Retail stores</li>
                                    <li><i class="jam jam-arrow-right"></i>Restaurants</li>
                                    <li><i class="jam jam-arrow-right"></i>Warehouses</li>
                                    <li><i class="jam jam-arrow-right"></i>Manufacturing facilities</li>
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <ul class="icon-list bullet-default">
                                    <li><i class="jam jam-arrow-right"></i>Medical facilities</li>
                                    <li><i class="jam jam-arrow-right"></i>Schools and universities</li>
                                    <li><i class="jam jam-arrow-right"></i>Construction sites</li>
                                    <li><i class="jam jam-arrow-right"></i>Real estate properties</li>
                                    <li><i class="jam jam-arrow-right"></i>Government facilities</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="mb-5">
                        <h3 class="h4 mb-3">Flexible Scheduling Options</h3>
                        <p>We offer flexible scheduling to work around your business hours, including:</p>
                        <ul class="icon-list bullet-default">
                            <li><i class="jam jam-arrow-right"></i>After-hours service</li>
                            <li><i class="jam jam-arrow-right"></i>Weekend appointments</li>
                            <li><i class="jam jam-arrow-right"></i>Emergency cleanouts</li>
                            <li><i class="jam jam-arrow-right"></i>Recurring service contracts</li>
                        </ul>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4">
                <div class="sidebar">
                    <!-- Contact Form -->
                    <div class="contact-section">
                        <h3>Get Your Business Quote</h3>
                        <p>Need commercial junk removal? We provide customized solutions for businesses.</p>
                        <a href="tel:+1-800-GARBAGE" class="phone">1-800-GARBAGE</a>
                        <div class="mt-3">
                            <a href="<?= base_url('contact') ?>" class="btn btn-primary w-100">Request Business Quote</a>
                        </div>
                    </div>

                    <!-- Related Services -->
                    <div class="related-services mt-4">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Commercial Services</h5>
                                <ul class="list-unstyled">
                                    <li><a href="<?= base_url('services/office-junk-removal') ?>">Office Junk Removal</a></li>
                                    <li><a href="<?= base_url('services/retail-merchandise-rubbish-removal') ?>">Retail Merchandise Removal</a></li>
                                    <li><a href="<?= base_url('services/worksite-junk-removal') ?>">Worksite Junk Removal</a></li>
                                    <li><a href="<?= base_url('services/renovation-rubbish-removal') ?>">Renovation Cleanup</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.service-hero {
    background: linear-gradient(135deg, rgba(var(--rgb-secondary), 0.95), rgba(var(--rgb-dark), 0.8));
    color: var(--color-white);
    padding: 100px 0;
    position: relative;
    overflow: hidden;
}

.service-hero::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: radial-gradient(circle at center, rgba(var(--rgb-primary), 0.1), transparent 70%);
    pointer-events: none;
}

.service-hero h1 {
    color: var(--color-white) !important;
    font-size: 3rem;
    font-weight: 700;
    text-shadow: 2px 2px 4px rgba(var(--rgb-dark), 0.3);
}

.service-hero .lead {
    color: rgba(var(--rgb-white), 0.9);
    font-size: 1.3rem;
    font-weight: 400;
}

.service-box {
    background: rgba(var(--rgb-white), 0.98);
    border: 2px solid rgba(var(--rgb-primary), 0.2);
    border-radius: 15px;
    padding: 30px;
    margin-bottom: 30px;
    transition: all 0.4s ease;
    box-shadow: 0 5px 20px rgba(var(--rgb-dark), 0.08);
}

.service-box:hover {
    transform: translateY(-10px);
    border-color: var(--color-primary);
    box-shadow: 0 15px 40px rgba(var(--rgb-primary), 0.2);
}

.service-features {
    background: rgba(var(--rgb-primary), 0.03);
    border-radius: 15px;
    padding: 30px;
    margin: 30px 0;
}

.service-features ul {
    list-style: none;
    padding: 0;
}

.service-features li {
    padding: 10px 0;
    border-bottom: 1px solid rgba(var(--rgb-primary), 0.1);
    position: relative;
    padding-left: 30px;
}

.service-features li:before {
    content: '✓';
    position: absolute;
    left: 0;
    color: var(--color-primary);
    font-weight: bold;
    font-size: 1.2rem;
}

.service-features li:last-child {
    border-bottom: none;
}

.contact-section {
    background: linear-gradient(135deg, rgba(var(--rgb-primary), 0.05), rgba(var(--rgb-accent), 0.02));
    border-radius: 20px;
    padding: 40px;
    margin: 40px 0;
    text-align: center;
}

.contact-section .phone {
    color: var(--color-primary);
    font-size: 1.5rem;
    font-weight: 600;
    text-decoration: none;
}

.card {
    border: 1px solid rgba(var(--rgb-primary), 0.2);
    border-radius: 10px;
}

.card a {
    color: var(--color-dark);
    text-decoration: none;
    transition: all 0.3s ease;
}

.card a:hover {
    color: var(--color-primary);
    text-decoration: none;
}
</style>

<?= $this->include('templates/footer'); ?>