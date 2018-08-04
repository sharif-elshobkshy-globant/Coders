<?php

namespace Drupal\wiki\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Controller routines for book a class routes.
 */
class WikiController extends ControllerBase {

  function mainRender($key = '') {
    $client = \Drupal::service('wiki.wikiApi');
    $params = \Drupal::request()->query->all();

    if (isset($params['search']) && ($params['search'])) {
      $key = $params['search'];
    }
    if (empty($key)) {
      return [];
    }
    $content = $client->getResponse($key);
    return [
      '#theme' => 'wiki_page',
      '#parameters' => [
        'key' => $key,
        'data' => $content
      ]
    ];
  }

}
