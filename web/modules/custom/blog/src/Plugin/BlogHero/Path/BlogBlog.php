<?php

namespace Drupal\blog\Plugin\BlogHero\Path;

use Drupal\blog_hero\Annotation\BlogHeroPath;
use Drupal\blog_hero\Plugin\BlogHero\Path\BlogHeroPathPluginBase;

/**
 * Hero blog for path.
 *
 * @BlogHeroPath(
 *   id = "blog_blog",
 *   match_type = "listed",
 *   match_path = {"/blog"},
 * )
 */
class BlogBlog extends BlogHeroPathPluginBase {

  /**
   *{@inheritdoc}
   */
  public function getHeroImage(): string {
    /** @var \Drupal\media\MediaStorage $media_storage */
    $media_storage = $this->getEntityTypeManger()->getStorage('media');
    $media_image = $media_storage->load(11);

    return $media_image->get('field_media_image')->entity->get('uri')->value;
  }

  /**
   *{@inheritdoc}
   */
  public function getHeroSubtitle(): string {
    return 'Last Video check';
  }

  /**
   *{@inheritdoc}
   */
  public function getHeroVideo(): array {
    /** @var \Drupal\media\MediaStorage $media_storage */
    $media_storage = $this->getEntityTypeManger()->getStorage('media');
    $media_video = $media_storage->load(10);

    return [
      'video/mp4' => $media_video->get('field_media_video_file')->entity->get('uri')->value,
    ];
  }

}
