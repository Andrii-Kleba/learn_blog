<?php

namespace Drupal\blog_article\Service;

use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Entity\EntityViewBuilderInterface;
use Drupal\node\NodeInterface;

/**
 * Blog Manager for blog_articles.
 *
 * @package Drupal\blog_article\Service
 */
class BlogManager implements BlogManagerInterface {

  /**
   * The entity type manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected EntityTypeManagerInterface $entityTypeManager;

  /**
   * The node storage.
   *
   * @var \Drupal\Core\Entity\EntityStorageInterface
   */
  protected EntityStorageInterface $nodeStorage;

  /**
   * The node view builder.
   *
   * @var \Drupal\Core\Entity\EntityViewBuilderInterface
   */
  protected EntityViewBuilderInterface $nodeViewBuilder;

  /**
   * BlogManager constructor.
   *
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   */
  public function __construct(EntityTypeManagerInterface $entity_type_manager) {
    $this->entityTypeManager = $entity_type_manager;
    $this->nodeStorage = $entity_type_manager->getStorage('node');
    $this->nodeViewBuilder = $entity_type_manager->getViewBuilder('node');
  }

  /**
   * {@inheritdoc}
   */
  public function getRelatedPostWithExactSameTags(NodeInterface $node, int $limit = 2): array {
    $result = &drupal_static($this::class . __METHOD__ . $node->id() . $limit, []);
    if (!isset($result)) {
      if ($node->hasField('field_blog_entry_tags') && !$node->get('field_blog_entry_tags')
          ->isEmpty()) {
        $query = $this->nodeStorage->getQuery()
          ->condition('status', NodeInterface::PUBLISHED)
          ->condition('type', 'blog_entry')
          ->condition('nid', $node->id(), '<>')
          ->range(0, $limit);
        $query->addTag('entity_query_random');

        foreach ($node->get('field_tags')->getValue() as $field_tag) {
          $and = $query->andConditionGroup();
          $and->condition('field_tags', $field_tag['target_id']);
          $query->condition($and);
        }

        $result = $query->execute();
      }
      else {
        $result = [];
      }
    }

    return $result;
  }

  /**
   * {@inheritdoc}
   */
  public function getRelatedPostsWithSameTags(NodeInterface $node, array $exclude_ids = [], int $limit = 2): array {
    $result = &drupal_static($this::class . __METHOD__ . $node->id() . $limit, []);

    if (!isset($result)) {
      if ($node->hasField('field_blog_entry_tags') && !$node->get('field_blog_entry_tags')
          ->isEmpty()) {
        $field_tags_ids = [];
        foreach ($node->get('field_blog_entry_tags')
                   ->getValue() as $field_tag) {
          $field_tags_ids[] = $field_tag['target_id'];
        }

        $query = $this->nodeStorage->getQuery()
          ->condition('status', NodeInterface::PUBLISHED)
          ->condition('type', 'blog_entry')
          ->condition('nid', $node->id(), '<>')
          ->condition('field_blog_entry_tags', $field_tags_ids, 'IN')
          ->range(0, $limit);

        if (!empty($exclude_ids)) {
          $query->condition('nid', $exclude_ids, 'NOT IN');
        }

        $query->addTag('entity_query_random');

        $result = $query->execute();
      }
      else {
        $result = [];
      }
    }

    return $result;

  }

  /**
   * {@inheritdoc}
   */
  public function getRandomPosts(int $limit = 2, array $exclude_ids = []): array {
    $query = $this->nodeStorage->getQuery()
      ->condition('status', NodeInterface::PUBLISHED)
      ->condition('type', 'blog_article')
      ->range(0, $limit);

    if (!empty($exclude_ids)) {
      $query->condition('nid', $exclude_ids, 'NOT IN');
    }

    $query->addTag('entity_query_random');

    return $query->execute();
  }

  /**
   * {@inheritdoc}
   */
  public function getRelatedPosts(NodeInterface $node, int $max = 4, int $exact_tags = 2): array {
    $result = &drupal_static($this::class . __METHOD__ . $node->id() . $max . $exact_tags, []);
    if (!isset($result)) {
      if ($exact_tags > $max) {
        $exact_tags = $max;
      }
      $exclude_ids = [
        $node->id(),
      ];

      $counter = 0;
      $result = [];
      if ($exact_tags > 0) {
        $exact_same = $this->getRelatedPostWithExactSameTags($node, $exact_tags);
        $result += $exact_same;
        $counter += count($exact_same);

        $exclude_ids += $exact_same;
      }

      if ($counter < $max) {
        $same_tags = $this->getRelatedPostsWithSameTags($node, $exclude_ids, ($max - $counter));
        $result += $same_tags;
        $counter += count($same_tags);

        $exclude_ids += $same_tags;
      }

      if ($counter < $max) {
        $random = $this->getRandomPosts(($max - $counter), $exclude_ids);
        $result += $random;
      }
    }

    return $result;

  }

}
