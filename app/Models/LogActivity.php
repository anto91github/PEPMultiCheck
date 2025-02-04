<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LogActivity extends Model
{
    protected $table = 'log_usage';
    protected $primaryKey = 'id';
    protected $fillable = ['type','status','request','response', 'created_at', 'url', 'method','request_by'];
    public $timestamps = false;
}
