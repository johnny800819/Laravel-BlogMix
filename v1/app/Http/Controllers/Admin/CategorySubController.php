<?php

namespace App\Http\Controllers\Admin;

use App\Http\Model\blog_category_sub;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;

class CategorySubController extends Controller
{
    public function index()
    {
        $category_sub_obj = new blog_category_sub;
        $category_sub_data = $category_sub_obj->get_all();
        $data = $category_sub_data;
        $f_name = $category_sub_obj->get_f_name();
        return view('Admin.Category.article-category-1')->with(compact('data','f_name'));
    }
    public function store(Request $request)
    {
        $input = $request->except('_token');
        switch ($input['ModalType']){
            case 'Add':
                blog_category_sub::create($request->except('_token','ModalType'));
                break;
            case 'Edit':
                blog_category_sub::where('cate_id_sub',$input['cate_id_sub'])
                    ->update($request->except('_token','ModalType'));
                break;
        }
        return redirect('admin/article-category-sub');
    }
    public function edit($id)
    {
        $data = blog_category_sub::find($id);
        return $data;
    }
    public function destroy($id)
    {
        $data = blog_category_sub::find($id);
        $data->cate_del_sub = 1;
        $e = $data->save();
        $return = [
            'result' => $e,
        ];
        return $return;
    }
}
