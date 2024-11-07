<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserEmailChange extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'users_email_changes';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array<string>
     */
    protected $guarded = [];
}
