<?php

/**
 * @file
 * Contains \Drupal\feedback_simple\Form\FeedbackSimpleSystemSettings.
 */

namespace Drupal\feedback_simple\Form;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Render\Element;
use Symfony\Component\DependencyInjection\ContainerInterface;

class FeedbackSimpleSettings extends ConfigFormBase {

  /**
   * The configuration factory.
   *
   * @var \Drupal\Core\Config\Config
   */
  protected $config;

  /**
   * {@inheritdoc}
   */
  public function __construct(ConfigFactoryInterface $config_factory) {
    $this->config = $config_factory->getEditable('feedback_simple.settings');
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
    // Load the service required to construct this class.
      $container->get('config.factory')
    );
  }
  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'feedback_simple_settings';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return ['feedback_simple.settings'];
  }


  public function buildForm(array $form, FormStateInterface $form_state) {
    $form = [];
    $form['#tree'] = TRUE;
    $form['feedback_simple'] = [
      '#type' => 'details',
      '#title' => t('Feedback Simple'),
      '#description' => t('Configure the Feedback Simple tab.'),
      '#open' => TRUE
    ];
    $form['feedback_simple']['enabled'] = [
      '#type' => 'checkbox',
      '#title' => t('Enabled'),
      '#default_value' => $this->config->get('feedback_simple')['enabled'],
    ];
    $form['feedback_simple']['link'] = [
      '#type' => 'textfield',
      '#title' => t('Link'),
      '#description' => t('Drupal path to visit when clicked.'),
      '#default_value' => $this->config->get('feedback_simple')['link'],
    ];
    $form['feedback_simple']['target'] = [
      '#type' => 'select',
      '#title' => t('Target'),
      '#description' => t('Location to open the link.'),
      '#options' => [
        '_self' => t('Current window'),
        '_blank' => t('New window'),
      ],
      '#default_value' => $this->config->get('feedback_simple')['target'],
    ];
    $form['feedback_simple']['class'] = [
      '#type' => 'textfield',
      '#title' => t('Class'),
      '#description' => t('CSS classes to apply, separated by spaces.'),
      '#default_value' => $this->config->get('feedback_simple')['class'],
    ];
    $form['feedback_simple']['align'] = [
      '#type' => 'select',
      '#title' => t('Alignment'),
      '#description' => t('Side of the window to attach to.'),
      '#options' => [
        'left' => t('Left'),
        'right' => t('Right'),
      ],
      '#default_value' => $this->config->get('feedback_simple')['align'],
    ];
    $form['feedback_simple']['top'] = [
      '#type' => 'select',
      '#title' => t('Top'),
      '#description' => t('Distance from the top.'),
      '#default_value' => $this->config->get()['top'],
    ];
    for ($i = 0; $i <= 100; $i += 5) {
      $top["$i%"] = "$i%";
    }
    $form['feedback_simple']['top']['#options'] = $top;
    $form['feedback_simple']['image'] = [
      '#type' => 'textfield',
      '#title' => t('Image'),
      '#description' => t('Path to the image.'),
      '#default_value' => $this->config->get('feedback_simple')['image'],
    ];
    $form['feedback_simple']['alt'] = [
      '#type' => 'textfield',
      '#title' => t('Image alt'),
      '#description' => t('Alternative text.'),
      '#default_value' => $this->config->get('feedback_simple')['alt'],
    ];
    $form['feedback_simple']['form_denyallow_markup'] = [
      '#markup' => t('<h3>Visibility rules</h3><p>By default, the Feedback tab
      shows on every page except on the <em>link</em> set above. Paths can explicity be
      set to hide or show below, by listing them with wild cards, one per line.</p>')
    ];
    $form['feedback_simple']['deny'] = [
      "#type" => 'textarea',
      '#title' => t('Deny'),
      '#description' => t('Hide on these paths.'),
      '#default_value' => $this->config->get('feedback_simple')['deny'],
    ];
    $form['feedback_simple']['allow'] = [
      "#type" => 'textarea',
      '#title' => t('Allow'),
      '#description' => t('Show on these paths.'),
      '#default_value' => $this->config->get('feedback_simple')['allow'],
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $config = $this->config('feedback_simple.settings');
    $config->set('feedback_simple', $form_state->getValue('feedback_simple'));
    $config->save();
    parent::submitForm($form, $form_state);
  }

}
