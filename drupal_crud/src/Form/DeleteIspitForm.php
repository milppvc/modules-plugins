<?php

namespace Drupal\studentski\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Form\ConfirmFormBase;
use Drupal\Core\Url;
use Drupal\Core\Render\Element;

/**
 * Class DeleteForm.
 *
 * @package Drupal\studentski\Form
 */
class DeleteIspitForm extends ConfirmFormBase {

    /**
     * {@inheritdoc}
     */
    public function getFormId() {
        return 'delete_form';
    }

    public $cid;

    public function getQuestion() {
        return t('Da li želite da izbrišete %cid?', array('%cid' => $this->cid));
    }

    public function getCancelUrl() {
        return new Url('studentski.display_ispit_controller_display');
    }

    public function getDescription() {
        return t('Da li ste sigurni!');
    }

    /**
     * {@inheritdoc}
     */
    public function getConfirmText() {
        return t('Izbriši!');
    }

    /**
     * {@inheritdoc}
     */
    public function getCancelText() {
        return t('Otkaži');
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(array $form, FormStateInterface $form_state, $cid = NULL) {

        $this->id = $cid;
        return parent::buildForm($form, $form_state);
    }

    /**
     * {@inheritdoc}
     */
    public function validateForm(array &$form, FormStateInterface $form_state) {
        parent::validateForm($form, $form_state);
    }

    /**
     * {@inheritdoc}
     */
    public function submitForm(array & $form, FormStateInterface $form_state) {

        $query = \Drupal::database();
        //echo $this->id; die;
        $query->delete('ispit')
                //->fields($field)
                ->condition('id', $this->id)
                ->execute();
        //if($query == TRUE){
        drupal_set_message("uspešno izbrisano");

        $form_state->setRedirect('studentski.display_ispit_controller_display');
    }

}
