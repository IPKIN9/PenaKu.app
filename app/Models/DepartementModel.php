<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DepartementModel extends Model
{
    use HasFactory;
    protected $table = 'departement';
    protected $fillable = [
        'id', 'name', 'created_at', 'updated_at'
    ];
}
