<?php

namespace Drupal\hello_world\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Returns responses for Hello World routes.
 */
class HelloWorldQuoteController extends ControllerBase {

  /**
   * Builds the response.
   */
  public function build() {

    $build[] = [
      '#theme' => 'hello_world_quote',
      '#markup' => $this->t('It works! Quote'),
      '#quote' => $this->t('Its cool quote for you hello_world'),
    ];

    $build[] = [
      '#theme' => 'hello_world_quote',
      '#markup' => $this->t('It works! Quote'),
      '#quote' => $this->t('another app'),
      '#author' => 'Oliver Naguhaski',
    ];

    $build[] = [
      '#theme' => 'hello_world_quote',
      '#markup' => $this->t('It works! Quote'),
      '#quote' => $this->t('another app'),
      '#author' => 'Oliver Naguhaski',
      '#source_title' => 'Homepage',
      '#source_url' => 'https://www.linkedin.com/feed/',
      '#year' => 2023,
    ];

    return $build;
  }

}
