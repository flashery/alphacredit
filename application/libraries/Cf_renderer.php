<?php

/**
 * Created by flashery
 * Date: 9/16/2016
 * Time: 1:49 PM
 */
class CF_Renderer extends CF_Creator {

    public function __construct() {
        parent::__construct();
    }

    public function render_fields($legend_lbl, $username = NULL) {
        $this->username = ($username == NULL) ? $this->CI->session->userdata('username') : $username;

        $fields = '<fieldset>';
        $fields .= '<legend id="more-info">' . $legend_lbl . '</legend>';

        foreach ($this->custom_fields->result() as $row) {
            if ($row->type == 'date') {
                $input_value = $this->get_input_value($row->id);
                $fields .= $this->create_date_input($row->internal_name, $row->name, $row->size, $input_value);
            } else {
                $fields .= $this->load_other_input($row->control, $row->id, $row->internal_name, $row->name, $row->size);
            }
        }
        $fields .= '</fieldset>';

        return $fields;
    }

    public function render_td($username = NULL) {
        // If viewing other users get its username, if not get self username
        $this->username = ($username == NULL) ? $this->CI->session->userdata('username') : $username;
        $td = '';
        foreach ($this->custom_fields->result() as $row) {
            $value = $this->get_input_value($row->id);
            $td .= '<tr>';
            $td .= $this->create_td($row->name, $value);
            $td .= '</tr>';
        }
        return $td;
    }

    private function load_other_input($control, $id, $internal_name, $name, $size) {
        $fields = '';
        switch ($control) {
            case "text":
                $input_value = $this->get_input_value($id);
                $fields .= $this->create_text_input($internal_name, $name, $size, $input_value);
                break;
            case "radio":
                $input_value = $this->get_input_value($id);
                $fields .= $this->create_radio_input($internal_name, $name, $input_value, $this->get_cf_pos_values($id));
                break;
            case "select":
                $input_value = $this->get_input_value($id);
                $fields .= $this->create_select_input($internal_name, $name, $input_value, $this->get_cf_pos_values($id));
                break;
            case "textarea":
                $input_value = $this->get_input_value($id);
                $fields .= $this->create_textarea_input($internal_name, $name, $size, $input_value);
                break;
            default:
                $fields .= "No input control to be displayed!";
        }
        return $fields;
    }

}
