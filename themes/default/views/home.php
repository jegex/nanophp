<?php $this->extend('layout') ?>
<?php $this->start('content') ?>
<section class="max-w-7xl mx-auto px-4 py-20 text-center">
  <div class="mb-6">
    <span class="text-7xl font-heading font-extrabold text-accent-gold/20">NanoPHP</span>
  </div>
  <h1 class="font-heading text-4xl font-bold mb-4">Welcome to <span class="text-accent-gold">NanoPHP</span> Framework</h1>
  <p class="text-text-secondary text-lg mb-8 max-w-2xl mx-auto">A lightweight, zero-dependency PHP MVC framework with plugin system, theme engine, and multi-language support.</p>
  <div class="grid grid-cols-1 md:grid-cols-3 gap-6 max-w-3xl mx-auto mb-12">
    <div class="bg-bg-secondary border border-gray-800 rounded-xl p-6 text-left">
      <div class="text-3xl mb-3 text-accent-gold">&#9881;</div>
      <h3 class="font-heading font-semibold text-text-primary mb-2">Plugin System</h3>
      <p class="text-sm text-text-secondary">Hook-driven architecture. Create self-contained plugins for any domain.</p>
    </div>
    <div class="bg-bg-secondary border border-gray-800 rounded-xl p-6 text-left">
      <div class="text-3xl mb-3 text-accent-gold">&#127912;</div>
      <h3 class="font-heading font-semibold text-text-primary mb-2">Theme Engine</h3>
      <p class="text-sm text-text-secondary">Layout inheritance, sections, partials, and multi-language support built in.</p>
    </div>
    <div class="bg-bg-secondary border border-gray-800 rounded-xl p-6 text-left">
      <div class="text-3xl mb-3 text-accent-gold">&#9889;</div>
      <h3 class="font-heading font-semibold text-text-primary mb-2">Zero Dependencies</h3>
      <p class="text-sm text-text-secondary">No Composer required. PHP 8.1+, custom PSR-4 autoloader. Upload and run.</p>
    </div>
  </div>
  <p class="text-text-secondary text-sm">Build something great. &#127775;</p>
</section>
<?php $this->end('content') ?>
