<?php
defined('PF_VERSION') OR header('Location:404.html');
class Pf_Language {
	private $l10n = array ();
	public function __construct() {
	}
	public function set_l10n($domain, &$mo) {
		$this->l10n [$domain] = &$mo;
	}
	public function &get_l10n($domain) {
		return $this->l10n [$domain];
	}
	public function isset_l10n($domain) {
		return isset ( $this->l10n [$domain] ) ? true : false;
	}
}