<?php

namespace Drupal\products\Plugin;

use Drupal\Component\Plugin\PluginInspectionInterface;

interface ImporterInterface extends PluginInspectionInterface {

  public function import();

  /**
   * Returns the Importer configuration entity.
   *
   * @return \Drupal\products\Plugin\ImporterInterface
   */
  public function getConfig(): ImporterInterface;

}
