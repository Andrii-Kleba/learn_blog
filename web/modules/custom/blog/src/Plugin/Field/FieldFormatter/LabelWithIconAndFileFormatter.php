<?php

namespace Drupal\blog\Plugin\Field\FieldFormatter;

use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Field\FieldDefinitionInterface;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\FormatterBase;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\media\MediaInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Plugin implementation of the 'Label with icon and file' formatter.
 *
 * @FieldFormatter(
 *   id = "blog_label_with_icon_and_file",
 *   label = @Translation("Label with icon and file"),
 *   field_types = {
 *     "entity_reference"
 *   }
 * )
 */
class LabelWithIconAndFileFormatter extends FormatterBase implements ContainerFactoryPluginInterface {

  /**
   * The Entity type manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected EntityTypeManagerInterface $entityTypeManager;

  /**
   * LabelWithIconAndFileFormatter constructor.
   */
  public function __construct(
    $plugin_id,
    $plugin_definition,
    FieldDefinitionInterface $field_definition,
    array $settings,
    $label,
    $view_mode,
    array $third_party_settings,
    EntityTypeManagerInterface $entity_type_manager,
  ) {
    parent::__construct(
      $plugin_id,
      $plugin_definition,
      $field_definition,
      $settings,
      $label,
      $view_mode,
      $third_party_settings
    );

    $this->entityTypeManager = $entity_type_manager;
  }

  /**
   * Creates an instance of the plugin.
   *
   * @param \Symfony\Component\DependencyInjection\ContainerInterface $container
   *   The container to pull out services used in the plugin.
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin ID for the plugin instance.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   *
   * @return static
   *   Returns an instance of this plugin.
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition): static {
    return new static(
      $plugin_id,
      $plugin_definition,
      $configuration['field_definition'],
      $configuration['settings'],
      $configuration['label'],
      $configuration['view_mode'],
      $configuration['third_party_settings'],
      $container->get('entity_type.manager'),
    );
  }

  /**
   * {@inheritdoc}
   */
  public static function isApplicable(FieldDefinitionInterface $field_definition) {
    return ($field_definition->getFieldStorageDefinition()
        ->getSetting('target_type') == 'media');
  }

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode): array {
    $element = [];

    foreach ($items as $delta => $item) {
      /** @var \Drupal\media\MediaInterface $entity */
      $entity = $this->entityTypeManager->getStorage('media')
        ->load($item->target_id);
      $element[$delta] = [
        '#theme' => 'blog_label_with_icon_and_file',
        '#url' => $this->getMediaUrl($entity),
        '#label' => $entity->label(),
        '#filesize' => $this->getMediaFileSize($entity),
        '#media_type' => $entity->bundle(),
        '#mime_type' => $this->getMediaMimeType($entity),
      ];
    }

    return $element;
  }

  /**
   * Gets media URL.
   *
   * @param \Drupal\media\MediaInterface $entity
   * The entity where to look for url.
   */
  protected function getMediaUrl(MediaInterface $entity) {
    switch ($entity->bundle()) {
      case 'audio':
        return $this->createFileUrlFromFieldName($entity, 'field_media_audio_file');

      case 'document':
        return $this->createFileUrlFromFieldName($entity, 'field_media_document');

      case 'image':
        return $this->createFileUrlFromFieldName($entity, 'field_media_image');

      case 'remote_video':
        /** @var \Drupal\file\FileInterface $file_entity */
        return $entity->get('field_media_oembed_video')->value;

      case 'video':
        return $this->createFileUrlFromFieldName($entity, 'field_media_video_file');
    }

    return;
  }

  /**
   * Create media filesize.
   *
   * @param \Drupal\media\MediaInterface $entity
   */
  protected function getMediaFileSize(MediaInterface $entity) {
    switch ($entity->bundle()) {
      case 'audio':
        return $this->createFileSizeFromFieldName($entity, 'field_media_audio_file');

      case 'document':
        return $this->createFileSizeFromFieldName($entity, 'field_media_document');

      case 'image':
        return $this->createFileSizeFromFieldName($entity, 'field_media_image');

      case 'video':
        return $this->createFileSizeFromFieldName($entity, 'field_media_video_file');
    }

    return;
  }

  /**
   * Get file mime type.
   *
   * @param \Drupal\media\MediaInterface $entity
   */
  protected function getMediaMimeType(MediaInterface $entity) {
    return match ($entity->bundle()) {
      'audio' => $this->createMimeTypeFromFieldName($entity, 'field_media_audio_file'),
      'document' => $this->createMimeTypeFromFieldName($entity, 'field_media_document'),
      'image' => $this->createMimeTypeFromFieldName($entity, 'field_media_image'),
      'remote_video' => 'video/x-wmv',
      'video' => $this->createMimeTypeFromFieldName($entity, 'field_media_video_file'),
      default => 'application/octet-stream',
    };
  }

  /**
   * Create fileUrl from entity field name.
   *
   * @param \Drupal\media\MediaInterface $entity
   *  The entity with field.
   * @param string $field_name
   *  The field name.
   *
   * @return string
   *  The file url.
   */
  public function createFileUrlFromFieldName(MediaInterface $entity, string $field_name): string {
    /** @var \Drupal\file\FileInterface $file_entity */
    $file_entity = $entity->get($field_name)->entity;
    $file_uri = $file_entity->getFileUri();
    return file_create_url($file_uri);
  }

  /**
   * Create filesize from entity field name.
   *
   * @param \Drupal\media\MediaInterface $entity
   *  The entity with field.
   * @param string $field_name
   *  The field name.
   *
   * @return string
   *  The formatted file size.
   */
  public function createFileSizeFromFieldName(MediaInterface $entity, string $field_name): string {
    /** @var \Drupal\file\FileInterface $file_entity */
    $file_entity = $entity->get($field_name)->entity;

    return format_size($file_entity->getSize());
  }

  /**
   * Gets mime_type from entity field name.
   *
   * @param \Drupal\media\MediaInterface $entity
   *  The entity with field.
   * @param string $field_name
   *  The field name.
   *
   * @return string
   *  The file mime type size.
   */
  public function createMimeTypeFromFieldName(MediaInterface $entity, string $field_name): string {
    /** @var \Drupal\file\FileInterface $file_entity */
    $file_entity = $entity->get($field_name)->entity;

    return $file_entity->getMimeType();
  }

}
