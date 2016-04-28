<?php

namespace Drupal\my_custom_block_module\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Provides a 'CustomBlock' block.
 *
 * @Block(
 *  id = "custom_block",
 *  admin_label = @Translation("Custom block"),
 * )
 */
class CustomBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function blockForm($form, FormStateInterface $form_state) {
    $form['message'] = array(
      '#type' => 'textarea',
      '#title' => $this->t('Message'),
      '#description' => $this->t('This is where text will go'),
      '#default_value' => isset($this->configuration['message']) ? $this->configuration['message'] : '',
      '#weight' => '0',
    );

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function blockSubmit($form, FormStateInterface $form_state) {
    $this->configuration['message'] = $form_state->getValue('message');
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    $build = [];
    $build['custom_block_message']['#markup'] = '<p>' . $this->configuration['message'] . '</p>';

    return $build;
  }

}
