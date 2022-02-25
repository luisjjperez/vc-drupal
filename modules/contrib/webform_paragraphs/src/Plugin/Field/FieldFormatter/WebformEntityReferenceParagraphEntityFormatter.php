<?php

namespace Drupal\webform_paragraphs\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\webform\Plugin\Field\FieldFormatter\WebformEntityReferenceEntityFormatter;

/**
 * Plugin implementation of the 'Webform rendered entity' formatter, in the
 * context of a paragraph. This is very useful for the webforms which are
 * rendered in rendered in the event nodes.
 *
 * @FieldFormatter(
 *   id = "webform_entity_reference_paragraph_entity_view",
 *   label = @Translation("Webform in Paragraph context"),
 *   description = @Translation("Display the referenced webform with default submission data, in a paragraph context."),
 *   field_types = {
 *     "webform"
 *   }
 * )
 */
class WebformEntityReferenceParagraphEntityFormatter extends WebformEntityReferenceEntityFormatter {

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $source_entity = $items->getEntity();
    $this->messageManager->setSourceEntity($source_entity);

    $elements = [];
    foreach ($this->getEntitiesToView($items, $langcode) as $delta => $entity) {
      /** @var \Drupal\webform\WebformInterface $entity */

      // Do not display the webform if the current user can't create submissions.
      if ($entity->id() && !$entity->access('submission_create')) {
        continue;
      }

      if ($entity->id() && $items[$delta]->status) {
        // If we are in the context of a paragraph, we add its id to the
        // webform submission form.
        $values = [];
        if ($source_entity->getEntityTypeId() == 'paragraph') {
          $values['paragraph_id'] = $source_entity->id();
        }
        $elements[$delta] = $entity->getSubmissionForm($values);
      }
      else {
        $this->messageManager->setWebform($entity);
        $elements[$delta] = $this->messageManager->build(WebformMessageManagerInterface::FORM_CLOSED_MESSAGE);
      }
    }

    return $elements;
  }
}
