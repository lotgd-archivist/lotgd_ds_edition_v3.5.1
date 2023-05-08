<?php
//by bathory
class CCrypt
{
    public static function mc_encrypt($encrypt){
        $iv = mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_CBC), MCRYPT_DEV_URANDOM);
        $key = pack('H*', ENCRYPTION_KEY);
        $mac = hash_hmac('sha256', $encrypt, substr(bin2hex($key), -32));
        $passcrypt = mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $key, $encrypt.$mac, MCRYPT_MODE_CBC, $iv);
        $encoded = base64_encode($passcrypt).'|'.base64_encode($iv);
        return $encoded;
    }

    public static function mc_decrypt($decrypt){
        $decrypt = explode('|', $decrypt);
        $decoded = base64_decode($decrypt[0]);
        $iv = base64_decode($decrypt[1]);
        $key = pack('H*', ENCRYPTION_KEY);
        $decrypted = trim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $key, $decoded, MCRYPT_MODE_CBC, $iv));
        $decrypted = mb_substr($decrypted, 0, -64);
        return $decrypted;
    }

    public static function make_password_hash($klartext, $already_md5 = false)
    {
        $klartext = stripslashes($klartext);
        if($already_md5) $md5 = $klartext;
        else $md5 = md5($klartext);
        return password_hash($md5, PASSWORD_DEFAULT);
    }

    public static function verify_password_hash($klartext, $hash)
    {
        $klartext = stripslashes($klartext);
        $md5_1 = md5($klartext);
        $md5_2 = md5(utf8_decode($klartext));
        $md5_3 = md5(utf8_encode($klartext));
        $md5_4 = $klartext;
        if ( password_verify($md5_1, $hash) || password_verify($md5_2, $hash) || password_verify($md5_3, $hash) || password_verify($md5_4, $hash) )
        {
            return true;
        }
        else
        {
            return false;
        }
    }
}