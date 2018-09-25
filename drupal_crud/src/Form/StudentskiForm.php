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
class StudentskiForm extends FormBase {

    /**
     * {@inheritdoc}
     */
    public function getFormId() {
        return 'studentski_form';
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(array $form, FormStateInterface $form_state) {

        $conn = Database::getConnection();
        $record = array();
        if (isset($_GET['num'])) {
            $query = $conn->select('studentski', 'm')
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

        $form['program'] = array(
            '#type' => 'select',
            '#title' => t('Program'),
            '#options' => array(
                'Elektronske komunikacije' => t('Elektronske komunikacije'),
            ),
            '#required' => TRUE,
            '#default_value' => (isset($record['program']) && $_GET['num']) ? $record['program'] : '',
        );


        $form['modul'] = array(
            '#type' => 'select',
            '#title' => ('Modul'),
            '#options' => array(
                'Softversko inženjerstvo' => t('Softversko inženjerstvo'),
                'Mrežne tehnologije' => t('Mrežne tehnologije'),
                'Elektronsko poslovanje' => t('Elektronsko poslovanje'),
            ),
            '#required' => TRUE,
            '#default_value' => (isset($record['modul']) && $_GET['num']) ? $record['modul'] : '',
        );



        $form['rok'] = array(
            '#type' => 'select',
            '#title' => t('Ispitni Rok'),
            '#options' => array(
                'Decembar' => t('Decembar'),
                'Jun' => t('Jun'),
                'Jul' => t('Jul'),
                'Septembar' => t('Septembar'),
                'Oktobar' => t('Oktobar'),
            ),
            '#states' => array(
                'visible' => array(
                    ':input[name="modul"]' => array(
                        array('value' => t('Softversko inženjerstvo'))
                    ),
                ),
            ),
            '#required' => TRUE,
            '#default_value' => (isset($record['rok']) && $_GET['num']) ? $record['rok'] : '',
        );

        $form['ispit'] = array(
            '#type' => 'select',
            '#title' => t('Naziv ispitnog predmeta'),
            '#options' => array(
                'Web tehnologije' => t('Web Tehnologije'),
                'Napredno web programiranje' => t('Napredno web programiranje'),
                'Cloud programiranje' => t('Cloud programiranje'),
                'Programiranje baza podataka' => t('Programiranje baza podataka'),
                'Projektovanje softvera' => t('Projektovanje softvera'),
            ),
            '#states' => array(
                'visible' => array(
                    ':input[name="modul"]' => array(
                        array('value' => t('Softversko inženjerstvo'))
                    ),
                ),
            ),
            '#required' => TRUE,
            '#default_value' => (isset($record['rok']) && $_GET['num']) ? $record['rok'] : '',
        );

        $form['submit'] = [
            '#type' => 'submit',
            '#value' => 'Sačuvaj',
        ];

        return $form;
    }

    /**
     * {@inheritdoc}
     */
    public function validateForm(array &$form, FormStateInterface $form_state) {



        if (strlen($form_state->getValue('indeks')) < 3) {
            $form_state->setErrorByName('indeks', $this->t('Indeks mora sadržati najmanje tri broja'));
        }

        parent::validateForm($form, $form_state);
    }

    /**
     * {@inheritdoc}
     */
    public function submitForm(array & $form, FormStateInterface $form_state) {

        $field = $form_state->getValues();
        $imeiprezime = $field['imeiprezime'];
        $indeks = $field['indeks'];
        $program = $field['program'];
        $modul = $field['modul'];
        $rok = $field['rok'];
        $ispit = $field['ispit'];

        
//        $connection = Database::getConnection();
$hasIndeks = db_select('ispit', 'n')
                ->fields('n', ['indeks'])
                ->execute()
                ->fetchAll();


$hasIndeks2 = db_select('studentski', 'n')
                ->fields('n', ['indeks'])
                ->execute()
                ->fetchAll();

print_r($hasIndeks);
print_r($hasIndeks2);








$hasIndeks=json_decode(json_encode($hasIndeks),true);
print_r($hasIndeks);

$hasIndeks2=json_decode(json_encode($hasIndeks2),true);
print_r($hasIndeks2);




$result_array = array_intersect_assoc($hasIndeks, $hasIndeks2);
print_r($result_array);

if($result_array == TRUE){
print("Radi");
}else{
print("Ne radi");
}




//ne funkcionise

        if (isset($_GET['num']) && $result_array == TRUE){
            $field = array(
                'imeiprezime' => $imeiprezime,
                'indeks' => $indeks,
                'program' => $program,
                'modul' => $modul,
                'rok' => $rok,
                'ispit' => $ispit,
            );
            $query = \Drupal::database();
            $query->update('studentski')
                    ->fields($field)
                    ->condition('id', $_GET['num'])
                    ->execute();
            drupal_set_message("Uspešno ažurirano");
            $form_state->setRedirect('studentski.display_table_controller_display');
    } else {
            $field = array(
                'imeiprezime' => $imeiprezime,
                'indeks' => $indeks,
                'program' => $program,
                'modul' => $modul,
                'rok' => $rok,
                'ispit' => $ispit,
            );
            $query = \Drupal::database();
            $query->insert('studentski')
                    ->fields($field)
                    ->execute();
            drupal_set_message("Uspešno sačuvano");

            $form_state->setRedirect('studentski.display_table_controller_display');
        }
    }
    

}
