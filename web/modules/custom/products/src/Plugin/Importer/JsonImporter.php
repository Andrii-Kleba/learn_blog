<?php

namespace Drupal\products\Plugin\Importer;

use Drupal\products\Plugin\ImporterBase;
use Drupal\products\Annotation\Importer;

/**
 *
 * @Importer(
 *   id = "json",
 *   label = @Translation("Json Importer")
 * )
 */
class JsonImporter extends ImporterBase {

  /**
   * {@inheritdoc}
   */
  public function import(): bool {
    $data = $this->getData();
    if (!$data) {
      return FALSE;
    }

    if (!isset($data->products)) {
      return FALSE;
    }

    $products = $data->products;
    foreach ($products as $product) {
      $this->persistProduct($product);
    }

    return TRUE;
  }

  /**
   * @return mixed
   */
  private function getData() {
    /** @var \Drupal\products\ImporterInterface $config */
    $config = $this->configuration['config'];
    $request = $this->httpClient->get($config->getUrl()->toString());
    $string_json = $request->getBody()->getContents();

    return json_decode($string_json);
  }

  private function persistProduct($data) {
    /** @var \Drupal\products\ImporterInterface $config */
    $config = $this->configuration['config'];

    $existing = $this->entityTypeManager->getStorage('product')
      ->loadByProperties([
        'remote_id' => $data->id(),
        'source' => $config->getSource(),
      ]);
    if (!$existing) {
      $values = [
        'remote_id' => $data->id(),
        'source' => $config->getSource(),
        'type' => $config->getBundle(),
      ];

      /** @var \Drupal\products\ProductInterface $product */
      $product = $this->entityTypeManager->getStorage('product')
        ->create($values);
      $product->setName($data->name);
      $product->setProductName($data->number);
      $product->save();
      return;
    }

    if (!$config->updateExisting()) {
      return;
    }

    /** @var \Drupal\products\ProductInterface $product */
    $product = reset($existing);
    $product->setName($data->name);
    $product->setProductName($data->number);
    $product->save();
  }

}
