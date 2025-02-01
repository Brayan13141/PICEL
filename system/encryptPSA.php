<?php
/*PSA*/
    class EncrypterPSA {
        public static function encrypt($data, $key) {
            $tamM = strlen($data)-3;
            $pri_ras = substr($data, 0, $tamM); 
            $seg_ras = substr($data, $tamM,  strlen($data));
            $key_encript = $pri_ras.$key.$seg_ras;
            return base64_encode(base64_encode(base64_encode($key_encript)));
        }
        public static function decrypt($data, $key) {
            $unkey = base64_decode(base64_decode(base64_decode($data)));
            return $unkey;
        }
    }    
/*PSA*/
?>