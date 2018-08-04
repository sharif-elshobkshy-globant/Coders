<?php

namespace Drupal\wiki;

use Drupal\Core\Link;
use Drupal\Core\Url;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\ClientInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class WikiApi {

  /**
   * An http client.
   *
   * @var \GuzzleHttp\ClientInterface
   */
  protected $http_client;

  /**
   * Construct a Wikipedia client object.
   *
   * @param \GuzzleHttp\ClientInterface $http_client
   */
  public function __construct(ClientInterface $http_client) {
    $this->http_client = $http_client;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('http_client')
    );
  }

  /**
   * Retrieve and decode the response.
   *
   * @param string $string
   *   A page title to seach for.
   *
   * @return array
   *   A decoded array of data about the page.
   *
   * @see https://www.mediawiki.org/wiki/API:Query
   * @see http://docs.guzzlephp.org/en/latest/quickstart.html
   */
  public function getResponse($string) {
    $uri = 'https://en.wikipedia.org/w/api.php';
    $props = ['extracts'];
    $query = [
      'action' => 'query',
      'format' => 'json',
      'prop' => implode('|', $props),
      'exintro' => '',
      'titles' => $string,
    ];
    // Show entire Body
    $props = ['text'];
    $query = [
      'action' => 'parse',
      'format' => 'json',
      'prop' => implode('|', $props),
      'page' => $string,
    ];

    // Search term in title
    $query = [
      'action' => 'query',
      'format' => 'json',
      'list' => 'search',
      'srprop' => 'snippet',
      'srsearch' => $string,
      'utf8' => '',
      'srlimit' => 50
    ];

    $options = ['query' => $query, 'http_errors' => FALSE];
    try {
      if ($response = $this->http_client->request('GET', $uri, $options)) {
        if ($data = $response->getBody()->getContents()) {
          $data = json_decode($data, TRUE);
          if (array_key_exists($query['action'], $data) && array_key_exists('search', $data[$query['action']])) {
            return $this->processResult($data[$query['action']]);
          }
        }
      }
    }
    catch (RequestException $e) {
      watchdog_exception('wikipedia_client', $e->getMessage());
    }
    return FALSE;
  }

  // /**
  //  * Helper to clean up the markup that is returned.
  //  */
  // public function clean($markup) {
  //   return trim(str_replace('<p>&nbsp;</p>', '', $markup));
  // }

  /**
   * Helper to get the extract of the Wikipedia page.
   */
  // public function getExtract($wiki_data) {
  //   if ($wiki_data) {
  //     $extract = reset($wiki_data['text']);
  //     $extract = $this->clean($extract);
  //     return $extract;
  //   }
  //   return '';
  // }

  // /**
  //  * Helper to get the link to the Wikipedia page.
  //  */
  // public function getLink($wiki_data) {
  //   if (array_key_exists('title', $wiki_data)) {
  //     $url = Url::fromUri('https://en.wikipedia.org/wiki/' . $wiki_data['title']);
  //     return $url->toString();
  //   }
  //   return '';
  // }

  /**
   * Helper to get the marked up extract and link from a Wikipedia page.
   */
  public function processResult($wiki_data) {
    return [
      'base' => 'https://en.wikipedia.org/wiki/',
      'total' => $wiki_data['searchinfo']['totalhits'],
      'result' => $wiki_data['search'],
    ];
    // return [
    //   'key' => $wiki_data['title'],
    //   'url' => $this->getLink($wiki_data),
    //   'content' => $this->getExtract($wiki_data)
    // ];
  }
}
