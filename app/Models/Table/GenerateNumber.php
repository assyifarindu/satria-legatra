<?php

namespace App\Models\Table;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GenerateNumber extends Model
{
    use SoftDeletes, HasFactory;

    protected $connection = 'legatra';
    
    protected $table = 'generate_numbers';


    protected $fillable = [
        'user',
        'create_by',
        'nrp',
        'nrp_user',
        'document_type',
        'company_id',
        'department_id',
        'document_title',
        'file',
        'receipent',
        'document_number',
        'last_number',
        'sign_by'
    ];

    protected $with = [
        'company',
        'department'
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }
}
