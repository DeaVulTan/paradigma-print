<?php
defined('PF_VERSION') OR header('Location:404.html');
class Pf_Security{
    public static $hash_type = 'sha1';
    /**
     * Create a hash from string using given method or fallback on next available method.
     * 
     * @param unknown $string
     * @param string $type
     * @param string $salt
     * @return string
     */
    public function hash($string, $type = null, $salt = false) {
        if (empty($type)) {
            $type = self::$hash_type;
        }
        $type = strtolower($type);
    
        if ($salt) {
            if (!is_string($salt)) {
                $salt = __SECURITY_SALT__;
            }
            $string = $salt . $string;
        }
    
        if (!$type || $type === 'sha1') {
            if (function_exists('sha1')) {
                return sha1($string);
            }
            $type = 'sha256';
        }
    
        if ($type === 'sha256' && function_exists('mhash')) {
            return bin2hex(mhash(MHASH_SHA256, $string));
        }
    
        if (function_exists('hash')) {
            return hash($type, $string);
        }
        return md5($string);
    }
    /**
     * Runs $text through a XOR cipher.
     * 
     * @param string $text Encrypted string to decrypt, normal string to encrypt
     * @param unknown $key
     * @return string
     */
    public function cipher($text, $key) {
        if (empty($key)) {
            $key = __SECURITY_SALT__;
        }

        srand(__SECURITY_CIPHER_SEED__);
        $out = '';
        $keyLength = strlen($key);
        for ($i = 0, $textLength = strlen($text); $i < $textLength; $i++) {
            $j = ord(substr($key, $i % $keyLength, 1));
            while ($j--) {
                rand(0, 255);
            }
            $mask = rand(0, 255);
            $out .= chr(ord(substr($text, $i, 1)) ^ $mask);
        }
        srand();
        return $out;
    }
    
    /*$salt = $security->genrandom(40);
    $seed = $security->genrandom(29, "0123456789");
    
    echo "\tConfigure::write('Security.salt', '$salt');\n";
    echo "\tConfigure::write('Security.cipherSeed', '$seed');\n";*/
    /**
     * Generate random string salt or ramdom number cipher seed.  
     * 
     * @param unknown $len
     * @param string $salt
     * @return string
     */
    public function genrandom($len, $salt = null) {
        if (empty($salt)) {
            $salt = $this->salt('a', 'z'). $this->salt('A', 'Z'). $this->salt('0', '9');
        }
        $str = "";
        for ($i = 0; $i < $len; $i++) {
            $index = rand(0, strlen($salt) - 1);
            $str .= $salt[$index];
        }
    
        return $str;
    }
    
    /**
     * 
     * @param unknown $from
     * @param unknown $end
     * @return string
     */
    public function salt($from, $end) {
        $salt = '';
        for ($no = ord($from); $no <= ord($end); $no++) {
            $salt .= chr($no);
        }
    
        return $salt;
    }
}