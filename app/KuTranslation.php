<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class KuTranslation extends Model
{
	protected $primaryKey = 'topic_id';

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'ku_translations';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['topic_id', 'topic', 'abstract'];

	public function topic()
	{
		return $this->belongsTo(Topic::class);
	}
}
