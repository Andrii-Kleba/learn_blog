<?php

namespace Drupal\blog_paragraphs\Plugin\paragraphs\Behavior;

use Drupal\Component\Utility\Html;
use Drupal\Core\Entity\Display\EntityViewDisplayInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\paragraphs\Entity\Paragraph;
use Drupal\paragraphs\Entity\ParagraphsType;
use Drupal\paragraphs\ParagraphInterface;
use Drupal\paragraphs\ParagraphsBehaviorBase;
use Drupal\paragraphs\Annotation\ParagraphsBehavior;

/**
 * Class GalleryBehavior
 *
 * @ParagraphsBehavior(
 *   id = "blog_paragraphs_image_and_text",
 *   label = @Translation("Image and TextBehavior Settings"),
 *   description = @Translation("Settings for image and text behavior paragraph
 *   type."), weight = 0,
 * )
 */
class ImageAndTextBehavior extends ParagraphsBehaviorBase {

  /**
   * {@inheritdoc}
   */
  public static function isApplicable(ParagraphsType $paragraphs_type): bool {
    return $paragraphs_type->id() === 'image_and_text';
  }

  /**
   * Extends the paragraph render array with behavior;
   *
   * @param array $build
   * @param \Drupal\paragraphs\Entity\Paragraph $paragraph
   * @param \Drupal\Core\Entity\Display\EntityViewDisplayInterface $display
   * @param $view_mode
   *
   * @return array
   */
  public function view(array &$build, Paragraph $paragraph, EntityViewDisplayInterface $display, $view_mode) {
    $bem_block = 'paragraph-' . $paragraph->bundle() . ($view_mode === 'default' ? '' : '-' . $view_mode);
    $image_position = $paragraph->getBehaviorSetting($this->getPluginId(), 'image_position', 'left');
    $image_size = $paragraph->getBehaviorSetting($this->getPluginId(), 'image_size', '4_12');

    $build['#attributes']['class'][] = Html::getClass($bem_block . '--image-position-' . $image_position);
    $build['#attributes']['class'][] = Html::getClass($bem_block . '--image-size-' . $image_size);

    if (isset($build['field_image']['#formatter']) && $build['field_image']['#formatter'] === 'media_thumbnail') {
      $image_style = match ($image_size) {
        '6_12' => 'paragraph_image_text_6_of_12',
        '8_12' => 'paragraph_image_text_8_of_12',
        default => 'paragraph_image_text_4_of_12',
      };

      $build['field_image'][0]['#image_style'] = $image_style;
    }
  }

  /**
   * {@inheritdoc}
   */
  public function buildBehaviorForm(ParagraphInterface $paragraph, array &$form, FormStateInterface $form_state): array {
    $form['image_position'] = [
      '#type' => 'select',
      '#title' => $this->t('Image position'),
      '#options' => [
        'left' => $this->t('On the Left'),
        'right' => $this->t('On the Right'),
      ],
      '#default_value' => $paragraph->getBehaviorSetting($this->getPluginId(), 'image_position', 'left'),
    ];

    $form['image_size'] = [
      '#type' => 'select',
      '#title' => $this->t('Image size'),
      '#description' => $this->t('Size of the image in grid.'),
      '#options' => [
        '4_12' => $this->t('4 columns of 12'),
        '6_12' => $this->t('6 columns of 12'),
        '8_12' => $this->t('8 columns of 12'),
      ],
      '#default_value' => $paragraph->getBehaviorSetting($this->getPluginId(), 'image_size', '4_12'),
    ];

    return $form;
  }

}
