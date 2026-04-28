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
        isReimagining: false,
        isProcessing: false,
        isSharing: false
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
        if (this.state.isProcessing) return;
        this.state.isProcessing = true;
        this.elements.btnCapture.disabled = true;
        this.elements.btnCapture.style.opacity = '0.5';

        const canvas = document.createElement('canvas');
        canvas.width = this.elements.video.videoWidth;
        canvas.height = this.elements.video.videoHeight;
        const ctx = canvas.getContext('2d');
        ctx.drawImage(this.elements.video, 0, 0);
        
        this.state.capturedImage = canvas.toDataURL('image/jpeg', 0.8);
        this.showView('viewLoading');
        
        try {
            await this.processImage();
        } finally {
            this.state.isProcessing = false;
            this.elements.btnCapture.disabled = false;
            this.elements.btnCapture.style.opacity = '1';
        }
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
        if (this.state.isProcessing) return;
        this.state.isProcessing = true;

        this.showView('viewLoading');
        const h2 = this.elements.viewLoading.querySelector('h2');
        if (h2) h2.innerText = "Daily Vision is reimagining...";
        
        try {
            await this.processImage(true);
        } finally {
            this.state.isProcessing = false;
        }
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

        // Prevent double-taps
        if (this.state.isSharing) return;
        this.state.isSharing = true;

        const btnShare = this.elements.btnShare;
        const originalHtml = btnShare.innerHTML;
        btnShare.disabled = true;
        btnShare.innerHTML = `<svg class="animate-spin h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>`;

        try {
            const { aiResult } = this.state;
            const dataUrl = this.elements.canvas.toDataURL('image/jpeg', 0.9);
            
            // 1. Prepare file synchronously to keep user gesture alive
            const arr = dataUrl.split(','), mime = arr[0].match(/:(.*?);/)[1];
            const bstr = atob(arr[1]), u8arr = new Uint8Array(bstr.length);
            let n = bstr.length;
            while(n--) u8arr[n] = bstr.charCodeAt(n);
            const file = new File([u8arr], 'daily-vision.jpg', { type: mime });

            // 2. Save to server
            const saveResponse = await fetch(`${APP_URL}/api/visions`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    image: dataUrl,
                    ...aiResult
                })
            });

            const saved = await saveResponse.json();
            const shareUrl = saved.url || APP_URL;

            // 3. Share
            const shareData = {
                title: 'Daily Vision | A Spiritual Reflection',
                text: `✨ Daily Vision Reflection\n\n"${aiResult.verseText}"\n— ${aiResult.verseReference}\n\nRead more:`,
                url: shareUrl
            };

            // CRITICAL: On most mobile browsers, sharing a 'file' overrides the URL/Text.
            // Since we have a unique link with a preview image (SEO), sharing the link is MUCH better.
            if (saved.url) {
                // If the server saved it successfully, share the link for best social previews
                await navigator.share(shareData);
            } else if (navigator.canShare && navigator.canShare({ files: [file] })) {
                // Fallback to file only if link saving failed
                await navigator.share({
                    files: [file],
                    title: shareData.title,
                    text: shareData.text
                });
            }
        } catch (err) {
            console.error("Share error:", err);
            // If it's a 'cancel' by the user, don't alert
            if (err.name !== 'AbortError') {
                alert("Could not share. Please try again.");
            }
        } finally {
            this.state.isSharing = false;
            btnShare.disabled = false;
            btnShare.innerHTML = originalHtml;
        }
    }
};
