<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>
<section class="relative bg-white font-poppins bg-[url('/img/header-about.webp')] bg-cover bg-no-repeat">
    <div class="lg:pt-[80px] pt-[80px] lg:pb-[56px] pb-[56px] lg:px-[80px] px-[16px]">
        <div class="lg:max-w-[1440px] w-full mx-auto flex flex-col justify-center items-start">
            <div class="flex flex-row justify-center items-center text-white gap-[8px] h-[72px] lg:text-[16px] text-[12px]">
                <a href="/"><p class="font-normal">Home</p></a>
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M10.045 7.28526L6.27556 3.51333L5.33337 4.45615L8.63168 7.75667L5.33337 11.0572L6.27556 12L10.045 8.22807C10.1699 8.10304 10.24 7.93347 10.24 7.75667C10.24 7.57986 10.1699 7.4103 10.045 7.28526Z" fill="white"/>
                </svg>
                <p class="font-semibold">Programs</p>
            </div>
            <div class="lg:max-w-[850px] flex flex-col justify-center lg:items-center items-start text-white gap-[24px] lg:h-[104px] mx-auto">
                <p class="lg:text-[40px] text-[20px] font-bold text-center">Katalog Program Vokasi</p>
            </div>
        </div>
    </div>
</section>

<section class="relative bg-white font-poppins lg:px-[80px] px-[16px] lg:pt-[88px] pt-[40px] lg:pb-[120px] pb-[40px]">
    <div class="lg:max-w-[1440px] w-full mx-auto">
        <?php if (empty($programs)): ?>
            <div class="text-center py-16 text-gray-500">
                <p>Belum ada program pelatihan aktif saat ini.</p>
            </div>
        <?php else: ?>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                <?php foreach ($programs as $prog): ?>
                    <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden shadow-sm hover:shadow-lg transition duration-200 flex flex-col justify-between">
                        <div>
                            <div class="h-48 bg-gray-100 overflow-hidden relative">
                                <img src="<?= esc($prog['image'] ?: '/img/img-course-1.webp') ?>" alt="<?= esc($prog['name']) ?>" class="w-full h-full object-cover">
                                <?php if (!empty($prog['has_certificate'])): ?>
                                    <span class="absolute top-3 right-3 bg-brand text-white text-[11px] font-semibold px-3 py-1 rounded-full shadow">
                                        Bersertifikat
                                    </span>
                                <?php endif; ?>
                            </div>
                            <div class="p-6">
                                <h3 class="font-bold text-lg text-gray-900 mb-2 leading-snug"><?= esc($prog['name']) ?></h3>
                                <p class="text-xs text-gray-600 line-clamp-3 leading-relaxed mb-4">
                                    <?= esc($prog['short_desc'] ?? $prog['description']) ?>
                                </p>
                                <div class="flex items-center gap-3 text-xs text-gray-500 pt-2 border-t border-gray-100">
                                    <span><?= esc($prog['duration'] ?? 'Fleksibel') ?></span>
                                    <span>•</span>
                                    <span><?= esc($prog['schedule_info'] ?? 'Hybrid') ?></span>
                                </div>
                            </div>
                        </div>
                        <div class="p-6 pt-0">
                            <a href="/programs/programs_detail?id=<?= $prog['id'] ?>"
                               class="block w-full text-center bg-[#F5841F] hover:bg-[#e07212] text-white font-semibold py-3 rounded-full transition">
                               Lihat Detail & Daftar
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>
<?= $this->endSection(); ?>