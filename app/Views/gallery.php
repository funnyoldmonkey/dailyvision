<div class="flex-1 flex flex-col overflow-y-auto bg-bg pb-32">
    <header class="p-8 pb-4">
        <h1 class="text-sage font-serif font-bold text-[32px] tracking-tight">Community Visions</h1>
        <p class="text-ink/60 font-sans text-[14px] uppercase tracking-widest">Shared reflections from the community</p>
    </header>

    <div class="px-4 grid grid-cols-2 gap-4">
        <?php foreach ($visions as $vision): ?>
            <a href="<?php echo base_url('v/' . $vision['id']); ?>" class="relative aspect-[3/4] rounded-xl overflow-hidden shadow-lg active:scale-95 transition-transform group">
                <img src="<?php echo base_url($vision['image_path']); ?>" alt="Vision" class="absolute inset-0 w-full h-full object-cover">
                <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent opacity-100 transition-opacity"></div>
                
                <div class="absolute bottom-0 left-0 p-3 text-white">
                    <p class="text-[10px] uppercase font-bold tracking-widest mb-1 line-clamp-2 italic text-white/90">
                        "<?php echo htmlspecialchars($vision['verse_text']); ?>"
                    </p>
                    <span class="text-[9px] text-white/50"><?php echo time_ago($vision['created_at']); ?></span>
                </div>
            </a>
        <?php endforeach; ?>

        <?php if (empty($visions)): ?>
            <div class="col-span-2 py-20 text-center">
                <p class="text-sage font-serif italic text-lg">No visions have been shared yet.</p>
                <p class="text-ink/40 text-sm mt-2">Be the first to capture one!</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Fixed Navigation Bar -->
<div class="fixed bottom-0 inset-x-0 bg-bg/80 backdrop-blur-md safe-area-inset-bottom">
    <div class="p-4 flex items-center justify-center">
        <a href="<?php echo base_url(); ?>" class="px-12 h-[56px] rounded-full bg-sage text-white uppercase tracking-[0.1em] font-bold shadow-lg shadow-sage/20 active:scale-95 transition-transform flex items-center justify-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14.5 4h-5L7 7H4a2 2 0 0 0-2 2v9a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2h-3l-2.5-3z"/><circle cx="12" cy="13" r="3"/></svg>
            Capture Vision
        </a>
    </div>
    <footer class="pb-4 text-center">
        <p class="text-[10px] uppercase tracking-[0.1em] text-[#A09E98]">Built by Jall Fiel</p>
    </footer>
</div>
