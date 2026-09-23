<?php
/**
 * FaviconForge Pro - Icon Generator API
 * 
 * Endpoint principal para geração de favicons e ícones.
 * Processa uploads, gera ícones em múltiplos tamanhos e cria ZIP.
 * 
 * @author FaviconForge Team
 * @version 1.0.0
 */

// Definir constante de segurança antes de incluir config
define('FAVICONFORGE_LOADED', true);

// Headers de segurança e CORS
header('Content-Type: application/json; charset=utf-8');
header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: DENY');
header('X-XSS-Protection: 1; mode=block');

// Em produção, ajuste para seu domínio específico
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Responder a preflight requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// Apenas POST é permitido
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    sendError('Método não permitido', 405);
}

// Incluir configurações
require_once __DIR__ . '/config.php';

// ===================================
// Funções Auxiliares
// ===================================

/**
 * Enviar resposta de erro JSON
 */
function sendError(string $message, int $code = 400): void {
    http_response_code($code);
    echo json_encode([
        'success' => false,
        'error' => $message
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

/**
 * Enviar resposta de sucesso JSON
 */
function sendSuccess(array $data): void {
    echo json_encode(array_merge(['success' => true], $data), JSON_UNESCAPED_UNICODE);
    exit;
}

/**
 * Gerar ID único para a sessão
 */
function generateSessionId(): string {
    return bin2hex(random_bytes(16));
}

/**
 * Validar arquivo de upload
 */
function validateUpload(array $file): void {
    // Verificar erro de upload
    if ($file['error'] !== UPLOAD_ERR_OK) {
        $errors = [
            UPLOAD_ERR_INI_SIZE => 'Arquivo excede o limite do servidor',
            UPLOAD_ERR_FORM_SIZE => 'Arquivo excede o limite do formulário',
            UPLOAD_ERR_PARTIAL => 'Upload incompleto',
            UPLOAD_ERR_NO_FILE => 'Nenhum arquivo enviado',
            UPLOAD_ERR_NO_TMP_DIR => 'Erro no servidor',
            UPLOAD_ERR_CANT_WRITE => 'Erro ao salvar arquivo',
        ];
        sendError($errors[$file['error']] ?? 'Erro no upload');
    }
    
    // Verificar tamanho
    if ($file['size'] > MAX_FILE_SIZE) {
        sendError('Arquivo muito grande. Máximo: ' . (MAX_FILE_SIZE / 1024 / 1024) . 'MB');
    }
    
    // Verificar extensão
    $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if (!in_array($extension, ALLOWED_EXTENSIONS)) {
        sendError('Extensão não permitida. Use: ' . implode(', ', ALLOWED_EXTENSIONS));
    }
    
    // Verificar MIME type real (mais seguro)
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mimeType = $finfo->file($file['tmp_name']);
    
    if (!in_array($mimeType, ALLOWED_MIME_TYPES)) {
        // SVG pode ter MIME text/xml ou text/plain
        if ($extension === 'svg' && (strpos($mimeType, 'text/') === 0 || strpos($mimeType, 'image/svg') === 0)) {
            // OK para SVG
        } else {
            sendError('Tipo de arquivo inválido');
        }
    }
    
    // Verificar se é realmente uma imagem (exceto SVG)
    if ($extension !== 'svg') {
        $imageInfo = @getimagesize($file['tmp_name']);
        if ($imageInfo === false) {
            sendError('Arquivo não é uma imagem válida');
        }
    }
}

/**
 * Sanitizar nome de arquivo
 */
function sanitizeFilename(string $filename): string {
    $filename = preg_replace('/[^a-zA-Z0-9._-]/', '', $filename);
    return substr($filename, 0, 100);
}

// ===================================
// Classe Principal de Geração
// ===================================

class IconGenerator {
    private string $sessionId;
    private string $uploadPath;
    private string $outputPath;
    private string $sourcePath;
    private array $settings;
    private array $generatedIcons = [];
    private bool $useImagick;
    
    public function __construct(string $sessionId, array $settings) {
        $this->sessionId = $sessionId;
        $this->settings = $settings;
        $this->uploadPath = UPLOAD_DIR . $sessionId . '/';
        $this->outputPath = OUTPUT_DIR . $sessionId . '/';
        $this->useImagick = PREFER_IMAGICK && extension_loaded('imagick');
        
        // Criar diretórios
        if (!mkdir($this->uploadPath, 0755, true)) {
            throw new Exception('Erro ao criar diretório de upload');
        }
        if (!mkdir($this->outputPath, 0755, true)) {
            throw new Exception('Erro ao criar diretório de saída');
        }
    }
    
    /**
     * Processar imagem de origem
     */
    public function processSourceImage(array $uploadedFile): void {
        $extension = strtolower(pathinfo($uploadedFile['name'], PATHINFO_EXTENSION));
        $this->sourcePath = $this->uploadPath . 'source.' . $extension;
        
        if (!move_uploaded_file($uploadedFile['tmp_name'], $this->sourcePath)) {
            throw new Exception('Erro ao mover arquivo');
        }
        
        // Converter SVG para PNG se necessário
        if ($extension === 'svg') {
            $this->convertSvgToPng();
        }
    }
    
    /**
     * Converter SVG para PNG
     */
    private function convertSvgToPng(): void {
        $pngPath = $this->uploadPath . 'source.png';
        
        if ($this->useImagick) {
            $imagick = new Imagick();
            $imagick->setBackgroundColor(new ImagickPixel('transparent'));
            $imagick->readImage($this->sourcePath);
            $imagick->setImageFormat('png');
            
            // Redimensionar para tamanho base grande
            $imagick->resizeImage(1024, 1024, Imagick::FILTER_LANCZOS, 1, true);
            $imagick->writeImage($pngPath);
            $imagick->destroy();
        } else {
            // Fallback usando librsvg ou rsvg-convert se disponível
            $cmd = sprintf(
                'rsvg-convert -w 1024 -h 1024 %s -o %s 2>&1',
                escapeshellarg($this->sourcePath),
                escapeshellarg($pngPath)
            );
            exec($cmd, $output, $returnVar);
            
            if ($returnVar !== 0) {
                // Tentar usar GD com simplexml (muito básico)
                $this->convertSvgWithGd();
                return;
            }
        }
        
        if (file_exists($pngPath)) {
            $this->sourcePath = $pngPath;
        }
    }
    
    /**
     * Converter SVG com GD (fallback básico)
     */
    private function convertSvgWithGd(): void {
        // GD não suporta SVG nativamente
        // Esta é uma conversão muito básica que pode não funcionar para SVGs complexos
        throw new Exception('SVG não suportado neste servidor. Use PNG ou JPG.');
    }
    
    /**
     * Gerar todos os ícones selecionados
     */
    public function generateIcons(): void {
        $platforms = $this->settings['platforms'] ?? [];
        
        foreach ($platforms as $platform) {
            $definitions = getIconDefinitions($platform);
            $folderPath = $this->outputPath . $platform . '/';
            
            if (!file_exists($folderPath)) {
                mkdir($folderPath, 0755, true);
            }
            
            foreach ($definitions as $def) {
                $this->generateSingleIcon($def, $folderPath, $platform);
            }
        }
    }
    
    /**
     * Gerar um único ícone
     */
    private function generateSingleIcon(array $def, string $folderPath, string $platform): void {
        $size = $def['size'];
        $filename = $def['name'];
        $outputFile = $folderPath . $filename;
        
        // Verificar se é ícone .ico
        if (isset($def['ico']) && $def['ico']) {
            $this->generateIcoFile($size, $outputFile);
            return;
        }
        
        // Verificar se é background sólido (para adaptive icons)
        if (isset($def['background']) && $def['background']) {
            $this->generateBackgroundIcon($size, $outputFile);
            return;
        }
        
        // Verificar se é maskable
        $isMaskable = isset($def['purpose']) && $def['purpose'] === 'maskable';
        
        if ($this->useImagick) {
            $this->generateWithImagick($size, $outputFile, $isMaskable);
        } else {
            $this->generateWithGd($size, $outputFile, $isMaskable);
        }
        
        // Adicionar à lista de gerados
        $this->generatedIcons[] = [
            'name' => $filename,
            'size' => $size . '×' . $size,
            'type' => strtoupper($platform),
            'file' => $platform . '/' . $filename,
            'preview' => $this->getIconPreviewUrl($platform . '/' . $filename),
            'purpose' => $def['purpose'] ?? 'any'
        ];
    }
    
    /**
     * Gerar ícone usando Imagick
     */
    private function generateWithImagick(int $size, string $outputFile, bool $isMaskable): void {
        $canvas = new Imagick();
        $canvas->newImage($size, $size, new ImagickPixel('transparent'));
        $canvas->setImageFormat('png');
        
        // Adicionar fundo se configurado
        if ($this->settings['background'] === 'solid') {
            $draw = new ImagickDraw();
            $draw->setFillColor(new ImagickPixel($this->settings['bgColor']));
            
            $radius = ($this->settings['borderRadius'] / 100) * ($size / 2);
            if ($radius > 0) {
                $draw->roundRectangle(0, 0, $size - 1, $size - 1, $radius, $radius);
            } else {
                $draw->rectangle(0, 0, $size - 1, $size - 1);
            }
            
            $canvas->drawImage($draw);
        }
        
        // Calcular padding
        $padding = $this->settings['padding'] / 100;
        if ($isMaskable && $this->settings['maskableMode']) {
            // Safe area adicional para maskable (10% extra)
            $padding += 0.1;
        }
        
        $paddingPx = (int)($size * $padding);
        $drawSize = $size - ($paddingPx * 2);
        
        // Carregar e redimensionar imagem fonte
        $source = new Imagick($this->sourcePath);
        $source->setImageBackgroundColor(new ImagickPixel('transparent'));
        
        // Manter proporção
        $srcWidth = $source->getImageWidth();
        $srcHeight = $source->getImageHeight();
        $ratio = min($drawSize / $srcWidth, $drawSize / $srcHeight);
        $newWidth = (int)($srcWidth * $ratio);
        $newHeight = (int)($srcHeight * $ratio);
        
        $source->resizeImage($newWidth, $newHeight, Imagick::FILTER_LANCZOS, 1);
        
        // Centralizar
        $offsetX = (int)(($size - $newWidth) / 2);
        $offsetY = (int)(($size - $newHeight) / 2);
        
        // Aplicar clipping de borda arredondada se necessário
        if ($this->settings['borderRadius'] > 0 && $this->settings['background'] !== 'solid') {
            $mask = new Imagick();
            $mask->newImage($size, $size, new ImagickPixel('transparent'));
            $draw = new ImagickDraw();
            $draw->setFillColor(new ImagickPixel('white'));
            $radius = ($this->settings['borderRadius'] / 100) * ($size / 2);
            $draw->roundRectangle(0, 0, $size - 1, $size - 1, $radius, $radius);
            $mask->drawImage($draw);
            
            $canvas->compositeImage($source, Imagick::COMPOSITE_OVER, $offsetX, $offsetY);
            $canvas->compositeImage($mask, Imagick::COMPOSITE_DSTIN, 0, 0);
            
            $mask->destroy();
        } else {
            $canvas->compositeImage($source, Imagick::COMPOSITE_OVER, $offsetX, $offsetY);
        }
        
        // Otimizar PNG
        $canvas->setImageCompression(Imagick::COMPRESSION_ZIP);
        $canvas->setImageCompressionQuality(PNG_QUALITY * 10);
        $canvas->stripImage();
        
        $canvas->writeImage($outputFile);
        
        $source->destroy();
        $canvas->destroy();
    }
    
    /**
     * Gerar ícone usando GD
     */
    private function generateWithGd(int $size, string $outputFile, bool $isMaskable): void {
        // Criar canvas
        $canvas = imagecreatetruecolor($size, $size);
        imagesavealpha($canvas, true);
        imagealphablending($canvas, false);
        
        // Fundo transparente ou sólido
        if ($this->settings['background'] === 'solid') {
            $bgColor = $this->hexToRgb($this->settings['bgColor']);
            $bg = imagecolorallocate($canvas, $bgColor['r'], $bgColor['g'], $bgColor['b']);
            
            if ($this->settings['borderRadius'] > 0) {
                // Fundo transparente primeiro
                $transparent = imagecolorallocatealpha($canvas, 0, 0, 0, 127);
                imagefill($canvas, 0, 0, $transparent);
                imagealphablending($canvas, true);
                
                // Desenhar retângulo arredondado
                $radius = (int)(($this->settings['borderRadius'] / 100) * ($size / 2));
                $this->imageRoundedRectangle($canvas, 0, 0, $size - 1, $size - 1, $radius, $bg);
            } else {
                imagefill($canvas, 0, 0, $bg);
            }
        } else {
            $transparent = imagecolorallocatealpha($canvas, 0, 0, 0, 127);
            imagefill($canvas, 0, 0, $transparent);
        }
        
        imagealphablending($canvas, true);
        
        // Calcular padding
        $padding = $this->settings['padding'] / 100;
        if ($isMaskable && $this->settings['maskableMode']) {
            $padding += 0.1;
        }
        
        $paddingPx = (int)($size * $padding);
        $drawSize = $size - ($paddingPx * 2);
        
        // Carregar imagem fonte
        $source = $this->loadImage($this->sourcePath);
        if (!$source) {
            throw new Exception('Erro ao carregar imagem fonte');
        }
        
        $srcWidth = imagesx($source);
        $srcHeight = imagesy($source);
        
        // Manter proporção
        $ratio = min($drawSize / $srcWidth, $drawSize / $srcHeight);
        $newWidth = (int)($srcWidth * $ratio);
        $newHeight = (int)($srcHeight * $ratio);
        
        // Centralizar
        $offsetX = (int)(($size - $newWidth) / 2);
        $offsetY = (int)(($size - $newHeight) / 2);
        
        // Redimensionar com alta qualidade
        imagecopyresampled(
            $canvas, $source,
            $offsetX, $offsetY, 0, 0,
            $newWidth, $newHeight, $srcWidth, $srcHeight
        );
        
        imagedestroy($source);
        
        // Salvar PNG
        imagesavealpha($canvas, true);
        imagepng($canvas, $outputFile, PNG_QUALITY);
        imagedestroy($canvas);
    }
    
    /**
     * Desenhar retângulo arredondado com GD
     */
    private function imageRoundedRectangle($img, $x1, $y1, $x2, $y2, $radius, $color): void {
        // Cantos
        imagefilledellipse($img, $x1 + $radius, $y1 + $radius, $radius * 2, $radius * 2, $color);
        imagefilledellipse($img, $x2 - $radius, $y1 + $radius, $radius * 2, $radius * 2, $color);
        imagefilledellipse($img, $x1 + $radius, $y2 - $radius, $radius * 2, $radius * 2, $color);
        imagefilledellipse($img, $x2 - $radius, $y2 - $radius, $radius * 2, $radius * 2, $color);
        
        // Retângulos
        imagefilledrectangle($img, $x1 + $radius, $y1, $x2 - $radius, $y2, $color);
        imagefilledrectangle($img, $x1, $y1 + $radius, $x2, $y2 - $radius, $color);
    }
    
    /**
     * Carregar imagem de qualquer formato suportado
     */
    private function loadImage(string $path) {
        $info = getimagesize($path);
        if (!$info) return null;
        
        switch ($info['mime']) {
            case 'image/png':
                return imagecreatefrompng($path);
            case 'image/jpeg':
                return imagecreatefromjpeg($path);
            case 'image/webp':
                return imagecreatefromwebp($path);
            case 'image/gif':
                return imagecreatefromgif($path);
            default:
                return null;
        }
    }
    
    /**
     * Converter hex para RGB
     */
    private function hexToRgb(string $hex): array {
        $hex = ltrim($hex, '#');
        return [
            'r' => hexdec(substr($hex, 0, 2)),
            'g' => hexdec(substr($hex, 2, 2)),
            'b' => hexdec(substr($hex, 4, 2))
        ];
    }
    
    /**
     * Gerar arquivo ICO
     */
    private function generateIcoFile(int $size, string $outputFile): void {
        // Primeiro gerar PNG
        $pngFile = str_replace('.ico', '.png', $outputFile);
        
        if ($this->useImagick) {
            $this->generateWithImagick($size, $pngFile, false);
        } else {
            $this->generateWithGd($size, $pngFile, false);
        }
        
        // Converter para ICO
        if ($this->useImagick) {
            $imagick = new Imagick($pngFile);
            $imagick->setImageFormat('ico');
            $imagick->writeImage($outputFile);
            $imagick->destroy();
        } else {
            // Fallback: copiar PNG como ICO (não ideal, mas funciona em navegadores modernos)
            // Para ICO real, seria necessário biblioteca adicional
            $this->pngToIco($pngFile, $outputFile);
        }
        
        // Remover PNG temporário
        if (file_exists($pngFile)) {
            unlink($pngFile);
        }
    }
    
    /**
     * Converter PNG para ICO (formato básico)
     */
    private function pngToIco(string $pngFile, string $icoFile): void {
        $png = imagecreatefrompng($pngFile);
        $width = imagesx($png);
        $height = imagesy($png);
        
        // Header ICO
        $ico = pack('vvv', 0, 1, 1); // Reserved, Type, Count
        
        // Directory entry
        $ico .= pack('CCCCvvVV',
            $width < 256 ? $width : 0,  // Width
            $height < 256 ? $height : 0, // Height
            0, // Color palette
            0, // Reserved
            1, // Color planes
            32, // Bits per pixel
            0, // Size (will be updated)
            22 // Offset to data
        );
        
        // Capture PNG data
        ob_start();
        imagepng($png);
        $pngData = ob_get_clean();
        
        // Update size in directory
        $size = strlen($pngData);
        $ico = substr($ico, 0, 14) . pack('V', $size) . substr($ico, 18);
        
        // Write file
        file_put_contents($icoFile, $ico . $pngData);
        
        imagedestroy($png);
    }
    
    /**
     * Gerar ícone de background (para adaptive icons)
     */
    private function generateBackgroundIcon(int $size, string $outputFile): void {
        if ($this->useImagick) {
            $canvas = new Imagick();
            $bgColor = $this->settings['background'] === 'solid' 
                ? $this->settings['bgColor'] 
                : '#ffffff';
            $canvas->newImage($size, $size, new ImagickPixel($bgColor));
            $canvas->setImageFormat('png');
            $canvas->writeImage($outputFile);
            $canvas->destroy();
        } else {
            $canvas = imagecreatetruecolor($size, $size);
            $bgColor = $this->hexToRgb($this->settings['background'] === 'solid' 
                ? $this->settings['bgColor'] 
                : '#ffffff');
            $bg = imagecolorallocate($canvas, $bgColor['r'], $bgColor['g'], $bgColor['b']);
            imagefill($canvas, 0, 0, $bg);
            imagepng($canvas, $outputFile, PNG_QUALITY);
            imagedestroy($canvas);
        }
        
        $this->generatedIcons[] = [
            'name' => basename($outputFile),
            'size' => $size . '×' . $size,
            'type' => 'ANDROID',
            'file' => 'android/' . basename($outputFile),
            'preview' => $this->getIconPreviewUrl('android/' . basename($outputFile)),
            'purpose' => 'background'
        ];
    }
    
    /**
     * Obter URL de preview do ícone
     */
    private function getIconPreviewUrl(string $relativePath): string {
        // Retornar caminho relativo à raiz do site
        return 'temp/output/' . $this->sessionId . '/' . $relativePath . '?t=' . time();
    }
    
    /**
     * Gerar manifest.json para PWA
     */
    public function generateManifest(): ?array {
        if (!in_array('pwa', $this->settings['platforms'])) {
            return null;
        }
        
        $icons = [];
        foreach ($this->generatedIcons as $icon) {
            if ($icon['type'] === 'PWA') {
                $icons[] = [
                    'src' => '/icons/' . $icon['file'],
                    'sizes' => $icon['size'],
                    'type' => 'image/png',
                    'purpose' => $icon['purpose']
                ];
            }
        }
        
        $manifest = [
            'name' => 'Seu App',
            'short_name' => 'App',
            'description' => 'Descrição do seu aplicativo',
            'start_url' => '/',
            'display' => 'standalone',
            'background_color' => $this->settings['background'] === 'solid' 
                ? $this->settings['bgColor'] 
                : '#ffffff',
            'theme_color' => $this->settings['background'] === 'solid' 
                ? $this->settings['bgColor'] 
                : '#6366f1',
            'icons' => $icons
        ];
        
        // Salvar manifest.json
        $manifestPath = $this->outputPath . 'manifest.json';
        file_put_contents($manifestPath, json_encode($manifest, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
        
        return $manifest;
    }
    
    /**
     * Gerar código HTML
     */
    public function generateHtmlCode(): string {
        $html = "<!-- Favicons gerados por FaviconForge Pro -->\n";
        
        // Favicons
        if (in_array('favicon', $this->settings['platforms'])) {
            $html .= '<link rel="icon" type="image/x-icon" href="/favicon.ico">' . "\n";
            $html .= '<link rel="icon" type="image/png" sizes="16x16" href="/icons/favicon/favicon-16x16.png">' . "\n";
            $html .= '<link rel="icon" type="image/png" sizes="32x32" href="/icons/favicon/favicon-32x32.png">' . "\n";
            $html .= '<link rel="icon" type="image/png" sizes="48x48" href="/icons/favicon/favicon-48x48.png">' . "\n";
        }
        
        // Apple Touch Icons
        if (in_array('ios', $this->settings['platforms'])) {
            $html .= "\n<!-- Apple Touch Icons -->\n";
            $html .= '<link rel="apple-touch-icon" href="/icons/apple/apple-touch-icon.png">' . "\n";
            $html .= '<link rel="apple-touch-icon" sizes="152x152" href="/icons/apple/apple-touch-icon-152x152.png">' . "\n";
            $html .= '<link rel="apple-touch-icon" sizes="167x167" href="/icons/apple/apple-touch-icon-167x167.png">' . "\n";
            $html .= '<link rel="apple-touch-icon" sizes="180x180" href="/icons/apple/apple-touch-icon-180x180.png">' . "\n";
        }
        
        // PWA Manifest
        if (in_array('pwa', $this->settings['platforms'])) {
            $html .= "\n<!-- PWA -->\n";
            $html .= '<link rel="manifest" href="/manifest.json">' . "\n";
            $html .= '<meta name="theme-color" content="' . ($this->settings['background'] === 'solid' ? $this->settings['bgColor'] : '#6366f1') . '">' . "\n";
        }
        
        // Android
        if (in_array('android', $this->settings['platforms'])) {
            $html .= "\n<!-- Android -->\n";
            $html .= '<link rel="icon" type="image/png" sizes="192x192" href="/icons/android/android-chrome-192x192.png">' . "\n";
            $html .= '<link rel="icon" type="image/png" sizes="512x512" href="/icons/android/android-chrome-512x512.png">' . "\n";
        }
        
        return $html;
    }
    
    /**
     * Criar arquivo ZIP com todos os ícones
     */
    public function createZip(): string {
        $zipPath = $this->outputPath . 'favicons.zip';
        $zip = new ZipArchive();
        
        if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
            throw new Exception('Erro ao criar arquivo ZIP');
        }
        
        // Adicionar ícones
        foreach ($this->settings['platforms'] as $platform) {
            $folderPath = $this->outputPath . $platform . '/';
            if (is_dir($folderPath)) {
                $files = scandir($folderPath);
                foreach ($files as $file) {
                    if ($file !== '.' && $file !== '..') {
                        $zip->addFile($folderPath . $file, $platform . '/' . $file);
                    }
                }
            }
        }
        
        // Adicionar manifest.json se existir
        $manifestPath = $this->outputPath . 'manifest.json';
        if (file_exists($manifestPath)) {
            $zip->addFile($manifestPath, 'manifest.json');
        }
        
        // Adicionar README
        $readme = $this->generateReadme();
        $zip->addFromString('README.txt', $readme);
        
        // Adicionar HTML snippet
        $zip->addFromString('html-code.html', $this->generateHtmlCode());
        
        $zip->close();
        
        return 'temp/output/' . $this->sessionId . '/favicons.zip';
    }
    
    /**
     * Gerar arquivo README
     */
    private function generateReadme(): string {
        $readme = "======================================\n";
        $readme .= "  FAVICONS GERADOS POR FAVICONFORGE PRO\n";
        $readme .= "======================================\n\n";
        $readme .= "Data de geração: " . date('d/m/Y H:i:s') . "\n\n";
        $readme .= "INSTRUÇÕES DE USO:\n";
        $readme .= "-----------------\n\n";
        
        $readme .= "1. FAVICONS (pasta /favicon/)\n";
        $readme .= "   - Copie favicon.ico para a raiz do seu site\n";
        $readme .= "   - Copie os PNGs para /icons/favicon/\n\n";
        
        if (in_array('pwa', $this->settings['platforms'])) {
            $readme .= "2. PWA (pasta /pwa/)\n";
            $readme .= "   - Copie os ícones para /icons/pwa/\n";
            $readme .= "   - Copie manifest.json para a raiz\n";
            $readme .= "   - Edite o manifest.json com os dados do seu app\n\n";
        }
        
        if (in_array('ios', $this->settings['platforms'])) {
            $readme .= "3. APPLE/iOS (pasta /apple/)\n";
            $readme .= "   - Copie os ícones para /icons/apple/\n";
            $readme .= "   - Adicione as tags <link> ao seu HTML\n\n";
        }
        
        if (in_array('android', $this->settings['platforms'])) {
            $readme .= "4. ANDROID (pasta /android/)\n";
            $readme .= "   - Use playstore-icon.png para a Play Store\n";
            $readme .= "   - Use ic_launcher_* para adaptive icons\n\n";
        }
        
        if (in_array('chrome', $this->settings['platforms'])) {
            $readme .= "5. CHROME EXTENSION (pasta /chrome-extension/)\n";
            $readme .= "   - Use estes ícones no manifest.json da extensão\n\n";
        }
        
        $readme .= "CÓDIGO HTML:\n";
        $readme .= "------------\n";
        $readme .= "Veja o arquivo html-code.html para o código pronto.\n\n";
        
        $readme .= "======================================\n";
        $readme .= "  Gerado por FaviconForge Pro\n";
        $readme .= "======================================\n";
        
        return $readme;
    }
    
    /**
     * Obter lista de ícones gerados
     */
    public function getGeneratedIcons(): array {
        return $this->generatedIcons;
    }
    
    /**
     * Obter caminho de download
     */
    public function getDownloadPath(): string {
        return 'temp/output/' . $this->sessionId;
    }
}

// ===================================
// Processamento Principal
// ===================================

try {
    // Garantir que diretórios existem
    if (!ensureDirectoriesExist()) {
        sendError('Erro ao criar diretórios temporários', 500);
    }
    
    // Verificar permissões de escrita
    if (!is_writable(UPLOAD_DIR)) {
        sendError('Diretório de upload sem permissão de escrita. Verifique as permissões da pasta temp/uploads/', 500);
    }
    
    if (!is_writable(OUTPUT_DIR)) {
        sendError('Diretório de saída sem permissão de escrita. Verifique as permissões da pasta temp/output/', 500);
    }
    
    // Limpar arquivos antigos (em background)
    if (rand(1, 10) === 1) { // 10% das requisições
        cleanupTempFiles();
    }
    
    // Validar upload
    if (!isset($_FILES['image'])) {
        sendError('Nenhuma imagem enviada');
    }
    
    validateUpload($_FILES['image']);
    
    // Obter configurações
    $platforms = [];
    if (isset($_POST['platforms'])) {
        $decoded = json_decode($_POST['platforms'], true);
        if (is_array($decoded)) {
            $validPlatforms = ['favicon', 'pwa', 'chrome', 'android', 'ios'];
            $platforms = array_intersect($decoded, $validPlatforms);
        }
    }
    
    if (empty($platforms)) {
        sendError('Selecione pelo menos uma plataforma');
    }
    
    $settings = [
        'platforms' => array_values($platforms), // Garantir array indexado
        'background' => in_array($_POST['background'] ?? '', ['transparent', 'solid']) 
            ? $_POST['background'] 
            : 'transparent',
        'bgColor' => preg_match('/^#[0-9A-Fa-f]{6}$/', $_POST['bgColor'] ?? '') 
            ? $_POST['bgColor'] 
            : '#ffffff',
        'padding' => max(0, min(30, intval($_POST['padding'] ?? 10))),
        'borderRadius' => max(0, min(50, intval($_POST['borderRadius'] ?? 0))),
        'maskableMode' => ($_POST['maskableMode'] ?? '0') === '1'
    ];
    
    // Gerar sessão e processar
    $sessionId = generateSessionId();
    $generator = new IconGenerator($sessionId, $settings);
    
    // Processar imagem fonte
    $generator->processSourceImage($_FILES['image']);
    
    // Gerar ícones
    $generator->generateIcons();
    
    // Verificar se ícones foram gerados
    $icons = $generator->getGeneratedIcons();
    if (empty($icons)) {
        sendError('Nenhum ícone foi gerado. Verifique a imagem enviada.');
    }
    
    // Gerar manifest
    $manifest = $generator->generateManifest();
    
    // Gerar código HTML
    $htmlCode = $generator->generateHtmlCode();
    
    // Criar ZIP
    $zipFile = $generator->createZip();
    
    // Verificar se o ZIP foi criado
    $zipFullPath = __DIR__ . '/' . $zipFile;
    if (!file_exists($zipFullPath)) {
        // Tentar caminho alternativo
        $zipFullPath = OUTPUT_DIR . $sessionId . '/favicons.zip';
    }
    
    // Resposta de sucesso
    sendSuccess([
        'sessionId' => $sessionId,
        'icons' => $icons,
        'downloadPath' => 'temp/output/' . $sessionId,
        'zipFile' => 'temp/output/' . $sessionId . '/favicons.zip',
        'htmlCode' => $htmlCode,
        'manifest' => $manifest,
        'debug' => DEBUG_MODE ? [
            'iconsCount' => count($icons),
            'outputPath' => OUTPUT_DIR . $sessionId,
            'platforms' => $settings['platforms']
        ] : null
    ]);
    
} catch (Exception $e) {
    logError($e->getMessage(), ['trace' => $e->getTraceAsString()]);
    sendError($e->getMessage(), 500);
}
