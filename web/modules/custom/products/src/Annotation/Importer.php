<?php

namespace Drupal\products\Annotation;

use Drupal\Component\Annotation\Plugin;

/**
 * Define importer annotation.
 *
 * @Annotation
 */
class Importer extends Plugin {

  /**
   * The plugin ID.
   *
   * @var string
   */
  public string $id;

  /**
   * The label of the plugin.
   *
   * @var string
   */
  public string $label;

}
