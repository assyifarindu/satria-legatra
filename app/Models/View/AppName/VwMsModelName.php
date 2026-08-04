<?php

namespace App\Models\View\AppName;

use Illuminate\Database\Eloquent\Model;

class VwMsModelName extends Model
{

  protected $connection = 'mysql';
  protected $table = 'vw_ms_viewname';

  protected $fillable = [
    'id', 'name', 'is_deleted', 'created_by'
  ];
}
