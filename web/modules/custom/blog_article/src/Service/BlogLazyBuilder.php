<?php

namespace Drupal\blog_article\Service;

/**
 * Class BlogLazyBuilder
 *
 * @package Drupal\blog_article\Service
 */
class BlogLazyBuilder implements BlogLazyBuilderInterface {

  /**
   * {@inheritdoc}
   */
  public static function randomBlogPosts(): array {
    return [
      '#theme' => 'blog_article_random_posts',
    ];
  }

  public static function trustedCallbacks() {
    return ['randomBlogPosts'];
  }

}
