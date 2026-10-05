<?php
namespace App\Controllers;

use App\Models\ProgramModel;
use App\Models\GalleryModel;
use App\Models\FaqModel;

class Home extends BaseController
{
	public function index()
	{
		// Fetch active courses from DB (max 3 for centered display)
		$programModel = new ProgramModel();
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
				'duration'        => $p['duration'] ?? '3 Hari',
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
					'about'           => "Pelatihan komprehensif tata kelola, risiko, dan kepatuhan ESG.",
					'duration'        => "3 Hari",
					'modules_count'   => "4 modul",
					'has_video'       => "1 video",
					'has_certificate' => "Sertifikat"
				],
			];
		}

		// Limit courses to max 3 as requested
		$courses = array_slice($courses, 0, 3);

		// Fetch dynamic gallery from DB
		$galleryModel = new GalleryModel();
		$galleries = $galleryModel->getActiveGalleries();
		if (empty($galleries)) {
			$galleries = [
				['image' => '/img/gallery-1.webp', 'title' => 'Gallery 1'],
				['image' => '/img/gallery-2.webp', 'title' => 'Gallery 2'],
				['image' => '/img/gallery-3.webp', 'title' => 'Gallery 3'],
			];
		}

		// Fetch dynamic FAQ from DB
		$faqModel = new FaqModel();
		$faqs = $faqModel->getActiveFaqs();
		if (empty($faqs)) {
			$faqs = [
				['question' => 'Berapa lama training akan berlangsung?', 'answer' => 'Durasi training bervariasi tergantung modul, umumnya berlangsung antara 2 sampai 4 minggu secara hybrid.'],
				['question' => 'Berapa orang yang menjadi peserta dalam satu kelas?', 'answer' => 'Setiap kelas dibatasi maksimal 30 peserta agar pembelajaran lebih efektif dan interaktif.'],
				['question' => 'Bagaimana cara pembayaran untuk mengikuti training?', 'answer' => 'Pembayaran dapat dilakukan melalui transfer bank setelah pendaftaran kelas disetujui oleh tim kami.'],
			];
		}

		$data = [
			'courses'   => $courses,
			'galleries' => $galleries,
			'faqs'      => $faqs,
			'title'     => 'Vocational Learning Center — DataSatu'
		];

		return view('home', $data);
	}

	public function aboutus()
	{
		return view('about_us', [
			'title' => 'About Us — DataSatu VLC'
		]);
	}

	public function programs()
	{
		$programModel = new ProgramModel();
		$programs = $programModel->getActivePrograms();

		return view('programs', [
			'programs' => $programs,
			'title'    => 'Program Pelatihan — DataSatu VLC'
		]);
	}

	public function programsdetail($identifier = null)
	{
		$programModel = new ProgramModel();
		
		$programId = $this->request->getGet('id');
		$program = null;

		if (!empty($identifier)) {
			if (is_numeric($identifier)) {
				$program = $programModel->getProgramWithModules((int)$identifier);
			} else {
				$program = $programModel->getProgramBySlug($identifier);
			}
		} elseif (!empty($programId)) {
			$program = $programModel->getProgramWithModules((int)$programId);
		}

		if (!$program) {
			$first = $programModel->where('status', 'active')->first();
			if ($first) {
				$program = $programModel->getProgramWithModules($first['id']);
			}
		}

		// Check member's profile for phone number
		$memberPhone = '';
		$memberId = session('member_id');
		if ($memberId) {
			$profileModel = new \App\Models\ProfileModel();
			$profile = $profileModel->where('member_id', $memberId)->first();
			$memberPhone = trim($profile['phone'] ?? '');
		}

		return view('programs_detail', [
			'program'     => $program,
			'memberPhone' => $memberPhone,
			'title'       => ($program['name'] ?? 'Detail Program') . ' — DataSatu VLC'
		]);
	}

	public function enroll($programId = null)
	{
		$memberId = session('member_id');
		if (!$memberId) {
			return redirect()->to('/login?redirect=' . urlencode('/programs/programs_detail?id=' . $programId))->with('error', 'Silakan masuk atau buat akun untuk mendaftar kelas.');
		}

		$profileModel = new \App\Models\ProfileModel();
		$profile = $profileModel->where('member_id', $memberId)->first();
		$existingPhone = trim($profile['phone'] ?? '');

		// Check if phone was submitted in this request
		$postedPhone = trim($this->request->getPost('phone') ?? '');
		$phoneToUse = !empty($postedPhone) ? $postedPhone : $existingPhone;

		$programModel = new \App\Models\ProgramModel();
		$program = $programModel->find($programId);
		$redirectUrl = $program && !empty($program['slug']) ? '/programs/detail/' . $program['slug'] : '/programs/programs_detail?id=' . $programId;

		if (empty($phoneToUse)) {
			return redirect()->to($redirectUrl)->withInput()->with('error', 'Nomor WhatsApp / Telepon wajib diisi untuk konfirmasi pendaftaran.');
		}

		// Save or update phone in profile if newly entered
		if (!empty($postedPhone) && $postedPhone !== $existingPhone) {
			if ($profile) {
				$profileModel->update($profile['id'], ['phone' => $postedPhone]);
			} else {
				$profileModel->insert(['member_id' => $memberId, 'phone' => $postedPhone]);
			}
		}

		$enrollmentModel = new \App\Models\EnrollmentModel();
		$enrolled = $enrollmentModel->enrollMember($memberId, $programId);

		return redirect()->to($redirectUrl)->with('success', 'Pendaftaran kelas berhasil!');
	}
}