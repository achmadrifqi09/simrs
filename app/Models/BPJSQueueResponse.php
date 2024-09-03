<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BPJSQueueResponse extends Model
{
    use HasFactory;

    public $table = 'response_antrean_bpjs';
    protected $primaryKey = 'id';

    protected $fillable = [
        'nik',
        'rm_number',
        'bpjs_number',
        'response_status',
        'response_message',
        'response_body',
        'task_id_1',
        'task_id_2',
        'task_id_3',
        'task_id_4',
        'task_id_5'
    ];
}
