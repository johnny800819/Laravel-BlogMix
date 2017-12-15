<?php

namespace App\Http\Model;

use Illuminate\Database\Eloquent\Model;

class blog_article extends Model
{
    protected $table = 'blog_article';
    protected $primaryKey = 'art_id';
    public $timestamps = false;
    protected $guarded = [];

    public function get_all()
    {
        return blog_article::join('blog_category','blog_article.cate_id','=','blog_category.cate_id')
            ->join('blog_category_sub','blog_article.cate_id_sub','=','blog_category_sub.cate_id_sub')
            ->orderBy('blog_article.art_id','desc')
            ->get();
    }
}
