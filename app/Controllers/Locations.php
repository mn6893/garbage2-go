<?php

namespace App\Controllers;

class Locations extends BaseController
{
    public function index()
    {
        $data = [
            'title' => 'Our Service Locations - Garbage2Go',
            'page_title' => 'Service Locations',
            'description' => 'We provide rubbish removal services across multiple locations'
        ];
        return view('locations/index', $data);
    }

    public function toronto()
    {
        $data = [
            'title' => 'Rubbish Removal Toronto - Garbage2Go',
            'location_name' => 'Toronto',
            'province' => 'Ontario',
            'description' => 'Professional rubbish removal services in Toronto and surrounding areas'
        ];
        return view('locations/location_detail', $data);
    }

    public function ottawa()
    {
        $data = [
            'title' => 'Rubbish Removal Ottawa - Garbage2Go',
            'location_name' => 'Ottawa',
            'province' => 'Ontario',
            'description' => 'Reliable junk removal services in Ottawa and the National Capital Region'
        ];
        return view('locations/location_detail', $data);
    }

    public function mississauga()
    {
        $data = [
            'title' => 'Rubbish Removal Mississauga - Garbage2Go',
            'location_name' => 'Mississauga',
            'province' => 'Ontario',
            'description' => 'Fast and efficient rubbish removal services in Mississauga'
        ];
        return view('locations/location_detail', $data);
    }

    public function brampton()
    {
        $data = [
            'title' => 'Rubbish Removal Brampton - Garbage2Go',
            'location_name' => 'Brampton',
            'province' => 'Ontario',
            'description' => 'Professional junk removal services serving Brampton residents'
        ];
        return view('locations/location_detail', $data);
    }

    public function hamilton()
    {
        $data = [
            'title' => 'Rubbish Removal Hamilton - Garbage2Go',
            'location_name' => 'Hamilton',
            'province' => 'Ontario',
            'description' => 'Comprehensive rubbish removal services in Hamilton'
        ];
        return view('locations/location_detail', $data);
    }

    public function london()
    {
        $data = [
            'title' => 'Rubbish Removal London - Garbage2Go',
            'location_name' => 'London',
            'province' => 'Ontario',
            'description' => 'Trusted junk removal services in London, Ontario'
        ];
        return view('locations/location_detail', $data);
    }

    public function markham()
    {
        $data = [
            'title' => 'Rubbish Removal Markham - Garbage2Go',
            'location_name' => 'Markham',
            'province' => 'Ontario',
            'description' => 'Expert rubbish removal services for Markham residents'
        ];
        return view('locations/location_detail', $data);
    }

    public function vaughan()
    {
        $data = [
            'title' => 'Rubbish Removal Vaughan - Garbage2Go',
            'location_name' => 'Vaughan',
            'province' => 'Ontario',
            'description' => 'Professional junk removal services in Vaughan'
        ];
        return view('locations/location_detail', $data);
    }

    public function kitchener()
    {
        $data = [
            'title' => 'Rubbish Removal Kitchener - Garbage2Go',
            'location_name' => 'Kitchener',
            'province' => 'Ontario',
            'description' => 'Reliable rubbish removal services in Kitchener-Waterloo'
        ];
        return view('locations/location_detail', $data);
    }
}