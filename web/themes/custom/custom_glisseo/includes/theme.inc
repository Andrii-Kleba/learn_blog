<?php

/**
 * @file Custom logic for theme.
 */

use Drupal\node\NodeInterface;

/**
 * Implements hook_preprocess_HOOK() for blog entry.
 */
function custom_glisseo_preprocess_blog_previous_next__blog_entry(array &$variables) {
  /** @var \Drupal\node\NodeInterface $ */
  $next_entity = !empty($variables['next']) ? $variables['next']['entity'] : NULL;
  /** @var \Drupal\node\NodeInterface $previous_entity */
  $previous_entity = !empty($variables['previous']) ? $variables['previous']['entity'] : NULL;

  if ($next_entity) {
    /** @var \Drupal\media\MediaInterface $promo_media */
    $promo_media = $next_entity->get('field_promo_image')->entity;
    $variables['next']['promo_uri'] = $promo_media->get('field_media_image')->entity->getFileUri();

    $tags = [];
    /** @var \Drupal\taxonomy\TermInterface $tag_term */
    $tag_terms = $next_entity
      ->get('field_blog_entry_tags')
      ->referencedEntities();
    foreach ($tag_terms as $tag_term) {
      $tags[] = [
        'label' => $tag_term->label(),
      ];
    }

    $variables['next']['tags'] = $tags;
  }

  if ($previous_entity) {
    /** @var \Drupal\media\MediaInterface $promo_media */
    $promo_media = $previous_entity->get('field_promo_image')->entity;
    $variables['previous']['promo_uri'] = $promo_media->get('field_media_image')->entity->getFileUri();

    $tags = [];
    /** @var \Drupal\taxonomy\TermInterface $tag_term */
    $tag_terms = $previous_entity
      ->get('field_blog_entry_tags')
      ->referencedEntities();
    foreach ($tag_terms as $tag_term) {
      $tags[] = [
        'label' => $tag_term->label(),
      ];
    }

    $variables['previous']['tags'] = $tags;
  }
}

/**
 * Implements hook_preprocess_HOOK() for blog-hero--blog-node-blog-article.
 */
function custom_glisseo_preprocess_blog_hero__blog_node_blog_article(&$variables) {
  $tags = [];
  /** @var NodeInterface $node */
  $node = \Drupal::routeMatch()->getParameter('node');
  if ($node instanceof NodeInterface) {
    $tag_terms = $node
      ->get('field_blog_entry_tags')
      ->referencedEntities();
    foreach ($tag_terms as $tag_term) {
      $tags[] = [
        'label' => $tag_term->label(),
        'path' => $tag_term->toUrl()->toString(),
      ];
    }
  }

  $variables['tags'] = $tags;
}
