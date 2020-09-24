<?php

namespace Granada\Form;

class FormField {

    private $item;
    private $type;
    private $name;
    private $value;
    private $tags;
    private $label;
    private $select_options = [];
    private $helptext;
    private $length;
    private $required;
    private $content;
    /**
     * @var \Granada\Form\Form $form
     */
    private $form;

    public function __construct($formclass) {
        $this->form = new $formclass;
    }

    public function setItem($data) {
        $this->item = $data;
        return $this;
    }

    public function setType($data) {
        $this->type = $data;
        return $this;
    }

    public function setName($data) {
        $this->name = $data;
        return $this;
    }

    public function setValue($data) {
        $this->value = $data;
        return $this;
    }

    public function setTags($data) {
        $this->tags = $data;
        return $this;
    }

    public function setLabel($data) {
        $this->label = $data;
        return $this;
    }

    public function setHelptext($data) {
        $this->helptext = $data;
        return $this;
    }

    public function setLength($data) {
        $this->length = $data;
        return $this;
    }

    public function setSelectOptions($data) {
        $this->select_options = $data;
        return $this;
    }

    public function setRequired($data) {
        $this->required = $data;
        return $this;
    }

    public function setContent($data) {
        $this->content = $data;
        return $this;
    }

    public function renderSelectOptions() {
        ob_start();
        foreach ($this->select_options as $value => $text) {
?>
            <option value="<?= $value ?>" <?php if ($value == $this->value) { ?> selected <?php } ?>><?= $text ?></option>
<?php
        }
        return ob_get_clean();
    }

    public function render() {
        if ($this->content) {
            return $this->content;
        }
        $twig = new \Twig\Environment(new \Twig\Loader\ArrayLoader(array(
            'template' => $this->form->fieldTemplate($this->type, $this->length, $this->tags, $this->item, $this->name),
        )));
        return $twig->render('template', array(
            'value' => $this->value,
            'label' => $this->label,
            'label_for' => $this->name,
            'help' => $this->helptext,
            'name' => $this->name,
            'length' => $this->length,
            'options' => $this->renderSelectOptions(),
            'required' => $this->required,
            'readonly' => in_array('readonly', $this->tags ?: []) ? 'readonly' : '',
        ));
    }
}
