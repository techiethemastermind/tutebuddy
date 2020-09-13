<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

use Illuminate\Support\Facades\DB;

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

    public function assignments()
    {
        return $this->hasMany(Models\Assignment::class, 'user_id');
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

    // Get Child Account
    public function child()
    {
        $child = DB::table('user_child')->where('user_id', $this->id)->first();
        if(!empty($child)) {
            return $this->find($child->child_id);
        }
        return null;
    }

    public function studentCourse()
    {
        $course_student = DB::table('course_student')->where('user_id', $this->id)->first();
        return Models\Course::find($course_student->course_id);
    }
}
