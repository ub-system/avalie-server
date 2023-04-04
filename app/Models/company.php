<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    private $fillable = [
        'name',
        'branch',
        'city',
    ];

    public function assessments(){
        return $this->hasMany(Assessment::class);
    }
}
