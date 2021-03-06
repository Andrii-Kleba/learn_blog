<?php

namespace Drupal\products\Form;

use Drupal\Core\Entity\EntityForm;
use Drupal\Core\Form\FormStateInterface;

class ProductTypeForm extends EntityForm {

  /**
   * {@inheritdoc}
   */
  public function form(array $form, FormStateInterface $form_state): array {
    $form = parent::form($form, $form_state);

    /** @var \Drupal\products\ProductTypeInterface $product_type */
    $product_type = $this->entity;
    $form['label'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Label'),
      '#maxlength' => 255,
      '#default_value' => $product_type->label(),
      '#description' => $this->t('Label for the Product type.'),
      '#required' => TRUE,
    ];

    $form['id'] = [
      '#type' => 'machine_name',
      '#defaults_value' => $product_type->id(),
      '#machine_name' => [
        'exists' => '\Drupal\products\Entity\ProductType::load',
      ],
      '#disabled' => !$product_type->isNew(),
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    $product_type = $this->entity;
    $status = $product_type->save();

    switch ($status) {
      case SAVED_NEW:
        \Drupal::messenger()->addMessage($this->t('Created the %label Product type', ['%label' => $product_type->label()]));
        break;

      default:
        \Drupal::messenger()->addMessage($this->t('Saved the %label Product type', ['%label' => $product_type->label()]));
    }

    $form_state->setRedirectUrl($product_type->toUrl('collection'));
  }

}
