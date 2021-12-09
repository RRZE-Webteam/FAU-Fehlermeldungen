<?php
namespace FAU\Fehlermeldungen;


defined('ABSPATH') || exit;

/**
 * Shortcode
 */
class Shortcode {
    protected $pluginFile;
    private $pluginname = '';
    private $data;

    public function __construct($pluginFile)  {
        $this->pluginFile = $pluginFile;
    }


    public function onLoaded() {
        add_shortcode('fau_fehlermeldungen', [$this, 'shortcodeOutput'], 10, 2);
    }

 


    /**
     * Generieren Sie die Shortcode-Ausgabe
     * @param  array   $atts Shortcode-Attribute
     * @param  string  $content Beiliegender Inhalt
     * @return string Gib den Inhalt zurück
     */
    public function shortcodeOutput( $atts ) {
        // merge given attributes with default ones
	$shortcode_attr = shortcode_atts( array(
	    'type'      => '500',
	    'fulltext'	=> true,
	), $atts );


	$errortype = $shortcode_attr['type'];
	$full = $shortcode_attr['fulltext'];
	$html = $this->get_errormessage($errortype,$full);
	if (!empty($html)) {
	    Main::fau_fehlermeldungen_enqueue_style('fau-fehlermeldungen');	   
	}
	return $html;
	

    }
    public function get_errormessage($type = 'other', $fulltext = true, $name = '') {
	$error_templates = Options::getTemplates();
	
	
	$errornum = intval($type);
	if (!isset($error_templates[$type])) {  
	    $type = 'other';
	    
	}
	if (!isset($this->data)) 
	    $this->data = new \stdClass();

	
	$this->data->errorcode = $type;
	if ($fulltext === true) {
	    $this->data->showdefaulterror = true;
	} else {
	     $this->data->showdefaulterror = false;
	}

	$this->data->errornum = $errornum;
	$this->data->imgpath = trailingslashit( plugins_url( '', $this->pluginFile ) ); 
	
	
	
	if (!empty($name)) {
	    $template = $type.'/'.$name;
	} else {
	    $rand_key = array_rand($error_templates[$type], 1);
	    $template = $type.'/'.$error_templates[$type][$rand_key];
	}
	 $content = Template::getContent($template, $this->data);
	 if (!empty($content)) {
	      return $content;
	 } else {
	     $msg = "<!-- No Entry found for Error ".$type." -->";
	     return $msg;
	 }
	
	
    }
    
}

