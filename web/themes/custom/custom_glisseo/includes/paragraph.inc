<?php

/**
 * @file
 * Preprocesses for paragraphs.
 */

/**
 * Implements hook_preprocess_HOOK() for paragraph-code.html.twig.
 */
function custom_glisseo_preprocess_paragraph__code(array &$variables) {
  $variables['#attached']['library'][] = 'custom_glisseo/paragraph.code';
}
