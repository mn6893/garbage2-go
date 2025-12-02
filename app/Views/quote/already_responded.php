<?= $this->include('templates/header_pages'); ?>

<?php
$statusMessages = [
    'accepted' => [
        'title' => 'Quote Already Accepted',
        'icon' => 'icofont-check-circled',
        'color' => '#28a745',
        'message' => 'You have already accepted this quote. Our team will contact you shortly to schedule your service.',
        'details' => 'If you have any questions or need to make changes, please contact us directly.'
    ],
    'rejected' => [
        'title' => 'Quote Already Declined',
        'icon' => 'icofont-close-circled',
        'color' => '#dc3545',
        'message' => 'You have already declined this quote.',
        'details' => 'If you\'ve changed your mind or would like a new quote, please contact us or submit a new quote request.'
    ],
    'considering' => [
        'title' => 'Quote Under Consideration',
        'icon' => 'icofont-clock-time',
        'color' => '#ffc107',
        'message' => 'You have already marked this quote as "considering".',
        'details' => 'When you\'re ready to proceed, please contact us or click the accept link in your original email.'
    ],
    'completed' => [
        'title' => 'Service Completed',
        'icon' => 'icofont-check-circled',
        'color' => '#28a745',
        'message' => 'This service has already been completed and paid for.',
        'details' => 'Thank you for choosing GarbageToGo! We hope you were satisfied with our service.'
    ],
    'talk_to_manager' => [
        'title' => 'Callback Requested',
        'icon' => 'icofont-phone',
        'color' => '#2c5aa0',
        'message' => 'You have already requested to speak with a manager.',
        'details' => 'Our team will contact you at the time you specified. If you need immediate assistance, please call us.'
    ]
];

$status = $quote['status'] ?? 'pending';
$info = $statusMessages[$status] ?? [
    'title' => 'Response Already Recorded',
    'icon' => 'icofont-info-circle',
    'color' => '#6c757d',
    'message' => 'A response has already been recorded for this quote.',
    'details' => 'Please contact us if you need to make any changes.'
];

$responseDate = !empty($quote['customer_response_date']) ? date('F j, Y \a\t g:i A', strtotime($quote['customer_response_date'])) : 'N/A';
?>

<div class="wrapper image-wrapper bg-image page-title-wrapper inverse-text" data-image-src="<?= base_url('style/images/art/bg3.jpg') ?>">
  <div class="container inner text-center">
    <div class="space90"></div>
    <h1 class="page-title"><?= $info['title'] ?></h1>
    <p class="lead">Quote #<?= $quote['id'] ?></p>
  </div>
</div>

<div class="wrapper light-wrapper">
  <div class="container inner">
    <div class="row">
      <div class="col-lg-8 mx-auto text-center">

        <div class="bg-white p-40 rounded shadow-sm">
          <div class="icon fs-64 mb-20" style="color: <?= $info['color'] ?>;">
            <i class="<?= $info['icon'] ?>"></i>
          </div>

          <h2 class="mb-20"><?= $info['title'] ?></h2>

          <div class="alert mb-30" style="background-color: <?= $info['color'] ?>15; border-left: 4px solid <?= $info['color'] ?>;">
            <p class="lead mb-0"><?= $info['message'] ?></p>
          </div>

          <div class="bg-light p-30 rounded mb-30">
            <h4 class="mb-15">Your Response Details</h4>
            <p class="mb-10"><strong>Quote Number:</strong> #<?= $quote['id'] ?></p>
            <p class="mb-10"><strong>Current Status:</strong> <span class="badge" style="background-color: <?= $info['color'] ?>; color: white; padding: 5px 15px; border-radius: 20px;"><?= ucfirst($status) ?></span></p>
            <?php if ($responseDate !== 'N/A'): ?>
            <p class="mb-0"><strong>Response Recorded:</strong> <?= $responseDate ?></p>
            <?php endif; ?>
          </div>

          <div class="alert alert-info mb-30">
            <p class="mb-0"><?= $info['details'] ?></p>
          </div>

          <a href="<?= base_url('/') ?>" class="btn btn-purple btn-lg">
            <i class="icofont-home"></i> Back to Home
          </a>
        </div>

        <!-- Contact Information -->
        <div class="row mt-40">
          <div class="col-md-6 mb-20">
            <div class="box bg-white shadow-sm p-30 rounded">
              <div class="icon mb-15"><i class="icofont-phone fs-32 color-purple"></i></div>
              <h5>Need to Make Changes?</h5>
              <p class="mb-0"><strong>+1 (647) 913-8775</strong></p>
              <p class="text-muted mb-0">Mon-Sat: 8AM - 8PM</p>
            </div>
          </div>
          <div class="col-md-6 mb-20">
            <div class="box bg-white shadow-sm p-30 rounded">
              <div class="icon mb-15"><i class="icofont-envelope fs-32 color-purple"></i></div>
              <h5>Email Us</h5>
              <p class="mb-0"><strong>info@garbagetogo.ca</strong></p>
              <p class="text-muted mb-0">We respond within 24 hours</p>
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>
</div>

<?= $this->include('templates/footer'); ?>