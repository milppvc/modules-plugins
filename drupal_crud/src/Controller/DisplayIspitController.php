<?php

namespace Drupal\studentski\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Database\Database;
use Drupal\Core\Url;

/**
 * Class DisplayTableController.
 *
 * @package Drupal\studentski\Controller
 */
class DisplayIspitController extends ControllerBase {

    public function getContent() {
        // Sadržaj se nalazi u .twig fajlu: templates/description.html.twig.
        // @todo: Pravimo linkove za node i ukazujemo na devel module.
        $build = [
            'description' => [
                '#theme' => 'ispit_description',
                '#description' => 'foo',
                '#attributes' => [],
            ],
        ];
        return $build;
    }

    /**
     * 
     *
     * @return string
     *   
     */
    public function display() {
        

        //create table header
        $header_table = array(
            'id' => t('ID'),
            'imeiprezime' => t('Ime i prezime'),
            'indeks' => t('Indeks'),
            'ispit' => t('Ispit'),
            'predispitne' => t('Predispitne'),
            'opt' => t('Operacija'),
            'opt1' => t('Operacija'),
        );

//select records from table
        $query = \Drupal::database()->select('ispit', 'm');
        $query->fields('m', ['id', 'imeiprezime', 'indeks', 'ispit', 'predispitne']);
        $results = $query->execute()->fetchAll();
        $rows = array();
        foreach ($results as $data) {
            $delete = Url::fromUserInput('/studentski/form/delete/' . $data->id);
            $edit = Url::fromUserInput('/studentski/form/ispit?num=' . $data->id);

            //print the data from table
            $rows[] = array(
                'id' => $data->id,
                'imeiprezime' => $data ->imeiprezime,
                'indeks' => $data->indeks,
                'ispit' => $data->ispit,
                'predispitne' => $data->predispitne,
                \Drupal::l('Izbriši', $delete),
                \Drupal::l('Izmeni', $edit),
            );
        }
        //display data in site
        $form['table'] = [
            '#type' => 'table',
            '#header' => $header_table,
            '#rows' => $rows,
            '#empty' => t('Nijedan prijavljen ispit nije pronađen'),
        ];
//        echo '<pre>';print_r($form['table']);exit;
        return $form;
    }

}
