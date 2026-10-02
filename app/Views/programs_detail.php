<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>

<!-- Hero Top Header -->
<section class="relative bg-brand text-white pt-12 pb-16 overflow-hidden">
    <div class="absolute inset-0 opacity-15 pointer-events-none bg-[radial-gradient(#fff_1px,transparent_1px)] [background-size:16px_16px]"></div>
    
    <div class="max-w-[1440px] mx-auto px-4 lg:px-10 relative z-10">
        <!-- Breadcrumbs -->
        <div class="flex items-center gap-2 text-xs sm:text-sm text-white/80 mb-6">
            <a href="/" class="hover:underline">Home</a>
            <span>/</span>
            <a href="/#courses" class="hover:underline">Programs</a>
            <span>/</span>
            <span class="text-white font-semibold truncate"><?= esc($program['name'] ?? 'Detail Program') ?></span>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">
            <!-- Left Info -->
            <div class="lg:col-span-7 space-y-4">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-white/15 text-xs font-semibold tracking-wide">
                    <svg class="size-3.5" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-3z" /></svg>
                    Datasatu Vocational Learning Center
                </span>
                
                <h1 class="text-2xl sm:text-4xl font-extrabold tracking-tight leading-tight">
                    <?= esc($program['name'] ?? 'ESGRC (Governance, Risk, and Compliance)') ?>
                </h1>

                <p class="text-sm sm:text-base text-white/90 leading-relaxed max-w-2xl">
                    <?= esc($program['short_desc'] ?? $program['description'] ?? '') ?>
                </p>

                <!-- Key Attributes -->
                <div class="flex flex-wrap items-center gap-4 pt-2 text-xs sm:text-sm text-white/95">
                    <div class="flex items-center gap-1.5 bg-black/20 px-3 py-1.5 rounded-lg backdrop-blur-xs">
                        <svg class="size-4 text-accent" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
                        <span><?= $program['modules_count'] ?? count($program['modules'] ?? []) ?> Modul Pelatihan</span>
                    </div>

                    <?php if (!empty($program['duration'])): ?>
                        <div class="flex items-center gap-1.5 bg-black/20 px-3 py-1.5 rounded-lg backdrop-blur-xs">
                            <svg class="size-4 text-accent" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10" stroke-width="2"/><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6l4 2"/></svg>
                            <span><?= esc($program['duration']) ?></span>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($program['has_certificate'])): ?>
                        <div class="flex items-center gap-1.5 bg-black/20 px-3 py-1.5 rounded-lg backdrop-blur-xs">
                            <svg class="size-4 text-emerald-300" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span>Sertifikat Resmi</span>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- CTA Action Button -->
                <div class="pt-4">
                    <?php if ($isEnrolled): ?>
                        <div class="inline-flex items-center gap-2 bg-emerald-500 text-white font-semibold px-6 py-3 rounded-full shadow-lg">
                            <svg class="size-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                            <span>Anda Sudah Terdaftar di Kelas Ini</span>
                        </div>
                    <?php else: ?>
                        <?php if ($memberId): ?>
                            <!-- Logged In: One-Click Enroll -->
                            <form action="/programs/enroll/<?= $program['id'] ?>" method="POST" class="inline-block">
                                <?= csrf_field() ?>
                                <button type="submit" class="bg-[#F5841F] hover:bg-[#e07212] text-white font-bold px-8 py-3.5 rounded-full shadow-lg hover:shadow-xl transition transform active:scale-95 text-base flex items-center gap-2">
                                    <span>Daftar Kelas Sekarang</span>
                                    <svg class="size-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                </button>
                            </form>
                        <?php else: ?>
                            <!-- Not Logged In: Redirect to Login/Register with program context -->
                            <a href="/register?program_id=<?= $program['id'] ?>" class="inline-flex items-center gap-2 bg-[#F5841F] hover:bg-[#e07212] text-white font-bold px-8 py-3.5 rounded-full shadow-lg hover:shadow-xl transition transform active:scale-95 text-base">
                                <span>Daftar Kelas Sekarang</span>
                                <svg class="size-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                            </a>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Right Image Card -->
            <div class="lg:col-span-5">
                <div class="rounded-2xl overflow-hidden shadow-2xl border-4 border-white/20 bg-white">
                    <img src="<?= esc($program['image'] ?: '/img/img-course-1.webp') ?>" alt="<?= esc($program['name']) ?>" class="w-full object-cover">
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Program Details & Curriculum -->
<section class="max-w-[1440px] mx-auto px-4 lg:px-10 py-12">
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">
        <!-- Syllabus & Description (8 Cols) -->
        <div class="lg:col-span-8 space-y-8">
            <!-- About Course -->
            <div>
                <h2 class="text-xl sm:text-2xl font-bold text-gray-900 mb-4 pb-2 border-b border-gray-200">
                    Tentang Program
                </h2>
                <div class="text-sm sm:text-base text-gray-700 leading-relaxed space-y-4">
                    <p><?= nl2br(esc($program['description'] ?? '')) ?></p>
                </div>
            </div>

            <!-- Curriculum Modules -->
            <div>
                <h2 class="text-xl sm:text-2xl font-bold text-gray-900 mb-4 pb-2 border-b border-gray-200">
                    Kurikulum & Modul Pembelajaran
                </h2>

                <?php if (empty($program['modules'])): ?>
                    <p class="text-sm text-gray-500 py-4">Modul pelatihan sedang disusun oleh instruktur.</p>
                <?php else: ?>
                    <div class="space-y-3">
                        <?php foreach ($program['modules'] as $idx => $mod): ?>
                            <div class="rounded-xl border border-gray-200 bg-white p-4 hover:border-brand/40 transition">
                                <div class="flex items-center justify-between gap-4">
                                    <div class="flex items-center gap-3 min-w-0">
                                        <span class="size-8 rounded-full bg-brand/10 text-brand font-bold text-xs flex items-center justify-center shrink-0">
                                            <?= $idx + 1 ?>
                                        </span>
                                        <div class="min-w-0">
                                            <h4 class="text-sm font-semibold text-gray-900 truncate"><?= esc($mod['title']) ?></h4>
                                            <?php if (!empty($mod['description'])): ?>
                                                <p class="text-xs text-gray-500 mt-0.5 truncate"><?= esc($mod['description']) ?></p>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-3 shrink-0 text-xs text-gray-500">
                                        <?php if (!empty($mod['duration_minutes'])): ?>
                                            <span><?= (int)$mod['duration_minutes'] ?> menit</span>
                                        <?php endif; ?>
                                        <?php if (!empty($mod['has_video'])): ?>
                                            <span class="text-brand font-medium flex items-center gap-1">
                                                <svg class="size-3.5" viewBox="0 0 24 24" fill="currentColor"><path d="M8 5v14l11-7z"/></svg>
                                                Video
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Sidebar Summary Card (4 Cols) -->
        <div class="lg:col-span-4">
            <div class="sticky top-24 rounded-2xl border border-gray-200 bg-gray-50 p-6 shadow-sm space-y-6">
                <div>
                    <span class="text-xs text-gray-500 uppercase font-semibold">Investasi Program</span>
                    <div class="text-2xl font-extrabold text-brand mt-1">
                        <?= ($program['price'] > 0) ? 'Rp ' . number_format($program['price'], 0, ',', '.') : 'Gratis / Beasiswa' ?>
                    </div>
                </div>

                <div class="space-y-3 border-t border-gray-200 pt-4 text-xs sm:text-sm text-gray-700">
                    <div class="flex items-center justify-between">
                        <span class="text-gray-500">Kapasitas Kelas</span>
                        <span class="font-medium"><?= $program['max_participants'] ?? 50 ?> Peserta</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-gray-500">Metode Belajar</span>
                        <span class="font-medium"><?= esc($program['schedule_info'] ?? 'Hybrid') ?></span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-gray-500">Sertifikat</span>
                        <span class="font-medium"><?= !empty($program['has_certificate']) ? 'Ya' : 'Tidak' ?></span>
                    </div>
                </div>

                <div class="pt-2">
                    <?php if ($isEnrolled): ?>
                        <div class="w-full text-center bg-emerald-100 text-emerald-800 text-xs font-semibold py-3 rounded-full">
                            Anda Sudah Terdaftar
                        </div>
                    <?php else: ?>
                        <?php if ($memberId): ?>
                            <form action="/programs/enroll/<?= $program['id'] ?>" method="POST">
                                <?= csrf_field() ?>
                                <button type="submit" class="w-full text-center bg-[#F5841F] hover:bg-[#e07212] text-white font-semibold py-3 rounded-full shadow transition">
                                    Daftar Kelas
                                </button>
                            </form>
                        <?php else: ?>
                            <a href="/register?program_id=<?= $program['id'] ?>" class="block w-full text-center bg-[#F5841F] hover:bg-[#e07212] text-white font-semibold py-3 rounded-full shadow transition">
                                Daftar Kelas
                            </a>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>

<?= $this->endSection(); ?>