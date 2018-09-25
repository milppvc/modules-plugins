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
class DisplayTableController extends ControllerBase {

    public function getContent() {
        // Sadržaj se nalazi u .twig fajlu: templates/description.html.twig.
        // @todo: Pravimo linkove za node i ukazujemo na devel module.
        $build = [
            'description' => [
                '#theme' => 'studentski_description',
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
        

        //pravimo header
        $header_table = array(
            'id' => t('ID'),
            'imeiprezime' => t('Ime i prezime'),
            'indeks' => t('Indeks'),
            'program' => t('Program'),
            'modul' => t('Modul'),
            'rok' => t('Rok'),
            'ispit' => t('Ispit'),
            'opt' => t('Operacija'),
            'opt1' => t('Operacija'),
        );

//biramo podatke iz tabele
        $query = \Drupal::database()->select('studentski', 'm');
        $query->fields('m', ['id', 'imeiprezime', 'indeks', 'program', 'modul', 'rok', 'ispit']);
        $results = $query->execute()->fetchAll();
        $rows = array();
        foreach ($results as $data) {
            $delete = Url::fromUserInput('/studentski/form/delete/' . $data->id);
            $edit = Url::fromUserInput('/studentski/form/studentski?num=' . $data->id);

            //print the data from table
            $rows[] = array(
                'id' => $data->id,
                'imeiprezime' => $data->imeiprezime,
                'indeks' => $data->indeks,
                'program' => $data->program,
                'modul' => $data->modul,
                'rok' => $data->rok,
                'ispit' => $data->ispit,
                \Drupal::l('Izbriši', $delete),
                \Drupal::l('Izmeni', $edit),
            );
        }
        //prikazujemo podatke iz tabele
        $form['table'] = [
            '#type' => 'table',
            '#header' => $header_table,
            '#rows' => $rows,
            '#empty' => t('Nijedan prijavljen ispit nije pronađen'),
        ];
       
        return $form;
    }

}
