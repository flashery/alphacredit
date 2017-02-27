<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Image_resizer
 *
 * @author flashery
 */
class Image_resizer {

    public function resize() {
        $config['image_library'] = 'gd2';
        $config['source_image'] = '/path/to/image/mypic.jpg';
        $config['create_thumb'] = TRUE;
        $config['maintain_ratio'] = TRUE;
        $config['width'] = 75;
        $config['height'] = 50;

        $this->load->library('image_lib', $config);

        $this->image_lib->resize();
    }

}
