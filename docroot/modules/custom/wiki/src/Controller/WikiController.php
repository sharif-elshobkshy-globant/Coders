<?php

namespace Drupal\wiki\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Controller routines for wiki routes.
 */
class WikiController extends ControllerBase {

  /**
   * The wikipedia API client.
   *
   * @var \Drupal\wiki\WikiApi
   */
  protected $wikiClient;

  /**
   * Constructs a WikiController object.
   */
  public function __construct() {
    $this->wikiClient = \Drupal::service('wiki.wikiApi');
  }

  /**
   * Main Render function callback.
   */
  function mainRender($key = '') {
    // Wiki Search Form
    $form = $this->formBuilder()->getForm('Drupal\wiki\Form\WikiBlockForm');
    $params = \Drupal::request()->query->all();
    // Get keys.
    $content = [];
    if (isset($params['search']) && ($params['search'])) {
      $key = $params['search'];
    }
    // Get articles from wikipedia.
    if ($key) {
      $content = $this->wikiClient->getResponse($key);
    }
    return [
      '#theme' => 'wiki_page',
      '#parameters' => [
        'searchForm' => $form,
        'key' => $key,
        'data' => $content
      ]
    ];
  }

}
