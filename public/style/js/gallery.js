// Gallery JavaScript Functionality
document.addEventListener('DOMContentLoaded', function() {
    
    // Initialize all before/after sliders
    initializeBeforeAfterSliders();
    
    // Initialize gallery filtering
    initializeGalleryFiltering();
    
    // Initialize modal functionality
    initializeModalFunctionality();
    
    // Initialize view details functionality
    initializeViewDetails();
    
    /**
     * Initialize before/after sliders
     */
    function initializeBeforeAfterSliders() {
        // Regular gallery sliders
        const sliders = document.querySelectorAll('.before-after-slider');
        sliders.forEach(slider => {
            initializeSlider(slider, '.after-image');
        });
        
        // Detail page slider
        const detailSlider = document.querySelector('.detail-before-after-slider');
        if (detailSlider) {
            initializeSlider(detailSlider, '.detail-after-image');
        }
        
        // Modal slider
        const modalSlider = document.querySelector('.modal-before-after-slider');
        if (modalSlider) {
            initializeSlider(modalSlider, '.modal-after-image');
        }
    }
    
    /**
     * Initialize individual slider
     */
    function initializeSlider(slider, afterImageSelector) {
        const afterImage = slider.querySelector(afterImageSelector);
        const handle = slider.querySelector('.slider-handle, .detail-slider-handle, .modal-slider-handle');
        
        let isDragging = false;
        let startX = 0;
        let currentX = 50; // Start at 50%
        
        // Update clip path
        function updateClipPath(percentage) {
            if (afterImage) {
                afterImage.style.clipPath = `polygon(${percentage}% 0%, 100% 0%, 100% 100%, ${percentage}% 100%)`;
            }
            if (handle) {
                handle.style.left = percentage + '%';
            }
        }
        
        // Mouse events
        if (handle) {
            handle.addEventListener('mousedown', startDrag);
        }
        
        slider.addEventListener('mousemove', drag);
        slider.addEventListener('mouseup', stopDrag);
        slider.addEventListener('mouseleave', stopDrag);
        
        // Touch events for mobile
        if (handle) {
            handle.addEventListener('touchstart', startDragTouch, { passive: false });
        }
        
        slider.addEventListener('touchmove', dragTouch, { passive: false });
        slider.addEventListener('touchend', stopDrag);
        
        // Click to set position
        slider.addEventListener('click', function(e) {
            if (!isDragging) {
                const rect = slider.getBoundingClientRect();
                const x = e.clientX - rect.left;
                const percentage = (x / rect.width) * 100;
                currentX = Math.max(0, Math.min(100, percentage));
                updateClipPath(currentX);
            }
        });
        
        function startDrag(e) {
            isDragging = true;
            startX = e.clientX;
            e.preventDefault();
        }
        
        function startDragTouch(e) {
            isDragging = true;
            startX = e.touches[0].clientX;
            e.preventDefault();
        }
        
        function drag(e) {
            if (!isDragging) return;
            
            const rect = slider.getBoundingClientRect();
            const x = e.clientX - rect.left;
            const percentage = (x / rect.width) * 100;
            currentX = Math.max(0, Math.min(100, percentage));
            updateClipPath(currentX);
        }
        
        function dragTouch(e) {
            if (!isDragging) return;
            
            const rect = slider.getBoundingClientRect();
            const x = e.touches[0].clientX - rect.left;
            const percentage = (x / rect.width) * 100;
            currentX = Math.max(0, Math.min(100, percentage));
            updateClipPath(currentX);
            e.preventDefault();
        }
        
        function stopDrag() {
            isDragging = false;
        }
        
        // Initialize at 50%
        updateClipPath(currentX);
    }
    
    /**
     * Initialize gallery filtering
     */
    function initializeGalleryFiltering() {
        const filterButtons = document.querySelectorAll('.gallery-filter');
        const galleryItems = document.querySelectorAll('.gallery-item');
        
        filterButtons.forEach(button => {
            button.addEventListener('click', function() {
                const filter = this.getAttribute('data-filter');
                
                // Update active button
                filterButtons.forEach(btn => btn.classList.remove('active'));
                this.classList.add('active');
                
                // Filter items
                galleryItems.forEach(item => {
                    const category = item.getAttribute('data-category');
                    
                    if (filter === 'all' || category === filter) {
                        item.classList.remove('hidden');
                        setTimeout(() => {
                            item.style.opacity = '1';
                            item.style.transform = 'scale(1)';
                        }, 50);
                    } else {
                        item.style.opacity = '0';
                        item.style.transform = 'scale(0.8)';
                        setTimeout(() => {
                            item.classList.add('hidden');
                        }, 300);
                    }
                });
            });
        });
    }
    
    /**
     * Initialize modal functionality
     */
    function initializeModalFunctionality() {
        const enlargeButtons = document.querySelectorAll('.enlarge-image');
        const modal = document.getElementById('imageModal');
        const modalBeforeImg = document.getElementById('modal-before-img');
        const modalAfterImg = document.getElementById('modal-after-img');
        const modalTitle = document.getElementById('imageModalLabel');
        
        if (!modal) return;
        
        enlargeButtons.forEach(button => {
            button.addEventListener('click', function() {
                const beforeSrc = this.getAttribute('data-before');
                const afterSrc = this.getAttribute('data-after');
                const title = this.getAttribute('data-title');
                
                modalBeforeImg.src = beforeSrc;
                modalAfterImg.src = afterSrc;
                modalTitle.textContent = title;
                
                // Show modal using Bootstrap
                const bootstrapModal = new bootstrap.Modal(modal);
                bootstrapModal.show();
                
                // Reinitialize slider after modal is shown
                setTimeout(() => {
                    initializeBeforeAfterSliders();
                }, 100);
            });
        });
        
        // Reset modal slider when closed
        modal.addEventListener('hidden.bs.modal', function() {
            const modalSlider = modal.querySelector('.modal-before-after-slider');
            if (modalSlider) {
                const afterImage = modalSlider.querySelector('.modal-after-image');
                const handle = modalSlider.querySelector('.modal-slider-handle');
                
                if (afterImage) {
                    afterImage.style.clipPath = 'polygon(50% 0%, 100% 0%, 100% 100%, 50% 100%)';
                }
                if (handle) {
                    handle.style.left = '50%';
                }
            }
        });
    }
    
    /**
     * Initialize view details functionality
     */
    function initializeViewDetails() {
        const viewDetailsButtons = document.querySelectorAll('.view-details');
        
        viewDetailsButtons.forEach(button => {
            button.addEventListener('click', function() {
                const itemId = this.getAttribute('data-item-id');
                
                // Add loading state
                this.innerHTML = '<i class="spinner-border spinner-border-sm me-2"></i>Loading...';
                this.disabled = true;
                
                // Navigate to detail page
                setTimeout(() => {
                    window.location.href = `/gallery/${itemId}`;
                }, 500);
            });
        });
    }
    
    /**
     * Keyboard navigation for accessibility
     */
    document.addEventListener('keydown', function(e) {
        const focusedSlider = document.querySelector('.before-after-slider:focus, .detail-before-after-slider:focus, .modal-before-after-slider:focus');
        
        if (focusedSlider && (e.key === 'ArrowLeft' || e.key === 'ArrowRight')) {
            e.preventDefault();
            
            const afterImage = focusedSlider.querySelector('.after-image, .detail-after-image, .modal-after-image');
            const handle = focusedSlider.querySelector('.slider-handle, .detail-slider-handle, .modal-slider-handle');
            
            if (afterImage && handle) {
                const currentLeft = parseFloat(handle.style.left) || 50;
                const step = 5;
                let newLeft = currentLeft;
                
                if (e.key === 'ArrowLeft') {
                    newLeft = Math.max(0, currentLeft - step);
                } else if (e.key === 'ArrowRight') {
                    newLeft = Math.min(100, currentLeft + step);
                }
                
                afterImage.style.clipPath = `polygon(${newLeft}% 0%, 100% 0%, 100% 100%, ${newLeft}% 100%)`;
                handle.style.left = newLeft + '%';
            }
        }
    });
    
    /**
     * Lazy loading for images
     */
    function initializeLazyLoading() {
        const images = document.querySelectorAll('img[data-src]');
        
        const imageObserver = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    img.src = img.dataset.src;
                    img.classList.remove('lazy');
                    imageObserver.unobserve(img);
                }
            });
        });
        
        images.forEach(img => imageObserver.observe(img));
    }
    
    // Initialize lazy loading if supported
    if ('IntersectionObserver' in window) {
        initializeLazyLoading();
    }
    
    /**
     * Smooth scroll for anchor links
     */
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth'
                });
            }
        });
    });
    
    /**
     * Performance optimization: Debounce resize events
     */
    let resizeTimeout;
    window.addEventListener('resize', function() {
        clearTimeout(resizeTimeout);
        resizeTimeout = setTimeout(() => {
            // Reinitialize sliders on resize to maintain proper positioning
            initializeBeforeAfterSliders();
        }, 250);
    });
    
});

/**
 * Utility function to preload images
 */
function preloadImages(imageArray) {
    const promises = imageArray.map(src => {
        return new Promise((resolve, reject) => {
            const img = new Image();
            img.onload = resolve;
            img.onerror = reject;
            img.src = src;
        });
    });
    
    return Promise.all(promises);
}

/**
 * Add smooth entrance animations
 */
function addEntranceAnimations() {
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };
    
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
                observer.unobserve(entry.target);
            }
        });
    }, observerOptions);
    
    // Apply to gallery items
    document.querySelectorAll('.gallery-item').forEach((item, index) => {
        item.style.opacity = '0';
        item.style.transform = 'translateY(30px)';
        item.style.transition = `opacity 0.6s ease ${index * 0.1}s, transform 0.6s ease ${index * 0.1}s`;
        observer.observe(item);
    });
}

// Initialize entrance animations when DOM is ready
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', addEntranceAnimations);
} else {
    addEntranceAnimations();
}