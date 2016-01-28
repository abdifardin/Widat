<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Database\Eloquent\SoftDeletes;

class DeleteRecommendation extends Model
{
    //
	
	use SoftDeletes;
	
	protected $table = 'delete_recommendations';
	protected $primaryKey = 'topic_id';
	protected $dates = ['deleted_at'];
	
	
	public function topics()
	{
		return $this->belongsTo(Topic::class);
	}
	
	public function users()
	{
		return $this->belongsTo(User::class);
	}
}
