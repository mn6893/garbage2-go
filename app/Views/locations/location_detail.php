<?= $this->include('templates/header_pages'); ?>

<div class="service-hero">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <h1>Rubbish Removal <?= $location_name ?></h1>
                <p class="lead"><?= $description ?></p>
                <div class="mt-4">
                    <a href="<?= base_url('contact') ?>" class="btn btn-primary btn-lg me-3">Get Free Quote</a>
                    <a href="tel:+1-800-GARBAGE" class="btn btn-secondary btn-lg">Call Now</a>
                </div>
            </div>
            <div class="col-lg-6">
                <img src="<?= base_url('style/images/locations/' . strtolower($location_name) . '.jpg') ?>" alt="<?= $location_name ?> Rubbish Removal" class="img-fluid service-image">
            </div>
        </div>
    </div>
</div>

<div class="wrapper py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-8">
                <h2>Professional Rubbish Removal Services in <?= $location_name ?></h2>
                <p class="lead">Garbage2Go provides comprehensive rubbish removal services throughout <?= $location_name ?>, <?= $province ?>. Our experienced team is ready to help with all your junk removal needs.</p>
                
                <div class="service-features mb-5">
                    <h3>Services Available in <?= $location_name ?>:</h3>
                    <ul>
                        <li>Residential rubbish removal</li>
                        <li>Commercial junk removal</li>
                        <li>Construction debris cleanup</li>
                        <li>Office cleanouts</li>
                        <li>Estate cleanouts</li>
                        <li>Appliance removal</li>
                        <li>Furniture removal</li>
                        <li>Same-day service available</li>
                    </ul>
                </div>

                <div class="row mb-5">
                    <div class="col-md-6">
                        <div class="service-box">
                            <div class="icon text-center">
                                <i class="icofont-truck" style="font-size: 3rem; color: #00EC01;"></i>
                            </div>
                            <h4>Local Service</h4>
                            <p>We're proud to serve the <?= $location_name ?> community with reliable, professional junk removal services.</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="service-box">
                            <div class="icon text-center">
                                <i class="icofont-clock-time" style="font-size: 3rem; color: #00EC01;"></i>
                            </div>
                            <h4>Fast Response</h4>
                            <p>Same-day and next-day service available throughout the <?= $location_name ?> area.</p>
                        </div>
                    </div>
                </div>

                <div class="mb-5">
                    <h3>Why Choose Garbage2Go in <?= $location_name ?>?</h3>
                    <ul class="icon-list bullet-default">
                        <li><i class="jam jam-arrow-right"></i>Local <?= $location_name ?> team</li>
                        <li><i class="jam jam-arrow-right"></i>Licensed and insured</li>
                        <li><i class="jam jam-arrow-right"></i>Eco-friendly disposal methods</li>
                        <li><i class="jam jam-arrow-right"></i>Transparent pricing</li>
                        <li><i class="jam jam-arrow-right"></i>Professional service</li>
                        <li><i class="jam jam-arrow-right"></i>Customer satisfaction guarantee</li>
                    </ul>
                </div>
            </div>
            
            <div class="col-lg-4">
                <div class="sidebar">
                    <div class="contact-section">
                        <h3>Get Your Free Quote in <?= $location_name ?></h3>
                        <p>Ready to clear out your junk? Contact our <?= $location_name ?> team today.</p>
                        <a href="tel:+1-800-GARBAGE" class="phone">1-800-GARBAGE</a>
                        <div class="mt-3">
                            <a href="<?= base_url('contact') ?>" class="btn btn-primary w-100">Request Quote Online</a>
                        </div>
                    </div>

                    <div class="related-services mt-4">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Our Services</h5>
                                <ul class="list-unstyled">
                                    <li><a href="<?= base_url('services/household-rubbish-removal') ?>">Household Rubbish Removal</a></li>
                                    <li><a href="<?= base_url('services/commercial-junk-removal') ?>">Commercial Junk Removal</a></li>
                                    <li><a href="<?= base_url('services/office-junk-removal') ?>">Office Junk Removal</a></li>
                                    <li><a href="<?= base_url('services/green-waste-removal') ?>">Green Waste Removal</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="other-locations mt-4">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Other Locations</h5>
                                <ul class="list-unstyled">
                                    <li><a href="<?= base_url('rubbish-removal-toronto') ?>">Toronto</a></li>
                                    <li><a href="<?= base_url('rubbish-removal-ottawa') ?>">Ottawa</a></li>
                                    <li><a href="<?= base_url('rubbish-removal-mississauga') ?>">Mississauga</a></li>
                                    <li><a href="<?= base_url('rubbish-removal-hamilton') ?>">Hamilton</a></li>
                                    <li><a href="<?= base_url('location') ?>">View All Locations</a></li>
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
    background: linear-gradient(135deg, rgba(0, 31, 63, 0.95), rgba(17, 17, 17, 0.8));
    color: white;
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
    background: radial-gradient(circle at center, rgba(0, 236, 1, 0.1), transparent 70%);
    pointer-events: none;
}

.service-hero h1 {
    color: white !important;
    font-size: 3rem;
    font-weight: 700;
    text-shadow: 2px 2px 4px rgba(17, 17, 17, 0.3);
}

.service-hero .lead {
    color: rgba(255, 255, 255, 0.9);
    font-size: 1.3rem;
    font-weight: 400;
}

.service-box {
    background: rgba(255, 255, 255, 0.98);
    border: 2px solid rgba(0, 236, 1, 0.2);
    border-radius: 15px;
    padding: 30px;
    margin-bottom: 30px;
    transition: all 0.4s ease;
    box-shadow: 0 5px 20px rgba(17, 17, 17, 0.08);
}

.service-box:hover {
    transform: translateY(-10px);
    border-color: #00EC01;
    box-shadow: 0 15px 40px rgba(0, 236, 1, 0.2);
}

.service-features {
    background: rgba(0, 236, 1, 0.03);
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
    border-bottom: 1px solid rgba(0, 236, 1, 0.1);
    position: relative;
    padding-left: 30px;
}

.service-features li:before {
    content: '✓';
    position: absolute;
    left: 0;
    color: #00EC01;
    font-weight: bold;
    font-size: 1.2rem;
}

.service-features li:last-child {
    border-bottom: none;
}

.contact-section {
    background: linear-gradient(135deg, rgba(0, 236, 1, 0.05), rgba(0, 51, 102, 0.02));
    border-radius: 20px;
    padding: 40px;
    margin: 40px 0;
    text-align: center;
}

.contact-section .phone {
    color: #00EC01;
    font-size: 1.5rem;
    font-weight: 600;
    text-decoration: none;
}

.card {
    border: 1px solid rgba(0, 236, 1, 0.2);
    border-radius: 10px;
}

.card a {
    color: #111111;
    text-decoration: none;
    transition: all 0.3s ease;
}

.card a:hover {
    color: #00EC01;
    text-decoration: none;
}
</style>

<?= $this->include('templates/footer'); ?>