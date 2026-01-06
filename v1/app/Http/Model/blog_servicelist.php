<?php

namespace App\Http\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\CanResetPassword;

class blog_servicelist extends Model
{
    protected $table = 'blog_service_list';
    protected $primaryKey = 'slist_id';
    public $timestamps = false;
    protected $guarded = [];

    public function joinuserinfo($model_slist_id)
    {
        return $this->join('blog_member', 'blog_member.id', '=', 'blog_service_list.slist_user_id')
                    ->where('blog_service_list.slist_id', '=', $model_slist_id)
                    ->get();

    }
}
