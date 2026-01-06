<?php

namespace App\Http\Controllers\Admin;

use App\Http\Model\blog_servicelist;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;

class ServiceListController extends Controller
{
    public function index()
    {
        $data = blog_servicelist::all();
        return view('Admin.ServiceList.service-list')->with(compact('data'));
    }

    public function edit($slist_id)
    {
        $obj = new blog_servicelist();
        $data = $obj->joinuserinfo($slist_id)->first();
        return view('Admin.ServiceList.service-edit')->with(compact('data'));
    }

    public function update($slist_id)
    {
        $input = Input::except('_token','_method');
        $db = blog_servicelist::find($slist_id);
        $db->slist_status = $input['optionsRadios'][0];
        $db->slist_response = $input['slist_response'];
        $db->response_time = date('Y-m-d H:i:s');
        $db->save();
        return redirect('admin/service-list');
    }
}
