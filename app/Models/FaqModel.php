<?php

namespace App\Models;

use CodeIgniter\Model;

class FaqModel extends Model
{
    protected $table = 'tblvlcfaq';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $allowedFields = [
        'question',
        'answer',
        'sort_order',
        'is_active'
    ];

    public function getActiveFaqs()
    {
        return $this->where('is_active', '1')
                    ->orderBy('sort_order', 'ASC')
                    ->orderBy('id', 'ASC')
                    ->findAll();
    }
}
