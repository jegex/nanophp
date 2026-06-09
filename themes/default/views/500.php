<?php $this->extend('layout') ?>
<?php $this->start('content') ?>
<section class="max-w-7xl mx-auto px-4 py-24 text-center">
  <h1 class="font-heading text-8xl font-bold text-accent-red mb-4">500</h1>
  <p class="text-xl text-text-secondary mb-8"><?= $this->__('500.message') ?? 'Something went wrong on our end.' ?></p>
  <a href="<?= $base_url ?>/" class="btn-cta inline-flex items-center gap-2">
    <?= $this->__('500.go_home') ?? 'Go Home' ?>
  </a>
</section>
<?php $this->end('content') ?>
