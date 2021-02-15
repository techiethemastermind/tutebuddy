<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

use Cmgmyr\Messenger\Traits\Messagable;
use Cmgmyr\Messenger\Models\Message;
use Cmgmyr\Messenger\Models\Participant;
use Cmgmyr\Messenger\Models\Thread;

use Illuminate\Support\Facades\DB;
use Mail;

class User extends Authenticatable
{
    use Notifiable;
    use HasRoles;
    use Messagable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'uuid', 'name', 'email', 'username', 'nick_name', 'password', 'role', 'active', 'verified', 'about', 'verify_token',
        'remember_token', 'headline', 'phone_number', 'country', 'state', 'city', 'address', 'zip', 'timezone', 'profession',
        'qualifications', 'achievements', 'experience', 'last_login_at', 'last_login_ip', 'withhold'
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
        $child_ids = DB::table('user_child')->where('user_id', $this->id)->pluck('child_id');
        return User::whereIn('id', $child_ids)->get();
    }

    // Get bank detail
    public function bank()
    {
        return $this->hasOne(Models\Bank::class);
    }

    public function reviews()
    {
        return $this->morphMany(Models\Review::class, 'reviewable');
    }

    public function notify_message()
    {
        $userId = $this->id;
        $threads = Thread::where('subject', 'like', '%' . $userId . '%')->latest('updated_at')->get();
        $partners = [];

        foreach($threads as $thread) {

            if($thread->userUnreadMessagesCount($userId) > 0) {

                $grouped_participants = $thread->participants->where('user_id', '!=', $userId)->groupBy(function($item) {
                    return $item->user_id;
                });

                foreach($grouped_participants as $participants) {
                    $participant = $participants[0];

                    $item = [
                        'partner_id' => $participant->user_id,
                        'unread' => $thread->userUnreadMessagesCount($userId),
                        'msg' => $thread->latestMessage
                    ];
                    array_push($partners, $item);
                }
            }
        }

        return $partners;
    }

    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $link = config('app.url') . 'password/reset/' . $token . '?email=' . urlencode($this->email);
        $data = [
            'template_type' => 'Forgot_Password',
            'mail_data' => [
                'link' => $link
            ]
        ];
        Mail::to($this->email)->send(new \App\Mail\SendMail($data));
    }
}
