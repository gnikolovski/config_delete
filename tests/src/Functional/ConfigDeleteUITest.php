<?php

namespace Drupal\Tests\config_delete\Functional;

use Drupal\Tests\BrowserTestBase;

/**
 * Tests the user interface for deleting configuration.
 *
 * @group config_delete
 */
class ConfigDeleteUITest extends BrowserTestBase {

  /**
   * Modules to enable.
   *
   * @var array
   */
  public static $modules = ['automated_cron', 'config', 'config_delete'];

  /**
   * {@inheritdoc}
   */
  protected function setUp() {
    parent::setUp();

    $this->drupalLogin($this->drupalCreateUser(['delete configuration']));
  }

  /**
   * Tests form structure.
   */
  public function testFormStructure() {
    $this->drupalGet('admin/config/development/configuration/delete');
    $this->assertSession()->statusCodeEquals(200);
    $this->assertSession()->titleEquals('Delete | Drupal');
    $this->assertSession()->selectExists('edit-config-type');
    $this->assertSession()->selectExists('edit-config-name');
    $this->assertSession()->buttonExists(t('Delete'));
  }

  /**
   * Tests config delete.
   */
  public function testConfigDeletion() {
    $config = $this->config('automated_cron.settings');
    $this->assertNotNull($config->get('interval'));
    $form_values = [
      'config_type' => 'system.simple',
      'config_name' => 'automated_cron.settings',
    ];
    $this->drupalPostForm('admin/config/development/configuration/delete', $form_values, 'Delete');
    $this->assertSession()->pageTextContains(t('Configuration "automated_cron.settings" successfully deleted.'));
    $config = $this->config('automated_cron.settings');
    $this->assertFalse($config->get('interval'));
  }

  /**
   * Tests form access.
   */
  public function testFormAccess() {
    $this->drupalLogout();
    $this->drupalGet('admin/config/development/configuration/delete');
    $this->assertSession()->statusCodeEquals(403);
  }

}
