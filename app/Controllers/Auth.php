<?php

namespace App\Controllers;

use App\Models\MemberModel;
use App\Models\ProfileModel;
use App\Models\EnrollmentModel;
use App\Models\ProgramModel;

class Auth extends BaseController
{
    protected $memberModel;
    protected $profileModel;
    protected $enrollmentModel;
    protected $programModel;

    public function __construct()
    {
        $this->memberModel = new MemberModel();
        $this->profileModel = new ProfileModel();
        $this->enrollmentModel = new EnrollmentModel();
        $this->programModel = new ProgramModel();
    }

    public function login()
    {
        if (session('is_logged_in')) {
            return redirect()->to('/');
        }

        $programId = $this->request->getGet('program_id');
        $program = null;
        if (!empty($programId)) {
            $program = $this->programModel->getProgramWithModules($programId);
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
            $program = $this->programModel->getProgramWithModules($programId);
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

        $member = $this->memberModel->where('email', $email)->first();

        if (!$member) {
            return redirect()->back()->withInput()->with('error', 'Email tidak terdaftar.');
        }

        if ($member['status'] === 'banned') {
            return redirect()->back()->withInput()->with('error', 'Akun Anda sedang dinonaktifkan / dibanned.');
        }

        $isValid = MemberModel::verifyPassword($password, $member['password'], $member['salt'] ?? '');

        if (!$isValid) {
            return redirect()->back()->withInput()->with('error', 'Password yang Anda masukkan salah.');
        }

        // Update last login
        $this->memberModel->update($member['memberID'], [
            'lastlogin' => date('Y-m-d H:i:s')
        ]);

        // Set session
        session()->set([
            'is_logged_in' => true,
            'member_id'    => $member['memberID'],
            'member_name'  => $member['fullname'] ?? 'Member',
            'member_email' => $member['email'],
        ]);

        // Auto-enroll if program_id was passed
        if (!empty($programId)) {
            $this->enrollmentModel->enrollMember($member['memberID'], $programId);
            return redirect()->to('/programs/programs_detail?id=' . $programId)->with('success', 'Login berhasil dan Anda telah terdaftar di kelas!');
        }

        if (!empty($redirect)) {
            return redirect()->to($redirect)->with('success', 'Selamat datang kembali, ' . ($member['fullname'] ?? '') . '!');
        }

        return redirect()->to('/')->with('success', 'Selamat datang kembali, ' . ($member['fullname'] ?? '') . '!');
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

        // Check if email already registered
        $existing = $this->memberModel->where('email', $email)->first();
        if ($existing) {
            return redirect()->back()->withInput()->with('error', 'Email ini sudah terdaftar. Silakan masuk ke tab Login.');
        }

        // Insert member
        $memberId = $this->memberModel->registerMember([
            'fullname' => $fullname,
            'email'    => $email,
            'password' => $password,
        ]);

        if (!$memberId) {
            return redirect()->back()->withInput()->with('error', 'Gagal mendaftarkan akun. Silakan coba kembali.');
        }

        // Insert profile
        $this->profileModel->insert([
            'member_id' => $memberId,
            'phone'     => $phone,
        ]);

        // Auto-login
        session()->set([
            'is_logged_in' => true,
            'member_id'    => $memberId,
            'member_name'  => $fullname,
            'member_email' => $email,
        ]);

        // If enrolled directly from a program
        if (!empty($programId)) {
            $this->enrollmentModel->enrollMember($memberId, $programId);
            return redirect()->to('/programs/programs_detail?id=' . $programId)->with('success', 'Registrasi berhasil! Anda telah resmi terdaftar di kelas.');
        }

        if (!empty($redirect)) {
            return redirect()->to($redirect)->with('success', 'Pendaftaran akun berhasil!');
        }

        return redirect()->to('/')->with('success', 'Pendaftaran berhasil! Selamat datang di VLC Datasatu.');
    }

    public function logout()
    {
        session()->remove(['is_logged_in', 'member_id', 'member_name', 'member_email']);
        return redirect()->to('/')->with('success', 'Anda telah berhasil keluar.');
    }
}
