<?php

namespace App\Services;

class ColorService
{
    private $backgroud_colors = [
        '#92a8d1',
        '#6b5b95',
        '#d64161',
        '#ff7b25',
        '#86af49',
        '#405d27',
        '#034f84',
        '#618685',
        '#50394c',
        '#c94c4c',
        '#3e4444'
    ];

    private $font_colors = [
        'black',
        'white',
        'white',
        'white',
        'white',
        'white',
        'white',
        'white',
        'white',
        'white',
        'white'
    ];

    public function getScheduleColor($int) {

        return [
            'background_color' => $this->backgroud_colors[$int],
            'font_color' => $this->font_colors[$int]
        ];
    }
}