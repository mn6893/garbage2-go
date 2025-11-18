<?php

namespace App\Controllers;

class Gallery extends BaseController
{
    public function index(): string
    {
        $data = [
            'title' => 'Gallery - Before & After | Garbage2Go',
            'galleryItems' => $this->getGalleryItems()
        ];
        
        return view('gallery/index', $data);
    }

    /**
     * Get gallery items with before/after images
     */
    private function getGalleryItems(): array
    {
        return [
            [
                'id' => 1,
                'title' => 'Garage Cleanout - Toronto',
                'description' => 'Complete garage transformation with junk removal and organization',
                'before_image' => 'gallery/garage-before1.jpg',
                'after_image' => 'gallery/garage-after1.jpg',
                'category' => 'garage',
                'location' => 'Toronto, ON'
            ],
            [
                'id' => 2,
                'title' => 'Garage Cleanout - Toronto',
                'description' => 'Full garage cleanout and debris removal',
                'before_image' => 'gallery/garage-before2.jpg',
                'after_image' => 'gallery/garage-after2.jpg',
                'category' => 'garage',
                'location' => 'Toronto, ON'
            ],
            [
                'id' => 3,
                'title' => 'Backyard Cleanup - Toronto',
                'description' => 'Garden cleanup and green waste removal',
                'before_image' => 'gallery/garage-before6.jpg',
                'after_image' => 'gallery/garage-after6.jpg',
                'category' => 'commercial',
                'location' => 'Toronto, ON'
            ],
            [
                'id' => 4,
                'title' => 'Backyard Cleanup - Hamilton',
                'description' => 'Garden cleanup and green waste removal',
                'before_image' => 'gallery/garage-before5.jpg',
                'after_image' => 'gallery/garage-after5.jpg',
                'category' => 'outdoor',
                'location' => 'Hamilton, ON'
            ],
            [
                'id' => 5,
                'title' => 'Kitchen Renovation - Brampton',
                'description' => 'Kitchen renovation debris and old appliance removal',
                'before_image' => 'gallery/garage-before3.jpg',
                'after_image' => 'gallery/garage-after3.jpg',
                'category' => 'renovation',
                'location' => 'Brampton, ON'
            ],
            [
                'id' => 6,
                'title' => 'Estate Cleanout - London',
                'description' => 'Complete estate property cleanup and junk removal',
                'before_image' => 'gallery/garage-before4.jpg',
                'after_image' => 'gallery/garage-after4.jpg',
                'category' => 'estate',
                'location' => 'London, ON'
            ]
        ];
    }

    /**
     * Get single gallery item for detailed view
     */
    public function detail($id): string
    {
        $galleryItems = $this->getGalleryItems();
        $item = null;
        
        foreach ($galleryItems as $galleryItem) {
            if ($galleryItem['id'] == $id) {
                $item = $galleryItem;
                break;
            }
        }
        
        if (!$item) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
        
        $data = [
            'title' => $item['title'] . ' | Gallery | Garbage2Go',
            'item' => $item
        ];
        
        return view('gallery/detail', $data);
    }
}