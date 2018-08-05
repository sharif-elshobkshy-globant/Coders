<?php

namespace Drupal\wiki\Form;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Render\RendererInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Builds the wiki search form for the wiki block.
 *
 * @internal
 */
class WikiBlockForm extends FormBase {

  /**
   * The config factory.
   *
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $configFactory;

  /**
   * The renderer.
   *
   * @var \Drupal\Core\Render\RendererInterface
   */
  protected $renderer;

  /**
   * Constructs a new WikiBlockForm.
   *
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   The config factory.
   * @param \Drupal\Core\Render\RendererInterface $renderer
   *   The renderer.
   */
  public function __construct(ConfigFactoryInterface $config_factory, RendererInterface $renderer) {
    $this->configFactory = $config_factory;
    $this->renderer = $renderer;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('config.factory'),
      $container->get('renderer')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'wiki_block_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $params = \Drupal::request()->query->all();
    $form['search'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Wiki Search'),
      '#title_display' => 'invisible',
      '#size' => 40,
      '#default_value' => (isset($params['search'])) ? $params['search'] : '',
      '#required' => true,
      '#attributes' => [
        'title' => $this->t('Insert keywords to search on Wikipedia.'),
        'placeholder' => $this->t('Insert keywords'),
      ],
    ];
    $form['description'] = [
      '#markup' => t('Search articles on wikepedia.'),
      '#weight' => -1
    ];
    $form['actions'] = [
      '#type' => 'actions'
    ];
    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Search Wikipedia'),
      '#name' => '',
    ];
    $form['#method'] = 'get';
    //$form['#action'] = '#submitted';
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $values = $form_state->getValues();
  }

}
