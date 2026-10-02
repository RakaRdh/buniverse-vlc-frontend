<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>

<!-- Hero Top Curved Banner -->
<div class="relative w-full bg-[#C41E24] text-white pt-10 pb-16 px-4 hero-wave overflow-hidden">
    <div class="absolute inset-0 opacity-15 pointer-events-none bg-[radial-gradient(#fff_1px,transparent_1px)] [background-size:16px_16px]"></div>
    
    <div class="max-w-[1440px] mx-auto text-center relative z-10">
        <h1 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold uppercase tracking-wide">
            <?= !empty($program) ? 'Daftar Kelas' : 'LOGIN / SIGN IN' ?>
        </h1>
    </div>
</div>

<div class="max-w-2xl mx-auto px-4 py-8 -mt-6">
    <!-- Optional Program Information Card (When enrolling from a course) -->
    <?php if (!empty($program)): ?>
        <div class="text-center mb-10">
            <div class="flex justify-center mb-4">
                <img src="/img/logo-vocational.webp" alt="Logo VLC" class="h-10 object-contain">
            </div>

            <!-- Divider with shield icon -->
            <div class="relative flex py-4 items-center">
                <div class="flex-grow border-t border-slate-200"></div>
                <span class="flex-shrink mx-4 text-slate-300">
                    <svg class="size-5" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 2L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-3z" />
                    </svg>
                </span>
                <div class="flex-grow border-t border-slate-200"></div>
            </div>

            <h2 class="text-xl sm:text-2xl font-bold text-slate-900 mb-2">
                <?= esc($program['name']) ?>
            </h2>
            <p class="text-xs sm:text-sm text-slate-600 max-w-xl mx-auto leading-relaxed mb-6">
                <?= esc($program['short_desc'] ?? $program['description']) ?>
            </p>

            <div class="rounded-xl overflow-hidden shadow-lg border border-slate-100 max-w-lg mx-auto">
                <img src="<?= esc($program['image'] ?: '/img/img-course-1.webp') ?>" alt="<?= esc($program['name']) ?>" class="w-full object-cover max-h-64">
            </div>

            <!-- Divider with shield icon -->
            <div class="relative flex py-6 items-center">
                <div class="flex-grow border-t border-slate-200"></div>
                <span class="flex-shrink mx-4 text-slate-300">
                    <svg class="size-5" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 2L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-3z" />
                    </svg>
                </span>
                <div class="flex-grow border-t border-slate-200"></div>
            </div>
        </div>
    <?php endif; ?>

    <!-- Navigation Tabs (LOGIN | REGISTER) -->
    <div class="flex border-b border-slate-200 mb-8 max-w-md mx-auto">
        <button type="button" id="tabBtnLogin" onclick="switchAuthTab('login')"
                class="flex-1 py-3 text-center text-sm font-semibold transition-all cursor-pointer <?= ($active_tab === 'login') ? 'text-[#C41E24] border-b-2 border-[#C41E24] -mb-px' : 'text-slate-400 hover:text-slate-600' ?>">
            LOGIN
        </button>
        <button type="button" id="tabBtnRegister" onclick="switchAuthTab('register')"
                class="flex-1 py-3 text-center text-sm font-semibold transition-all cursor-pointer <?= ($active_tab === 'register') ? 'text-[#C41E24] border-b-2 border-[#C41E24] -mb-px' : 'text-slate-400 hover:text-slate-600' ?>">
            REGISTER
        </button>
    </div>

    <!-- LOGIN FORM -->
    <div id="loginFormContainer" class="<?= ($active_tab === 'login') ? 'block' : 'hidden' ?> max-w-lg mx-auto">
        <form action="/auth/login" method="POST" class="space-y-5">
            <?= csrf_field() ?>
            <input type="hidden" name="redirect" value="<?= esc($redirect ?? '') ?>">
            <input type="hidden" name="program_id" value="<?= esc($program_id ?? '') ?>">

            <div>
                <label for="login_email" class="block text-xs font-semibold text-slate-700 mb-1.5">Email* :</label>
                <input type="email" id="login_email" name="email" value="<?= esc(old('email') ?? '') ?>" required
                       placeholder="Isi Email anda"
                       class="w-full rounded-full border border-slate-300 px-5 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-[#C41E24] focus:border-transparent transition">
            </div>

            <div>
                <label for="login_password" class="block text-xs font-semibold text-slate-700 mb-1.5">Password * :</label>
                <input type="password" id="login_password" name="password" required
                       placeholder="Isi Password anda"
                       class="w-full rounded-full border border-slate-300 px-5 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-[#C41E24] focus:border-transparent transition">
            </div>

            <div class="pt-4 text-center">
                <button type="submit"
                        class="bg-[#F5841F] hover:bg-[#e07212] text-white font-semibold py-3 px-12 rounded-full shadow-md hover:shadow-lg transition transform active:scale-95">
                    Submit
                </button>
            </div>
        </form>
    </div>

    <!-- REGISTER FORM -->
    <div id="registerFormContainer" class="<?= ($active_tab === 'register') ? 'block' : 'hidden' ?> max-w-lg mx-auto">
        <form action="/auth/register" method="POST" class="space-y-4">
            <?= csrf_field() ?>
            <input type="hidden" name="redirect" value="<?= esc($redirect ?? '') ?>">
            <input type="hidden" name="program_id" value="<?= esc($program_id ?? '') ?>">

            <div>
                <label for="reg_fullname" class="block text-xs font-semibold text-slate-700 mb-1.5">Nama* :</label>
                <input type="text" id="reg_fullname" name="fullname" value="<?= esc(old('fullname') ?? '') ?>" required
                       placeholder="Isi nama anda"
                       class="w-full rounded-full border border-slate-300 px-5 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-[#C41E24] focus:border-transparent transition">
            </div>

            <div>
                <label for="reg_email" class="block text-xs font-semibold text-slate-700 mb-1.5">Email* :</label>
                <input type="email" id="reg_email" name="email" value="<?= esc(old('email') ?? '') ?>" required
                       placeholder="Isi Email anda"
                       class="w-full rounded-full border border-slate-300 px-5 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-[#C41E24] focus:border-transparent transition">
            </div>

            <div>
                <label for="reg_phone" class="block text-xs font-semibold text-slate-700 mb-1.5">No Telp* :</label>
                <input type="text" id="reg_phone" name="phone" value="<?= esc(old('phone') ?? '') ?>" required
                       placeholder="Isi No Telp anda"
                       class="w-full rounded-full border border-slate-300 px-5 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-[#C41E24] focus:border-transparent transition">
            </div>

            <div>
                <label for="reg_password" class="block text-xs font-semibold text-slate-700 mb-1.5">Password * :</label>
                <input type="password" id="reg_password" name="password" required minlength="6"
                       placeholder="Isi Password anda"
                       class="w-full rounded-full border border-slate-300 px-5 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-[#C41E24] focus:border-transparent transition">
            </div>

            <div>
                <label for="reg_password_confirm" class="block text-xs font-semibold text-slate-700 mb-1.5">Ulangi Password * :</label>
                <input type="password" id="reg_password_confirm" name="password_confirm" required minlength="6"
                       placeholder="Ulangi Isi Password anda"
                       class="w-full rounded-full border border-slate-300 px-5 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-[#C41E24] focus:border-transparent transition">
            </div>

            <div class="flex items-start gap-2 pt-2 px-1">
                <input type="checkbox" id="reg_terms" name="terms" value="1" required
                       class="mt-1 rounded border-slate-300 text-[#C41E24] focus:ring-[#C41E24]">
                <label for="reg_terms" class="text-[11px] text-slate-500 leading-snug cursor-pointer">
                    Dengan mengklik button di bawah anda berarti setuju dan tunduk terhadap aturan yang telah di tetapkan datasatu vocational learning center
                </label>
            </div>

            <div class="pt-4 text-center">
                <button type="submit"
                        class="bg-[#F5841F] hover:bg-[#e07212] text-white font-semibold py-3 px-12 rounded-full shadow-md hover:shadow-lg transition transform active:scale-95">
                    Submit
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function switchAuthTab(tab) {
        const loginContainer = document.getElementById('loginFormContainer');
        const registerContainer = document.getElementById('registerFormContainer');
        const btnLogin = document.getElementById('tabBtnLogin');
        const btnRegister = document.getElementById('tabBtnRegister');

        if (tab === 'login') {
            loginContainer.classList.remove('hidden');
            loginContainer.classList.add('block');
            registerContainer.classList.add('hidden');
            registerContainer.classList.remove('block');

            btnLogin.className = "flex-1 py-3 text-center text-sm font-semibold transition-all cursor-pointer text-[#C41E24] border-b-2 border-[#C41E24] -mb-px";
            btnRegister.className = "flex-1 py-3 text-center text-sm font-semibold transition-all cursor-pointer text-slate-400 hover:text-slate-600";
        } else {
            registerContainer.classList.remove('hidden');
            registerContainer.classList.add('block');
            loginContainer.classList.add('hidden');
            loginContainer.classList.remove('block');

            btnRegister.className = "flex-1 py-3 text-center text-sm font-semibold transition-all cursor-pointer text-[#C41E24] border-b-2 border-[#C41E24] -mb-px";
            btnLogin.className = "flex-1 py-3 text-center text-sm font-semibold transition-all cursor-pointer text-slate-400 hover:text-slate-600";
        }
    }
</script>

<?= $this->endSection(); ?>
