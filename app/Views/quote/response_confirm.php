<?= $this->include('templates/header_pages'); ?>

<?php
$actionTitles = [
    'accept' => 'Accept Quote',
    'reject' => 'Decline Quote',
    'consider' => 'Consider Later'
];

$actionMessages = [
    'accept' => 'You are about to accept this quote. Our team will contact you shortly to schedule the service.',
    'reject' => 'You are about to decline this quote. We appreciate you considering our services.',
    'consider' => 'You need more time to decide. We will keep your quote active and follow up with you.'
];

$actionIcons = [
    'accept' => 'icofont-check-circled',
    'reject' => 'icofont-close-circled',
    'consider' => 'icofont-clock-time'
];

$actionColors = [
    'accept' => '#28a745',
    'reject' => '#dc3545',
    'consider' => '#ffc107'
];

$buttonClasses = [
    'accept' => 'btn-success',
    'reject' => 'btn-danger',
    'consider' => 'btn-warning'
];

$title = $actionTitles[$action] ?? 'Confirm Response';
$message = $actionMessages[$action] ?? 'Please confirm your response.';
$icon = $actionIcons[$action] ?? 'icofont-question-circle';
$color = $actionColors[$action] ?? '#6c757d';
$buttonClass = $buttonClasses[$action] ?? 'btn-secondary';
?>

<div class="wrapper image-wrapper bg-image page-title-wrapper inverse-text" data-image-src="<?= base_url('style/images/art/bg3.jpg') ?>">
  <div class="container inner text-center">
    <div class="space90"></div>
    <h1 class="page-title">Confirm Your Decision</h1>
    <p class="lead">Quote #<?= $quote['id'] ?></p>
  </div>
</div>

<div class="wrapper light-wrapper">
  <div class="container inner">
    <div class="row">
      <div class="col-lg-8 mx-auto text-center">

        <div class="bg-white p-40 rounded shadow-sm">
          <div class="icon fs-64 mb-20" style="color: <?= $color ?>;">
            <i class="<?= $icon ?>"></i>
          </div>

          <h2 class="mb-20"><?= $title ?></h2>

          <div class="alert alert-info mb-30" style="border-left: 4px solid <?= $color ?>;">
            <p class="lead mb-0"><?= $message ?></p>
          </div>

          <div class="bg-light p-30 rounded mb-30" style="border-left: 4px solid #2c5aa0;">
            <h4 class="mb-15">Quote Details</h4>
            <p class="mb-10"><strong>Quote Number:</strong> #<?= $quote['id'] ?></p>
            <p class="mb-10"><strong>Name:</strong> <?= htmlspecialchars($quote['name']) ?></p>
            <p class="mb-10"><strong>Address:</strong> <?= htmlspecialchars($quote['address']) ?></p>
            <?php if (!empty($quote['quote_amount'])): ?>
            <p class="mb-0"><strong>Quote Amount:</strong> $<?= number_format($quote['quote_amount'], 2) ?></p>
            <?php endif; ?>
          </div>

          <div class="alert alert-warning mb-30">
            <h5 class="mb-10"><i class="icofont-warning-alt"></i> Are you sure?</h5>
            <p class="mb-0">Please confirm that you want to <strong><?= strtolower($title) ?></strong>.</p>
          </div>

          <form action="<?= base_url('quote/response') ?>" method="POST">
            <input type="hidden" name="encoded_quote_id" value="<?= htmlspecialchars($encodedQuoteId) ?>">
            <input type="hidden" name="action" value="<?= htmlspecialchars($action) ?>">

            <div class="d-flex justify-content-center gap-3">
              <button type="submit" class="btn <?= $buttonClass ?> btn-lg mr-10">
                <i class="<?= $icon ?>"></i> Yes, <?= $title ?>
              </button>
              <a href="<?= base_url('/') ?>" class="btn btn-outline-secondary btn-lg">
                <i class="icofont-arrow-left"></i> Cancel
              </a>
            </div>
          </form>
        </div>

        <!-- Contact Information -->
        <div class="row mt-40">
          <div class="col-md-6 mb-20">
            <div class="box bg-white shadow-sm p-30 rounded">
              <div class="icon mb-15"><i class="icofont-phone fs-32 color-purple"></i></div>
              <h5>Have Questions?</h5>
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