<?php

namespace Drupal\hello_world;

use Drupal\Component\EventDispatcher\Event;

class SalutationEvent extends Event {

  const EVENT = 'hello_world.salutation_event';

  /**
   * The salutation message.
   *
   * @var string
   */
  protected string $message;

  /**
   * Return salutation message.
   *
   * @return string
   */
  public function getMessage(): string {
    return $this->message;
  }

  /**
   * Set salutation message.
   *
   * @param $message
   */
  public function setMessage($message) {
    $this->message = $message;
  }

}
