<?php

namespace Drupal\wiki\Plugin\Block;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Block\BlockBase;

/**
 * Provides a 'Wiki Search form' block.
 *
 * @Block(
 *   id = "wiki_search_form_block",
 *   admin_label = @Translation("Wiki Search form"),
 *   category = @Translation("Forms")
 * )
 */
class WikiSearchBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  protected function blockAccess(AccountInterface $account) {
    return AccessResult::allowedIfHasPermission($account, 'search content');
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    return \Drupal::formBuilder()->getForm('Drupal\wiki\Form\WikiBlockForm');
  }

}
