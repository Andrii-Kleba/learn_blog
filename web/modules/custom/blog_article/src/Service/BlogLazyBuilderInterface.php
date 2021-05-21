<?php

namespace Drupal\blog_article\Service;

use Drupal\Core\Security\TrustedCallbackInterface;

/**
 * Interface BlogLazyBuilderInterface
 *
 * @package Drupal\blog_article\Service
 */
interface BlogLazyBuilderInterface extends TrustedCallbackInterface {

  /**
   * Gets random post with theme hook.
   *
   * @return array
   *  Render array with theme hook.
   */
  public static function randomBlogPosts(): array;
}
