<?php

namespace Drupal\products\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBase;
use Drupal\Core\Url;
use Drupal\products\ImporterInterface;

/**
 * @ConfigEntityType(
 *   id = "importer",
 *   label = @Translation("Importer"),
 *   handlers = {
 *     "list_builder" = "Drupal\products\ImporterListBuilder",
 *     "form" = {
 *       "add" = "Drupal\products\Form\ImporterForm",
 *       "edit" = "Drupal\products\Form\ImporterForm",
 *       "delete" = "Drupal\products\Form\ImporterDeleteForm",
 *    },
 *      "route_provider" = {
 *        "html" = "Drupal\Core\Entity\Routing\AdminHtmlRouteProvider",
 *    },
 *   },
 *   config_prefix = "importer",
 *   admin_permission = "administer site configuration",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label",
 *     "uuid" = "uuid"
 *   },
 *   config_export = {
 *     "id",
 *     "label",
 *     "url",
 *     "plugin",
 *     "update_existing",
 *     "source",
 *     "bundle"
 *   },
 *   links = {
 *     "add-form" = "/admin/structure/importer/add",
 *     "edit-form" = "/admin/structure/importer/{importer}/edit",
 *     "delete-form" = "/admin/structure/importer/{importer}/delete",
 *     "collection" = "/admin/structure/importer",
 *   }
 * )
 */
class Importer extends ConfigEntityBase implements ImporterInterface {

  /**
   * The Importer ID.
   *
   * @var string
   */
  protected string $id;

  /**
   * The importer label.
   *
   * @var string
   */
  protected string $label;

  /**
   * The Url from where the import file can be retrieved.
   *
   * @var string
   */
  protected string $url;

  /**
   * The plugin ID of the plugin to be used for processing this import.
   *
   * @var string
   */
  protected string $plugin;

  /**
   * Whether or not to update existing products if they have already been
   * imported.
   *
   * @var bool
   */
  protected bool $update_existing = TRUE;

  /**
   * The source of the products.
   *
   * @var string
   */
  protected string $source;

  /**
   * The product bundle.
   *
   * @var string
   */
  protected string $bundle;

  /**
   * {@inheritdoc}
   */
  public function getPluginId(): string {
    return $this->plugin ?? '';
  }

  /**
   * {@inheritdoc}
   */
  public function getUrl(): Url|NULL {
    return isset($this->url) ? Url::fromUri($this->url) : NULL;
  }

  /**
   * {@inheritdoc}
   */
  public function updateExisting(): bool {
    return $this->update_existing;
  }

  /**
   * {@inheritdoc}
   */
  public function getSource(): string {
    return $this->source ?? '';
  }

  /**
   * {@inheritdoc}
   */
  public function getBundle(): string {
    return $this->bundle ?? '';
  }

}
