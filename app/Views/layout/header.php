<?php 
$isLoggedIn = session()->get('is_logged_in') || session()->get('auth_user'); 
$userName = session()->get('member_name') ?? (session()->get('auth_user')['name'] ?? 'Peserta');
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
                <span class="hidden sm:inline text-gray-700 font-semibold">Hi, <?= esc($userName) ?></span>
                <a href="/logout" class="text-brand hover:text-brand-dark transition">Logout</a>
            <?php else: ?>
                <a href="/login" class="text-gray-800 hover:text-brand transition">Login</a>
                <span class="text-gray-300">|</span>
                <a href="/register" class="text-gray-800 hover:text-brand transition">Sign In</a>
            <?php endif; ?>
        </div>
    </div>
</header>