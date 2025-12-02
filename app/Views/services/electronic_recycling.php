<?= $this->include('templates/header_pages'); ?>

<div class="service-hero">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <h1>Electronic Recycling & Disposal Services</h1>
                <p class="lead">Responsible electronic waste recycling with secure data destruction and eco-friendly disposal</p>
                <div class="mt-4">
                    <a href="<?= base_url('quote') ?>" class="btn btn-primary btn-lg me-3">Get Free Quote</a>
                    <a href="tel:+16479138775" class="btn btn-secondary btn-lg">Call Now</a>
                </div>
            </div>
            <div class="col-lg-6">
                <img src="<?= base_url('style/images/services/electronic-recycling.jpg') ?>" alt="Electronic Recycling Services" class="img-fluid service-image">
            </div>
        </div>
    </div>
</div>

<div class="wrapper py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-8">
                <h2>Professional Electronic Recycling Services</h2>
                <p class="lead">At Garbage To Go Inc., we take responsible disposal seriously — especially when it comes to electronics. Our Electronic Recycling Service ensures your old or unused electronic devices are collected, processed, and disposed of safely and sustainably. We make it easy for homes and businesses to recycle electronics while protecting their data and supporting a cleaner environment.</p>

                <div class="service-features mb-5">
                    <h3>What We Handle</h3>
                    <p>We collect and recycle a wide range of electronic items, including:</p>
                    <ul>
                        <li>Computers, laptops, and tablets</li>
                        <li>Monitors, printers, and accessories</li>
                        <li>TVs, audio and video equipment</li>
                        <li>Cell phones, servers, and networking devices</li>
                        <li>Office electronics and IT equipment</li>
                    </ul>
                </div>

                <div class="service-features mb-5">
                    <h3>Our Process</h3>

                    <div class="process-step mb-4">
                        <h4><span class="step-number">1</span> Pickup & Collection</h4>
                        <p>Schedule a pickup, and our team will collect your electronics directly from your home or business. Items are securely transported for processing.</p>
                    </div>

                    <div class="process-step mb-4">
                        <h4><span class="step-number">2</span> Secure Data Destruction</h4>
                        <p>For all devices containing data, we offer data wiping and hard drive shredding to ensure complete information security.</p>
                    </div>

                    <div class="process-step mb-4">
                        <h4><span class="step-number">3</span> Sorting, Recycling & Refurbishment</h4>
                        <p>After collection, electronics are carefully sorted. Functional items may be refurbished and donated to local charities or organizations in need, while non-reusable components are recycled in an environmentally responsible manner.</p>
                    </div>

                    <div class="process-step mb-4">
                        <h4><span class="step-number">4</span> Certification (Upon Request)</h4>
                        <ul>
                            <li><strong>Certificate of Destruction</strong> is available for customers requesting proof of secure data and equipment disposal.</li>
                            <li><strong>Certificate of Collection</strong> is available only when devices are approved for recycling or refurbishment to be donated or repurposed for charitable use.</li>
                        </ul>
                    </div>
                </div>

                <div class="service-features mb-5">
                    <h3>Why Choose Us for Electronic Recycling</h3>
                    <ul>
                        <li>Eco-friendly and responsible disposal</li>
                        <li>Secure data handling and certified destruction</li>
                        <li>Convenient pickup and drop-off options</li>
                        <li>Supporting local communities through equipment donations</li>
                    </ul>
                </div>

                <div class="cta-section mb-5">
                    <h3>Ready to Recycle Your Electronics?</h3>
                    <p>Contact us today to schedule your electronic recycling pickup and contribute to a cleaner, more sustainable environment.</p>
                    <a href="<?= base_url('quote') ?>" class="btn btn-primary btn-lg">Request Free Quote</a>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="contact-section mb-4">
                    <h3>Get Your Free Quote</h3>
                    <p>Need electronic recycling services?</p>
                    <a href="tel:+16479138775" class="phone">+1 (647) 913-8775</a>
                    <div class="mt-3">
                        <a href="<?= base_url('quote') ?>" class="btn btn-primary w-100">Request Quote</a>
                    </div>
                </div>

                <div class="info-box mb-4">
                    <h4>Service Areas</h4>
                    <p>We serve all major cities across Ontario including:</p>
                    <ul class="list-unstyled">
                        <li><i class="icofont-location-pin"></i> Toronto & GTA</li>
                        <li><i class="icofont-location-pin"></i> Hamilton</li>
                        <li><i class="icofont-location-pin"></i> London</li>
                        <li><i class="icofont-location-pin"></i> Kitchener-Waterloo</li>
                    </ul>
                </div>

                <div class="info-box">
                    <h4>Quick Facts</h4>
                    <ul class="list-unstyled">
                        <li><i class="icofont-check-circled"></i> Same-day service available</li>
                        <li><i class="icofont-check-circled"></i> Certified data destruction</li>
                        <li><i class="icofont-check-circled"></i> Eco-friendly recycling</li>
                        <li><i class="icofont-check-circled"></i> Equipment donations</li>
                        <li><i class="icofont-check-circled"></i> Commercial & residential</li>
                    </ul>
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
}

.service-hero h1 {
    color: white !important;
    font-size: 2.5rem;
    font-weight: 700;
    margin-bottom: 20px;
}

.service-hero .lead {
    font-size: 1.2rem;
    margin-bottom: 30px;
}

.service-image {
    border-radius: 20px;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
}

.service-features {
    background: rgba(0, 236, 1, 0.03);
    border-radius: 15px;
    padding: 30px;
    margin-bottom: 30px;
}

.service-features h3 {
    color: #003366;
    margin-bottom: 20px;
    font-weight: 600;
}

.service-features h4 {
    color: #003366;
    font-size: 1.3rem;
    margin-bottom: 15px;
    font-weight: 600;
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

.service-features li:last-child {
    border-bottom: none;
}

.service-features li:before {
    content: '✓';
    position: absolute;
    left: 0;
    color: #00EC01;
    font-weight: bold;
    font-size: 1.2rem;
}

.process-step {
    background: white;
    padding: 20px;
    border-radius: 10px;
    border-left: 4px solid #00EC01;
}

.step-number {
    display: inline-block;
    width: 35px;
    height: 35px;
    background: #00EC01;
    color: white;
    border-radius: 50%;
    text-align: center;
    line-height: 35px;
    font-weight: bold;
    margin-right: 10px;
}

.contact-section {
    background: linear-gradient(135deg, rgba(0, 236, 1, 0.05), rgba(0, 51, 102, 0.02));
    border-radius: 20px;
    padding: 40px;
    text-align: center;
}

.contact-section h3 {
    color: #003366;
    margin-bottom: 15px;
}

.contact-section .phone {
    color: #00EC01;
    font-size: 1.5rem;
    font-weight: 600;
    text-decoration: none;
    display: block;
    margin: 20px 0;
}

.contact-section .phone:hover {
    color: #003366;
}

.info-box {
    background: #f8f9fa;
    padding: 25px;
    border-radius: 15px;
}

.info-box h4 {
    color: #003366;
    margin-bottom: 15px;
    font-weight: 600;
}

.info-box ul li {
    padding: 8px 0;
    color: #555;
}

.info-box i {
    color: #00EC01;
    margin-right: 10px;
}

.cta-section {
    background: linear-gradient(135deg, #003366, #00558b);
    color: white;
    padding: 40px;
    border-radius: 20px;
    text-align: center;
}

.cta-section h3 {
    color: white;
    margin-bottom: 15px;
}

.cta-section p {
    color: rgba(255, 255, 255, 0.9);
    font-size: 1.1rem;
    margin-bottom: 25px;
}

@media (max-width: 768px) {
    .service-hero h1 {
        font-size: 2rem;
    }

    .service-hero {
        padding: 60px 0;
    }
}
</style>

<?= $this->include('templates/footer'); ?>