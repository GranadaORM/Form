<?php

namespace Granada\Form;

/**
 * @property \Granada\Form\Form $form
 */
class FormField {

    public $item;
    protected $type;
    protected $name;
    protected $value;
    protected $tags;
    protected $label;
    protected $input_id;
    protected $select_options = [];
    protected $helptext;
    protected $length;
    protected $required;
    protected $content;
    protected $htmlOptions = [];
    protected $form;

    public function __construct($form = null) {
        $this->form = $form;
        $this->input_id = uniqid('ff');
    }

    public function setForm($data) {
        $this->form = $data;
        return $this;
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

    public function setHtmlOptions($data) {
        $this->htmlOptions = $data;
        return $this;
    }

    public function setOverrides($data) {
        if (is_array($data)) {
            foreach ($data as $var => $val) {
                $this->$var = $val;
            }
        }
        return $this;
    }

    public function renderSelectOptions() {
        ob_start();
        foreach ($this->select_options as $value => $text) {
            echo $this->tag('option', array(
                'value' => $value,
                'selected' => ($value == $this->value ? 'selected' : ''),
            ), 'long', $text);
        }
        return ob_get_clean();
    }

    public function render() {
        if ($this->content) {
            return $this->content;
        }
        return $this->tag('span', array(
            'class' => 'vf-field',
            'data-name' => $this->name,
        ), 'long', $this->renderInput());
    }

    /**
     * Output an opening form tag
     *
     * @param string $action
     * @param string $method
     * @param array $htmlOptions
     * @return string
     */
    public function beginForm($action = '', $method = 'post', $htmlOptions = array()) {
        return $this->tag('form', array_merge(array(
            'action' => $action,
            'method' => $method,
        ), $htmlOptions), 'none') . $this->tag('input', array(
            'type' => 'hidden',
            'name' => 'formdata',
            'class' => 'formdata',
        ));
    }

    /**
     * Output the end of the form
     *
     * @return string
     */
    public function endForm() {
        return '</form>';
    }

    /**
     * @param string $type The tag type, e.g input or a
     * @param array $htmlOptions list of pairs of values
     * @param string $close 'short', 'long' or 'none' for closing type
     * @param string $contents What to put between open and close tags if it is a long close type
     * @return string
     */
    public function tag($type, $htmlOptions, $close = 'short', $contents = '') {
        ob_start();
        echo '<';
        echo $type;
        foreach ($htmlOptions as $name => $value) {
            if ($value === '') {
                if ($name != 'value') {
                    continue;
                }
            }
            echo ' ';
            if ($name == 'custom') {
                echo $value;
            } else {
                echo $name;
                echo '="';
                if ($name == 'id') {
                    echo $this->slug($value);
                } else {
                    echo htmlentities($value);
                }
                echo '"';
            }
        }
        if ($close == 'short') {
            echo ' /';
        }
        if ($close == 'long') {
            echo '>';
            echo $contents;
            echo '</';
            echo $type;
        }
        echo '>';
        return ob_get_clean();
    }

    /**
     * Get a slug for a string
     *
     * @param string $string
     * @return string
     */
    public function slug($string) {
        // Trim and to lower
        $slug = strtolower(trim($string));
        // Remove non-chars
        $slug = preg_replace("/[^a-zA-Z0-9\/_|+ -]/", '', $slug);
        // Replace separators with dash
        $slug = preg_replace("/[\/_|+ -]+/", '-', $slug);
        return $slug;
    }

    /**
     * Does the column have this comment tag
     * @param string $tag
     * @return bool
     */
    public function hasTag($tag) {
        if (!is_array($this->tags)) {
            return false;
        }
        return in_array($tag, $this->tags);
    }

    /**
     * Insert a text input field
     *
     * @param array $overrides
     * @return string
     */
    public function textField($overrides = array()) {
        return $this->inputField('text', $overrides);
    }

    /**
     * Insert a date input field
     *
     * @param array $overrides
     * @return string
     */
    public function dateField($overrides = array()) {
        return $this->inputField('date', $overrides);
    }

    /**
     * Insert a time input field
     *
     * @param array $overrides
     * @return string
     */
    public function timeField($overrides = array()) {
        return $this->inputField('time', $overrides);
    }

    /**
     * Insert a email input field
     *
     * @param array $overrides
     * @return string
     */
    public function emailField($overrides = array()) {
        return $this->inputField('email', $overrides);
    }

    /**
     * Insert a color input field
     *
     * @param array $overrides
     * @return string
     */
    public function colorField($overrides = array()) {
        return $this->inputField('color', $overrides);
    }

    /**
     * Insert a tel input field
     *
     * @param array $overrides
     * @return string
     */
    public function telField($overrides = array()) {
        return $this->inputField('tel', $overrides);
    }

    /**
     * Insert a textarea field
     *
     * @param array $overrides
     * @return string
     */
    public function textareaField($overrides = array()) {
        return $this->tag('textarea', $this->fieldOptions(array(
            'id' => $this->input_id,
            'data-name' => $this->name,
            'required' => $this->required ? 'required' : '',
        ), $overrides), 'long', $this->value);
    }

    /**
     * Insert a hidden input field
     *
     * @param array $overrides
     * @return string
     */
    public function hiddenField($overrides = array()) {
        return $this->inputField('hidden', $overrides);
    }

    /**
     * Insert a checkbox field
     *
     * @param array $overrides
     * @return string
     */
    public function checkboxField($overrides = array()) {
        return $this->tag('input', $this->fieldOptions(array(
            'type' => 'checkbox',
            'id' => $this->input_id,
            'data-name' => $this->name,
            'value' => 1,
            'checked' => $this->value ? 'checked' : '',
        ), $overrides));
    }

    /**
     * Insert a select field
     *
     * @param array $overrides
     * @return string
     */
    public function selectField($overrides = array()) {
        return $this->tag('select', $this->fieldOptions(array(
            'id' => $this->input_id,
            'data-name' => $this->name,
        ), $overrides), 'long', $this->renderSelectOptions());
    }


    /**
     * Insert a submit field
     *
     * @param array $overrides
     * @return string
     */
    public function submitField($overrides = array()) {
        return $this->tag('input', $this->fieldOptions(array(
            'type' => 'submit',
            'id' => $this->input_id,
            'name' => 'submit',
            'value' => $this->value,
        ), $overrides));
    }

    /**
     * Insert an input field
     *
     * @param string $type
     * @param array $overrides
     * @return string
     */
    public function inputField($type, $overrides = array()) {
        return $this->tag('input', $this->fieldOptions(array(
            'type' => $type,
            'id' => $this->input_id,
            'data-name' => $this->name,
            'value' => $this->value,
            'required' => $this->required ? 'required' : '',
        ), $overrides));
    }

    public function fieldOptions($htmlOptions, $overrides) {
        if (!array_key_exists('class', $overrides)) {
            $overrides['class'] = '';
        }
        $overrides['class'] .= ' vf-formfield ff-' . $this->slug($this->name);
        return array_merge($htmlOptions, $overrides);
    }
}
