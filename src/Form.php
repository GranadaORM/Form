<?php

namespace Granada\Form;

class Form {

    /**
     * @var \Granada\Builder\ExtendedModel
     */
    private $model;

    public function __construct($model = null) {
        $this->model = $model;
    }

    public function getFields($field_names = null) {
        if (is_null($field_names)) {
            $field_names = $this->model->formFields();
        }
        $fields = array();
        foreach ($field_names as $field_name) {
            $fields[] = $this->build($field_name);
        }
        return $fields;
    }

    /**
     * Get the form field
     * @param string $field_name
     * @return FormField
     */
    public function build($field_name = null) {
        if (!$field_name) {
            return new FormField(get_class($this));
        }
        $options = [];
        if ($this->model->fieldType($field_name) == 'reference') {
            // Get list of possible results
            $refmodel = $this->model->refModel($field_name);
            $options = $refmodel::find_pairs_representation();
        }
        if ($this->model->fieldType($field_name) == 'enum') {
            $options = $this->model->enum_options($field_name);
        }
        return (new FormField(get_class($this)))
            ->setItem($this->model)
            ->setType($this->model->fieldType($field_name))
            ->setName($field_name)
            ->setValue($this->model->$field_name)
            ->setTags($this->model->fieldTags($field_name))
            ->setLabel($this->model->fieldHumanName($field_name))
            ->setHelptext($this->model->fieldHelpText($field_name))
            ->setLength($this->model->fieldLength($field_name))
            ->setSelectOptions($options)
            ->setRequired($this->model->fieldIsRequired($field_name));
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
     * Output an opening form tag
     *
     * @param string $action
     * @param string $method
     * @param array $htmlOptions
     * @param boolean $fileupload Include the enctype for forms that have a basic file upload field
     * @return string
     */
    public function beginForm($action = '', $method = 'post', $htmlOptions = array(), $fileupload = false) {
        return $this->tag('form', array_merge(array(
            'action' => $action,
            'method' => $method,
            'enctype' => $fileupload ? 'multipart/form-data' : '',
        ), $htmlOptions), 'none');
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
     * Get the form template for this field in twig format
     * @param string $type
     * @param integer $length
     * @param string[] $tags
     * @param \Granada\Granada|null $item
     * @param string $fieldname
     * @return string
     */
    public function fieldTemplate($type, $length, $tags, $item, $fieldname) {
        return '<label for={{ label_for }}>{{ label }}{% if help %}
        <i class="fas fa-question-circle" title="{{ help }}"></i>
        {% endif %}</label><input type="text" name="{{ name }}" value="{{ value }}" />';
    }

    /**
     * Render a form field
     * @param string $field
     * @return string
     */
    public function renderField($field) {
        return $this->build($field)
            ->render();
    }

    /**
     * Render a number of fields at once
     * @param string[] $fields
     * @return string
     */
    public function renderFields($fields = array()) {
        $response = '';
        if (!$fields) {
            $fields = $this->model->formFields();
        }
        foreach ($fields as $field) {
            $response .= $this->renderField($field);
        }
        return $response;
    }

    /**
     * Insert a hidden input field
     *
     * @param string $name
     * @param string $value
     * @return string
     */
    public function hiddenField($name, $value = '') {
        return $this->tag('input', array(
            'type' => 'hidden',
            'name' => $name,
        ));
    }
}
