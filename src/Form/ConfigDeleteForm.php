<?php

namespace Drupal\config_delete\Form;

use Drupal\config\Form\ConfigSingleExportForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * Provides a form for deleting a single configuration file.
 *
 * @internal
 */
class ConfigDeleteForm extends ConfigSingleExportForm {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'config_delete_form';
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $config_type = $form_state->getValue('config_type');
    $config_name = $form_state->getValue('config_name');

    if ($form_state->getValue('config_type') !== 'system.simple') {
      $definition = $this->entityManager->getDefinition($config_type);
      $name = $definition->getConfigPrefix() . '.' . $config_name;
    }
    else {
      $name = $config_name;
    }

    \Drupal::configFactory()->getEditable($name)
      ->delete();

    drupal_set_message(t('Configuration "@config_name" successfully deleted.',
      ['@config_name' => $name])
    );
  }

}
