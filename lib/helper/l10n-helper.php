<?php
defined('PF_VERSION') OR header('Location:404.html');
function load_includes_language(){
    global $locale;
    $mofile = ABSPATH . '/lib/languages/'.$locale.'.mo';
    return load_textdomain("includes", $mofile);
}
function load_theme_language($domain,$extra = '',$theme='default'){
    global $locale;
    if (! empty ( $extra )) {
        $mofile = ABSPATH . '/'.$extra.'/app/themes/'.$theme.'/languages/'.$locale.'.mo';
    } else {
        $mofile = ABSPATH.'/admin/themes/'.$theme.'/languages/'.$locale.'.mo';
    }
    return load_textdomain($domain, $mofile);
}
function load_language($domain, $extra = '') {
	global $locale;
	if (! empty ( $extra )) {
		$mofile = ABSPATH . '/app/plugins/default/'.$domain.'/languages/'.$locale.'.mo';
	} else {
		$mofile = ABSPATH . '/app/plugins/others/'.$domain.'/languages/'.$locale.'.mo';
	}
	return load_textdomain($domain, $mofile);
}
function load_textdomain($domain, $mofile) {
	if (! is_readable ( $mofile ))
		return false;

	$mo = File_Gettext::factory('mo');

	if ($mo !== false && ! $mo->load($mofile ))
		return false;
	
	Pf::language ()->set_l10n ( $domain, $mo );
	
	return true;
}
function &get_translations_for_domain($domain) {
	if (! Pf::language ()->isset_l10n ( $domain )) {
		$empty_mo = File_Gettext::factory('mo');
		Pf::language ()->set_l10n ( $domain, $empty_mo );
	}
	$mo = & Pf::language ()->get_l10n ( $domain );
	
	return $mo;
}
function translate($text, $domain = 'default') {
	$mo = & get_translations_for_domain ( $domain );

	if ($mo !== false){
    	$translations = $mo->toArray();
    	if (!empty($translations['strings']) && !empty($translations['strings'][$text]) ){
    	    $text = $translations['strings'][$text];
    	}
	}
	
	return $text;
}
function __($text, $domain = 'default') {
	return translate ( $text, $domain );
}