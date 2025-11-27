<?= $this->include('templates/header_pages'); ?>

<?php
$actionTitles = [
    'accept' => 'Quote Accepted!',
    'reject' => 'Response Received',
    'consider' => 'We\'ll Keep in Touch'
];

$actionMessages = [
    'accept' => 'Thank you for accepting our quote! We\'re excited to serve you.',
    'reject' => 'Thank you for considering our services.',
    'consider' => 'We appreciate your interest and look forward to hearing from you.'
];

$actionIcons = [
    'accept' => 'icofont-check-circled color-green',
    'reject' => 'icofont-info-circle color-blue',
    'consider' => 'icofont-clock-time color-yellow'
];

$title = $actionTitles[$action] ?? 'Response Received';
$message = $actionMessages[$action] ?? 'Thank you for your response.';
$icon = $actionIcons[$action] ?? 'icofont-check-circled';
?>

<div class="wrapper image-wrapper bg-image page-title-wrapper inverse-text" data-image-src="<?= base_url('style/images/art/bg3.jpg') ?>">
  <div class="container inner text-center">
    <div class="space90"></div>
    <h1 class="page-title"><?= $title ?></h1>
    <p class="lead"><?= $message ?></p>
  </div>
</div>

<div class="wrapper light-wrapper">
  <div class="container inner">
    <div class="row">
      <div class="col-lg-8 mx-auto text-center">

        <div class="alert alert-success alert-lg" role="alert">
          <div class="icon fs-48 mb-20">
            <i class="<?= $icon ?>"></i>
          </div>

          <?php if ($action === 'accept'): ?>
            <h3 class="mb-15">Wonderful! We're on it!</h3>
            <p class="lead mb-20">Your acceptance has been recorded and our team has been notified.</p>

            <div class="bg-white p-30 rounded shadow-sm mb-30" style="border-left: 4px solid #28a745;">
              <h4 class="mb-15">What Happens Next?</h4>
              <ul class="text-left mb-0" style="max-width: 500px; margin: 0 auto;">
                <li class="mb-10">✓ Our team will contact you within 24 hours to confirm the service date and time</li>
                <li class="mb-10">✓ We'll send you a service confirmation email with all the details</li>
                <li class="mb-10">✓ On the scheduled day, our professional team will arrive promptly</li>
                <li class="mb-10">✓ We'll complete the junk removal efficiently and clean up thoroughly</li>
              </ul>
            </div>

            <p class="mb-20"><strong>Quote ID:</strong> #<?= $quote['id'] ?></p>
            <p class="mb-20">If you have any questions in the meantime, please don't hesitate to reach out!</p>

          <?php elseif ($action === 'consider'): ?>
            <h3 class="mb-15">No Problem - Take Your Time!</h3>
            <p class="lead mb-20">We understand you need time to decide. Your quote will remain valid as specified.</p>

            <div class="bg-white p-30 rounded shadow-sm mb-30" style="border-left: 4px solid #ffc107;">
              <h4 class="mb-15">Your Quote Details</h4>
              <p class="mb-10"><strong>Quote ID:</strong> #<?= $quote['id'] ?></p>
              <p class="mb-10"><strong>Status:</strong> Considering</p>
              <p class="mb-10">You can come back to this quote anytime by clicking the link in your email.</p>
            </div>

            <p class="mb-20">When you're ready to proceed, simply click the "Accept Quote" button in the email we sent you.</p>

          <?php elseif ($action === 'reject'): ?>
            <h3 class="mb-15">Thank You for Considering Us</h3>
            <p class="lead mb-20">We appreciate the opportunity to provide you with a quote.</p>

            <div class="bg-white p-30 rounded shadow-sm mb-30" style="border-left: 4px solid #6c757d;">
              <h4 class="mb-15">We'd Love Your Feedback</h4>
              <p class="mb-10">If you don't mind sharing, we'd appreciate knowing why you declined:</p>
              <ul class="text-left mb-0" style="max-width: 400px; margin: 0 auto;">
                <li>Price concerns?</li>
                <li>Timing not right?</li>
                <li>Found another service?</li>
                <li>Changed plans?</li>
              </ul>
              <p class="mt-15 mb-0">Feel free to email us at <strong>info@garbagetogo.ca</strong></p>
            </div>

            <p class="mb-20">We hope to have the opportunity to serve you in the future!</p>
          <?php endif; ?>
        </div>

        <!-- Contact Information -->
        <div class="row mt-40">
          <div class="col-md-6 mb-20">
            <div class="box bg-white shadow-sm p-30 rounded">
              <div class="icon mb-15"><i class="icofont-phone fs-32 color-purple"></i></div>
              <h5>Call Us</h5>
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

        <div class="space40"></div>
        <a href="<?= base_url('/') ?>" class="btn btn-purple">Back to Home</a>
      </div>
    </div>
  </div>
</div>

<?= $this->include('templates/footer'); ?>
