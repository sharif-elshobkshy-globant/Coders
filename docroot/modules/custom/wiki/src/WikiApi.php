<?php

namespace Drupal\wiki;

use Drupal\Core\Link;
use Drupal\Core\Url;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\ClientInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class WikiApi {

  /**
   * Http client.
   *
   * @var \GuzzleHttp\ClientInterface
   */
  protected $http_client;

  /**
   * Http client..
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
   * Fetch results from resource
   *
   * @param string $string
   *   Search articles with this string.
   *
   * @return array
   *   List of pages containing string.
   *
   */
  public function getResponse($string, $limit = 150) {
    $uri = 'https://en.wikipedia.org/w/api.php';
    // Search term in title
    $query = [
      'action' => 'query',
      'format' => 'json',
      'list' => 'search',
      'srprop' => 'snippet',
      'srsearch' => $string,
      'utf8' => '',
      'srlimit' => $limit
    ];
    $options = ['query' => $query, 'http_errors' => FALSE];
    try {
      $response = $this->http_client->request('GET', $uri, $options);
      if ($response) {
        $data = $response->getBody()->getContents();
        if ($data) {
          $data = json_decode($data, TRUE);
          if (array_key_exists('query', $data) && array_key_exists('search', $data['query'])) {
            return $this->processResult($data['query']);
          }
        }
      }
    }
    catch (RequestException $e) {
      watchdog_exception('wiki', $e->getMessage());
    }
    return FALSE;
  }

  /**
   * Process result fetched from wikipedia.
   */
  public function processResult($wiki_data) {
    return [
      'base' => 'https://en.wikipedia.org/wiki/',
      'total' => $wiki_data['searchinfo']['totalhits'],
      'result' => $wiki_data['search'],
    ];
  }
}
