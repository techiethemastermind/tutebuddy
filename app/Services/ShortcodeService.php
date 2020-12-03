<?php

namespace App\Services;

class ShortCodeService
{
    public $data;

    public function replace($data, $html)
    {
        $this->data = $data;

        $expression_for_text = '/{(.*?)}/';
        $expression_for_button = '/\[([^\]]*)\]/';
        preg_match_all($expression_for_text, $html, $matches_text);
        preg_match_all($expression_for_button, $html, $matches_button);
        
        if(count($matches_text[1]) > 0) {
            foreach($matches_text[1] as $code) {
                $text = $this->shortcode_text($code);
                $html = str_replace('{'. $code .'}', $text, $html);
            }
        }

        if(count($matches_button[1]) > 0) {
            foreach($matches_button[1] as $code) {
                $button = $this->shortcode_button($code);
                $html = str_replace('['. $code .']', $button, $html);
            }
        }

        return $html;
    }

    public function shortcode_text($code)
    {
        switch($code)
        {
            case 'user_name':
                if(auth()->check()) {
                    return auth()->user()->name;
                } else {
                    return '';
                }
                
            break;

            case 'site_name':
                return config('app.name');
            break;

            case 'site_url':
                return config('app.url');
            break;

            case 'verify_link':
                return url('user/verify', auth()->user()->verify_token);
            break;

            case 'customer_contact_info':
                $html = '';
                if(!empty($this->data['mail_data'])) {
                    $model = $this->data['mail_data']['model_type'];
                    if(!empty($model)) {
                        if($model == 'App\Models\Contact') {
                            $data = \App\Models\Contact::find($this->data['mail_data']['model_id']);
                            $html = $this->getContactHtml($data);
                        }
                    }
                }
                return $html;
            break;

            case 'course_name':
                $html = '';
                if(!empty($this->data['mail_data'])) {
                    $model = $this->data['mail_data']['model_type'];
                    if(!empty($model)) {
                        if($model == 'App\Models\Certificate') {
                            $data = \App\Models\Certificate::find($this->data['mail_data']['model_id']);
                            $html = $data->course->title;
                        }

                        if($model == 'App\Models\Course') {
                            $data = \App\Models\Course::find($this->data['mail_data']['model_id']);
                            $html = $data->title;
                        }

                        if($model == 'App\Models\Quiz') {
                            $data = \App\Models\Quiz::find($this->data['mail_data']['model_id']);
                            $html = $data->course->title;
                        }

                        if($model == 'App\Models\Test') {
                            $data = \App\Models\Test::find($this->data['mail_data']['model_id']);
                            $html = $data->course->title;
                        }
                    }
                }
                return $html;
            break;

            case 'fee_amount':
                $html = '';
                if(!empty($this->data['mail_data'])) {
                    $model = $this->data['mail_data']['model_type'];
                    if(!empty($model)) {
                        if($model == 'App\Models\Order') {
                            $data = \App\Models\Order::find($this->data['mail_data']['model_id']);
                            $html = getCurrency(config('app.currency'))['symbol'] . $data->amount;
                        }
                    }
                }
                return $html;
            break;

            case 'order_items_table':
                $html = '';
                if(!empty($this->data['mail_data'])) {
                    $model = $this->data['mail_data']['model_type'];
                    if(!empty($model)) {
                        $html = $this->data['order_items_table'];
                    }
                }
                return $html;
            break;

            case 'failure_gateway_response':
                $html = '';
                if(!empty($this->data['mail_data'])) {
                    $html = $reason;
                }
                return $html;
            break;

            case 'instructor_name':
                $html = '';
                if(!empty($this->data['mail_data'])) {
                    $model = $this->data['mail_data']['model_type'];
                    if(!empty($model)) {
                        if($model == 'App\Models\Assignment') {
                            $data = \App\Models\Assignment::find($this->data['mail_data']['model_id']);
                            $html = $data->user->name;
                        }

                        if($model == 'App\Models\Quiz') {
                            $data = \App\Models\Quiz::find($this->data['mail_data']['model_id']);
                            $html = $data->user->name;
                        }

                        if($model == 'App\Models\Test') {
                            $data = \App\Models\Test::find($this->data['mail_data']['model_id']);
                            $html = $data->user->name;
                        }
                    }
                }
                return $html;
            break;

            case 'assignment_created_date':
                $html = '';
                if(!empty($this->data['mail_data'])) {
                    $model = $this->data['mail_data']['model_type'];
                    if(!empty($model)) {
                        if($model == 'App\Models\Assignment') {
                            $data = \App\Models\Assignment::find($this->data['mail_data']['model_id']);
                            $html = $data->created_at;
                        }
                    }
                }
                return $html;
            break;

            case 'assignment_submit_date':
                $html = '';
                if(!empty($this->data['mail_data'])) {
                    $model = $this->data['mail_data']['model_type'];
                    if(!empty($model)) {
                        if($model == 'App\Models\Assignment') {
                            $data = \App\Models\Assignment::find($this->data['mail_data']['model_id']);
                            $html = $data->due_date;
                        }
                    }
                }
                return $html;
            break;

            case 'assignment_submit_time':
                $html = '';
                if(!empty($this->data['mail_data'])) {
                    $model = $this->data['mail_data']['model_type'];
                    if(!empty($model)) {
                        if($model == 'App\Models\Assignment') {
                            $data = \App\Models\Assignment::find($this->data['mail_data']['model_id']);
                            $html = $data->due_time;
                        }
                    }
                }
                return $html;
            break;

            case 'replying_user':
                $html = '';
                if(!empty($this->data['mail_data'])) {
                    $model = $this->data['mail_data']['model_type'];
                    if(!empty($model)) {
                        if($model == 'App\Models\DiscussionResult') {
                            $data = \App\Models\DiscussionResult::find($this->data['mail_data']['model_id']);
                            $html = $data->user->name;
                        }
                    }
                }
                return $html;
            break;

            case 'discussion_topic':
                $html = '';
                if(!empty($this->data['mail_data'])) {
                    $model = $this->data['mail_data']['model_type'];
                    if(!empty($model)) {
                        if($model == 'App\Models\DiscussionResult') {
                            $data = \App\Models\DiscussionResult::find($this->data['mail_data']['model_id']);
                            $html = $data->discussion->title;
                        }
                    }
                }
                return $html;
            break;

            case 'sender_name':
                $html = '';
                if(!empty($this->data['mail_data'])) {
                    $model = $this->data['mail_data']['model_type'];
                    if(!empty($model)) {
                        if($model == 'App\Models\Message') {
                            $html = auth()->user()->name;
                        }
                    }
                }
                return $html;
            break;

            case 'quiz_created_date':
                $html = '';
                if(!empty($this->data['mail_data'])) {
                    $model = $this->data['mail_data']['model_type'];
                    if(!empty($model)) {
                        if($model == 'App\Models\Quiz') {
                            $data = \App\Models\Quiz::find($this->data['mail_data']['model_id']);
                            $html = $data->created_at;
                        }
                    }
                }
                return $html;
            break;

            case 'take_quiz_by_date':
                $html = '';
                if(!empty($this->data['mail_data'])) {
                    $model = $this->data['mail_data']['model_type'];
                    if(!empty($model)) {
                        if($model == 'App\Models\Quiz') {
                            $data = \App\Models\Quiz::find($this->data['mail_data']['model_id']);
                            $html = $data->start_date;
                        }
                    }
                }
                return $html;
            break;

            case 'take_quiz_by_time':
                $html = '';
                if(!empty($this->data['mail_data'])) {
                    $model = $this->data['mail_data']['model_type'];
                    if(!empty($model)) {
                        if($model == 'App\Models\Quiz') {
                            $data = \App\Models\Quiz::find($this->data['mail_data']['model_id']);
                            $html = $data->duration;
                        }
                    }
                }
                return $html;
            break;

            case 'test_created_date':
                $html = '';
                if(!empty($this->data['mail_data'])) {
                    $model = $this->data['mail_data']['model_type'];
                    if(!empty($model)) {
                        if($model == 'App\Models\Test') {
                            $data = \App\Models\Test::find($this->data['mail_data']['model_id']);
                            $html = $data->created_at;
                        }
                    }
                }
                return $html;
            break;

            case 'test_date':
                $html = '';
                if(!empty($this->data['mail_data'])) {
                    $model = $this->data['mail_data']['model_type'];
                    if(!empty($model)) {
                        if($model == 'App\Models\Test') {
                            $data = \App\Models\Test::find($this->data['mail_data']['model_id']);
                            $html = $data->start_date;
                        }
                    }
                }
                return $html;
            break;

            case 'test_date':
                $html = '';
                if(!empty($this->data['mail_data'])) {
                    $model = $this->data['mail_data']['model_type'];
                    if(!empty($model)) {
                        if($model == 'App\Models\Test') {
                            $data = \App\Models\Test::find($this->data['mail_data']['model_id']);
                            $html = $data->start_date;
                        }
                    }
                }
                return $html;
            break;

            case 'test_time':
                $html = '';
                if(!empty($this->data['mail_data'])) {
                    $model = $this->data['mail_data']['model_type'];
                    if(!empty($model)) {
                        if($model == 'App\Models\Test') {
                            $data = \App\Models\Test::find($this->data['mail_data']['model_id']);
                            $html = $data->start_date;
                        }
                    }
                }
                return $html;
            break;
        }
    }

    public function shortcode_button($code)
    {
        $style_btn = 'text-align: center;
                border: 1px solid transparent;
                padding: .3rem .5rem;
                border-radius: .25rem;
                text-transform: uppercase;
                color: #fff;
                font-size: 14px;
                margin: 0 10px 10px 0;
                display: inline-block;
                line-height: 14px;
                text-decoration: none;
                box-shadow: inset 0 1px 0 hsla(0,0%,100%,.15), 0 1px 1px rgba(39,44,51,.075);';
        $style_primary = 'background-color: #5567ff;border-color: #5567ff;';
        $style_accent = 'background-color: #ed0b4c;border-color: #ed0b4c;';
        $style_info = 'background-color: #17a2b8;border-color: #17a2b8;';

        switch($code)
        {
            case 'explore_courses':
                $route = route('courses.search');
                return '<a href="'. $route .'" style="'. $style_btn . $style_primary .'"> Explore Courses </a>';
            break;

            case 'explore_instructors':
                $route = route('courses.search');
                return '<a href="'. $route .'" style="'. $style_btn . $style_accent .'">Explore Instructors </a>';
            break;

            case 'login':
                $route = route('login');
                return '<a href="'. $route .'" style="'. $style_btn . $style_info .'">Login to Account </a>';
            break;

            case 'reset_password':
                if(!empty($this->data['mail_data'])) {
                    return '<a href="'. $this->data['mail_data']['link'] .'" style="'. $style_btn . $style_primary .'"> Reset Password </a>';
                } else {
                    return '';
                }
            break;

            case 'read_discussion':
                if(!empty($this->data['mail_data'])) {
                    return '<a href="'. route('admin.discussions.topics') .'" style="'. $style_btn . $style_primary .'"> Read Discussion </a>';
                } else {
                    return '';
                }
            break;

            case 'read_message':
                if(!empty($this->data['mail_data'])) {
                    return '<a href="'. route('admin.messages.index') .'" style="'. $style_btn . $style_primary .'"> Read Messages </a>';
                } else {
                    return '';
                }
            break;
        }
    }

    function getContactHtml($data)
    {
        $contact_view = view('emails.parts.contact', ['data' => $data])->render();
        return $contact_view;
    }
}