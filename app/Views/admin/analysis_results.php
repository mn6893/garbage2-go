<?= $this->include('admin/header') ?>

<div class="container-fluid py-4">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="mb-2">
                        <i class="icofont-brain mr-10"></i>
                        AI Analysis Results
                    </h2>
                    <p class="text-muted">Detailed analysis of uploaded images</p>
                </div>
                <div>
                    <a href="<?= base_url('admin/image-analysis') ?>" class="btn btn-primary">
                        <i class="icofont-upload-alt mr-10"></i>
                        Analyze New Images
                    </a>
                </div>
            </div>
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

    <!-- Results Summary -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">
                        <i class="icofont-chart-bar-graph mr-10"></i>
                        Analysis Summary
                    </h5>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="text-center p-3 bg-light rounded">
                                <h3 class="mb-1"><?= count($results) ?></h3>
                                <p class="mb-0 text-muted">Images Analyzed</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="text-center p-3 bg-light rounded">
                                <h3 class="mb-1 text-success">
                                    <i class="icofont-check-circled"></i>
                                </h3>
                                <p class="mb-0 text-muted">Analysis Complete</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="text-center p-3 bg-light rounded">
                                <h3 class="mb-1"><?= date('Y-m-d H:i:s') ?></h3>
                                <p class="mb-0 text-muted">Timestamp</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Individual Results -->
    <?php foreach ($results as $index => $result): ?>
        <div class="row mb-4">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">
                            <i class="icofont-image mr-10"></i>
                            Image <?= $index + 1 ?>: <?= htmlspecialchars($result['image_name']) ?>
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <!-- Image Preview -->
                            <div class="col-md-4">
                                <div class="text-center">
                                    <img src="<?= base_url('admin/view-analysis-image/' . $result['image_name']) ?>"
                                         alt="Analyzed Image"
                                         class="img-fluid rounded shadow-sm mb-3"
                                         style="max-height: 400px; object-fit: contain;">
                                    <div>
                                        <small class="text-muted">
                                            <i class="icofont-file mr-5"></i>
                                            <?= $result['image_name'] ?>
                                        </small>
                                    </div>
                                </div>
                            </div>

                            <!-- Analysis Results -->
                            <div class="col-md-8">
                                <?php if (isset($result['success']) && $result['success']): ?>

                                    <!-- Quote Summary -->
                                    <div class="alert alert-success mb-4">
                                        <h5 class="mb-3">
                                            <i class="icofont-dollar-true mr-10"></i>
                                            Quote Generated Successfully
                                        </h5>
                                        <div class="row text-center">
                                            <div class="col-md-6">
                                                <h4 class="mb-0 text-success">$<?= number_format($result['quote']['estimatedCost']['min'], 2) ?></h4>
                                                <small class="text-muted">Minimum Cost</small>
                                            </div>
                                            <div class="col-md-6">
                                                <h4 class="mb-0 text-primary">$<?= number_format($result['quote']['estimatedCost']['max'], 2) ?></h4>
                                                <small class="text-muted">Maximum Cost</small>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Price Breakdown -->
                                    <div class="card mb-4">
                                        <div class="card-header bg-primary text-white">
                                            <h6 class="mb-0">
                                                <i class="icofont-calculator-alt-2 mr-10"></i>
                                                Detailed Price Breakdown
                                            </h6>
                                        </div>
                                        <div class="card-body">
                                            <table class="table table-sm table-hover mb-0">
                                                <tbody>
                                                    <tr>
                                                        <td><strong>Base Service Charge</strong></td>
                                                        <td class="text-end">$<?= number_format($result['quote']['breakdown']['baseCost'], 2) ?></td>
                                                    </tr>
                                                    <?php if ($result['quote']['breakdown']['volumeCost'] > 0): ?>
                                                    <tr>
                                                        <td>Volume-based Charge</td>
                                                        <td class="text-end">$<?= number_format($result['quote']['breakdown']['volumeCost'], 2) ?></td>
                                                    </tr>
                                                    <?php endif; ?>
                                                    <?php if ($result['quote']['breakdown']['specialFees'] > 0): ?>
                                                    <tr>
                                                        <td>Special Item Handling</td>
                                                        <td class="text-end">$<?= number_format($result['quote']['breakdown']['specialFees'], 2) ?></td>
                                                    </tr>
                                                    <?php endif; ?>
                                                    <?php if ($result['quote']['breakdown']['environmentalFee'] > 0): ?>
                                                    <tr>
                                                        <td>Environmental Disposal Fee</td>
                                                        <td class="text-end">$<?= number_format($result['quote']['breakdown']['environmentalFee'], 2) ?></td>
                                                    </tr>
                                                    <?php endif; ?>
                                                    <?php if ($result['quote']['breakdown']['disposalFee'] > 0): ?>
                                                    <tr>
                                                        <td>Disposal Fee</td>
                                                        <td class="text-end">$<?= number_format($result['quote']['breakdown']['disposalFee'], 2) ?></td>
                                                    </tr>
                                                    <?php endif; ?>
                                                    <?php if ($result['quote']['breakdown']['seasonalAdjustment'] > 0): ?>
                                                    <tr>
                                                        <td>Seasonal Adjustment</td>
                                                        <td class="text-end">$<?= number_format($result['quote']['breakdown']['seasonalAdjustment'], 2) ?></td>
                                                    </tr>
                                                    <?php endif; ?>
                                                    <tr class="table-light">
                                                        <td><strong>Subtotal</strong></td>
                                                        <td class="text-end"><strong>$<?= number_format($result['quote']['breakdown']['subtotal'], 2) ?></strong></td>
                                                    </tr>
                                                    <?php if ($result['quote']['breakdown']['gst'] > 0): ?>
                                                    <tr>
                                                        <td>GST (5%)</td>
                                                        <td class="text-end">$<?= number_format($result['quote']['breakdown']['gst'], 2) ?></td>
                                                    </tr>
                                                    <?php endif; ?>
                                                    <?php if ($result['quote']['breakdown']['pst'] > 0): ?>
                                                    <tr>
                                                        <td>PST</td>
                                                        <td class="text-end">$<?= number_format($result['quote']['breakdown']['pst'], 2) ?></td>
                                                    </tr>
                                                    <?php endif; ?>
                                                    <tr class="table-success">
                                                        <td><strong>Total Estimated Cost</strong></td>
                                                        <td class="text-end"><strong class="fs-5 text-success">$<?= number_format($result['quote']['breakdown']['total'], 2) ?></strong></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>

                                    <!-- Waste Assessment Details -->
                                    <div class="card mb-4">
                                        <div class="card-header bg-info text-white">
                                            <h6 class="mb-0">
                                                <i class="icofont-recycle mr-10"></i>
                                                Waste Assessment Details
                                            </h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="row mb-3">
                                                <div class="col-md-6">
                                                    <p class="mb-2"><strong>Waste Type:</strong></p>
                                                    <span class="badge bg-info fs-6"><?= htmlspecialchars($result['quote']['details']['wasteType']) ?></span>
                                                </div>
                                                <div class="col-md-6">
                                                    <p class="mb-2"><strong>Estimated Volume:</strong></p>
                                                    <span class="badge bg-secondary fs-6"><?= htmlspecialchars($result['quote']['details']['volume']) ?></span>
                                                </div>
                                            </div>
                                            <div class="mb-2">
                                                <p class="mb-1"><strong>Quote Valid Until:</strong></p>
                                                <span class="text-muted"><?= date('F j, Y', strtotime($result['quote']['details']['validUntil'])) ?></span>
                                            </div>
                                        </div>
                                    </div>

                                <!-- Display Analysis Data -->
                                <div class="analysis-content">
                                    <?php
                                    $aiAnalysis = $result['ai_analysis'] ?? [];
                                    $wasteAssessment = $result['waste_assessment'] ?? [];
                                    ?>

                                    <?php if (!empty($result['quote']['items'])): ?>
                                        <!-- Items Detected -->
                                        <div class="card mb-4">
                                            <div class="card-header bg-light">
                                                <h6 class="mb-0">
                                                    <i class="icofont-list mr-10"></i>
                                                    Items Detected
                                                </h6>
                                            </div>
                                            <div class="card-body">
                                                <div class="table-responsive">
                                                    <table class="table table-bordered table-sm">
                                                        <thead class="table-light">
                                                            <tr>
                                                                <th>Item</th>
                                                                <th>Quantity</th>
                                                                <th>Condition</th>
                                                                <th>Category</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php foreach ($result['quote']['items'] as $item): ?>
                                                                <tr>
                                                                    <td><?= htmlspecialchars($item['name'] ?? 'N/A') ?></td>
                                                                    <td><?= htmlspecialchars($item['quantity'] ?? 'N/A') ?></td>
                                                                    <td><?= htmlspecialchars($item['condition'] ?? 'N/A') ?></td>
                                                                    <td><?= htmlspecialchars($item['category'] ?? 'N/A') ?></td>
                                                                </tr>
                                                            <?php endforeach; ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endif; ?>

                                    <!-- Recommendations -->
                                    <?php if (!empty($result['quote']['recommendations'])): ?>
                                        <div class="card mb-4">
                                            <div class="card-header bg-light">
                                                <h6 class="mb-0">
                                                    <i class="icofont-star mr-10"></i>
                                                    Recommendations
                                                </h6>
                                            </div>
                                            <div class="card-body">
                                                <ul class="list-group list-group-flush">
                                                    <?php foreach ($result['quote']['recommendations'] as $recommendation): ?>
                                                        <li class="list-group-item">
                                                            <i class="icofont-check text-success mr-10"></i>
                                                            <?= htmlspecialchars($recommendation) ?>
                                                        </li>
                                                    <?php endforeach; ?>
                                                </ul>
                                            </div>
                                        </div>
                                    <?php endif; ?>

                                    <!-- Raw JSON Data (Collapsible) -->
                                    <div class="mb-3">
                                        <button class="btn btn-sm btn-outline-secondary" type="button" data-bs-toggle="collapse" data-bs-target="#rawData<?= $index ?>">
                                            <i class="icofont-code mr-5"></i>
                                            View Complete Raw Data
                                        </button>
                                        <div class="collapse mt-2" id="rawData<?= $index ?>">
                                            <div class="card">
                                                <div class="card-header bg-dark text-white">
                                                    <small>Complete Analysis Data (JSON)</small>
                                                </div>
                                                <div class="card-body p-0">
                                                    <pre class="bg-dark text-white p-3 mb-0" style="max-height: 400px; overflow-y: auto;"><code><?= json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) ?></code></pre>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                <?php else: ?>
                                    <!-- Error Message -->
                                    <div class="alert alert-danger">
                                        <h5 class="alert-heading">
                                            <i class="icofont-warning mr-10"></i>
                                            Analysis Failed
                                        </h5>
                                        <p class="mb-0">
                                            <strong>Error:</strong> <?= htmlspecialchars($result['error'] ?? 'Unknown error occurred during analysis') ?>
                                        </p>
                                    </div>
                                    <div class="mb-3">
                                        <button class="btn btn-sm btn-outline-secondary" type="button" data-bs-toggle="collapse" data-bs-target="#rawData<?= $index ?>">
                                            <i class="icofont-code mr-5"></i>
                                            View Error Details
                                        </button>
                                        <div class="collapse mt-2" id="rawData<?= $index ?>">
                                            <div class="card">
                                                <div class="card-header bg-danger text-white">
                                                    <small>Error Details (JSON)</small>
                                                </div>
                                                <div class="card-body p-0">
                                                    <pre class="bg-dark text-white p-3 mb-0" style="max-height: 400px; overflow-y: auto;"><code><?= json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) ?></code></pre>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>

    <!-- Back Button -->
    <div class="row">
        <div class="col-12 text-center">
            <a href="<?= base_url('admin/image-analysis') ?>" class="btn btn-lg btn-primary">
                <i class="icofont-upload-alt mr-10"></i>
                Analyze More Images
            </a>
        </div>
    </div>
</div>

<style>
.analysis-content {
    font-size: 14px;
}

.analysis-content h6 {
    border-bottom: 2px solid #667eea;
    padding-bottom: 5px;
}

.table-sm {
    font-size: 13px;
}

.badge {
    padding: 8px 15px;
}

pre code {
    font-size: 12px;
}
</style>

<?= $this->include('admin/footer') ?>
