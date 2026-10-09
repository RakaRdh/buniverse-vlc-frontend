<?php
namespace App\Controllers;

use App\Services\ApiService;

class Home extends BaseController
{
	protected ApiService $api;

	public function __construct()
	{
		$this->api = new ApiService();
	}

	public function index()
	{
		// Fetch active courses from REST API (max 3 for centered display)
		$dbPrograms = $this->api->getPrograms();
		$courses = [];

		foreach ($dbPrograms as $p) {
			$details = $this->api->getProgramDetail($p['id']);
			$courses[] = [
				'id'              => $p['id'],
				'slug'            => $p['slug'],
				'image'           => base_url($p['image'] ?: 'img/img-course-1.webp'),
				'name'            => $p['name'],
				'title'           => $p['name'],
				'about'           => $p['short_desc'] ?? $p['description'],
				'duration'        => $p['duration'] ?? '3 Hari',
				'modules_count'   => ($details['modules_count'] ?? $p['modules_count'] ?? 0) . ' modul',
				'has_video'       => '1 video',
				'has_certificate' => !empty($p['has_certificate']) ? 'Sertifikat' : '',
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

		// Fetch dynamic gallery from REST API
		$galleries = $this->api->getGalleries();
		if (empty($galleries)) {
			$galleries = [
				['image' => '/img/gallery-1.webp', 'title' => 'Gallery 1'],
				['image' => '/img/gallery-2.webp', 'title' => 'Gallery 2'],
				['image' => '/img/gallery-3.webp', 'title' => 'Gallery 3'],
			];
		}

		// Fetch dynamic FAQ from REST API
		$faqs = $this->api->getFaqs();
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
		$programs = $this->api->getPrograms();

		return view('programs', [
			'programs' => $programs,
			'title'    => 'Program Pelatihan — DataSatu VLC'
		]);
	}

	public function programsdetail($identifier = null)
	{
		$programId = $this->request->getGet('id');
		$program = null;

		if (!empty($identifier)) {
			$program = $this->api->getProgramDetail($identifier);
		} elseif (!empty($programId)) {
			$program = $this->api->getProgramDetail((int)$programId);
		}

		if (!$program) {
			$all = $this->api->getPrograms();
			if (!empty($all[0]['id'])) {
				$program = $this->api->getProgramDetail($all[0]['id']);
			}
		}

		// Check member's profile for phone number & enrollment status via API
		$memberPhone = '';
		$alreadyEnrolled = false;
		$memberId = session('member_id');
		if ($memberId) {
			$profileRes = $this->api->getProfile($memberId);
			if (!empty($profileRes['data']['profile']['phone'])) {
				$memberPhone = trim($profileRes['data']['profile']['phone']);
			}
			$enrollments = $profileRes['data']['enrollments'] ?? $this->api->getMemberEnrollments($memberId);
			if (!empty($enrollments) && !empty($program['id'])) {
				foreach ($enrollments as $e) {
					if ((int)$e['program_id'] === (int)$program['id']) {
						$alreadyEnrolled = true;
						break;
					}
				}
			}
		}

		return view('programs_detail', [
			'program'         => $program,
			'memberPhone'     => $memberPhone,
			'alreadyEnrolled' => $alreadyEnrolled,
			'title'           => ($program['name'] ?? 'Detail Program') . ' — DataSatu VLC'
		]);
	}

	public function enroll($programId = null)
	{
		$memberId = session('member_id');
		if (!$memberId) {
			return redirect()->to('/login?redirect=' . urlencode('/programs/programs_detail?id=' . $programId))->with('error', 'Silakan masuk atau buat akun untuk mendaftar kelas.');
		}

		$program = $this->api->getProgramDetail($programId);
		$redirectUrl = ($program && !empty($program['slug'])) ? '/programs/detail/' . $program['slug'] : '/programs/programs_detail?id=' . $programId;

		$postedPhone = trim($this->request->getPost('phone') ?? '');

		// Call REST API to enroll
		$response = $this->api->enroll($memberId, $programId, $postedPhone);

		if (!empty($response['success'])) {
			return redirect()->to($redirectUrl)->with('success', $response['message'] ?? 'Pendaftaran kelas berhasil!');
		}

		return redirect()->to($redirectUrl)->withInput()->with('error', $response['message'] ?? 'Gagal mendaftar kelas.');
	}
}