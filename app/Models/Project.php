<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    //
    protected $fillable = ['client_id', 'title', 'description', 'status', 'budget', 'category', 'deadline'];

    public function client()
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }
}
