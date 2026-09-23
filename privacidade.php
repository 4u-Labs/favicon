<?php
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");
$v = time();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Política de Privacidade & LGPD — FaviconForge Pro</title>
    <meta name="description" content="Política de Privacidade e conformidade LGPD do FaviconForge Pro 4U.IA.BR. Processamento 100% local, retenção zero de imagens e privacidade total.">
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
        }

        h1 {
            font-size: 2rem;
            font-weight: 800;
            margin-bottom: 0.5rem;
            color: #fff;
        }

        .update-date {
            color: var(--text-muted);
            font-size: 0.85rem;
            margin-bottom: 2rem;
            border-bottom: 1px solid var(--card-border);
            padding-bottom: 1rem;
        }

        .highlight-box {
            background: rgba(56, 189, 248, 0.08);
            border: 1px solid rgba(56, 189, 248, 0.25);
            border-radius: 10px;
            padding: 1.25rem;
            margin-bottom: 2rem;
            display: flex;
            align-items: flex-start;
            gap: 1rem;
        }

        .highlight-box i {
            color: var(--accent);
            font-size: 1.5rem;
            margin-top: 3px;
        }

        h2 {
            font-size: 1.25rem;
            font-weight: 700;
            color: #fff;
            margin: 1.75rem 0 0.75rem 0;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        p, ul {
            color: #cbd5e1;
            margin-bottom: 1rem;
        }

        ul {
            padding-left: 1.5rem;
        }

        li {
            margin-bottom: 0.5rem;
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
                4U.IA.BR // Privacidade
            </div>
        </div>
    </header>

    <main class="container">
        <article class="content-card">
            <h1>Política de Privacidade & LGPD</h1>
            <div class="update-date">Última atualização: <?= date('d/m/Y') ?> • Em conformidade com a LGPD (Lei nº 13.709/2018)</div>

            <div class="highlight-box">
                <i class="fas fa-shield-halved"></i>
                <div>
                    <strong style="color: #fff; font-size: 1rem;">Processamento 100% Local & Retenção Zero</strong>
                    <p style="font-size: 0.9rem; margin-top: 4px; margin-bottom: 0;">
                        O FaviconForge Pro opera integralmente dentro da memória do seu navegador web via HTML5 Canvas e FileReader API. <strong>Nenhuma imagem testada é enviada, salva ou armazenada em servidores externos.</strong>
                    </p>
                </div>
            </div>

            <h2>1. Princípio da Retenção Zero de Imagens</h2>
            <p>
                Ao carregar ou arrastar uma imagem (PNG, JPG, SVG ou WebP) para testar proporções e simulações de favicon, o processamento ocorre exclusivamente no lado do cliente (client-side). Suas imagens nunca trafegam pela internet nem ficam gravadas em bancos de dados.
            </p>

            <h2>2. Coleta Mínima de Dados Técnicos</h2>
            <p>
                Como serviço web estático e cliente, não exigimos criação de conta, login ou cadastro para a simulação de favicons. Nossos servidores registram apenas logs técnicos de requisições HTTP (endereço IP de origem, data/hora e user-agent) exclusivamente para fins de segurança, prevenção a ataques DDoS e estabilidade operacional da rede Hostinger.
            </p>

            <h2>3. Armazenamento Local e Cookies</h2>
            <p>
                Este aplicativo não utiliza cookies de rastreamento de terceiros ou publicidade direcionada. Quaisquer preferências de tema ou arquivos em teste permanecem apenas na memória volátil da sessão ativa do seu navegador.
            </p>

            <h2>4. Direitos do Titular (LGPD Art. 18)</h2>
            <p>
                Por operarmos sob o modelo de Retenção Zero sem armazenamento de dados pessoais, não mantemos perfis nem bancos de dados de usuários do FaviconForge Pro. Caso você entre em contato conosco através do formulário de Suporte, seus dados de contato (nome e e-mail) serão utilizados exclusivamente para responder à sua solicitação e poderão ser eliminados a qualquer momento mediante requisição.
            </p>

            <h2>5. Contato com o Encarregado de Dados (DPO)</h2>
            <p>
                Para dúvidas, esclarecimentos ou requisições sobre nossa política de privacidade e conformidade com a LGPD, envie um e-mail para: <strong>contato@4u.ia.br</strong>.
            </p>
        </article>
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
