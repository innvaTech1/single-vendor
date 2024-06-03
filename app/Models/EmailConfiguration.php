<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailConfiguration extends Model
{
    use HasFactory;

    protected $fillable = [
        'mail_type',
        'mail_host',
        'mail_port',
        'email',
        'smtp_username',
        'smtp_password',
        'mail_encryption',
    ];
}
