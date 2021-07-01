<?php

namespace Drupal\hello_world\Plugin\Field\FieldWidget;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\WidgetBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\field_group\FormatterHelper;

/**
 * Plugin implementation of the 'field_example_text' widget.
 *
 * @FieldWidget(
 *   id = "field_example_text",
 *   module = "field_example",
 *   label = @Translation("RGB value as #ffffff"),
 *   field_types = {
 *     "entity_reference_revisions"
 *   }
 * )
 */
class BarWidget extends WidgetBase {

  public function formElement(FieldItemListInterface $items, $delta, array $element, array &$form, FormStateInterface $form_state) {

    if (\Drupal::moduleHandler()->moduleExists('field_group')) {
      $context = [
        'entity_type' => 'node',
        'bundle' => 'article',
        'entity' => 'email_gate',
        'context' => 'form',
        'display_context' => 'form',
        'mode' => NULL
      ];

      field_group_attach_groups($element['subform'], $context);
      if (method_exists(FormatterHelper::class, 'formProcess')) {
        $element['subform']['#process'][] = [FormatterHelper::class, 'formProcess'];
      }
      elseif (function_exists('field_group_form_pre_render')) {
        $element['subform']['#pre_render'][] = 'field_group_form_pre_render';
      }
      elseif (function_exists('field_group_form_process')) {
        $element['subform']['#process'][] = 'field_group_form_process';
      }
    }


    $value = isset($items[$delta]->value) ? $items[$delta]->value : '';
    $element += [
      '#type' => 'textfield',
      '#default_value' => $value,
      '#size' => 7,
      '#maxlength' => 7,
      '#element_validate' => [
        [static::class, 'validate'],
      ],
    ];
    return ['value' => $element];
  }
  /**
   * Validate the color text field.
   */
  public static function validate($element, FormStateInterface $form_state) {
    $value = $element['#value'];
    if (strlen($value) == 0) {
      $form_state->setValueForElement($element, '');
      return;
    }
    if (!preg_match('/^#([a-f0-9]{6})$/iD', strtolower($value))) {
      $form_state->setError($element, t("Color must be a 6-digit hexadecimal value, suitable for CSS."));
    }
  }


}
