<?php

namespace LaravelLiberu\Api\Models;

use Illuminate\Database\Eloquent\Model;
use LaravelLiberu\Rememberable\Traits\Rememberable;
use LaravelLiberu\Tables\Traits\TableCache;
use LaravelLiberu\Users\Models\User;

class Log extends Model
{
    use Rememberable;
    use TableCache;

    protected $guarded = ['id'];

    protected $table = 'api_logs';

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
