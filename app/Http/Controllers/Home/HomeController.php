<?php

namespace App\Http\Controllers\Home;

use App\Http\Model\blog_article;
use App\Http\Model\blog_cart;
use App\Http\Model\blog_cart_item;
use App\Http\Model\blog_links;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;

class HomeController extends Controller
{
    public function __construct()
    {
        //取得廣告參數
        $ad = blog_links::all();
        View::share('ad',$ad);

        //取得個人購物車物品總數
        if(Auth::check()){
            $cart = new blog_cart();
            //先where好條件（find和get的回傳型態一樣,因此where->get = find）再做call function的動作
            $items = $cart->where('cart_user_id','=',Auth::user()->id)->first();
            $cart_item_count = empty($items) ? 0 : count($items->cartItems()->get()->toArray());
            View::share('cart_item_count',$cart_item_count);
        }
    }
    public function index()
    {
        //瀏覽率最高的5篇文章
        $hot = blog_article::orderby('art_view','desc')->take(5)->get();

        //最新的6篇文章
        $new = blog_article::orderby('art_time','desc')->take(6)->get();

        return view('Home.Article.index')->with(compact('hot','new'));
    }

    public function ListFun()
    {
        //一次取4篇文章（含分頁功能）
        $article = blog_article::orderby('art_time','desc')->paginate(4);

        //前台要的資訊傳遞
        $count = blog_article::count();

        return view('Home.Article.list')->with(compact('article','count'));
    }

    public function NewFun($art_id)
    {
        blog_article::where('art_id','=',$art_id)->increment('art_view');

        $data = blog_article::join('blog_category', 'blog_category.cate_id', '=', 'blog_article.cate_id')
                            ->join('blog_category_sub', 'blog_category_sub.cate_id_sub', '=', 'blog_article.cate_id_sub')
                            ->where('art_id','=',$art_id)->first();

//        dd($data);

        return view('Home.Article.new')->with(compact('data'));
    }

    public function test()
    {
        //echo phpinfo();
        //return view('GAtest');
        return view('smoole');
    }
}
