<?php
function encrypt($data, $key) {
    $cipher = "AES-256-CBC";
    $iv = random_bytes(openssl_cipher_iv_length($cipher));
    $encrypted = openssl_encrypt($data, $cipher, $key, 0, $iv);
    return base64_encode($iv . $encrypted);
}

function decrypt($data, $key) {
    $cipher = "AES-256-CBC";
    $data = base64_decode($data);
    $ivLength = openssl_cipher_iv_length($cipher);
    $iv = substr($data, 0, $ivLength);
    $encrypted = substr($data, $ivLength);
    return openssl_decrypt($encrypted, $cipher, $key, 0, $iv);
}

$key = "put something here";
$pass = "database user password";

$encrypted = encrypt ($pass, $key);

echo "$encrypted\n";

$dec = decrypt ($encrypted, $key);

echo "$dec\n";

?>
