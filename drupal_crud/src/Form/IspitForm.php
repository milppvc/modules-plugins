<?php

namespace Drupal\studentski\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Database\Database;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * Class StudentskiForm.
 *
 * @package Drupal\studentski\Form
 */
class IspitForm extends FormBase {

    /**
     * {@inheritdoc}
     */
    public function getFormId() {
        return 'ispit_form';
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(array $form, FormStateInterface $form_state) {

        $conn = Database::getConnection();
        $record = array();
        if (isset($_GET['num'])) {
            $query = $conn->select('ispit', 'm')
                    ->condition('id', $_GET['num'])
                    ->fields('m');
            $record = $query->execute()->fetchAssoc();
        }
        
       

        $form['imeiprezime'] = array(
            '#type' => 'textfield',
            '#title' => t('Ime i prezime studenta:'),
            '#required' => TRUE,
            '#default_value' => (isset($record['imeiprezime']) && $_GET['num']) ? $record['imeiprezime'] : '',
        );
        
         $form['indeks'] = array(
            '#type' => 'number',
            '#title' => t('Broj Indeksa:'),
            '#required' => TRUE,
            '#default_value' => (isset($record['indeks']) && $_GET['num']) ? $record['indeks'] : '',
        );
        
        $form['ispit'] = array(
            '#type' => 'textfield',
            '#title' => t('Naziv ispitnog predmeta'),
            '#required' => TRUE,
            '#default_value' => (isset($record['ispit']) && $_GET['num']) ? $record['ispit'] : '',
        );
        
        $form['predispitne'] = array(
            '#type' => 'select',
            '#title' => t('Predispitne:'),
            '#options' => array(
                'Da' => t('Da'),
                'Ne' => t('Ne'),
            ),
            '#required' => TRUE,
            '#default_value' => (isset($record['predispitne']) && $_GET['num']) ? $record['predispitne'] : '',
        );

        $form['submit'] = [
            '#type' => 'submit',
            '#value' => 'Sačuvaj',
                //'#value' => t('Submit'),
        ];

        return $form;
    }

    /**
     * {@inheritdoc}
     */
    
    /**
     * {@inheritdoc}
     */
    public function validateForm(array &$form, FormStateInterface $form_state) {



        if (strlen($form_state->getValue('indeks')) < 3) {
            $form_state->setErrorByName('indeks', $this->t('Indeks mora sadržati najmanje tri broja'));
        }

        parent::validateForm($form, $form_state);
    }
    
    public function submitForm(array & $form, FormStateInterface $form_state) {

        $field = $form_state->getValues();
        $imeiprezime = $field['imeiprezime'];
        $indeks = $field['indeks'];
        $ispit = $field['ispit'];
        $predispitne = $field['predispitne'];

        

        if (isset($_GET['num'])) {
            $field = array(
                'imeiprezime' => $imeiprezime,
                'indeks' => $indeks,
                'ispit' => $ispit,
                'predispitne' => $predispitne,
            );
            $query = \Drupal::database();
            $query->update('ispit')
                    ->fields($field)
                    ->condition('id', $_GET['num'])
                    ->execute();
            drupal_set_message("Uspešno ažurirano");
            $form_state->setRedirect('studentski.display_ispit_controller_display');
        } else {
            $field = array(
                'imeiprezime' => $imeiprezime,
                'indeks' => $indeks,
                'ispit' => $ispit,
                'predispitne' => $predispitne,
            );
            $query = \Drupal::database();
            $query->insert('ispit')
                    ->fields($field)
                    ->execute();
            drupal_set_message("Uspešno sačuvano");

            $form_state->setRedirect('studentski.display_ispit_controller_display');
        }

    }

}
