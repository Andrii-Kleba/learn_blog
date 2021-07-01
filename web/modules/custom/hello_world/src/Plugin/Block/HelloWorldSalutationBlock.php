<?php

namespace Drupal\hello_world\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\hello_world\Service\HelloWorldSalutation;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 *  Hello World Salutation block.
 *
 * @Block (
 *   id = "hello_world_salutation_block",
 *   admin_label = @Translation("Hello World Salutation")
 * )
 */
class HelloWorldSalutationBlock extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * @var \Drupal\hello_world\Service\HelloWorldSalutation
   */
  protected HelloWorldSalutation $salutation;

  public function __construct(array $configuration, $plugin_id, $plugin_definition, HelloWorldSalutation $hello_world_salutation) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->salutation = $hello_world_salutation;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition): static {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('hello_world.salutation')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    return [
      '#markup' => $this->salutation->getSalutation(),
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration(): array {
    return [
      'enabled' => 1,
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function blockForm($form, FormStateInterface $form_state): array {
    $config = $this->getConfiguration();

    $form['enabled'] =[
      '#type' => 'checkbox',
      '#title' => $this->t('Enabled'),
      '#description' => $this->t('Check this box if you want to enable this feature.'),
      '#default_value' => $config['enabled'],
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function blockSubmit($form, FormStateInterface $form_state) {
  $this->configuration['enabled'] = $form_state->getValue('enabled');
  }

}
