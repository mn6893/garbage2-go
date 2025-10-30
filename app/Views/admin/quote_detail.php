<?= $this->include('admin/header'); ?>

<div class="wrapper light-wrapper">
  <div class="container-fluid">
    <div class="row">
      <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-30">
          <h2 class="section-title mb-0">Quote Details #<?= $quote['id'] ?></h2>
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="<?= base_url('admin/dashboard') ?>">Dashboard</a></li>
              <li class="breadcrumb-item"><a href="<?= base_url('admin/quotes') ?>">Quotes</a></li>
              <li class="breadcrumb-item active">Quote #<?= $quote['id'] ?></li>
            </ol>
          </nav>
        </div>
        
        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?= session()->getFlashdata('success') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
        
        <div class="row">
          <!-- Customer Information -->
          <div class="col-md-6">
            <div class="card mb-20">
              <div class="card-header">
                <h5 class="card-title mb-0">Customer Information</h5>
              </div>
              <div class="card-body">
                <div class="row">
                  <div class="col-6">
                    <p><strong>Name:</strong><br><?= esc($quote['name']) ?></p>
                  </div>
                  <div class="col-6">
                    <p><strong>Email:</strong><br>
                      <a href="mailto:<?= esc($quote['email']) ?>"><?= esc($quote['email']) ?></a>
                    </p>
                  </div>
                  <div class="col-6">
                    <p><strong>Phone:</strong><br>
                      <a href="tel:<?= esc($quote['phone']) ?>"><?= esc($quote['phone']) ?></a>
                    </p>
                  </div>
                  <div class="col-6">
                    <p><strong>City:</strong><br><?= esc($quote['city'] ?? 'Not provided') ?></p>
                  </div>
                </div>
              </div>
            </div>
          </div>
          
          <!-- Status & Timeline -->
          <div class="col-md-6">
            <div class="card mb-20">
              <div class="card-header">
                <h5 class="card-title mb-0">Status & Timeline</h5>
              </div>
              <div class="card-body">
                <form method="post" action="<?= base_url('admin/quote/update-status') ?>">
                  <?= csrf_field() ?>
                  <input type="hidden" name="id" value="<?= $quote['id'] ?>">
                  
                  <div class="mb-15">
                    <label class="form-label">Current Status</label>
                    <select name="status" class="form-control">
                      <option value="pending" <?= $quote['status'] === 'pending' ? 'selected' : '' ?>>Pending</option>
                      <option value="ai_queued" <?= $quote['status'] === 'ai_queued' ? 'selected' : '' ?>>AI Queued</option>
                      <option value="ai_processing" <?= $quote['status'] === 'ai_processing' ? 'selected' : '' ?>>AI Processing</option>
                      <option value="ai_quoted" <?= $quote['status'] === 'ai_quoted' ? 'selected' : '' ?>>AI Quoted</option>
                      <option value="ai_error" <?= $quote['status'] === 'ai_error' ? 'selected' : '' ?>>AI Error</option>
                      <option value="contacted" <?= $quote['status'] === 'contacted' ? 'selected' : '' ?>>Contacted</option>
                      <option value="quoted" <?= $quote['status'] === 'quoted' ? 'selected' : '' ?>>Manual Quote</option>
                      <option value="accepted" <?= $quote['status'] === 'accepted' ? 'selected' : '' ?>>Accepted</option>
                      <option value="rejected" <?= $quote['status'] === 'rejected' ? 'selected' : '' ?>>Rejected</option>
                      <option value="completed" <?= $quote['status'] === 'completed' ? 'selected' : '' ?>>Completed</option>
                    </select>
                  </div>
                  
                  <button type="submit" class="btn btn-purple">Update Status</button>
                </form>
                
                <hr>
                
                <p><strong>Submitted:</strong><br>
                  <?= date('F j, Y \a\t g:i A', strtotime($quote['created_at'])) ?>
                </p>
                
                <?php if ($quote['ai_processed_at']): ?>
                <p><strong>AI Processed:</strong><br>
                  <?= date('F j, Y \a\t g:i A', strtotime($quote['ai_processed_at'])) ?>
                </p>
                <?php endif; ?>
                
                <?php if ($quote['updated_at'] && $quote['updated_at'] !== $quote['created_at']): ?>
                <p><strong>Last Updated:</strong><br>
                  <?= date('F j, Y \a\t g:i A', strtotime($quote['updated_at'])) ?>
                </p>
                <?php endif; ?>
              </div>
            </div>
          </div>
        </div>
        
        <!-- Address & Description -->
        <div class="row">
          <div class="col-md-12">
            <div class="card mb-20">
              <div class="card-header">
                <h5 class="card-title mb-0">Service Details</h5>
              </div>
              <div class="card-body">
                <div class="row">
                  <div class="col-md-6">
                    <h6>Address:</h6>
                    <p><?= nl2br(esc($quote['address'])) ?></p>
                  </div>
                  <div class="col-md-6">
                    <h6>Description:</h6>
                    <p><?= nl2br(esc($quote['description'])) ?></p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        
        <!-- AI Analysis Results -->
        <?php if (!empty($quote['ai_analysis']) || !empty($quote['generated_quote'])): ?>
        <div class="row">
          <div class="col-md-12">
            <div class="card mb-20">
              <div class="card-header">
                <h5 class="card-title mb-0">AI Analysis Results</h5>
              </div>
              <div class="card-body">
                <div class="row">
                  
                  <!-- AI Generated Quote -->
                  <?php if (!empty($quote['generated_quote'])): ?>
                    <?php $generatedQuote = json_decode($quote['generated_quote'], true); ?>
                    <div class="col-md-6">
                      <h6>Generated Quote</h6>
                      <?php if (isset($generatedQuote['quote'])): ?>
                        <div class="bg-light p-15 rounded mb-15">
                          <table class="table table-sm mb-0">
                            <tr>
                              <td><strong>Total Estimate:</strong></td>
                              <td class="text-end"><strong class="text-success">$<?= number_format($generatedQuote['quote']['total_amount'] ?? 0, 2) ?></strong></td>
                            </tr>
                            <tr>
                              <td>Base Cost:</td>
                              <td class="text-end">$<?= number_format($generatedQuote['quote']['base_cost'] ?? 0, 2) ?></td>
                            </tr>
                            <?php if (!empty($generatedQuote['quote']['volume_cost'])): ?>
                            <tr>
                              <td>Volume Cost:</td>
                              <td class="text-end">$<?= number_format($generatedQuote['quote']['volume_cost'], 2) ?></td>
                            </tr>
                            <?php endif; ?>
                            <?php if (!empty($generatedQuote['quote']['special_fees'])): ?>
                            <tr>
                              <td>Special Fees:</td>
                              <td class="text-end">$<?= number_format($generatedQuote['quote']['special_fees'], 2) ?></td>
                            </tr>
                            <?php endif; ?>
                            <?php if (!empty($generatedQuote['quote']['taxes'])): ?>
                            <tr>
                              <td>Taxes:</td>
                              <td class="text-end">$<?= number_format($generatedQuote['quote']['taxes'], 2) ?></td>
                            </tr>
                            <?php endif; ?>
                          </table>
                        </div>
                      <?php endif; ?>
                      
                      <?php if (!empty($quote['ai_confidence_score'])): ?>
                        <p><strong>AI Confidence:</strong> 
                          <span class="badge badge-<?= $quote['ai_confidence_score'] >= 0.8 ? 'success' : ($quote['ai_confidence_score'] >= 0.6 ? 'warning' : 'danger') ?>">
                            <?= round($quote['ai_confidence_score'] * 100) ?>%
                          </span>
                        </p>
                      <?php endif; ?>
                      
                      <?php if (!empty($quote['ai_processed_at'])): ?>
                        <p><strong>Processed At:</strong> <?= date('M j, Y g:i A', strtotime($quote['ai_processed_at'])) ?></p>
                      <?php endif; ?>
                    </div>
                  <?php endif; ?>
                  
                  <!-- AI Vision Analysis -->
                  <?php if (!empty($quote['ai_analysis'])): ?>
                    <?php $aiAnalysis = json_decode($quote['ai_analysis'], true); ?>
                    <div class="col-md-6">
                      <h6>Waste Analysis</h6>
                      <?php if (isset($aiAnalysis['wasteType'])): ?>
                        <p><strong>Waste Type:</strong> <?= esc($aiAnalysis['wasteType']) ?></p>
                      <?php endif; ?>
                      
                      <?php if (isset($aiAnalysis['volumeEstimate'])): ?>
                        <p><strong>Volume Estimate:</strong> 
                          <?= $aiAnalysis['volumeEstimate']['min'] ?> - <?= $aiAnalysis['volumeEstimate']['max'] ?> 
                          <?= $aiAnalysis['volumeEstimate']['unit'] ?>
                        </p>
                      <?php endif; ?>
                      
                      <?php if (!empty($aiAnalysis['wasteTypes'])): ?>
                        <p><strong>Detected Items:</strong></p>
                        <ul class="list-unstyled">
                          <?php foreach (array_slice($aiAnalysis['wasteTypes'], 0, 5) as $item): ?>
                            <li>• <?= esc($item) ?></li>
                          <?php endforeach; ?>
                        </ul>
                      <?php endif; ?>
                      
                      <?php if (!empty($aiAnalysis['hazardousItems'])): ?>
                        <p><strong class="text-warning">Hazardous Items Detected:</strong></p>
                        <ul class="list-unstyled text-warning">
                          <?php foreach ($aiAnalysis['hazardousItems'] as $item): ?>
                            <li>⚠️ <?= esc($item) ?></li>
                          <?php endforeach; ?>
                        </ul>
                      <?php endif; ?>
                    </div>
                  <?php endif; ?>
                  
                </div>
                
                <!-- Detailed OpenAI Analysis -->
                <?php if (!empty($quote['ai_analysis']) || !empty($quote['waste_assessment'])): ?>
                <hr>
                <div class="row">
                  <div class="col-12">
                    <h6>Detailed AI Analysis</h6>
                    
                    <!-- AI Analysis Details -->
                    <?php if (!empty($quote['ai_analysis'])): ?>
                      <?php $aiAnalysis = json_decode($quote['ai_analysis'], true); ?>
                      <div class="card border-left-info mb-15">
                        <div class="card-header bg-light">
                          <h6 class="mb-0">OpenAI Vision Analysis</h6>
                        </div>
                        <div class="card-body">
                          <div class="row">
                            <?php if (isset($aiAnalysis['analysis'])): ?>
                              <div class="col-md-6">
                                <strong>Waste Classification:</strong>
                                <ul class="list-unstyled mt-5">
                                  <li><strong>Primary Type:</strong> <?= esc($aiAnalysis['analysis']['primaryWasteType'] ?? 'N/A') ?></li>
                                  <li><strong>Category:</strong> <?= esc($aiAnalysis['analysis']['volumeEstimate']['category'] ?? 'N/A') ?></li>
                                  <li><strong>Estimated Volume:</strong> <?= esc($aiAnalysis['analysis']['volumeEstimate']['description'] ?? 'N/A') ?></li>
                                  <li><strong>Estimated Bags:</strong> <?= esc($aiAnalysis['analysis']['volumeEstimate']['bags'] ?? 'N/A') ?></li>
                                  <li><strong>Cubic Yards:</strong> <?= esc($aiAnalysis['analysis']['volumeEstimate']['cubicYards'] ?? 'N/A') ?></li>
                                </ul>
                              </div>
                              <div class="col-md-6">
                                <strong>Safety & Compliance:</strong>
                                <ul class="list-unstyled mt-5">
                                  <?php if (!empty($aiAnalysis['analysis']['hazardousItems'])): ?>
                                    <li><strong class="text-danger">Hazardous Items:</strong></li>
                                    <?php foreach ($aiAnalysis['analysis']['hazardousItems'] as $item): ?>
                                      <li class="text-danger ml-15">⚠️ <?= esc($item) ?></li>
                                    <?php endforeach; ?>
                                  <?php endif; ?>
                                  
                                  <?php if (!empty($aiAnalysis['analysis']['recyclableItems'])): ?>
                                    <li><strong class="text-success">Recyclable Items:</strong></li>
                                    <?php foreach (array_slice($aiAnalysis['analysis']['recyclableItems'], 0, 3) as $item): ?>
                                      <li class="text-success ml-15">♻️ <?= esc($item) ?></li>
                                    <?php endforeach; ?>
                                    <?php if (count($aiAnalysis['analysis']['recyclableItems']) > 3): ?>
                                      <li class="text-muted ml-15">... and <?= count($aiAnalysis['analysis']['recyclableItems']) - 3 ?> more</li>
                                    <?php endif; ?>
                                  <?php endif; ?>
                                </ul>
                              </div>
                            <?php endif; ?>
                          </div>
                          
                          <?php if (isset($aiAnalysis['processingTime'])): ?>
                            <small class="text-muted">Processing Time: <?= round($aiAnalysis['processingTime'], 2) ?>s</small>
                          <?php endif; ?>
                        </div>
                      </div>
                    <?php endif; ?>
                    
                    <!-- Waste Assessment Details -->
                    <?php if (!empty($quote['waste_assessment'])): ?>
                      <?php $wasteAssessment = json_decode($quote['waste_assessment'], true); ?>
                      <div class="card border-left-warning mb-15">
                        <div class="card-header bg-light">
                          <h6 class="mb-0">Waste Assessment & Business Rules</h6>
                        </div>
                        <div class="card-body">
                          <div class="row">
                            <div class="col-md-6">
                              <strong>Assessment Results:</strong>
                              <ul class="list-unstyled mt-5">
                                <li><strong>Final Waste Type:</strong> <?= esc($wasteAssessment['wasteType'] ?? 'N/A') ?></li>
                                <li><strong>Category:</strong> <?= esc($wasteAssessment['wasteCategory'] ?? 'N/A') ?></li>
                                <?php if (!empty($wasteAssessment['wasteSubtypes'])): ?>
                                  <li><strong>Subtypes:</strong> <?= esc(implode(', ', array_slice($wasteAssessment['wasteSubtypes'], 0, 3))) ?></li>
                                <?php endif; ?>
                                <li><strong>Final Confidence:</strong> <?= esc($wasteAssessment['confidence'] ?? 'N/A') ?>%</li>
                              </ul>
                            </div>
                            <div class="col-md-6">
                              <strong>Compliance Check:</strong>
                              <?php if (!empty($wasteAssessment['complianceCheck'])): ?>
                                <ul class="list-unstyled mt-5">
                                  <li><strong>Status:</strong> 
                                    <span class="badge badge-<?= $wasteAssessment['complianceCheck']['status'] === 'compliant' ? 'success' : 'danger' ?>">
                                      <?= ucfirst($wasteAssessment['complianceCheck']['status'] ?? 'unknown') ?>
                                    </span>
                                  </li>
                                  <?php if (!empty($wasteAssessment['complianceCheck']['specialRequirements'])): ?>
                                    <li><strong>Special Requirements:</strong></li>
                                    <?php foreach ($wasteAssessment['complianceCheck']['specialRequirements'] as $req): ?>
                                      <li class="ml-15">• <?= esc($req) ?></li>
                                    <?php endforeach; ?>
                                  <?php endif; ?>
                                </ul>
                              <?php endif; ?>
                            </div>
                          </div>
                        </div>
                      </div>
                    <?php endif; ?>
                    
                    <!-- Generated Quote Breakdown -->
                    <?php if (!empty($quote['generated_quote'])): ?>
                      <?php $generatedQuote = json_decode($quote['generated_quote'], true); ?>
                      <?php if (isset($generatedQuote['breakdown'])): ?>
                        <div class="card border-left-success">
                          <div class="card-header bg-light">
                            <h6 class="mb-0">Quote Breakdown Details</h6>
                          </div>
                          <div class="card-body">
                            <div class="row">
                              <div class="col-md-6">
                                <strong>Cost Components:</strong>
                                <table class="table table-sm mt-10">
                                  <tr><td>Base Cost:</td><td class="text-end">$<?= number_format($generatedQuote['breakdown']['baseCost'] ?? 0, 2) ?></td></tr>
                                  <tr><td>Volume Cost:</td><td class="text-end">$<?= number_format($generatedQuote['breakdown']['volumeCost'] ?? 0, 2) ?></td></tr>
                                  <tr><td>Special Fees:</td><td class="text-end">$<?= number_format($generatedQuote['breakdown']['specialFees'] ?? 0, 2) ?></td></tr>
                                  <tr><td>Environmental Fee:</td><td class="text-end">$<?= number_format($generatedQuote['breakdown']['environmentalFee'] ?? 0, 2) ?></td></tr>
                                  <tr><td>Disposal Fee:</td><td class="text-end">$<?= number_format($generatedQuote['breakdown']['disposalFee'] ?? 0, 2) ?></td></tr>
                                  <tr><td>Subtotal:</td><td class="text-end">$<?= number_format($generatedQuote['breakdown']['subtotal'] ?? 0, 2) ?></td></tr>
                                  <tr><td>GST:</td><td class="text-end">$<?= number_format($generatedQuote['breakdown']['gst'] ?? 0, 2) ?></td></tr>
                                  <tr><td>PST:</td><td class="text-end">$<?= number_format($generatedQuote['breakdown']['pst'] ?? 0, 2) ?></td></tr>
                                  <tr class="font-weight-bold"><td>Total:</td><td class="text-end text-success">$<?= number_format($generatedQuote['breakdown']['total'] ?? 0, 2) ?></td></tr>
                                </table>
                              </div>
                              <div class="col-md-6">
                                <strong>Quote Details:</strong>
                                <ul class="list-unstyled mt-10">
                                  <?php if (isset($generatedQuote['details'])): ?>
                                    <li><strong>Service Type:</strong> <?= esc($generatedQuote['details']['serviceType'] ?? 'N/A') ?></li>
                                    <li><strong>Location:</strong> <?= esc($generatedQuote['details']['location'] ?? 'N/A') ?></li>
                                    <li><strong>Valid Until:</strong> <?= esc($generatedQuote['details']['validUntil'] ?? 'N/A') ?></li>
                                    <li><strong>Quote ID:</strong> <?= esc($generatedQuote['quoteId'] ?? 'N/A') ?></li>
                                  <?php endif; ?>
                                </ul>
                                
                                <?php if (isset($generatedQuote['estimatedCost'])): ?>
                                  <strong>Price Range:</strong>
                                  <div class="bg-light p-10 rounded mt-5">
                                    <small>Min: $<?= number_format($generatedQuote['estimatedCost']['min'] ?? 0, 2) ?></small><br>
                                    <small>Max: $<?= number_format($generatedQuote['estimatedCost']['max'] ?? 0, 2) ?></small>
                                  </div>
                                <?php endif; ?>
                              </div>
                            </div>
                          </div>
                        </div>
                      <?php endif; ?>
                    <?php endif; ?>
                  </div>
                </div>
                <?php endif; ?>
                <!-- AI Recommendations -->
                <?php if (!empty($generatedQuote['recommendations'])): ?>
                <hr>
                <div class="row">
                  <div class="col-12">
                    <h6>AI Recommendations</h6>
                    <div class="bg-info-light p-15 rounded">
                      <?php foreach ($generatedQuote['recommendations'] as $recommendation): ?>
                        <p class="mb-5">• <?= esc($recommendation) ?></p>
                      <?php endforeach; ?>
                    </div>
                  </div>
                </div>
                <?php endif; ?>
                
              </div>
            </div>
          </div>
        </div>
        <?php endif; ?>
        
        <!-- Email Status Section -->
        <?php if ($quote['status'] === 'ai_quoted' || !empty($quote['generated_quote'])): ?>
        <div class="row">
          <div class="col-md-12">
            <div class="card mb-20">
              <div class="card-header">
                <h5 class="card-title mb-0">Email Delivery Status</h5>
              </div>
              <div class="card-body">
                <div class="row">
                  <div class="col-md-6">
                    <h6>Customer Email</h6>
                    <div class="d-flex align-items-center mb-10">
                      <?php if ($quote['email_sent_to_customer']): ?>
                        <span class="badge bg-success me-2">✓ Sent Successfully</span>
                      <?php else: ?>
                        <span class="badge bg-danger me-2">✗ Not Sent</span>
                      <?php endif; ?>
                      <small class="text-muted">
                        Attempts: <?= $quote['customer_email_attempts'] ?? 0 ?>
                      </small>
                    </div>
                    
                    <?php if (!empty($quote['customer_email_error'])): ?>
                      <div class="alert alert-danger alert-sm">
                        <strong>Last Error:</strong> <?= esc($quote['customer_email_error']) ?>
                      </div>
                    <?php endif; ?>
                    
                    <?php if (!$quote['email_sent_to_customer'] && ($quote['customer_email_attempts'] ?? 0) < 3): ?>
                      <form method="post" action="<?= base_url('admin/retry-quote-emails/' . $quote['id']) ?>" class="d-inline">
                        <input type="hidden" name="email_type" value="customer">
                        <button type="submit" class="btn btn-sm btn-warning">Retry Customer Email</button>
                      </form>
                    <?php endif; ?>
                  </div>
                  
                  <div class="col-md-6">
                    <h6>Admin Notification</h6>
                    <div class="d-flex align-items-center mb-10">
                      <?php if ($quote['email_sent_to_admin']): ?>
                        <span class="badge bg-success me-2">✓ Sent Successfully</span>
                      <?php else: ?>
                        <span class="badge bg-danger me-2">✗ Not Sent</span>
                      <?php endif; ?>
                      <small class="text-muted">
                        Attempts: <?= $quote['admin_email_attempts'] ?? 0 ?>
                      </small>
                    </div>
                    
                    <?php if (!empty($quote['admin_email_error'])): ?>
                      <div class="alert alert-danger alert-sm">
                        <strong>Last Error:</strong> <?= esc($quote['admin_email_error']) ?>
                      </div>
                    <?php endif; ?>
                    
                    <?php if (!$quote['email_sent_to_admin'] && ($quote['admin_email_attempts'] ?? 0) < 3): ?>
                      <form method="post" action="<?= base_url('admin/retry-quote-emails/' . $quote['id']) ?>" class="d-inline">
                        <input type="hidden" name="email_type" value="admin">
                        <button type="submit" class="btn btn-sm btn-warning">Retry Admin Email</button>
                      </form>
                    <?php endif; ?>
                  </div>
                </div>
                
                <?php if (!empty($quote['last_email_attempt'])): ?>
                  <hr>
                  <small class="text-muted">
                    Last Email Attempt: <?= date('F j, Y \a\t g:i A', strtotime($quote['last_email_attempt'])) ?>
                  </small>
                <?php endif; ?>
                
                <?php if ((!$quote['email_sent_to_customer'] || !$quote['email_sent_to_admin']) && 
                          (($quote['customer_email_attempts'] ?? 0) < 3 || ($quote['admin_email_attempts'] ?? 0) < 3)): ?>
                  <hr>
                  <form method="post" action="<?= base_url('admin/retry-quote-emails/' . $quote['id']) ?>" class="d-inline">
                    <input type="hidden" name="email_type" value="both">
                    <button type="submit" class="btn btn-sm btn-primary">Retry Both Emails</button>
                  </form>
                <?php endif; ?>
              </div>
            </div>
          </div>
        </div>
        <?php endif; ?>
        
        <!-- Images -->
        <div class="row">
          <div class="col-md-12">
            <div class="card mb-20">
              <div class="card-header">
                <h5 class="card-title mb-0">Quote Images</h5>
              </div>
              <div class="card-body">
                <?php if (!empty($quote['images'])): ?>
                <div class="row mb-20">
                  <?php
                  $images = json_decode($quote['images'], true);
                  if (is_array($images)):
                    foreach ($images as $index => $image):
                  ?>
                    <div class="col-md-3 col-sm-6 mb-15">
                      <div class="image-container">
                        <img src="<?= base_url('admin/quote/image/' . $quote['id'] . '/' . $index) ?>"
                             alt="Quote Image" class="img-fluid rounded"
                             style="width: 100%; height: 200px; object-fit: cover; cursor: pointer;"
                             onclick="openImageModal('<?= base_url('admin/quote/image/' . $quote['id'] . '/' . $index) ?>')">
                        <div class="mt-10 text-center">
                          <button type="button" class="btn btn-sm btn-outline-primary me-1"
                                  onclick="openImageModal('<?= base_url('admin/quote/image/' . $quote['id'] . '/' . $index) ?>')">
                            <i class="icofont-eye"></i> View
                          </button>
                          <a href="<?= base_url('admin/quote/download/' . $quote['id'] . '/' . $index) ?>"
                             class="btn btn-sm btn-outline-success" target="_blank">
                            <i class="icofont-download"></i> Download
                          </a>
                        </div>
                        <div class="mt-5 text-center">
                          <small class="text-muted"><?= esc($image) ?></small>
                        </div>
                      </div>
                    </div>
                  <?php
                    endforeach;
                  endif;
                  ?>
                </div>
                <hr>
                <?php else: ?>
                <div class="alert alert-info">
                  <i class="icofont-info-circle"></i> No images uploaded yet. Use the form below to add images for AI analysis.
                </div>
                <?php endif; ?>

                <!-- Upload New Images Section -->
                <div class="upload-section">
                  <h6 class="mb-15">Upload Additional Images for AI Processing</h6>
                  <form id="uploadImageForm" method="post" action="<?= base_url('admin/quote/' . $quote['id'] . '/upload-images') ?>" enctype="multipart/form-data">
                    <?= csrf_field() ?>
                    <div class="row">
                      <div class="col-md-8">
                        <div class="mb-15">
                          <label class="form-label">Select Images (Max 5MB each, JPG/PNG)</label>
                          <input type="file" name="additional_images[]" id="additional_images"
                                 class="form-control" multiple accept="image/jpeg,image/jpg,image/png" required>
                          <small class="text-muted">You can select multiple images at once</small>
                        </div>
                      </div>
                      <div class="col-md-4">
                        <label class="form-label">&nbsp;</label>
                        <div class="d-grid gap-2">
                          <button type="submit" class="btn btn-info" id="uploadBtn">
                            <i class="icofont-upload-alt"></i> Upload Images
                          </button>
                        </div>
                      </div>
                    </div>
                    <div class="form-check mb-15">
                      <input class="form-check-input" type="checkbox" name="process_with_ai" id="process_with_ai" value="1" checked>
                      <label class="form-check-label" for="process_with_ai">
                        <strong>Process with AI immediately after upload</strong>
                        <br><small class="text-muted">Automatically analyze images and generate/update quote</small>
                      </label>
                    </div>
                  </form>

                  <!-- Image Preview Section -->
                  <div id="imagePreviewSection" class="mt-20" style="display: none;">
                    <h6>Selected Images Preview:</h6>
                    <div id="imagePreviewContainer" class="row"></div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        
                <!-- Action Buttons -->
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-body text-center">
                <a href="<?= base_url('admin/quotes') ?>" class="btn btn-outline-default">
                  <i class="icofont-arrow-left mr-5"></i> Back to Quotes
                </a>
                
                <?php if (!empty($quote['images'])): ?>
                <form method="post" action="<?= base_url('admin/quote/' . $quote['id'] . '/process-ai') ?>" class="d-inline">
                  <?= csrf_field() ?>
                  <button type="submit" class="btn btn-info" onclick="return confirm('<?= !empty($quote['ai_processed_at']) ? 'This quote has already been processed. Reprocess with AI?' : 'Trigger AI processing for this quote?' ?>')">
                    <i class="icofont-robot mr-5"></i> <?= !empty($quote['ai_processed_at']) ? 'Reprocess with AI' : 'Process with AI' ?>
                  </button>
                </form>
                <?php endif; ?>
                
                <a href="mailto:<?= esc($quote['email']) ?>?subject=Quote Request #<?= $quote['id'] ?>&body=Dear <?= esc($quote['name']) ?>,%0D%0A%0D%0AThank you for your quote request.%0D%0A%0D%0ABest regards,%0D%0AJunk Collection Team" 
                   class="btn btn-purple">
                  <i class="icofont-envelope mr-5"></i> Send Email
                </a>
                <a href="tel:<?= esc($quote['phone']) ?>" class="btn btn-green">
                  <i class="icofont-phone mr-5"></i> Call Customer
                </a>
              </div>
            </div>
          </div>
        </div>      </div>
    </div>
  </div>
</div>

<!-- Image Modal -->
<div class="modal fade" id="imageModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Image Preview - <span id="modalImageName">Quote Image</span></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body text-center">
        <img id="modalImage" src="" alt="Full Size Image" class="img-fluid rounded shadow">
        <div class="mt-15">
          <a id="modalDownloadBtn" href="" class="btn btn-success" target="_blank">
            <i class="icofont-download"></i> Download Image
          </a>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
function openImageModal(imageSrc) {
    document.getElementById('modalImage').src = imageSrc;

    // Extract image info from the URL for better modal title
    const urlParts = imageSrc.split('/');
    const quoteId = urlParts[urlParts.length - 2];
    const imageIndex = urlParts[urlParts.length - 1];

    document.getElementById('modalImageName').textContent = `Quote #${quoteId} - Image ${parseInt(imageIndex) + 1}`;

    // Set download button
    const downloadUrl = imageSrc.replace('/image/', '/download/');
    document.getElementById('modalDownloadBtn').href = downloadUrl;

    new bootstrap.Modal(document.getElementById('imageModal')).show();
}

// Image preview before upload
document.addEventListener('DOMContentLoaded', function() {
    const imageInput = document.getElementById('additional_images');
    const previewSection = document.getElementById('imagePreviewSection');
    const previewContainer = document.getElementById('imagePreviewContainer');
    const uploadForm = document.getElementById('uploadImageForm');
    const uploadBtn = document.getElementById('uploadBtn');

    if (imageInput) {
        imageInput.addEventListener('change', function(e) {
            const files = e.target.files;
            previewContainer.innerHTML = '';

            if (files.length > 0) {
                previewSection.style.display = 'block';

                Array.from(files).forEach((file, index) => {
                    if (file.type.match('image.*')) {
                        const reader = new FileReader();

                        reader.onload = function(e) {
                            const col = document.createElement('div');
                            col.className = 'col-md-3 col-sm-6 mb-15';
                            col.innerHTML = `
                                <div class="card">
                                    <img src="${e.target.result}" class="card-img-top" style="height: 150px; object-fit: cover;">
                                    <div class="card-body p-2">
                                        <small class="text-muted">${file.name}</small>
                                        <br><small class="text-muted">${(file.size / 1024 / 1024).toFixed(2)} MB</small>
                                    </div>
                                </div>
                            `;
                            previewContainer.appendChild(col);
                        };

                        reader.readAsDataURL(file);
                    }
                });
            } else {
                previewSection.style.display = 'none';
            }
        });
    }

    // Handle form submission with loading state
    if (uploadForm) {
        uploadForm.addEventListener('submit', function(e) {
            uploadBtn.disabled = true;
            uploadBtn.innerHTML = '<i class="icofont-spinner-alt-1 icofont-rotate"></i> Uploading...';
        });
    }
});
</script>

<?= $this->include('admin/footer'); ?>