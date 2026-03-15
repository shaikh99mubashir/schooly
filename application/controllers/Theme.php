<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Theme extends CI_Controller
{

    public function css()
    {
        // Set content type for CSS
        header("Content-Type: text/css");

        // Example: Load from config, session, or DB
        $theme_color = '#7367f0'; // fallback
        $theme_font_color = '#fff'; // fallback

        $theme_setting = $this->customlib->getCurrentThemeSetting();

        $theme_color = $theme_setting['theme_color'];
        $theme_font_color = $theme_setting['theme_font_color'];
       
        // Output CSS directly
        echo "
            :root {
                --bs-primary: {$theme_color};
                --bs-btn-border-color: {$theme_color};
                --bs-primary-hover: {$theme_color};
                --bs-hover-color: {$theme_color};
                --bs-alert-bg: {$theme_color};
                --custom-hover-theme: {$theme_color};
                --bs-primary-contrast: {$theme_font_color};
            }
        ";
    }
}
