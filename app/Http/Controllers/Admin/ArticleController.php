<?php

namespace App\Http\Controllers\Admin;

use App\Http\Model\blog_article;
use App\Http\Model\blog_category;
use App\Http\Model\blog_category_sub;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;

class ArticleController extends Controller
{
    //name : admin.article.create
    public function index()
    {
        $obj = new blog_article();
        $data = $obj->get_all();
        return view('Admin.Article.article-list')->with(compact('data'));
    }

    //name : admin.article.create
    public function create()
    {
        $f_name = blog_category::where('cate_del','=','0')->get();
        $sub_name = blog_category_sub::where('cate_del_sub','=','0')->get();
        return view('Admin.Article.add-article')->with(compact('f_name','sub_name'));
    }

    //name : admin.article.store
    public function store()
    {
        $input = Input::except('_token');

        $messages = [
            'art_title.required'  => '標題不可空白！',
            'art_time.required'  => '發布時間不可空白！',
            'art_content.required'  => '文章內容不可空白！',
        ];
        $validator = Validator::make($input, [
            'art_title' => 'required',
            'art_time' => 'required',
            'art_content' => 'required',
        ], $messages);

        if($input['art_content'] == '<p>&nbsp;</p>'){
            $validator->errors()->add('art_content_error', '文章內容不可空白！');
            return redirect()->back()->withErrors($validator->errors());
        }

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors());
        }
        else{
            try{
                $re = blog_article::create($input);
                if($re){
                    return redirect('admin/article');
                }
            }catch(\Exception $e){
                $validator->errors()->add('db_error', '資料庫發生問題！<BR>'.substr($e,0,200).'...');
                return redirect()->back()->withErrors($validator->errors());
            }
        }
    }

    public function edit($art_id)
    {
        $f_name = blog_category::where('cate_del','=','0')->get();
        $sub_name = blog_category_sub::where('cate_del_sub','=','0')->get();
        $data = blog_article::find($art_id);
        return view('Admin.Article.edit-article')->with(compact('f_name','sub_name','data'));
    }

    public function update($art_id)
    {
        $input = Input::except('_method','_token');
        $re = blog_article::find($art_id)->update($input);
        if ($re){
            return redirect('admin/article');
        }else{
            $errors = '資料庫發生問題！';
            return redirect()->back()->with(compact('errors'));
        }
    }

    public function destroy($id)
    {
        $data = blog_article::where('art_id','=',$id);
        $e = $data->delete();
        $return = [
            'result' => $e,
        ];
        return $return;
    }
}
