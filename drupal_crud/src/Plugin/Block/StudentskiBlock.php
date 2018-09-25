<?php

namespace Drupal\studentski\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a 'StudentskiBlock' block.
 *
 */
class StudentskiBlock extends BlockBase {

    /**
     * {@inheritdoc}
     */
    public function build() {

        $form = \Drupal::formBuilder()->getForm('Drupal\studentski\Form\StudentskiForm');

        return $form;
    }

}
