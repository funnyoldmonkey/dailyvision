/**
 * Daily Vision - App Logic
 */

const App = {
    elements: {
        viewCamera: document.getElementById('view-camera'),
        viewLoading: document.getElementById('view-loading'),
        viewReflection: document.getElementById('view-reflection'),
        video: document.getElementById('camera-stream'),
        btnCapture: document.getElementById('btn-capture'),
        btnRetake: document.getElementById('btn-retake'),
        btnReimagine: document.getElementById('btn-reimagine'),
        btnSave: document.getElementById('btn-save'),
        btnShare: document.getElementById('btn-share'),
        canvas: document.getElementById('reflection-canvas'),
        verseText: document.getElementById('reflection-verse'),
        reference: document.getElementById('reflection-reference'),
        devotion: document.getElementById('reflection-devotion'),
    },

    state: {
        stream: null,
        capturedImage: null,
        aiResult: null,
        isReimagining: false
    },

    async init() {
        this.bindEvents();
        await this.startCamera();
    },

    bindEvents() {
        this.elements.btnCapture.addEventListener('click', () => this.capturePhoto());
        this.elements.btnRetake.addEventListener('click', () => this.retake());
        this.elements.btnReimagine.addEventListener('click', () => this.reimagine());
        this.elements.btnSave.addEventListener('click', () => this.saveImage());
        this.elements.btnShare.addEventListener('click', () => this.shareImage());
    },

    async startCamera() {
        try {
            this.state.stream = await navigator.mediaDevices.getUserMedia({
                video: { facingMode: 'environment' },
                audio: false
            });
            this.elements.video.srcObject = this.state.stream;
        } catch (err) {
            console.error("Camera error:", err);
            alert("Please allow camera access to use Daily Vision.");
        }
    },

    stopCamera() {
        if (this.state.stream) {
            this.state.stream.getTracks().forEach(track => track.stop());
        }
    },

    showView(viewId) {
        ['viewCamera', 'viewLoading', 'viewReflection'].forEach(v => {
            this.elements[v].classList.add('hidden');
        });
        this.elements[viewId].classList.remove('hidden');
        this.elements[viewId].classList.add('flex');
    },

    async capturePhoto() {
        if (this.elements.btnCapture.disabled) return;
        this.elements.btnCapture.disabled = true;
        this.elements.btnCapture.style.opacity = '0.5';

        const canvas = document.createElement('canvas');
        canvas.width = this.elements.video.videoWidth;
        canvas.height = this.elements.video.videoHeight;
        const ctx = canvas.getContext('2d');
        ctx.drawImage(this.elements.video, 0, 0);
        
        this.state.capturedImage = canvas.toDataURL('image/jpeg', 0.8);
        this.showView('viewLoading');
        await this.processImage();
        
        this.elements.btnCapture.disabled = false;
        this.elements.btnCapture.style.opacity = '1';
    },

    async processImage(isReimagine = false) {
        this.state.isReimagining = isReimagine;
        try {
            const response = await fetch(`${APP_URL}/api/analyze`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    image: this.state.capturedImage,
                    reimagine: isReimagine
                })
            });

            const result = await response.json();
            this.state.aiResult = result; // Store first so it's available for debugging

            if (!result) throw new Error("The spiritual lens returned an empty reflection.");
            if (result.error) throw new Error(result.error);

            await this.generateCanvas();
            this.updateUI();
            this.showView('viewReflection');
        } catch (err) {
            console.error("AI Error:", err);
            
            // Clean up any old debug box
            const oldBox = document.getElementById('debug-error');
            if (oldBox) oldBox.remove();

            alert("Something went wrong with the spiritual lens. Please try again.");
            this.retake();
        }
    },

    async generateCanvas() {
        const { aiResult, capturedImage } = this.state;
        const canvas = this.elements.canvas;
        const ctx = canvas.getContext('2d');

        // Load image
        const img = new Image();
        img.src = capturedImage;
        await new Promise(r => img.onload = r);

        // Set canvas dimensions to match image
        canvas.width = img.width;
        canvas.height = img.height;

        // 1. Draw photo
        ctx.drawImage(img, 0, 0);

        // 2. Apply vibe color tint
        if (aiResult.vibeColor) {
            ctx.save();
            ctx.globalCompositeOperation = 'overlay';
            ctx.fillStyle = aiResult.vibeColor;
            ctx.globalAlpha = 0.3;
            ctx.fillRect(0, 0, canvas.width, canvas.height);
            ctx.restore();
        }

        // 3. Add gradient based on text position
        const gradient = ctx.createLinearGradient(0, 0, 0, canvas.height);
        if (aiResult.textPosition === 'top') {
            gradient.addColorStop(0, 'rgba(0,0,0,0.7)');
            gradient.addColorStop(0.4, 'rgba(0,0,0,0)');
        } else if (aiResult.textPosition === 'bottom') {
            gradient.addColorStop(0.5, 'rgba(0,0,0,0)');
            gradient.addColorStop(1, 'rgba(0,0,0,0.85)');
        } else {
            gradient.addColorStop(0.2, 'rgba(0,0,0,0)');
            gradient.addColorStop(0.5, 'rgba(0,0,0,0.7)');
            gradient.addColorStop(0.8, 'rgba(0,0,0,0)');
        }
        ctx.fillStyle = gradient;
        ctx.fillRect(0, 0, canvas.width, canvas.height);

        // 4. Load dynamic font
        if (aiResult.uniqueFont) {
            const fontName = aiResult.uniqueFont.replace(/\s+/g, '+');
            const link = document.createElement('link');
            link.rel = 'stylesheet';
            link.href = `https://fonts.googleapis.com/css2?family=${fontName}:ital,wght@0,400;0,700;1,400&display=swap`;
            document.head.appendChild(link);
            // Wait for font to load
            await document.fonts.load(`1em "${aiResult.uniqueFont}"`);
        }

        // 5. Draw text
        ctx.fillStyle = 'white';
        ctx.textAlign = 'center';
        ctx.shadowColor = 'rgba(0,0,0,0.8)';
        ctx.shadowBlur = 15;

        const padding = 80;
        const centerX = canvas.width / 2;
        const maxContentHeight = canvas.height * 0.7; // Max 70% of image height
        
        let verseFontSize = 72;
        let summaryFontSize = 36;
        let summaryLineHeight = 45;
        let verseLineHeight = 85;
        let gap = 60;
        let totalHeight = 0;
        let summaryLines = [];
        let verseLines = [];

        // --- Auto-Scaling Loop ---
        while (verseFontSize > 30) {
            ctx.font = `italic ${summaryFontSize}px "${aiResult.uniqueFont || 'Georgia'}"`;
            summaryLines = this.getWrapTextLines(ctx, aiResult.devotionalSummary, canvas.width - padding * 2);
            
            ctx.font = `bold ${verseFontSize}px "${aiResult.uniqueFont || 'Georgia'}"`;
            verseLines = this.getWrapTextLines(ctx, `"${aiResult.verseText}"`, canvas.width - padding * 2);
            
            totalHeight = (summaryLines.length * summaryLineHeight) + gap + (verseLines.length * verseLineHeight) + 80;

            if (totalHeight <= maxContentHeight) break;

            // Shrink and try again
            verseFontSize -= 4;
            summaryFontSize -= 2;
            summaryLineHeight -= 3;
            verseLineHeight -= 5;
            gap -= 2;
        }

        // Adjust spacing for the final layout
        gap = 20; 
        totalHeight = (summaryLines.length * summaryLineHeight) + gap + (verseLines.length * verseLineHeight) + 40;

        // Forced Bottom Alignment (Low as possible)
        let startY = canvas.height - totalHeight - 30;

        // Draw Summary
        ctx.font = `italic ${summaryFontSize}px "${aiResult.uniqueFont || 'Georgia'}"`;
        this.wrapText(ctx, aiResult.devotionalSummary, centerX, startY, canvas.width - padding * 2, summaryLineHeight);
        
        startY += (summaryLines.length * summaryLineHeight) + gap;
        
        // Draw Verse
        ctx.font = `bold ${verseFontSize}px "${aiResult.uniqueFont || 'Georgia'}"`;
        this.wrapText(ctx, `"${aiResult.verseText}"`, centerX, startY, canvas.width - padding * 2, verseLineHeight);

        // Draw Reference
        startY += (verseLines.length * verseLineHeight) + 20;
        ctx.font = `${summaryFontSize + 4}px "${aiResult.uniqueFont || 'Georgia'}"`;
        ctx.fillText(`— ${aiResult.verseReference}`, centerX, startY);
    },

    wrapText(ctx, text, x, y, maxWidth, lineHeight) {
        const words = text.split(' ');
        let line = '';
        for (let n = 0; n < words.length; n++) {
            const testLine = line + words[n] + ' ';
            const metrics = ctx.measureText(testLine);
            const testWidth = metrics.width;
            if (testWidth > maxWidth && n > 0) {
                ctx.fillText(line, x, y);
                line = words[n] + ' ';
                y += lineHeight;
            } else {
                line = testLine;
            }
        }
        ctx.fillText(line, x, y);
    },

    getWrapTextLines(ctx, text, maxWidth) {
        const words = text.split(' ');
        let line = '';
        let lines = [];
        for (let n = 0; n < words.length; n++) {
            const testLine = line + words[n] + ' ';
            const metrics = ctx.measureText(testLine);
            const testWidth = metrics.width;
            if (testWidth > maxWidth && n > 0) {
                lines.push(line);
                line = words[n] + ' ';
            } else {
                line = testLine;
            }
        }
        lines.push(line);
        return lines;
    },

    updateUI() {
        const { aiResult } = this.state;
        this.elements.verseText.innerText = aiResult.verseText;
        this.elements.reference.innerText = aiResult.verseReference;
        this.elements.devotion.innerText = aiResult.fullDevotion;
    },

    retake() {
        this.state.capturedImage = null;
        this.state.aiResult = null;
        this.showView('viewCamera');
        this.startCamera();
    },

    async reimagine() {
        this.showView('viewLoading');
        const h2 = this.elements.viewLoading.querySelector('h2');
        h2.innerText = "Daily Vision is reimagining...";
        await this.processImage(true);
    },

    saveImage() {
        const link = document.createElement('a');
        link.download = `daily-vision-${Date.now()}.jpg`;
        link.href = this.elements.canvas.toDataURL('image/jpeg', 0.9);
        link.click();
    },

    async shareImage() {
        if (!navigator.share) {
            alert("Sharing is not supported on this browser.");
            return;
        }

        try {
            const { aiResult } = this.state;
            const dataUrl = this.elements.canvas.toDataURL('image/jpeg', 0.9);
            const res = await fetch(dataUrl);
            const blob = await res.blob();
            const file = new File([blob], 'daily-vision.jpg', { type: 'image/jpeg' });

            const shareData = {
                title: 'Daily Vision | A Spiritual Reflection',
                text: `✨ Daily Vision Reflection\n\n"${aiResult.verseText}"\n— ${aiResult.verseReference}\n\n${aiResult.fullDevotion}\n\nShared via Daily Vision App`
            };

            if (navigator.canShare && navigator.canShare({ files: [file] })) {
                shareData.files = [file];
            }

            await navigator.share(shareData);
        } catch (err) {
            console.error("Share error:", err);
        }
    }
};

App.init();
