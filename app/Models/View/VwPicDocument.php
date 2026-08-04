<?php

namespace App\Models\View;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VwPicDocument extends Model
{
    use HasFactory;
    protected $connection = 'legatra';
    protected $table = 'vw_pic_document';
}
