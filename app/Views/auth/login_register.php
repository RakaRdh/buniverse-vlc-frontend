<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>

<!-- Hero Top Curved Banner using header-bg.webp (Organic original wave shape) -->
<section class="relative w-full overflow-hidden text-center text-white min-h-[140px] sm:min-h-[170px] lg:min-h-[200px] flex items-center justify-center">
    <img src="/img/header-bg.webp" alt="" class="absolute inset-0 w-full h-full object-fill object-bottom select-none pointer-events-none">
    <div class="relative z-10 max-w-4xl mx-auto px-4 py-8 sm:py-10">
        <h1 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold tracking-tight drop-shadow-xs">
            <?= !empty($program) ? 'Daftar Kelas' : 'Login / Daftar' ?>
        </h1>
    </div>
</section>

<div class="max-w-2xl mx-auto px-4 py-8">
    <!-- Flash Messages -->
    <?php if (session()->getFlashdata('error')): ?>
        <div class="max-w-xl mx-auto mb-6 p-4 rounded-xl bg-red-50 border border-red-200 text-red-700 text-xs sm:text-sm font-semibold flex items-center gap-2">
            <i data-lucide="alert-circle" class="size-4 shrink-0 text-red-500"></i>
            <div>
                <span><?= esc(session()->getFlashdata('error')) ?></span>
                <?php if (session()->getFlashdata('unverified_email')): ?>
                    <form action="/auth/resend-verification" method="POST" class="mt-2">
                        <?= csrf_field() ?>
                        <input type="hidden" name="email" value="<?= esc(session()->getFlashdata('unverified_email')) ?>">
                        <button type="submit" class="text-xs text-[#C41E24] underline hover:text-[#9b151a] font-bold">
                            Kirim Ulang Email Verifikasi &rarr;
                        </button>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="max-w-xl mx-auto mb-6 p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs sm:text-sm font-semibold flex items-center gap-2">
            <i data-lucide="check-circle-2" class="size-4 shrink-0 text-emerald-500"></i>
            <div>
                <span><?= esc(session()->getFlashdata('success')) ?></span>
                <?php if (session()->getFlashdata('unverified_email')): ?>
                    <form action="/auth/resend-verification" method="POST" class="mt-2">
                        <?= csrf_field() ?>
                        <input type="hidden" name="email" value="<?= esc(session()->getFlashdata('unverified_email')) ?>">
                        <button type="submit" class="text-xs text-[#C41E24] underline hover:text-[#9b151a] font-bold">
                            Kirim Ulang Email Verifikasi &rarr;
                        </button>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    <?php endif; ?>

    <!-- Program Details Section (matching Gambar 2 & Gambar 3) -->
    <?php if (!empty($program)): ?>
        <div class="text-center mb-8">
            <!-- Logo Vocational Learning Center -->
            <div class="flex justify-center mb-4">
                <img src="/img/logo-vocational.webp" alt="Datasatu Vocational Learning Center" class="h-10 object-contain">
            </div>

            <!-- Divider with logo-vlc-white.webp (Gambar 4) -->
            <div class="relative flex py-6 items-center max-w-xl mx-auto">
                <div class="flex-grow border-t border-slate-200"></div>
                <span class="flex-shrink mx-4">
                    <img src="/img/logo-vlc-white.webp" alt="VLC" class="h-7 w-auto opacity-75">
                </span>
                <div class="flex-grow border-t border-slate-200"></div>
            </div>

            <!-- Title & Short Description -->
            <h2 class="text-xl sm:text-2xl font-bold text-slate-900 mb-3 px-2">
                <?= esc($program['name']) ?>
            </h2>
            <p class="text-xs sm:text-sm text-slate-600 max-w-xl mx-auto leading-relaxed mb-6 px-4">
                <?= esc($program['short_desc'] ?? $program['description']) ?>
            </p>

            <!-- Course Banner Image -->
            <div class="rounded-xl overflow-hidden shadow-md border border-slate-100 max-w-xl mx-auto mb-6">
                <img src="<?= esc($program['image'] ?: '/img/img-course-1.webp') ?>" alt="<?= esc($program['name']) ?>" class="w-full object-cover">
            </div>

            <!-- Second Divider with logo-vlc-white.webp (Gambar 4) -->
            <div class="relative flex py-6 items-center max-w-xl mx-auto">
                <div class="flex-grow border-t border-slate-200"></div>
                <span class="flex-shrink mx-4">
                    <img src="/img/logo-vlc-white.webp" alt="VLC" class="h-7 w-auto opacity-75">
                </span>
                <div class="flex-grow border-t border-slate-200"></div>
            </div>
        </div>
    <?php endif; ?>

    <!-- Enrollment State if already logged in -->
    <?php $isLoggedIn = session()->get('is_logged_in'); ?>
    <?php if ($isLoggedIn && !empty($program)): ?>
        <?php 
            $enrollmentModel = new \App\Models\EnrollmentModel();
            $userEnrollment = $enrollmentModel->where('member_id', session('member_id'))->where('program_id', $program['id'])->first();
            $alreadyEnrolled = !empty($userEnrollment);
        ?>

        <?php if ($alreadyEnrolled): ?>
            <?php $eStatus = $userEnrollment['status'] ?? 'waiting'; ?>

            <?php if ($eStatus === 'waiting' || $eStatus === 'enrolled'): ?>
                <!-- Status Waiting: Menunggu Verifikasi Admin -->
                <div class="max-w-xl mx-auto my-8 p-6 md:p-8 text-center rounded-2xl bg-amber-50/80 border border-amber-200/90 shadow-sm">
                    <div class="inline-flex size-12 rounded-full bg-amber-100 text-amber-600 items-center justify-center mb-3">
                        <i data-lucide="clock" class="size-6"></i>
                    </div>
                    <h3 class="text-base sm:text-lg font-bold text-amber-950">Menunggu Verifikasi Admin Terlebih Dahulu</h3>
                    <p class="text-xs sm:text-sm text-amber-900/90 max-w-md mx-auto mt-1.5 leading-relaxed">
                        Pendaftaran Anda telah diterima dan sedang dalam tahap review oleh tim admin. Proses verifikasi memerlukan waktu <strong>1&ndash;3 hari kerja</strong>.
                    </p>
                    <div class="mt-5 flex items-center justify-center gap-4 text-xs font-semibold">
                        <a href="/profile" class="text-[#C41E24] hover:underline font-bold">
                            Lihat Status di Profil &rarr;
                        </a>
                        <span class="text-slate-300">&bull;</span>
                        <a href="/programs" class="text-slate-600 hover:text-slate-900 hover:underline">
                            Lihat Kelas Lainnya
                        </a>
                    </div>
                </div>
            <?php elseif ($eStatus === 'contacted'): ?>
                <!-- Status Contacted -->
                <div class="max-w-xl mx-auto my-8 p-6 md:p-8 text-center rounded-2xl bg-blue-50/80 border border-blue-200 shadow-sm">
                    <div class="inline-flex size-12 rounded-full bg-blue-100 text-blue-600 items-center justify-center mb-3">
                        <i data-lucide="phone-call" class="size-6"></i>
                    </div>
                    <h3 class="text-base sm:text-lg font-bold text-blue-950">Pendaftaran Sedang Ditindaklanjuti</h3>
                    <p class="text-xs sm:text-sm text-blue-900/90 max-w-md mx-auto mt-1.5 leading-relaxed">
                        Tim admin kami telah menghubungi kontak WhatsApp Anda untuk konfirmasi jadwal dan administrasi kelas.
                    </p>
                    <div class="mt-5 flex items-center justify-center gap-4 text-xs font-semibold">
                        <a href="/profile" class="text-[#C41E24] hover:underline font-bold">
                            Lihat Status di Profil &rarr;
                        </a>
                        <span class="text-slate-300">&bull;</span>
                        <a href="/programs" class="text-slate-600 hover:text-slate-900 hover:underline">
                            Lihat Kelas Lainnya
                        </a>
                    </div>
                </div>
            <?php elseif ($eStatus === 'active' || $eStatus === 'in_progress'): ?>
                <!-- Status Active: Terverifikasi -->
                <div class="max-w-xl mx-auto my-8 p-6 md:p-8 text-center rounded-2xl bg-emerald-50/90 border border-emerald-200 shadow-sm">
                    <div class="inline-flex size-12 rounded-full bg-emerald-100 text-emerald-600 items-center justify-center mb-3">
                        <i data-lucide="check-circle-2" class="size-6"></i>
                    </div>
                    <h3 class="text-base sm:text-lg font-bold text-emerald-950">Pendaftaran Anda Telah Terverifikasi</h3>
                    <p class="text-xs sm:text-sm text-emerald-800/90 max-w-md mx-auto mt-1.5 leading-relaxed">
                        Status: Terdaftar sebagai peserta aktif di kelas ini. Tim admin telah mengonfirmasi keikutsertaan Anda.
                    </p>
                    <div class="mt-5 flex items-center justify-center gap-4 text-xs font-semibold">
                        <a href="/profile" class="text-[#C41E24] hover:underline font-bold">
                            Lihat Status di Profil &rarr;
                        </a>
                        <span class="text-slate-300">&bull;</span>
                        <a href="/programs" class="text-slate-600 hover:text-slate-900 hover:underline">
                            Lihat Kelas Lainnya
                        </a>
                    </div>
                </div>
            <?php elseif ($eStatus === 'finished'): ?>
                <!-- Status Finished -->
                <div class="max-w-xl mx-auto my-8 p-6 md:p-8 text-center rounded-2xl bg-emerald-50/90 border border-emerald-200 shadow-sm">
                    <div class="inline-flex size-12 rounded-full bg-emerald-100 text-emerald-600 items-center justify-center mb-3">
                        <i data-lucide="award" class="size-6"></i>
                    </div>
                    <h3 class="text-base sm:text-lg font-bold text-emerald-950">Pelatihan Telah Selesai</h3>
                    <p class="text-xs sm:text-sm text-emerald-800/90 max-w-md mx-auto mt-1.5 leading-relaxed">
                        Anda telah berhasil menyelesaikan program pelatihan vokasi ini. Terima kasih telah belajar bersama Datasatu VLC.
                    </p>
                    <div class="mt-5 flex items-center justify-center gap-4 text-xs font-semibold">
                        <a href="/profile" class="text-[#C41E24] hover:underline font-bold">
                            Lihat Status di Profil &rarr;
                        </a>
                        <span class="text-slate-300">&bull;</span>
                        <a href="/programs" class="text-slate-600 hover:text-slate-900 hover:underline">
                            Lihat Kelas Lainnya
                        </a>
                    </div>
                </div>
            <?php elseif ($eStatus === 'rejected'): ?>
                <!-- Status Rejected -->
                <div class="max-w-xl mx-auto my-8 p-6 md:p-8 text-center rounded-2xl bg-rose-50 border border-rose-200 shadow-sm">
                    <div class="inline-flex size-12 rounded-full bg-rose-100 text-rose-600 items-center justify-center mb-3">
                        <i data-lucide="x-circle" class="size-6"></i>
                    </div>
                    <h3 class="text-base sm:text-lg font-bold text-rose-950">Verifikasi Pendaftaran Belum Disetujui</h3>
                    <p class="text-xs sm:text-sm text-rose-800/90 max-w-md mx-auto mt-1.5 leading-relaxed">
                        Mohon maaf, pendaftaran Anda untuk batch program ini belum dapat disetujui (kuota telah penuh atau batas verifikasi 3 hari telah berakhir). Silakan memilih kelas lainnya di katalog kami.
                    </p>
                    <div class="mt-5">
                        <a href="/programs" class="inline-block bg-[#C41E24] hover:bg-[#a8151a] text-white text-xs font-bold py-2.5 px-6 rounded-full shadow-sm transition">
                            Lihat Pilihan Kelas Lainnya &rarr;
                        </a>
                    </div>
                </div>
            <?php endif; ?>
        <?php else: ?>
            <?php
                $memberPhone = '';
                $profileModel = new \App\Models\ProfileModel();
                $profile = $profileModel->where('member_id', session('member_id'))->first();
                $memberPhone = trim($profile['phone'] ?? '');
            ?>
            <div class="max-w-lg mx-auto my-8 p-6 text-center rounded-2xl bg-white border border-slate-200 shadow-sm">
                <?php if (empty($memberPhone)): ?>
                    <div class="mb-5 pb-4 border-b border-slate-100 text-left">
                        <div class="flex items-center gap-2 text-[#C41E24] mb-1 font-bold text-sm">
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
                            <label for="enroll_fullname_auth" class="block text-xs font-semibold text-slate-700 mb-1.5">Nama* :</label>
                            <input type="text" id="enroll_fullname_auth" value="<?= esc(session('member_name')) ?>" readonly
                                   class="w-full rounded-full border border-slate-300 bg-slate-50 px-5 py-3 text-sm text-slate-600 cursor-not-allowed select-none focus:outline-none">
                        </div>

                        <div>
                            <label for="enroll_email_auth" class="block text-xs font-semibold text-slate-700 mb-1.5">Email* :</label>
                            <input type="email" id="enroll_email_auth" value="<?= esc(session('member_email')) ?>" readonly
                                   class="w-full rounded-full border border-slate-300 bg-slate-50 px-5 py-3 text-sm text-slate-600 cursor-not-allowed select-none focus:outline-none">
                        </div>

                        <div>
                            <label for="enroll_phone_auth" class="block text-xs font-semibold text-slate-700 mb-1.5">No Telp* :</label>
                            <input type="text" id="enroll_phone_auth" name="phone" required
                                   value="<?= esc(old('phone') ?? '') ?>"
                                   placeholder="Isi No Telp anda"
                                   class="w-full rounded-full border border-slate-300 px-5 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-[#C41E24] focus:border-transparent transition">
                        </div>

                        <div class="pt-4 text-center">
                            <button type="submit" class="bg-[#F5841F] hover:bg-[#e07212] text-white font-semibold py-3 px-12 rounded-full shadow-md hover:shadow-lg transition transform active:scale-95">
                                Submit
                            </button>
                        </div>
                    </form>
                <?php else: ?>
                    <p class="text-xs text-slate-500 mb-2">Masuk sebagai <strong class="text-slate-800"><?= esc(session('member_name')) ?></strong> (<?= esc(session('member_email')) ?>)</p>
                    <p class="text-xs text-slate-400 mb-4">No. Telepon / WhatsApp: <strong class="text-slate-600"><?= esc($memberPhone) ?></strong></p>
                    <form action="/programs/enroll/<?= $program['id'] ?>" method="POST" class="mt-4">
                        <?= csrf_field() ?>
                        <button type="submit" class="bg-[#F5841F] hover:bg-[#e07212] text-white font-semibold py-3 px-10 rounded-full shadow-md transition transform active:scale-95">
                            Daftar Kelas Ini Sekarang
                        </button>
                    </form>
                <?php endif; ?>
            </div>
        <?php endif; ?>

    <?php else: ?>

        <!-- Tabs (Login | Daftar Sekarang) matching programs_detail.php -->
        <div class="flex border-b border-slate-200 mb-8 max-w-xl mx-auto">
            <button type="button" id="tabBtnLogin" onclick="switchAuthTab('login')"
                    class="flex-1 py-3 text-center text-sm font-bold transition-all cursor-pointer <?= ($active_tab === 'login') ? 'text-[#C41E24] border-b-2 border-[#C41E24] -mb-px' : 'text-slate-400 hover:text-slate-600' ?>">
                Login
            </button>
            <button type="button" id="tabBtnRegister" onclick="switchAuthTab('register')"
                    class="flex-1 py-3 text-center text-sm font-bold transition-all cursor-pointer <?= ($active_tab === 'register') ? 'text-[#C41E24] border-b-2 border-[#C41E24] -mb-px' : 'text-slate-400 hover:text-slate-600' ?>">
                Daftar Sekarang
            </button>
        </div>

        <!-- LOGIN FORM -->
        <div id="loginFormContainer" class="<?= ($active_tab === 'login') ? 'block' : 'hidden' ?> max-w-lg mx-auto">
            <form action="/auth/login" method="POST" class="space-y-5">
                <?= csrf_field() ?>
                <input type="hidden" name="redirect" value="<?= esc($redirect ?? '') ?>">
                <input type="hidden" name="program_id" value="<?= esc($program['id'] ?? $program_id ?? '') ?>">

                <div>
                    <label for="login_email" class="block text-xs font-semibold text-slate-700 mb-1.5">Email* :</label>
                    <input type="email" id="login_email" name="email" value="<?= esc(old('email') ?? '') ?>" required
                           placeholder="Isi Email anda"
                           class="w-full rounded-full border border-slate-300 px-5 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-[#C41E24] focus:border-transparent transition">
                </div>

                <div>
                    <label for="login_password" class="block text-xs font-semibold text-slate-700 mb-1.5">Password * :</label>
                    <div class="relative">
                        <input type="password" id="login_password" name="password" required
                               placeholder="Isi Password anda"
                               class="w-full rounded-full border border-slate-300 pl-5 pr-12 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-[#C41E24] focus:border-transparent transition">
                        <button type="button" onclick="toggleFrontendPassword('login_password', this)"
                                class="absolute inset-y-0 right-0 flex items-center pr-4 text-slate-400 hover:text-slate-600 focus:outline-none"
                                title="Tampilkan / Sembunyikan Password">
                            <i data-lucide="eye" class="size-4"></i>
                        </button>
                    </div>
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
                <input type="hidden" name="program_id" value="<?= esc($program['id'] ?? $program_id ?? '') ?>">

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
                    <div class="relative">
                        <input type="password" id="reg_password" name="password" required minlength="6"
                               placeholder="Isi Password anda"
                               class="w-full rounded-full border border-slate-300 pl-5 pr-12 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-[#C41E24] focus:border-transparent transition">
                        <button type="button" onclick="toggleFrontendPassword('reg_password', this)"
                                class="absolute inset-y-0 right-0 flex items-center pr-4 text-slate-400 hover:text-slate-600 focus:outline-none"
                                title="Tampilkan / Sembunyikan Password">
                            <i data-lucide="eye" class="size-4"></i>
                        </button>
                    </div>
                </div>

                <div>
                    <label for="reg_password_confirm" class="block text-xs font-semibold text-slate-700 mb-1.5">Ulangi Password * :</label>
                    <div class="relative">
                        <input type="password" id="reg_password_confirm" name="password_confirm" required minlength="6"
                               placeholder="Ulangi Isi Password anda"
                               class="w-full rounded-full border border-slate-300 pl-5 pr-12 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-[#C41E24] focus:border-transparent transition">
                        <button type="button" onclick="toggleFrontendPassword('reg_password_confirm', this)"
                                class="absolute inset-y-0 right-0 flex items-center pr-4 text-slate-400 hover:text-slate-600 focus:outline-none"
                                title="Tampilkan / Sembunyikan Password">
                            <i data-lucide="eye" class="size-4"></i>
                        </button>
                    </div>
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
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/lucide@0.468.0/dist/umd/lucide.min.js"></script>
<script>
    function toggleFrontendPassword(inputId, btn) {
        const input = document.getElementById(inputId);
        if (!input) return;
        const icon = btn.querySelector('[data-lucide]');
        if (input.type === 'password') {
            input.type = 'text';
            if (icon) icon.setAttribute('data-lucide', 'eye-off');
        } else {
            input.type = 'password';
            if (icon) icon.setAttribute('data-lucide', 'eye');
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

    document.addEventListener("DOMContentLoaded", function() {
        if (window.lucide) {
            lucide.createIcons();
        }
    });
</script>

<?= $this->endSection(); ?>
