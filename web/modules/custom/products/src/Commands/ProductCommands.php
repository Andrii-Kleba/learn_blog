<?php

namespace Drupal\products\Commands;

use Drupal\products\Plugin\ImporterInterface;
use Drupal\products\Plugin\ImporterManager;
use Drush\Commands\DrushCommands;
use Symfony\Component\Console\Input\InputOption;

class ProductCommands extends DrushCommands {

  /**
   * @var \Drupal\products\Plugin\ImporterManager
   */
  protected ImporterManager $importerManager;

  /**
   * ProductCommands constructor.
   *
   * @param \Drupal\products\Plugin\ImporterManager $importer_manager
   */
  public function __construct(ImporterManager $importer_manager) {
    $this->importerManager = $importer_manager;
  }

  /**
   * Imports the Product.
   *
   * @option importer
   *  The importer config ID to use.
   *
   * @command products-import-run
   * @aliases pir
   *
   * @param array $options
   *  The command options.
   */
  public function import(array $options = ['importer' => InputOption::VALUE_OPTIONAL]) {
    $importer = $options['importer'];

    if (!is_null($importer)) {
      $plugin = $this->importerManager->createInstanceFormConfig($importer);
      if (is_null($plugin)) {
        $this->logger->log('error', t('The specified importer does not exists.'));
        return;
      }

      $this->runPluginImport($plugin);
      return;
    }

    $plugins = $this->importerManager->createInstanceFromAllConfigs();
    if (!$plugins) {
      $this->logger()->log('error', t('There are no importers to run'));
      return;
    }

    foreach ($plugins as $plugin) {
      $this->runPluginImport($plugin);
    }

  }

  /**
   *  Runs an individual Importer plugin.
   *
   * @param \Drupal\products\Plugin\ImporterInterface $plugin
   */
  protected function runPluginImport(ImporterInterface $plugin) {
    $result = $plugin->import();
    $message_value = ['@importer' => $plugin->getConfig()->label()];
    if ($result) {
      $this->logger()
        ->log('status', t('The "@importer" has been run.', $message_value));
      return;
    }

    $this->logger()
      ->log('error', t('There was a problem running the "@importer" importer.', $message_value));
  }

}
