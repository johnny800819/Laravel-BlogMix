<?php

namespace App\Http\Controllers\Admin;

use App\Http\Model\blog_user;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;


require_once __DIR__ . '/includes/autoload.php';

class AdminController extends Controller
{
    public function index()
    {
        $site_data = [
            'siteKey' => '6Ld0bjMUAAAAAN-BLWDEQP1eT2jTP_DxjaeRBXX7',
            'lang' => 'zh-TW',
        ];
        $err_log = '';
        if($input = Input::all()) {
            $secret = '6Ld0bjMUAAAAAC1NJQkXSFStTnjBk1U8oITiqhYf';
            $recaptcha = new \ReCaptcha\ReCaptcha($secret);
            // 確認驗證碼與 IP
            $resp = $recaptcha->verify($_POST['g-recaptcha-response'], $_SERVER['REMOTE_ADDR']);
            if (!($resp->isSuccess())) {
//              foreach ($resp->getErrorCodes() as $code) {echo '<p>' . $code . '</p> ';}
                $err_log = 'Please Enter Authentication';
                return view('Admin.login',compact('site_data','err_log'));
            }
            $TableData = blog_user::where('user_id',1)->firstOrFail();
            if($TableData->user_email != $input['email'] || decrypt($TableData->user_pass) != $input['pass']){
                $err_log = 'Email or Password error';
                return view('Admin.login',compact('site_data','err_log'));
            }
            session([
                'user'=>$TableData
            ]);
            return redirect('admin/main-page');
        }else{
            return view('Admin.login',compact('site_data','err_log'));
        }
    }

    public function admin_list()
    {
        $data = blog_user::all();
        return view('Admin.admin-list')->with(compact('data'));
    }

    public function main_page()
    {
        return view('Admin.main-page');
    }

    public function admin_logout()
    {
        session(['user'=>null]);
        return redirect('admin/login');
    }

    public function pass_edit(Request $request)
    {
        if($request->isMethod('post') && $input = Input::all()){
            $admin_user = blog_user::where('user_id',1)->firstOrFail();
            $messages = [
                'password_o.required'  => '原密碼為空值！',
                'password_n.required'  => '新密碼為空值！',
                'password_n.between'   => '新密碼請輸入5-16位數！',
                'password_n.confirmed' => '新密碼確認不一致！',
            ];
            $validator = Validator::make($input, [
                'password_o' => 'required',
                'password_n' => 'between:5,16|confirmed|required',
            ], $messages);

            if(decrypt($admin_user->user_pass) != $input['password_o'])
            {
                /*return redirect()->back()->with('errors','原密碼輸入錯誤！');*/
                $validator->errors()->add('anyway', '原密碼輸入錯誤！');
                return redirect()->back()->withErrors($validator->errors());
            }

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator->errors());
            }
            else{
                $admin_user->user_pass = encrypt($input['password_n']);
                $admin_user->update();
                return redirect()->back()->with('success','密碼更改成功！下次登入請使用新密碼');
            }
        }else{
            return view('Admin.pass-edit');
        }
    }
}
