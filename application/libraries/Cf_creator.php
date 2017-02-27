<?php

/**
 * Created by flashery
 * Date: 9/16/2016
 * Time: 1:49 PM
 */
class CF_Creator {

    protected $CI = null;
    protected $custom_fields = null;
    protected $username = '';

    public function __construct() {
        $this->CI = &get_instance();
        $this->CI->load->model('custom_fields');
        $this->custom_fields = $this->CI->custom_fields->get_custom_fields();
    }

    public function create_form_header($form_action, $legend_name) {
        $form_header = form_open($form_action);
        $form_header .= '<fieldset>';
        $form_header .= '<legend>' . $legend_name . '</legend>';
        $form_header .= validation_errors('<p class="error">');
        return $form_header;
    }

    public function create_form_footer() {
        $form_footer = '</fieldset>';
        $form_footer .= '</form>';
        return $form_footer;
    }

    public function create_text_input($name, $label, $input_class, $input_value) {
        $text = '<div class="input-label">';
        $text .= '<label for="' . $name . '">' . ucwords($label) . ':</label>';
        $text .= '</div>';
        $text .= '<div class="form-input">';
        $text .= '<input class="' . $input_class . '" type="text" name="' . $name . '" value="' . $input_value . '">';
        $text .= '</div>';

        return $text;
    }

    public function create_password_input($name, $label, $input_class, $input_value) {
        $text = '<div class="input-label">';
        $text .= '<label for="' . $name . '">' . ucwords($label) . ':</label>';
        $text .= '</div>';
        $text .= '<div class="form-input">';
        $text .= '<input class="' . $input_class . '" type="password" name="' . $name . '" value="' . $input_value . '">';
        $text .= '</div>';

        return $text;
    }

    /**
     * @return null
     */
    public function create_textarea_input($name, $label, $input_class, $input_value) {
        $textarea = '<div class="input-label">';
        $textarea .= '<label for="' . $name . '">' . ucwords($label) . ':</label>';
        $textarea .= '</div>';
        $textarea .= '<div class="form-input">';
        $textarea .= '<textarea class="' . $input_class . '" name="' . $name . '">' . $input_value . '</textarea>';
        $textarea .= '</div>';

        return $textarea;
    }

    /**
     * @return null
     */
    public function create_radio_input($name, $label, $input_value, $options = array()) {
        $radio = '<div class="input-label">';
        $radio .= '<label for="' . $name . '">' . ucwords($label) . ':</label>';
        $radio .= '</div>';
        $radio .= '<div class="form-input">';
        foreach ($options as $option) {
            if ($input_value === $option['value']) {
                $radio .= '<input type="radio" name="' . $name . '" value="' . $option['value'] . '" checked="checked">' . $option['value'] . '';
            } else {
                $radio .= '<input type="radio" name="' . $name . '" value="' . $option['value'] . '">' . $option['value'] . '';
            }
        }
        $radio .= '</div>';

        return $radio;
    }

    /**
     * @return null
     */
    public function create_select_input($name, $label, $input_value, $options = array()) {
        $select = '<div class="input-label">';
        $select .= '<label for="' . $name . '">' . ucwords($label) . ':</label>';
        $select .= '</div>';
        $select .= '<div class="form-input">';
        $select .= '<select class="D" name="' . $name . '" >';
        foreach ($options as $option) {
            if ($input_value === $option['value']) {
                $select .= '<option selected="selected" value="' . $option['value'] . '">' . $option['value'] . '</opion>';
            } else {
                $select .= '<option value="' . $option['value'] . '">' . $option['value'] . '</opion>';
            }
        }
        $select .= '</select>';
        $select .= '</div>';

        return $select;
    }

    public function create_date_input($name, $label, $input_class, $input_value = '') {
        $date_field = '<div class="input-label">';
        $date_field .= '<label for="' . $name . '">' . ucwords($label) . ':</label>';
        $date_field .= '</div>';
        $date_field .= '<div class="form-input">';
        $date_field .= '<input class="' . $input_class . ' datepicker" type="text" name="' . $name . '" value="' . $input_value . '">';
        $date_field .= '</div>';
        return $date_field;
    }

    public function create_td($name, $value) {
        $td = '<td>' . $name . '</td><td id="' . strtolower(str_replace(' ', '-', $name)) . '">' . $value . '</td>';
        return $td;
    }

    protected function get_input_value($field_id) {
        $input_value = $this->CI->custom_fields->get_cf_values($this->username, $field_id);
        return $input_value['string_value'];
    }

    protected function get_cf_pos_values($field_id) {
        $input_value = $this->CI->custom_fields->get_cf_possible_values($field_id);
        return $input_value;
    }

}
