<?php

namespace App\Models\Table\AppName;

use Illuminate\Database\Eloquent\Model;

class TrxModelName extends Model
{

  protected $connection = 'mysql';
  protected $table = 'trx_tablename';

  protected $fillable = [
    'id', 'name', 'is_deleted', 'created_by'
  ];
}
