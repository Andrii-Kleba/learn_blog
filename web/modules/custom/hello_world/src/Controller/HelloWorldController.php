<?php

namespace Drupal\hello_world\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\hello_world\Service\HelloWorldSalutation;
use Symfony\Component\DependencyInjection\ContainerInterface;

class HelloWorldController extends ControllerBase {

  protected HelloWorldSalutation $salutation;

  public function __construct(HelloWorldSalutation $salutation) {
    $this->salutation = $salutation;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container): static {
    return new static(
      $container->get('hello_world.salutation')
    );
  }


  public function helloWorld() {
    return [
      '#markup' => $this->salutation->getSalutation(),
    ];
  }

}
