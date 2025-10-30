<?php

namespace App\Controllers;

class Services extends BaseController
{
    public function householdJunkRemoval()
    {
        $data = [
            'title' => 'Household Junk Removal - GarbageToGo',
            'service_title' => 'Household Junk Removal',
            'service_description' => 'Professional household junk removal services for your home',
            'hero_image' => 'household-junk.jpg'
        ];
        return view('services/household_junk_removal', $data);
    }

    public function garageJunkRemoval()
    {
        $data = [
            'title' => 'Garage Junk Removal - GarbageToGo',
            'service_title' => 'Garage Junk Removal',
            'service_description' => 'Clear out your garage with our professional junk removal service',
            'hero_image' => 'garage-junk.jpg'
        ];
        return view('services/garage_junk_removal', $data);
    }

    public function backyardCleanUp()
    {
        $data = [
            'title' => 'Backyard Clean-up and Junk Removal - GarbageToGo',
            'service_title' => 'Backyard Clean-up & Junk Removal',
            'service_description' => 'Transform your backyard with our comprehensive clean-up service',
            'hero_image' => 'backyard-cleanup.jpg'
        ];
        return view('services/backyard_clean_up', $data);
    }

    public function mattressRecycling()
    {
        $data = [
            'title' => 'Mattress Recycling Canada - GarbageToGo',
            'service_title' => 'Mattress Recycling Canada',
            'service_description' => 'Eco-friendly mattress recycling and disposal services',
            'hero_image' => 'mattress-recycling.jpg'
        ];
        return view('services/mattress_recycling', $data);
    }

    public function junkRemovalCanada()
    {
        $data = [
            'title' => 'Junk Removal Canada - GarbageToGo',
            'service_title' => 'Junk Removal Canada',
            'service_description' => 'Complete junk removal services across Canada',
            'hero_image' => 'junk-removal.jpg'
        ];
        return view('services/junk_removal_canada', $data);
    }

    public function commercialJunkRemoval()
    {
        $data = [
            'title' => 'Commercial Junk Removal - GarbageToGo',
            'service_title' => 'Commercial Junk Removal',
            'service_description' => 'Professional junk removal services for businesses',
            'hero_image' => 'commercial-junk.jpg'
        ];
        return view('services/commercial_junk_removal', $data);
    }

    public function realEstateJunkRemoval()
    {
        $data = [
            'title' => 'Real Estate Junk Removal - GarbageToGo',
            'service_title' => 'Real Estate Junk Removal',
            'service_description' => 'Junk removal services for real estate properties',
            'hero_image' => 'real-estate-junk.jpg'
        ];
        return view('services/real_estate_junk_removal', $data);
    }

    public function endOfLeaseJunkRemoval()
    {
        $data = [
            'title' => 'End of Lease Junk Removal - GarbageToGo',
            'service_title' => 'End of Lease Junk Removal',
            'service_description' => 'Get your bond back with our end of lease cleaning service',
            'hero_image' => 'end-of-lease.jpg'
        ];
        return view('services/end_of_lease_junk_removal', $data);
    }

    public function officeJunkRemoval()
    {
        $data = [
            'title' => 'Office Junk Removal - GarbageToGo',
            'service_title' => 'Office Junk Removal',
            'service_description' => 'Professional office cleanout and junk removal services',
            'hero_image' => 'office-junk.jpg'
        ];
        return view('services/office_junk_removal', $data);
    }

    public function renovationJunkRemoval()
    {
        $data = [
            'title' => 'Renovation Junk Removal - GarbageToGo',
            'service_title' => 'Renovation Junk Removal',
            'service_description' => 'Clean up after your renovation with our professional service',
            'hero_image' => 'renovation-junk.jpg'
        ];
        return view('services/renovation_junk_removal', $data);
    }

    public function retailMerchandiseJunkRemoval()
    {
        $data = [
            'title' => 'Retail Merchandise Junk Removal - GarbageToGo',
            'service_title' => 'Retail Merchandise Junk Removal',
            'service_description' => 'Dispose of old retail merchandise responsibly',
            'hero_image' => 'retail-merchandise.jpg'
        ];
        return view('services/retail_merchandise_junk_removal', $data);
    }

    public function worksiteJunkRemoval()
    {
        $data = [
            'title' => 'Worksite Junk Removal - GarbageToGo',
            'service_title' => 'Worksite Junk Removal',
            'service_description' => 'Keep your worksite clean with our professional service',
            'hero_image' => 'worksite-junk.jpg'
        ];
        return view('services/worksite_junk_removal', $data);
    }

    public function deceasedEstateJunkRemoval()
    {
        $data = [
            'title' => 'Deceased Estate Junk Removal - GarbageToGo',
            'service_title' => 'Deceased Estate Junk Removal',
            'service_description' => 'Compassionate cleanout services for deceased estates',
            'hero_image' => 'deceased-estate.jpg'
        ];
        return view('services/deceased_estate_junk_removal', $data);
    }

    public function greenWasteRemoval()
    {
        $data = [
            'title' => 'Green Waste Removal - GarbageToGo',
            'service_title' => 'Green Waste Removal',
            'service_description' => 'Eco-friendly garden and green waste removal service',
            'hero_image' => 'green-waste.jpg'
        ];
        return view('services/green_waste_removal', $data);
    }

    public function electronicRecycling()
    {
        $data = [
            'title' => 'Electronic Recycling & Disposal Services - GarbageToGo',
            'service_title' => 'Electronic Recycling & Disposal Services',
            'service_description' => 'Responsible electronic waste recycling with secure data destruction',
            'hero_image' => 'electronic-recycling.jpg'
        ];
        return view('services/electronic_recycling', $data);
    }
}