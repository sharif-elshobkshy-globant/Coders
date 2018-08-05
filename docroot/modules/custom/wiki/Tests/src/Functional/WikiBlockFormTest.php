<?php
namespace Drupal\wiki\Tests\Functional;

use Drupal\Core\Url;
use Drupal\Tests\BrowserTestBase;
use Drupal\simpletest\WebTestBase;
use Drupal\Component\Utility\SafeMarkup;
use Drupal\Component\Utility\Html;
use Drupal\block\Entity\Block;
use Drupal\user\Entity\Role;
use Drupal\user\RoleInterface;

/**
 * Tests Wiki Search Block
 *
 * @group wiki
 */
class WikiBlockFormTest extends BrowserTestBase {

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
   * Test Wiki Search Block
   */
  public function testBuildForm() {
    $form = \Drupal::formBuilder()->getForm('Drupal\wiki\Form\WikiBlockForm');

    // Test ID and validate field type
    $this->assertEqual($form['#form_id'], 'wiki_block_form', 'Form ID does not match expected.');
    $this->assertEqual($form['search']['#type'], 'textfield', 'Search field type does not match expected.');
  }

}
