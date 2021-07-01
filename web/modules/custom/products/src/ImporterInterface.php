<?php

namespace Drupal\products;

use Drupal\Core\Config\Entity\ConfigEntityInterface;
use Drupal\Core\Url;

interface ImporterInterface extends ConfigEntityInterface {

  /**
   * Returns the Url where the import can get the data from.
   *
   * @return \Drupal\Core\Url|NULL
   */
  public function getUrl(): Url|null;

  /**
   * Returns the Importer plugin ID to be used by this importer.
   *
   * @return string
   */
  public function getPluginId(): string;

  /**
   * Whether or not to update existing product if they have already been
   * imported.
   *
   * @return bool
   */
  public function updateExisting(): bool;

  /**
   * Returns the source of the product.
   *
   * @return string
   */
  public function getSource(): string;

  /**
   * Returns the Product type that needs to be created.
   *
   * @return string
   */
  public function getBundle(): string;

}
