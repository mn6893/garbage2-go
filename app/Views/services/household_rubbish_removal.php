<?= $this->include('templates/header_pages'); ?>

<!-- Service Hero Section -->
<div class="service-hero">
    <div class="container">
        <div class="row align-items-center min-vh-50">
            <div class="col-lg-6">
                <h1 class="service-hero-title">Household Junk Removal</h1>
                <p class="lead service-hero-subtitle">Professional household junk removal services for your home</p>
                <div class="mt-4">
                    <a href="<?= base_url('contact') ?>" class="btn btn-primary btn-lg me-3">Get Free Quote</a>
                    <a href="tel:+1-800-GARBAGE" class="btn btn-secondary btn-lg">Call Now</a>
                </div>
            </div>
            <div class="col-lg-6">
                <img src="<?= base_url('style/images/services/household-junk.jpg') ?>" alt="Household Junk Removal" class="img-fluid service-image">
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
                    <h2 class="h3 mb-4">Professional Household Junk Removal Services</h2>
                    <p class="lead">Clear out your home with our comprehensive household junk removal service. From single items to complete house cleanouts, we handle it all with care and professionalism.</p>
                    
                    <div class="service-features mb-5">
                        <h3 class="h4 mb-3">What We Remove:</h3>
                        <ul class="list-unstyled">
                            <li>Old furniture and appliances</li>
                            <li>Electronics and e-waste</li>
                            <li>Household clutter and junk</li>
                            <li>Basement and attic cleanouts</li>
                            <li>Garage cleanouts</li>
                            <li>Old mattresses and box springs</li>
                            <li>Exercise equipment</li>
                            <li>General household items</li>
                        </ul>
                    </div>

                    <div class="row mb-5">
                        <div class="col-md-6">
                            <div class="service-box">
                                <div class="icon text-center">
                                    <i class="icofont-home" style="font-size: 3rem; color: var(--color-primary);"></i>
                                </div>
                                <h4>Complete Home Cleanouts</h4>
                                <p>We handle everything from single room cleanouts to entire house clearances, making your space clean and clutter-free.</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="service-box">
                                <div class="icon text-center">
                                    <i class="icofont-recycle" style="font-size: 3rem; color: var(--color-primary);"></i>
                                </div>
                                <h4>Responsible Disposal</h4>
                                <p>We donate usable items and recycle materials whenever possible, minimizing environmental impact.</p>
                            </div>
                        </div>
                    </div>

                    <div class="mb-5">
                        <h3 class="h4 mb-3">Why Choose Garbage2Go for Household Junk Removal?</h3>
                        <p>Our experienced team understands that your home is your sanctuary. We treat your property with respect and ensure a thorough, efficient removal process that leaves your space ready for whatever comes next.</p>
                        
                        <ul class="icon-list bullet-default">
                            <li><i class="jam jam-arrow-right"></i>Licensed and insured professionals</li>
                            <li><i class="jam jam-arrow-right"></i>Upfront, transparent pricing</li>
                            <li><i class="jam jam-arrow-right"></i>Same-day service available</li>
                            <li><i class="jam jam-arrow-right"></i>We do all the heavy lifting</li>
                            <li><i class="jam jam-arrow-right"></i>Eco-friendly disposal methods</li>
                            <li><i class="jam jam-arrow-right"></i>No hidden fees or surprises</li>
                        </ul>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4">
                <div class="sidebar">
                    <!-- Contact Form -->
                    <div class="contact-section">
                        <h3>Get Your Free Quote</h3>
                        <p>Ready to declutter your home? Contact us today for a free, no-obligation quote.</p>
                        <a href="tel:+1-800-GARBAGE" class="phone">1-800-GARBAGE</a>
                        <div class="mt-3">
                            <a href="<?= base_url('contact') ?>" class="btn btn-primary w-100">Request Quote Online</a>
                        </div>
                    </div>

                    <!-- Related Services -->
                    <div class="related-services mt-4">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Related Services</h5>
                                <ul class="list-unstyled">
                                    <li><a href="<?= base_url('services/garage-junk-removal') ?>">Garage Junk Removal</a></li>
                                    <li><a href="<?= base_url('services/backyard-clean-up-and-junk-removal') ?>">Backyard Clean-up</a></li>
                                    <li><a href="<?= base_url('services/mattress-recycling-melbourne') ?>">Mattress Recycling</a></li>
                                    <li><a href="<?= base_url('services/end-of-lease-junk-removal') ?>">End of Lease Cleanup</a></li>
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