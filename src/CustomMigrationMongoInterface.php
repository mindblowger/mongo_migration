<?php

namespace Drupal\mongo_migration;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface defining a custom migration mongo entity type.
 */
interface CustomMigrationMongoInterface extends ContentEntityInterface, EntityOwnerInterface, EntityChangedInterface {

}
