<?php

namespace Drupal\blog_paragraphs\Plugin\paragraphs\Behavior;

use Drupal\Core\Annotation\Translation;
use Drupal\Core\Entity\Display\EntityViewDisplayInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\paragraphs\Annotation\ParagraphsBehavior;
use Drupal\paragraphs\Entity\Paragraph;
use Drupal\paragraphs\Entity\ParagraphsType;
use Drupal\paragraphs\ParagraphInterface;
use Drupal\paragraphs\ParagraphsBehaviorBase;
use JetBrains\PhpStorm\ArrayShape;

/**
 * @ParagraphsBehavior(
 *   id = "blog_paragraphs_paragraph_title",
 *   label = @Translation("Paragraph title settings"),
 *   description= @Translation("Allows to configure paragraph title behavior."),
 *   weight = 0,
 * )
 */
class ParagraphTitleBehavior extends ParagraphsBehaviorBase {

  /**
   * {@inheritdoc}
   */
  public static function isApplicable(ParagraphsType $paragraphs_type): bool {
    return TRUE;
  }

  /**
   * Extends the paragraph render array with behavior.
   */
  public function view(array &$build, Paragraph $paragraph, EntityViewDisplayInterface $display, $view_mode) {

  }

  /**
   * {@inheritdoc}
   */
  public function preprocess(&$variables) {
    /** @var \Drupal\paragraphs\ParagraphInterface $paragraph */
    $paragraph = $variables['paragraph'];
    $variables['title_wrapper'] = $paragraph->getBehaviorSetting($this->getPluginId(), 'title_wrapper', 'h2');
  }

  /**
   * {@inheritdoc}
   */
  public function buildBehaviorForm(ParagraphInterface $paragraph, array &$form, FormStateInterface $form_state) {

    if ($paragraph->hasField('field_title')) {
      $form['title_wrapper'] = [
        '#type' => 'select',
        '#title' => $this->t('Title wrapper element'),
        '#options' => $this->getHeadingOptions(),
        '#default_value' => $paragraph->getBehaviorSetting($this->getPluginId(), 'title_wrapper', 'h2'),
      ];
    }

    return $form;
  }

  /**
   * Defines list of available options for title element.
   */
  #[ArrayShape([
    'h2' => 'string',
    'h3' => 'string',
    'h4' => 'string',
    'div' => 'string',
  ])]
  public function getHeadingOptions(): array {
    return [
      'h2' => 'h2',
      'h3' => 'h3',
      'h4' => 'h4',
      'div' => 'div',
    ];
  }

}

