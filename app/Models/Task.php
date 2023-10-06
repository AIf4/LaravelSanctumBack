<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class Task extends Model
{
    use HasApiTokens,HasFactory;

    protected $fillable = [
        'title',
        'description',
        'state',
        'proyect_id'
    ];


    public function proyect() {
        return $this->belongsTo(Proyect::class, 'proyect_id');
    }

    public function user(){
        return $this->belongsToMany(User::class, 'user_task');
    }
}
