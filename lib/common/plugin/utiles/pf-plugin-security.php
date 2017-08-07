<?php
defined('PF_VERSION') OR header('Location:404.html');
/**
 *
 * @package		Vitubo
 * @author		Vitubo Team 
 * @copyright   Vitubo Team
 * @link		http://www.vitubo.com
 * @since		Version 1.0
 * @filesource
 *
 */
class Pf_Plugin_Security {

    protected $_xss_hash = '';
    protected $_never_allowed_str = array('document.cookie' => '[removed]', 'document.write' => '[removed]', '.parentNode' => '[removed]', '.innerHTML' => '[removed]', 'window.location' => '[removed]', '-moz-binding' => '[removed]', '<!--' => '&lt;!--', '-->' => '--&gt;', '<![CDATA[' => '&lt;![CDATA[', '<comment>' => '&lt;comment&gt;');
    protected $_never_allowed_regex = array('javascript\s*:', 'expression\s*(\(|&\#40;)', // CSS and IE
        'vbscript\s*:', // IE, surprise!
        'Redirect\s+302', "([\"'])?data\s*:[^\\1]*?base64[^\\1]*?,[^\\1]*?\\1?");

    public function xss_clean($str, $is_image = FALSE) {
        if (is_array($str)) {
            while (list($key) = each($str)) {
                $str[$key] = $this->xss_clean($str[$key]);
            }

            return $str;
        }
        $str = remove_invisible_characters($str);
        $str = $this->_validate_entities($str);
        $str = rawurldecode($str);
        $str = preg_replace_callback("/[a-z]+=([\'\"]).*?\\1/si", array(
            $this,
            '_convert_attribute'
                ), $str);
        $str = preg_replace_callback("/<\w+.*?(?=>|<|$)/si", array(
            $this,
            '_decode_entity'
                ), $str);
        $str = remove_invisible_characters($str);
        if (strpos($str, "\t") !== FALSE) {
            $str = str_replace("\t", ' ', $str);
        }
        $converted_string = $str;
        $str = $this->_do_never_allowed($str);
        if ($is_image === TRUE) {
            $str = preg_replace('/<\?(php)/i', "&lt;?\\1", $str);
        } else {
            $str = str_replace(array(
                '<?',
                '?' . '>'
                    ), array(
                '&lt;?',
                '?&gt;'
                    ), $str);
        }


        $words = array(
            'javascript',
            'expression',
            'vbscript',
            'script',
            'base64',
            'applet',
            'alert',
            'document',
            'write',
            'cookie',
            'window'
        );

        foreach ($words as $word) {
            $temp = '';

            for ($i = 0, $wordlen = strlen($word); $i < $wordlen; $i++) {
                $temp .= substr($word, $i, 1) . "\s*";
            }

            $str = preg_replace_callback('#(' . substr($temp, 0, -3) . ')(\W)#is', array(
                $this,
                '_compact_exploded_words'
                    ), $str);
        }

        do {
            $original = $str;

            if (preg_match("/<a/i", $str)) {
                $str = preg_replace_callback("#<a\s+([^>]*?)(>|$)#si", array(
                    $this,
                    '_js_link_removal'
                        ), $str);
            }

            if (preg_match("/<img/i", $str)) {
                $str = preg_replace_callback("#<img\s+([^>]*?)(\s?/?>|$)#si", array(
                    $this,
                    '_js_img_removal'
                        ), $str);
            }

            if (preg_match("/script/i", $str) OR preg_match("/xss/i", $str)) {
                $str = preg_replace("#<(/*)(script|xss)(.*?)\>#si", '[removed]', $str);
            }
        } while ($original != $str);

        unset($original);

        $str = $this->_remove_evil_attributes($str, $is_image);

        $naughty = 'alert|applet|audio|basefont|base|behavior|bgsound|blink|body|embed|expression|form|frameset|frame|head|html|ilayer|iframe|input|isindex|layer|link|meta|object|plaintext|style|script|textarea|title|video|xml|xss';
        $str = preg_replace_callback('#<(/*\s*)(' . $naughty . ')([^><]*)([><]*)#is', array(
            $this,
            '_sanitize_naughty_html'
                ), $str);

        $str = preg_replace('#(alert|cmd|passthru|eval|exec|expression|system|fopen|fsockopen|file|file_get_contents|readfile|unlink)(\s*)\((.*?)\)#si', "\\1\\2&#40;\\3&#41;", $str);

        $str = $this->_do_never_allowed($str);


        if ($is_image === TRUE) {
            return ($str == $converted_string) ? TRUE : FALSE;
        }

        return $str;
    }

    protected function _remove_evil_attributes($str, $is_image) {
        $evil_attributes = array(
            'on\w*',
            'style',
            'xmlns',
            'formaction'
        );
        if ($is_image === TRUE) {
            unset($evil_attributes[array_search('xmlns', $evil_attributes)]);
        }

        do {
            $count = 0;
            $attribs = array();
            preg_match_all('/(' . implode('|', $evil_attributes) . ')\s*=\s*(\042|\047)([^\\2]*?)(\\2)/is', $str, $matches, PREG_SET_ORDER);
            foreach ($matches as $attr) {
                $attribs[] = preg_quote($attr[0], '/');
            }
            preg_match_all('/(' . implode('|', $evil_attributes) . ')\s*=\s*([^\s>]*)/is', $str, $matches, PREG_SET_ORDER);
            foreach ($matches as $attr) {
                $attribs[] = preg_quote($attr[0], '/');
            }
            if (count($attribs) > 0) {
                $str = preg_replace('/(<?)(\/?[^><]+?)([^A-Za-z<>\-])(.*?)(' . implode('|', $attribs) . ')(.*?)([\s><]?)([><]*)/i', '$1$2 $4$6$7$8', $str, -1, $count);
            }
        } while ($count);

        return $str;
    }

    protected function _do_never_allowed($str) {
        $str = str_replace(array_keys($this->_never_allowed_str), $this->_never_allowed_str, $str);

        foreach ($this->_never_allowed_regex as $regex) {
            $str = preg_replace('#' . $regex . '#is', '[removed]', $str);
        }

        return $str;
    }

    protected function _validate_entities($str) {
        $str = preg_replace('|\&([a-z\_0-9\-]+)\=([a-z\_0-9\-]+)|i', $this->xss_hash() . "\\1=\\2", $str);
        $str = preg_replace('#(&\#?[0-9a-z]{2,})([\x00-\x20])*;?#i', "\\1;\\2", $str);
        $str = preg_replace('#(&\#x?)([0-9A-F]+);?#i', "\\1\\2;", $str);
        $str = str_replace($this->xss_hash(), '&', $str);

        return $str;
    }

    public function xss_hash() {
        if ($this->_xss_hash == '') {
            mt_srand();
            $this->_xss_hash = md5(time() + mt_rand(0, 1999999999));
        }

        return $this->_xss_hash;
    }

    protected function _convert_attribute($match) {
        return str_replace(array(
            '>',
            '<',
            '\\'
                ), array(
            '&gt;',
            '&lt;',
            '\\\\'
                ), $match[0]);
    }

    protected function _compact_exploded_words($matches) {
        return preg_replace('/\s+/s', '', $matches[1]) . $matches[2];
    }

    protected function _decode_entity($match) {
        return $this->entity_decode($match[0], strtoupper(config_item('charset')));
    }

    protected function _sanitize_naughty_html($matches) {
        $str = '&lt;' . $matches[1] . $matches[2] . $matches[3];
        $str .= str_replace(array(
            '>',
            '<'
                ), array(
            '&gt;',
            '&lt;'
                ), $matches[4]);

        return $str;
    }

}
