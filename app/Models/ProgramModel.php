<?php

namespace App\Models;

use CodeIgniter\Model;

class ProgramModel extends Model
{
    protected $table = 'tblprogram';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $allowedFields = [
        'name',
        'slug',
        'description',
        'short_desc',
        'image',
        'price',
        'duration',
        'modules',
        'schedule_info',
        'max_participants',
        'has_certificate',
        'status',
        'start_date',
        'end_date',
        'created_by'
    ];

    public function getActivePrograms()
    {
        return $this->where('status', 'active')
                    ->orderBy('id', 'DESC')
                    ->findAll();
    }

    public function getProgramBySlug($slug)
    {
        return $this->getProgramWithModules($slug);
    }

    public function getProgramWithModules($slugOrId)
    {
        $program = is_numeric($slugOrId) 
            ? $this->find($slugOrId) 
            : $this->where('slug', $slugOrId)->first();

        if (!$program) {
            return null;
        }

        $manualModules = (!empty($program['modules']) && is_numeric($program['modules']) && (int)$program['modules'] > 0)
            ? (int)$program['modules']
            : 0;

        $modules = $this->db->table('tblprogram_module')
            ->where('program_id', $program['id'])
            ->orderBy('sort_order', 'ASC')
            ->get()
            ->getResultArray();

        $program['manual_modules'] = $manualModules;
        $program['modules_items'] = $modules;
        $program['modules'] = $modules;
        $program['modules_count'] = $manualModules > 0 ? $manualModules : count($modules);

        // Hitung peserta aktif (distinct member)
        $activeCountRow = $this->db->table('tblprogram_enrollment')
            ->select('COUNT(DISTINCT member_id) as total')
            ->where('program_id', $program['id'])
            ->whereIn('status', ['active', 'in_progress'])
            ->get()
            ->getRow();
        $activeCount = !empty($activeCountRow) ? (int)$activeCountRow->total : 0;
        $program['active_participants_count'] = $activeCount;
        $maxCap = !empty($program['max_participants']) ? (int)$program['max_participants'] : 0;
        $program['is_full'] = ($maxCap > 0 && $activeCount >= $maxCap);

        return $program;
    }
}
