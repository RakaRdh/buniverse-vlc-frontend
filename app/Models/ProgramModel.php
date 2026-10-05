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

        $modules = $this->db->table('tblprogram_module')
            ->where('program_id', $program['id'])
            ->orderBy('sort_order', 'ASC')
            ->get()
            ->getResultArray();

        $program['modules'] = $modules;
        $program['modules_count'] = count($modules);
        return $program;
    }
}
