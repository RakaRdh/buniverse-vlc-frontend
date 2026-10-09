<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>

<!-- Hero Top Curved Banner using header-bg.webp (Starts cleanly AFTER navbar, organic original wave shape) -->
<section class="relative w-full overflow-hidden text-center text-white min-h-[140px] sm:min-h-[170px] lg:min-h-[200px] flex items-center justify-center">
    <img src="/img/header-bg.webp" alt="" class="absolute inset-0 w-full h-full object-fill object-bottom select-none pointer-events-none">
    <div class="relative z-10 max-w-4xl mx-auto px-4 py-8 sm:py-10">
        <h1 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold tracking-tight drop-shadow-xs">
            Daftar Kelas
        </h1>
    </div>
</section>

<div class="max-w-4xl mx-auto px-4 py-8 lg:py-12">
    <?php if (!empty($program)): ?>
        <div class="text-center mb-8">
            <div class="flex justify-center mb-4">
                <img src="/img/logo-vocational.webp" alt="Datasatu Vocational Learning Center" class="h-10 object-contain">
            </div>

            <div class="relative flex py-4 items-center max-w-2xl mx-auto">
                <div class="flex-grow border-t border-slate-200"></div>
                <span class="flex-shrink mx-4">
                    <img src="/img/logo-vlc-white.webp" alt="VLC" class="h-7 w-auto opacity-75">
                </span>
                <div class="flex-grow border-t border-slate-200"></div>
            </div>

            <h2 class="text-xl sm:text-2xl lg:text-3xl font-extrabold text-slate-900 mb-3 px-2">
                <?= esc($program['name']) ?>
            </h2>
            <?php if (!empty($program['short_desc'])): ?>
                <p class="text-xs sm:text-sm text-slate-600 max-w-2xl mx-auto leading-relaxed mb-6 px-4">
                    <?= esc($program['short_desc']) ?>
                </p>
            <?php endif; ?>

            <div class="rounded-2xl overflow-hidden shadow-md border border-slate-100 max-w-2xl mx-auto mb-6">
                <img src="<?= esc($program['image'] ?: '/img/img-course-1.webp') ?>" alt="<?= esc($program['name']) ?>" class="w-full object-cover">
            </div>

            <?php if (!empty($program['description'])): ?>
                <div class="max-w-2xl mx-auto text-left text-xs sm:text-sm text-slate-700 leading-relaxed my-6 px-4 prose max-w-none">
                    <?= $program['description'] ?>
                </div>
            <?php endif; ?>

            <div class="relative flex py-4 items-center max-w-2xl mx-auto">
                <div class="flex-grow border-t border-slate-200"></div>
                <span class="flex-shrink mx-4">
                    <img src="/img/logo-vlc-white.webp" alt="VLC" class="h-7 w-auto opacity-75">
                </span>
                <div class="flex-grow border-t border-slate-200"></div>
            </div>
        </div>
    <?php endif; ?>

    <?php $isLoggedIn = session()->get('is_logged_in'); ?>
    <?php if ($isLoggedIn && !empty($program)): ?>
        <?php $alreadyEnrolled = $alreadyEnrolled ?? false; ?>

        <?php if ($alreadyEnrolled): ?>
            <div class="max-w-xl mx-auto my-8 p-6 text-center rounded-2xl bg-emerald-50 border border-emerald-200 shadow-sm">
                <div class="inline-flex size-12 rounded-full bg-emerald-100 text-emerald-600 items-center justify-center mb-3">
                    <svg class="size-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                </div>
                <h3 class="text-base font-bold text-emerald-900">Anda Sudah Terdaftar di Kelas Ini</h3>
                <p class="text-xs text-emerald-700 mt-1">Status: Terdaftar sebagai peserta aktif.</p>
                <div class="mt-5 flex items-center justify-center gap-4">
                    <a href="/profile" class="inline-block text-xs font-bold text-[#C41E24] hover:underline">
                        Lihat Status di Profil &rarr;
                    </a>
                    <span class="text-slate-300">&bull;</span>
                    <a href="/#courses" class="inline-block text-xs font-semibold text-emerald-800 hover:underline">
                        Lihat Kelas Lainnya
                    </a>
                </div>
            </div>
        <?php else: ?>
            <div class="max-w-xl mx-auto my-8 p-6 md:p-8 rounded-2xl bg-white border border-slate-200 shadow-sm">
                <?php if (empty($memberPhone)): ?>
                    <div class="mb-5 pb-4 border-b border-slate-100 text-left">
                        <div class="flex items-center gap-2.5 text-[#C41E24] mb-1.5 font-bold text-sm">
                            <i data-lucide="alert-circle" class="size-4 shrink-0"></i>
                            <span>Lengkapi Nomor Telepon Anda</span>
                        </div>
                        <p class="text-xs text-slate-500 leading-relaxed">
                            Akun Anda belum memiliki nomor WhatsApp / Telepon aktif. Silakan lengkapi nomor telepon Anda untuk konfirmasi jadwal kelas dan pendaftaran.
                        </p>
                    </div>

                    <form action="/programs/enroll/<?= $program['id'] ?>" method="POST" class="space-y-4 text-left">
                        <?= csrf_field() ?>

                        <div>
                            <label for="enroll_fullname" class="block text-xs font-bold text-slate-700 mb-1.5">Nama Lengkap * :</label>
                            <input type="text" id="enroll_fullname" value="<?= esc(session('member_name')) ?>" readonly
                                   class="w-full rounded-full border border-slate-300 bg-slate-50 px-6 py-3 text-sm text-slate-600 cursor-not-allowed select-none focus:outline-none">
                        </div>

                        <div>
                            <label for="enroll_email" class="block text-xs font-bold text-slate-700 mb-1.5">Email * :</label>
                            <input type="email" id="enroll_email" value="<?= esc(session('member_email')) ?>" readonly
                                   class="w-full rounded-full border border-slate-300 bg-slate-50 px-6 py-3 text-sm text-slate-600 cursor-not-allowed select-none focus:outline-none">
                        </div>

                        <div>
                            <label for="enroll_phone" class="block text-xs font-bold text-slate-700 mb-1.5">Nomor WhatsApp / Telepon * :</label>
                            <input type="text" id="enroll_phone" name="phone" required
                                   value="<?= esc(old('phone') ?? '') ?>"
                                   placeholder="Contoh: 081234567890"
                                   class="w-full rounded-full border border-slate-300 px-6 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-[#C41E24] focus:border-transparent transition">
                        </div>

                        <div class="pt-4 text-center">
                            <button type="submit"
                                    class="bg-[#FF8D28] hover:bg-[#e07212] text-white font-bold py-3.5 px-14 rounded-full shadow-md hover:shadow-lg transition transform active:scale-95">
                                Submit
                            </button>
                        </div>
                    </form>
                <?php else: ?>
                    <div class="text-center">
                        <p class="text-xs text-slate-500 mb-2">Masuk sebagai <strong class="text-slate-800"><?= esc(session('member_name')) ?></strong> (<?= esc(session('member_email')) ?>)</p>
                        <p class="text-xs text-slate-400 mb-5">No. Telepon / WhatsApp terdaftar: <strong class="text-slate-600"><?= esc($memberPhone) ?></strong></p>
                        <form action="/programs/enroll/<?= $program['id'] ?>" method="POST">
                            <?= csrf_field() ?>
                            <button type="submit" class="bg-[#FF8D28] hover:bg-[#e07212] text-white font-bold py-3 px-10 rounded-full shadow-md transition transform active:scale-95">
                                Daftar Kelas Ini Sekarang
                            </button>
                        </form>
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>

    <?php else: ?>

        <div class="flex border-b border-slate-200 mb-8 max-w-xl mx-auto">
            <button type="button" id="tabBtnLogin" onclick="switchAuthTab('login')"
                    class="flex-1 py-3 text-center text-sm font-bold transition-all cursor-pointer text-slate-400 hover:text-slate-600">
                Login
            </button>
            <button type="button" id="tabBtnRegister" onclick="switchAuthTab('register')"
                    class="flex-1 py-3 text-center text-sm font-bold transition-all cursor-pointer text-[#C41E24] border-b-2 border-[#C41E24] -mb-px">
                Daftar Sekarang
            </button>
        </div>

        <div id="loginFormContainer" class="hidden max-w-xl mx-auto">
            <form action="/auth/login" method="POST" class="space-y-5">
                <?= csrf_field() ?>
                <input type="hidden" name="program_id" value="<?= esc($program['id'] ?? '') ?>">

                <div>
                    <label for="login_email" class="block text-xs font-bold text-slate-700 mb-1.5">Email * :</label>
                    <input type="email" id="login_email" name="email" value="<?= esc(old('email') ?? '') ?>" required
                           placeholder="Isi email Anda"
                           class="w-full rounded-full border border-slate-300 px-6 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-[#C41E24] focus:border-transparent transition">
                </div>

                <div>
                    <label for="login_password" class="block text-xs font-bold text-slate-700 mb-1.5">Password * :</label>
                    <div class="relative">
                        <input type="password" id="login_password" name="password" required
                               placeholder="Isi password Anda"
                               class="w-full rounded-full border border-slate-300 pl-6 pr-12 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-[#C41E24] focus:border-transparent transition">
                        <button type="button" onclick="toggleFrontendPassword('login_password', this)"
                                class="absolute inset-y-0 right-0 flex items-center pr-5 text-slate-400 hover:text-slate-600 focus:outline-none"
                                title="Tampilkan / Sembunyikan Password">
                            <i data-lucide="eye" class="size-4"></i>
                        </button>
                    </div>
                </div>

                <div class="pt-4 text-center">
                    <button type="submit"
                            class="bg-[#FF8D28] hover:bg-[#e07212] text-white font-bold py-3.5 px-14 rounded-full shadow-md hover:shadow-lg transition transform active:scale-95">
                        Submit
                    </button>
                </div>
            </form>
        </div>

        <?php
        /**
         * RELASI DAN ALUR PENDAFTARAN:
         * Form registrasi ini terhubung ke App\Controllers\Auth::attemptRegister:
         * 1. Data akun (fullname, email, password ter-hash SHA-512) disimpan ke tabel `tblmember`.
         * 2. Nomor telepon/WhatsApp disimpan ke relasi profil di tabel `tblmember_profile` (foreign key: member_id).
         * 3. Jika pengguna mendaftar melalui halaman detail program ini, program_id akan langsung
         *    didaftarkan ke tabel `tblprogramenrollment` dengan status awal 'enrolled'.
         */
        ?>
        <div id="registerFormContainer" class="block max-w-xl mx-auto">
            <form action="/auth/register" method="POST" class="space-y-4">
                <?= csrf_field() ?>
                <input type="hidden" name="program_id" value="<?= esc($program['id'] ?? '') ?>">

                <div>
                    <label for="reg_fullname" class="block text-xs font-bold text-slate-700 mb-1.5">Nama Lengkap * :</label>
                    <input type="text" id="reg_fullname" name="fullname" value="<?= esc(old('fullname') ?? '') ?>" required
                           placeholder="Isi nama lengkap Anda"
                           class="w-full rounded-full border border-slate-300 px-6 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-[#C41E24] focus:border-transparent transition">
                </div>

                <div>
                    <label for="reg_email" class="block text-xs font-bold text-slate-700 mb-1.5">Email * :</label>
                    <input type="email" id="reg_email" name="email" value="<?= esc(old('email') ?? '') ?>" required
                           placeholder="Isi alamat email Anda"
                           class="w-full rounded-full border border-slate-300 px-6 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-[#C41E24] focus:border-transparent transition">
                </div>

                <div>
                    <label for="reg_phone" class="block text-xs font-bold text-slate-700 mb-1.5">Nomor WhatsApp / Telepon * :</label>
                    <input type="text" id="reg_phone" name="phone" value="<?= esc(old('phone') ?? '') ?>" required
                           placeholder="Contoh: 081234567890"
                           class="w-full rounded-full border border-slate-300 px-6 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-[#C41E24] focus:border-transparent transition">
                </div>

                <div>
                    <label for="reg_password" class="block text-xs font-bold text-slate-700 mb-1.5">Password * :</label>
                    <div class="relative">
                        <input type="password" id="reg_password" name="password" required minlength="6"
                               placeholder="Minimal 6 karakter"
                               class="w-full rounded-full border border-slate-300 pl-6 pr-12 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-[#C41E24] focus:border-transparent transition">
                        <button type="button" onclick="toggleFrontendPassword('reg_password', this)"
                                class="absolute inset-y-0 right-0 flex items-center pr-5 text-slate-400 hover:text-slate-600 focus:outline-none"
                                title="Tampilkan / Sembunyikan Password">
                            <i data-lucide="eye" class="size-4"></i>
                        </button>
                    </div>
                </div>

                <div>
                    <label for="reg_password_confirm" class="block text-xs font-bold text-slate-700 mb-1.5">Ulangi Password * :</label>
                    <div class="relative">
                        <input type="password" id="reg_password_confirm" name="password_confirm" required minlength="6"
                               placeholder="Ulangi isi password Anda"
                               class="w-full rounded-full border border-slate-300 pl-6 pr-12 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-[#C41E24] focus:border-transparent transition">
                        <button type="button" onclick="toggleFrontendPassword('reg_password_confirm', this)"
                                class="absolute inset-y-0 right-0 flex items-center pr-5 text-slate-400 hover:text-slate-600 focus:outline-none"
                                title="Tampilkan / Sembunyikan Password">
                            <i data-lucide="eye" class="size-4"></i>
                        </button>
                    </div>
                </div>

                <div class="flex items-start gap-2 pt-2 px-1">
                    <input type="checkbox" id="reg_terms" name="terms" value="1" required
                           class="mt-1 rounded border-slate-300 text-[#C41E24] focus:ring-[#C41E24]">
                    <label for="reg_terms" class="text-[11px] text-slate-500 leading-snug cursor-pointer">
                        Dengan mengklik tombol di bawah Anda setuju dan tunduk terhadap aturan yang telah ditetapkan Datasatu Vocational Learning Center.
                    </label>
                </div>

                <div class="pt-4 text-center">
                    <button type="submit"
                            class="bg-[#FF8D28] hover:bg-[#e07212] text-white font-bold py-3.5 px-14 rounded-full shadow-md hover:shadow-lg transition transform active:scale-95">
                        Submit
                    </button>
                </div>
            </form>
        </div>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/lucide@0.468.0/dist/umd/lucide.min.js"></script>
<script>
    function toggleFrontendPassword(inputId, btn) {
        const input = document.getElementById(inputId);
        const icon = btn.querySelector('[data-lucide]');
        if (input.type === 'password') {
            input.type = 'text';
            icon.setAttribute('data-lucide', 'eye-off');
        } else {
            input.type = 'password';
            icon.setAttribute('data-lucide', 'eye');
        }
        if (window.lucide) {
            lucide.createIcons();
        }
    }

    function switchAuthTab(tab) {
        const loginContainer = document.getElementById('loginFormContainer');
        const registerContainer = document.getElementById('registerFormContainer');
        const btnLogin = document.getElementById('tabBtnLogin');
        const btnRegister = document.getElementById('tabBtnRegister');

        if (!loginContainer || !registerContainer) return;

        if (tab === 'login') {
            loginContainer.classList.remove('hidden');
            loginContainer.classList.add('block');
            registerContainer.classList.add('hidden');
            registerContainer.classList.remove('block');

            btnLogin.className = "flex-1 py-3 text-center text-sm font-bold transition-all cursor-pointer text-[#C41E24] border-b-2 border-[#C41E24] -mb-px";
            btnRegister.className = "flex-1 py-3 text-center text-sm font-bold transition-all cursor-pointer text-slate-400 hover:text-slate-600";
        } else {
            registerContainer.classList.remove('hidden');
            registerContainer.classList.add('block');
            loginContainer.classList.add('hidden');
            loginContainer.classList.remove('block');

            btnRegister.className = "flex-1 py-3 text-center text-sm font-bold transition-all cursor-pointer text-[#C41E24] border-b-2 border-[#C41E24] -mb-px";
            btnLogin.className = "flex-1 py-3 text-center text-sm font-bold transition-all cursor-pointer text-slate-400 hover:text-slate-600";
        }
    }

    document.addEventListener("DOMContentLoaded", function() {
        if (window.lucide) {
            lucide.createIcons();
        }
    });
</script>

<?= $this->endSection(); ?>