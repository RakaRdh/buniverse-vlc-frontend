<?= $this->extend('layout/template') ?>
<?= $this->section('content') ?>

<!-- HERO SECTION -->
<section id="home" class="relative bg-brand pt-[7rem] lg:pt-[9rem] pb-20 lg:pb-[8rem] text-white overflow-hidden min-h-[480px] sm:min-h-[560px] lg:min-h-[620px]">
    <!-- Background photo (hero-bg.webp) -->
    <div class="absolute inset-0 bg-brand">
        <img src="/img/hero-bg.webp" alt=""
             class="w-full h-full object-cover object-center"
             onerror="this.style.display='none'">
    </div>

    <div class="max-w-[1200px] mx-auto px-4 lg:px-10 flex flex-col lg:flex-row items-center gap-10 relative z-10">
        <div class="flex-1 text-center lg:text-left">
            <h1 class="text-3xl lg:text-4xl font-extrabold leading-tight mb-4">Learn Today, Lead Tomorrow</h1>
            <p class="text-white/90 mb-6 max-w-md mx-auto lg:mx-0 text-sm lg:text-base leading-relaxed">
                Kembangkan skill yang relevan dengan industri dan jadilah lebih siap menghadapi peluang serta tantangan karier di masa depan.
            </p>
            <a href="/register" class="inline-block bg-accent hover:bg-accent-dark text-white font-bold px-8 py-3 rounded-full transition shadow-md hover:shadow-lg">Sign Up</a>
        </div>

        <div class="flex-1 relative w-full max-w-xs sm:max-w-sm lg:max-w-md mx-auto mt-6 lg:mt-0">
            <!-- People illustration -->
            <div class="relative overflow-hidden aspect-square">
                <img src="/img/hero-right.webp" alt="Learn Today, Lead Tomorrow"
                     class="w-full h-full object-center object-contain"
                     onerror="this.style.display='none'">
            </div>
        </div>
    </div>
</section>

<!-- ABOUT / GALLERY SECTION (DYNAMIC FROM DB) -->
<section id="about" class="max-w-[1200px] mx-auto px-4 lg:px-10 pt-10 pb-16 relative">
    <!-- decorative coral ring shape -->
    <div class="hidden lg:block absolute -right-10 top-16 w-28 h-28 rounded-full bg-[#F2836E]">
        <div class="absolute top-3 right-3 w-11 h-11 rounded-full bg-white"></div>
    </div>

    <div class="grid lg:grid-cols-2 gap-10 lg:gap-16 items-center">
        <div class="relative">
            <!-- decorative play-triangle shape -->
            <svg class="hidden lg:block absolute -top-10 right-10 w-14 h-14 -rotate-[15deg] z-10" viewBox="0 0 100 100" fill="none">
                <path d="M20 10 L85 50 L20 90 Z" stroke="#F2836E" stroke-width="11" stroke-linejoin="round" stroke-linecap="round"/>
            </svg>

            <!-- Dynamic 3 Images Accordion Gallery -->
            <div class="flex gap-3 h-80 lg:h-[420px]">
                <?php if (!empty($galleries)): ?>
                    <?php foreach ($galleries as $idx => $g): ?>
                        <div class="<?= ($idx === count($galleries) - 1) ? 'flex-[2]' : 'flex-1' ?> hover:flex-[2] transition-[flex-grow] duration-500 ease-in-out rounded-2xl bg-gray-200 overflow-hidden shadow-md">
                            <img src="<?= esc($g['image']) ?>" alt="<?= esc($g['title'] ?? 'Galeri VLC') ?>" class="w-full h-full object-cover" onerror="this.src='/img/gallery-1.webp'">
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="flex-1 hover:flex-[2] transition-[flex-grow] duration-500 ease-in-out rounded-2xl bg-gray-200 overflow-hidden">
                        <img src="/img/gallery-1.webp" alt="" class="w-full h-full object-cover">
                    </div>
                    <div class="flex-1 hover:flex-[2] transition-[flex-grow] duration-500 ease-in-out rounded-2xl bg-gray-200 overflow-hidden">
                        <img src="/img/gallery-2.webp" alt="" class="w-full h-full object-cover">
                    </div>
                    <div class="flex-[2] hover:flex-[2] transition-[flex-grow] duration-500 ease-in-out rounded-2xl bg-gray-200 overflow-hidden">
                        <img src="/img/gallery-3.webp" alt="" class="w-full h-full object-cover">
                    </div>
                <?php endif; ?>
            </div>

            <!-- decorative coral cross shape -->
            <div class="hidden lg:block absolute -left-6 -bottom-6 w-14 h-14 rotate-12 z-10">
                <div class="absolute inset-y-0 left-1/2 -translate-x-1/2 w-5 rounded-full bg-[#F2836E]"></div>
                <div class="absolute inset-x-0 top-1/2 -translate-y-1/2 h-5 rounded-full bg-[#F2836E]"></div>
            </div>
        </div>
        <div>
            <h2 class="text-xl lg:text-3xl font-extrabold mb-4 text-slate-900 leading-snug">Apa Itu Vocational Learning Center?</h2>
            <p class="text-gray-600 leading-relaxed text-sm lg:text-base">
                Pusat pembelajaran berbasis keterampilan untuk mengembangkan kompetensi yang relevan dengan kebutuhan dunia kerja. Vocational Learning Center Data Satu didukung mentor berpengalaman, materi aplikatif, dan pembelajaran yang dirancang untuk membantu peserta meningkatkan skill serta membuka peluang karier yang lebih luas.
            </p>
        </div>
    </div>
</section>

<!-- COURSES SECTION (CENTERED, MAX 3, BEAUTIFUL FULL BG) -->
<section id="courses" class="relative bg-brand py-16 lg:py-24 text-white text-center overflow-hidden min-h-[580px] flex flex-col justify-center">
    <!-- Course Background (course-bg.webp) -->
    <div class="absolute inset-0 bg-[#C41E24]">
        <img src="/img/course-bg.webp" alt=""
             class="w-full h-full object-cover object-center"
             onerror="this.style.display='none'">
    </div>

    <div class="relative max-w-[1200px] mx-auto px-4 z-10 w-full">
        <div class="max-w-2xl mx-auto mb-10">
            <h2 class="text-2xl lg:text-3xl font-extrabold mb-3">Pilih Kelas Sesuai Minat dan Kebutuhanmu</h2>
            <p class="text-white/90 text-sm leading-relaxed">
                Temukan berbagai pilihan pelatihan dengan topik yang relevan dan aplikatif. Setiap bulan, tersedia kelas dengan beragam tema yang dapat kamu pilih sesuai kebutuhan pengembangan diri dan karier. Pelatihan dapat diikuti secara fleksibel, baik <strong>offline maupun online melalui format hybrid.</strong>
            </p>
        </div>

        <!-- Centered Horizontal Flex Container for 1, 2, or 3 cards -->
        <div class="flex flex-wrap justify-center gap-6 max-w-5xl mx-auto items-stretch">
            <?php if (empty($courses)): ?>
                <p class="text-white/80 text-sm w-full py-8">Belum ada kelas tersedia saat ini.</p>
            <?php endif; ?>
            <?php foreach ($courses as $course): ?>
                <div class="w-full sm:w-[320px] md:w-[340px] bg-white text-gray-800 rounded-2xl overflow-hidden text-left shadow-xl flex flex-col justify-between transition-transform duration-300 hover:-translate-y-1">
                    <div>
                        <!-- Thumbnail Header -->
                        <div class="h-44 bg-gray-200 relative overflow-hidden">
                            <img src="<?= esc($course['image'] ?? '/img/img-course-1.webp') ?>" alt="<?= esc($course['title']) ?>"
                                 class="w-full h-full object-cover" onerror="this.src='/img/img-course-1.webp'">
                        </div>

                        <!-- Card Body -->
                        <div class="p-5 pb-4">
                            <h3 class="font-bold text-base mb-2 text-slate-900 leading-snug"><?= esc($course['title']) ?></h3>
                            <ul class="text-xs text-gray-600 space-y-1.5 mb-4 list-disc list-inside">
                                <li>Durasi: <?= esc($course['duration'] ?? '3 Hari') ?></li>
                                <?php if (!empty($course['modules_count'])): ?><li><?= (int) $course['modules_count'] ?> modul</li><?php endif; ?>
                                <?php if (!empty($course['has_certificate'])): ?><li>Sertifikat Pelatihan</li><?php endif; ?>
                            </ul>
                        </div>
                    </div>

                    <!-- Button Section -->
                    <div class="p-5 pt-0">
                        <a href="/programs/detail/<?= esc($course['slug']) ?>"
                           class="block w-full text-center bg-[#FF8D28] hover:bg-[#e07212] text-white font-bold py-3 rounded-full shadow-sm transition">
                           Daftar Kelas
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- PLAY DIVIDER -->
<div class="flex justify-center my-10">
    <div class="w-14 h-14 rounded-full bg-accent/10 flex items-center justify-center">
        <div class="w-0 h-0 border-t-[10px] border-t-transparent border-b-[10px] border-b-transparent border-l-[16px] border-l-accent ml-1"></div>
    </div>
</div>

<!-- FAQ SECTION (DYNAMIC FROM DB) -->
<section id="faq" class="max-w-[900px] mx-auto px-4 pb-16 relative">
    <!-- decorative shapes -->
    <div class="hidden lg:block absolute -left-6 bottom-4 w-16 h-16 bg-accent/40 rounded-2xl -rotate-12"></div>
    <div class="hidden lg:block absolute -right-6 bottom-10 w-20 h-20 rounded-full border-[10px] border-accent/30"></div>

    <div class="bg-accent/10 rounded-3xl p-6 lg:p-12">
        <h2 class="text-center text-2xl lg:text-3xl font-extrabold mb-10 text-gray-900">Paling Sering Ditanyakan</h2>
        <div class="space-y-4">
            <?php if (!empty($faqs)): ?>
                <?php foreach ($faqs as $f): ?>
                    <details class="bg-accent text-white rounded-2xl px-5 py-4 group shadow-sm">
                        <summary class="cursor-pointer font-bold flex items-center justify-between list-none text-sm lg:text-base">
                            <span><?= esc($f['question']) ?></span>
                            <span class="ml-4 text-xl font-bold leading-none group-open:rotate-45 transition-transform">+</span>
                        </summary>
                        <p class="text-sm text-white/95 mt-3 leading-relaxed"><?= esc($f['answer']) ?></p>
                    </details>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</section>

<?= $this->endSection() ?>