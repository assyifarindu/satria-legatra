<?php

namespace App\Models\Table;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;
    protected $connection = 'legatra';
    
    protected $fillable = [
        'user_id','url','feature','is_clicked','id_feature','created_by'
    ];
}
