<?php

namespace FAU\Fehlermeldungen;

defined('ABSPATH') || exit;

class Options {  
    protected static function known_themes() {
	$known_themes = array(
	    'fauthemes' => [
		    'FAU-Einrichtungen',
		    'FAU-Einrichtungen-BETA',
		    'FAU-Medfak',
		    'FAU-RWFak',
		    'FAU-Philfak',
		    'FAU-Techfak',
		    'FAU-Natfak',
		    'FAU-Blog',
		    'FAU-Jobs'
	    ],
	    'rrzethemes' => [
		    'RRZE 2019',
	    ],
	);


        return $known_themes;
    }

    public static function getKnownThemes() {
	return self::known_themes();

    }

    protected static function templates() {
	
	$error_templates = array(
	    "403"   => array(
		"rw-freedom"
	    ),
	    "404"   => array(
		"nat-black-hole",
		"med-aluminium",
		"zentral-failure",
		"rw-cybercrime",
		"tf-black-box",
		"phil-aeria",
		"phil-ufg",
		"phil-dopamin",

	    ),
	    "other"   => array(
		"phil-wbf",
		"phil-irrtum",
		"phil-astrolabium"
	    ),	
	);

        return $error_templates;
    }

    public static function getTemplates() {
        return self::templates();

    }

   

}


