<?php
namespace App\Models\Table;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ErrorLogs extends Model
{
  
  protected $connection = 'legatra';
  protected $table = 'error_logs';

  protected $fillable = [
    'id', 'remote_addr', 'action', 'code', 'message', 'ex_string', 'apps', 'created_by', 'created_at',
  ];
}
