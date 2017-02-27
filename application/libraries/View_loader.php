<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class View_loader {

    public function load_other($controller, $template_folder, $content, $data = NULL) {
        $data['main_content'] = $template_folder . '/' . $content;
        $controller->load->view('includes/' . $template_folder . '/template', $data);
    }

    public function load($controller, $content, $data = NULL, $slug = NULL) {
        
        $template_folder = lcfirst(get_class($controller));
        
        if ($slug != NULL) {
            $main_content = $template_folder  . '/' . $content . '/' . $slug;
        } else {
            $main_content = $template_folder . '/' . $content;
        }
        $data['main_content'] = $main_content;
        $controller->load->view('includes/' . $template_folder . '/template', $data);
    }

}
