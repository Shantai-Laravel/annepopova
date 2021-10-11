<?php

namespace App\Models;

use App\Base as Model;

class FeedBack extends Model
{
    protected $table = 'feed_back';

    protected $fillable = [
                    'form',
                    'first_name',
                    'second_name',
                    'email',
                    'phone',
                    'company',
                    'image',
                    'subject',
                    'message',
                    'additional_1',
                    'additional_2',
                    'additional_3',
                    'status',
                    'pre_order'
                ];

}
