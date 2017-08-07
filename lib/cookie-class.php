<?php
defined('PF_VERSION') OR header('Location:404.html');
class Pf_Cookie {
    const Session = null;
    const OneDay = 86400;
    const SevenDays = 604800;
    const ThirtyDays = 2592000;
    const SixMonths = 15811200;
    const OneYear = 31536000;
    const Lifetime = - 1; // 2030-01-01 00:00:00
    
    /**
     * Returns true if there is a cookie with this name.
     *
     * @param string $name            
     * @return bool
     */
    public function exists($name) {
        return isset ( $_COOKIE [$name] );
    }
    
    /**
     * Returns true if there no cookie with this name or it's empty, or 0,
     * or a few other things.
     * Check http://php.net/empty for a full list.
     *
     * @param string $name            
     * @return bool
     */
    public function is_empty($name) {
        return empty ( $_COOKIE [$name] );
    }
    
    /**
     * Get the value of the given cookie.
     * If the cookie does not exist the value
     * of $default will be returned.
     *
     * @param string $name            
     * @param string $default            
     * @return mixed
     */
    public function get($name, $default = '') {
        
        return (isset ( $_COOKIE [$name] ) ? $this->decrypt ( $_COOKIE [$name] ) : $default);
    }
    
    /**
     * Set a cookie.
     * Silently does nothing if headers have already been sent.
     *
     * @param string $name            
     * @param string $value            
     * @param mixed $expiry            
     * @param string $path            
     * @param string $domain            
     * @return bool
     */
    public function set($name, $value, $expiry = self::OneYear, $path = '/', $domain = false) {
        $retval = false;
        if (! headers_sent ()) {
            if ($domain === false)
                $domain = $_SERVER ['HTTP_HOST'];
            
            if ($expiry === - 1)
                $expiry = 1893456000; // Lifetime = 2030-01-01 00:00:00
            elseif (is_numeric ( $expiry ))
                $expiry += time ();
            else
                $expiry = strtotime ( $expiry );
            
            $retval = @setcookie ( $name, $this->encrypt ( $value ), $expiry, $path, $domain );
        }
        return $retval;
    }
    
    /**
     * Delete a cookie.
     *
     * @param string $name            
     * @param string $path            
     * @param string $domain            
     * @param bool $remove_from_global
     *            Set to true to remove this cookie from this request.
     * @return bool
     */
    public function delete($name, $path = '/', $domain = false, $remove_from_global = false) {
        $retval = false;
        if (! headers_sent ()) {
            if ($domain === false)
                $domain = $_SERVER ['HTTP_HOST'];
            $retval = setcookie ( $name, '', time () - 42000, $path, $domain );
            
            if ($remove_from_global)
                unset ( $_COOKIE [$name] );
        }
        return $retval;
    }
    /**
     * Encrypts $value using public cipher method in Security class
     *
     * @param unknown $value            
     * @return string
     */
    protected function encrypt($value) {
        if (is_array ( $value )) {
            $value = $this->implode ( $value );
        }
        
        $value = "PfCMS==." . base64_encode ( Pf::security ()->cipher ( $value, __SECURITY_SALT__ ) );
        
        return $value;
    }
    
    /**
     * Decrypts $value using public cipher method in Security class
     *
     * @param unknown $values            
     * @return multitype:NULL
     */
    protected function decrypt($value) {
        $pos = strpos ( $value, 'PfCMS==.' );
        
        if ($pos !== false) {
            $value = substr ( $value, 7 );
            $decrypted = $this->explode ( Pf::security ()->cipher ( base64_decode ( $value ), __SECURITY_SALT__ ) );
        }else{
            return $value;
        }
        
        return $decrypted;
    }
    
    /**
     * Implode method to keep keys are multidimensional arrays.
     *
     * @param array $array            
     * @return string
     */
    protected function implode(array $array) {
        return json_encode ( $array );
    }
    /**
     * Explode method to return array from string set in implode method.
     * 
     * @param unknown $string            
     * @return Ambigous <unknown, mixed>|Ambigous <>|multitype:Ambigous <>
     */
    protected function explode($string) {
        $first = substr ( $string, 0, 1 );
        if ($first === '{' || $first === '[') {
            $ret = json_decode ( $string, true );
            return ($ret !== null) ? $ret : $string;
        }
        $array = array ();
        foreach ( explode ( ',', $string ) as $pair ) {
            $key = explode ( '|', $pair );
            if (! isset ( $key [1] )) {
                return $key [0];
            }
            $array [$key [0]] = $key [1];
        }
        return $array;
    }
}

