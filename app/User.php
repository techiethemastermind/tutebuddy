<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use Notifiable;
    use HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'role', 'active', 'verified', 'about', 'verify_token', 'remember_token'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function courses()
    {
        return $this->belongsToMany(Models\Course::class, 'course_user');
    }

    public function chapters()
    {
        return $this->hasMany(Models\ChapterStudent::class, 'user_id');
    }

    public function purchasedCourses(){
        $orders = Order::where('status','=',1)
            ->where('user_id','=',$this->id)
            ->pluck('id');
        $courses_id = OrderItem::whereIn('order_id',$orders)
            ->where('item_type','=',"App\Models\Course")
            ->pluck('item_id');
        $courses = Course::whereIn('id',$courses_id)
            ->get();
        return $courses;
    }

    //Get Certificates
    public function certificates(){
        return $this->hasMany(Models\Certificate::class);
    }
}
