<?php

namespace Granada\Form;

class Materialize extends FormField {

    public function renderInput() {
        ob_start();
?>
        <div class="row">
            <div class="input-field col s12">
                <?php
                if ($this->type == 'submit') {
                    echo $this->submitField(array(
                        'class' => 'btn',
                    ));
                } else if ($this->type == 'date') {
                    echo $this->dateField(array(
                        'class' => 'validate',
                    ));
                } else if ($this->type == 'time') {
                    echo $this->timeField(array(
                        'class' => 'validate',
                    ));
                } else if ($this->type == 'datetime') {
                    echo $this->hiddenField(array(
                        'placeholder' => 'xxxx',
                        'class' => 'validate',
                    ));
                ?>
                    <div class="row">
                        <div class="col s6">
                            <?= $this->dateField(array(
                                'id' => $this->input_id . 'd',
                                'class' => 'validate',
                            )) ?>
                        </div>
                        <div class="col s6">
                            <?= $this->timeField(array(
                                'id' => $this->input_id . 't',
                                'class' => 'validate',
                            )) ?>
                        </div>
                    </div>
                <?php
                } else if ($this->type == 'color') {
                    echo $this->colorField(array(
                        'class' => 'validate',
                    ));
                } else if ($this->type == 'email') {
                    echo $this->emailField(array(
                        'class' => 'validate',
                    ));
                } else if ($this->type == 'phone') {
                    echo $this->telField(array(
                        'class' => 'validate',
                    ));
                } else if ($this->type == 'bool') {
                ?>
                    <label><?= $this->checkboxField() ?><span><?= $this->label ?></span></label>
                <?php
                } else if ($this->type == 'booltristate') {
                    echo $this->selectField();
                } else if ($this->type == 'reference') {
                    echo $this->selectField();
                } else if ($this->type == 'enum') {
                    echo $this->selectField();
                } else if ($this->length > 255 || $this->length == 0 || $this->type == 'text') {
                    echo $this->textareaField(array(
                        'class' => 'materialize-textarea',
                    ));
                } else {
                    echo $this->textField(array(
                        'class' => 'validate',
                    ));
                }
                ?>
                <?php
                if ($this->type != 'bool') {
                    ob_start();
                    echo $this->label;
                    if ($this->helptext) {
                        echo $this->tag('i', array(
                            'class' => 'fas fa-question-circle',
                            'title' => $this->helptext,
                        ), 'long');
                    }
                    echo $this->tag('label', array('for' => $this->input_id), 'long', ob_get_clean());
                }
                ?>
            </div>
        </div>
    <?php
        return ob_get_clean();
    }

}
