<div class="min-h-screen bg-gray-50 flex flex-col">
    <!-- Header -->
    <header class="bg-white border-b border-gray-200 px-8 py-4 flex items-center justify-between sticky top-0 z-30">
        <div class="flex items-center gap-4">
            <div class="w-10 h-10 bg-gray-900 rounded-lg flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="text-white w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/></svg>
            </div>
            <div>
                <h1 class="text-lg font-bold text-gray-900">Boss Panel</h1>
                <p class="text-[10px] uppercase tracking-widest text-gray-400 font-bold">Admin Dashboard</p>
            </div>
        </div>

        <div class="flex items-center gap-4">
            <a href="<?php echo base_url(); ?>" target="_blank" class="text-xs font-bold text-gray-500 hover:text-gray-900 transition-colors uppercase tracking-widest">View App</a>
            <form action="<?php echo base_url('boss/logout'); ?>" method="POST">
                <button type="submit" class="px-4 py-2 bg-gray-100 text-gray-600 rounded-lg text-xs font-bold hover:bg-red-50 hover:text-red-600 transition-all uppercase tracking-widest">Logout</button>
            </form>
        </div>
    </header>

    <main class="p-8 max-w-6xl mx-auto w-full space-y-8">
        
        <?php if (isset($_GET['success'])): ?>
            <div class="p-4 bg-green-50 border border-green-100 text-green-700 rounded-xl text-sm font-medium animate-pulse">
                Action completed successfully.
            </div>
        <?php endif; ?>

        <!-- Settings Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <section class="bg-white p-8 rounded-2xl shadow-sm border border-gray-200">
                <h2 class="text-sm font-bold text-gray-900 uppercase tracking-widest mb-6 border-b pb-4">Engine Settings</h2>
                <form action="<?php echo base_url('boss/settings'); ?>" method="POST" class="space-y-6">
                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">Gemini API Key</label>
                        <input type="text" name="gemini_api_key" value="<?php echo htmlspecialchars($settings['gemini_api_key'] ?? ''); ?>" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-gray-900 outline-none transition-all">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">AI Model Name</label>
                        <input type="text" name="ai_model_name" value="<?php echo htmlspecialchars($settings['ai_model_name'] ?? ''); ?>" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-gray-900 outline-none transition-all">
                    </div>
                    <button type="submit" class="w-full py-3 bg-gray-900 text-white rounded-lg text-xs font-bold hover:bg-black transition-colors uppercase tracking-widest">Save Config</button>
                </form>
            </section>

            <section class="bg-white p-8 rounded-2xl shadow-sm border border-gray-200 flex flex-col justify-center text-center">
                <p class="text-gray-400 text-sm italic font-serif">"You are the master of the spiritual lens."</p>
                <div class="mt-4 text-xs text-gray-300 font-mono">
                    System Time: <?php echo date('Y-m-d H:i:s'); ?>
                </div>
            </section>
        </div>

        <!-- Vision Management -->
        <section class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="p-8 border-b border-gray-100 flex items-center justify-between">
                <div>
                    <h2 class="text-sm font-bold text-gray-900 uppercase tracking-widest">Vision Management</h2>
                    <p class="text-xs text-gray-400 mt-1"><?php echo count($visions); ?> total items in gallery</p>
                </div>
                
                <div class="flex items-center gap-3">
                    <button onclick="selectAll()" class="text-[10px] font-bold text-gray-500 hover:text-gray-900 uppercase tracking-widest">Select All</button>
                    <span class="text-gray-200">|</span>
                    <button form="bulk-delete-form" name="delete_all" onclick="return confirm('Are you absolutely sure? This will wipe the entire gallery!')" class="text-[10px] font-bold text-red-400 hover:text-red-600 uppercase tracking-widest">Wipe All</button>
                </div>
            </div>

            <form id="bulk-delete-form" action="<?php echo base_url('boss/visions/delete'); ?>" method="POST">
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead class="bg-gray-50 border-b border-gray-100">
                            <tr>
                                <th class="p-4 w-12"><input type="checkbox" id="master-checkbox" onclick="toggleAll()"></th>
                                <th class="p-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Preview</th>
                                <th class="p-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Verse</th>
                                <th class="p-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Date</th>
                                <th class="p-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            <?php foreach ($visions as $v): ?>
                                <tr class="hover:bg-gray-50/50 transition-colors">
                                    <td class="p-4">
                                        <input type="checkbox" name="vision_ids[]" value="<?php echo $v['id']; ?>" class="vision-checkbox">
                                    </td>
                                    <td class="p-4">
                                        <div class="w-12 h-16 bg-gray-100 rounded overflow-hidden">
                                            <img src="<?php echo base_url($v['image_path']); ?>" class="w-full h-full object-cover opacity-80" onerror="this.src='https://placehold.co/48x64?text=Missing'">
                                        </div>
                                    </td>
                                    <td class="p-4">
                                        <div class="max-w-xs">
                                            <p class="text-sm font-bold text-gray-800 line-clamp-1"><?php echo htmlspecialchars($v['verse_text']); ?></p>
                                            <p class="text-[10px] text-gray-400 uppercase tracking-widest font-semibold"><?php echo htmlspecialchars($v['verse_reference']); ?></p>
                                        </div>
                                    </td>
                                    <td class="p-4 text-xs text-gray-500 whitespace-nowrap">
                                        <?php echo time_ago($v['created_at']); ?>
                                    </td>
                                    <td class="p-4 text-right">
                                        <button type="submit" name="vision_ids[]" value="<?php echo $v['id']; ?>" class="p-2 text-gray-400 hover:text-red-600 transition-colors" onclick="return confirm('Delete this vision?')">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/><line x1="10" x2="10" y1="11" y2="17"/><line x1="14" x2="14" y1="11" y2="17"/></svg>
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>

                            <?php if (empty($visions)): ?>
                                <tr>
                                    <td colspan="5" class="p-20 text-center text-gray-400 italic text-sm">
                                        Gallery is empty.
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <div class="p-8 bg-gray-50 border-t border-gray-100 flex items-center justify-between">
                    <p class="text-xs text-gray-400">Select items to perform bulk actions.</p>
                    <button type="submit" class="px-6 py-3 bg-red-600 text-white rounded-lg text-xs font-bold hover:bg-red-700 transition-all uppercase tracking-widest shadow-lg shadow-red-100">Delete Selected</button>
                </div>
            </form>
        </section>
    </main>
</div>

<script>
    function toggleAll() {
        const master = document.getElementById('master-checkbox');
        const checkboxes = document.querySelectorAll('.vision-checkbox');
        checkboxes.forEach(cb => cb.checked = master.checked);
    }

    function selectAll() {
        const master = document.getElementById('master-checkbox');
        master.checked = true;
        toggleAll();
    }
</script>
