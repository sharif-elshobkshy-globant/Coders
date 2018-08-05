<?php
namespace Drupal\wiki\Tests\Functional;

use Drupal\Core\Url;
use Drupal\Tests\BrowserTestBase;
use Drupal\simpletest\WebTestBase;
use Drupal\wiki\Controller\WikiController;

/**
 * Tests Wiki Controller
 *
 * @group wiki
 */
class WikiControllerTest extends BrowserTestBase {

  /**
   * Modules to enable.
   *
   * @var array
   */
  public static $modules = ['wiki'];

  protected function setUp() {
    parent::setUp();
  }

  /**
   * Test Wiki Main Render
   */
  public function testMainRender() {
    $key = 'brad';
    $controller = new WikiController();
    $result = $controller->mainRender($key);

    // Test response from Controller.
    $this->assertNotNull($result, 'Failed testing result from main render.');
    $this->assertNotNull($result['#theme'], 'Failed testing result from main render.');
    $this->assertEqual($result['#theme'], 'wiki_page', 'Result not expected. Should return theme name.');
    $this->assertEqual($result['#parameters']['key'], $key, 'Not returning searched key.');
  }

  /**
   * Test Wiki Main Render
   */
  public function testMainRenderNoResult() {
    $controller = new WikiController();
    $result = $controller->mainRender();

    // Test empty response from Controller.
    $this->assertEqual($result, array(), 'Empty response expected when no search key provided.');
  }


}
