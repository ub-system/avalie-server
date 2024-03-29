<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'branch',
        'city',
    ];

    public function assessments(){
        return $this->hasMany(Assessment::class);
    }

    public function getAll($filter = null)
    {
        if (!$filter) {
            return $this->paginate(5);
        }

        return $this->where('name', 'LIKE', "$filter%")->paginate(5);
    }
}
