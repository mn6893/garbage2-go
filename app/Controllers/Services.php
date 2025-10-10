<?php

namespace App\Controllers;

class Services extends BaseController
{
    public function householdRubbishRemoval()
    {
        $data = [
            'title' => 'Household Rubbish Removal - Garbage2Go',
            'service_title' => 'Household Rubbish Removal',
            'service_description' => 'Professional household rubbish removal services for your home',
            'hero_image' => 'household-rubbish.jpg'
        ];
        return view('services/household_rubbish_removal', $data);
    }

    public function garageRubbishRemoval()
    {
        $data = [
            'title' => 'Garage Junk Removal - Garbage2Go',
            'service_title' => 'Garage Junk Removal',
            'service_description' => 'Clear out your garage with our professional junk removal service',
            'hero_image' => 'garage-junk.jpg'
        ];
        return view('services/garage_rubbish_removal', $data);
    }

    public function backyardCleanUp()
    {
        $data = [
            'title' => 'Backyard Clean-up and Rubbish Removal - Garbage2Go',
            'service_title' => 'Backyard Clean-up & Rubbish Removal',
            'service_description' => 'Transform your backyard with our comprehensive clean-up service',
            'hero_image' => 'backyard-cleanup.jpg'
        ];
        return view('services/backyard_clean_up', $data);
    }

    public function mattressRecycling()
    {
        $data = [
            'title' => 'Mattress Recycling Melbourne - Garbage2Go',
            'service_title' => 'Mattress Recycling Melbourne',
            'service_description' => 'Eco-friendly mattress recycling and disposal services',
            'hero_image' => 'mattress-recycling.jpg'
        ];
        return view('services/mattress_recycling', $data);
    }

    public function rubbishRemovalMelbourne()
    {
        $data = [
            'title' => 'Rubbish Removal Melbourne - Garbage2Go',
            'service_title' => 'Rubbish Removal Melbourne',
            'service_description' => 'Complete rubbish removal services across Melbourne',
            'hero_image' => 'rubbish-removal.jpg'
        ];
        return view('services/rubbish_removal_melbourne', $data);
    }

    public function commercialJunkRemoval()
    {
        $data = [
            'title' => 'Commercial Junk Removal - Garbage2Go',
            'service_title' => 'Commercial Junk Removal',
            'service_description' => 'Professional junk removal services for businesses',
            'hero_image' => 'commercial-junk.jpg'
        ];
        return view('services/commercial_junk_removal', $data);
    }

    public function realEstateJunkRemoval()
    {
        $data = [
            'title' => 'Real Estate Junk Removal - Garbage2Go',
            'service_title' => 'Real Estate Junk Removal',
            'service_description' => 'Junk removal services for real estate properties',
            'hero_image' => 'real-estate-junk.jpg'
        ];
        return view('services/real_estate_junk_removal', $data);
    }

    public function endOfLeaseRubbishRemoval()
    {
        $data = [
            'title' => 'End of Lease Rubbish Removal - Garbage2Go',
            'service_title' => 'End of Lease Rubbish Removal',
            'service_description' => 'Get your bond back with our end of lease cleaning service',
            'hero_image' => 'end-of-lease.jpg'
        ];
        return view('services/end_of_lease_rubbish_removal', $data);
    }

    public function officeJunkRemoval()
    {
        $data = [
            'title' => 'Office Junk Removal - Garbage2Go',
            'service_title' => 'Office Junk Removal',
            'service_description' => 'Professional office cleanout and junk removal services',
            'hero_image' => 'office-junk.jpg'
        ];
        return view('services/office_junk_removal', $data);
    }

    public function renovationRubbishRemoval()
    {
        $data = [
            'title' => 'Renovation Rubbish Removal - Garbage2Go',
            'service_title' => 'Renovation Rubbish Removal',
            'service_description' => 'Clean up after your renovation with our professional service',
            'hero_image' => 'renovation-rubbish.jpg'
        ];
        return view('services/renovation_rubbish_removal', $data);
    }

    public function retailMerchandiseRubbishRemoval()
    {
        $data = [
            'title' => 'Retail Merchandise Rubbish Removal - Garbage2Go',
            'service_title' => 'Retail Merchandise Rubbish Removal',
            'service_description' => 'Dispose of old retail merchandise responsibly',
            'hero_image' => 'retail-merchandise.jpg'
        ];
        return view('services/retail_merchandise_rubbish_removal', $data);
    }

    public function worksiteJunkRemoval()
    {
        $data = [
            'title' => 'Worksite Junk Removal - Garbage2Go',
            'service_title' => 'Worksite Junk Removal',
            'service_description' => 'Keep your worksite clean with our professional service',
            'hero_image' => 'worksite-junk.jpg'
        ];
        return view('services/worksite_junk_removal', $data);
    }

    public function deceasedEstateRubbishRemoval()
    {
        $data = [
            'title' => 'Deceased Estate Rubbish Removal - Garbage2Go',
            'service_title' => 'Deceased Estate Rubbish Removal',
            'service_description' => 'Compassionate cleanout services for deceased estates',
            'hero_image' => 'deceased-estate.jpg'
        ];
        return view('services/deceased_estate_rubbish_removal', $data);
    }

    public function greenWasteRemoval()
    {
        $data = [
            'title' => 'Green Waste Removal - Garbage2Go',
            'service_title' => 'Green Waste Removal',
            'service_description' => 'Eco-friendly garden and green waste removal service',
            'hero_image' => 'green-waste.jpg'
        ];
        return view('services/green_waste_removal', $data);
    }
}