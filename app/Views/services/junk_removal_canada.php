<?= $this->include('templates/header_pages'); ?>
<div class="service-hero">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <h1>Junk Removal Canada</h1>
                <p class="lead">Complete junk removal services across Canada and surrounding areas</p>
                <div class="mt-4">
                    <a href="<?= base_url('contact') ?>" class="btn btn-primary btn-lg me-3">Get Free Quote</a>
                    <a href="tel:+1-800-GARBAGE" class="btn btn-secondary btn-lg">Call Now</a>
                </div>
            </div>
            <div class="col-lg-6">
                <img src="<?= base_url('style/images/services/junk-removal.jpg') ?>" alt="Junk Removal Canada" class="img-fluid service-image">
            </div>
        </div>
    </div>
</div>
<div class="wrapper py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-8">
                <h2>Canada's Leading Junk Removal Service</h2>
                <p class="lead">Serving all of Canada with professional, reliable junk removal services for homes and businesses.</p>
                <div class="service-features mb-5">
                    <h3>Areas We Serve:</h3>
                    <ul><li>Melbourne CBD</li><li>Inner suburbs</li><li>Outer suburbs</li><li>Bayside areas</li><li>Northern suburbs</li><li>Western suburbs</li><li>Eastern suburbs</li><li>Southern suburbs</li></ul>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="contact-section">
                    <h3>Get Your Free Quote</h3>
                    <p>Serving all of Melbourne</p>
                    <a href="tel:+1-800-GARBAGE" class="phone">1-800-GARBAGE</a>
                    <div class="mt-3"><a href="<?= base_url('contact') ?>" class="btn btn-primary w-100">Request Quote</a></div>
                </div>
            </div>
        </div>
    </div>
</div>
<style>.service-hero{background:linear-gradient(135deg,rgba(0,31,63,0.95),rgba(17,17,17,0.8));color:white;padding:100px 0}.service-hero h1{color:white!important;font-size:3rem;font-weight:700}.service-features{background:rgba(0,236,1,0.03);border-radius:15px;padding:30px}.service-features ul{list-style:none;padding:0}.service-features li{padding:10px 0;border-bottom:1px solid rgba(0,236,1,0.1);position:relative;padding-left:30px}.service-features li:before{content:'✓';position:absolute;left:0;color:#00EC01;font-weight:bold}.contact-section{background:linear-gradient(135deg,rgba(0,236,1,0.05),rgba(0,51,102,0.02));border-radius:20px;padding:40px;text-align:center}.contact-section .phone{color:#00EC01;font-size:1.5rem;font-weight:600;text-decoration:none}</style>
<?= $this->include('templates/footer'); ?>