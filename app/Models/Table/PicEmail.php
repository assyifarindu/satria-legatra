<?php

namespace App\Models\Table;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class PicEmail extends Model
{
    use HasFactory;

    protected $connection = 'legatra';


    protected $fillable = [
        'user_id'
    ];

    // public function getUser()
    // {
    //     $satria = env('DB_DATABASE');

    //     $query = DB::table('pic_emails')
    //         ->leftJoin('', 'spb.id', '=', 'bpb.spb_id')
    //         ->get();
    // }
}
