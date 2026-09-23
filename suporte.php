<?php
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");
$v = time();
$msg_status = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = htmlspecialchars(trim($_POST['nome'] ?? ''));
    $email = filter_var(trim($_POST['email'] ?? ''), FILTER_SANITIZE_EMAIL);
    $assunto = htmlspecialchars(trim($_POST['assunto'] ?? 'Suporte FaviconForge Pro'));
    $mensagem = htmlspecialchars(trim($_POST['mensagem'] ?? ''));

    if (!empty($nome) && !empty($email) && !empty($mensagem) && filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $log_data = [
            'timestamp' => date('c'),
            'app' => 'favicon',
            'nome' => $nome,
            'email' => $email,
            'assunto' => $assunto,
            'mensagem' => $mensagem,
            'ip' => $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0'
        ];

        $log_dir = __DIR__ . '/uploads';
        if (!is_dir($log_dir)) {
            @mkdir($log_dir, 0755, true);
        }
        @file_put_contents($log_dir . '/messages_log.json', json_encode($log_data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "\n", FILE_APPEND);

        $to = 'contato@4u.ia.br';
        $email_subject = 'Suporte Testador Favicon: ' . $assunto;
        $body = "Mensagem enviada via Suporte FaviconForge Pro:\n\n"
              . "Nome: {$nome}\n"
              . "E-mail: {$email}\n"
              . "Assunto: {$assunto}\n"
              . "Data/Hora: " . date('d/m/Y H:i:s') . "\n\n"
              . "Mensagem:\n{$mensagem}\n";

        $headers = "From: contato@4u.ia.br\r\n"
                 . "Reply-To: {$email}\r\n"
                 . "Content-Type: text/plain; charset=UTF-8\r\n"
                 . "X-Mailer: PHP/" . phpversion();

        @mail($to, $email_subject, $body, $headers);
        $msg_status = 'success';
    } else {
        $msg_status = 'error';
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Suporte & FAQ — FaviconForge Pro</title>
    <meta name="description" content="Central de Ajuda, Perguntas Frequentes e Suporte Técnico do FaviconForge Pro 4U.IA.BR.">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="icon" type="image/png" sizes="32x32" href="fav.png?v=<?= $v ?>">
    <style>
        :root {
            --bg: #090d16;
            --card-bg: #111827;
            --card-border: rgba(255, 255, 255, 0.08);
            --accent: #38bdf8;
            --text: #f1f5f9;
            --text-muted: #94a3b8;
            --radius: 14px;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            background-color: var(--bg);
            color: var(--text);
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
            font-size: 15px;
            line-height: 1.7;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            background-image: 
                radial-gradient(ellipse at 50% 0%, rgba(56, 189, 248, 0.12), transparent 50%),
                radial-gradient(circle at 100% 100%, rgba(99, 102, 241, 0.08), transparent 40%);
        }

        .header-bar {
            background: rgba(15, 23, 42, 0.85);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid var(--card-border);
            padding: 1rem 1.5rem;
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .header-inner {
            max-width: 900px;
            margin: 0 auto;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .btn-back {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            color: #fff;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.9rem;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            background: rgba(255, 255, 255, 0.06);
            border: 1px solid var(--card-border);
            transition: all 0.2s ease;
        }

        .btn-back:hover {
            background: rgba(255, 255, 255, 0.12);
            color: var(--accent);
        }

        .container {
            max-width: 900px;
            width: 100%;
            margin: 2.5rem auto;
            padding: 0 1.5rem;
            flex: 1;
        }

        .content-card {
            background: var(--card-bg);
            border: 1px solid var(--card-border);
            border-radius: var(--radius);
            padding: 2.5rem;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.35);
            margin-bottom: 2rem;
        }

        h1 {
            font-size: 2rem;
            font-weight: 800;
            margin-bottom: 0.5rem;
            color: #fff;
        }

        .subtitle {
            color: var(--text-muted);
            margin-bottom: 2rem;
            border-bottom: 1px solid var(--card-border);
            padding-bottom: 1rem;
        }

        /* FAQ Accordion */
        .faq-item {
            border: 1px solid var(--card-border);
            border-radius: 10px;
            margin-bottom: 0.85rem;
            overflow: hidden;
            background: rgba(255, 255, 255, 0.02);
        }

        .faq-question {
            padding: 1rem 1.25rem;
            font-weight: 700;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: space-between;
            color: #fff;
            user-select: none;
            transition: background 0.2s ease;
        }

        .faq-question:hover {
            background: rgba(255, 255, 255, 0.05);
        }

        .faq-answer {
            padding: 0 1.25rem 1rem 1.25rem;
            color: #94a3b8;
            font-size: 0.95rem;
            display: none;
            line-height: 1.6;
        }

        .faq-item.active .faq-answer {
            display: block;
        }

        .faq-item.active .faq-icon {
            transform: rotate(180deg);
        }

        .faq-icon {
            transition: transform 0.2s ease;
            color: var(--accent);
        }

        /* Form */
        .form-group {
            margin-bottom: 1.25rem;
        }

        label {
            display: block;
            margin-bottom: 0.4rem;
            color: #e2e8f0;
            font-weight: 600;
            font-size: 0.9rem;
        }

        input[type="text"], input[type="email"], textarea {
            width: 100%;
            padding: 0.8rem 1rem;
            border-radius: 10px;
            background: #0b1120;
            border: 1px solid var(--card-border);
            color: #fff;
            font-family: inherit;
            font-size: 0.92rem;
            outline: none;
            transition: border-color 0.2s ease;
        }

        input:focus, textarea:focus {
            border-color: var(--accent);
        }

        .btn-submit {
            background: var(--accent);
            color: #04101e;
            padding: 0.8rem 1.75rem;
            border-radius: 10px;
            font-weight: 700;
            font-size: 0.95rem;
            border: none;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.2s ease;
        }

        .btn-submit:hover {
            background: #7dd3fc;
            transform: translateY(-1px);
        }

        .alert-success {
            background: rgba(34, 197, 94, 0.15);
            border: 1px solid rgba(34, 197, 94, 0.35);
            color: #bbf7d0;
            padding: 1rem;
            border-radius: 10px;
            margin-bottom: 1.5rem;
        }

        .alert-error {
            background: rgba(239, 68, 68, 0.15);
            border: 1px solid rgba(239, 68, 68, 0.35);
            color: #fca5a5;
            padding: 1rem;
            border-radius: 10px;
            margin-bottom: 1.5rem;
        }

        /* Footer */
        .footer-clean {
            padding: 2.5rem 1.5rem;
            background: rgba(11, 15, 25, 0.95);
            border-top: 1px solid rgba(255, 255, 255, 0.06);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 12px;
            margin-top: 3rem;
        }

        .footer-brand {
            font-size: 0.88rem;
            font-weight: 700;
            color: #cbd5e1;
        }

        .footer-links {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
            justify-content: center;
        }

        .footer-links a {
            color: #94a3b8;
            text-decoration: none;
            font-size: 0.82rem;
        }

        .footer-links a:hover { color: #f1f5f9; }
        .footer-links a.donate-link { color: #fef08a; font-weight: 700; }
        .footer-copyright { font-size: 0.75rem; color: rgba(255, 255, 255, 0.35); }
    </style>
</head>
<body>

    <header class="header-bar">
        <div class="header-inner">
            <a href="index.php" class="btn-back">
                <i class="fas fa-arrow-left"></i> Voltar ao FaviconForge Pro
            </a>
            <div style="font-size: 0.88rem; font-weight: 700; color: var(--text-muted);">
                4U.IA.BR // Suporte & FAQ
            </div>
        </div>
    </header>

    <main class="container">
        <!-- FAQ Section -->
        <section class="content-card">
            <h1>Perguntas Frequentes (FAQ)</h1>
            <div class="subtitle">Respostas rápidas sobre o funcionamento do FaviconForge Pro</div>

            <div class="faq-item active">
                <div class="faq-question" onclick="this.parentElement.classList.toggle('active')">
                    <span>1. Por que o favicon não aparece imediatamente na aba de alguns navegadores?</span>
                    <i class="fas fa-chevron-down faq-icon"></i>
                </div>
                <div class="faq-answer">
                    Muitos navegadores (especialmente Google Chrome e Safari) mantêm um cache agressivo de favicons por URL. Nosso testador força a recriação da tag <code>&lt;link rel="icon"&gt;</code> através de Canvas com timestamp para garantir a visualização imediata da imagem em teste.
                </div>
            </div>

            <div class="faq-item">
                <div class="faq-question" onclick="this.parentElement.classList.toggle('active')">
                    <span>2. Qual é a resolução recomendada para a imagem original?</span>
                    <i class="fas fa-chevron-down faq-icon"></i>
                </div>
                <div class="faq-answer">
                    Recomendamos utilizar uma imagem quadrada de proporção 1:1, idealmente em formato PNG com fundo transparente, medindo no mínimo 512×512 pixels. Isso garante excelente definição tanto nas abas de 16×16px quanto em telas de alta densidade (Retina/HiDPI) e atalhos de celular (192×192px e 512×512px).
                </div>
            </div>

            <div class="faq-item">
                <div class="faq-question" onclick="this.parentElement.classList.toggle('active')">
                    <span>3. Minhas imagens são enviadas para algum servidor?</span>
                    <i class="fas fa-chevron-down faq-icon"></i>
                </div>
                <div class="faq-answer">
                    Não! O FaviconForge Pro opera 100% no lado do cliente (client-side) usando APIs nativas do seu próprio navegador (HTML5 Canvas e FileReader). Suas imagens nunca são gravadas em nenhum servidor nem compartilhadas com terceiros.
                </div>
            </div>

            <div class="faq-item">
                <div class="faq-question" onclick="this.parentElement.classList.toggle('active')">
                    <span>4. Como exportar os arquivos gerados?</span>
                    <i class="fas fa-chevron-down faq-icon"></i>
                </div>
                <div class="faq-answer">
                    Você pode clicar em "Baixar PNG" em cada card de resolução individual ou utilizar o botão "Baixar Pacote Completo (.ZIP)" para fazer o download de todas as versões (16, 32, 48, 64, 180, 192 e 512px) de uma só vez, acompanhadas do arquivo <code>site.webmanifest</code>.
                </div>
            </div>
        </section>

        <!-- Contact Form -->
        <section class="content-card">
            <h2 style="font-size:1.5rem; font-weight:800; color:#fff; margin-bottom:0.5rem;">Fale Conosco</h2>
            <div class="subtitle">Tem sugestões, feedback ou encontrou algum problema? Envie sua mensagem direta para a equipe 4U.IA.BR.</div>

            <?php if ($msg_status === 'success'): ?>
                <div class="alert-success">
                    <i class="fas fa-check-circle"></i> Mensagem enviada com sucesso! Responderemos o mais breve possível.
                </div>
            <?php elseif ($msg_status === 'error'): ?>
                <div class="alert-error">
                    <i class="fas fa-exclamation-circle"></i> Por favor, preencha todos os campos obrigatórios com um e-mail válido.
                </div>
            <?php endif; ?>

            <form method="POST" action="suporte.php">
                <div class="form-group">
                    <label for="nome">Seu Nome *</label>
                    <input type="text" id="nome" name="nome" required placeholder="Como podemos te chamar?">
                </div>

                <div class="form-group">
                    <label for="email">Seu E-mail *</label>
                    <input type="email" id="email" name="email" required placeholder="exemplo@dominio.com">
                </div>

                <div class="form-group">
                    <label for="assunto">Assunto</label>
                    <input type="text" id="assunto" name="assunto" placeholder="Dúvida, sugestão ou feedback">
                </div>

                <div class="form-group">
                    <label for="mensagem">Mensagem *</label>
                    <textarea id="mensagem" name="mensagem" rows="5" required placeholder="Descreva sua solicitação com detalhes..."></textarea>
                </div>

                <button type="submit" class="btn-submit">
                    <i class="fas fa-paper-plane"></i> Enviar Mensagem
                </button>
            </form>
        </section>
    </main>

    <footer class="footer-clean">
        <div class="footer-brand">
            <i class="fas fa-icons"></i> <span>FaviconForge Pro — 4U.IA.BR</span>
        </div>
        <div class="footer-links">
            <a href="privacidade.php">Privacidade</a>
            <span style="color:rgba(255,255,255,0.15)">•</span>
            <a href="termos.php">Termos de Uso</a>
            <span style="color:rgba(255,255,255,0.15)">•</span>
            <a href="suporte.php">Suporte & FAQ</a>
            <span style="color:rgba(255,255,255,0.15)">•</span>
            <a href="https://www.paypal.com/ncp/payment/L7YRCS984T33N" target="_blank" rel="noopener noreferrer" class="donate-link">☕ Apoie</a>
            <span style="color:rgba(255,255,255,0.15)">•</span>
            <a href="https://github.com/4u-Labs" target="_blank" rel="noopener noreferrer">GitHub</a>
        </div>
        <div class="footer-copyright">
            &copy; <?= date('Y') ?> 4U.IA.BR — Todos os direitos reservados.
        </div>
    </footer>

</body>
</html>
