<?php

namespace App\Models\Table\AppName;

use Illuminate\Database\Eloquent\Model;

class MsModelName extends Model
{

  protected $connection = 'mysql';
  protected $table = 'ms_tablename';

  protected $fillable = [
    'id', 'name', 'is_deleted', 'created_by'
  ];
}
