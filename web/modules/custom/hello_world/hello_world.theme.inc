<?php

/**
 * @file
 * For preprocces or other HOOK.
 */

use Drupal\Core\Link;
use Drupal\Core\Url;

/**
 * Implements template_preprocess_HOOK() for hello_world_second.
 *
 * @param $variables
 */
function template_preprocess_hello_world_second(&$variables) {
  $list_type = &$variables['list_type'];
  $allowed_list_types = ['ul', 'ol'];

  if (!in_array($list_type, $allowed_list_types)) {
    $list_type = 'ul';
  }
}

/**
 * Implements template_preprocess_HOOK() for hello_world_quote.
 *
 * @param $variables
 */
function template_preprocess_hello_world_quote(&$variables) {
  $variables['source'] = NULL;
  $source = &$variables['source'];

  if ($variables['source_title'] && $variables['source_url']) {
    $url = Url::fromUri($variables['source_url'], [
      'attributes' => [
        'target' => 'blank',
        'rel' => 'nofollow',
      ],
    ]);
    $source = Link::fromTextAndUrl($variables['source_title'], $url)
      ->toString();
  }
  elseif ($variables['source_title']) {
    $source = $variables['source_tile'];
  }

  $footer = [];
  !$variables['author'] ?: $footer[] = $variables['author'];
  !$variables['year'] ?: $footer[] = $variables['year'];
  !$source ?: $footer[] = $source;
  $variables['footer'] = !empty($footer) ? implode(', ', $footer) : FALSE;
}

/**
 * Implements hook_theme_suggestions_HOOK() for hello_world_quote.
 */
function hello_world_theme_suggestions_hello_world_quote(array $variables) {
  $suggestions = [];
  /** @var \Drupal\Core\Transliteration\PhpTransliteration $transliteration */
  $transliteration = \Drupal::transliteration();

  $author = $variables['author'];
  $source = $variables['source_title'];
  $year = $variables['year'];

  if ($author) {
    $author = $transliteration->transliterate($author);
    $author = strtolower($author);
    $author = str_replace(' ', '_', $author);
  }

  if ($source) {
    $source = $transliteration->transliterate($source);
    $source = strtolower($source);
    $source = str_replace(' ', '_', $source);
  }

  $pattern = 'hello_world_quote__';

  if ($year) {
    $suggestions[] = $pattern . $year;
  }

  if ($source) {
    $suggestions[] = $pattern . $source;
  }

  if ($author) {
    $suggestions[] = $pattern . $author;
  }

  if ($author && $year && $source) {
    $suggestions[] = $pattern . $author . '__' . $year . '__' . $source;
  }

  return $suggestions;
}

/**
 * Implements template_preprocess_HOOK() for contact_message_feedback_form.
 */
function template_preprocess_contact_message_feedback_form(&$variables) {

}
