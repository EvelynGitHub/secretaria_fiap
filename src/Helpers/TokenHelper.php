<?php
// filepath: src/Helpers/TokenHelper.php

namespace SecretariaFiap\Helpers;

class TokenHelper
{
    /**
     * Gera um token baseado em email, data e um salt do ambiente.
     */
    public static function gerar(string $email): string
    {
        $salt = getenv('TOKEN_SALT') ?: 'segredo_padrao';
        $payload = [
            'email' => $email,
            'exp' => time() + 60 * 60 * 2 // 2 horas de validade
        ];
        $json = json_encode($payload);
        $base64 = base64_encode($json);
        $assinatura = hash_hmac('sha256', $base64, $salt);
        return $base64 . '.' . $assinatura;
    }

    /**
     * Valida e decodifica o token.
     * Retorna array com dados do payload se válido, ou null se inválido/expirado.
     */
    public static function decodificar(string $token): ?array
    {
        $salt = getenv('TOKEN_SALT') ?: 'segredo_padrao';
        $partes = explode('.', $token);
        if (count($partes) !== 2) {
            return null;
        }
        [$base64, $assinatura] = $partes;
        $assinaturaEsperada = hash_hmac('sha256', $base64, $salt);

        if (!hash_equals($assinaturaEsperada, $assinatura)) {
            return null;
        }

        $payload = json_decode(base64_decode($base64), true);
        if (!$payload || !isset($payload['exp']) || $payload['exp'] < time()) {
            return null;
        }
        return $payload;
    }
}