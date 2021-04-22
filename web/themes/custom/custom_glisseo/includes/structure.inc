<?php

/**
 * @file Custom logic for structure.
 */

use Drupal\Core\Template\Attribute;

/**
 * Implements hook_preprocess_HOOK() for page.html.twig.
 */
function blog_article_preprocess_page(array &$variables) {
  $main_layout_attributes_custom = new Attribute();
  $main_layout_attributes_custom->addClass('main-layout');

  // Handle sidebar.
  if (!empty($variables['page']['sidebar_first'])) {
    $main_layout_attributes_custom->addClass('main-layout--sidebar-first');
  }
  else {
    $main_layout_attributes_custom->addClass('main-layout--no-sidebars');
  }

  $variables['main_layout_attributes_custom'] = $main_layout_attributes_custom;
}
