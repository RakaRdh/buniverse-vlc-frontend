<?php 
$isLoggedIn = session()->get('is_logged_in') || session()->get('auth_user') || session()->get('member_id'); 
$userName = session()->get('member_name') ?? (session()->get('auth_user')['name'] ?? 'Peserta');
?>
<header class="w-full border-b border-gray-100 bg-white sticky top-0 z-50 shadow-xs">
    <div class="max-w-[1440px] mx-auto flex items-center justify-between px-4 lg:px-10 h-[72px]">
        <!-- Brand Logo -->
        <a href="/" class="flex items-center gap-2 shrink-0">
            <img src="/img/logo-vocational.webp" alt="Logo Vocational Learning Center" class="h-[42px] object-contain object-left" />
        </a>

        <!-- Navigation Menu -->
        <nav class="hidden lg:flex items-center gap-8 text-sm font-semibold text-gray-700">
            <a href="/#home" class="nav-smooth-link hover:text-brand transition-colors">Home</a>
            <a href="/#about" class="nav-smooth-link hover:text-brand transition-colors">About</a>
            <a href="/#courses" class="nav-smooth-link hover:text-brand transition-colors">Product</a>
            <a href="/#faq" class="nav-smooth-link hover:text-brand transition-colors">FaQ</a>
        </nav>

        <!-- Right User Actions -->
        <div class="flex items-center gap-4 text-sm font-medium">
            <?php if ($isLoggedIn): ?>
                <!-- State Setelah Login: Icon Orang + Nama | Icon Logout -->
                <div class="flex items-center gap-2.5">
                    <a href="/profile" class="flex items-center gap-1.5 text-gray-800 hover:text-brand font-semibold transition-colors" title="Buka Profil Saya">
                        <svg class="size-4 text-brand shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        <span class="max-w-[140px] sm:max-w-[200px] truncate"><?= esc($userName) ?></span>
                    </a>

                    <span class="text-gray-300">|</span>

                    <a href="/logout" onclick="return confirm('Apakah Anda yakin ingin keluar?');" 
                       class="text-gray-400 hover:text-brand transition-colors p-1" title="Keluar / Logout">
                        <svg class="size-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                    </a>
                </div>
            <?php else: ?>
                <!-- State Belum Login -->
                <div class="flex items-center gap-2.5">
                    <a href="/login" class="text-gray-800 hover:text-brand transition-colors font-semibold">Login</a>
                    <span class="text-gray-300">|</span>
                    <a href="/register" class="text-gray-800 hover:text-brand transition-colors font-semibold">Sign In</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</header>

<script>
    // Smooth scrolling handler for nav links
    document.addEventListener("DOMContentLoaded", function() {
        const links = document.querySelectorAll('.nav-smooth-link');
        links.forEach(link => {
            link.addEventListener('click', function(e) {
                const href = this.getAttribute('href');
                if (href.startsWith('/#') || href.startsWith('#')) {
                    const targetId = href.replace(/^\/#/, '').replace(/^#/, '');
                    const targetElem = document.getElementById(targetId);
                    
                    // If we are already on homepage, scroll smoothly
                    if (targetElem && (window.location.pathname === '/' || window.location.pathname === '')) {
                        e.preventDefault();
                        const navHeight = 72;
                        const targetPosition = targetElem.getBoundingClientRect().top + window.pageYOffset - navHeight;
                        window.scrollTo({
                            top: targetPosition,
                            behavior: 'smooth'
                        });
                        history.pushState(null, null, '#' + targetId);
                    }
                }
            });
        });
    });
</script>