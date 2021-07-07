<?php

namespace Drupal\sports\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Database\Connection;
use Symfony\Component\DependencyInjection\ContainerInterface;

class SportsController extends ControllerBase {

  /**
   * @var \Drupal\Core\Database\Connection
   */
  protected Connection $connection;

  /**
   * SportsController constructor.
   *
   * @param \Drupal\Core\Database\Connection $connection
   */
  public function __construct(Connection $connection) {
    $this->connection = $connection;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('database')
    );
  }

  /**
   * Return all players.
   *
   * @return array
   */
  public function players(): array {

//    $database = $this->connection->insert('player');
//    $fields = [
//      'name' => 'Diego M.',
//      'data' => serialize(['known for' => 'Hand of God']),
//    ];
//    $id = $database->fields($fields)->execute();

    $limit = 5; // The number of item per page.
    $query = $this->connection->select('player', 'p')
      ->fields('p')
      ->extend('\Drupal\Core\Database\Query\PagerSelectExtender')
      ->limit($limit);
    $result = $query->execute()->fetchAll();
    $header = [$this->t('Name'), $this->t('Birthday')];
    $rows = [];

    foreach ($result as $row) {
      $rows[] = [$row->name, $row->data];
    }

    $build = [];
    $build[] = [
      '#theme' => 'table',
      '#header' => $header,
      '#rows' => $rows,
    ];

    $build[] = [
      '#type' => 'pager',
    ];

    return $build;
  }

}
