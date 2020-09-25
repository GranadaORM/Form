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
                    <input type="submit" name="{{ name }}" value="{{ value }}" class="btn btn-default" {{ readonly }} />
                <?php } else if ($type == 'date') { ?>
                    <input type="date" name="{{ name }}" value="{{ value }}" class="input" {{ readonly }} />
                <?php } else if ($type == 'datetime') { ?>
                    <input type="datetime-local" name="{{ name }}" value="{{ value }}" class="input" {{ readonly }} />
                <?php } else if ($type == 'time') { ?>
                    <input type="time" name="{{ name }}" value="{{ value }}" class="input" {{ readonly }} />
                <?php } else if ($type == 'color') { ?>
                    <input type="color" name="{{ name }}" value="{{ value }}" class="input" {{ readonly }} />
                <?php } else if ($type == 'email') { ?>
                    <input type="email" name="{{ name }}" value="{{ value }}" class="input" {{ readonly }} />
                <?php } else if ($type == 'bool') { ?>
                    <input type="hidden" name="{{ name }}" value="0" /><label><input type="checkbox" class="form-check-input" name="{{ name }}" value="1" {% if value == 1 %}checked{% endif %} {{ readonly }} /> {{ label }}</label></label>
                <?php } else if ($type == 'booltristate') { ?>
                    <select name="{{ name }}">
                        <option>Neither</option>
                        <option value="1" {% if value == 1 %} selected {% endif %}>Yes</option>
                        <option value="0" {% if value is same as("0") %} selected {% endif %}>No</option>
                    </select>
                <?php } else if ($type == 'reference') { ?>
                    <select name="{{ name }}">
                        <option value="">-- None --</option>
                        {{ options|raw }}
                    </select>
                <?php } else if ($type == 'enum') { ?>
                    <select name="{{ name }}">
                        <option value="">-- None --</option>
                        {{ options|raw }}
                    </select>
                <?php } else if ($length > 255 || $length == 0 || $type == 'text') { ?>
                    <textarea name="{{ name }}" class="input" {{ readonly }}>{{ value|raw }}</textarea>
                <?php } else { ?>
                    <input type="text" name="{{ name }}" value="{{ value }}" maxlength="<?= $length ?>" data-length="<?= $length ?>" class="input" {{ readonly }} />
                <?php } ?>
            </div>
        </div>
<?php
        return ob_get_clean();
    }
}
