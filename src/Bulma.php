<?php

namespace Granada\Form;

class Bulma extends Form {

    public function fieldTemplate($type, $length, $tags, $item, $fieldname) {
        ob_start();
?>
        <div class="field">
            <?php if ($type == 'bool') { ?>
            <?php } else { ?>
                <label for="{{ label_for }}" class="label">
                    {{ label }}
                    {% if help %}
                    <span class="badge badge-secondary" data-toggle="tooltip" title="{{ help }}">?</span>
                    {% endif %}
                </label>
            <?php } ?>
            <div class="control">
                <?php if ($type == 'submit') { ?>
                    <input type="submit" id="{{ label_for }}" name="{{ name }}" value="{{ value }}" class="btn btn-default" {{ readonly }} />
                <?php } else if ($type == 'date') { ?>
                    <input type="date" id="{{ label_for }}" name="{{ name }}" value="{{ value }}" class="input" {{ readonly }} />
                <?php } else if ($type == 'datetime') { ?>
                    <input type="datetime-local" id="{{ label_for }}" name="{{ name }}" value="{{ value }}" class="input" {{ readonly }} />
                <?php } else if ($type == 'time') { ?>
                    <input type="time" id="{{ label_for }}" name="{{ name }}" value="{{ value }}" class="input" {{ readonly }} />
                <?php } else if ($type == 'color') { ?>
                    <input type="color" id="{{ label_for }}" name="{{ name }}" value="{{ value }}" class="input" {{ readonly }} />
                <?php } else if ($type == 'email') { ?>
                    <input type="email" id="{{ label_for }}" name="{{ name }}" value="{{ value }}" class="input" {{ readonly }} />
                <?php } else if ($type == 'bool') { ?>
                    <input type="hidden" name="{{ name }}" value="0" /><label><input type="checkbox" class="form-check-input" id="{{ label_for }}" name="{{ name }}" value="1" {% if value == 1 %}checked{% endif %} {{ readonly }} /> {{ label }}</label></label>
                <?php } else if ($type == 'booltristate') { ?>
                    <select id="{{ label_for }}" name="{{ name }}">
                        <option value="">Neither</option>
                        <option value="1" {% if value == 1 %} selected {% endif %}>Yes</option>
                        <option value="0" {% if value is same as("0") %} selected {% endif %}>No</option>
                    </select>
                <?php } else if ($type == 'reference') { ?>
                    <select id="{{ label_for }}" name="{{ name }}">
                        <option value="">-- None --</option>
                        {{ options|raw }}
                    </select>
                <?php } else if ($type == 'enum') { ?>
                    <select id="{{ label_for }}" name="{{ name }}">
                        <option value="">-- None --</option>
                        {{ options|raw }}
                    </select>
                <?php } else if ($length > 255 || $length == 0 || $type == 'text') { ?>
                    <textarea id="{{ label_for }}" name="{{ name }}" class="input" {{ readonly }}>{{ value|raw }}</textarea>
                <?php } else { ?>
                    <input type="text" id="{{ label_for }}" name="{{ name }}" value="{{ value }}" maxlength="<?= $length ?>" data-length="<?= $length ?>" class="input" {{ readonly }} />
                <?php } ?>
            </div>
        </div>
<?php
        return ob_get_clean();
    }
}
