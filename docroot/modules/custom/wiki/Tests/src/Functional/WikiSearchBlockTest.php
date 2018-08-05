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
class WikiSearchBlockTest extends BrowserTestBase {

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
   * Test Wiki Search Block
   */
  public function testWikiSearchBlockTest() {
    // Select the 'Wiki Search Form' block to be configured and moved.
    $block = [];
    $block['id'] = 'wikisearchform';
    $block['settings[label]'] = $this->randomMachineName(8);
    $block['settings[label_display]'] = TRUE;
    $block['theme'] = $this->config('system.theme')->get('default');
    $block['region'] = 'header';

    // Set block title to confirm that interface works and override any custom titles.
    $this->drupalPostForm('admin/structure/block/add/' . $block['id'] . '/' . $block['theme'], ['settings[label]' => $block['settings[label]'], 'settings[label_display]' => $block['settings[label_display]'], 'id' => $block['id'], 'region' => $block['region']], t('Save block'));
    $this->assertText(t('The block configuration has been saved.'), 'Block title set.');
    // Check to see if the block was created by checking its configuration.
    $instance = Block::load($block['id']);

    $this->assertEqual($instance->label(), $block['settings[label]'], 'Stored block title found.');

    // Disable the block.
    $this->drupalGet('admin/structure/block');
    $this->clickLink('Disable');

    // Confirm that the block is now listed as disabled.
    $this->assertText(t('The block settings have been updated.'), 'Block successfully moved to disabled region.');

    // Confirm that the block instance title and markup are not displayed.
    $this->drupalGet('node');
    $this->assertNoText(t($block['settings[label]']));
    // Check for <div id="block-my-block-instance-name"> if the machine name
    // is my_block_instance_name.
    $xpath = $this->buildXPathQuery('//div[@id=:id]/*', [':id' => 'block-' . str_replace('_', '-', strtolower($block['id']))]);
    $this->assertNoFieldByXPath($xpath, FALSE, 'Block found in no regions.');
  }

}
