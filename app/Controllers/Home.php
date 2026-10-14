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
			$rawPrice = $details['price'] ?? $p['price'] ?? null;
			$priceVal = ($rawPrice !== null && $rawPrice !== '') ? (float)$rawPrice : null;
			$priceFormatted = null;
			if ($priceVal !== null) {
				$priceFormatted = $priceVal > 0 ? 'Rp ' . number_format($priceVal, 0, ',', '.') : 'Gratis';
			}
			$isFull = !empty($details['is_full']) || !empty($p['is_full']);

			$courses[] = [
				'id'                      => $p['id'],
				'slug'                    => $p['slug'],
				'image'                   => base_url($p['image'] ?: 'img/img-course-1.webp'),
				'name'                    => $p['name'],
				'title'                   => $p['name'],
				'about'                   => $p['short_desc'] ?? $p['description'],
				'duration'                => !empty($p['duration']) ? $p['duration'] : (!empty($details['duration']) ? $details['duration'] : '3 Hari'),
				'modules_count'           => ((int)($details['modules_count'] ?? $p['modules_count'] ?? 0) > 0) ? ((int)($details['modules_count'] ?? $p['modules_count'])) . ' modul' : null,
				'has_video'               => '1 video',
				'has_certificate'         => !empty($p['has_certificate']) ? 'Sertifikat' : '',
				'price'                   => $priceVal,
				'price_formatted'         => $priceFormatted,
				'is_full'                 => $isFull,
				'max_participants'        => $details['max_participants'] ?? $p['max_participants'] ?? null,
				'active_participants_count' => $details['active_participants_count'] ?? $p['active_participants_count'] ?? 0,
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
					'has_certificate' => "Sertifikat",
					'price'           => 0,
					'price_formatted' => "Gratis",
					'is_full'         => false,
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
		$currentEnrollment = null;
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
						$currentEnrollment = $e;
						if ($e['status'] !== 'rejected') {
							$alreadyEnrolled = true;
						}
						break;
					}
				}
			}
		}

		return view('programs_detail', [
			'program'           => $program,
			'memberPhone'       => $memberPhone,
			'alreadyEnrolled'   => $alreadyEnrolled,
			'currentEnrollment' => $currentEnrollment,
			'title'             => ($program['name'] ?? 'Detail Program') . ' — DataSatu VLC'
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
		if (!empty($postedPhone)) {
			$postedPhone = \App\Helpers\PhoneHelper::normalize($postedPhone) ?: $postedPhone;
			$phoneVal = \App\Helpers\PhoneHelper::validate($postedPhone);
			if (!$phoneVal['valid']) {
				return redirect()->to($redirectUrl)->withInput()->with('error', $phoneVal['message']);
			}
		}

		// Check if already banned
		$existingEnrollments = $this->api->getMemberEnrollments($memberId);
		if (!empty($existingEnrollments)) {
			foreach ($existingEnrollments as $e) {
				if ((int)$e['program_id'] === (int)$programId) {
					if ($e['status'] === 'banned') {
						return redirect()->to($redirectUrl)->with('error', 'Akun Anda telah diblokir (Banned) dari program pelatihan ini dan tidak dapat mendaftar kembali.');
					}
				}
			}
		}

		// Call REST API to enroll
		$response = $this->api->enroll($memberId, $programId, $postedPhone);

		if (!empty($response['success'])) {
			$successMsg = !empty($response['message']) 
				? $response['message'] 
				: 'Pendaftaran kelas berhasil dikirimkan! Status Anda saat ini menunggu verifikasi admin (estimasi 1-3 hari kerja).';
			return redirect()->to($redirectUrl)->with('success', $successMsg);
		}

		return redirect()->to($redirectUrl)->withInput()->with('error', $response['message'] ?? 'Gagal mendaftar kelas.');
	}

	/**
	 * Cache Invalidation Endpoint (triggered by CMS webhook or manually)
	 * Route: api/clear-cache
	 */
	public function clearCache()
	{
		$this->api->clearDataCache();

		// Optional: clear file uploads disk cache if clean_uploads parameter sent
		$cleanUploads = $this->request->getVar('clean_uploads');
		if (!empty($cleanUploads)) {
			$cacheDir = WRITEPATH . 'cache/uploads/';
			if (is_dir($cacheDir)) {
				$files = new \RecursiveIteratorIterator(
					new \RecursiveDirectoryIterator($cacheDir, \RecursiveDirectoryIterator::SKIP_DOTS),
					\RecursiveIteratorIterator::CHILD_FIRST
				);
				foreach ($files as $fileinfo) {
					if ($fileinfo->isFile()) {
						@unlink($fileinfo->getRealPath());
					}
				}
			}
		}

		return $this->response->setJSON([
			'success'   => true,
			'message'   => 'Frontend cache cleared successfully.',
			'timestamp' => date('Y-m-d H:i:s'),
		]);
	}
}