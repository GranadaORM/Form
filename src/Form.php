<?php

namespace Granada\Form;

/**
 * @property \Granada\Form\FormField $field
 * @property \Granada\Builder\ExtendedModel $model
 */
class Form {

    protected $fieldname;
    protected $model;

    public function __construct($fieldname, $model = null) {
        $this->fieldname = $fieldname;
        $this->model = $model;
    }

    public function field() {
        $class = $this->fieldname;
        $field = new $class($this);
        return $field->setItem($this->model);
    }

    /**
     * Is the form in create mode, e.g. has an empty model
     * @return bool
     */
    public function isCreateMode() {
        return $this->model->is_new();
    }

    /**
     * Is the form in update mode, e.g. has a model attached from the database
     * @return bool
     */
    public function isUpdateMode() {
        return !$this->isCreateMode();
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
     * @param array $overrides Elements in the field to override
     * @return FormField
     */
    public function build($field_name = null, $overrides = []) {
        if (!$field_name) {
            return $this->field();
        }
        $options = [];
        if ($this->model->fieldType($field_name) == 'reference') {
            // Get list of possible results
            $refmodel = $this->model->refModel($field_name);
            $filterFunc = 'referenceFilter_' . $field_name;
            $options = $this->model->$filterFunc()->find_pairs_representation();
            if (!array_key_exists($this->model->$field_name, $options)) {
                foreach ($refmodel::where_id($this->model->$field_name)->find_pairs_representation() as $id => $representation) {
                    $options[$id] = $representation;
                }
            }
        }
        if ($this->model->fieldType($field_name) == 'enum') {
            $options = $this->model->enum_options($field_name);
        }
        if ($this->model->fieldType($field_name) == 'booltristate') {
            $options[''] = 'Neither';
            $options['1'] = 'Yes';
            $options['0'] = 'No';
        }
        return $this->field()
            ->setItem($this->model)
            ->setType($this->model->fieldType($field_name))
            ->setName($field_name)
            ->setValue($this->model->$field_name)
            ->setTags($this->model->fieldTags($field_name))
            ->setLabel($this->model->fieldHumanName($field_name))
            ->setHelptext($this->model->fieldHelpText($field_name))
            ->setLength($this->model->fieldLength($field_name))
            ->setSelectOptions($options)
            ->setRequired($this->model->fieldIsRequired($field_name))
            ->setOverrides($overrides);
    }

    /**
     * Render a form field
     * @param string $field
     * @param array $overrides Elements in the field to override
     * @return string
     */
    public function renderField($field, $overrides = []) {
        return $this->build($field, $overrides)
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
     * Output an opening form tag
     *
     * @param string $action
     * @param string $method
     * @param array $htmlOptions
     * @return string
     */
    public function beginForm($action = '', $method = 'post', $htmlOptions = array()) {
        return $this->field()->beginForm($action, $method, $htmlOptions);
    }

    /**
     * Output the end of the form
     *
     * @return string
     */
    public function endForm() {
        return $this->field()->endForm();
    }

    /**
     * Insert a hidden input field
     *
     * @param string $name
     * @param string $value
     * @param array $htmlOptions
     * @return string
     */
    public function hiddenField($name, $value = '', $htmlOptions = array()) {
        echo $this->field()
            ->setType('hidden')
            ->setName($name)
            ->setValue($value)
            ->setHtmlOptions($htmlOptions)
            ->render();
    }
}
