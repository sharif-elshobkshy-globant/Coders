<?php

namespace Drupal\wiki\Tests;
use Drupal\Tests\BrowserTestBase;
use Drupal\simpletest\WebTestBase;
use Drupal\Component\Utility\SafeMarkup;

/**
 * Tests if the search form block is available.
 *
 * @group search
 */
class WikiBlockTest extends BrowserTestBase {

  /**
   * Modules to enable.
   *
   * @var array
   */
  public static $modules = ['block'];

  protected function setUp() {
    parent::setUp();

    // Create and log in user.
    $admin_user = $this->drupalCreateUser(['administer blocks']);
    $this->drupalLogin($admin_user);
  }

  /**
   * Test that the search form block can be placed and works.
   */
  public function testWikiFormBlock() {

    // Test availability of the search block in the admin "Place blocks" list.
    $this->drupalGet('admin/structure/block');
    $block = $this->drupalPlaceBlock('wiki_block_form');

    $this->drupalGet('');
    $this->assertText($block->label(), 'Block title was found.');

    // Check that name attribute is not empty.
    $pattern = "//input[@type='submit' and @name='']";
    $elements = $this->xpath($pattern);
    $this->assertTrue(empty($elements), 'The search input field does not have empty name attribute.');

    // Test a normal search via the block form, from the front page.
    $terms = ['keys' => 'test'];
    $this->submitGetForm('', $terms, t('Search'));
    $this->assertResponse(200);
    $this->assertText('Your search yielded no results');

    // Test a search from the block on a 404 page.
    $this->drupalGet('foo');
    $this->assertResponse(404);
    $this->submitGetForm(NULL, $terms, t('Search'));
    $this->assertResponse(200);
    $this->assertText('Your search yielded no results');

    $visibility = $block->getVisibility();
    $visibility['request_path']['pages'] = 'search';
    $block->setVisibilityConfig('request_path', $visibility['request_path']);

    $this->submitGetForm('', $terms, t('Search'));
    $this->assertResponse(200);
    $this->assertText('Your search yielded no results');

    // Test an empty search via the block form, from the front page.
    $terms = ['keys' => ''];
    $this->submitGetForm('', $terms, t('Search'));
    $this->assertResponse(200);
    $this->assertText('Please enter some keywords');

    // Test that after entering a too-short keyword in the form, you can then
    // search again with a longer keyword. First test using the block form.
    $this->submitGetForm('node', ['keys' => $this->randomMachineName(1)], t('Search'));
    $this->assertText('You must include at least one keyword to match in the content', 'Keyword message is displayed when searching for short word');
    $this->assertNoText(t('Please enter some keywords'), 'With short word entered, no keywords message is not displayed');
    $this->submitGetForm(NULL, ['keys' => $this->randomMachineName()], t('Search'), 'search-block-form');
    $this->assertNoText('You must include at least one keyword to match in the content', 'Keyword message is not displayed when searching for long word after short word search');

  }


    /**
     * Simulates submission of a form using GET instead of POST.
     *
     * Forms that use the GET method cannot be submitted with
     * WebTestBase::drupalPostForm(), which explicitly uses POST to submit the
     * form. So this method finds the form, verifies that it has input fields and
     * a submit button matching the inputs to this method, and then calls
     * WebTestBase::drupalGet() to simulate the form submission to the 'action'
     * URL of the form (if set, or the current URL if not).
     *
     * See WebTestBase::drupalPostForm() for more detailed documentation of the
     * function parameters.
     *
     * @param string $path
     *   Location of the form to be submitted: either a Drupal path, absolute
     *   path, or NULL to use the current page.
     * @param array $edit
     *   Form field data to submit. Unlike drupalPostForm(), this does not support
     *   file uploads.
     * @param string $submit
     *   Value of the submit button to submit clicking. Unlike drupalPostForm(),
     *   this does not support AJAX.
     * @param string $form_html_id
     *   (optional) HTML ID of the form, to disambiguate.
     */
    protected function submitGetForm($path, $edit, $submit, $form_html_id = NULL) {
      if (isset($path)) {
        $this->drupalGet($path);
      }

      if ($this->parse()) {
        // Iterate over forms to find one that matches $edit and $submit.
        $edit_save = $edit;
        $xpath = '//form';
        if (!empty($form_html_id)) {
          $xpath .= "[@id='" . $form_html_id . "']";
        }
        $forms = $this->xpath($xpath);
        foreach ($forms as $form) {
          // Try to set the fields of this form as specified in $edit.
          $edit = $edit_save;
          $post = [];
          $upload = [];
          $submit_matches = $this->handleForm($post, $edit, $upload, $submit, $form);
          if (!$edit && $submit_matches) {
            // Everything matched, so "submit" the form.
            $action = isset($form['action']) ? $this->getAbsoluteUrl((string) $form['action']) : NULL;
            $this->drupalGet($action, ['query' => $post]);
            return;
          }
        }

        // We have not found a form which contained all fields of $edit and
        // the submit button.
        foreach ($edit as $name => $value) {
          $this->fail(SafeMarkup::format('Failed to set field @name to @value', ['@name' => $name, '@value' => $value]));
        }
        $this->assertTrue($submit_matches, format_string('Found the @submit button', ['@submit' => $submit]));
        $this->fail(format_string('Found the requested form fields at @path', ['@path' => $path]));
      }
    }

}
