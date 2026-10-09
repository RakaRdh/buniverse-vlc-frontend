<?php

namespace App\Controllers;

use App\Services\ApiService;

class Profile extends BaseController
{
    protected ApiService $api;

    public function __construct()
    {
        $this->api = new ApiService();
    }

    public function index()
    {
        $memberId = session('member_id');
        if (!$memberId) {
            return redirect()->to('/login')->with('error', 'Silakan login terlebih dahulu untuk mengakses profil Anda.');
        }

        $res = $this->api->getProfile($memberId);

        if (empty($res['success']) || empty($res['data']['member'])) {
            session()->destroy();
            return redirect()->to('/login')->with('error', 'Akun member tidak ditemukan.');
        }

        $member = $res['data']['member'];
        $profile = $res['data']['profile'] ?? null;
        $enrollments = $res['data']['enrollments'] ?? [];

        $data = [
            'title'       => 'Profil Saya — Datasatu VLC',
            'member'      => $member,
            'profile'     => $profile,
            'enrollments' => $enrollments,
        ];

        return view('profile', $data);
    }

    public function update()
    {
        $memberId = session('member_id');
        if (!$memberId) {
            return redirect()->to('/login')->with('error', 'Sesi login telah kedaluwarsa.');
        }

        $fullname = trim($this->request->getPost('fullname') ?? '');
        $phone = trim($this->request->getPost('phone') ?? '');
        $address = trim($this->request->getPost('address') ?? '');

        if (empty($fullname)) {
            return redirect()->back()->with('error', 'Nama lengkap tidak boleh kosong.');
        }

        if (empty($phone)) {
            return redirect()->back()->with('error', 'Nomor WhatsApp / Telepon tidak boleh kosong.');
        }

        $res = $this->api->updateProfile($memberId, [
            'fullname' => $fullname,
            'phone'    => $phone,
            'address'  => $address,
        ]);

        if (!empty($res['success'])) {
            session()->set('member_name', $fullname);
            return redirect()->to('/profile')->with('success', 'Data profil Anda berhasil diperbarui.');
        }

        return redirect()->back()->with('error', $res['message'] ?? 'Terjadi kendala saat menyimpan profil.');
    }
}
