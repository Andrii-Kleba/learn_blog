<?php

namespace Drupal\products;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;

interface ProductInterface extends ContentEntityInterface, EntityChangedInterface {

  /**
   * Gets the Product name.
   *
   * @return string
   */

  public function getName(): string;

  /**
   * Sets the Product name.
   *
   * @param string $name
   *
   * @return \Drupal\products\ProductInterface
   * The called Product entity.
   */
  public function setName(string $name): ProductInterface;

  /**
   * Gets the Product number.
   *
   * @return int
   */

  public function getProductNumber(): int;

  /**
   * Sets the Product number.
   *
   * @param int $number
   *
   * @return \Drupal\products\ProductInterface
   * The called Product entity.
   */
  public function setProductName(int $number): ProductInterface;

  /**
   * Gets the Product remote ID.
   *
   * @return string
   */
  public function getRemoteId(): string;

  /**
   * Sets the Product remote ID.
   *
   * @param string $id
   *
   * @return \Drupal\products\ProductInterface
   * The called Product entity.
   */
  public function setRemoteId(string $id): ProductInterface;

  /**
   * Gets the Product source.
   *
   * @return string
   */
  public function getSource(): string;

  /**
   * Sets the Product source.
   *
   * @param string $source
   *
   * @return \Drupal\products\ProductInterface
   * The called Product entity.
   *
   */
  public function setSource(string $source): ProductInterface;

  /**
   * Gets the Product creation timestamp.
   *
   * @return int
   */
  public function getCreatedTime(): int;

  /**
   * Sets the Product creation timestamp.
   *
   * @param int $timestamp
   *
   * @return \Drupal\products\ProductInterface
   * The called Product entity.
   */
  public function setCreatedTime(int $timestamp): ProductInterface;

}
