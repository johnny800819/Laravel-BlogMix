<?php

namespace App\Http\Model;


use Illuminate\Database\Eloquent\Model;


class blog_category_sub extends Model
{
    protected $table = 'blog_category_sub';
    protected $primaryKey = 'cate_id_sub';
    public $timestamps = false;
    protected $guarded = ['cate_id_sub'];

    public function get_all()
    {
        return blog_category_sub::join('blog_category','blog_category_sub.cate_pid_sub','=','blog_category.cate_id')
            ->where('blog_category_sub.cate_del_sub','0')
            ->orderBy('blog_category.cate_order','asc')
            ->get();
    }

    public function get_f_name()
    {
        return blog_category::select('cate_name','cate_id')->get();
    }
}
