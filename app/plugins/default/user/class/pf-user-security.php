<?php
class Pf_User_Security{
    public function encrypt($value) {
        if (is_array ( $value )) {
            $value = $this->implode ( $value );
        }
        $value = base64_encode (Pf::security ()->cipher ( $value, __SECURITY_SALT__ ) );
        return $value;
    }
    public function decrypt($value) {
       return  $this->explode ( Pf::security ()->cipher ( base64_decode ($value ), __SECURITY_SALT__));
    }
    protected function implode(array $array) {
        return json_encode ( $array );
    }
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