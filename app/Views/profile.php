<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>

<!-- Hero Top Curved Banner using header-bg.webp (Organic original wave shape) -->
<section class="relative w-full overflow-hidden text-center text-white min-h-[140px] sm:min-h-[170px] lg:min-h-[200px] flex items-center justify-center">
    <img src="/img/header-bg.webp" alt="" class="absolute inset-0 w-full h-full object-fill object-bottom select-none pointer-events-none">
    <div class="relative z-10 max-w-4xl mx-auto px-4 py-8 sm:py-10">
        <h1 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold tracking-tight drop-shadow-xs">
            Profil Peserta
        </h1>
    </div>
</section>

<?php
/**
 * RELASI DATA PROFIL:
 * Halaman ini membaca data dari tabel terelasi:
 * 1. `tblmember`: Menyimpan kredensial dasar (memberID, fullname, email, password, salt, status, created_at).
 * 2. `tblmember_profile`: Menyimpan data pelengkap profil (phone, address, avatar) dengan foreign key member_id -> tblmember.memberID.
 * 3. `tblprogramenrollment`: Menampilkan daftar kursus/kelas yang diikuti beserta statusnya (enrolled, contacted, in_progress, finished).
 */
?>
<section class="py-10 lg:py-14 bg-slate-50 min-h-[500px]">
    <div class="max-w-5xl mx-auto px-4 lg:px-8">
        
        <?php if (session()->getFlashdata('error')): ?>
            <div class="mb-6 flex items-center gap-3 rounded-2xl border border-red-500/20 bg-red-50 p-4 text-xs lg:text-sm text-red-600 shadow-xs">
                <svg class="size-5 shrink-0 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span><?= esc(session()->getFlashdata('error')) ?></span>
            </div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('success')): ?>
            <div class="mb-6 flex items-center gap-3 rounded-2xl border border-emerald-500/20 bg-emerald-50 p-4 text-xs lg:text-sm text-emerald-700 shadow-xs">
                <svg class="size-5 shrink-0 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span><?= esc(session()->getFlashdata('success')) ?></span>
            </div>
        <?php endif; ?>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            
            <div class="lg:col-span-7 space-y-6">
                <div class="bg-white rounded-3xl border border-slate-200/80 p-6 sm:p-8 shadow-sm">
                    <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                        <div>
                            <h2 class="text-base lg:text-lg font-bold text-slate-900">Status Kursus yang Diikuti</h2>
                            <p class="text-xs text-slate-500 mt-0.5">Pantau konfirmasi dan tindak lanjut kelas oleh admin.</p>
                        </div>
                        <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-bold text-slate-700">
                            <?= count($enrollments) ?> Program
                        </span>
                    </div>

                    <?php if (empty($enrollments)): ?>
                        <div class="py-12 text-center text-slate-400">
                            <svg class="size-12 mx-auto mb-3 opacity-40 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                            </svg>
                            <p class="text-xs lg:text-sm font-medium text-slate-600">Anda belum mendaftar di kelas pelatihan manapun.</p>
                            <a href="/#courses" class="inline-block mt-4 px-6 py-2.5 rounded-full bg-[#FF8D28] hover:bg-[#e07212] text-white text-xs font-bold shadow-sm transition">
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
                                        'contacted'   => 'Admin VLC telah menghubungi nomor telepon Anda. Silakan tunggu jadwal pembukaan kelas.',
                                        default       => 'Pendaftaran terkirim! Tim admin VLC akan segera menghubungi WhatsApp/telepon Anda untuk konfirmasi & info kelas.'
                                    };
                                ?>
                                <div class="rounded-2xl border border-slate-200 p-4 sm:p-5 hover:border-slate-300 transition-colors bg-white">
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
                                    <div class="rounded-xl bg-slate-50 border border-slate-100 p-3 text-xs text-slate-600 flex items-start gap-2.5">
                                        <svg class="size-4 shrink-0 text-[#C41E24] mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <p class="leading-relaxed"><?= esc($statusDesc) ?></p>
                                    </div>

                                    <div class="mt-3.5 pt-3 border-t border-slate-100 flex items-center justify-between text-[11px] text-slate-500">
                                        <span>Terdaftar: <strong class="text-slate-700"><?= esc(substr($en['enrolled_at'] ?? $en['created_at'], 0, 16)) ?></strong></span>
                                        <a href="/programs/detail/<?= esc($en['program_slug']) ?>" class="font-bold text-[#C41E24] hover:underline">
                                            Detail Silabus &rarr;
                                        </a>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Right Column: Form Pelengkap Profil & Avatar Placeholder (5 Cols) -->
            <div class="lg:col-span-5 space-y-6">
                <div class="bg-white rounded-3xl border border-slate-200/80 p-6 sm:p-8 shadow-sm">
                    
                    <!-- Avatar Placeholder Icon & Header -->
                    <div class="text-center pb-6 border-b border-slate-100 mb-6">
                        <div class="size-20 rounded-full bg-slate-100 border-2 border-[#C41E24]/20 flex items-center justify-center text-slate-400 mx-auto mb-3 shadow-xs">
                            <svg class="size-10 text-[#C41E24]" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                            </svg>
                        </div>
                        <h2 class="text-base font-bold text-slate-900"><?= esc($member['fullname'] ?? 'Peserta VLC') ?></h2>
                        <p class="text-xs text-slate-500 mt-0.5"><?= esc($member['email']) ?></p>
                    </div>

                    <form action="/profile/update" method="POST" class="space-y-4">
                        <?= csrf_field() ?>

                        <div>
                            <label for="fullname" class="block text-xs font-bold text-slate-700 mb-1.5">
                                Nama Lengkap <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="fullname" name="fullname" value="<?= esc($member['fullname'] ?? '') ?>" required
                                   class="w-full px-4 py-2.5 text-xs lg:text-sm rounded-full border border-slate-300 bg-white focus:outline-none focus:ring-2 focus:ring-[#C41E24] focus:border-[#C41E24] text-slate-800 transition"
                                   placeholder="Nama lengkap Anda">
                        </div>

                        <div>
                            <label for="email" class="block text-xs font-bold text-slate-700 mb-1.5">
                                Email <span class="text-slate-400 font-normal">(Akun Utama)</span>
                            </label>
                            <input type="email" id="email" value="<?= esc($member['email']) ?>" readonly
                                   class="w-full px-4 py-2.5 text-xs lg:text-sm rounded-full border border-slate-200 bg-slate-50 text-slate-500 cursor-not-allowed">
                        </div>

                        <div>
                            <label for="phone" class="block text-xs font-bold text-slate-700 mb-1.5">
                                Nomor WhatsApp / Telepon <span class="text-red-500">*</span>
                            </label>
                            <input type="tel" id="phone" name="phone" value="<?= esc($profile['phone'] ?? '') ?>" required
                                   class="w-full px-4 py-2.5 text-xs lg:text-sm rounded-full border border-slate-300 bg-white focus:outline-none focus:ring-2 focus:ring-[#C41E24] focus:border-[#C41E24] text-slate-800 transition"
                                   placeholder="081234567890">
                        </div>

                        <div>
                            <label for="address" class="block text-xs font-bold text-slate-700 mb-1.5">
                                Alamat / Domisili
                            </label>
                            <textarea id="address" name="address" rows="3"
                                      class="w-full px-4 py-2.5 text-xs lg:text-sm rounded-2xl border border-slate-300 bg-white focus:outline-none focus:ring-2 focus:ring-[#C41E24] focus:border-[#C41E24] text-slate-800 transition"
                                      placeholder="Kota domisili atau alamat lengkap Anda"><?= esc($profile['address'] ?? '') ?></textarea>
                        </div>

                        <div class="pt-2">
                            <button type="submit"
                                    class="w-full py-3 px-6 rounded-full bg-[#FF8D28] hover:bg-[#e07212] text-white text-xs lg:text-sm font-bold shadow-md transition cursor-pointer flex items-center justify-center gap-2">
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
