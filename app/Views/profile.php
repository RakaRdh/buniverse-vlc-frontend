<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>

<!-- Banner Section -->
<section class="relative bg-white font-poppins bg-[url('/img/header-about.webp')] bg-cover bg-top bg-no-repeat">
    <div class="pt-[60px] pb-[50px] lg:px-[80px] px-[16px]">
        <div class="max-w-[1240px] mx-auto flex flex-col justify-center items-start">
            <div class="flex flex-row items-center text-white gap-2 text-xs lg:text-sm mb-4">
                <a href="/" class="hover:underline opacity-90">Home</a>
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 16 16" fill="none">
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M10.045 7.28526L6.27556 3.51333L5.33337 4.45615L8.63168 7.75667L5.33337 11.0572L6.27556 12L10.045 8.22807C10.1699 8.10304 10.24 7.93347 10.24 7.75667C10.24 7.57986 10.1699 7.4103 10.045 7.28526Z" fill="white"/>
                </svg>
                <span class="font-semibold">Profil Peserta</span>
            </div>
            
            <div class="flex flex-col sm:flex-row sm:items-center gap-4 text-white">
                <div class="size-16 rounded-full bg-white text-[#C41E24] flex items-center justify-center font-black text-2xl shadow-lg border-2 border-white/40">
                    <?= esc(strtoupper(substr($member['fullname'] ?? 'P', 0, 1))) ?>
                </div>
                <div>
                    <h1 class="text-2xl lg:text-3xl font-bold tracking-tight"><?= esc($member['fullname'] ?? 'Peserta VLC') ?></h1>
                    <p class="text-xs lg:text-sm text-white/85 mt-0.5">
                        <?= esc($member['email']) ?> &bull; Member sejak <?= esc(substr($member['signupdate'] ?? date('Y-m-d'), 0, 10)) ?>
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Main Content -->
<section class="py-10 lg:py-14 bg-slate-50 font-poppins min-h-[500px]">
    <div class="max-w-[1240px] mx-auto px-4 lg:px-8">
        
        <!-- Flash Alerts -->
        <?php if (session()->getFlashdata('error')): ?>
            <div class="mb-6 flex items-center gap-3 rounded-xl border border-red-500/20 bg-red-50 p-4 text-xs lg:text-sm text-red-600 shadow-xs">
                <svg class="size-5 shrink-0 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span><?= esc(session()->getFlashdata('error')) ?></span>
            </div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('success')): ?>
            <div class="mb-6 flex items-center gap-3 rounded-xl border border-emerald-500/20 bg-emerald-50 p-4 text-xs lg:text-sm text-emerald-700 shadow-xs">
                <svg class="size-5 shrink-0 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span><?= esc(session()->getFlashdata('success')) ?></span>
            </div>
        <?php endif; ?>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            
            <!-- Left Column: Status Kelas yang Di-apply (7 Cols) -->
            <div class="lg:col-span-7 space-y-6">
                <div class="bg-white rounded-2xl border border-slate-200/80 p-6 shadow-sm">
                    <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                        <div>
                            <h2 class="text-base lg:text-lg font-bold text-slate-900">Status Kursus yang Diikuti</h2>
                            <p class="text-xs text-slate-500 mt-0.5">Pantau status tindak lanjut & progres kelas pelatihan Anda.</p>
                        </div>
                        <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-700">
                            <?= count($enrollments) ?> Program
                        </span>
                    </div>

                    <?php if (empty($enrollments)): ?>
                        <div class="py-12 text-center text-slate-400">
                            <svg class="size-12 mx-auto mb-3 opacity-40 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                            </svg>
                            <p class="text-xs lg:text-sm font-medium text-slate-600">Anda belum mendaftar di kelas pelatihan manapun.</p>
                            <a href="/#courses" class="inline-flex items-center gap-2 mt-4 px-4 py-2 rounded-xl bg-[#C41E24] text-white text-xs font-bold shadow-sm hover:bg-[#A8151A] transition">
                                Jelajahi Program Pelatihan &rarr;
                            </a>
                        </div>
                    <?php else: ?>
                        <div class="space-y-4 mt-5">
                            <?php foreach ($enrollments as $en): ?>
                                <?php
                                    $statusBadge = match($en['status']) {
                                        'finished'    => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                        'in_progress' => 'bg-indigo-50 text-indigo-700 border-indigo-200',
                                        'contacted'   => 'bg-sky-50 text-sky-700 border-sky-200',
                                        default       => 'bg-amber-50 text-amber-700 border-amber-200'
                                    };
                                    $statusTitle = match($en['status']) {
                                        'finished'    => 'Finished (Selesai)',
                                        'in_progress' => 'In Progress (Sedang Belajar)',
                                        'contacted'   => 'Contacted (Sudah Dihubungi)',
                                        default       => 'Enrolled (Pendaftaran Diterima)'
                                    };
                                    $statusDesc = match($en['status']) {
                                        'finished'    => 'Selamat! Anda telah menyelesaikan seluruh rangkaian materi kelas ini.',
                                        'in_progress' => 'Kelas sedang berlangsung. Silakan ikuti sesi materi sesuai jadwal.',
                                        'contacted'   => 'Admin VLC telah menghubungi Anda via WhatsApp/Telepon. Silakan tunggu pembukaan kelas.',
                                        default       => 'Pendaftaran terkirim! Tim kami akan segera menghubungi nomor telepon Anda untuk konfirmasi jadwal & info kelas.'
                                    };
                                ?>
                                <div class="rounded-xl border border-slate-200 p-4 sm:p-5 hover:border-slate-300 transition-colors bg-white">
                                    <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-3 mb-3">
                                        <div>
                                            <h3 class="text-sm lg:text-base font-bold text-slate-900 leading-snug">
                                                <?= esc($en['program_name']) ?>
                                            </h3>
                                            <?php if (!empty($en['batch_info'])): ?>
                                                <span class="inline-block mt-1 text-[11px] font-medium text-slate-500 bg-slate-100 px-2 py-0.5 rounded">
                                                    <?= esc($en['batch_info']) ?>
                                                </span>
                                            <?php endif; ?>
                                        </div>
                                        <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-bold border <?= $statusBadge ?> shrink-0 self-start">
                                            <?= esc($statusTitle) ?>
                                        </span>
                                    </div>

                                    <!-- Status Message Alert Box -->
                                    <div class="rounded-lg bg-slate-50 border border-slate-100 p-3 text-xs text-slate-600 flex items-start gap-2.5">
                                        <svg class="size-4 shrink-0 text-[#C41E24] mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <p class="leading-relaxed"><?= esc($statusDesc) ?></p>
                                    </div>

                                    <div class="mt-3.5 pt-3 border-t border-slate-100 flex items-center justify-between text-[11px] text-slate-500">
                                        <span>Terdaftar pada: <strong class="text-slate-700"><?= esc(substr($en['enrolled_at'] ?? $en['created_at'], 0, 16)) ?></strong></span>
                                        <a href="/programs/programs_detail?id=<?= $en['program_id'] ?>" class="font-bold text-[#C41E24] hover:underline">
                                            Lihat Detail Silabus &rarr;
                                        </a>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Right Column: Form Pelengkap Profil (5 Cols) -->
            <div class="lg:col-span-5 space-y-6">
                <div class="bg-white rounded-2xl border border-slate-200/80 p-6 shadow-sm">
                    <div class="pb-4 border-b border-slate-100 mb-5">
                        <h2 class="text-base lg:text-lg font-bold text-slate-900">Lengkapi Data Diri</h2>
                        <p class="text-xs text-slate-500 mt-0.5">Pastikan nomor telepon WhatsApp aktif untuk kemudahan koordinasi kelas.</p>
                    </div>

                    <form action="/profile/update" method="POST" class="space-y-4">
                        <?= csrf_field() ?>

                        <div>
                            <label for="fullname" class="block text-xs font-semibold text-slate-700 mb-1.5">
                                Nama Lengkap <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="fullname" name="fullname" value="<?= esc($member['fullname'] ?? '') ?>" required
                                   class="w-full px-3.5 py-2.5 text-xs lg:text-sm rounded-xl border border-slate-300 bg-white focus:outline-none focus:ring-2 focus:ring-[#C41E24] focus:border-[#C41E24] text-slate-800 transition"
                                   placeholder="Nama lengkap Anda">
                        </div>

                        <div>
                            <label for="email" class="block text-xs font-semibold text-slate-700 mb-1.5">
                                Email <span class="text-slate-400 font-normal">(Akun Utama)</span>
                            </label>
                            <input type="email" id="email" value="<?= esc($member['email']) ?>" readonly
                                   class="w-full px-3.5 py-2.5 text-xs lg:text-sm rounded-xl border border-slate-200 bg-slate-50 text-slate-500 cursor-not-allowed">
                        </div>

                        <div>
                            <label for="phone" class="block text-xs font-semibold text-slate-700 mb-1.5">
                                Nomor WhatsApp / Telepon <span class="text-red-500">*</span>
                            </label>
                            <input type="tel" id="phone" name="phone" value="<?= esc($profile['phone'] ?? '') ?>" required
                                   class="w-full px-3.5 py-2.5 text-xs lg:text-sm rounded-xl border border-slate-300 bg-white focus:outline-none focus:ring-2 focus:ring-[#C41E24] focus:border-[#C41E24] text-slate-800 transition"
                                   placeholder="081234567890">
                        </div>

                        <div>
                            <label for="address" class="block text-xs font-semibold text-slate-700 mb-1.5">
                                Alamat / Domisili
                            </label>
                            <textarea id="address" name="address" rows="3"
                                      class="w-full px-3.5 py-2.5 text-xs lg:text-sm rounded-xl border border-slate-300 bg-white focus:outline-none focus:ring-2 focus:ring-[#C41E24] focus:border-[#C41E24] text-slate-800 transition"
                                      placeholder="Kota domisili atau alamat lengkap Anda"><?= esc($profile['address'] ?? '') ?></textarea>
                        </div>

                        <div class="pt-2">
                            <button type="submit"
                                    class="w-full py-3 px-4 rounded-xl bg-[#C41E24] hover:bg-[#A8151A] text-white text-xs lg:text-sm font-bold shadow-md shadow-red-600/20 transition cursor-pointer flex items-center justify-center gap-2">
                                <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                <span>Simpan Perubahan Profil</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</section>

<?= $this->endSection(); ?>
