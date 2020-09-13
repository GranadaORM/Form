<?php

namespace Granada\Form;

class Bulma extends Form {

    public function wrapperTemplate() {
        ob_start();
?>
        <div class="field">
            <label for="{{ label_for }}" class="label">
                {{ label }}
            </label>
            <div class="control">
                {{ content|raw }}
            </div>
            {% if help %}
            <p class="help">{{ help }}</p>
            {% endif %}
        </div>
<?php
        return ob_get_clean();
    }

    public function fieldTemplate($type, $length, $tags) {
        if ($type == 'submit') {
            return '<input type="submit" name="{{ name }}" value="{{ value }}" class="button" {{ readonly }} />';
        }
        if ($type == 'date') {
            return '<input type="date" name="{{ name }}" value="{{ value }}" class="input {{ class }}" {{ readonly }} />';
        }
        if ($type == 'datetime') {
            return '<input type="datetime-local" name="{{ name }}" value="{{ value }}" class="input {{ class }}" {{ readonly }} />';
        }
        if ($type == 'time') {
            return '<input type="time" name="{{ name }}" value="{{ value }}" class="input {{ class }}" {{ readonly }} />';
        }
        if ($type == 'color') {
            return '<input type="color" name="{{ name }}" value="{{ value }}" class="input {{ class }}" {{ readonly }} />';
        }
        if ($type == 'email') {
            return '<input type="email" name="{{ name }}" value="{{ value }}" class="input {{ class }}" {{ readonly }} />';
        }
        if ($type == 'boolean') {
            return '<input type="hidden" name="{{ name }}" value="0" />' .
                '<input type="checkbox" name="{{ name }}" value="1" {% if value %}checked{% endif %} {{ readonly }} />';
        }
        if ($type == 'booltristate') {
            return '<div class="select">
            <select name="{{ name }}">
            <option>Neither</option>
            <option value="1" {% if value == 1 %} selected {% endif %}>Yes</option>
            <option value="0" {% if value is same as("0") %} selected {% endif %}>No</option>
            </select>
            </div>';
        }
        if ($length > 255 || $type == 'text') {
            // Use textarea
            return '<textarea name="{{ name }}" class="input {{ class }}" {{ readonly }}>{{ value|raw }}</textarea>';
        }
        return '<input type="text" name="{{ name }}" value="{{ value }}" maxlength="' . $length . '" class="input {{ class }}" {{ readonly }} />';
        return parent::fieldTemplate($type, $length, $tags);
    }
}
