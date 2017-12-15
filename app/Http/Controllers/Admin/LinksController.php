<?php

namespace App\Http\Controllers\Admin;

use App\Http\Model\blog_links;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;

class LinksController extends Controller
{
    public function index()
    {
        $data = blog_links::orderBy('link_order','asc')->get();
        return view('Admin.Links.ad-links-list')->with(compact('data'));
    }

    public function create()
    {
        return view('Admin.Links.add-ad-links');
    }

    public function store()
    {
        $input = Input::except('link_toggle','_token');

        $messages = [
            'link_title.required'  => '標題不可空白！',
            'link_url.required'  => '連結不可空白！',
            'link_url.url'  => 'This URL is non-available！',
            'link_description.required'  => '文章內容不可空白！',
            'link_order.required' => '順序不可空白！',
            'link_order.integer' => '順序請輸入整數！',
        ];
        $validator = Validator::make($input, [
            'link_title' => 'required',
            'link_url' => 'required|url',
            'link_description' => 'required',
            'link_order' => 'required|integer',
        ], $messages);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors());
        }
        else{
            try{
                $re = blog_links::create($input);
                if($re){
                    return redirect('admin/ad-links');
                }
            }catch(\Exception $e){
                $validator->errors()->add('db_error', '資料庫發生問題！<BR>'.substr($e,0,200).'...');
                return redirect()->back()->withErrors($validator->errors());
            }
        }
    }

    //admin/ad-links/{ad_links}/edit
    public function edit($id)
    {
        $data = blog_links::find($id);
        return view('Admin.Links.edit-ad-links')->with(compact('data'));
    }

    public function update($link_id)
    {
        $input = Input::except('link_toggle','_method','_token');
        $re = blog_links::find($link_id)->update($input);
        if ($re){
            return redirect('admin/ad-links');
        }else{
            $errors = '資料庫發生問題！';
            return redirect()->back()->with(compact('errors'));
        }
    }

    public function destroy($id)
    {
        $data = blog_links::where('link_id','=',$id);
        $e = $data->delete();
        $return = [
            'result' => $e,
        ];
        return $return;
    }
}
