<?php

namespace Drupal\mongo_migration\Form;

use Drupal\Core\Entity\ContentEntityForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * Form controller for the custom migration mongo entity edit forms.
 */
class CustomMigrationMongoForm extends ContentEntityForm {

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    $result = parent::save($form, $form_state);

    $entity = $this->getEntity();

    $message_arguments = ['%label' => $entity->toLink()->toString()];
    $logger_arguments = [
      '%label' => $entity->label(),
      'link' => $entity->toLink($this->t('View'))->toString(),
    ];

    switch ($result) {
      case SAVED_NEW:
        $this->messenger()->addStatus($this->t('New custom migration mongo %label has been created.', $message_arguments));
        $this->logger('mongo_migration')->notice('Created new custom migration mongo %label', $logger_arguments);
        break;

      case SAVED_UPDATED:
        $this->messenger()->addStatus($this->t('The custom migration mongo %label has been updated.', $message_arguments));
        $this->logger('mongo_migration')->notice('Updated custom migration mongo %label.', $logger_arguments);
        break;
    }

    $form_state->setRedirect('entity.mongo_migration.canonical', ['mongo_migration' => $entity->id()]);

    return $result;
  }

}
