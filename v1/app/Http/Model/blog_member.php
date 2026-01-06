<?php

namespace App\Http\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\CanResetPassword;

class blog_member extends Model implements Authenticatable,CanResetPassword
{
    //Laravel Auth功能，要在Model端加入這2項介面，在藉由use trait實作介面function
    use \Illuminate\Auth\Authenticatable;
    use \Illuminate\Auth\Passwords\CanResetPassword;

    protected $table = 'blog_member';
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $guarded = [];

    public function get_order_list($id)
    {
        return $this->join('blog_order', 'blog_member.id', '=', 'blog_order.order_member')
                    ->where('blog_member.id', '=', $id)
                    ->paginate(5);
    }
}
