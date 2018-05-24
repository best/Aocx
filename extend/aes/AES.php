<?php

namespace aes;

class AES
{
    static public function generateKey($length = 32)
    {
        if (!in_array($length, array(16, 24, 32)))
            return False;

        $str = '';
        for ($i = 0; $i < $length; $i++) {
            $str .= chr(rand(33, 126));
        }

        return $str;
    }

    static public function encrypt($plaintext, $key ,$iv = '')
    {
        if (strlen($key) > 32 || !$key)
            return trigger_error('key too large or key is empty.', E_USER_WARNING) && False;

        $cipher = "AES-256-CFB";

        if (in_array($cipher, openssl_get_cipher_methods())) {
            if(!$iv){
                $ivlen = openssl_cipher_iv_length($cipher);
                $iv = self::generateKey($ivlen);
            }
            $ciphertext = openssl_encrypt($plaintext, $cipher, $key, $options = 0, $iv);
            return [
                'ciphertext' => $ciphertext,
                'iv' => $iv
                ];
        }
    }

    static public function decrypt($ciphertext, $key, $iv)
    {
        if (strlen($key) > 32 || !$key)
            return trigger_error('key too large or key is empty.', E_USER_WARNING) && False;

        $cipher = "AES-256-CFB";

        if (in_array($cipher, openssl_get_cipher_methods())) {
            $original_plaintext = openssl_decrypt($ciphertext, $cipher, $key, $options = 0, $iv);
            return $original_plaintext;
        }
    }
}