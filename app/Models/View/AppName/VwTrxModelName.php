<?php

namespace App\Models\View\AppName;

use Illuminate\Database\Eloquent\Model;

class VwTrxModelName extends Model
{

  protected $connection = 'mysql';
  protected $table = 'vw_trx_viewname';

  protected $fillable = [
    'id', 'name', 'is_deleted', 'created_by'
  ];
}
