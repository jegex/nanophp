<!DOCTYPE html>
<html lang="<?= $this->__('lang.code') ?>">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
<?php if (!empty($meta)): foreach ($meta as $k => $v): ?>
  <meta <?= str_starts_with($k, 'og:') ? 'property' : 'name' ?>="<?= e($k) ?>" content="<?= e($v) ?>">
<?php endforeach; endif; ?>
  <title><?= e($page_title) ?> | <?= e($site_name) ?></title>
  <?= $head_append ?? '' ?>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Plus+Jakarta+Sans:wght@600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="<?= $this->asset('css/style.css') ?>">
  <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
  <style>[x-cloak]{display:none!important}</style>
</head>
<body class="min-h-screen">
  <div class="min-h-screen flex flex-col">
    <?php $this->partial('navigation') ?>
    <main class="flex-1">
      <?php $this->section('content') ?>
    </main>
    <?php $this->partial('footer') ?>
  </div>
  <?= $body_append ?? '' ?>
</body>
</html>
