<?php
// core/Security/Crypto.php

class Crypto {

    private const SECRET_KEY = 'CIIDI_APP_ENCRYPTION_MASTER_KEY_2026';
    private const CIPHER = 'aes-256-cbc';

    /**
     * Cifra una cadena utilizando AES-256-CBC con vector de inicialización aleatorio y firma HMAC-SHA256 (Encrypt-then-MAC).
     */
    public static function encrypt(string $plainText): string {
        if ($plainText === '') {
            return '';
        }

        $key = hash('sha256', self::SECRET_KEY, true);
        $ivLen = openssl_cipher_iv_length(self::CIPHER);
        $iv = openssl_random_pseudo_bytes($ivLen);
        $encrypted = openssl_encrypt($plainText, self::CIPHER, $key, OPENSSL_RAW_DATA, $iv);
        $hmac = hash_hmac('sha256', $iv . $encrypted, $key, true);

        return 'ENC:' . base64_encode($iv . $hmac . $encrypted);
    }

    /**
     * Descifra una cadena previamente cifrada con `encrypt`. Si no posee el prefijo 'ENC:', la devuelve intacta (retrocompatibilidad).
     */
    public static function decrypt(string $cipherText): string {
        if (!str_starts_with($cipherText, 'ENC:')) {
            return $cipherText;
        }

        $raw = base64_decode(substr($cipherText, 4));
        if ($raw === false) {
            return '';
        }

        $key = hash('sha256', self::SECRET_KEY, true);
        $ivLen = openssl_cipher_iv_length(self::CIPHER);
        $hmacLen = 32;

        if (strlen($raw) < ($ivLen + $hmacLen)) {
            return '';
        }

        $iv = substr($raw, 0, $ivLen);
        $hmac = substr($raw, $ivLen, $hmacLen);
        $ciphertextRaw = substr($raw, $ivLen + $hmacLen);

        $calcmac = hash_hmac('sha256', $iv . $ciphertextRaw, $key, true);
        if (!hash_equals($hmac, $calcmac)) {
            return ''; // Integridad comprometida
        }

        $decrypted = openssl_decrypt($ciphertextRaw, self::CIPHER, $key, OPENSSL_RAW_DATA, $iv);
        return $decrypted !== false ? $decrypted : '';
    }

    /**
     * Verifica si una cadena está en formato cifrado seguro.
     */
    public static function isEncrypted(string $text): bool {
        return str_starts_with($text, 'ENC:');
    }
}
