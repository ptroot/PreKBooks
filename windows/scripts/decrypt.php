<?php
function decrypt($data, $key) {
    $cipher = "AES-256-CBC";
    $data = base64_decode($data);
    $ivLength = openssl_cipher_iv_length($cipher);
    $iv = substr($data, 0, $ivLength);
    $encrypted = substr($data, $ivLength);
    return openssl_decrypt($encrypted, $cipher, $key, 0, $iv);
}

$dec = decrypt ($argv[2], $argv[1]);

echo "$dec\n";

?>
