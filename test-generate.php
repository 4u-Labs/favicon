<?php
/**
 * FaviconForge Pro - Teste de Geração
 * 
 * Testa a criação de ícones sem upload.
 * REMOVA ESTE ARQUIVO EM PRODUÇÃO!
 */

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');

$result = [
    'success' => false,
    'tests' => [],
    'errors' => []
];

// Diretórios
$baseDir = __DIR__;
$tempDir = $baseDir . '/temp/';
$outputDir = $tempDir . 'output/';
$testDir = $outputDir . 'test_' . time() . '/';

try {
    // Teste 1: Criar diretórios
    if (!file_exists($tempDir)) {
        if (!mkdir($tempDir, 0755, true)) {
            throw new Exception('Não foi possível criar pasta temp/');
        }
    }
    $result['tests'][] = '✓ Pasta temp/ existe ou foi criada';
    
    if (!file_exists($outputDir)) {
        if (!mkdir($outputDir, 0755, true)) {
            throw new Exception('Não foi possível criar pasta temp/output/');
        }
    }
    $result['tests'][] = '✓ Pasta temp/output/ existe ou foi criada';
    
    if (!mkdir($testDir, 0755, true)) {
        throw new Exception('Não foi possível criar pasta de teste');
    }
    $result['tests'][] = '✓ Pasta de teste criada: ' . basename($testDir);
    
    // Teste 2: Verificar GD
    if (!extension_loaded('gd')) {
        throw new Exception('Extensão GD não está carregada');
    }
    $result['tests'][] = '✓ Extensão GD carregada';
    
    // Teste 3: Criar imagem de teste
    $testImage = imagecreatetruecolor(64, 64);
    if (!$testImage) {
        throw new Exception('Não foi possível criar imagem com GD');
    }
    
    // Fundo transparente
    imagesavealpha($testImage, true);
    imagealphablending($testImage, false);
    $transparent = imagecolorallocatealpha($testImage, 0, 0, 0, 127);
    imagefill($testImage, 0, 0, $transparent);
    
    // Desenhar um círculo colorido
    imagealphablending($testImage, true);
    $color = imagecolorallocate($testImage, 99, 102, 241);
    imagefilledellipse($testImage, 32, 32, 48, 48, $color);
    
    $result['tests'][] = '✓ Imagem de teste criada na memória';
    
    // Teste 4: Salvar imagem
    $testFile = $testDir . 'test-icon-64x64.png';
    imagesavealpha($testImage, true);
    if (!imagepng($testImage, $testFile, 6)) {
        throw new Exception('Não foi possível salvar imagem PNG');
    }
    imagedestroy($testImage);
    
    $result['tests'][] = '✓ Imagem salva: ' . basename($testFile);
    
    // Verificar se arquivo existe
    if (!file_exists($testFile)) {
        throw new Exception('Arquivo não foi criado');
    }
    
    $fileSize = filesize($testFile);
    $result['tests'][] = '✓ Arquivo verificado: ' . $fileSize . ' bytes';
    
    // Teste 5: Verificar acesso via web
    $webPath = 'temp/output/' . basename($testDir) . '/test-icon-64x64.png';
    $result['testImageUrl'] = $webPath;
    $result['tests'][] = '✓ URL do teste: ' . $webPath;
    
    // Teste 6: ZIP
    if (extension_loaded('zip')) {
        $zipFile = $testDir . 'test.zip';
        $zip = new ZipArchive();
        if ($zip->open($zipFile, ZipArchive::CREATE) === true) {
            $zip->addFile($testFile, 'test-icon-64x64.png');
            $zip->close();
            $result['tests'][] = '✓ Arquivo ZIP criado';
            $result['testZipUrl'] = 'temp/output/' . basename($testDir) . '/test.zip';
        }
    } else {
        $result['tests'][] = '⚠ Extensão ZIP não disponível';
    }
    
    $result['success'] = true;
    $result['message'] = 'Todos os testes passaram! O sistema está funcionando corretamente.';
    
} catch (Exception $e) {
    $result['errors'][] = $e->getMessage();
    $result['message'] = 'Erro: ' . $e->getMessage();
}

// Cleanup (opcional - comentar para manter arquivos de teste)
// if (file_exists($testDir)) {
//     array_map('unlink', glob($testDir . '*'));
//     rmdir($testDir);
// }

echo json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
