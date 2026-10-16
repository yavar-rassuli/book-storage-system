<!-- Header -->
<?php include("include/header.php"); ?>
<?php require_once __DIR__ . '/include/db.php'; ?>

<div class="seite container flex flex-col items-center mt-10">
  <div class="action--page bg-veryLightGray p-6">
    <h2 class="text-2xl font-bold mb-4">Danke für Ihre Nachricht!</h2>

    <?php if (!empty($_POST['name'])): ?>
      <p><strong>Name:</strong> <?= e($_POST['name']) ?></p>
    <?php endif; ?>

    <?php if (!empty($_POST['email'])): ?>
      <p><strong>E-Mail:</strong> <?= e($_POST['email']) ?></p>
    <?php endif; ?>

    <?php if (!empty($_POST['gossip'])): ?>
      <p><strong>Wie haben Sie von uns erfahren?</strong> <?= e($_POST['gossip']) ?></p>
    <?php endif; ?>

    <?php if (!empty($_POST['message'])): ?>
      <p><strong>Nachricht:</strong> <?= e($_POST['message']) ?></p>
    <?php endif; ?>
  </div>
</div>

<!-- FOOTER -->
<?php include("include/footer.php"); ?>
