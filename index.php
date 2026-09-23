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
    <title>Favicon Studio Pro — Testador, Simulador & Gerador de Favicons // 4U.IA.BR</title>
    <meta name="description" content="O estúdio definitivo de favicons: simule em abas reais (Chrome Dark/Light, Google Search, celular), personalize padding e cores, e gere assets completos para sites, PWAs, iOS e Android.">

    <!-- Favicons dinâmicos -->
    <link id="dynamic-favicon" rel="icon" type="image/png" sizes="32x32" href="fav.png?v=<?= $v ?>">
    <link id="dynamic-favicon-16" rel="icon" type="image/png" sizes="16x16" href="fav.png?v=<?= $v ?>">
    <link id="apple-touch-icon" rel="apple-touch-icon" sizes="180x180" href="fav.png?v=<?= $v ?>">

    <!-- Google Fonts & Font Awesome -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <!-- JSZip para exportação instantânea no cliente -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>

    <style>
        :root {
            --bg: #090d16;
            --card-bg: #111827;
            --card-border: rgba(255, 255, 255, 0.08);
            --accent: #38bdf8;
            --accent-hover: #7dd3fc;
            --accent-glow: rgba(56, 189, 248, 0.25);
            --purple: #8b5cf6;
            --text: #f1f5f9;
            --text-muted: #94a3b8;
            --radius: 16px;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            background-color: var(--bg);
            color: var(--text);
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            background-image: 
                radial-gradient(ellipse at 50% 0%, rgba(56, 189, 248, 0.14), transparent 50%),
                radial-gradient(circle at 100% 100%, rgba(139, 92, 246, 0.09), transparent 40%);
        }

        /* Top Navbar */
        .top-navbar {
            width: 100%;
            background: rgba(15, 23, 42, 0.88);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid var(--card-border);
            padding: 0.85rem 1.5rem;
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .nav-container {
            max-width: 1160px;
            margin: 0 auto;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
        }

        .brand-group {
            display: flex;
            align-items: center;
            gap: 0.85rem;
            text-decoration: none;
        }

        .brand-logo-img {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            object-fit: contain;
        }

        .brand-name {
            font-size: 1.05rem;
            font-weight: 800;
            letter-spacing: -0.02em;
            color: #fff;
        }

        .brand-name span {
            color: var(--accent);
        }

        .brand-badge {
            background: linear-gradient(135deg, #38bdf8, #818cf8);
            color: #04101e;
            font-size: 0.68rem;
            font-weight: 800;
            padding: 2px 7px;
            border-radius: 6px;
            margin-left: 6px;
            text-transform: uppercase;
        }

        .nav-actions {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            flex-wrap: wrap;
        }

        .nav-btn {
            font-size: 0.82rem;
            font-weight: 600;
            padding: 0.45rem 0.85rem;
            border-radius: 8px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.45rem;
            transition: all 0.2s ease;
        }

        .nav-btn-muted {
            color: var(--text-muted);
            border: 1px solid rgba(255, 255, 255, 0.1);
            background: rgba(255, 255, 255, 0.04);
        }

        .nav-btn-muted:hover {
            color: #fff;
            border-color: rgba(255, 255, 255, 0.25);
            background: rgba(255, 255, 255, 0.08);
        }

        .nav-btn-donate {
            background: rgba(234, 179, 8, 0.15);
            color: #fef08a;
            border: 1px solid rgba(234, 179, 8, 0.35);
        }

        .nav-btn-donate:hover {
            background: #eab308;
            color: #000;
            box-shadow: 0 4px 12px rgba(234, 179, 8, 0.3);
        }

        .container {
            max-width: 1160px;
            width: 100%;
            margin: 0 auto;
            padding: 2rem 1.5rem;
            flex: 1;
        }

        /* Hero */
        header.hero {
            text-align: center;
            margin-bottom: 2.25rem;
        }

        .hero-pill {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.35rem 0.95rem;
            background: rgba(56, 189, 248, 0.1);
            border: 1px solid rgba(56, 189, 248, 0.25);
            border-radius: 9999px;
            color: var(--accent);
            font-size: 0.8rem;
            font-weight: 600;
            letter-spacing: 0.05em;
            text-transform: uppercase;
            margin-bottom: 0.85rem;
        }

        h1.hero-title {
            font-size: 2.4rem;
            font-weight: 800;
            letter-spacing: -0.03em;
            margin-bottom: 0.6rem;
            background: linear-gradient(135deg, #ffffff 30%, #38bdf8 70%, #818cf8 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        p.hero-subtitle {
            color: var(--text-muted);
            font-size: 1.05rem;
            max-width: 720px;
            margin: 0 auto;
            line-height: 1.55;
        }

        /* Top Control Bar */
        .controls-card {
            background: var(--card-bg);
            border: 1px solid var(--card-border);
            border-radius: var(--radius);
            padding: 1.25rem 1.5rem;
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: space-between;
            gap: 1.25rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
        }

        .source-info {
            display: flex;
            align-items: center;
            gap: 1.15rem;
        }

        .current-img-preview {
            width: 58px;
            height: 58px;
            border-radius: 12px;
            border: 1px solid var(--card-border);
            background: #1e293b;
            padding: 4px;
            object-fit: contain;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.4);
            transition: all 0.2s ease;
        }

        .source-text h3 {
            font-size: 1.05rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .source-text p {
            color: var(--text-muted);
            font-size: 0.85rem;
            font-family: 'JetBrains Mono', monospace;
            margin-top: 3px;
        }

        .actions-group {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            flex-wrap: wrap;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.65rem 1.15rem;
            border-radius: 10px;
            font-size: 0.88rem;
            font-weight: 600;
            cursor: pointer;
            border: none;
            transition: all 0.2s ease;
            text-decoration: none;
        }

        .btn-primary {
            background: var(--accent);
            color: #04101e;
            box-shadow: 0 4px 14px var(--accent-glow);
        }

        .btn-primary:hover {
            background: var(--accent-hover);
            transform: translateY(-1px);
        }

        .btn-secondary {
            background: rgba(255, 255, 255, 0.06);
            color: var(--text);
            border: 1px solid var(--card-border);
        }

        .btn-secondary:hover {
            background: rgba(255, 255, 255, 0.12);
            border-color: rgba(255, 255, 255, 0.2);
        }

        .btn-success {
            background: #10b981;
            color: #042f1a;
            box-shadow: 0 4px 14px rgba(16, 185, 129, 0.25);
        }

        .btn-success:hover {
            background: #34d399;
            transform: translateY(-1px);
        }

        /* Drag & Drop Upload Zone */
        .dropzone {
            border: 2px dashed rgba(56, 189, 248, 0.35);
            background: rgba(56, 189, 248, 0.03);
            border-radius: var(--radius);
            padding: 1.5rem;
            text-align: center;
            margin-bottom: 2rem;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .dropzone:hover, .dropzone.dragover {
            border-color: var(--accent);
            background: rgba(56, 189, 248, 0.08);
            transform: translateY(-1px);
        }

        .dropzone i {
            font-size: 2rem;
            color: var(--accent);
            margin-bottom: 0.4rem;
        }

        .dropzone p {
            font-size: 0.95rem;
            color: var(--text-muted);
        }

        .dropzone span {
            color: var(--accent);
            font-weight: 600;
            text-decoration: underline;
        }

        .dropzone-tip {
            font-size: 0.78rem !important;
            color: rgba(255, 255, 255, 0.4) !important;
            margin-top: 0.3rem;
        }

        /* Two-Column Studio Layout */
        .studio-grid {
            display: grid;
            grid-template-columns: 1fr 340px;
            gap: 1.75rem;
            margin-bottom: 2.5rem;
        }

        @media (max-width: 960px) {
            .studio-grid {
                grid-template-columns: 1fr;
            }
        }

        /* Section Headings */
        .section-title {
            font-size: 1.25rem;
            font-weight: 700;
            margin-bottom: 1.15rem;
            display: flex;
            align-items: center;
            gap: 0.6rem;
        }

        .section-title i {
            color: var(--accent);
        }

        /* Mockup Cards */
        .mockups-column {
            display: flex;
            flex-direction: column;
            gap: 1.25rem;
        }

        .mockup-card {
            background: var(--card-bg);
            border: 1px solid var(--card-border);
            border-radius: var(--radius);
            overflow: hidden;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.3);
        }

        .mockup-header {
            padding: 0.75rem 1.25rem;
            background: rgba(255, 255, 255, 0.03);
            border-bottom: 1px solid var(--card-border);
            font-size: 0.85rem;
            font-weight: 600;
            color: var(--text-muted);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .mockup-content {
            padding: 1.25rem;
        }

        /* Browser Mockup Dark */
        .chrome-window-dark {
            background: #202124;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 10px 25px rgba(0,0,0,0.5);
        }

        .chrome-tabbar-dark {
            display: flex;
            align-items: flex-end;
            background: #1f2023;
            padding: 8px 8px 0 8px;
            gap: 4px;
        }

        .chrome-tab-dark {
            background: #35363a;
            border-radius: 8px 8px 0 0;
            padding: 7px 14px;
            display: flex;
            align-items: center;
            gap: 9px;
            max-width: 210px;
            font-size: 12px;
            color: #e8eaed;
            white-space: nowrap;
            overflow: hidden;
        }

        .chrome-tab-dark img {
            width: 16px;
            height: 16px;
            flex-shrink: 0;
            object-fit: contain;
        }

        .chrome-tab-dark span {
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .chrome-tab-dark .close-btn {
            margin-left: auto;
            font-size: 14px;
            color: #9aa0a6;
        }

        .chrome-addressbar-dark {
            background: #292a2d;
            padding: 7px 14px;
            font-size: 12px;
            color: #9aa0a6;
            display: flex;
            align-items: center;
            gap: 8px;
            border-bottom: 1px solid #3c4043;
        }

        .chrome-body-dark {
            padding: 16px;
            font-size: 12px;
            color: #5f6368;
            text-align: center;
            background: #202124;
        }

        /* Browser Mockup Light */
        .chrome-window-light {
            background: #ffffff;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 10px 25px rgba(0,0,0,0.3);
        }

        .chrome-tabbar-light {
            display: flex;
            align-items: flex-end;
            background: #dee1e6;
            padding: 8px 8px 0 8px;
            gap: 4px;
        }

        .chrome-tab-light {
            background: #ffffff;
            border-radius: 8px 8px 0 0;
            padding: 7px 14px;
            display: flex;
            align-items: center;
            gap: 9px;
            max-width: 210px;
            font-size: 12px;
            color: #3c4043;
            white-space: nowrap;
            overflow: hidden;
        }

        .chrome-tab-light img {
            width: 16px;
            height: 16px;
            flex-shrink: 0;
            object-fit: contain;
        }

        .chrome-addressbar-light {
            background: #f1f3f4;
            padding: 7px 14px;
            font-size: 12px;
            color: #5f6368;
            display: flex;
            align-items: center;
            gap: 8px;
            border-bottom: 1px solid #e8eaed;
        }

        .chrome-body-light {
            padding: 16px;
            font-size: 12px;
            color: #9aa0a6;
            text-align: center;
            background: #ffffff;
        }

        /* Google Snippet */
        .google-snippet {
            background: #202124;
            padding: 1.25rem;
            border-radius: 10px;
            font-family: Arial, sans-serif;
        }

        .google-site-line {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 8px;
        }

        .google-fav-circle {
            width: 28px;
            height: 28px;
            border-radius: 50%;
            background: #303134;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .google-fav-circle img {
            width: 18px;
            height: 18px;
            object-fit: contain;
        }

        .google-site-name {
            color: #dadce0;
            font-size: 14px;
            font-weight: 500;
        }

        .google-site-url {
            color: #bdc1c6;
            font-size: 12px;
        }

        .google-title {
            color: #8ab4f8;
            font-size: 18px;
            text-decoration: underline;
            margin-bottom: 4px;
            cursor: pointer;
        }

        .google-desc {
            color: #bdc1c6;
            font-size: 13px;
            line-height: 1.5;
        }

        /* Mobile Screen Mockup */
        .mobile-screen {
            background: linear-gradient(180deg, #1e1b4b, #0f172a);
            border-radius: 16px;
            padding: 20px 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 22px;
        }

        .mobile-app-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 6px;
        }

        .mobile-app-icon {
            width: 56px;
            height: 56px;
            border-radius: 14px;
            background: #000;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            border: 1px solid rgba(255,255,255,0.12);
            box-shadow: 0 6px 14px rgba(0,0,0,0.4);
        }

        .mobile-app-icon img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .mobile-app-label {
            font-size: 11px;
            color: #ffffff;
            font-weight: 500;
            text-shadow: 0 1px 3px rgba(0,0,0,0.8);
        }

        /* Customization Controls Sidebar */
        .customizer-card {
            background: var(--card-bg);
            border: 1px solid var(--card-border);
            border-radius: var(--radius);
            padding: 1.5rem;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.3);
            display: flex;
            flex-direction: column;
            gap: 1.25rem;
        }

        .control-group {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .control-label {
            font-size: 0.88rem;
            font-weight: 700;
            color: #e2e8f0;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .control-label span.val {
            font-family: 'JetBrains Mono', monospace;
            color: var(--accent);
            font-weight: 600;
            font-size: 0.82rem;
        }

        /* Toggle Buttons */
        .toggle-group {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 6px;
            background: rgba(255, 255, 255, 0.03);
            padding: 4px;
            border-radius: 10px;
            border: 1px solid var(--card-border);
        }

        .toggle-btn {
            padding: 0.5rem;
            font-size: 0.82rem;
            font-weight: 600;
            border-radius: 8px;
            border: none;
            background: transparent;
            color: var(--text-muted);
            cursor: pointer;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
        }

        .toggle-btn.active {
            background: rgba(56, 189, 248, 0.15);
            color: var(--accent);
            border: 1px solid rgba(56, 189, 248, 0.3);
        }

        /* Color Picker */
        .color-picker-row {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .color-input {
            width: 44px;
            height: 38px;
            padding: 0;
            border: 1px solid var(--card-border);
            border-radius: 8px;
            background: transparent;
            cursor: pointer;
        }

        .color-text {
            flex: 1;
            padding: 0.5rem 0.75rem;
            border-radius: 8px;
            background: #0b1120;
            border: 1px solid var(--card-border);
            color: #fff;
            font-family: 'JetBrains Mono', monospace;
            font-size: 0.85rem;
        }

        /* Range Sliders */
        .slider-wrapper {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .slider-input {
            flex: 1;
            accent-color: var(--accent);
            cursor: pointer;
        }

        /* Platforms Checkbox List */
        .platform-checkboxes {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .platform-item {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 0.85rem;
            color: #cbd5e1;
            cursor: pointer;
            user-select: none;
        }

        .platform-item input[type="checkbox"] {
            accent-color: var(--accent);
            width: 16px;
            height: 16px;
        }

        /* Native Resolutions Grid */
        .sizes-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
            gap: 1.25rem;
            margin-bottom: 2.5rem;
        }

        .size-card {
            background: var(--card-bg);
            border: 1px solid var(--card-border);
            border-radius: var(--radius);
            padding: 1.25rem 1rem;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0.75rem;
            text-align: center;
            transition: transform 0.2s ease, border-color 0.2s ease;
        }

        .size-card:hover {
            transform: translateY(-2px);
            border-color: rgba(56, 189, 248, 0.35);
        }

        .size-box {
            background: #0b101b;
            border: 1px dashed rgba(255, 255, 255, 0.15);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 6px;
        }

        .size-label {
            font-size: 0.88rem;
            font-weight: 700;
            color: var(--accent);
            font-family: 'JetBrains Mono', monospace;
        }

        .size-desc {
            font-size: 0.76rem;
            color: var(--text-muted);
            line-height: 1.35;
        }

        .size-dl-btn {
            margin-top: auto;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: var(--text);
            font-size: 0.75rem;
            padding: 0.35rem 0.65rem;
            border-radius: 6px;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            transition: all 0.2s ease;
        }

        .size-dl-btn:hover {
            background: var(--accent);
            color: #000;
            border-color: var(--accent);
        }

        /* Export Section */
        .export-card {
            background: var(--card-bg);
            border: 1px solid var(--card-border);
            border-radius: var(--radius);
            padding: 1.75rem;
            margin-bottom: 2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1.5rem;
            flex-wrap: wrap;
        }

        .export-info h3 {
            font-size: 1.2rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .export-info p {
            color: var(--text-muted);
            font-size: 0.88rem;
            margin-top: 4px;
        }

        .export-actions {
            display: flex;
            align-items: center;
            gap: 0.85rem;
            flex-wrap: wrap;
        }

        /* Code Boxes */
        .code-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.5rem;
            margin-bottom: 2.5rem;
        }

        @media (max-width: 800px) {
            .code-grid {
                grid-template-columns: 1fr;
            }
        }

        .code-card {
            background: var(--card-bg);
            border: 1px solid var(--card-border);
            border-radius: var(--radius);
            padding: 1.5rem;
        }

        .code-card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 0.75rem;
        }

        .code-card-header h4 {
            font-size: 0.95rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: #fff;
        }

        .code-copy-btn {
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid var(--card-border);
            color: #cbd5e1;
            font-size: 0.76rem;
            padding: 0.35rem 0.65rem;
            border-radius: 6px;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            transition: all 0.2s ease;
        }

        .code-copy-btn:hover {
            background: var(--accent);
            color: #000;
            border-color: var(--accent);
        }

        .code-box {
            background: #0b1120;
            border: 1px solid rgba(255,255,255,0.06);
            border-radius: 10px;
            padding: 1rem;
            font-family: 'JetBrains Mono', monospace;
            font-size: 0.8rem;
            color: #38bdf8;
            overflow-x: auto;
            line-height: 1.5;
            max-height: 180px;
        }

        /* Standard 4U Footer */
        .footer-clean {
            position: relative;
            width: 100%;
            z-index: 100;
            padding: 2.5rem 1.5rem;
            background: rgba(11, 15, 25, 0.95);
            backdrop-filter: blur(10px);
            border-top: 1px solid rgba(255, 255, 255, 0.06);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 12px;
            margin-top: 3rem;
        }

        .footer-brand {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 0.88rem;
            font-weight: 700;
            color: #cbd5e1;
        }

        .footer-brand i {
            color: var(--accent);
        }

        .footer-links {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            flex-wrap: wrap;
        }

        .footer-links a {
            color: #94a3b8;
            text-decoration: none;
            font-size: 0.82rem;
            font-weight: 500;
            transition: color 0.2s ease;
        }

        .footer-links a:hover {
            color: #f1f5f9;
        }

        .footer-links .sep {
            color: rgba(255, 255, 255, 0.15);
            font-size: 0.75rem;
        }

        .footer-links a.donate-link {
            color: #fef08a;
            font-weight: 700;
        }

        .footer-links a.donate-link:hover {
            color: #facc15;
        }

        .footer-copyright {
            font-size: 0.75rem;
            color: rgba(255, 255, 255, 0.35);
            letter-spacing: 0.05em;
        }

        /* Toast Notification */
        #toast {
            position: fixed;
            bottom: 28px;
            right: 28px;
            background: #0284c7;
            color: white;
            padding: 12px 22px;
            border-radius: 12px;
            font-weight: 600;
            font-size: 0.92rem;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.5);
            display: none;
            align-items: center;
            gap: 10px;
            z-index: 10000;
            animation: slideIn 0.3s ease;
        }

        @keyframes slideIn {
            from { transform: translateY(20px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
    </style>
</head>
<body>

    <!-- Top Navigation Bar -->
    <nav class="top-navbar">
        <div class="nav-container">
            <a href="https://4u.ia.br" class="brand-group">
                <img src="fav.png?v=<?= $v ?>" class="brand-logo-img" alt="4U">
                <div class="brand-name">4U<span>.IA.BR</span> // <span style="font-weight:600; color:#cbd5e1;">Favicon Studio</span> <span class="brand-badge">PRO</span></div>
            </a>
            <div class="nav-actions">
                <a href="https://4u.ia.br/loja/" class="nav-btn nav-btn-muted">
                    <i class="fas fa-store"></i> Loja de Apps
                </a>
                <a href="https://www.paypal.com/ncp/payment/L7YRCS984T33N" target="_blank" rel="noopener noreferrer" class="nav-btn nav-btn-donate" title="Apoie o Projeto via PayPal">
                    <i class="fas fa-coffee"></i> Apoie
                </a>
            </div>
        </div>
    </nav>

    <div class="container">
        
        <!-- Hero Section -->
        <header class="hero">
            <div class="hero-pill">
                <i class="fas fa-wand-magic-sparkles"></i> Testador, Simulador & Gerador Unificado
            </div>
            <h1 class="hero-title">Favicon Studio Pro</h1>
            <p class="hero-subtitle">
                Teste e simule em tempo real como o seu favicon aparece em abas de navegadores, no Google e em smartphones. Ajuste padding, cores e bordas e exporte o pacote completo para todas as plataformas.
            </p>
        </header>

        <!-- Top Controls Bar -->
        <div class="controls-card">
            <div class="source-info">
                <img id="current-fav-img" src="fav.png?v=<?= $v ?>" class="current-img-preview" alt="Favicon atual" onerror="handleImgError(this)">
                <div class="source-text">
                    <h3 id="image-status-title">Carregando imagem padrão...</h3>
                    <p id="image-meta">Arquivo: <strong>fav.png</strong></p>
                </div>
            </div>

            <div class="actions-group">
                <button class="btn btn-primary" onclick="reloadFavicon(true)">
                    <i class="fas fa-sync-alt"></i> Atualizar Aba (Forçar Cache)
                </button>
                <button class="btn btn-secondary" onclick="document.getElementById('file-input').click()">
                    <i class="fas fa-folder-open"></i> Escolher Imagem...
                </button>
                <input type="file" id="file-input" accept="image/*" style="display: none;" onchange="handleFileSelect(event)">
            </div>
        </div>

        <!-- Drag & Drop Zone -->
        <div class="dropzone" id="dropzone" onclick="document.getElementById('file-input').click()">
            <i class="fas fa-cloud-arrow-up"></i>
            <p>Arraste e solte uma imagem aqui, <span>clique para selecionar</span> ou <strong>cole direto da área de transferência (Ctrl + V)</strong>.</p>
            <p class="dropzone-tip">Suporta PNG, JPG, WebP e SVG transparente ou sólido</p>
        </div>

        <!-- Studio Grid (Simulador + Customizador) -->
        <div class="studio-grid">
            
            <!-- Left Column: Real-Time Browser & System Mockups -->
            <div class="mockups-column">
                <h2 class="section-title"><i class="fas fa-desktop"></i> Simulação em Navegadores & Sistemas Reais</h2>

                <!-- Mockup 1: Google Chrome Dark Mode -->
                <div class="mockup-card">
                    <div class="mockup-header">
                        <span><i class="fab fa-chrome"></i> Aba Google Chrome (Modo Escuro)</span>
                        <span style="color: #4ade80;">16 × 16 px</span>
                    </div>
                    <div class="mockup-content">
                        <div class="chrome-window-dark">
                            <div class="chrome-tabbar-dark">
                                <div class="chrome-tab-dark">
                                    <img class="preview-target" id="mock-tab-dark" src="fav.png?v=<?= $v ?>" alt="icon">
                                    <span>4U.IA.BR // Inovação e IA</span>
                                    <span class="close-btn">&times;</span>
                                </div>
                            </div>
                            <div class="chrome-addressbar-dark">
                                <i class="fas fa-lock" style="font-size: 10px; color: #8ab4f8;"></i>
                                <span>https://4u.ia.br</span>
                            </div>
                            <div class="chrome-body-dark">
                                Simulação fidedigna de renderização em aba física escura
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Mockup 2: Google Chrome Light Mode -->
                <div class="mockup-card">
                    <div class="mockup-header">
                        <span><i class="fab fa-chrome"></i> Aba Google Chrome (Modo Claro)</span>
                        <span style="color: #4ade80;">16 × 16 px</span>
                    </div>
                    <div class="mockup-content">
                        <div class="chrome-window-light">
                            <div class="chrome-tabbar-light">
                                <div class="chrome-tab-light">
                                    <img class="preview-target" id="mock-tab-light" src="fav.png?v=<?= $v ?>" alt="icon">
                                    <span>4U.IA.BR // Inovação e IA</span>
                                    <span class="close-btn" style="margin-left: auto; color: #5f6368;">&times;</span>
                                </div>
                            </div>
                            <div class="chrome-addressbar-light">
                                <i class="fas fa-lock" style="font-size: 10px; color: #1a73e8;"></i>
                                <span>https://4u.ia.br</span>
                            </div>
                            <div class="chrome-body-light">
                                Simulação fidedigna de renderização em aba física clara
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Mockup 3: Google Search SERP -->
                <div class="mockup-card">
                    <div class="mockup-header">
                        <span><i class="fab fa-google"></i> Resultado no Google Search</span>
                        <span style="color: #4ade80;">18 × 18 px</span>
                    </div>
                    <div class="mockup-content">
                        <div class="google-snippet">
                            <div class="google-site-line">
                                <div class="google-fav-circle">
                                    <img class="preview-target" id="mock-serp" src="fav.png?v=<?= $v ?>" alt="google fav">
                                </div>
                                <div>
                                    <div class="google-site-name">4U.IA.BR</div>
                                    <div class="google-site-url">https://4u.ia.br</div>
                                </div>
                            </div>
                            <div class="google-title">4U.IA.BR // Ecossistema de Aplicações e Inteligência Artificial</div>
                            <div class="google-desc">
                                Aplicações profissionais rodando 100% no navegador, zero instalação e máxima performance com foco em privacidade.
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Mockup 4: Smartphone Home Screen -->
                <div class="mockup-card">
                    <div class="mockup-header">
                        <span><i class="fas fa-mobile-screen"></i> Atalho na Tela Inicial (PWA / Mobile)</span>
                        <span style="color: #4ade80;">192 × 192 px</span>
                    </div>
                    <div class="mockup-content">
                        <div class="mobile-screen">
                            <div class="mobile-app-item">
                                <div class="mobile-app-icon" id="mock-mobile-container">
                                    <img class="preview-target" id="mock-mobile" src="fav.png?v=<?= $v ?>" alt="mobile icon">
                                </div>
                                <span class="mobile-app-label">Meu WebApp</span>
                            </div>
                            <div class="mobile-app-item" style="opacity: 0.45;">
                                <div class="mobile-app-icon" style="background: #1e293b; display: flex; align-items: center; justify-content: center;">
                                    <i class="fas fa-camera" style="font-size: 24px; color: #94a3b8;"></i>
                                </div>
                                <span class="mobile-app-label">Câmera</span>
                            </div>
                            <div class="mobile-app-item" style="opacity: 0.45;">
                                <div class="mobile-app-icon" style="background: #1e293b; display: flex; align-items: center; justify-content: center;">
                                    <i class="fas fa-envelope" style="font-size: 24px; color: #94a3b8;"></i>
                                </div>
                                <span class="mobile-app-label">E-mail</span>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Right Column: Live Customization & Generator Options -->
            <div>
                <h2 class="section-title"><i class="fas fa-sliders"></i> Ajustes de Geração</h2>
                <div class="customizer-card">
                    
                    <!-- Fundo: Transparente ou Sólido -->
                    <div class="control-group">
                        <label class="control-label">Fundo do Ícone</label>
                        <div class="toggle-group">
                            <button type="button" class="toggle-btn active" id="btn-bg-trans" onclick="setBackgroundMode('transparent')">
                                <i class="fas fa-border-none"></i> Transparente
                            </button>
                            <button type="button" class="toggle-btn" id="btn-bg-solid" onclick="setBackgroundMode('solid')">
                                <i class="fas fa-fill-drip"></i> Sólido
                            </button>
                        </div>
                    </div>

                    <!-- Cor de Fundo -->
                    <div class="control-group" id="group-bg-color" style="display: none;">
                        <label class="control-label">Cor de Preenchimento</label>
                        <div class="color-picker-row">
                            <input type="color" id="bg-color-picker" class="color-input" value="#0f172a" oninput="updateBgColor(this.value)">
                            <input type="text" id="bg-color-text" class="color-text" value="#0f172a" maxlength="7" oninput="updateBgColor(this.value)">
                        </div>
                    </div>

                    <!-- Padding / Margem Interna -->
                    <div class="control-group">
                        <label class="control-label">
                            <span>Padding (Safe Area)</span>
                            <span class="val" id="val-padding">0%</span>
                        </label>
                        <div class="slider-wrapper">
                            <input type="range" id="slider-padding" class="slider-input" min="0" max="30" value="0" oninput="updatePadding(this.value)">
                        </div>
                    </div>

                    <!-- Border Radius -->
                    <div class="control-group">
                        <label class="control-label">
                            <span>Bordas Arredondadas</span>
                            <span class="val" id="val-radius">0%</span>
                        </label>
                        <div class="slider-wrapper">
                            <input type="range" id="slider-radius" class="slider-input" min="0" max="50" value="0" oninput="updateRadius(this.value)">
                        </div>
                    </div>

                    <!-- Modo Maskable -->
                    <div class="control-group" style="padding-top: 6px; border-top: 1px solid var(--card-border);">
                        <label class="platform-item">
                            <input type="checkbox" id="check-maskable" checked onchange="updateMaskable(this.checked)">
                            <span><strong>Safe Area Maskable PWA</strong> (Otimização para ícones adaptativos)</span>
                        </label>
                    </div>

                    <!-- Plataformas Desejadas -->
                    <div class="control-group" style="padding-top: 6px; border-top: 1px solid var(--card-border);">
                        <label class="control-label">Plataformas no Pacote ZIP</label>
                        <div class="platform-checkboxes">
                            <label class="platform-item"><input type="checkbox" checked id="plat-fav"> <span>Favicons Web (16, 32, 48, 64)</span></label>
                            <label class="platform-item"><input type="checkbox" checked id="plat-pwa"> <span>PWA & Manifest (192, 512)</span></label>
                            <label class="platform-item"><input type="checkbox" checked id="plat-ios"> <span>Apple Touch / iOS (180)</span></label>
                            <label class="platform-item"><input type="checkbox" checked id="plat-android"> <span>Android Launcher Assets</span></label>
                        </div>
                    </div>

                    <!-- Botão de Ação Rápida -->
                    <button class="btn btn-success" style="width: 100%; justify-content: center; margin-top: 6px;" onclick="downloadAllZip()">
                        <i class="fas fa-file-zipper"></i> Baixar Pacote Completo (.ZIP)
                    </button>

                </div>
            </div>

        </div>

        <!-- Scaled Resolutions Preview & Individual Download -->
        <h2 class="section-title"><i class="fas fa-th-large"></i> Resoluções Nativas & Download Individual</h2>
        <div class="sizes-grid">
            <div class="size-card">
                <div class="size-box" style="width: 36px; height: 36px;">
                    <canvas id="canvas-16" width="16" height="16" style="width: 16px; height: 16px; object-fit: contain;"></canvas>
                </div>
                <div class="size-label">16 × 16</div>
                <div class="size-desc">Aba padrão Chrome/Edge/Firefox</div>
                <button class="size-dl-btn" onclick="downloadSize(16, 'favicon-16x16.png')">
                    <i class="fas fa-download"></i> Baixar PNG
                </button>
            </div>

            <div class="size-card">
                <div class="size-box" style="width: 50px; height: 50px;">
                    <canvas id="canvas-32" width="32" height="32" style="width: 32px; height: 32px; object-fit: contain;"></canvas>
                </div>
                <div class="size-label">32 × 32</div>
                <div class="size-desc">Telas HiDPI / Barra de Favoritos</div>
                <button class="size-dl-btn" onclick="downloadSize(32, 'favicon-32x32.png')">
                    <i class="fas fa-download"></i> Baixar PNG
                </button>
            </div>

            <div class="size-card">
                <div class="size-box" style="width: 66px; height: 66px;">
                    <canvas id="canvas-48" width="48" height="48" style="width: 48px; height: 48px; object-fit: contain;"></canvas>
                </div>
                <div class="size-label">48 × 48</div>
                <div class="size-desc">Barra de Tarefas do Windows</div>
                <button class="size-dl-btn" onclick="downloadSize(48, 'favicon-48x48.png')">
                    <i class="fas fa-download"></i> Baixar PNG
                </button>
            </div>

            <div class="size-card">
                <div class="size-box" style="width: 82px; height: 82px;">
                    <canvas id="canvas-64" width="64" height="64" style="width: 64px; height: 64px; object-fit: contain;"></canvas>
                </div>
                <div class="size-label">64 × 64</div>
                <div class="size-desc">macOS Dock / Alta Densidade</div>
                <button class="size-dl-btn" onclick="downloadSize(64, 'favicon-64x64.png')">
                    <i class="fas fa-download"></i> Baixar PNG
                </button>
            </div>

            <div class="size-card">
                <div class="size-box" style="width: 104px; height: 104px;">
                    <canvas id="canvas-180" width="180" height="180" style="width: 88px; height: 88px; object-fit: contain;"></canvas>
                </div>
                <div class="size-label">180 × 180</div>
                <div class="size-desc">Apple Touch Icon (iOS / Safari)</div>
                <button class="size-dl-btn" onclick="downloadSize(180, 'apple-touch-icon.png')">
                    <i class="fas fa-download"></i> Baixar PNG
                </button>
            </div>

            <div class="size-card">
                <div class="size-box" style="width: 104px; height: 104px;">
                    <canvas id="canvas-192" width="192" height="192" style="width: 88px; height: 88px; object-fit: contain;"></canvas>
                </div>
                <div class="size-label">192 × 192</div>
                <div class="size-desc">Android PWA / Web App Manifest</div>
                <button class="size-dl-btn" onclick="downloadSize(192, 'icon-192.png')">
                    <i class="fas fa-download"></i> Baixar PNG
                </button>
            </div>

            <div class="size-card">
                <div class="size-box" style="width: 104px; height: 104px;">
                    <canvas id="canvas-512" width="512" height="512" style="width: 88px; height: 88px; object-fit: contain;"></canvas>
                </div>
                <div class="size-label">512 × 512</div>
                <div class="size-desc">Splash Screen & PWA Alta Fidelidade</div>
                <button class="size-dl-btn" onclick="downloadSize(512, 'icon-512.png')">
                    <i class="fas fa-download"></i> Baixar PNG
                </button>
            </div>
        </div>

        <!-- Export Package Card -->
        <div class="export-card">
            <div class="export-info">
                <h3><i class="fas fa-boxes-packing" style="color: var(--accent);"></i> Pacote de Assets para Produção</h3>
                <p>Gere e baixe todos os ícones configurados juntamente com o arquivo <code>site.webmanifest</code> em um único ZIP.</p>
            </div>
            <div class="export-actions">
                <button class="btn btn-success" id="btn-main-zip" onclick="downloadAllZip()">
                    <i class="fas fa-file-zipper"></i> Baixar Pacote Completo (.ZIP)
                </button>
                <button class="btn btn-secondary" onclick="generateServerZip()">
                    <i class="fas fa-server"></i> Gerar via Servidor (Pastas Específicas)
                </button>
            </div>
        </div>

        <!-- Code Snippets Grid -->
        <div class="code-grid">
            <div class="code-card">
                <div class="code-card-header">
                    <h4><i class="fas fa-code" style="color: #38bdf8;"></i> Tags HTML para o <code>&lt;head&gt;</code></h4>
                    <button class="code-copy-btn" onclick="copySnippet('html-code')">
                        <i class="fas fa-copy"></i> Copiar
                    </button>
                </div>
                <div class="code-box" id="html-code">&lt;link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png"&gt;
&lt;link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png"&gt;
&lt;link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png"&gt;
&lt;link rel="manifest" href="/site.webmanifest"&gt;
&lt;meta name="theme-color" content="#0f172a"&gt;</div>
            </div>

            <div class="code-card">
                <div class="code-card-header">
                    <h4><i class="fas fa-file-lines" style="color: #818cf8;"></i> Arquivo <code>site.webmanifest</code></h4>
                    <button class="code-copy-btn" onclick="copySnippet('manifest-code')">
                        <i class="fas fa-copy"></i> Copiar
                    </button>
                </div>
                <div class="code-box" id="manifest-code">{
  "name": "Meu WebApp",
  "short_name": "WebApp",
  "icons": [
    {
      "src": "/icon-192.png",
      "sizes": "192x192",
      "type": "image/png"
    },
    {
      "src": "/icon-512.png",
      "sizes": "512x512",
      "type": "image/png"
    }
  ],
  "theme_color": "#0f172a",
  "background_color": "#0f172a",
  "display": "standalone"
}</div>
            </div>
        </div>

    </div>

    <!-- Standard 4U Institutional Footer -->
    <footer class="footer-clean">
        <div class="footer-brand">
            <i class="fas fa-icons"></i> <span>Favicon Studio Pro — 4U.IA.BR</span>
        </div>
        <div class="footer-links">
            <a href="privacidade.php">Privacidade</a>
            <span class="sep">•</span>
            <a href="termos.php">Termos de Uso</a>
            <span class="sep">•</span>
            <a href="suporte.php">Suporte & FAQ</a>
            <span class="sep">•</span>
            <a href="https://www.paypal.com/ncp/payment/L7YRCS984T33N" target="_blank" rel="noopener noreferrer" class="donate-link" title="Apoie o Projeto via PayPal">☕ Apoie</a>
            <span class="sep">•</span>
            <a href="https://github.com/4u-Labs" target="_blank" rel="noopener noreferrer" title="4U.IA.BR no GitHub">GitHub</a>
        </div>
        <div class="footer-copyright">
            &copy; <span id="year"><?php echo date('Y'); ?></span> 4U.IA.BR — Todos os direitos reservados.
        </div>
    </footer>

    <!-- Toast Notification -->
    <div id="toast">
        <i class="fas fa-check-circle"></i> <span id="toast-text">Ação concluída com sucesso!</span>
    </div>

    <script>
        // Configuração de Estado Global
        const state = {
            currentImage: null,
            currentImageSrc: 'fav.png?v=<?= $v ?>',
            fileName: 'fav.png',
            bgMode: 'transparent',
            bgColor: '#0f172a',
            padding: 0,
            radius: 0,
            maskable: true
        };

        const targetSizes = [16, 32, 48, 64, 180, 192, 512];

        function showToast(message) {
            const toast = document.getElementById('toast');
            document.getElementById('toast-text').innerText = message;
            toast.style.display = 'flex';
            setTimeout(() => { toast.style.display = 'none'; }, 3500);
        }

        function copySnippet(elementId) {
            const text = document.getElementById(elementId).innerText;
            navigator.clipboard.writeText(text).then(() => {
                showToast('Código copiado para a área de transferência!');
            }).catch(() => {
                showToast('Copiado com sucesso!');
            });
        }

        // Renderiza imagem com opções de padding, background e border-radius
        function drawProcessedToCanvas(canvas, size) {
            canvas.width = size;
            canvas.height = size;
            const ctx = canvas.getContext('2d');
            ctx.clearRect(0, 0, size, size);
            ctx.imageSmoothingEnabled = true;
            ctx.imageSmoothingQuality = 'high';

            // Background sólido se ativo
            if (state.bgMode === 'solid') {
                ctx.fillStyle = state.bgColor;
                if (state.radius > 0) {
                    const r = (state.radius / 100) * (size / 2);
                    roundRect(ctx, 0, 0, size, size, r);
                    ctx.fill();
                } else {
                    ctx.fillRect(0, 0, size, size);
                }
            }

            if (!state.currentImage) return;

            // Calcula padding
            const padPx = (state.padding / 100) * size;
            const drawSize = Math.max(1, size - (padPx * 2));

            // Proporções
            const imgW = state.currentImage.naturalWidth || state.currentImage.width;
            const imgH = state.currentImage.naturalHeight || state.currentImage.height;
            const ratio = imgW / imgH;

            let dw, dh;
            if (ratio >= 1) {
                dw = drawSize;
                dh = drawSize / ratio;
            } else {
                dh = drawSize;
                dw = drawSize * ratio;
            }

            const ox = (size - dw) / 2;
            const oy = (size - dh) / 2;

            // Recorte por border-radius
            if (state.radius > 0) {
                ctx.save();
                const r = (state.radius / 100) * (size / 2);
                roundRect(ctx, 0, 0, size, size, r);
                ctx.clip();
            }

            ctx.drawImage(state.currentImage, ox, oy, dw, dh);

            if (state.radius > 0) {
                ctx.restore();
            }
        }

        function roundRect(ctx, x, y, width, height, radius) {
            ctx.beginPath();
            ctx.moveTo(x + radius, y);
            ctx.lineTo(x + width - radius, y);
            ctx.quadraticCurveTo(x + width, y, x + width, y + radius);
            ctx.lineTo(x + width, y + height - radius);
            ctx.quadraticCurveTo(x + width, y + height, x + width - radius, y + height);
            ctx.lineTo(x + radius, y + height);
            ctx.quadraticCurveTo(x, y + height, x, y + height - radius);
            ctx.lineTo(x, y + radius);
            ctx.quadraticCurveTo(x, y, x + radius, y);
            ctx.closePath();
        }

        // Atualiza todas as prévias da tela
        function updateAllPreviews() {
            if (!state.currentImage) return;

            // 1. Renderiza os tamanhos nativos
            targetSizes.forEach(size => {
                const cv = document.getElementById(`canvas-${size}`);
                if (cv) {
                    drawProcessedToCanvas(cv, size);
                }
            });

            // 2. Cria versão base para os mockups
            const previewCanvas = document.createElement('canvas');
            drawProcessedToCanvas(previewCanvas, 192);
            const previewDataUrl = previewCanvas.toDataURL('image/png');

            // Atualiza imagem do topo
            const topImg = document.getElementById('current-fav-img');
            if (topImg) topImg.src = previewDataUrl;

            // Atualiza mockups
            const mDark = document.getElementById('mock-tab-dark');
            const mLight = document.getElementById('mock-tab-light');
            const mSerp = document.getElementById('mock-serp');
            const mMobile = document.getElementById('mock-mobile');

            if (mDark) mDark.src = previewDataUrl;
            if (mLight) mLight.src = previewDataUrl;
            if (mSerp) mSerp.src = previewDataUrl;
            if (mMobile) mMobile.src = previewDataUrl;

            // 3. Atualiza o favicon real da aba ativa do navegador
            setBrowserFavicon(previewDataUrl);

            // Atualiza diagnósticos
            const isSquare = (state.currentImage.naturalWidth === state.currentImage.naturalHeight);
            document.getElementById('image-status-title').innerHTML = 
                `<i class="fas fa-check-circle" style="color: #4ade80;"></i> Imagem Carregada (${state.currentImage.naturalWidth} × ${state.currentImage.naturalHeight} px)`;
            document.getElementById('image-meta').innerHTML = 
                `Proporção: <strong>${isSquare ? '1:1 (Perfeita)' : (state.currentImage.naturalWidth + ':' + state.currentImage.naturalHeight + ' — Não quadrada')}</strong> | Arquivo: ${state.fileName}`;
        }

        // Aplica o favicon na aba física do navegador
        function setBrowserFavicon(url) {
            document.querySelectorAll("link[rel*='icon']").forEach(el => el.remove());

            const link32 = document.createElement('link');
            link32.rel = 'icon';
            link32.type = 'image/png';
            link32.sizes = '32x32';
            link32.href = url;
            document.head.appendChild(link32);

            const link16 = document.createElement('link');
            link16.rel = 'icon';
            link16.type = 'image/png';
            link16.sizes = '16x16';
            link16.href = url;
            document.head.appendChild(link16);

            const appleLink = document.createElement('link');
            appleLink.rel = 'apple-touch-icon';
            appleLink.sizes = '180x180';
            appleLink.href = url;
            document.head.appendChild(appleLink);
        }

        // Carrega imagem fonte
        function loadSourceImage(src, name, notify) {
            const img = new Image();
            img.crossOrigin = 'anonymous';
            img.onload = function() {
                state.currentImage = this;
                state.currentImageSrc = src;
                state.fileName = name || 'imagem';
                updateAllPreviews();
                if (notify) {
                    showToast(`Favicon "${state.fileName}" aplicado com sucesso!`);
                }
            };
            img.onerror = function() {
                document.getElementById('image-status-title').innerHTML = 
                    `<i class="fas fa-exclamation-triangle" style="color: #f87171;"></i> Erro ao carregar imagem`;
            };
            img.src = src;
        }

        function reloadFavicon(notify) {
            const timestamp = new Date().getTime();
            loadSourceImage('fav.png?v=' + timestamp, 'fav.png', notify);
        }

        // Controles de Customização
        function setBackgroundMode(mode) {
            state.bgMode = mode;
            document.getElementById('btn-bg-trans').classList.toggle('active', mode === 'transparent');
            document.getElementById('btn-bg-solid').classList.toggle('active', mode === 'solid');
            document.getElementById('group-bg-color').style.display = (mode === 'solid') ? 'flex' : 'none';
            updateAllPreviews();
        }

        function updateBgColor(color) {
            state.bgColor = color;
            document.getElementById('bg-color-picker').value = color;
            document.getElementById('bg-color-text').value = color;
            if (state.bgMode === 'solid') {
                updateAllPreviews();
            }
        }

        function updatePadding(val) {
            state.padding = parseInt(val, 10);
            document.getElementById('val-padding').innerText = val + '%';
            updateAllPreviews();
        }

        function updateRadius(val) {
            state.radius = parseInt(val, 10);
            document.getElementById('val-radius').innerText = val + '%';
            updateAllPreviews();
        }

        function updateMaskable(checked) {
            state.maskable = checked;
            if (checked && state.padding < 10) {
                updatePadding(10);
                document.getElementById('slider-padding').value = 10;
            }
        }

        // Manipulação de Arquivos (Upload, Drag&Drop, Paste)
        function handleFile(file) {
            if (!file || !file.type.startsWith('image/')) {
                alert('Por favor, envie um arquivo de imagem válido (PNG, JPG, SVG, WebP).');
                return;
            }
            const reader = new FileReader();
            reader.onload = function(e) {
                loadSourceImage(e.target.result, file.name, true);
            };
            reader.readAsDataURL(file);
        }

        function handleFileSelect(event) {
            const file = event.target.files[0];
            if (file) handleFile(file);
        }

        // Drag & Drop
        const dropzone = document.getElementById('dropzone');
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(evt => {
            dropzone.addEventListener(evt, preventDefaults, false);
            document.body.addEventListener(evt, preventDefaults, false);
        });

        function preventDefaults(e) {
            e.preventDefault();
            e.stopPropagation();
        }

        ['dragenter', 'dragover'].forEach(evt => {
            dropzone.addEventListener(evt, () => dropzone.classList.add('dragover'), false);
        });

        ['dragleave', 'drop'].forEach(evt => {
            dropzone.addEventListener(evt, () => dropzone.classList.remove('dragover'), false);
        });

        dropzone.addEventListener('drop', (e) => {
            const dt = e.dataTransfer;
            const files = dt.files;
            if (files && files.length > 0) {
                handleFile(files[0]);
            }
        });

        // Paste da área de transferência (Ctrl + V)
        window.addEventListener('paste', (e) => {
            const items = (e.clipboardData || e.originalEvent.clipboardData).items;
            for (const item of items) {
                if (item.type.indexOf('image') === 0) {
                    const blob = item.getAsFile();
                    handleFile(blob);
                    break;
                }
            }
        });

        // Download de tamanho único
        function downloadSize(size, filename) {
            if (!state.currentImage) {
                alert('Aguarde o carregamento da imagem...');
                return;
            }
            const canvas = document.createElement('canvas');
            drawProcessedToCanvas(canvas, size);
            const link = document.createElement('a');
            link.download = filename;
            link.href = canvas.toDataURL('image/png');
            link.click();
            showToast(`Download de ${filename} iniciado!`);
        }

        // Download de Pacote ZIP Instantâneo via JSZip
        async function downloadAllZip() {
            if (!state.currentImage) {
                alert('Aguarde o carregamento da imagem...');
                return;
            }

            if (typeof JSZip === 'undefined') {
                alert('JSZip não carregou. Baixe individualmente pelos botões acima.');
                return;
            }

            const btn = document.getElementById('btn-main-zip');
            const originalHtml = btn.innerHTML;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Gerando ZIP...';
            btn.disabled = true;

            try {
                const zip = new JSZip();

                const filesConfig = [
                    { size: 16, name: 'favicon-16x16.png' },
                    { size: 32, name: 'favicon-32x32.png' },
                    { size: 48, name: 'favicon-48x48.png' },
                    { size: 64, name: 'favicon-64x64.png' },
                    { size: 180, name: 'apple-touch-icon.png' },
                    { size: 192, name: 'icon-192.png' },
                    { size: 512, name: 'icon-512.png' }
                ];

                for (const item of filesConfig) {
                    const cv = document.createElement('canvas');
                    drawProcessedToCanvas(cv, item.size);
                    const dataUrl = cv.toDataURL('image/png');
                    const base64 = dataUrl.replace(/^data:image\/png;base64,/, '');
                    zip.file(item.name, base64, { base64: true });
                }

                // manifest.json
                const manifest = {
                    name: "Meu WebApp",
                    short_name: "WebApp",
                    icons: [
                        { src: "/icon-192.png", sizes: "192x192", type: "image/png" },
                        { src: "/icon-512.png", sizes: "512x512", type: "image/png" }
                    ],
                    theme_color: state.bgColor,
                    background_color: state.bgColor,
                    display: "standalone"
                };
                zip.file("site.webmanifest", JSON.stringify(manifest, null, 2));

                const blob = await zip.generateAsync({ type: "blob" });
                const link = document.createElement('a');
                link.href = URL.createObjectURL(blob);
                link.download = 'favicon-studio-assets.zip';
                link.click();

                showToast('Pacote ZIP completo baixado com sucesso!');
            } catch (err) {
                alert('Erro ao gerar ZIP: ' + err.message);
            } finally {
                btn.innerHTML = originalHtml;
                btn.disabled = false;
            }
        }

        // Geração via Servidor PHP (generate.php)
        async function generateServerZip() {
            if (!state.currentImage) {
                alert('Aguarde o carregamento da imagem...');
                return;
            }

            showToast('Enviando para o gerador de servidor...');
            const canvas = document.createElement('canvas');
            drawProcessedToCanvas(canvas, 512);

            canvas.toBlob(async (blob) => {
                const formData = new FormData();
                formData.append('image', blob, state.fileName || 'icon.png');
                formData.append('background', state.bgMode);
                formData.append('bgColor', state.bgColor);
                formData.append('padding', state.padding);
                formData.append('borderRadius', state.radius);
                formData.append('maskableMode', state.maskable ? '1' : '0');
                formData.append('platforms[]', 'favicon');
                formData.append('platforms[]', 'pwa');
                formData.append('platforms[]', 'ios');
                formData.append('platforms[]', 'android');

                try {
                    const res = await fetch('generate.php', { method: 'POST', body: formData });
                    const data = await res.json();
                    if (data.success && data.downloadUrl) {
                        window.location.href = data.downloadUrl;
                        showToast('Download do pacote avançado iniciado!');
                    } else {
                        // Fallback para client-side zip
                        downloadAllZip();
                    }
                } catch(e) {
                    downloadAllZip();
                }
            }, 'image/png');
        }

        function handleImgError(img) {
            img.src = 'data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="64" height="64"><rect width="64" height="64" fill="%23334155"/><text x="50%" y="55%" dominant-baseline="middle" text-anchor="middle" fill="%2394a3b8" font-size="12">Sem Img</text></svg>';
        }

        // Inicialização
        window.addEventListener('DOMContentLoaded', () => {
            reloadFavicon(false);
            const y = new Date().getFullYear();
            if (document.getElementById('year')) document.getElementById('year').textContent = y;
        });
    </script>
</body>
</html>
