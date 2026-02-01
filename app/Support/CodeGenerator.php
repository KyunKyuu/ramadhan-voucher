<?php

namespace App\Support;

class CodeGenerator
{
    /**
     * Generate a unique code using base32 encoding.
     *
     * @param int $length Length of the code to generate
     * @return string Generated code
     */
    public static function make(int $length = 14): string
    {
        // Generate random bytes
        $bytes = random_bytes(ceil($length * 0.8));
        
        // Convert to base32 (using custom alphabet without ambiguous characters)
        $base32Alphabet = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';
        $base32 = '';
        
        foreach (str_split($bytes) as $byte) {
            $base32 .= $base32Alphabet[ord($byte) % 32];
        }
        
        // Return the requested length
        return strtoupper(substr($base32, 0, $length));
    }

    /**
     * Generate a public token for claims.
     *
     * @param int $length Length of the token
     * @return string Generated token
     */
    public static function makeToken(int $length = 32): string
    {
        $bytes = random_bytes($length);
        return bin2hex($bytes);
    }
}
