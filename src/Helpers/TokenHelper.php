<?php
// filepath: src/Helpers/TokenHelper.php

namespace SecretariaFiap\Helpers;

class TokenHelper
{
    const BLACKLIST_FILE = __DIR__ . "/../../public/blacklist.txt";

    /**
     * Gera um token baseado em email, data e um salt do ambiente.
     */
    public static function gerar(string $email): string
    {
        $salt = getenv('TOKEN_SALT') ?: 'segredo_padrao';
        $payload = [
            'email' => $email,
            'exp' => time() + 60 * 60 * 2, // 2 horas de validade
            'jti' => GeradorUuid::gerar() // Inclui o JTI no payload que é um identificador único do token
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

        // --- VERIFICAÇÃO DA BLACKLIST ---
        if (!isset($payload['jti'])) {
            return null; // Token sem JTI não pode ser revogado
        }

        // Se o token (JTI) estiver na blacklist, retorne null
        if (self::verificarTokenBlacklist($payload['jti'])) {
            return null; // Token está na lista negra (revogado)
        }

        return $payload;
    }

    /**
     * Adiciona o JTI de um token à lista negra.
     * Cada JTI é armazenado em uma nova linha.
     * @param string $token Token a ser adicionado à blacklist.
     * @return bool True se adicionado com sucesso, false caso contrário.
     */
    public static function adicionarTokenBlacklist(string $token): bool
    {
        $partes = explode('.', $token);
        if (count($partes) !== 2) {
            return false;
        }
        [$base64, $assinatura] = $partes;

        $payload = json_decode(base64_decode($base64), true);
        $jti = $payload['jti'];

        // Garante que o diretório exista
        $dir = dirname(self::BLACKLIST_FILE);

        if (!is_dir($dir)) {
            mkdir($dir, 0777, true); // Cria o diretório recursivamente
        }

        // Abre o arquivo para escrita, adiciona ao final e bloqueia exclusivamente
        // FILE_APPEND: Adiciona ao final do arquivo
        // LOCK_EX: Adquire um bloqueio exclusivo durante a escrita para evitar problemas de concorrência
        return (bool) file_put_contents(self::BLACKLIST_FILE, $jti . PHP_EOL, FILE_APPEND);
    }

    /**
     * Verifica se um JTI está presente na lista negra do arquivo.
     * @param string $jti ID único do Token a ser verificado.
     * @return bool True se o token estiver na blacklist, false caso contrário.
     */
    public static function verificarTokenBlacklist(string $jti): bool
    {
        if (!file_exists(self::BLACKLIST_FILE)) {
            return false; // Se o arquivo não existe, nenhum token está na blacklist
        }

        $handle = fopen(self::BLACKLIST_FILE, 'r');
        if ($handle === false) {
            return false; // Não foi possível abrir o arquivo
        }

        // Adquire um bloqueio compartilhado durante a leitura
        // Isso ajuda a evitar leitura de arquivo incompleto se outro processo estiver escrevendo
        if (!flock($handle, LOCK_SH)) {
            fclose($handle);
            return false;
        }

        $found = false;
        while (($line = fgets($handle)) !== false) {
            if (trim($line) === $jti) {
                $found = true;
                break;
            }
        }

        flock($handle, LOCK_UN); // Libera o bloqueio
        fclose($handle);

        return $found;
    }
}