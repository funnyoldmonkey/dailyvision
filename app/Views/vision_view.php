<div class="flex-1 flex flex-col overflow-y-auto bg-bg">
    <!-- Top Half: Canvas Preview -->
    <div class="p-4 pt-8">
        <div class="reflection-container rounded-[12px] overflow-hidden shadow-2xl">
            <img src="<?php echo base_url($vision['image_path']); ?>" alt="Spiritual Vision" class="w-full h-auto object-contain">
        </div>
    </div>

    <!-- Bottom Half: Deeper Dive -->
    <div class="flex-1 flex flex-col p-8 pb-56">
        <span class="text-[11px] uppercase tracking-[0.15em] text-sage font-bold mb-4">A Shared Vision</span>
        <h2 class="font-serif text-[36px] leading-[1.3] text-ink mb-2"><?php echo htmlspecialchars($vision['verse_text']); ?></h2>
        <p class="font-serif italic text-[18px] text-sage mb-8"><?php echo htmlspecialchars($vision['verse_reference']); ?></p>
        
        <div class="border-left-2 border-beige pl-6 border-l-2">
            <p class="font-sans text-[17px] leading-[1.7] text-[#555]"><?php echo nl2br(htmlspecialchars($vision['full_devotion'])); ?></p>
        </div>

        <div class="mt-12 p-6 bg-beige/30 rounded-xl border border-beige/50 text-center">
            <p class="font-serif italic text-sage mb-4">"This vision was captured through the Spiritual Lens."</p>
            <a href="<?php echo base_url(); ?>" class="inline-block px-8 py-3 bg-sage text-white rounded-full uppercase tracking-widest font-bold text-[12px] shadow-lg hover:scale-105 transition-transform">
                Capture Your Own
            </a>
        </div>
    </div>

    <!-- Fixed Action Bar & Footer -->
    <div class="fixed bottom-0 inset-x-0 bg-bg/80 backdrop-blur-md safe-area-inset-bottom">
        <div class="p-4 flex items-center gap-4">
            <a href="<?php echo base_url(); ?>" class="w-[56px] h-[56px] rounded-full bg-beige text-ink flex items-center justify-center hover:bg-[#e6e2d6] transition-colors shadow-sm">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14.5 4h-5L7 7H4a2 2 0 0 0-2 2v9a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2h-3l-2.5-3z"/><circle cx="12" cy="13" r="3"/></svg>
            </a>
            
            <a href="<?php echo base_url('gallery'); ?>" class="w-[56px] h-[56px] rounded-full bg-beige text-ink flex items-center justify-center hover:bg-[#e6e2d6] transition-colors shadow-sm">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="3" rx="2" ry="2"/><circle cx="9" cy="9" r="2"/><path d="m21 15-3.086-3.086a2 2 0 0 0-2.828 0L6 21"/></svg>
            </a>

            <button onclick="window.location.href='<?php echo base_url(); ?>'" class="flex-1 h-[56px] rounded-full bg-sage text-white uppercase tracking-[0.05em] font-bold shadow-lg shadow-sage/20 active:scale-95 transition-transform flex items-center justify-center gap-2">
                Launch App
            </button>
        </div>

        <footer class="pb-4 text-center">
            <p class="text-[10px] uppercase tracking-[0.1em] text-[#A09E98]">Built by Jall Fiel</p>
        </footer>
    </div>
</div>
