<?php

namespace App\Models;

use CodeIgniter\Model;

class EnrollmentModel extends Model
{
    protected $table = 'tblprogram_enrollment';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $allowedFields = [
        'member_id',
        'program_id',
        'status',
        'enrolled_at',
        'completed_at',
        'progress',
        'notes'
    ];
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    public function isEnrolled($memberId, $programId): bool
    {
        return $this->where('member_id', $memberId)
                    ->where('program_id', $programId)
                    ->countAllResults() > 0;
    }

    public function enrollMember($memberId, $programId): bool|int|string
    {
        if ($this->isEnrolled($memberId, $programId)) {
            return false;
        }

        return $this->insert([
            'member_id'   => $memberId,
            'program_id'  => $programId,
            'status'      => 'enrolled',
            'enrolled_at' => date('Y-m-d H:i:s'),
            'progress'    => 0,
        ]);
    }
}
