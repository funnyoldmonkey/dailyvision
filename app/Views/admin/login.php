<div class="min-h-screen bg-gray-50 flex flex-col items-center justify-center p-6">
    <div class="max-w-md w-full bg-white rounded-2xl shadow-xl p-10 border border-gray-100">
        <div class="text-center mb-8">
            <div class="w-16 h-16 bg-gray-900 rounded-2xl mx-auto mb-4 flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="text-white w-8 h-8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="11" x="3" y="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
            </div>
            <h1 class="text-2xl font-bold text-gray-900">Boss Access</h1>
            <p class="text-gray-500 mt-2 text-sm uppercase tracking-widest font-semibold">Security Clearance Required</p>
        </div>

        <?php if (isset($error)): ?>
            <div class="mb-6 p-4 bg-red-50 border border-red-100 text-red-600 rounded-xl text-sm font-medium">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <form action="<?php echo base_url('boss/login'); ?>" method="POST" class="space-y-6">
            <div>
                <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-3">
                    What's your nickname when you were a kid?
                </label>
                <input 
                    type="password" 
                    name="password" 
                    required 
                    autofocus
                    placeholder="••••••••"
                    class="w-full px-5 py-4 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-gray-900 transition-all text-gray-900"
                >
            </div>

            <button type="submit" class="w-full py-4 bg-gray-900 text-white rounded-xl font-bold hover:bg-black transition-colors shadow-lg shadow-gray-200">
                Verify Identity
            </button>
        </form>

        <div class="mt-10 text-center">
            <a href="<?php echo base_url(); ?>" class="text-gray-400 text-sm hover:text-gray-600 transition-colors">
                &larr; Return to Application
            </a>
        </div>
    </div>
</div>
