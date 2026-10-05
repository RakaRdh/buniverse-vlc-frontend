<?php 
$isLoggedIn = session()->get('is_logged_in') || session()->get('auth_user') || session()->get('member_id'); 
$userName = session()->get('member_name') ?? (session()->get('auth_user')['name'] ?? 'Peserta');
?>
<header class="w-full border-b border-gray-100 bg-white sticky top-0 z-50 shadow-xs">
    <div class="max-w-[1440px] mx-auto flex items-center justify-between px-4 lg:px-10 h-[60px] sm:h-[68px] lg:h-[72px]">
        <div class="w-8 lg:hidden"></div>

        <a href="/" class="flex items-center justify-center lg:justify-start gap-2 shrink-0">
            <img src="/img/logo-vocational.webp" alt="Logo Vocational Learning Center" class="h-[26px] sm:h-[32px] lg:h-[42px] object-contain" />
        </a>

        <nav class="hidden lg:flex items-center gap-8 text-[15px] font-bold text-gray-800">
            <a href="/#home" class="nav-smooth-link font-bold text-gray-800 hover:text-brand transition-colors">Home</a>
            <a href="/#about" class="nav-smooth-link font-bold text-gray-800 hover:text-brand transition-colors">About</a>
            <a href="/#courses" class="nav-smooth-link font-bold text-gray-800 hover:text-brand transition-colors">Product</a>
            <a href="/#faq" class="nav-smooth-link font-bold text-gray-800 hover:text-brand transition-colors">FaQ</a>
        </nav>

        <div class="hidden lg:flex items-center gap-4 text-sm font-medium">
            <?php if ($isLoggedIn): ?>
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
                <div class="flex items-center gap-2.5">
                    <a href="/login" class="text-gray-800 hover:text-brand transition-colors font-semibold">Login</a>
                    <span class="text-gray-300">|</span>
                    <a href="/register" class="text-gray-800 hover:text-brand transition-colors font-semibold">Sign In</a>
                </div>
            <?php endif; ?>
        </div>

        <button type="button" onclick="toggleMobileFeMenu(true)" class="lg:hidden p-1.5 text-slate-800 hover:opacity-80 focus:outline-none cursor-pointer" aria-label="Toggle Menu">
            <img src="/img/hamburger.webp" alt="Menu" class="h-5 sm:h-6 w-auto object-contain">
        </button>
    </div>

    <div id="mobileFeBackdrop" onclick="toggleMobileFeMenu(false)" class="hidden fixed inset-0 bg-black/40 backdrop-blur-xs z-50 transition-opacity duration-300 opacity-0 pointer-events-none"></div>

    <div id="mobileFeMenu" class="fixed top-0 right-0 h-full w-[280px] max-w-[85vw] bg-white z-50 shadow-2xl flex flex-col justify-between p-6 transform translate-x-full transition-transform duration-300 ease-in-out">
        <div>
            <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-6">
                <span class="text-sm font-bold text-slate-800 tracking-wide uppercase">Menu</span>
                <button type="button" onclick="toggleMobileFeMenu(false)" class="p-1.5 text-slate-400 hover:text-slate-700 rounded-full hover:bg-slate-100 transition" aria-label="Close Menu">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <nav class="flex flex-col space-y-4 text-base font-bold text-slate-800">
                <a href="/#home" onclick="toggleMobileFeMenu(false)" class="nav-smooth-link py-1 hover:text-brand transition flex items-center justify-between">
                    <span>Home</span>
                    <svg class="w-4 h-4 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </a>
                <a href="/#about" onclick="toggleMobileFeMenu(false)" class="nav-smooth-link py-1 hover:text-brand transition flex items-center justify-between">
                    <span>About</span>
                    <svg class="w-4 h-4 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </a>
                <a href="/#courses" onclick="toggleMobileFeMenu(false)" class="nav-smooth-link py-1 hover:text-brand transition flex items-center justify-between">
                    <span>Product</span>
                    <svg class="w-4 h-4 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </a>
                <a href="/#faq" onclick="toggleMobileFeMenu(false)" class="nav-smooth-link py-1 hover:text-brand transition flex items-center justify-between">
                    <span>FaQ</span>
                    <svg class="w-4 h-4 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </a>
            </nav>
        </div>

        <div class="border-t border-slate-100 pt-5">
            <?php if ($isLoggedIn): ?>
                <div class="flex items-center justify-between">
                    <a href="/profile" class="flex items-center gap-2 text-brand font-bold text-sm">
                        <svg class="size-4 text-brand" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        <span class="max-w-[140px] truncate"><?= esc($userName) ?></span>
                    </a>
                    <a href="/logout" onclick="return confirm('Apakah Anda yakin ingin keluar?');" class="text-xs font-semibold text-slate-500 hover:text-red-600">Logout</a>
                </div>
            <?php else: ?>
                <div class="flex flex-col gap-2.5">
                    <a href="/register" class="w-full text-center bg-[#FF8D28] text-white py-2.5 rounded-full text-sm font-bold shadow-xs">Sign In</a>
                    <a href="/login" class="w-full text-center border border-slate-200 text-slate-800 hover:text-brand py-2.5 rounded-full text-sm font-bold transition">Login</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</header>

<script>
    function toggleMobileFeMenu(forceState) {
        const menu = document.getElementById('mobileFeMenu');
        const backdrop = document.getElementById('mobileFeBackdrop');
        if (!menu || !backdrop) return;
        
        const isOpening = (typeof forceState === 'boolean') ? forceState : menu.classList.contains('translate-x-full');
        
        if (isOpening) {
            backdrop.classList.remove('hidden');
            setTimeout(() => {
                backdrop.classList.remove('opacity-0', 'pointer-events-none');
                menu.classList.remove('translate-x-full');
            }, 10);
            document.body.classList.add('overflow-hidden');
        } else {
            backdrop.classList.add('opacity-0', 'pointer-events-none');
            menu.classList.add('translate-x-full');
            setTimeout(() => {
                backdrop.classList.add('hidden');
            }, 300);
            document.body.classList.remove('overflow-hidden');
        }
    }
</script>

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