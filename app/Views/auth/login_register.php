<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>

<!-- Hero Top Curved Banner using header-bg.webp -->
<section class="relative w-full overflow-hidden text-center text-white min-h-[140px] sm:min-h-[170px] lg:min-h-[200px] flex items-center justify-center">
    <img src="/img/header-bg.webp" alt="" class="absolute inset-0 w-full h-full object-fill object-bottom select-none pointer-events-none">
    <div class="relative z-10 max-w-4xl mx-auto px-4 py-8 sm:py-10">
        <h1 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold tracking-tight drop-shadow-xs">
            Login / Daftar
        </h1>
    </div>
</section>

<div class="max-w-2xl mx-auto px-4 py-8">
    <!-- Flash Messages -->
    <?php if (session()->getFlashdata('error')): ?>
        <div class="max-w-lg mx-auto mb-6 p-4 rounded-xl bg-red-50 border border-red-200 text-red-700 text-xs sm:text-sm font-semibold flex items-center gap-2">
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
        <div class="max-w-lg mx-auto mb-6 p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs sm:text-sm font-semibold flex items-center gap-2">
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

    <!-- Tabs (Login | Daftar Sekarang) -->
    <div class="flex border-b border-slate-200 mb-8 max-w-lg mx-auto">
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
                    <button type="button" tabindex="-1" onclick="toggleFrontendPassword('login_password', this)"
                            class="absolute inset-y-0 right-0 flex items-center pr-4 text-slate-400 hover:text-slate-600 focus:outline-none"
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

    <!-- REGISTER FORM -->
    <div id="registerFormContainer" class="<?= ($active_tab === 'register') ? 'block' : 'hidden' ?> max-w-lg mx-auto">
        <form id="registerForm" action="/auth/register" method="POST" class="space-y-4">
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
                <p id="reg_email_feedback" class="text-[11px] font-semibold mt-1 hidden"></p>
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
                    <button type="button" tabindex="-1" onclick="toggleFrontendPassword('reg_password', this)"
                            class="absolute inset-y-0 right-0 flex items-center pr-4 text-slate-400 hover:text-slate-600 focus:outline-none"
                            title="Tampilkan / Sembunyikan Password">
                        <i data-lucide="eye" class="size-4"></i>
                    </button>
                </div>
                <p id="reg_password_error" class="text-[11px] text-red-500 font-semibold mt-1 hidden"></p>
            </div>

            <div>
                <label for="reg_password_confirm" class="block text-xs font-semibold text-slate-700 mb-1.5">Ulangi Password * :</label>
                <div class="relative">
                    <input type="password" id="reg_password_confirm" name="password_confirm" required minlength="6"
                           placeholder="Ulangi Isi Password anda"
                           class="w-full rounded-full border border-slate-300 pl-5 pr-12 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-[#C41E24] focus:border-transparent transition">
                    <button type="button" tabindex="-1" onclick="toggleFrontendPassword('reg_password_confirm', this)"
                            class="absolute inset-y-0 right-0 flex items-center pr-4 text-slate-400 hover:text-slate-600 focus:outline-none"
                            title="Tampilkan / Sembunyikan Password">
                        <i data-lucide="eye" class="size-4"></i>
                    </button>
                </div>
                <p id="reg_password_match_feedback" class="text-[11px] font-semibold mt-1 hidden"></p>
            </div>

            <div class="flex items-start gap-2 pt-2 px-1">
                <input type="checkbox" id="reg_terms" name="terms" value="1" required
                       class="mt-1 rounded border-slate-300 text-[#C41E24] focus:ring-[#C41E24]">
                <label for="reg_terms" class="text-[11px] text-slate-500 leading-snug cursor-pointer">
                    Dengan mengklik button di bawah anda berarti setuju dan tunduk terhadap aturan yang telah di tetapkan datasatu vocational learning center
                </label>
            </div>

            <div class="pt-4 text-center">
                <button type="submit" id="regSubmitBtn"
                        class="bg-[#FF8D28] hover:bg-[#e07212] text-white font-bold py-3.5 px-14 rounded-full shadow-md hover:shadow-lg transition transform active:scale-95">
                    Submit
                </button>
            </div>
        </form>
    </div>
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

    // Realtime Password Match Validation
    function validatePasswordMatch() {
        const pw = document.getElementById('reg_password');
        const pwConfirm = document.getElementById('reg_password_confirm');
        const pwErr = document.getElementById('reg_password_error');
        const matchFeedback = document.getElementById('reg_password_match_feedback');

        if (!pw || !pwConfirm) return true;

        let isValid = true;

        // Validasi panjang password
        if (pw.value.length > 0 && pw.value.length < 6) {
            pwErr.textContent = 'Password minimal 6 karakter.';
            pwErr.classList.remove('hidden');
            pw.classList.add('border-red-500');
            isValid = false;
        } else {
            pwErr.classList.add('hidden');
            pw.classList.remove('border-red-500');
        }

        // Validasi kecocokan password
        if (pwConfirm.value.length > 0) {
            if (pw.value !== pwConfirm.value) {
                matchFeedback.textContent = 'Password tidak cocok.';
                matchFeedback.className = 'text-[11px] text-red-500 font-semibold mt-1 block';
                pwConfirm.classList.add('border-red-500');
                pwConfirm.classList.remove('border-emerald-500');
                isValid = false;
            } else {
                matchFeedback.textContent = 'Password cocok.';
                matchFeedback.className = 'text-[11px] text-emerald-600 font-semibold mt-1 block';
                pwConfirm.classList.remove('border-red-500');
                pwConfirm.classList.add('border-emerald-500');
            }
        } else {
            matchFeedback.classList.add('hidden');
            pwConfirm.classList.remove('border-red-500', 'border-emerald-500');
        }

        return isValid;
    }

    // Email existence check state
    let emailStatus = {
        checked: false,
        exists: false,
        email: ''
    };
    let emailCheckTimeout = null;

    async function checkEmailExists(email) {
        email = email.trim().toLowerCase();
        const feedback = document.getElementById('reg_email_feedback');
        const input = document.getElementById('reg_email');
        if (!feedback || !input) return true;

        if (!email || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
            feedback.textContent = 'Format email tidak valid.';
            feedback.className = 'text-[11px] text-red-500 font-semibold mt-1 block';
            input.classList.add('border-red-500');
            emailStatus = { checked: true, exists: true, email: email };
            return false;
        }

        try {
            feedback.textContent = 'Memeriksa email...';
            feedback.className = 'text-[11px] text-slate-400 font-semibold mt-1 block';

            const res = await fetch('/auth/check-email?email=' + encodeURIComponent(email));
            const data = await res.json();

            if (data.exists) {
                feedback.textContent = 'Email ini sudah terdaftar. Silakan login atau gunakan email lain.';
                feedback.className = 'text-[11px] text-red-500 font-semibold mt-1 block';
                input.classList.add('border-red-500');
                input.classList.remove('border-emerald-500');
                emailStatus = { checked: true, exists: true, email: email };
                return false;
            } else {
                feedback.textContent = 'Email dapat digunakan.';
                feedback.className = 'text-[11px] text-emerald-600 font-semibold mt-1 block';
                input.classList.remove('border-red-500');
                input.classList.add('border-emerald-500');
                emailStatus = { checked: true, exists: false, email: email };
                return true;
            }
        } catch (err) {
            feedback.classList.add('hidden');
            input.classList.remove('border-red-500');
            emailStatus = { checked: true, exists: false, email: email };
            return true;
        }
    }

    document.addEventListener("DOMContentLoaded", function() {
        if (window.lucide) {
            lucide.createIcons();
        }

        const regPw = document.getElementById('reg_password');
        const regPwConfirm = document.getElementById('reg_password_confirm');
        const regEmail = document.getElementById('reg_email');
        const regForm = document.getElementById('registerForm');

        if (regPw) {
            regPw.addEventListener('input', validatePasswordMatch);
        }
        if (regPwConfirm) {
            regPwConfirm.addEventListener('input', validatePasswordMatch);
        }

        if (regEmail) {
            regEmail.addEventListener('blur', function() {
                checkEmailExists(this.value);
            });
            regEmail.addEventListener('input', function() {
                clearTimeout(emailCheckTimeout);
                const val = this.value;
                emailStatus.checked = false;
                emailCheckTimeout = setTimeout(function() {
                    if (val.length > 4) {
                        checkEmailExists(val);
                    }
                }, 600);
            });
        }

        if (regForm) {
            regForm.addEventListener('submit', async function(e) {
                const pwValid = validatePasswordMatch();
                const pw = document.getElementById('reg_password').value;
                const pwConfirm = document.getElementById('reg_password_confirm').value;

                if (pw.length < 6) {
                    e.preventDefault();
                    document.getElementById('reg_password').focus();
                    validatePasswordMatch();
                    return;
                }

                if (pw !== pwConfirm) {
                    e.preventDefault();
                    document.getElementById('reg_password_confirm').focus();
                    validatePasswordMatch();
                    return;
                }

                const emailVal = regEmail.value.trim().toLowerCase();
                // Jika belum dicek atau email berubah, cek langsung sekarang
                if (!emailStatus.checked || emailStatus.email !== emailVal) {
                    e.preventDefault();
                    const available = await checkEmailExists(emailVal);
                    if (available) {
                        regForm.submit();
                    } else {
                        regEmail.focus();
                    }
                    return;
                }

                if (emailStatus.exists) {
                    e.preventDefault();
                    regEmail.focus();
                    checkEmailExists(emailVal);
                    return;
                }
            });
        }
    });
</script>

<?= $this->endSection(); ?>
