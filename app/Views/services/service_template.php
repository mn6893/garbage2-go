<?= $this->include('templates/header_pages'); ?>

<!-- Service Hero Section -->
<div class="service-hero">
    <div class="container">
        <div class="row align-items-center min-vh-50">
            <div class="col-lg-6">
                <h1 class="service-hero-title"><?= $service_title ?></h1>
                <p class="lead service-hero-subtitle"><?= $service_description ?></p>
                <div class="mt-4">
                    <a href="<?= base_url('quote') ?>" class="btn btn-primary btn-lg me-3">Get Free Quote</a>
                    <a href="tel:+16479138775" class="btn btn-secondary btn-lg">Call Now</a>
                </div>
            </div>
            <div class="col-lg-6">
                <img src="<?= base_url('style/images/services/' . $hero_image) ?>" alt="<?= $service_title ?>" class="img-fluid service-image">
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
                    <h2 class="h3 mb-4">Professional <?= $service_title ?> Services</h2>
                    <p class="lead">At Garbage2Go, we understand that dealing with rubbish and junk can be overwhelming. That's why we provide comprehensive, professional services to make the process as easy as possible for you.</p>
                    
                    <div class="service-features mb-5">
                        <h3 class="h4 mb-3">What We Include:</h3>
                        <ul class="list-unstyled">
                            <li>Free on-site consultation and quote</li>
                            <li>Professional and insured team</li>
                            <li>All loading and heavy lifting</li>
                            <li>Responsible disposal and recycling</li>
                            <li>Broom-clean sweep up</li>
                            <li>Same-day service available</li>
                            <li>Transparent, upfront pricing</li>
                            <li>Eco-friendly disposal methods</li>
                        </ul>
                    </div>

                    <div class="row mb-5">
                        <div class="col-md-6">
                            <div class="service-box">
                                <div class="icon text-center">
                                    <i class="icofont-recycle" style="font-size: 3rem; color: var(--color-primary);"></i>
                                </div>
                                <h4>Eco-Friendly</h4>
                                <p>We prioritize recycling and environmentally responsible disposal methods to minimize our impact on the planet.</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="service-box">
                                <div class="icon text-center">
                                    <i class="icofont-clock-time" style="font-size: 3rem; color: var(--color-primary);"></i>
                                </div>
                                <h4>Fast Service</h4>
                                <p>Same-day and next-day service available. We work around your schedule to provide convenient service times.</p>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-5">
                        <div class="col-md-6">
                            <div class="service-box">
                                <div class="icon text-center">
                                    <i class="icofont-dollar" style="font-size: 3rem; color: var(--color-primary);"></i>
                                </div>
                                <h4>Fair Pricing</h4>
                                <p>Transparent, upfront pricing with no hidden fees. You'll know exactly what you're paying before we start.</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="service-box">
                                <div class="icon text-center">
                                    <i class="icofont-shield-alt" style="font-size: 3rem; color: var(--color-primary);"></i>
                                </div>
                                <h4>Fully Insured</h4>
                                <p>Our team is fully licensed and insured, giving you peace of mind during the removal process.</p>
                            </div>
                        </div>
                    </div>

                    <div class="process-section mb-5">
                        <h3 class="h4 mb-4">Our Simple 3-Step Process</h3>
                        <div class="row">
                            <div class="col-md-4 text-center mb-4">
                                <div class="process-step">
                                    <div class="step-number">1</div>
                                    <h5>Book Online or Call</h5>
                                    <p>Schedule your appointment online or give us a call. We offer flexible scheduling.</p>
                                </div>
                            </div>
                            <div class="col-md-4 text-center mb-4">
                                <div class="process-step">
                                    <div class="step-number">2</div>
                                    <h5>Free Quote</h5>
                                    <p>Our team arrives and provides a free, no-obligation quote based on the volume of items.</p>
                                </div>
                            </div>
                            <div class="col-md-4 text-center mb-4">
                                <div class="process-step">
                                    <div class="step-number">3</div>
                                    <h5>We Remove Everything</h5>
                                    <p>Once you approve the quote, we handle all the heavy lifting and clean up after ourselves.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4">
                <div class="sidebar">
                    <!-- Contact Form -->
                    <div class="contact-section">
                        <h3>Get Your Free Quote</h3>
                        <p>Ready to get started? Contact us today for a free, no-obligation quote.</p>
                        <a href="tel:+16479138775" class="phone">+1 (647) 913-8775</a>
                        <div class="mt-3">
                            <a href="<?= base_url('quote') ?>" class="btn btn-primary w-100">Request Quote Online</a>
                        </div>
                    </div>

                    <!-- Service Areas -->
                    <div class="service-areas mt-4">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Service Areas</h5>
                                <ul class="list-unstyled">
                                    <li><a href="<?= base_url('rubbish-removal-toronto') ?>">Toronto</a></li>
                                    <li><a href="<?= base_url('rubbish-removal-mississauga') ?>">Mississauga</a></li>
                                    <li><a href="<?= base_url('rubbish-removal-brampton') ?>">Brampton</a></li>
                                    <li><a href="<?= base_url('rubbish-removal-hamilton') ?>">Hamilton</a></li>
                                    <li><a href="<?= base_url('location') ?>">View All Locations</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Related Services -->
                    <div class="related-services mt-4">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Related Services</h5>
                                <ul class="list-unstyled">
                                    <li><a href="<?= base_url('services/household-rubbish-removal') ?>">Household Rubbish Removal</a></li>
                                    <li><a href="<?= base_url('services/commercial-junk-removal') ?>">Commercial Junk Removal</a></li>
                                    <li><a href="<?= base_url('services/green-waste-removal') ?>">Green Waste Removal</a></li>
                                    <li><a href="<?= base_url('services/mattress-recycling-melbourne') ?>">Mattress Recycling</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- FAQ Section -->
<div class="wrapper light-wrapper py-5">
    <div class="container">
        <h2 class="text-center mb-5">Frequently Asked Questions</h2>
        <div class="row">
            <div class="col-lg-8 offset-lg-2">
                <div class="accordion" id="faqAccordion">
                    <div class="accordion-item">
                        <h3 class="accordion-header">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">
                                How much does <?= strtolower($service_title) ?> cost?
                            </button>
                        </h3>
                        <div id="faq1" class="accordion-collapse collapse show" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                Our pricing is based on the volume of items we remove. We provide free, upfront quotes with no hidden fees. Typical costs range from $200-$800 depending on the amount of material.
                            </div>
                        </div>
                    </div>
                    
                    <div class="accordion-item">
                        <h3 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">
                                Do you recycle the items you collect?
                            </button>
                        </h3>
                        <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                Yes! We're committed to environmental responsibility. We recycle and donate as much as possible, and only send items to landfill as a last resort.
                            </div>
                        </div>
                    </div>
                    
                    <div class="accordion-item">
                        <h3 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq3">
                                How quickly can you provide service?
                            </button>
                        </h3>
                        <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                We offer same-day and next-day service in most areas. Our flexible scheduling means we can work around your availability, including evenings and weekends.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.process-step .step-number {
    width: 60px;
    height: 60px;
    background: linear-gradient(135deg, var(--color-primary), var(--color-accent));
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
    font-weight: bold;
    margin: 0 auto 20px;
}

.accordion-button {
    background: rgba(var(--rgb-primary), 0.05);
    border: none;
    color: var(--color-dark);
    font-weight: 600;
}

.accordion-button:not(.collapsed) {
    background: var(--color-primary);
    color: white;
}

.card {
    border: 1px solid rgba(var(--rgb-primary), 0.2);
    border-radius: 10px;
}

.card-title {
    color: var(--color-dark);
    font-weight: 600;
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