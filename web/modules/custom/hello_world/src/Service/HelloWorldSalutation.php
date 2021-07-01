<?php

namespace Drupal\hello_world\Service;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\Core\StringTranslation\TranslatableMarkup;
use Drupal\hello_world\SalutationEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class HelloWorldSalutation {

  use StringTranslationTrait;

  /**
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  protected ConfigFactoryInterface $configFactory;

  /**
   * @var \Symfony\Component\EventDispatcher\EventDispatcherInterface
   */
  protected EventDispatcherInterface $eventDispatcher;

  public function __construct(ConfigFactoryInterface $config_factory, EventDispatcherInterface $event_dispatcher) {
    $this->configFactory = $config_factory;
    $this->eventDispatcher = $event_dispatcher;
  }

  public function getSalutation(): mixed {
    $config = $this->configFactory->get('hello_world.custom_salutation');
    $salutation = $config->get('salutation');

    if ($salutation != '') {
      $event = new SalutationEvent();
      $event->setMessage($salutation);
      $event = $this->eventDispatcher->dispatch($event, SalutationEvent::EVENT);
      return $event->getMessage();
    }

    $time = new \DateTime();
    if ((int) $time->format('G') >= 00 && (int) $time->format('G') < 12) {
      return $this->t('Good morning world');
    }

    if ((int) $time->format('G') >= 12 && (int) $time->format('G') < 18) {
      return $this->t('Good afternoon world');
    }

    if ((int) $time->format('G') >= 18) {
      return $this->t('Good evening world');
    }
  }

}
