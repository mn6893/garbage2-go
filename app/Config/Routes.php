<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('about', 'Home::about');
$routes->get('services', 'Home::services');
$routes->get('contact', 'Home::contact');

// Household Rubbish Removal Services
$routes->get('services/household-rubbish-removal', 'Services::householdRubbishRemoval');
$routes->get('services/garage-rubbish-removal', 'Services::garageRubbishRemoval');
$routes->get('services/backyard-clean-up-and-rubbish-removal', 'Services::backyardCleanUp');
$routes->get('services/mattress-recycling-melbourne', 'Services::mattressRecycling');
$routes->get('rubbish-removal-melbourne', 'Services::rubbishRemovalMelbourne');

// Commercial Junk Removal Services
$routes->get('services/commercial-junk-removal', 'Services::commercialJunkRemoval');
$routes->get('services/real-estate-junk-removal', 'Services::realEstateJunkRemoval');
$routes->get('services/end-of-lease-rubbish-removal', 'Services::endOfLeaseRubbishRemoval');
$routes->get('services/office-junk-removal', 'Services::officeJunkRemoval');
$routes->get('services/renovation-rubbish-removal', 'Services::renovationRubbishRemoval');
$routes->get('services/retail-merchandise-rubbish-removal', 'Services::retailMerchandiseRubbishRemoval');
$routes->get('services/worksite-junk-removal', 'Services::worksiteJunkRemoval');

// Other Services
$routes->get('services/deceased-estate-rubbish-removal', 'Services::deceasedEstateRubbishRemoval');
$routes->get('services/green-waste-removal', 'Services::greenWasteRemoval');

// Location Routes
$routes->get('location', 'Locations::index');
$routes->get('rubbish-removal-toronto', 'Locations::toronto');
$routes->get('rubbish-removal-ottawa', 'Locations::ottawa');
$routes->get('rubbish-removal-mississauga', 'Locations::mississauga');
$routes->get('rubbish-removal-brampton', 'Locations::brampton');
$routes->get('rubbish-removal-hamilton', 'Locations::hamilton');
$routes->get('rubbish-removal-london', 'Locations::london');
$routes->get('rubbish-removal-markham', 'Locations::markham');
$routes->get('rubbish-removal-vaughan', 'Locations::vaughan');
$routes->get('rubbish-removal-kitchener', 'Locations::kitchener');
