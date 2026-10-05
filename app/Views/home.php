<?= $this->extend('layout/template') ?>
<?= $this->section('content') ?>

<section id="home" class="relative bg-brand -mt-[60px] sm:-mt-[68px] lg:-mt-[72px] pt-[84px] sm:pt-[100px] lg:pt-[120px] pb-16 sm:pb-24 lg:pb-32 text-white min-h-[560px] sm:min-h-[620px] lg:min-h-[680px] overflow-hidden">
    <div class="absolute inset-0 bg-brand pointer-events-none select-none overflow-hidden">
        <img src="/img/hero-bg.webp" alt=""
             class="w-full h-full object-cover sm:object-fill object-top"
             onerror="this.style.display='none'">
    </div>

    <div class="max-w-[1200px] mx-auto px-6 sm:px-8 lg:px-10 flex flex-col lg:flex-row items-center gap-6 lg:gap-8 relative z-10">
        <div class="flex-1 text-center lg:text-left pt-2 lg:pt-0 max-w-[280px] xs:max-w-[320px] sm:max-w-md mx-auto lg:mx-0">
            <h1 class="text-xl xs:text-2xl sm:text-3xl lg:text-4xl font-extrabold leading-tight mb-3 sm:mb-4 px-2 sm:px-0">Upgrade Your Skills,<br class="hidden sm:inline"> Elevate Your Career</h1>
            <p class="text-white/90 mb-4 lg:mb-6 text-xs sm:text-sm lg:text-base leading-relaxed">
                Pelajari keterampilan praktis yang relevan dengan kebutuhan industri dan siapkan diri untuk peluang karier yang lebih baik.
            </p>
            <a href="/register" class="hidden lg:inline-block bg-accent hover:bg-accent-dark text-white font-bold px-8 py-3 rounded-full transition shadow-md hover:shadow-lg">Sign Up</a>
        </div>

        <div class="flex-1 relative w-full max-w-[340px] sm:max-w-md lg:max-w-[540px] xl:max-w-[580px] mx-auto z-20 flex flex-col items-center">
            <div class="relative w-full translate-y-3 sm:translate-y-6 lg:translate-y-16 xl:translate-y-20">
                <img src="/img/hero-right.webp" alt="Upgrade Your Skills, Elevate Your Career"
                     class="w-full h-auto object-contain select-none pointer-events-none drop-shadow-xl"
                     onerror="this.style.display='none'">
            </div>
            <div class="lg:hidden mt-6 sm:mt-8 z-30">
                <a href="/register" class="inline-block bg-accent hover:bg-accent-dark text-white font-bold px-12 py-3.5 rounded-full shadow-lg transition transform active:scale-95 text-base">Sign Up</a>
            </div>
        </div>
    </div>
</section>

<section id="about" class="max-w-[1200px] mx-auto px-4 lg:px-10 pt-16 sm:pt-20 lg:pt-28 pb-16 relative overflow-visible">
    <div class="lg:hidden mb-12 relative w-full max-w-[420px] mx-auto">
        <img src="/img/ellipse.webp" alt="" class="absolute -top-12 -right-8 w-32 h-32 object-contain pointer-events-none select-none z-0">

        <div class="relative h-[240px] sm:h-[270px] w-full z-10">
            <?php 
                $img1 = $galleries[0]['image'] ?? '/img/gallery-1.webp';
                $img2 = $galleries[1]['image'] ?? '/img/gallery-2.webp';
                $img3 = $galleries[2]['image'] ?? '/img/gallery-3.webp';
            ?>
            <div class="w-[52%] h-[145px] sm:h-[165px] rounded-2xl overflow-hidden shadow-md absolute top-0 -left-6 bg-gray-200">
                <img src="<?= esc($img1) ?>" alt="VLC Workshop" class="w-full h-full object-cover" onerror="this.src='/img/gallery-1.webp'">
            </div>

            <div class="w-[52%] h-[145px] sm:h-[165px] rounded-2xl overflow-hidden shadow-md absolute top-0 -right-6 bg-gray-200">
                <img src="<?= esc($img3) ?>" alt="VLC Training" class="w-full h-full object-cover" onerror="this.src='/img/gallery-3.webp'">
            </div>

            <div class="w-[84%] max-w-[320px] h-[160px] sm:h-[180px] rounded-2xl overflow-hidden shadow-2xl absolute bottom-0 left-1/2 -translate-x-1/2 z-20 border-[3px] border-white bg-gray-200">
                <img src="<?= esc($img2) ?>" alt="Vocational Learning Center" class="w-full h-full object-cover" onerror="this.src='/img/gallery-2.webp'">
            </div>
        </div>

        <img src="/img/polygon.webp" alt="" class="absolute -bottom-6 -left-6 w-24 h-24 object-contain z-30 pointer-events-none drop-shadow-md">
        <img src="/img/union.webp" alt="" class="absolute -bottom-8 -right-6 w-20 h-20 object-contain z-0 pointer-events-none">
    </div>

    <div class="grid lg:grid-cols-2 gap-10 lg:gap-16 items-center">
        <div class="hidden lg:block relative">
            <img src="/img/polygon.webp" alt="" class="absolute -top-10 right-10 w-14 h-14 -rotate-[15deg] z-10 pointer-events-none">

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

            <img src="/img/union.webp" alt="" class="absolute -left-6 -bottom-6 w-14 h-14 rotate-12 z-10 pointer-events-none">
        </div>

        <div>
            <h2 class="text-xl sm:text-2xl lg:text-3xl font-extrabold mb-4 text-slate-900 leading-snug text-center lg:text-left">Apa Itu Vocational Learning Center?</h2>
            <p class="text-gray-600 leading-relaxed text-xs sm:text-sm lg:text-base text-center lg:text-left">
                Pusat pembelajaran berbasis keterampilan untuk mengembangkan kompetensi yang relevan dengan kebutuhan dunia kerja. Vocational Learning Center Data Satu didukung mentor berpengalaman, materi aplikatif, dan pembelajaran yang dirancang untuk membantu peserta meningkatkan skill serta membuka peluang karier yang lebih luas.
            </p>
        </div>
    </div>
</section>

<section id="courses" class="relative bg-brand py-20 sm:py-28 lg:py-36 text-white text-center flex flex-col justify-center overflow-hidden">
    <div class="absolute inset-0 bg-brand pointer-events-none select-none overflow-hidden">
        <img src="/img/course-bg.webp" alt=""
             class="w-full h-full object-cover sm:object-fill object-center"
             onerror="this.style.display='none'">
    </div>

    <div class="relative max-w-[1200px] mx-auto px-4 z-10 w-full">
        <div class="max-w-2xl mx-auto mb-8 sm:mb-10">
            <h2 class="text-xl sm:text-2xl lg:text-3xl font-extrabold mb-3">Pilih Kelas Sesuai Minat dan Kebutuhanmu</h2>
            <p class="text-white/90 text-xs sm:text-sm leading-relaxed px-2">
                Temukan berbagai pilihan pelatihan dengan topik yang relevan dan aplikatif. Setiap bulan, tersedia kelas dengan beragam tema yang dapat kamu pilih sesuai kebutuhan pengembangan diri dan karier. Pelatihan dapat diikuti secara fleksibel, baik <strong>offline maupun online melalui format hybrid.</strong>
            </p>
        </div>

        <div class="flex overflow-x-auto sm:overflow-visible snap-x snap-mandatory sm:snap-none gap-4 sm:gap-6 px-6 sm:px-0 pb-2 sm:pb-0 no-scrollbar justify-start sm:justify-center max-w-5xl mx-auto items-stretch [scrollbar-width:none] [-ms-overflow-style:none] [&::-webkit-scrollbar]:hidden">
            <?php if (empty($courses)): ?>
                <p class="text-white/80 text-sm w-full py-8">Belum ada kelas tersedia saat ini.</p>
            <?php endif; ?>
            <?php foreach ($courses as $course): ?>
                <div class="w-[82vw] max-w-[320px] sm:w-[320px] md:w-[340px] shrink-0 sm:shrink snap-center bg-white text-gray-800 rounded-2xl overflow-hidden text-left shadow-2xl flex flex-col justify-between">
                    <div>
                        <div class="h-40 sm:h-44 bg-gray-200 relative overflow-hidden">
                            <img src="<?= esc($course['image'] ?? '/img/img-course-1.webp') ?>" alt="<?= esc($course['title']) ?>"
                                 class="w-full h-full object-cover" onerror="this.src='/img/img-course-1.webp'">
                        </div>

                        <div class="p-5 pb-4">
                            <h3 class="font-bold text-sm sm:text-base mb-2 text-slate-900 leading-snug"><?= esc($course['title']) ?></h3>
                            <ul class="text-xs text-gray-600 space-y-1.5 mb-4 list-disc list-inside">
                                <li>Durasi: <?= esc($course['duration'] ?? '3 Hari') ?></li>
                                <?php if (!empty($course['modules_count'])): ?><li><?= (int) $course['modules_count'] ?> modul</li><?php endif; ?>
                                <?php if (!empty($course['has_certificate'])): ?><li>Sertifikat Pelatihan</li><?php endif; ?>
                            </ul>
                        </div>
                    </div>

                    <div class="p-5 pt-0">
                        <a href="/programs/detail/<?= esc($course['slug']) ?>"
                           class="block w-full text-center bg-[#FF8D28] text-white font-bold py-3 rounded-full shadow-sm transition">
                           Daftar Kelas
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<div class="flex justify-center my-8 sm:my-10">
    <div class="w-12 sm:w-14 h-12 sm:h-14 rounded-full bg-accent/10 flex items-center justify-center">
        <div class="w-0 h-0 border-t-[8px] sm:border-t-[10px] border-t-transparent border-b-[8px] sm:border-b-[10px] border-b-transparent border-l-[14px] sm:border-l-[16px] border-l-accent ml-1"></div>
    </div>
</div>

<section id="faq" class="max-w-[920px] mx-auto px-4 pb-20 relative">
    <div class="hidden lg:block absolute -left-6 bottom-4 w-16 h-16 bg-accent/40 rounded-2xl -rotate-12"></div>
    <div class="hidden lg:block absolute -right-6 bottom-10 w-20 h-20 rounded-full border-[10px] border-accent/30"></div>

    <div class="bg-[#FCDECE] rounded-3xl p-5 sm:p-8 lg:p-12 shadow-xs">
        <h2 class="text-center text-xl sm:text-2xl lg:text-3xl font-extrabold mb-8 sm:mb-10 text-gray-900">Paling Sering Ditanyakan</h2>
        
        <div class="space-y-4">
            <?php if (!empty($faqs)): ?>
                <?php foreach ($faqs as $idx => $f): ?>
                    <div class="faq-item">
                        <button type="button" onclick="toggleFaqAccordion(this)" 
                                class="w-full bg-[#FF8D28] text-white rounded-full px-5 sm:px-8 py-3 sm:py-4 font-bold text-xs sm:text-base flex justify-between items-center cursor-pointer shadow-sm select-none relative z-10 transition-colors text-left">
                            <span class="pr-2"><?= esc($f['question']) ?></span>
                            <span class="faq-icon text-lg sm:text-2xl font-bold ml-2 leading-none flex-shrink-0 transition-transform duration-300">+</span>
                        </button>
                        
                        <div class="faq-answer-wrapper grid transition-all duration-300 ease-in-out grid-rows-[0fr] opacity-0">
                            <div class="overflow-hidden">
                                <div class="mx-3 sm:mx-6 bg-[#F4F4F4] text-slate-800 rounded-b-2xl px-5 sm:px-8 pt-5 pb-5 sm:pb-6 text-xs sm:text-sm leading-relaxed -mt-3 relative z-0">
                                    <p><?= esc($f['answer']) ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</section>

<script>
    function toggleFaqAccordion(btn) {
        const item = btn.closest('.faq-item');
        const wrapper = item.querySelector('.faq-answer-wrapper');
        const icon = btn.querySelector('.faq-icon');
        const isOpen = wrapper.classList.contains('grid-rows-[1fr]');

        if (isOpen) {
            wrapper.classList.remove('grid-rows-[1fr]', 'opacity-100');
            wrapper.classList.add('grid-rows-[0fr]', 'opacity-0');
            icon.innerText = '+';
        } else {
            wrapper.classList.remove('grid-rows-[0fr]', 'opacity-0');
            wrapper.classList.add('grid-rows-[1fr]', 'opacity-100');
            icon.innerText = '−';
        }
    }
</script>

<?= $this->endSection() ?>