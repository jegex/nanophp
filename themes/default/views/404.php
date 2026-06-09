<?php $this->extend('layout') ?>
<?php $this->start('content') ?>
<section class="max-w-7xl mx-auto px-4 py-20 text-center">
  <div class="mb-8">
    <span class="text-8xl font-heading font-extrabold text-accent-gold/30">404</span>
  </div>
  <h1 class="font-heading text-3xl font-bold mb-3"><?= $this->__('404.title') ?></h1>
  <p class="text-text-secondary text-lg mb-8 max-w-md mx-auto"><?= $this->__('404.message') ?></p>
  <a href="<?= $this->base_url ?: '/' ?>" class="btn-cta inline-flex items-center gap-2">
    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
    <?= $this->__('404.back_home') ?>
  </a>
</section>
<?php $this->end('content') ?>
