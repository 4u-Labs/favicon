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
    <title>Termos de Uso — FaviconForge Pro</title>
    <meta name="description" content="Termos de Uso e Condições Gerais do FaviconForge Pro 4U.IA.BR.">
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
                4U.IA.BR // Termos de Uso
            </div>
        </div>
    </header>

    <main class="container">
        <article class="content-card">
            <h1>Termos de Uso & Condições de Serviço</h1>
            <div class="update-date">Última atualização: <?= date('d/m/Y') ?> • Plataforma 4U.IA.BR</div>

            <h2>1. Aceite dos Termos</h2>
            <p>
                Ao acessar e utilizar o <strong>FaviconForge Pro</strong>, disponibilizado pela 4U.IA.BR, você concorda expressamente com todos os termos e diretrizes aqui estabelecidos. Caso não concorde com algum dos termos, solicitamos que descontinue o uso do aplicativo.
            </p>

            <h2>2. Finalidade e Gratuidade do Serviço</h2>
            <p>
                O FaviconForge Pro é uma ferramenta profissional gratuita de simulação visual e inspeção de ícones para desenvolvedores, web designers e criadores de conteúdo. O serviço possibilita verificar a renderização de imagens em abas de navegadores, resultados de busca do Google e atalhos móveis, bem como exportar arquivos em resoluções padronizadas.
            </p>

            <h2>3. Propriedade Intelectual e Direitos Autorais</h2>
            <p>
                Todas as imagens, logotipos, marcas e arquivos carregados para teste no aplicativo permanecem como propriedade intelectual exclusiva de seus respectivos autores e detentores de direitos. O aplicativo não reivindica posse, licença ou titularidade sobre qualquer asset testado ou gerado.
            </p>

            <h2>4. Isenção de Garantias e Limitação de Responsabilidade</h2>
            <p>
                O serviço é fornecido "no estado em que se encontra" (as is), sem garantias implícitas ou explícitas de compatibilidade com qualquer navegador ou sistema operacional legado. A 4U.IA.BR não se responsabiliza por eventuais incompatibilidades visuais, perdas acidentais de arquivos ou descontinuidades técnicas de APIs de terceiros.
            </p>

            <h2>5. Alterações nos Termos</h2>
            <p>
                A 4U.IA.BR reserva-se o direito de atualizar e aprimorar estes Termos de Uso a qualquer momento, visando refletir melhorias contínuas na plataforma ou novos requisitos legislativos.
            </p>

            <h2>6. Dúvidas e Suporte</h2>
            <p>
                Em caso de dúvidas sobre nossos termos ou funcionamento do sistema, contate nosso time através do canal institucional: <strong>contato@4u.ia.br</strong>.
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
