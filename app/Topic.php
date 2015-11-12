<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class Topic extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'topics';
	public $timestamps = false;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['topic', 'abstract', 'user_id'];

	public function kutranslation()
	{
		return $this->hasOne(KuTranslation::class);
	}

	public function user()
	{
		return $this->belongsTo(User::class);
	}
}
