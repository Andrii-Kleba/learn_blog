<?php

namespace Drupal\products\Form;

use Drupal\Core\Entity\ContentEntityForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class ProductForm
 *
 * @package Drupal\products\Form
 */
class ProductForm extends ContentEntityForm {

  public function save(array $form, FormStateInterface $form_state) {
    $entity = &$this->entity;
    $status = parent::save($form, $form_state);

    switch ($status) {
      case SAVED_NEW:
        \Drupal::messenger()
          ->addMessage($this->t('Created the %label Product.', ['%label' => $entity->label()]));
        break;

      default:
        \Drupal::messenger()
          ->addMessage($this->t('Saved the %label Product.', ['%label' => $entity->label()]));
    }

    $form_state->setRedirect('entity.product.canonical', ['product' => $entity->id()]);
  }

}
