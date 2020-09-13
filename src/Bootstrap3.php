<?php

namespace Granada\Form;

class Bootstrap3 extends Form {

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
        if (!array_key_exists('class', $htmlOptions)) {
            $htmlOptions['class'] = '';
        }
        $htmlOptions['class'] .= ' form-horizontal';

        return $this->tag('form', array_merge(array(
            'action' => $action,
            'method' => $method,
            'enctype' => $fileupload ? 'multipart/form-data' : '',
        ), $htmlOptions), 'none');
    }

    public function wrapperTemplate() {
        ob_start();
?>
        <div class="form-group row">
            <label for="{{ label_for }}" class="col-sm-2 control-label">
                {{ label }}
                {% if help %}
                <span class="badge badge-secondary" data-toggle="tooltip" title="{{ help }}">?</span>
                {% endif %}
            </label>
            <div class="col-sm-10">
                {{ content|raw }}
            </div>
        </div>
<?php
        return ob_get_clean();
    }

    public function fieldTemplate($type, $length, $tags) {
        if ($type == 'submit') {
            return '<input type="submit" name="{{ name }}" value="{{ value }}" class="btn btn-default" {{ readonly }} />';
        }
        if ($type == 'date') {
            return '<input type="date" name="{{ name }}" value="{{ value }}" class="form-control" {{ readonly }} />';
        }
        if ($type == 'datetime') {
            return '<input type="datetime-local" name="{{ name }}" value="{{ value }}" class="form-control" {{ readonly }} />';
        }
        if ($type == 'time') {
            return '<input type="time" name="{{ name }}" value="{{ value }}" class="form-control" {{ readonly }} />';
        }
        if ($type == 'color') {
            return '<input type="color" name="{{ name }}" value="{{ value }}" class="form-control" {{ readonly }} />';
        }
        if ($type == 'email') {
            return '<input type="email" name="{{ name }}" value="{{ value }}" class="form-control" {{ readonly }} />';
        }
        if ($type == 'boolean') {
            return '<input type="hidden" name="{{ name }}" value="0" />' .
                '<input type="checkbox" name="{{ name }}" value="1" {% if value %}checked{% endif %} {{ readonly }} />';
        }
        if ($length > 255 || $type == 'text') {
            // Use textarea
            return '<textarea name="{{ name }}" class="form-control" {{ readonly }}>{{ value|raw }}</textarea>';
        }
        return '<input type="text" name="{{ name }}" value="{{ value }}" maxlength="' . $length . '" class="form-control" {{ readonly }} />';
        return parent::fieldTemplate($type, $length, $tags);
    }
}
