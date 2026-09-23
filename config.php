<?php
/**
 * FaviconForge Pro - Configuration
 * 
 * Configurações centralizadas do sistema.
 * Ajuste conforme seu ambiente de produção.
 * 
 * @author FaviconForge Team
 * @version 1.0.0
 */

// Prevenir acesso direto
if (!defined('FAVICONFORGE_LOADED')) {
    http_response_code(403);
    exit('Acesso negado');
}

// ===================================
// Configurações de Ambiente
// ===================================

// Modo debug (desativar em produção!)
define('DEBUG_MODE', false);

// Timezone
date_default_timezone_set('America/Sao_Paulo');

// ===================================
// Configurações de Upload
// ===================================

// Tamanho máximo do arquivo (em bytes)
// 10MB = 10 * 1024 * 1024
define('MAX_FILE_SIZE', 10 * 1024 * 1024);

// Tipos MIME permitidos
define('ALLOWED_MIME_TYPES', [
    'image/png',
    'image/jpeg',
    'image/jpg',
    'image/svg+xml',
    'image/webp'
]);

// Extensões permitidas
define('ALLOWED_EXTENSIONS', ['png', 'jpg', 'jpeg', 'svg', 'webp']);

// ===================================
// Configurações de Diretórios
// ===================================

// Diretório base (ajuste para fora do public_html em produção)
// Em ambiente de produção, use algo como: /home/usuario/favicon_temp/
define('TEMP_DIR', __DIR__ . '/temp/');

// Diretório de uploads temporários
define('UPLOAD_DIR', TEMP_DIR . 'uploads/');

// Diretório de saída dos ícones gerados
define('OUTPUT_DIR', TEMP_DIR . 'output/');

// Tempo de vida dos arquivos temporários (em segundos)
// 1 hora = 3600 segundos
define('TEMP_FILE_LIFETIME', 3600);

// ===================================
// Configurações de Geração
// ===================================

// Qualidade JPEG (0-100)
define('JPEG_QUALITY', 95);

// Qualidade PNG (0-9, onde 0 = sem compressão)
define('PNG_QUALITY', 6);

// Usar Imagick se disponível (melhor qualidade)
define('PREFER_IMAGICK', true);

// ===================================
// Definições de Ícones por Plataforma
// ===================================

/**
 * Estrutura de cada ícone:
 * - size: Tamanho em pixels (largura = altura)
 * - name: Nome do arquivo
 * - purpose: Propósito do ícone (para manifest.json)
 * - folder: Pasta de destino
 */

// Favicons para navegadores
define('FAVICON_SIZES', [
    ['size' => 16,  'name' => 'favicon-16x16.png',   'folder' => 'favicon'],
    ['size' => 32,  'name' => 'favicon-32x32.png',   'folder' => 'favicon'],
    ['size' => 48,  'name' => 'favicon-48x48.png',   'folder' => 'favicon'],
    ['size' => 64,  'name' => 'favicon-64x64.png',   'folder' => 'favicon'],
    ['size' => 128, 'name' => 'favicon-128x128.png', 'folder' => 'favicon'],
    ['size' => 32,  'name' => 'favicon.ico',         'folder' => 'favicon', 'ico' => true],
]);

// Ícones PWA
define('PWA_SIZES', [
    ['size' => 192, 'name' => 'icon-192x192.png',          'folder' => 'pwa', 'purpose' => 'any'],
    ['size' => 512, 'name' => 'icon-512x512.png',          'folder' => 'pwa', 'purpose' => 'any'],
    ['size' => 192, 'name' => 'icon-192x192-maskable.png', 'folder' => 'pwa', 'purpose' => 'maskable'],
    ['size' => 512, 'name' => 'icon-512x512-maskable.png', 'folder' => 'pwa', 'purpose' => 'maskable'],
]);

// Ícones Chrome Extension
define('CHROME_SIZES', [
    ['size' => 16,  'name' => 'icon-16.png',  'folder' => 'chrome-extension'],
    ['size' => 32,  'name' => 'icon-32.png',  'folder' => 'chrome-extension'],
    ['size' => 48,  'name' => 'icon-48.png',  'folder' => 'chrome-extension'],
    ['size' => 128, 'name' => 'icon-128.png', 'folder' => 'chrome-extension'],
]);

// Ícones Android
define('ANDROID_SIZES', [
    ['size' => 512, 'name' => 'playstore-icon.png',        'folder' => 'android'],
    ['size' => 108, 'name' => 'ic_launcher_foreground.png', 'folder' => 'android'],
    ['size' => 108, 'name' => 'ic_launcher_background.png', 'folder' => 'android', 'background' => true],
    ['size' => 192, 'name' => 'android-chrome-192x192.png', 'folder' => 'android'],
    ['size' => 512, 'name' => 'android-chrome-512x512.png', 'folder' => 'android'],
]);

// Ícones Apple/iOS
define('IOS_SIZES', [
    ['size' => 180, 'name' => 'apple-touch-icon.png',         'folder' => 'apple'],
    ['size' => 180, 'name' => 'apple-touch-icon-180x180.png', 'folder' => 'apple'],
    ['size' => 167, 'name' => 'apple-touch-icon-167x167.png', 'folder' => 'apple'],
    ['size' => 152, 'name' => 'apple-touch-icon-152x152.png', 'folder' => 'apple'],
    ['size' => 120, 'name' => 'apple-touch-icon-120x120.png', 'folder' => 'apple'],
]);

// ===================================
// Funções de Configuração
// ===================================

/**
 * Obter todas as definições de ícones para uma plataforma
 * 
 * @param string $platform Nome da plataforma
 * @return array Lista de definições de ícones
 */
function getIconDefinitions(string $platform): array {
    $definitions = [
        'favicon' => FAVICON_SIZES,
        'pwa'     => PWA_SIZES,
        'chrome'  => CHROME_SIZES,
        'android' => ANDROID_SIZES,
        'ios'     => IOS_SIZES,
    ];
    
    return $definitions[$platform] ?? [];
}

/**
 * Criar diretórios necessários
 * 
 * @return bool Sucesso na criação
 */
function ensureDirectoriesExist(): bool {
    $dirs = [TEMP_DIR, UPLOAD_DIR, OUTPUT_DIR];
    
    foreach ($dirs as $dir) {
        if (!file_exists($dir)) {
            if (!mkdir($dir, 0755, true)) {
                return false;
            }
        }
        
        // Criar .htaccess para segurança
        $htaccess = $dir . '.htaccess';
        if (!file_exists($htaccess)) {
            file_put_contents($htaccess, "Deny from all\nOptions -Indexes\n");
        }
    }
    
    return true;
}

/**
 * Limpar arquivos temporários antigos
 * 
 * @return int Número de arquivos removidos
 */
function cleanupTempFiles(): int {
    $count = 0;
    $dirs = [UPLOAD_DIR, OUTPUT_DIR];
    
    foreach ($dirs as $dir) {
        if (!is_dir($dir)) continue;
        
        $files = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($dir, RecursiveDirectoryIterator::SKIP_DOTS),
            RecursiveIteratorIterator::CHILD_FIRST
        );
        
        foreach ($files as $file) {
            if (time() - $file->getMTime() > TEMP_FILE_LIFETIME) {
                if ($file->isDir()) {
                    @rmdir($file->getRealPath());
                } else {
                    @unlink($file->getRealPath());
                    $count++;
                }
            }
        }
    }
    
    return $count;
}

/**
 * Registrar erro de forma segura
 * 
 * @param string $message Mensagem de erro
 * @param array $context Contexto adicional
 */
function logError(string $message, array $context = []): void {
    if (DEBUG_MODE) {
        error_log(sprintf(
            "[FaviconForge] %s | %s | %s",
            date('Y-m-d H:i:s'),
            $message,
            json_encode($context)
        ));
    }
}
