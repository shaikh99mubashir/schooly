<?php
defined('BASEPATH') or exit('No direct script access allowed');

class FrontTheme extends MY_Controller
{

    public function css()
    {

        header("Content-Type: text/css");


        $theme_color = '#7367f0';
        $theme_font_color = '#7367f0';

        $theme_color_setting = $this->customlib->getFrontCurrentThemeSetting();

      
        $theme_color = $theme_color_setting['theme_color'];
        $theme_font_color = $theme_color_setting['theme_font_color'];
 



        // // Output CSS directly
        echo "
            :root {
                --bs-primary: {$theme_color};
                --submit-text-color: {$theme_font_color};
              
            }
        ";
    }
}
