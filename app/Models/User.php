<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'phone_number',
        'email',
        'password',
        'is_admin',
        'about_me',
        'skills',
        'profile_photo_path',
        'rating_points',
        'rating_count',
    ];

   
    protected $hidden = [
        'password',
        'remember_token',
    ];
    
    public function getAverageRatingAttribute()
    {
        if ($this->rating_count == 0) {
            return 0.0;
        }
        return number_format($this->rating_points / $this->rating_count, 1);
    }

    public function addRating($score)
    {
        
        if ($score >= 1 && $score <= 10) {
            $this->rating_points += $score;
            $this->rating_count += 1;
            $this->save();
        }
    }

   
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
