<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('about', 'Home::about');
$routes->get('services', 'Home::services');
$routes->get('contact', 'Home::contact');
$routes->post('contact/submit', 'Home::submitContact');

// Household Junk Removal Services
$routes->get('services/household-junk-removal', 'Services::householdJunkRemoval');
$routes->get('services/garage-junk-removal', 'Services::garageJunkRemoval');
$routes->get('services/backyard-clean-up-and-junk-removal', 'Services::backyardCleanUp');
$routes->get('services/mattress-recycling-canada', 'Services::mattressRecycling');
$routes->get('services/junk-removal-canada', 'Services::junkRemovalCanada');

// Commercial Junk Removal Services
$routes->get('services/commercial-junk-removal', 'Services::commercialJunkRemoval');
$routes->get('services/real-estate-junk-removal', 'Services::realEstateJunkRemoval');
$routes->get('services/end-of-lease-junk-removal', 'Services::endOfLeaseJunkRemoval');
$routes->get('services/office-junk-removal', 'Services::officeJunkRemoval');
$routes->get('services/renovation-junk-removal', 'Services::renovationJunkRemoval');
$routes->get('services/retail-merchandise-junk-removal', 'Services::retailMerchandiseJunkRemoval');
$routes->get('services/worksite-junk-removal', 'Services::worksiteJunkRemoval');

// Other Services
$routes->get('services/deceased-estate-junk-removal', 'Services::deceasedEstateJunkRemoval');
$routes->get('services/green-waste-removal', 'Services::greenWasteRemoval');
$routes->get('services/electronic-recycling', 'Services::electronicRecycling');

// Quote Routes
$routes->get('quote', 'Quote::index');
$routes->post('quote/submit', 'Quote::submit');
$routes->get('quote/success', 'Quote::success');
$routes->get('quote/image/(:any)', 'Quote::serveImage/$1');
$routes->get('quote/confirm/(:any)/(:alpha)', 'Quote::confirm/$1/$2');
$routes->post('quote/response', 'Quote::processResponse');
$routes->post('quote/talk-to-manager/submit', 'Quote::submitTalkToManager');

// Gallery Routes
$routes->get('gallery', 'Gallery::index');
$routes->get('gallery/(:num)', 'Gallery::detail/$1');

// Location Routes
$routes->get('location', 'Locations::index');
$routes->get('junk-removal-toronto', 'Locations::toronto');
$routes->get('junk-removal-ottawa', 'Locations::ottawa');
$routes->get('junk-removal-mississauga', 'Locations::mississauga');
$routes->get('junk-removal-brampton', 'Locations::brampton');
$routes->get('junk-removal-hamilton', 'Locations::hamilton');
$routes->get('junk-removal-london', 'Locations::london');
$routes->get('junk-removal-markham', 'Locations::markham');
$routes->get('junk-removal-vaughan', 'Locations::vaughan');
$routes->get('junk-removal-kitchener', 'Locations::kitchener');

// Admin Routes
$routes->group('admin', function($routes) {
    $routes->get('login', 'Admin::login');
    $routes->post('authenticate', 'Admin::authenticate');
    $routes->get('logout', 'Admin::logout');
    $routes->get('dashboard', 'Admin::dashboard');
    $routes->get('quotes', 'Admin::quotes');
    $routes->get('quote/(:num)', 'Admin::quote/$1');
    $routes->post('quote/update-status', 'Admin::updateQuoteStatus');
    $routes->post('quote/update-amount', 'Admin::updateQuoteAmount');
    $routes->post('quote/update-cost-breakdown', 'Admin::updateCostBreakdown');
    $routes->post('quote/(:num)/process-ai', 'Admin::processQuoteAI/$1');
    $routes->post('quote/(:num)/upload-images', 'Admin::uploadQuoteImages/$1');
    $routes->post('retry-quote-emails/(:num)', 'Admin::retryQuoteEmails/$1');
    $routes->post('bulk-retry-emails', 'Admin::bulkRetryEmails');
    $routes->get('quote/image/(:num)/(:num)', 'Admin::viewQuoteImage/$1/$2');
    $routes->get('quote/download/(:num)/(:num)', 'Admin::downloadQuoteImage/$1/$2');
    $routes->get('contacts', 'Admin::contacts');
    $routes->get('contact/(:num)', 'Admin::contact/$1');
    $routes->post('contact/update-status', 'Admin::updateContactStatus');
    $routes->get('image-analysis', 'Admin::imageAnalysis');
    $routes->post('image-analysis/process', 'Admin::processImageAnalysis');
    $routes->get('image-analysis/results', 'Admin::analysisResults');
    $routes->get('view-analysis-image/(:any)', 'Admin::viewAnalysisImage/$1');
});
