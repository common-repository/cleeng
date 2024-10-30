<?php

class Cleeng_Plugins
{


    public function setup()
    {


        $cleeng = Cleeng_Core::load('Cleeng_WpClient');
        
        $this->wpClient = $cleeng;
        
        if ($this->wpClient->isUserAuthenticated()){
            $this->userInfo = $cleeng->getUserInfo(); 
        } else {
            $this->userInfo = false;
        }

        $admin = Cleeng_Core::load('Cleeng_Admin');
        add_action( "admin_head-plugins.php", array($admin, 'render_javascript') );

        add_action('init', array($this, 'add_header_scripts'));

    }


    public function add_header_scripts() {
                // add UI dialog
        wp_enqueue_script( 'jquery-ui-dialog' );
        wp_enqueue_script( 'jquery-tmpl', CLEENG_PLUGIN_URL . 'js/jquery.tmpl.min.js');
        wp_enqueue_style('jqueryUi.css', CLEENG_PLUGIN_URL . 'css/south-street/jquery-ui-1.8.2.custom.css');
    }
}