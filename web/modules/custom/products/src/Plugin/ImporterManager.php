<?php

namespace Drupal\products\Plugin;

use Drupal\Core\Extension\ModuleHandlerInterface;
use Drupal\Core\Plugin\DefaultPluginManager;
use Drupal\Core\Cache\CacheBackendInterface;

class ImporterManager extends DefaultPluginManager {

  /**
   * ImporterManager constructor.
   *
   * @param \Traversable $namespaces
   * @param \Drupal\Core\Cache\CacheBackendInterface $cache_backend
   * @param \Drupal\Core\Extension\ModuleHandlerInterface $module_handler
   */
  public function __construct(
    \Traversable $namespaces,
    CacheBackendInterface $cache_backend,
    ModuleHandlerInterface $module_handler,
  ) {
    parent::__construct(
      'Plugin/Importer',
      $namespaces,
      $module_handler,
      'Drupal\products\Plugin\ImporterInterface',
      'Drupal\products\Annotation\Importer',
    );

    $this->alterInfo('products_importer_info');
    $this->setCacheBackend($cache_backend, 'products_importer_plugins');
  }

  /**
   * @param $id
   */
  public function createInstanceFormConfig($id) {
    $config = \Drupal::entityTypeManager()->getStorage('importer')->load($id);
    if (!$config instanceof \Drupal\products\ImporterInterface) {
      return NULL;
    }

    return $this->createInstance($config->getPluginId(), ['config' => $config]);
  }

  /**
   * Creates an array of importer plugins from all the existing Importer
   * configuration entities.
   */
  public function createInstanceFromAllConfigs() {
    $configs = \Drupal::entityTypeManager()
      ->getStorage('importer')
      ->loadMultiple();
    if (!$configs) {
      return [];
    }

    $plugins = [];
    foreach ($configs as $config) {
      $plugin = $this->createInstanceFormConfig($config->id());
      if (!$plugin) {
        continue;
      }

      $plugins[] = $plugin;
    }

    return $plugins;
  }

}
