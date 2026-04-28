<div id="view-camera" class="relative flex-1 flex flex-col overflow-hidden">
    <video id="camera-stream" class="absolute inset-0 w-full h-full object-cover" autoplay playsinline muted></video>
    
    <header class="absolute top-8 left-8">
        <h1 class="text-sage font-serif font-bold text-[20px] tracking-[-0.02em]">Daily Vision</h1>
    </header>

    <div class="absolute bottom-12 inset-x-0 flex justify-center">
        <button id="btn-capture" class="w-16 h-16 rounded-full border-4 border-white bg-transparent shadow-lg active:scale-95 transition-transform">
            <div class="w-full h-full rounded-full border-2 border-transparent bg-white/20"></div>
        </button>
    </div>
</div>

<div id="view-loading" class="hidden flex-1 flex flex-col items-center justify-center bg-bg p-8 text-center">
    <div class="loader mb-8"></div>
    <h2 class="text-sage font-serif font-bold text-[20px] tracking-widest uppercase text-center">Daily Vision is reflecting...</h2>
</div>

<div id="view-reflection" class="hidden flex-1 flex flex-col overflow-y-auto bg-bg">
    <!-- Top Half: Canvas Preview -->
    <div class="p-4 pt-8">
        <div class="reflection-container rounded-[12px] overflow-hidden">
            <canvas id="reflection-canvas" class="w-full h-auto max-h-[50vh] object-contain"></canvas>
        </div>
    </div>

    <!-- Bottom Half: Deeper Dive -->
    <div class="flex-1 flex flex-col p-8 pb-32">
        <span class="text-[11px] uppercase tracking-[0.15em] text-sage font-bold mb-4">Daily Reflection</span>
        <h2 id="reflection-verse" class="font-serif text-[36px] leading-[1.3] text-ink mb-2"></h2>
        <p id="reflection-reference" class="font-serif italic text-[18px] text-sage mb-8"></p>
        
        <div class="border-left-2 border-beige pl-6 border-l-2">
            <p id="reflection-devotion" class="font-sans text-[17px] leading-[1.7] text-[#555]"></p>
        </div>
    </div>

    <!-- Button Bar -->
    <div class="fixed bottom-0 inset-x-0 p-4 bg-bg/80 backdrop-blur-md flex items-center gap-4 safe-area-inset-bottom">
        <button id="btn-retake" class="w-[56px] h-[56px] rounded-full bg-beige text-ink flex items-center justify-center hover:bg-[#e6e2d6] transition-colors shadow-sm">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14.5 4h-5L7 7H4a2 2 0 0 0-2 2v9a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2h-3l-2.5-3z"/><circle cx="12" cy="13" r="3"/></svg>
        </button>
        
        <button id="btn-reimagine" class="w-[56px] h-[56px] rounded-full bg-beige text-ink flex items-center justify-center hover:bg-[#e6e2d6] transition-colors shadow-sm">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m12 3-1.912 5.813a2 2 0 0 1-1.275 1.275L3 12l5.813 1.912a2 2 0 0 1 1.275 1.275L12 21l1.912-5.813a2 2 0 0 1 1.275-1.275L21 12l-5.813-1.912a2 2 0 0 1-1.275-1.275L12 3Z"/><path d="M5 3v4"/><path d="M19 17v4"/><path d="M3 5h4"/><path d="M17 19h4"/></svg>
        </button>

        <button id="btn-save" class="flex-1 h-[56px] rounded-full bg-sage text-white uppercase tracking-[0.05em] font-bold shadow-lg shadow-sage/20 active:scale-95 transition-transform flex items-center justify-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" x2="12" y1="15" y2="3"/></svg>
            Save
        </button>

        <button id="btn-share" class="w-[56px] h-[56px] rounded-full bg-beige text-ink flex items-center justify-center hover:bg-[#e6e2d6] transition-colors shadow-sm">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="18" cy="5" r="3"/><circle cx="6" cy="12" r="3"/><circle cx="18" cy="19" r="3"/><line x1="8.59" x2="15.42" y1="13.51" y2="17.49"/><line x1="15.41" x2="8.59" y1="6.51" y2="10.49"/></svg>
        </button>
    </div>

    <footer class="mt-auto p-4 text-center">
        <p class="text-[10px] uppercase tracking-[0.1em] text-[#A09E98]">Built by Jall Fiel</p>
    </footer>
</div>
