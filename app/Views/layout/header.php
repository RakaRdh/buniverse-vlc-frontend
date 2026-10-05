<?php 
$isLoggedIn = session()->get('is_logged_in') || session()->get('auth_user') || session()->get('member_id'); 
$userName = session()->get('member_name') ?? (session()->get('auth_user')['name'] ?? 'Peserta');
$userInitial = strtoupper(substr(trim($userName), 0, 1));
if (empty($userInitial)) $userInitial = 'P';
?>
<header class="w-full border-b border-gray-100 bg-white sticky top-0 z-50 shadow-sm">
    <div class="max-w-[1440px] mx-auto flex items-center justify-between px-4 lg:px-10 h-[72px]">
        <a href="/" class="flex items-center gap-2 shrink-0">
            <img src="/img/logo-vocational.webp" alt="Logo Vocational Learning Center" class="h-[42px] object-cover object-center" />
        </a>

        <nav class="hidden lg:flex items-center gap-8 text-sm font-medium text-gray-700">
            <a href="/" class="hover:text-brand transition">Home</a>
            <a href="/about_us" class="hover:text-brand transition">About</a>
            <a href="/#courses" class="hover:text-brand transition">Product</a>
            <a href="/#faq" class="hover:text-brand transition">FaQ</a>
        </nav>

        <div class="flex items-center gap-4 text-sm font-medium">
            <?php if ($isLoggedIn): ?>
                <!-- Profile Avatar Button -->
                <a href="/profile" 
                   class="flex items-center gap-2.5 px-3 py-1.5 rounded-full border border-gray-200 bg-gray-50 hover:bg-gray-100 hover:border-brand/40 transition-all text-gray-800 group"
                   title="Buka Profil & Status Kelas Saya">
                    <div class="size-8 rounded-full bg-brand text-white flex items-center justify-center font-bold text-xs shadow-xs group-hover:scale-105 transition-transform">
                        <?= esc($userInitial) ?>
                    </div>
                    <div class="hidden sm:flex flex-col text-left">
                        <span class="text-xs font-bold leading-tight group-hover:text-brand transition-colors"><?= esc($userName) ?></span>
                        <span class="text-[10px] text-gray-500 font-medium">Profil Saya</span>
                    </div>
                    <svg class="size-4 text-gray-400 group-hover:text-brand transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </a>
                <a href="/logout" onclick="return confirm('Apakah Anda yakin ingin keluar?');" class="text-xs text-gray-500 hover:text-brand transition font-medium" title="Logout">
                    Keluar
                </a>
            <?php else: ?>
                <a href="/login" class="text-gray-800 hover:text-brand transition">Login</a>
                <span class="text-gray-300">|</span>
                <a href="/register" class="text-gray-800 hover:text-brand transition">Sign In</a>
            <?php endif; ?>
        </div>
    </div>
</header>