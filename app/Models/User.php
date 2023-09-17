<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Detail;
use App\Events\UserSaved;
use Illuminate\Support\Str;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Storage;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    protected $dispatchesEvents = [
        'saved' => UserSaved::class
    ];

    public function getAvatarAttribute()
    {
        
        
        if($this->photo){
            return $this->photo;
        }
        $imagePath = public_path("Profile.png");
        $image = "data:image/png;base64,".base64_encode(file_get_contents($imagePath));
    
        return $image;
    }

    public function getFullNameAttribute()
    {
        return "{$this->firstname} {$this->middlename} {$this->lastname}";
    }

    public function getMiddleinitialAttribute()
    {
         $initial = Str::substr($this->middlename, 0, 1);
         $initialU = Str::upper($initial);
         return $initialU.'.';
    }

    /**
     * Get all of the comments for the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function comments(): HasMany
    {
        return $this->hasMany(Detail::class);
    }
}
