<?php

namespace App\Http\Controllers\Admin;

use App\Http\Model\blog_category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Http\Requests;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;

class CategoryMainController extends Controller
{
    /**
     * Display a listing of the resource.
     * admin/article-category
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categorys = blog_category::where('cate_del','0')->orderBy('cate_order','asc')->get();
        return view('Admin.Category.article-category-0')->with('data',$categorys);
    }

    /**
     * Show the form for creating a new resource.
     * admin/article-category/create
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $input = Input::all();
        $category = blog_category::where('cate_id','=',$input['cate_id'])->firstOrFail();
        $category->cate_order = $input['cate_order'];
        $re = $category->update();
        if($re){
            $data = [
                'status' => 1,
                'msg' => '排序順位更新成功！',
            ];
        }else{
            $data = [
                'status' => 0,
                'msg' => '排序順位更新失敗！',
            ];
        }
        return $data;
    }

    /**
     * Store a newly created resource in storage.
     * admin/article-category
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = $request->except('_token');
        switch ($input['ModalType']){
            case 'Add':
                blog_category::create($request->except('_token','ModalType'));
                break;
            case 'Edit':
                blog_category::where('cate_id',$input['cate_id'])
                    ->update($request->except('_token','ModalType'));
                break;
        }
        return redirect('admin/article-category-main');
    }

    /**
     * Display the specified resource.
     * admin/article-category/{article_category}
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     * admin/article-category/{article_category}/edit
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = blog_category::find($id);
        return $data;
    }

    /**
     * Update the specified resource in storage.
     * admin/article-category/{article_category}
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * admin/article-category/{article_category}
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data = blog_category::find($id);
        $data->cate_del = 1;
        $e = $data->save();
        $return = [
            'result' => $e,
        ];
        return $return;
    }
}
