<?php

namespace FAU\Fehlermeldungen;

defined('ABSPATH') || exit;


use FAU\Fehlermeldungen\Shortcode;

/**
 * Hauptklasse (Main)
 */
class Main {
    /**
     * Der vollständige Pfad- und Dateiname der Plugin-Datei.
     * @var string
     */
    protected $pluginFile;

    /**
     * Variablen Werte zuweisen.
     * @param string $pluginFile Pfad- und Dateiname der Plugin-Datei
     */
    public function __construct($pluginFile) {
        $this->pluginFile = $pluginFile;
    }

    /**
     * Es wird ausgeführt, sobald die Klasse instanziiert wird.
     */
    public function onLoaded() {
        add_action('wp_enqueue_scripts', [$this, 'registerStyle']);

        // Shortcode-Klasse wird instanziiert.
        $shortcode = new Shortcode($this->pluginFile);
        $shortcode->onLoaded();
    }

   
   /**
     * Enqueue der Skripte.
     */
    public function registerStyle() {
	 wp_register_style(
            'fau-fehlermeldungen',
            plugins_url('css/fau-fehlermeldungen.css', plugin_basename($this->pluginFile))
        );
      
    }
    /*-----------------------------------------------------------------------------------*/
    /* enqueue with filter by theme
    /*-----------------------------------------------------------------------------------*/
    public static function fau_fehlermeldungen_enqueue_style($style = 'fau-fehlermeldungen') {
	$active_theme = wp_get_theme();
	$active_theme = $active_theme->get( 'Name' );


	$known_themes = Options::getKnownThemes();

//	if (in_array($active_theme, $known_themes['fauthemes'])) {
	    // No CSS for frontend
       // } elseif (in_array($active_theme, $known_themes['rrzethemes'])) {
	   // No CSS for frontend
//	} else{
	    wp_enqueue_style($style);
//	}

    }

    
   
}