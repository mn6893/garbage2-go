<?= $this->include('admin/header') ?>

<div class="container-fluid py-4">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="mb-2">
                <i class="icofont-image mr-10"></i>
                AI Image Analysis
            </h2>
            <p class="text-muted">Upload images to analyze with AI for waste assessment and junk identification</p>
        </div>
    </div>

    <!-- Alert Messages -->
    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="icofont-check-circled mr-10"></i>
            <?= session()->getFlashdata('success') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="icofont-warning mr-10"></i>
            <?= session()->getFlashdata('error') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <!-- Upload Form -->
    <div class="row">
        <div class="col-lg-8 col-md-10 mx-auto">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">
                        <i class="icofont-upload-alt mr-10"></i>
                        Upload Images for AI Analysis
                    </h4>
                </div>
                <div class="card-body">
                    <form action="<?= base_url('admin/image-analysis/process') ?>" method="post" enctype="multipart/form-data" id="uploadForm">
                        <?= csrf_field() ?>

                        <div class="mb-4">
                            <label for="images" class="form-label">
                                <strong>Select Images</strong>
                                <span class="text-muted">(Multiple images allowed, max 5MB per image)</span>
                            </label>
                            <input type="file"
                                   class="form-control form-control-lg"
                                   id="images"
                                   name="images[]"
                                   accept="image/*"
                                   multiple
                                   required>
                            <div class="form-text">
                                Supported formats: JPG, JPEG, PNG, GIF, WEBP
                            </div>
                        </div>

                        <!-- Image Preview Area -->
                        <div id="imagePreview" class="mb-4"></div>

                        <!-- AI Analysis Info -->
                        <div class="alert alert-info">
                            <h6 class="alert-heading">
                                <i class="icofont-info-circle mr-10"></i>
                                What the AI will analyze:
                            </h6>
                            <ul class="mb-0">
                                <li>Type and category of junk/waste items</li>
                                <li>Estimated volume and quantity</li>
                                <li>Material composition</li>
                                <li>Special handling requirements</li>
                                <li>Disposal recommendations</li>
                                <li>Environmental considerations</li>
                            </ul>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg" id="submitBtn">
                                <i class="icofont-brain mr-10"></i>
                                Analyze with AI
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Instructions Card -->
            <div class="card shadow-sm mt-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0">
                        <i class="icofont-info-square mr-10"></i>
                        Tips for Best Results
                    </h5>
                </div>
                <div class="card-body">
                    <ul class="mb-0">
                        <li>Take clear, well-lit photos from multiple angles</li>
                        <li>Ensure items are visible and not obscured</li>
                        <li>Include close-ups of specific items and wide shots for context</li>
                        <li>Avoid blurry or dark images</li>
                        <li>Remove any personal or sensitive information from photos</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.image-preview-container {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
    gap: 15px;
    margin-top: 15px;
}

.image-preview-item {
    position: relative;
    border: 2px solid #dee2e6;
    border-radius: 8px;
    overflow: hidden;
    aspect-ratio: 1;
}

.image-preview-item img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.image-preview-item .image-name {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    background: rgba(0,0,0,0.7);
    color: white;
    padding: 5px;
    font-size: 11px;
    text-align: center;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.btn-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
}

.btn-primary:hover {
    background: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
}
</style>

<script>
document.getElementById('images').addEventListener('change', function(e) {
    const preview = document.getElementById('imagePreview');
    preview.innerHTML = '<h6 class="mb-3">Selected Images:</h6><div class="image-preview-container"></div>';
    const container = preview.querySelector('.image-preview-container');

    const files = Array.from(e.target.files);

    files.forEach((file, index) => {
        if (file.type.startsWith('image/')) {
            const reader = new FileReader();

            reader.onload = function(event) {
                const div = document.createElement('div');
                div.className = 'image-preview-item';
                div.innerHTML = `
                    <img src="${event.target.result}" alt="Preview ${index + 1}">
                    <div class="image-name">${file.name}</div>
                `;
                container.appendChild(div);
            };

            reader.readAsDataURL(file);
        }
    });
});

document.getElementById('uploadForm').addEventListener('submit', function() {
    const btn = document.getElementById('submitBtn');
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm mr-10"></span>Processing... Please wait';
});
</script>

<?= $this->include('admin/footer') ?>
