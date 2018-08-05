<?php
namespace Drupal\wiki\Tests\Functional;

use Drupal\Core\Url;
use Drupal\Tests\BrowserTestBase;
use Drupal\simpletest\WebTestBase;
use Drupal\Component\Utility\SafeMarkup;

/**
 * Tests Wiki API responses.
 *
 * @group wiki
 */
class WikiAPITest extends BrowserTestBase {

  /**
   * Modules to enable.
   *
   * @var array
   */
  public static $modules = ['wiki'];

  protected function setUp() {
    parent::setUp();

    // Create and log in user.
    $admin_user = $this->drupalCreateUser(['administer blocks']);
    $this->drupalLogin($admin_user);
  }

  /**
   * Test that proper response structure is returned wikiApi Service
   */
  public function testGetResponseResult() {
    $client = \Drupal::service('wiki.wikiApi');
    $result = $client->getResponse('brad', 2);

    // Test response from wikipedia.
    $this->assertNotNull($result, 'Failed to test wikipedia response.');
    $this->assertNotNull($result['result'], 'Failed to test wikipedia results response.');
    $this->assertEqual(count($result['result']), 2, 'Result not expected. Check connection with Wiki API');
  }

  /**
   * Test that proper response structure is returned wikiApi Service
   */
  public function testGetResponseNoResult() {
    $client = \Drupal::service('wiki.wikiApi');
    $result = $client->getResponse('zxzxzxzxzx', 2);

    // Test response from wikipedia.
    $this->assertTrue(empty($result['result']), 'Failed to test wikipedia no results response.');
  }

  /**
   * Test the proccessResult function returns expected array structure.
   */
  public function testProcessResult() {
    $client = \Drupal::service('wiki.wikiApi');
    $data = [
      'searchinfo' => array('totalhits' => 54768),
      'search' => array(
        array(
          'ns' => 0,
          'title' => 'Test',
          'pageid' => 11089416,
          'snippet' => '<span>Test snippet</span>'
        )
      )
    ];
    $expected = [
      'base' => 'https://en.wikipedia.org/wiki/',
      'total' => 54768,
      'result' => array(
        array(
          'ns' => 0,
          'title' => 'Test',
          'pageid' => 11089416,
          'snippet' => '<span>Test snippet</span>'
        )
      )
    ];
    $result = $client->processResult($data);

    // Test response from wikipedia.
    $this->assertEqual($result, $expected, 'Process response different from expexted.');
  }

}
