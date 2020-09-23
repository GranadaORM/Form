<?php

namespace Granada\Form;

class Bootstrap4 extends Form {

    public function fieldTemplate($type, $length, $tags) {
        ob_start();
?>
        <div class="form-group row">
            <?php if ($type == 'bool') { ?>
                <div class="col-sm-2">
                </div>
            <?php } else { ?>
                <label for="{{ label_for }}" class="col-sm-2 col-form-label">
                    {{ label }}
                    {% if help %}
                    <span class="badge badge-secondary" data-toggle="tooltip" title="{{ help }}">?</span>
                    {% endif %}
                </label>
            <?php } ?>
            <div class="col-sm-10">
                <?php if ($type == 'submit') { ?>
                    <input type="submit" name="{{ name }}" value="{{ value }}" class="btn btn-default" {{ readonly }} />
                <?php } else if ($type == 'date') { ?>
                    <input type="date" name="{{ name }}" value="{{ value }}" class="form-control" {{ readonly }} />
                <?php } else if ($type == 'datetime') { ?>
                    <input type="datetime-local" name="{{ name }}" value="{{ value }}" class="form-control" {{ readonly }} />
                <?php } else if ($type == 'time') { ?>
                    <input type="time" name="{{ name }}" value="{{ value }}" class="form-control" {{ readonly }} />
                <?php } else if ($type == 'color') { ?>
                    <input type="color" name="{{ name }}" value="{{ value }}" class="form-control" {{ readonly }} />
                <?php } else if ($type == 'email') { ?>
                    <input type="email" name="{{ name }}" value="{{ value }}" class="form-control" {{ readonly }} />
                <?php } else if ($type == 'bool') { ?>
                    <input type="hidden" name="{{ name }}" value="0" /><input type="checkbox" class="form-check-input" name="{{ name }}" value="1" {% if value == 1 %}checked{% endif %} {{ readonly }} /><label class="form-check-label" for="{{ label_for }}">{{ label }}</label></label>
                <?php } else if ($type == 'booltristate') { ?>
                    <select name="{{ name }}">
                        <option>Neither</option>
                        <option value="1" {% if value == 1 %} selected {% endif %}>Yes</option>
                        <option value="0" {% if value is same as("0") %} selected {% endif %}>No</option>
                    </select>
                <?php } else if ($length > 255 || $length == 0 || $type == 'text') { ?>
                    <textarea name="{{ name }}" class="form-control" {{ readonly }}>{{ value|raw }}</textarea>
                <?php } else { ?>
                    <input type="text" name="{{ name }}" value="{{ value }}" maxlength="' . $length . '" data-length="' . $length . '" class="form-control" {{ readonly }} />
                <?php } ?>
            </div>
        </div>
<?php
        return ob_get_clean();
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
        if ($type == 'bool') {
            return '<input type="hidden" name="{{ name }}" value="0" />' .
                '<input type="checkbox" name="{{ name }}" value="1" {% if value %}checked{% endif %} {{ readonly }} />';
        }
        if ($type == 'booltristate') {
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
