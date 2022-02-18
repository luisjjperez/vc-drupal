<?php

namespace Drupal\whatsapp_bubble\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Config\ConfigFactoryInterface;

/**
 * Class ConfigForm.
 */
class ConfigForm extends ConfigFormBase {

  /**
   * Drupal\Core\Config\ConfigFactoryInterface definition.
   *
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $configFactory;

  /**
   * Constructs a new ConfigForm object.
   */
  public function __construct(
    ConfigFactoryInterface $config_factory
    ) {
    parent::__construct($config_factory);
    $this->configFactory = $config_factory;
  }

  /**
   * Create configuration.
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('config.factory')
    );
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'whatsapp_bubble.config',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'whatsapp_bubble_config_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('whatsapp_bubble.config');
    $form['alignment'] = [
      '#type' => 'select',
      '#title' => $this->t('Alignment'),
      '#description' => $this->t('Horizontal alignment of the bubble'),
      '#options' => [
        'left' => $this->t('left'),
        'right' => $this->t('right'),
        'center' => $this->t('center'),
      ],
      '#size' => 1,
      '#default_value' => $config->get('alignment'),
    ];
    $form['valignment'] = [
      '#type' => 'select',
      '#title' => $this->t('Vertical alignment'),
      '#description' => $this->t('Vertical alignment'),
      '#options' => [
        'top' => $this->t('top'),
        'bottom' => $this->t('bottom'),
        'middle' => $this->t('middle'),
      ],
      '#size' => 1,
      '#default_value' => $config->get('valignment'),
    ];
    $form['message'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Message'),
      '#description' => $this->t('Default message to include in the whatsapp message box'),
      '#default_value' => $config->get('message'),
    ];
    $form['phone_number'] = [
      '#type' => 'number',
      '#title' => $this->t('Phone Number'),
      '#description' => $this->t('The destination phone number'),
      '#default_value' => $config->get('phone_number'),
      '#required' => TRUE,
    ];
    $form['is_inverse'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Inverse color'),
      '#description' => 'If selected, the button background will show green and the icon white.',
      '#default_value' => $config->get('is_inverse'),
      '#required' => FALSE,
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    parent::submitForm($form, $form_state);

    $this->config('whatsapp_bubble.config')
      ->set('alignment', $form_state->getValue('alignment'))
      ->set('valignment', $form_state->getValue('valignment'))
      ->set('message', $form_state->getValue('message'))
      ->set('phone_number', $form_state->getValue('phone_number'))
      ->set('is_inverse', $form_state->getValue('is_inverse'))
      ->save();
  }

}
