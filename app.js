/**
 * FaviconForge Pro - Main Application
 * Professional Favicon Generator
 * 
 * @author FaviconForge Team
 * @version 1.0.0
 */

(function() {
    'use strict';

    // ===================================
    // Configuration
    // ===================================
    const CONFIG = {
        maxFileSize: 10 * 1024 * 1024, // 10MB
        allowedTypes: ['image/png', 'image/jpeg', 'image/jpg', 'image/svg+xml', 'image/webp'],
        allowedExtensions: ['png', 'jpg', 'jpeg', 'svg', 'webp'],
        apiEndpoint: 'generate.php',
        storageKey: 'faviconforge_settings'
    };

    // ===================================
    // State Management
    // ===================================
    const state = {
        file: null,
        imageData: null,
        settings: {
            platforms: ['favicon', 'pwa', 'chrome', 'android', 'ios'],
            background: 'transparent',
            bgColor: '#ffffff',
            padding: 10,
            borderRadius: 0,
            maskableMode: true
        },
        isGenerating: false,
        results: null
    };

    // ===================================
    // DOM Elements
    // ===================================
    const elements = {
        // Theme
        themeToggle: document.getElementById('themeToggle'),
        
        // Upload
        uploadZone: document.getElementById('uploadZone'),
        fileInput: document.getElementById('fileInput'),
        previewContainer: document.getElementById('previewContainer'),
        previewImage: document.getElementById('previewImage'),
        imageInfo: document.getElementById('imageInfo'),
        removeImage: document.getElementById('removeImage'),
        
        // Options
        platformCheckboxes: document.querySelectorAll('input[name="platforms"]'),
        backgroundToggles: document.querySelectorAll('[data-option="background"]'),
        bgColorOption: document.getElementById('bgColorOption'),
        bgColor: document.getElementById('bgColor'),
        bgColorText: document.getElementById('bgColorText'),
        padding: document.getElementById('padding'),
        paddingValue: document.getElementById('paddingValue'),
        borderRadius: document.getElementById('borderRadius'),
        borderRadiusValue: document.getElementById('borderRadiusValue'),
        maskableMode: document.getElementById('maskableMode'),
        
        // Preview
        previewTabs: document.querySelectorAll('.preview-tab'),
        previewPanels: document.querySelectorAll('.preview-panel'),
        previewFavicon: document.querySelector('.preview-favicon'),
        previewAndroidIcon: document.querySelector('.preview-android-icon'),
        previewIosIcon: document.querySelector('.preview-ios-icon'),
        previewPwaIcon: document.querySelector('.preview-pwa-icon'),
        previewPwaSplash: document.querySelector('.preview-pwa-splash'),
        
        // Generate
        generateBtn: document.getElementById('generateBtn'),
        
        // Progress
        progressSection: document.getElementById('progressSection'),
        progressFill: document.getElementById('progressFill'),
        progressPercent: document.getElementById('progressPercent'),
        progressStatus: document.getElementById('progressStatus'),
        
        // Results
        resultsSection: document.getElementById('resultsSection'),
        resultsGrid: document.getElementById('resultsGrid'),
        downloadAllBtn: document.getElementById('downloadAllBtn'),
        htmlCode: document.getElementById('htmlCode'),
        manifestSection: document.getElementById('manifestSection'),
        manifestCode: document.getElementById('manifestCode'),
        newGenerationBtn: document.getElementById('newGenerationBtn'),
        copyButtons: document.querySelectorAll('.btn-copy'),
        
        // Toast
        toast: document.getElementById('toast')
    };

    // ===================================
    // Theme Management
    // ===================================
    const ThemeManager = {
        init() {
            const savedTheme = localStorage.getItem('theme');
            const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            const theme = savedTheme || (prefersDark ? 'dark' : 'light');
            this.setTheme(theme);
            
            elements.themeToggle.addEventListener('click', () => this.toggle());
            
            // Listen for system theme changes
            window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', (e) => {
                if (!localStorage.getItem('theme')) {
                    this.setTheme(e.matches ? 'dark' : 'light');
                }
            });
        },
        
        setTheme(theme) {
            document.documentElement.setAttribute('data-theme', theme);
            localStorage.setItem('theme', theme);
        },
        
        toggle() {
            const current = document.documentElement.getAttribute('data-theme');
            this.setTheme(current === 'dark' ? 'light' : 'dark');
        }
    };

    // ===================================
    // Settings Management
    // ===================================
    const SettingsManager = {
        load() {
            try {
                const saved = localStorage.getItem(CONFIG.storageKey);
                if (saved) {
                    const parsed = JSON.parse(saved);
                    state.settings = { ...state.settings, ...parsed };
                }
            } catch (e) {
                console.warn('Could not load settings:', e);
            }
        },
        
        save() {
            try {
                localStorage.setItem(CONFIG.storageKey, JSON.stringify(state.settings));
            } catch (e) {
                console.warn('Could not save settings:', e);
            }
        },
        
        apply() {
            // Apply platforms
            elements.platformCheckboxes.forEach(cb => {
                cb.checked = state.settings.platforms.includes(cb.value);
            });
            
            // Apply background
            elements.backgroundToggles.forEach(btn => {
                btn.classList.toggle('active', btn.dataset.value === state.settings.background);
            });
            elements.bgColorOption.hidden = state.settings.background !== 'solid';
            
            // Apply colors
            elements.bgColor.value = state.settings.bgColor;
            elements.bgColorText.value = state.settings.bgColor;
            
            // Apply padding
            elements.padding.value = state.settings.padding;
            elements.paddingValue.textContent = state.settings.padding + '%';
            
            // Apply border radius
            elements.borderRadius.value = state.settings.borderRadius;
            elements.borderRadiusValue.textContent = state.settings.borderRadius + '%';
            
            // Apply maskable mode
            elements.maskableMode.checked = state.settings.maskableMode;
        }
    };

    // ===================================
    // File Upload Handler
    // ===================================
    const UploadHandler = {
        init() {
            // Click to upload
            elements.uploadZone.addEventListener('click', () => {
                if (!state.file) {
                    elements.fileInput.click();
                }
            });
            
            // File input change
            elements.fileInput.addEventListener('change', (e) => {
                if (e.target.files.length > 0) {
                    this.handleFile(e.target.files[0]);
                }
            });
            
            // Drag and drop
            elements.uploadZone.addEventListener('dragover', (e) => {
                e.preventDefault();
                elements.uploadZone.classList.add('drag-over');
            });
            
            elements.uploadZone.addEventListener('dragleave', (e) => {
                e.preventDefault();
                elements.uploadZone.classList.remove('drag-over');
            });
            
            elements.uploadZone.addEventListener('drop', (e) => {
                e.preventDefault();
                elements.uploadZone.classList.remove('drag-over');
                
                if (e.dataTransfer.files.length > 0) {
                    this.handleFile(e.dataTransfer.files[0]);
                }
            });
            
            // Remove image
            elements.removeImage.addEventListener('click', (e) => {
                e.stopPropagation();
                this.removeFile();
            });
        },
        
        handleFile(file) {
            // Validate file type
            const extension = file.name.split('.').pop().toLowerCase();
            
            if (!CONFIG.allowedTypes.includes(file.type) && !CONFIG.allowedExtensions.includes(extension)) {
                Toast.show('Formato não suportado. Use PNG, JPG, SVG ou WEBP.', 'error');
                return;
            }
            
            // Validate file size
            if (file.size > CONFIG.maxFileSize) {
                Toast.show('Arquivo muito grande. Máximo de 10MB.', 'error');
                return;
            }
            
            state.file = file;
            
            // Read and preview
            const reader = new FileReader();
            reader.onload = (e) => {
                state.imageData = e.target.result;
                this.showPreview();
                PreviewManager.updateAll();
            };
            reader.readAsDataURL(file);
        },
        
        showPreview() {
            const img = new Image();
            img.onload = () => {
                elements.previewImage.src = state.imageData;
                elements.imageInfo.innerHTML = `
                    <span>📐 ${img.width} × ${img.height}px</span>
                    <span>📁 ${this.formatFileSize(state.file.size)}</span>
                    <span>🖼️ ${state.file.type.split('/')[1].toUpperCase()}</span>
                `;
                
                elements.previewContainer.hidden = false;
                elements.generateBtn.disabled = false;
                
                // Hide results if showing
                elements.resultsSection.hidden = true;
            };
            img.src = state.imageData;
        },
        
        removeFile() {
            state.file = null;
            state.imageData = null;
            elements.fileInput.value = '';
            elements.previewContainer.hidden = true;
            elements.generateBtn.disabled = true;
            elements.resultsSection.hidden = true;
            PreviewManager.reset();
        },
        
        formatFileSize(bytes) {
            if (bytes < 1024) return bytes + ' B';
            if (bytes < 1024 * 1024) return (bytes / 1024).toFixed(1) + ' KB';
            return (bytes / (1024 * 1024)).toFixed(2) + ' MB';
        }
    };

    // ===================================
    // Options Handler
    // ===================================
    const OptionsHandler = {
        init() {
            // Platform checkboxes
            elements.platformCheckboxes.forEach(cb => {
                cb.addEventListener('change', () => {
                    state.settings.platforms = Array.from(elements.platformCheckboxes)
                        .filter(c => c.checked)
                        .map(c => c.value);
                    SettingsManager.save();
                });
            });
            
            // Background toggle
            elements.backgroundToggles.forEach(btn => {
                btn.addEventListener('click', () => {
                    elements.backgroundToggles.forEach(b => b.classList.remove('active'));
                    btn.classList.add('active');
                    state.settings.background = btn.dataset.value;
                    elements.bgColorOption.hidden = state.settings.background !== 'solid';
                    SettingsManager.save();
                    PreviewManager.updateAll();
                });
            });
            
            // Background color
            elements.bgColor.addEventListener('input', (e) => {
                state.settings.bgColor = e.target.value;
                elements.bgColorText.value = e.target.value;
                SettingsManager.save();
                PreviewManager.updateAll();
            });
            
            elements.bgColorText.addEventListener('input', (e) => {
                let value = e.target.value;
                if (!value.startsWith('#')) value = '#' + value;
                if (/^#[0-9A-Fa-f]{6}$/.test(value)) {
                    state.settings.bgColor = value;
                    elements.bgColor.value = value;
                    SettingsManager.save();
                    PreviewManager.updateAll();
                }
            });
            
            // Padding
            elements.padding.addEventListener('input', (e) => {
                state.settings.padding = parseInt(e.target.value);
                elements.paddingValue.textContent = e.target.value + '%';
                SettingsManager.save();
                PreviewManager.updateAll();
            });
            
            // Border radius
            elements.borderRadius.addEventListener('input', (e) => {
                state.settings.borderRadius = parseInt(e.target.value);
                elements.borderRadiusValue.textContent = e.target.value + '%';
                SettingsManager.save();
                PreviewManager.updateAll();
            });
            
            // Maskable mode
            elements.maskableMode.addEventListener('change', (e) => {
                state.settings.maskableMode = e.target.checked;
                SettingsManager.save();
            });
        }
    };

    // ===================================
    // Preview Manager
    // ===================================
    const PreviewManager = {
        init() {
            // Tab switching
            elements.previewTabs.forEach(tab => {
                tab.addEventListener('click', () => {
                    const targetId = tab.dataset.tab + 'Preview';
                    
                    elements.previewTabs.forEach(t => t.classList.remove('active'));
                    elements.previewPanels.forEach(p => p.classList.remove('active'));
                    
                    tab.classList.add('active');
                    document.getElementById(targetId).classList.add('active');
                });
            });
        },
        
        updateAll() {
            if (!state.imageData) return;
            
            // Create processed preview
            this.createPreviewImage().then(dataUrl => {
                if (elements.previewFavicon) elements.previewFavicon.src = dataUrl;
                if (elements.previewAndroidIcon) elements.previewAndroidIcon.src = dataUrl;
                if (elements.previewIosIcon) elements.previewIosIcon.src = dataUrl;
                if (elements.previewPwaIcon) elements.previewPwaIcon.src = dataUrl;
                if (elements.previewPwaSplash) elements.previewPwaSplash.src = dataUrl;
            });
        },
        
        createPreviewImage() {
            return new Promise((resolve) => {
                const canvas = document.createElement('canvas');
                const ctx = canvas.getContext('2d');
                const size = 192;
                
                canvas.width = size;
                canvas.height = size;
                
                // Background
                if (state.settings.background === 'solid') {
                    ctx.fillStyle = state.settings.bgColor;
                    
                    if (state.settings.borderRadius > 0) {
                        const radius = (state.settings.borderRadius / 100) * (size / 2);
                        this.roundRect(ctx, 0, 0, size, size, radius);
                        ctx.fill();
                    } else {
                        ctx.fillRect(0, 0, size, size);
                    }
                }
                
                const img = new Image();
                img.crossOrigin = 'anonymous';
                img.onload = () => {
                    const padding = (state.settings.padding / 100) * size;
                    const drawSize = size - (padding * 2);
                    
                    // Calculate aspect ratio
                    const aspectRatio = img.width / img.height;
                    let drawWidth, drawHeight, offsetX, offsetY;
                    
                    if (aspectRatio > 1) {
                        drawWidth = drawSize;
                        drawHeight = drawSize / aspectRatio;
                    } else {
                        drawHeight = drawSize;
                        drawWidth = drawSize * aspectRatio;
                    }
                    
                    offsetX = (size - drawWidth) / 2;
                    offsetY = (size - drawHeight) / 2;
                    
                    // Apply clipping for border radius
                    if (state.settings.borderRadius > 0) {
                        ctx.save();
                        const radius = (state.settings.borderRadius / 100) * (size / 2);
                        this.roundRect(ctx, 0, 0, size, size, radius);
                        ctx.clip();
                    }
                    
                    ctx.drawImage(img, offsetX, offsetY, drawWidth, drawHeight);
                    
                    if (state.settings.borderRadius > 0) {
                        ctx.restore();
                    }
                    
                    resolve(canvas.toDataURL('image/png'));
                };
                img.src = state.imageData;
            });
        },
        
        roundRect(ctx, x, y, width, height, radius) {
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
        },
        
        reset() {
            const placeholder = "data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 48 48'%3E%3Crect fill='%23e5e7eb' width='48' height='48' rx='8'/%3E%3C/svg%3E";
            
            if (elements.previewFavicon) elements.previewFavicon.src = placeholder;
            if (elements.previewAndroidIcon) elements.previewAndroidIcon.src = placeholder;
            if (elements.previewIosIcon) elements.previewIosIcon.src = placeholder;
            if (elements.previewPwaIcon) elements.previewPwaIcon.src = placeholder;
            if (elements.previewPwaSplash) elements.previewPwaSplash.src = placeholder;
        }
    };

    // ===================================
    // Generator
    // ===================================
    const Generator = {
        init() {
            elements.generateBtn.addEventListener('click', () => this.generate());
            elements.newGenerationBtn.addEventListener('click', () => this.reset());
            elements.downloadAllBtn.addEventListener('click', () => this.downloadAll());
            
            // Copy buttons
            elements.copyButtons.forEach(btn => {
                btn.addEventListener('click', () => {
                    const targetId = btn.dataset.target;
                    const text = document.getElementById(targetId).textContent;
                    this.copyToClipboard(text);
                });
            });
        },
        
        async generate() {
            if (!state.file || state.isGenerating) return;
            
            if (state.settings.platforms.length === 0) {
                Toast.show('Selecione pelo menos uma plataforma.', 'error');
                return;
            }
            
            state.isGenerating = true;
            elements.generateBtn.classList.add('loading');
            elements.generateBtn.querySelector('.btn-text').textContent = 'Gerando...';
            
            // Show progress
            elements.progressSection.hidden = false;
            elements.resultsSection.hidden = true;
            this.updateProgress(0, 'Preparando upload...');
            
            try {
                const formData = new FormData();
                formData.append('image', state.file);
                formData.append('platforms', JSON.stringify(state.settings.platforms));
                formData.append('background', state.settings.background);
                formData.append('bgColor', state.settings.bgColor);
                formData.append('padding', state.settings.padding);
                formData.append('borderRadius', state.settings.borderRadius);
                formData.append('maskableMode', state.settings.maskableMode ? '1' : '0');
                
                this.updateProgress(20, 'Enviando imagem...');
                
                const response = await fetch(CONFIG.apiEndpoint, {
                    method: 'POST',
                    body: formData
                });
                
                this.updateProgress(60, 'Processando ícones...');
                
                if (!response.ok) {
                    throw new Error('Erro no servidor: ' + response.status);
                }
                
                const result = await response.json();
                
                if (!result.success) {
                    throw new Error(result.error || 'Erro desconhecido');
                }
                
                this.updateProgress(90, 'Finalizando...');
                
                state.results = result;
                
                await new Promise(resolve => setTimeout(resolve, 500));
                
                this.updateProgress(100, 'Concluído!');
                
                await new Promise(resolve => setTimeout(resolve, 300));
                
                this.showResults();
                Toast.show('Ícones gerados com sucesso!', 'success');
                
            } catch (error) {
                console.error('Generation error:', error);
                Toast.show(error.message || 'Erro ao gerar ícones.', 'error');
                elements.progressSection.hidden = true;
            } finally {
                state.isGenerating = false;
                elements.generateBtn.classList.remove('loading');
                elements.generateBtn.querySelector('.btn-text').textContent = 'Gerar Ícones';
            }
        },
        
        updateProgress(percent, status) {
            elements.progressFill.style.width = percent + '%';
            elements.progressPercent.textContent = percent + '%';
            elements.progressStatus.textContent = status;
        },
        
        showResults() {
            elements.progressSection.hidden = true;
            elements.resultsSection.hidden = false;
            
            // Build results grid
            let gridHTML = '';
            
            if (state.results.icons) {
                state.results.icons.forEach(icon => {
                    gridHTML += `
                        <div class="result-item">
                            <div class="result-icon">
                                <img src="${icon.preview}" alt="${icon.name}">
                            </div>
                            <div class="result-info">
                                <span class="result-size">${icon.size}</span>
                                <span class="result-type">${icon.type}</span>
                            </div>
                            <button class="result-download" data-file="${icon.file}" title="Baixar">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4M7 10l5 5 5-5M12 15V3"/>
                                </svg>
                            </button>
                        </div>
                    `;
                });
            }
            
            elements.resultsGrid.innerHTML = gridHTML;
            
            // Add download handlers
            elements.resultsGrid.querySelectorAll('.result-download').forEach(btn => {
                btn.addEventListener('click', () => {
                    this.downloadFile(btn.dataset.file);
                });
            });
            
            // HTML code
            elements.htmlCode.textContent = state.results.htmlCode || '';
            
            // Manifest
            if (state.results.manifest) {
                elements.manifestSection.hidden = false;
                elements.manifestCode.textContent = JSON.stringify(state.results.manifest, null, 2);
            } else {
                elements.manifestSection.hidden = true;
            }
            
            // Scroll to results
            elements.resultsSection.scrollIntoView({ behavior: 'smooth', block: 'start' });
        },
        
        downloadFile(filename) {
            const link = document.createElement('a');
            link.href = state.results.downloadPath + '/' + filename;
            link.download = filename;
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        },
        
        downloadAll() {
            if (state.results && state.results.zipFile) {
                const link = document.createElement('a');
                link.href = state.results.zipFile;
                link.download = 'favicons.zip';
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
                Toast.show('Download iniciado!', 'success');
            }
        },
        
        reset() {
            elements.resultsSection.hidden = true;
            UploadHandler.removeFile();
            window.scrollTo({ top: 0, behavior: 'smooth' });
        },
        
        async copyToClipboard(text) {
            try {
                await navigator.clipboard.writeText(text);
                Toast.show('Código copiado!', 'success');
            } catch (err) {
                // Fallback
                const textarea = document.createElement('textarea');
                textarea.value = text;
                document.body.appendChild(textarea);
                textarea.select();
                document.execCommand('copy');
                document.body.removeChild(textarea);
                Toast.show('Código copiado!', 'success');
            }
        }
    };

    // ===================================
    // Toast Notifications
    // ===================================
    const Toast = {
        timeout: null,
        
        show(message, type = 'success') {
            clearTimeout(this.timeout);
            
            elements.toast.className = 'toast ' + type;
            elements.toast.querySelector('.toast-message').textContent = message;
            
            // Icon
            const iconContainer = elements.toast.querySelector('.toast-icon');
            if (type === 'success') {
                iconContainer.innerHTML = `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16"><path d="M20 6L9 17l-5-5"/></svg>`;
            } else {
                iconContainer.innerHTML = `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16"><path d="M18 6L6 18M6 6l12 12"/></svg>`;
            }
            
            elements.toast.classList.add('show');
            
            this.timeout = setTimeout(() => {
                elements.toast.classList.remove('show');
            }, 4000);
        }
    };

    // ===================================
    // Initialize Application
    // ===================================
    function init() {
        ThemeManager.init();
        SettingsManager.load();
        SettingsManager.apply();
        UploadHandler.init();
        OptionsHandler.init();
        PreviewManager.init();
        Generator.init();
        
        console.log('🎨 FaviconForge Pro initialized successfully!');
    }

    // Start when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }

})();
