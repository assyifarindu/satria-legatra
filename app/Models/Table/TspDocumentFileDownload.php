<?php

namespace App\Models\Table;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TspDocumentFileDownload extends Model
{
    use HasFactory, SoftDeletes;

    protected $connection = 'legatra';
    protected $table = 'tsp_document_file_downloads';
    protected $guarded = ['id'];
}