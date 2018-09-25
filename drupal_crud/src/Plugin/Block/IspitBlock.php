<?php

namespace Drupal\studentski\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Omogucavamo da se forma doda u blok
 *
 */
class IspitBlock extends BlockBase {

    /**
     * {@inheritdoc}
     */
    public function build() {

        $form = \Drupal::formBuilder()->getForm('Drupal\studentski\Form\IspitForm');

        return $form;
    }

}
