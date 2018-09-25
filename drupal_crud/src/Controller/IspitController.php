<?php

namespace Drupal\studentski\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Class StudentskiController.
 *
 * @package Drupal\studentski\Controller
 */
class IspitController extends ControllerBase {

    /**
     * 
     *
     * @return string
     *   
     */
    public function display() {
        return [
            '#type' => 'markup',
            '#markup' => $this->t('Ova stranica sadrži sve informacije o modulu ')
        ];
    }

}
