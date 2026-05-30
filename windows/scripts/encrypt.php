<?php
function encrypt($data, $key) {
    $cipher = "AES-256-CBC";
    $iv = random_bytes(openssl_cipher_iv_length($cipher));
    $encrypted = openssl_encrypt($data, $cipher, $key, 0, $iv);
    return base64_encode($iv . $encrypted);
}

$encrypted = encrypt ($argv[1], $argv[2]);

echo "$encrypted\n";

?>
