<?php

/**
 * @file
 * Main file for preprocessing custom theme hooks.
 */

use Drupal\blog_article\Service\BlogManagerInterface;
use Drupal\node\NodeInterface;
use Drupal\node\NodeStorageInterface;
use Drupal\node\NodeViewBuilder;

/**
 * Implements HOOK theme preprocess.
 */
function template_preprocess_blog_article_related_posts(array &$variables) {
  $items = [];

  /** @var \Drupal\node\NodeInterface $node */
  $node = \Drupal::routeMatch()->getParameter('node');
  if ($node instanceof NodeInterface) {
    /** @var NodeStorageInterface $node_storage */
    $node_storage = \Drupal::entityTypeManager()->getStorage('node');
    /** @var NodeViewBuilder $node_view_builder */
    $node_view_builder = \Drupal::entityTypeManager()->getViewBuilder('node');
    /** @var BlogManagerInterface $blog_manager */
    $blog_manager = \Drupal::service('blog_article.manager');

    $related_post_ids = $blog_manager->getRelatedPosts($node, $variables['max'], $variables['max_exact_same_tags']);

    foreach ($related_post_ids as $id) {
      /** @var NodeInterface $related_post */
      $related_post = $node_storage->load($id);
      $items[] = $node_view_builder->view($related_post, 'teaser');
    }
  }

  $variables['items'] = $items;
}

/**
 * Implements hook_preprocess_HOOK() for blog-article-random-posts.html.twig.
 */
function template_preprocess_blog_article_random_posts(array &$variables) {
  $items = [];

  /** @var NodeInterface $node */
  $node = \Drupal::routeMatch()->getParameter('node');
  if ($node instanceof NodeInterface) {
    /** @var NodeStorageInterface $node_storage */
    $node_storage = \Drupal::entityTypeManager()->getStorage('node');
    /** @var NodeViewBuilder $node_view_builder */
    $node_view_builder = \Drupal::entityTypeManager()->getViewBuilder('node');
    /** @var BlogManagerInterface $blog_manager */
    $blog_manager = \Drupal::service('blog_article.manager');

    $random_post_ids = $blog_manager->getRandomPosts(4, [$node->id()]);

    foreach ($random_post_ids as $id) {
      /** @var NodeInterface $related_post */
      $related_post = $node_storage->load($id);
      $items[] = $node_view_builder->view($related_post, 'teaser');
    }
  }

  $variables['items'] = $items;
}

