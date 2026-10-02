<?php
namespace App\Controllers;

class Home extends BaseController
{
	public function index()
	{
		// $jadwal_program = json_decode(file_get_contents('http://10.0.5.209/v4/tv/type:schedule'));
		// // dd($jadwal_program->result->btv);
		// $btv = $jadwal_program->result->btv;          // lebih singkat
		// $jumlah = count($btv);                        // biasanya 7

		// // 2. Cari indeks “hari ini”
		// $today = strtolower(date('l'));

		// $indeks = array_search(
		// 	$today,
		// 	array_map(fn($h) => strtolower($h->day), $btv),
		// 	true
		// );

		// if ($indeks === false) {
		// 	throw new RuntimeException('Hari ini tidak ada di array jadwal.');
		// }

		// // Tentukan array hasil sesuai aturan
		// if ($indeks === 0) {
		// 	$start_index = 0;
		// 	$hasil = array_slice($btv, 0, min(3, $jumlah));
		// } elseif ($indeks === $jumlah - 1) {
		// 	$start_index = max(0, $jumlah - 3);
		// 	$hasil = array_slice($btv, $start_index, 3);
		// } else {
		// 	$start_index = $indeks - 1;
		// 	$hasil = array_slice($btv, $start_index, 3);
		// }

		// // Mapping bulan bahasa Indonesia
		// $bulan_indonesia = [
		// 	'January' => 'Januari',
		// 	'February' => 'Februari',
		// 	'March' => 'Maret',
		// 	'April' => 'April',
		// 	'May' => 'Mei',
		// 	'June' => 'Juni',
		// 	'July' => 'Juli',
		// 	'August' => 'Agustus',
		// 	'September' => 'September',
		// 	'October' => 'Oktober',
		// 	'November' => 'November',
		// 	'December' => 'Desember',
		// ];

		// // Tambahkan tanggal dan bulan
		// $tanggal_awal = new \DateTime();

		// if ($start_index < $indeks) {
		// 	$tanggal_awal->modify('-' . ($indeks - $start_index) . ' days');
		// } elseif ($start_index > $indeks) {
		// 	$tanggal_awal->modify('+' . ($start_index - $indeks) . ' days');
		// }

		// foreach ($hasil as $i => $item) {
		// 	$tanggal = clone $tanggal_awal;
		// 	$tanggal->modify("+$i days");

		// 	$nama_bulan = $tanggal->format('F');
		// 	$item->tanggal = $tanggal->format('d');
		// 	$item->bulan = $bulan_indonesia[$nama_bulan] ?? $nama_bulan;
		// }
		// $data['jadwal_programs'] = $jadwal_program->result->btv;
		// $data['date_programs'] = $hasil;

		// $headlines = [
		// 	[
		// 		'id' => 1,
		// 		'slug' => 'jalan-dakwah',
		// 		'name' => 'Jalan Dakwah',
		// 		'description' => 'Program Jalan Dakwah adalah sebuah tayangan di BTV yang mengupas berbagai topik inspiratif dan edukatif terkait ajaran Islam.',
		// 		'day' => 'Setiap Hari',
		// 		'time' => '07.30 & 14.45',
		// 		'image' => '/img/home-jalan-dakwah.webp',
		// 	],
		// 	[
		// 		'id' => 2,
		// 		'slug' => 'kuyliner',
		// 		'name' => 'Kuyliner',
		// 		'description' => 'Menjelajahi berbagai tempat kuliner, mulai dari makanan populer hingga jajanan unik dan viral.',
		// 		'day' => 'Setiap Hari',
		// 		'time' => '15.00',
		// 		'image' => '/img/home-kuyliner.webp',
		// 	],
		// 	[
		// 		'id' => 3,
		// 		'slug' => 'dunia-binatang',
		// 		'name' => 'Dunia Binatang',
		// 		'description' => 'Menampilkan fakta-fakta unik dan menarik seputar dunia satwa dari berbagai negara.',
		// 		'day' => 'Setiap Hari',
		// 		'time' => '17.00',
		// 		'image' => '/img/home-dunia-binatang.webp',
		// 	],
		// ];
		// $data['headlines'] = $headlines;
		// Fetch active courses from DB
		$programModel = new \App\Models\ProgramModel();
		$dbPrograms = $programModel->getActivePrograms();
		$courses = [];

		foreach ($dbPrograms as $p) {
			$details = $programModel->getProgramWithModules($p['id']);
			$courses[] = [
				'id'              => $p['id'],
				'slug'            => $p['slug'],
				'image'           => base_url($p['image'] ?: 'img/img-course-1.webp'),
				'name'            => $p['name'],
				'title'           => $p['name'],
				'about'           => $p['short_desc'] ?? $p['description'],
				'modules_count'   => ($details['modules_count'] ?? 0) . ' modul',
				'has_video'       => '1 video',
				'has_certificate' => $p['has_certificate'] ? 'Sertifikat' : '',
			];
		}

		if (empty($courses)) {
			$courses = [
				[
					'id'              => 1,
					'slug'            => "esgrc",
					'image'           => base_url("img/img-course-1.webp"),
					'name'            => "ESGRC (Governance, Risk, and Compliance)",
					'title'           => "ESGRC (Governance, Risk, and Compliance)",
					'about'           => "ESGRC (Governance, Risk, and Compliance)",
					'modules_count'   => "12 modul",
					'has_video'       => "1 video",
					'has_certificate' => "Sertifikat"
				],
			];
		}

		$data = [
			'courses' => $courses,
			'faqs' => [],
		];
		return view('home', $data);
	}

	public function aboutus()
	{
		return view('about_us');
	}

	public function livestreaming()
	{
		return view('live_streaming', ['jadwal_programs' => [], 'rekomen_videos' => []]);
	}

	public function recommendvideo()
	{
		return view('recommendation_video', ['jadwal_programs' => [], 'rekomen_videos' => []]);
	}

	public function programs()
	{
		$programModel = new \App\Models\ProgramModel();
		$programs = $programModel->getActivePrograms();
		return view('programs', ['programs' => $programs]);
	}

	public function programsdetail($slugOrId = null)
	{
		$slug = $slugOrId ?? $this->request->getGet('slug') ?? $this->request->getGet('id') ?? 'esgrc';
		
		$programModel = new \App\Models\ProgramModel();
		$program = $programModel->getProgramWithModules($slug);

		if (!$program) {
			// Fallback to first available program
			$first = $programModel->first();
			if ($first) {
				$program = $programModel->getProgramWithModules($first['id']);
			}
		}

		$isEnrolled = false;
		$memberId = session('member_id');
		if ($memberId && $program) {
			$enrollmentModel = new \App\Models\EnrollmentModel();
			$isEnrolled = $enrollmentModel->isEnrolled($memberId, $program['id']);
		}

		$data = [
			'program'    => $program,
			'isEnrolled' => $isEnrolled,
			'memberId'   => $memberId,
		];

		return view('programs_detail', $data);
	}

	public function enroll($programId = null)
	{
		$programId = $programId ?? $this->request->getPost('program_id');
		$memberId = session('member_id');

		if (!$memberId) {
			return redirect()->to('/register?program_id=' . $programId)
							 ->with('info', 'Silakan masuk atau daftar terlebih dahulu untuk mendaftar kelas ini.');
		}

		$enrollmentModel = new \App\Models\EnrollmentModel();
		if ($enrollmentModel->isEnrolled($memberId, $programId)) {
			return redirect()->to('/programs/programs_detail?id=' . $programId)
							 ->with('info', 'Anda sudah terdaftar di kelas ini.');
		}

		$enrollmentModel->enrollMember($memberId, $programId);

		return redirect()->to('/programs/programs_detail?id=' . $programId)
						 ->with('success', 'Selamat! Pendaftaran Anda di kelas ini telah berhasil.');
	}

	public function anchors()
	{
		return view('anchors');
	}

	public function anchorsdetail()
	{
		return view('anchors_detail');
	}

	public function ids()
	{
		return view('ids');
	}
}