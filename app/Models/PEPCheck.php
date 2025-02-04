<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PEPCheck extends Model
{
    use SoftDeletes;
    
    protected $table = 'pep_data';
    protected $primaryKey = 'id';
    protected $fillable = ['nik','name','jabatan','instansi', 'tanggal_lahir', 'tempat_lahir', 'kabupaten','provinsi', 'check_by', 'client_code'];
}
