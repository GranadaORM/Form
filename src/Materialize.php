<?php

namespace Granada\Form;

class Materialize extends Form {

    public function wrapperTemplate() {
        ob_start();
?>
        <div class="row">
            <div class="input-field col s12">
                {{ content|raw }}
                <label for="{{ label_for }}">
                    {{ label }}
                </label>
                {% if help %}
                <span class="helper-text" data-error="wrong" data-success="right">{{ help }}</span>
                {% endif %}
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
            return '<input type="date" name="{{ name }}" value="{{ value }}" class="validate" {{ readonly }} />';
        }
        if ($type == 'datetime') {
            return '<input type="datetime-local" name="{{ name }}" value="{{ value }}" class="validate" {{ readonly }} />';
        }
        if ($type == 'time') {
            return '<input type="time" name="{{ name }}" value="{{ value }}" class="validate" {{ readonly }} />';
        }
        if ($type == 'color') {
            return '<input type="color" name="{{ name }}" value="{{ value }}" class="validate" {{ readonly }} />';
        }
        if ($type == 'email') {
            return '<input type="email" name="{{ name }}" value="{{ value }}" class="validate" {{ readonly }} />';
        }
        if ($type == 'boolean') {
            return '<input type="hidden" name="{{ name }}" value="0" />' .
                '<input type="checkbox" name="{{ name }}" value="1" {% if value %}checked{% endif %} {{ readonly }} />';
        }
        if ($type == 'booltristate') {
            return '
    <select name="{{ name }}">
      <option>Neither</option>
      <option value="1" {% if value == 1 %} selected {% endif %}>Yes</option>
      <option value="0" {% if value is same as("0") %} selected {% endif %}>No</option>
    </select>';
        }
        if ($length > 255 || $length == 0 || $type == 'text') {
            // Use textarea
            return '<textarea name="{{ name }}" class="materialize-textarea" {{ readonly }}>{{ value|raw }}</textarea>';
        }
        return '<input type="text" name="{{ name }}" value="{{ value }}" maxlength="' . $length . '" data-length="' . $length . '" class="validate" {{ readonly }} />';
        return parent::fieldTemplate($type, $length, $tags);
    }
}
