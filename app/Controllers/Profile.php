<?php

namespace App\Controllers;

use App\Models\MemberModel;
use App\Models\ProfileModel;
use App\Models\EnrollmentModel;

class Profile extends BaseController
{
    protected $memberModel;
    protected $profileModel;
    protected $enrollmentModel;

    public function __construct()
    {
        $this->memberModel = new MemberModel();
        $this->profileModel = new ProfileModel();
        $this->enrollmentModel = new EnrollmentModel();
    }

    public function index()
    {
        $memberId = session('member_id');
        if (!$memberId) {
            return redirect()->to('/login')->with('error', 'Silakan login terlebih dahulu untuk mengakses profil Anda.');
        }

        $member = $this->memberModel->find($memberId);
        if (!$member) {
            session()->destroy();
            return redirect()->to('/login')->with('error', 'Akun member tidak ditemukan.');
        }

        $profile = $this->profileModel->where('member_id', $memberId)->first();

        // Get enrollments
        $enrollments = $this->enrollmentModel
            ->select('tblprogram_enrollment.*, tblprogram.name as program_name, tblprogram.slug as program_slug, tblprogram.schedule_info as batch_info, tblprogram.image, tblprogram.price')
            ->join('tblprogram', 'tblprogram.id = tblprogram_enrollment.program_id', 'left')
            ->where('tblprogram_enrollment.member_id', $memberId)
            ->orderBy('tblprogram_enrollment.id', 'DESC')
            ->findAll();

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

        $db = \Config\Database::connect();
        $db->transBegin();

        try {
            // Update tblmember.fullname
            $this->memberModel->update($memberId, [
                'fullname' => $fullname
            ]);

            // Update or Insert tblprofile
            $existingProfile = $this->profileModel->where('member_id', $memberId)->first();
            if ($existingProfile) {
                $this->profileModel->update($existingProfile['id'], [
                    'phone'   => $phone,
                    'address' => $address
                ]);
            } else {
                $this->profileModel->insert([
                    'member_id' => $memberId,
                    'phone'     => $phone,
                    'address'   => $address
                ]);
            }

            $db->transCommit();

            // Update active session name
            session()->set('member_name', $fullname);

            return redirect()->to('/profile')->with('success', 'Data profil Anda berhasil diperbarui.');
        } catch (\Throwable $e) {
            $db->transRollback();
            return redirect()->back()->with('error', 'Terjadi kendala saat menyimpan profil: ' . $e->getMessage());
        }
    }
}
