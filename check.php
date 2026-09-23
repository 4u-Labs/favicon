<?php
/**
 * FaviconForge Pro - Verificação de Sistema
 * 
 * Este arquivo verifica se o servidor está configurado corretamente.
 * REMOVA ESTE ARQUIVO EM PRODUÇÃO!
 */

header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FaviconForge Pro - Diagnóstico</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { 
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 40px 20px;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            border-radius: 16px;
            box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25);
            overflow: hidden;
        }
        .header {
            background: linear-gradient(135deg, #6366F1 0%, #8B5CF6 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        .header h1 { font-size: 24px; margin-bottom: 8px; }
        .header p { opacity: 0.9; }
        .content { padding: 30px; }
        .check-item {
            display: flex;
            align-items: center;
            padding: 16px;
            border-bottom: 1px solid #e5e7eb;
            gap: 16px;
        }
        .check-item:last-child { border-bottom: none; }
        .status {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            flex-shrink: 0;
        }
        .status.ok { background: #dcfce7; color: #16a34a; }
        .status.error { background: #fee2e2; color: #dc2626; }
        .status.warning { background: #fef3c7; color: #d97706; }
        .check-info { flex: 1; }
        .check-info h3 { font-size: 14px; margin-bottom: 4px; }
        .check-info p { font-size: 12px; color: #6b7280; }
        .check-value {
            font-family: monospace;
            font-size: 12px;
            color: #6b7280;
            background: #f3f4f6;
            padding: 4px 8px;
            border-radius: 4px;
        }
        .section-title {
            font-size: 18px;
            font-weight: 600;
            padding: 20px 16px 10px;
            color: #374151;
            border-top: 2px solid #e5e7eb;
            margin-top: 20px;
        }
        .section-title:first-child { border-top: none; margin-top: 0; }
        .footer {
            background: #f9fafb;
            padding: 20px;
            text-align: center;
            font-size: 14px;
            color: #6b7280;
        }
        .footer a { color: #6366F1; text-decoration: none; }
        .test-section {
            margin-top: 30px;
            padding: 20px;
            background: #f9fafb;
            border-radius: 8px;
        }
        .test-section h3 { margin-bottom: 15px; }
        .test-btn {
            background: linear-gradient(135deg, #6366F1 0%, #8B5CF6 100%);
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 500;
        }
        .test-btn:hover { opacity: 0.9; }
        #testResult {
            margin-top: 15px;
            padding: 15px;
            background: white;
            border-radius: 8px;
            font-family: monospace;
            font-size: 12px;
            white-space: pre-wrap;
            display: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🔧 FaviconForge Pro - Diagnóstico</h1>
            <p>Verificação de configuração do servidor</p>
        </div>
        
        <div class="content">
            <h2 class="section-title">Extensões PHP</h2>
            
            <?php
            // Verificar GD
            $gdLoaded = extension_loaded('gd');
            $gdInfo = $gdLoaded ? gd_info() : null;
            ?>
            <div class="check-item">
                <div class="status <?= $gdLoaded ? 'ok' : 'error' ?>">
                    <?= $gdLoaded ? '✓' : '✗' ?>
                </div>
                <div class="check-info">
                    <h3>Extensão GD</h3>
                    <p><?= $gdLoaded ? 'Carregada - Suporte a imagens disponível' : 'NÃO CARREGADA - Necessária para processar imagens' ?></p>
                </div>
                <?php if ($gdLoaded && $gdInfo): ?>
                <span class="check-value">PNG: <?= $gdInfo['PNG Support'] ? 'Sim' : 'Não' ?> | JPEG: <?= $gdInfo['JPEG Support'] ? 'Sim' : 'Não' ?></span>
                <?php endif; ?>
            </div>
            
            <?php $imagickLoaded = extension_loaded('imagick'); ?>
            <div class="check-item">
                <div class="status <?= $imagickLoaded ? 'ok' : 'warning' ?>">
                    <?= $imagickLoaded ? '✓' : '!' ?>
                </div>
                <div class="check-info">
                    <h3>Extensão Imagick</h3>
                    <p><?= $imagickLoaded ? 'Carregada - Melhor qualidade de imagem' : 'Não carregada - Opcional, GD será usado' ?></p>
                </div>
            </div>
            
            <?php $zipLoaded = extension_loaded('zip'); ?>
            <div class="check-item">
                <div class="status <?= $zipLoaded ? 'ok' : 'error' ?>">
                    <?= $zipLoaded ? '✓' : '✗' ?>
                </div>
                <div class="check-info">
                    <h3>Extensão ZipArchive</h3>
                    <p><?= $zipLoaded ? 'Carregada - Compactação disponível' : 'NÃO CARREGADA - Necessária para criar ZIP' ?></p>
                </div>
            </div>
            
            <?php $finfoLoaded = extension_loaded('fileinfo'); ?>
            <div class="check-item">
                <div class="status <?= $finfoLoaded ? 'ok' : 'error' ?>">
                    <?= $finfoLoaded ? '✓' : '✗' ?>
                </div>
                <div class="check-info">
                    <h3>Extensão Fileinfo</h3>
                    <p><?= $finfoLoaded ? 'Carregada - Validação de MIME type disponível' : 'NÃO CARREGADA - Necessária para validar uploads' ?></p>
                </div>
            </div>
            
            <h2 class="section-title">Diretórios</h2>
            
            <?php
            $tempDir = __DIR__ . '/temp/';
            $uploadDir = __DIR__ . '/temp/uploads/';
            $outputDir = __DIR__ . '/temp/output/';
            
            // Criar diretórios se não existirem
            if (!file_exists($tempDir)) @mkdir($tempDir, 0755, true);
            if (!file_exists($uploadDir)) @mkdir($uploadDir, 0755, true);
            if (!file_exists($outputDir)) @mkdir($outputDir, 0755, true);
            
            $tempExists = file_exists($tempDir);
            $tempWritable = is_writable($tempDir);
            ?>
            <div class="check-item">
                <div class="status <?= ($tempExists && $tempWritable) ? 'ok' : 'error' ?>">
                    <?= ($tempExists && $tempWritable) ? '✓' : '✗' ?>
                </div>
                <div class="check-info">
                    <h3>Pasta /temp/</h3>
                    <p>
                        Existe: <?= $tempExists ? 'Sim' : 'Não' ?> | 
                        Gravável: <?= $tempWritable ? 'Sim' : 'Não' ?>
                    </p>
                </div>
                <span class="check-value"><?= $tempDir ?></span>
            </div>
            
            <?php
            $uploadExists = file_exists($uploadDir);
            $uploadWritable = is_writable($uploadDir);
            ?>
            <div class="check-item">
                <div class="status <?= ($uploadExists && $uploadWritable) ? 'ok' : 'error' ?>">
                    <?= ($uploadExists && $uploadWritable) ? '✓' : '✗' ?>
                </div>
                <div class="check-info">
                    <h3>Pasta /temp/uploads/</h3>
                    <p>
                        Existe: <?= $uploadExists ? 'Sim' : 'Não' ?> | 
                        Gravável: <?= $uploadWritable ? 'Sim' : 'Não' ?>
                    </p>
                </div>
            </div>
            
            <?php
            $outputExists = file_exists($outputDir);
            $outputWritable = is_writable($outputDir);
            ?>
            <div class="check-item">
                <div class="status <?= ($outputExists && $outputWritable) ? 'ok' : 'error' ?>">
                    <?= ($outputExists && $outputWritable) ? '✓' : '✗' ?>
                </div>
                <div class="check-info">
                    <h3>Pasta /temp/output/</h3>
                    <p>
                        Existe: <?= $outputExists ? 'Sim' : 'Não' ?> | 
                        Gravável: <?= $outputWritable ? 'Sim' : 'Não' ?>
                    </p>
                </div>
            </div>
            
            <h2 class="section-title">Configurações PHP</h2>
            
            <?php $uploadMax = ini_get('upload_max_filesize'); ?>
            <div class="check-item">
                <div class="status <?= (intval($uploadMax) >= 10) ? 'ok' : 'warning' ?>">
                    <?= (intval($uploadMax) >= 10) ? '✓' : '!' ?>
                </div>
                <div class="check-info">
                    <h3>upload_max_filesize</h3>
                    <p>Tamanho máximo de upload</p>
                </div>
                <span class="check-value"><?= $uploadMax ?></span>
            </div>
            
            <?php $postMax = ini_get('post_max_size'); ?>
            <div class="check-item">
                <div class="status <?= (intval($postMax) >= 10) ? 'ok' : 'warning' ?>">
                    <?= (intval($postMax) >= 10) ? '✓' : '!' ?>
                </div>
                <div class="check-info">
                    <h3>post_max_size</h3>
                    <p>Tamanho máximo do POST</p>
                </div>
                <span class="check-value"><?= $postMax ?></span>
            </div>
            
            <?php $memLimit = ini_get('memory_limit'); ?>
            <div class="check-item">
                <div class="status <?= (intval($memLimit) >= 128) ? 'ok' : 'warning' ?>">
                    <?= (intval($memLimit) >= 128) ? '✓' : '!' ?>
                </div>
                <div class="check-info">
                    <h3>memory_limit</h3>
                    <p>Limite de memória</p>
                </div>
                <span class="check-value"><?= $memLimit ?></span>
            </div>
            
            <h2 class="section-title">Teste de Geração</h2>
            
            <div class="test-section">
                <h3>🧪 Testar criação de arquivo de imagem</h3>
                <p style="margin-bottom: 15px; color: #6b7280; font-size: 14px;">
                    Este teste cria um ícone de teste para verificar se o sistema está funcionando.
                </p>
                <button class="test-btn" onclick="runTest()">Executar Teste</button>
                <div id="testResult"></div>
            </div>
        </div>
        
        <div class="footer">
            <p>⚠️ <strong>IMPORTANTE:</strong> Remova este arquivo (check.php) após verificar a configuração!</p>
            <p style="margin-top: 10px;">
                <a href="index.html">← Voltar para o FaviconForge Pro</a>
            </p>
        </div>
    </div>
    
    <script>
        function runTest() {
            const resultDiv = document.getElementById('testResult');
            resultDiv.style.display = 'block';
            resultDiv.textContent = 'Executando teste...';
            
            fetch('test-generate.php')
                .then(response => response.json())
                .then(data => {
                    resultDiv.textContent = JSON.stringify(data, null, 2);
                    resultDiv.style.background = data.success ? '#dcfce7' : '#fee2e2';
                })
                .catch(error => {
                    resultDiv.textContent = 'Erro: ' + error.message;
                    resultDiv.style.background = '#fee2e2';
                });
        }
    </script>
</body>
</html>
