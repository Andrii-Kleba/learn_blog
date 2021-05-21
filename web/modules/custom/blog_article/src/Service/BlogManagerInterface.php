<?php

namespace Drupal\blog_article\Service;

use Drupal\node\NodeInterface;

/**
 * Blog Manager for blog_articles.
 *
 * @package Drupal\blog_article\Service
 */
interface BlogManagerInterface {

  /**
   * Gets related posts with exact same tags.
   *
   * @param \Drupal\node\NodeInterface $node
   * @param int $limit
   *
   * @return array
   */
  public function getRelatedPostWithExactSameTags(NodeInterface $node, int $limit = 2): array;

  /**
   * Gets related posts with same tags.
   *
   * @param \Drupal\node\NodeInterface $node
   * @param array $exclude_ids
   * @param int $limit
   *
   * @return array
   */
  public function getRelatedPostsWithSameTags(NodeInterface $node, array $exclude_ids = [], int $limit = 2): array;

  /**
   * Gets Random posts.
   *
   * @param int $limit
   * @param array $exclude_ids
   *
   * @return array
   */
  public function getRandomPosts(int $limit = 2, array $exclude_ids = []): array;

  /**
   * Gets related posts.
   *
   * @param \Drupal\node\NodeInterface $node
   * @param int $max
   * @param int $exact_tags
   *
   * @return array
   */
  public function getRelatedPosts(NodeInterface $node, int $max = 4, int $exact_tags = 2): array;

}
