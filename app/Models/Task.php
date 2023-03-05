<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'description',
        'status',
        'project_id',
        'user_id'
    ];

    protected $dates = [
        'created_at',
        'updated_at'
    ];

    public function getProject(){
        return $this->belongsTo('App\Models\Project','project_id','id');
    }

    public function getUser(){
        return $this->belongsTo('App\Models\User','user_id','id');
    }
}
