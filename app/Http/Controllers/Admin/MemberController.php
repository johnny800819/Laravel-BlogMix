<?php

namespace App\Http\Controllers\Admin;

use App\Http\Model\blog_member;
use function GuzzleHttp\Psr7\copy_to_string;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;

class MemberController extends Controller
{
    public function index()
    {
        $data = blog_member::all();
        return view('Admin.Member.member-list')->with(compact('data'));
    }

    public function edit($id)
    {
        $data = blog_member::find($id);
        //dd($data);
        return view('Admin.Member.member-edit')->with(compact('data'));
    }

    public function update($id)
    {
        $input = Input::all();

        $member = blog_member::where('id',$id)->firstOrFail();
        $messages = [
            'password.required'  => '密碼為空值！',
            'password.confirmed' => '密碼確認不一致！',
        ];
        $validator = Validator::make($input, [
            'password' => 'between:5,16|confirmed|required',
        ], $messages);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors());
        }
        else{
            $pd = Hash::make($input['password']);
            //echo $re = Hash::check('123',$pd);
            $member->password = $pd;
            $member->mem_address1 = $input['mem_address1'];
            $member->mem_address2 = $input['mem_address2'];
            $member->mem_address3 = $input['mem_address3'];
            $member->mem_lock = $input['mem_lock'][0];
            $member->update();
            return redirect('admin/member');
        }
    }

    public function destroy($id,Request $request)
    {
        //封鎖或復權帳號
        $db = blog_member::where('id','=',$id)->first();
        $db->mem_lock = $request->input('mem_lock');
        $re = $db->update();
        if($re){
            return 1;
        }
    }

    public function show()
    {
        $data = blog_member::all();
        return view('Admin.Member.member-list')->with(compact('data'));
    }
}
