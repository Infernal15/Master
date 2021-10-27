<?php

namespace Drupal\stepcommerce\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\Plugin\Field\FieldFormatter\EntityReferenceFormatterBase;

/**
 * Plugin implementation of the 'Random_default' formatter.
 *
 * @FieldFormatter(
 *   id = "stepcommerce_rest_paragraphs_formatter",
 *   label = @Translation("Rest paragraphs"),
 *   field_types = {
 *     "entity_reference_revisions"
 *   }
 * )
 */
class ParagraphFormatter extends EntityReferenceFormatterBase {

  public function viewElements(FieldItemListInterface $items, $langcode) {
    $data = [];

    foreach ($this->getEntitiesToView($items, $langcode) as $delta => $entity) {
      $data[$delta] = [
        '#theme' => 'stepcommerce_rest_paragraphs_formatter',
        '#name' => $entity->field_name->value,
        '#value' => $entity->field_value->value
      ];
    }

    return $data;
  }

}
