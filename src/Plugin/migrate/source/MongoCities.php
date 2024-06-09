<?php

namespace Drupal\mongo_migration\Plugin\migrate\source;

use Drupal\migrate\Plugin\migrate\source\SourcePluginBase;
use Drupal\migrate\Plugin\MigrationInterface;
use Drupal\migrate\Row;
use MongoDB\Client;
use MongoDB\Model\BSONArray;

/**
 * Source plugin for MongoDB cities collection.
 *
 * @MigrateSource(
 *   id = "mongo_cities"
 * )
 */
class MongoCities extends SourcePluginBase {

  protected $client;
  protected $database;
  protected $collection;

  public function __construct(array $configuration, $plugin_id, $plugin_definition, MigrationInterface $migration) {
    parent::__construct($configuration, $plugin_id, $plugin_definition, $migration);
    $this->client = new Client('mongodb+srv://muneebkt:2ym0WycGK0yQPLDO@cluster0.nfbrutb.mongodb.net', [], []);
    $this->collection = $this->client->cities_db->cities;

  }

  /**
   *
   */
  public function initializeIterator() {
    $cursor = $this->collection->find();
    $documents = [];
    foreach ($cursor as $document) {
      $documents[] = (array) $document;
    }
    return new \ArrayIterator($documents);
  }

  /**
   *
   */
  public function fields() {
    return [
      '_id' => $this->t('ID'),
      'city' => $this->t('City'),
      'loc' => $this->t('Location'),
      'pop' => $this->t('Population'),
      'state' => $this->t('State'),
    ];
  }

  /**
   *
   */
  public function getIds() {
    return [
      '_id' => [
        'type' => 'string',
        'alias' => '_id',
      ],
    ];
  }

  /**
   *
   */
  public function prepareRow(Row $row) {
    $loc = $row->getSourceProperty('loc');
    // Check if $loc is an instance of BSONArray.
    if ($loc instanceof BSONArray) {
      $locArray = $loc->getArrayCopy();
      if (is_array($locArray) && count($locArray) == 2) {
        $row->setSourceProperty('latitude', $locArray[0]);
        $row->setSourceProperty('longitude', $locArray[1]);
      }
    }
    return parent::prepareRow($row);
  }

  /**
   *
   */
  public function __toString() {
    return 'MongoCities';
  }

}
