<?php

namespace App\Controllers;

use App\Services\ApiService;

class Auth extends BaseController
{
    protected ApiService $api;

    public function __construct()
    {
        $this->api = new ApiService();
    }

    public function login()
    {
        if (session('is_logged_in')) {
            return redirect()->to('/');
        }

        $programId = $this->request->getGet('program_id');
        $program = null;
        if (!empty($programId)) {
            $program = $this->api->getProgramDetail($programId);
        }

        $data = [
            'active_tab' => 'login',
            'redirect'   => $this->request->getGet('redirect') ?? '',
            'program_id' => $programId ?? '',
            'program'    => $program,
        ];

        return view('auth/login_register', $data);
    }

    public function register()
    {
        if (session('is_logged_in')) {
            return redirect()->to('/');
        }

        $programId = $this->request->getGet('program_id');
        $program = null;
        if (!empty($programId)) {
            $program = $this->api->getProgramDetail($programId);
        }

        $data = [
            'active_tab' => 'register',
            'redirect'   => $this->request->getGet('redirect') ?? '',
            'program_id' => $programId ?? '',
            'program'    => $program,
        ];

        return view('auth/login_register', $data);
    }

    public function attemptLogin()
    {
        $email = strtolower(trim($this->request->getPost('email') ?? ''));
        $password = (string) $this->request->getPost('password');
        $redirect = $this->request->getPost('redirect');
        $programId = $this->request->getPost('program_id');

        if (empty($email) || empty($password)) {
            return redirect()->back()->withInput()->with('error', 'Email dan Password wajib diisi.');
        }

        $response = $this->api->login($email, $password);

        if (empty($response['success'])) {
            return redirect()->back()->withInput()->with('error', $response['message'] ?? 'Login gagal.');
        }

        $memberData = $response['data']['member'] ?? [];
        $profileData = $response['data']['profile'] ?? [];
        $memberId = $memberData['memberID'] ?? null;

        if (!$memberId) {
            return redirect()->back()->withInput()->with('error', 'Data member tidak valid.');
        }

        // Set session
        session()->set([
            'is_logged_in' => true,
            'member_id'    => $memberId,
            'member_name'  => $memberData['fullname'] ?? 'Member',
            'member_email' => $memberData['email'] ?? $email,
        ]);

        // Auto-enroll if program_id was passed
        if (!empty($programId)) {
            $program = $this->api->getProgramDetail($programId);
            $redirectUrl = ($program && !empty($program['slug'])) ? '/programs/detail/' . $program['slug'] : '/programs/programs_detail?id=' . $programId;

            $memberPhone = trim($profileData['phone'] ?? '');

            if (empty($memberPhone)) {
                return redirect()->to($redirectUrl)->with('error', 'Silakan lengkapi Nomor WhatsApp / Telepon Anda terlebih dahulu untuk menyelesaikan pendaftaran kelas.');
            }

            $this->api->enroll($memberId, $programId, $memberPhone);
            return redirect()->to($redirectUrl)->with('success', 'Login berhasil dan Anda telah terdaftar di kelas!');
        }

        if (!empty($redirect)) {
            return redirect()->to($redirect)->with('success', 'Selamat datang kembali, ' . ($memberData['fullname'] ?? '') . '!');
        }

        return redirect()->to('/')->with('success', 'Selamat datang kembali, ' . ($memberData['fullname'] ?? '') . '!');
    }

    public function attemptRegister()
    {
        $fullname = trim($this->request->getPost('fullname') ?? '');
        $email = strtolower(trim($this->request->getPost('email') ?? ''));
        $phone = trim($this->request->getPost('phone') ?? '');
        $password = (string) $this->request->getPost('password');
        $passwordConfirm = (string) $this->request->getPost('password_confirm');
        $terms = $this->request->getPost('terms');
        $redirect = $this->request->getPost('redirect');
        $programId = $this->request->getPost('program_id');

        // Validations
        if (empty($fullname) || empty($email) || empty($phone) || empty($password)) {
            return redirect()->back()->withInput()->with('error', 'Semua kolom bertanda bintang (*) wajib diisi.');
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return redirect()->back()->withInput()->with('error', 'Format email tidak valid.');
        }

        if (strlen($password) < 6) {
            return redirect()->back()->withInput()->with('error', 'Password minimal 6 karakter.');
        }

        if ($password !== $passwordConfirm) {
            return redirect()->back()->withInput()->with('error', 'Konfirmasi password tidak cocok.');
        }

        if (empty($terms)) {
            return redirect()->back()->withInput()->with('error', 'Anda harus menyetujui syarat & aturan Datasatu Vocational Learning Center.');
        }

        $registerData = [
            'fullname'   => $fullname,
            'email'      => $email,
            'phone'      => $phone,
            'password'   => $password,
            'newsletter' => $this->request->getPost('newsletter') ? 1 : 0,
        ];

        $response = $this->api->register($registerData);

        if (empty($response['success'])) {
            return redirect()->back()->withInput()->with('error', $response['message'] ?? 'Pendaftaran gagal.');
        }

        $successMsg = 'Pendaftaran akun berhasil! Tautan verifikasi telah dikirimkan ke email (' . $email . '). Silakan periksa inbox email Anda untuk mengaktifkan akun sebelum masuk.';

        $loginUrl = '/login' . (!empty($programId) ? '?program_id=' . $programId : '');
        return redirect()->to($loginUrl)->with('success', $successMsg)->with('unverified_email', $email);
    }

    public function verify()
    {
        $token = trim($this->request->getGet('token') ?? '');
        $email = strtolower(trim($this->request->getGet('email') ?? ''));

        if (empty($token) || empty($email)) {
            return redirect()->to('/login')->with('error', 'Tautan verifikasi email tidak lengkap atau tidak valid.');
        }

        $res = $this->api->verifyEmail($token, $email);

        if (!empty($res['success'])) {
            return redirect()->to('/login')->with('success', $res['message'] ?? 'Email Anda berhasil diverifikasi! Silakan masuk dengan kata sandi Anda untuk melanjutkan.');
        }

        return redirect()->to('/login')->with('error', $res['message'] ?? 'Verifikasi email gagal atau tautan telah kedaluwarsa.');
    }

    public function resendVerification()
    {
        $email = strtolower(trim($this->request->getPost('email') ?? ''));
        if (empty($email)) {
            return redirect()->back()->with('error', 'Alamat email wajib diisi.');
        }

        $res = $this->api->resendVerification($email);
        if (!empty($res['success'])) {
            return redirect()->back()->with('success', $res['message'] ?? 'Tautan verifikasi baru berhasil dikirim ke email Anda.');
        }

        return redirect()->back()->with('error', $res['message'] ?? 'Gagal mengirim ulang tautan verifikasi.');
    }

    public function checkEmail()
    {
        $email = strtolower(trim($this->request->getVar('email') ?? ''));
        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return $this->response->setJSON([
                'success' => false,
                'exists'  => false,
                'message' => 'Format email tidak valid.'
            ]);
        }

        $res = $this->api->checkEmail($email);
        $exists = !empty($res['data']['exists']);

        return $this->response->setJSON([
            'success' => true,
            'exists'  => $exists,
            'message' => !empty($res['message']) ? $res['message'] : ($exists ? 'Email sudah terdaftar.' : 'Email tersedia.')
        ]);
    }

    public function logout()
    {
        session()->remove(['is_logged_in', 'member_id', 'member_name', 'member_email']);
        return redirect()->to('/')->with('success', 'Anda telah berhasil keluar.');
    }
}
