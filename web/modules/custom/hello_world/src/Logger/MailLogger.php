<?php

namespace Drupal\hello_world\Logger;

use Drupal\Component\Render\FormattableMarkup;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\Core\Logger\LogMessageParserInterface;
use Drupal\Core\Logger\RfcLoggerTrait;
use Drupal\Core\Logger\RfcLogLevel;
use Drupal\Core\Mail\MailManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class MailLogger implements LoggerInterface,ContainerInjectionInterface {

  use RfcLoggerTrait;

  /**
   * @var \Drupal\Core\Logger\LogMessageParserInterface
   */
  protected LogMessageParserInterface $parser;

  /**
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  protected ConfigFactoryInterface $configFactory;

  /**
   * @var \Drupal\Core\Mail\MailManagerInterface
   */
  protected MailManagerInterface $mailManager;

  /**
   * MailLogger constructor.
   *
   * @param \Drupal\Core\Logger\LogMessageParserInterface $log_message_parser
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   * @param \Drupal\Core\Mail\MailManagerInterface $mail_manager
   */
  public function __construct(LogMessageParserInterface $log_message_parser, ConfigFactoryInterface $config_factory, MailManagerInterface $mail_manager) {
    $this->parser = $log_message_parser;
    $this->configFactory = $config_factory;
    $this->mailManager = $mail_manager;
  }

  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('logger.log_message_parser'),
      $container->get('config.factory'),
      $container->get('plugin.manager.mail')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function log($level, $message, array $context = []) {
    if ($level !== RfcLogLevel::INFO) {
      return;
    }

    $to = $this->configFactory->get('system.site')->get('mail');
    $langcode = $this->configFactory->get('system.site')->get('langcode');
    $variables = $this->parser->parseMessagePlaceholders($message, $context);
    $markup = new FormattableMarkup($message, $variables);
    $this->mailManager->mail('hello_world', 'hello_world_log', $to, $langcode, ['message' => $markup]);
  }

}
