<?php

namespace Drupal\hello_world\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Returns responses for Hello World routes.
 */
class HelloWorldSecondController extends ControllerBase {

  /**
   * Builds the response.
   */
  public function build() {

    $build['content'] = [
      '#type' => 'item',
      '#markup' => $this->t('It works! second'),
    ];

    $build['second_content'] = [
      '#theme' => 'hello_world_second',
      '#list_type' => 'ul',
      '#items' => ['One item', 'Two item', 'Three item'],
    ];

    $correct_form =\Drupal::formBuilder()->getForm('Drupal\hello_world\Form\SalutationConfigurationForm');

    $build['third_content'] = $correct_form;

    return $build;
  }

}
