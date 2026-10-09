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
    <!-- Flash Messages -->
    <?php if (session()->getFlashdata('error')): ?>
        <div class="max-w-xl mx-auto mb-6 p-4 rounded-xl bg-red-50 border border-red-200 text-red-700 text-xs sm:text-sm font-semibold flex items-center gap-2">
            <i data-lucide="alert-circle" class="size-4 shrink-0 text-red-500"></i>
            <span><?= esc(session()->getFlashdata('error')) ?></span>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="max-w-xl mx-auto mb-6 p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs sm:text-sm font-semibold flex items-center gap-2">
            <i data-lucide="check-circle-2" class="size-4 shrink-0 text-emerald-500"></i>
            <span><?= esc(session()->getFlashdata('success')) ?></span>
        </div>
    <?php endif; ?>

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

        <?php if ($alreadyEnrolled && !empty($currentEnrollment)): ?>
            <?php $eStatus = $currentEnrollment['status'] ?? 'waiting'; ?>

            <?php if ($eStatus === 'waiting' || $eStatus === 'enrolled'): ?>
                <!-- Status Waiting: Menunggu Verifikasi Admin -->
                <div class="max-w-xl mx-auto my-8 p-6 md:p-8 text-center rounded-2xl bg-amber-50/80 border border-amber-200/90 shadow-sm">
                    <div class="inline-flex size-12 rounded-full bg-amber-100 text-amber-600 items-center justify-center mb-3">
                        <i data-lucide="clock" class="size-6"></i>
                    </div>
                    <h3 class="text-base sm:text-lg font-bold text-amber-950">Menunggu Verifikasi Admin Terlebih Dahulu</h3>
                    <p class="text-xs sm:text-sm text-amber-800/90 max-w-md mx-auto mt-1.5 leading-relaxed">
                        Pendaftaran Anda sedang dalam antrean verifikasi oleh tim admin Datasatu VLC (estimasi batas peninjauan 1–3 hari kerja). Konfirmasi pendaftaran akan dikirimkan ke email dan nomor WhatsApp Anda.
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
                <div class="max-w-xl mx-auto my-8 p-6 md:p-8 text-center rounded-2xl bg-sky-50/90 border border-sky-200 shadow-sm">
                    <div class="inline-flex size-12 rounded-full bg-sky-100 text-sky-600 items-center justify-center mb-3">
                        <i data-lucide="message-circle" class="size-6"></i>
                    </div>
                    <h3 class="text-base sm:text-lg font-bold text-sky-950">Pendaftaran Sedang Ditindaklanjuti</h3>
                    <p class="text-xs sm:text-sm text-sky-800/90 max-w-md mx-auto mt-1.5 leading-relaxed">
                        Tim admin kami sedang menghubungi Anda melalui WhatsApp untuk koordinasi jadwal kelas dan rincian administratif.
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
        <!-- State: User belum login - tampilkan CTA button dan notes -->
        <div class="max-w-xl mx-auto my-10 p-8 text-center rounded-2xl bg-white border border-slate-200 shadow-sm">
            <div class="flex flex-col items-center justify-center">
                <a href="/login?program_id=<?= esc($program['id'] ?? '') ?>"
                   class="inline-block bg-[#FF8D28] hover:bg-[#e07212] text-white text-base font-bold py-3.5 px-14 rounded-full shadow-md hover:shadow-lg transition transform active:scale-95">
                    Daftar Sekarang
                </a>
                <p class="text-xs text-slate-500 mt-3.5 font-medium">
                    Silahkan login menggunakan akun Datasatu, atau buat akun baru sekarang!*
                </p>
            </div>
        </div>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/lucide@0.468.0/dist/umd/lucide.min.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        if (window.lucide) {
            lucide.createIcons();
        }
    });
</script>


<?= $this->endSection(); ?>