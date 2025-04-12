<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $fillable = [
        'name',
        'email',
        'phone',
        'department_id',
        'joining_date',
    ];
    public function department()
{
    return $this->belongsTo(Department::class);
}

    
}
