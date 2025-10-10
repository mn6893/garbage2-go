<?= $this->include('templates/header_pages'); ?>

<div class="wrapper light-wrapper">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 offset-lg-2 text-center">
                <h1 class="page-title">Our Service Locations</h1>
                <p class="lead">We provide professional junk removal services across multiple locations in Ontario, Canada</p>
            </div>
        </div>
    </div>
</div>

<div class="wrapper py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <h2 class="text-center mb-5">Cities We Serve</h2>
                <div class="row">
                    <div class="col-md-4 mb-4">
                        <div class="service-box text-center">
                            <h4><a href="<?= base_url('junk-removal-toronto') ?>">Toronto</a></h4>
                            <p>Canada's largest city - comprehensive junk removal services across all boroughs</p>
                        </div>
                    </div>
                    <div class="col-md-4 mb-4">
                        <div class="service-box text-center">
                            <h4><a href="<?= base_url('rubbish-removal-ottawa') ?>">Ottawa</a></h4>
                            <p>National Capital Region - serving Ottawa and surrounding communities</p>
                        </div>
                    </div>
                    <div class="col-md-4 mb-4">
                        <div class="service-box text-center">
                            <h4><a href="<?= base_url('rubbish-removal-mississauga') ?>">Mississauga</a></h4>
                            <p>Professional junk removal services in Canada's sixth-largest city</p>
                        </div>
                    </div>
                    <div class="col-md-4 mb-4">
                        <div class="service-box text-center">
                            <h4><a href="<?= base_url('rubbish-removal-brampton') ?>">Brampton</a></h4>
                            <p>Reliable rubbish removal for Brampton residents and businesses</p>
                        </div>
                    </div>
                    <div class="col-md-4 mb-4">
                        <div class="service-box text-center">
                            <h4><a href="<?= base_url('rubbish-removal-hamilton') ?>">Hamilton</a></h4>
                            <p>Complete junk removal services in the Steel City</p>
                        </div>
                    </div>
                    <div class="col-md-4 mb-4">
                        <div class="service-box text-center">
                            <h4><a href="<?= base_url('rubbish-removal-london') ?>">London</a></h4>
                            <p>Professional rubbish removal in London, Ontario</p>
                        </div>
                    </div>
                    <div class="col-md-4 mb-4">
                        <div class="service-box text-center">
                            <h4><a href="<?= base_url('rubbish-removal-markham') ?>">Markham</a></h4>
                            <p>Expert junk removal services in Markham and York Region</p>
                        </div>
                    </div>
                    <div class="col-md-4 mb-4">
                        <div class="service-box text-center">
                            <h4><a href="<?= base_url('rubbish-removal-vaughan') ?>">Vaughan</a></h4>
                            <p>Comprehensive rubbish removal for Vaughan communities</p>
                        </div>
                    </div>
                    <div class="col-md-4 mb-4">
                        <div class="service-box text-center">
                            <h4><a href="<?= base_url('rubbish-removal-kitchener') ?>">Kitchener</a></h4>
                            <p>Professional junk removal in the Kitchener-Waterloo region</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="wrapper light-wrapper">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 offset-lg-2 text-center">
                <h2>Don't See Your City?</h2>
                <p class="lead">We're expanding our services all the time. Contact us to see if we serve your area or if we can arrange special service.</p>
                <div class="mt-4">
                    <a href="<?= base_url('contact') ?>" class="btn btn-primary btn-lg">Contact Us</a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.service-box {
    background: rgba(0, 236, 1, 0.03);
    border: 2px solid rgba(0, 236, 1, 0.2);
    border-radius: 15px;
    padding: 30px;
    transition: all 0.4s ease;
    box-shadow: 0 5px 20px rgba(17, 17, 17, 0.08);
}

.service-box:hover {
    transform: translateY(-10px);
    border-color: #00EC01;
    box-shadow: 0 15px 40px rgba(0, 236, 1, 0.2);
}

.service-box h4 a {
    color: #111111;
    text-decoration: none;
    font-weight: 600;
}

.service-box h4 a:hover {
    color: #00EC01;
}

.page-title {
    background: linear-gradient(135deg, #00EC01, #003366);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    font-size: 3.5rem !important;
    font-weight: 700 !important;
    margin-bottom: 1rem;
}
</style>

<?= $this->include('templates/footer'); ?>