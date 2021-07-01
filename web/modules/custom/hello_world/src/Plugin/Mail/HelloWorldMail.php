<?php

namespace Drupal\hello_world\Plugin\Mail;

use Drupal\Core\Mail\MailFormatHelper;
use Drupal\Core\Mail\MailInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * @Mail(
 *   id = "hello_world_mail",
 *   label = @Translation("Hello world mailer"),
 *   description = @Translation("Send an email using an external API specific to
 *   our Hello World module.")
 * )
 */
class HelloWorldMail implements MailInterface, ContainerFactoryPluginInterface {


  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static();
  }

  /**
   * {@inheritdoc}
   */
  public function format(array $message): array {
    $message['body'] = implode("\n\n", $message['body']);
    $message['body'] = MailFormatHelper::htmlToText($message['body']);
    $message['body'] = MailFormatHelper::wrapMail($message['body']);

    return $message;
  }

  /**
   * {@inheritdoc}
   */
  public function mail(array $message) {
    // TODO: Implement mail() method.
  }

}
