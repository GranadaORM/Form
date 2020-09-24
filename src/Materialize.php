<?php

namespace Granada\Form;

class Materialize extends Form {

    public function fieldTemplate($type, $length, $tags, $item, $fieldname) {
        ob_start();
?>
        <div class="row">
            <div class="input-field col s12">
                <?php if ($type == 'submit') { ?>
                    <input type="submit" name="{{ name }}" value="{{ value }}" class="btn btn-default" {{ readonly }} />
                <?php } else if ($type == 'date') { ?>
                    <input type="date" name="{{ name }}" value="{{ value }}" class="validate" {{ readonly }} />
                <?php } else if ($type == 'datetime') { ?>
                    <input type="datetime-local" name="{{ name }}" value="{{ value }}" class="validate" {{ readonly }} />
                <?php } else if ($type == 'time') { ?>
                    <input type="time" name="{{ name }}" value="{{ value }}" class="validate" {{ readonly }} />
                <?php } else if ($type == 'color') { ?>
                    <input type="color" name="{{ name }}" value="{{ value }}" class="validate" {{ readonly }} />
                <?php } else if ($type == 'email') { ?>
                    <input type="email" name="{{ name }}" value="{{ value }}" class="validate" {{ readonly }} />
                <?php } else if ($type == 'bool') { ?>
                    <label><input type="hidden" name="{{ name }}" value="0" /><input type="checkbox" name="{{ name }}" value="1" {% if value == 1 %}checked{% endif %} {{ readonly }} /><span>{{ label }}</span></label>
                <?php } else if ($type == 'booltristate') { ?>
                    <select name="{{ name }}">
                        <option>Neither</option>
                        <option value="1" {% if value == 1 %} selected {% endif %}>Yes</option>
                        <option value="0" {% if value is same as("0") %} selected {% endif %}>No</option>
                    </select>
                <?php } else if ($length > 255 || $length == 0 || $type == 'text') { ?>
                    <textarea name="{{ name }}" class="materialize-textarea" {{ readonly }}>{{ value|raw }}</textarea>
                <?php } else if ($type == 'reference') { ?>
                    <select name="{{ name }}">
                        <option>-- None --</option>
                        {{ options|raw }}
                    </select>
                <?php } else { ?>
                    <input type="text" name="{{ name }}" value="{{ value }}" maxlength="' . $length . '" data-length="' . $length . '" class="validate" {{ readonly }} />
                <?php } ?>
                <?php if ($type != 'bool') { ?>
                    <label for="{{ label_for }}">
                        {{ label }}
                        {% if help %}
                        <i class="fas fa-question-circle" title="{{ help }}"></i>
                        {% endif %}
                    </label>
                <?php } ?>
            </div>
        </div>
<?php
        return ob_get_clean();
    }
}
