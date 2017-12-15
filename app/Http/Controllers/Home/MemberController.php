<?php

namespace App\Http\Controllers\Home;

use App\Http\Model\blog_member;
use App\Http\Model\blog_servicelist;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Home\HomeController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;

class MemberController extends HomeController
{
    public function loginPage()
    {
        if(session('status') != 'login') {
            return view('Home.Member.login');
        }else{
            return redirect('/');
        }
    }

    public function login(Request $request = null , $data = "")
    {
        //判斷登入ｏｒ註冊
        $input = (isset($request)) ? Input::all() : $data;

        //Auth驗證時，密碼參數部分要用原始碼，他套件會轉成Hash code去比對
        if (Auth::attempt(['email' => $input['email'], 'password' => $input['password']])) {
            $db_member = blog_member::find(Auth::user()->id);
            $db_member->mem_logintime = date('Y-m-d H:i:s');
            $db_member->mem_loginip = (isset($request)) ? $request->ip() : $input['ip'];
            $db_member->mem_count = ($db_member->mem_count)+1;
            $db_member->save();

            if($db_member->mem_lock != '0'){
                Auth::logout();
                session(['status' => '']);
                $errors = ['error' => '帳號已被鎖定，請聯絡客服人員！'];
                return redirect()->back()->with(compact('errors'));
            }
            // 認證通過...
            session(['status' => 'login']);

            //Redirect執行它只能從路由，控制器動作和過濾器中獲得。否則，你必須send()
            //但send不會儲存session資訊，因此如果是註冊行為，就不要在這方法內redirect
            if(isset($request)){
                return redirect('/');
            }else{
                return true;
            }
        }
        else{
            //errors變數要使用web中間層才會在blade中存在
            $errors = ['error' => '帳號或密碼錯誤，請重新登入！'];
            return redirect()->back()->with(compact('errors'));
        }
    }

    public function logout()
    {
        Auth::logout();
        session(['status' => '']);
        return redirect('/');
    }

    public function register(Request $request)
    {
//        $data = [
//            'email' => 'test1@gmail.com',
//            'password' => 'test1',
//        ];
//        $this->login(null,$data);
//        return redirect('/');
//        exit();
        $resource = (empty($_REQUEST['resource'])) ? 'normal' : $_REQUEST['resource'];
        switch($resource){
            case 'normal':
                $input = Input::except('_token');
                $messages = [
                    'email.required' => 'Email不可空白！',
                    'password.required' => '密碼為空值！',
                    'password.confirmed' => '密碼確認不一致！',
                ];
                $validator = Validator::make($input, [
                    'email' => 'required',
                    'password' => 'between:5,16|confirmed|required',
                ], $messages);

                if (!isset($input['mem_lock'])) {
                    $validator->errors()->add('errors', '請勾選同意！');
                    return redirect()->back()->withErrors($validator->errors());
                }

                if ($validator->fails()) {
                    return redirect()->back()->withErrors($validator->errors());
                } else {
                    try {
                        $in = Input::except('_token','password_confirmation');

                        $ee = blog_member::where('email',$in['email'])->get();

//                echo $in['email'].'<br>';
//                echo $ee.'<br>';
//                echo $ee->count().'<br>';
//                echo $ee->count() != '0'.'<br>';

                        if($ee->count() != '0'){
                            $validator->errors()->add('errors', '申請信箱已存在！');
                            return redirect()->back()->withErrors($validator->errors());
                        }

                        $in['password'] = Hash::make($input['password']);
                        $in['mem_createtime'] = date('Y-m-d H:i:s');
                        $re = blog_member::create($in);
                        if ($re) {
                            $data = [
                                'email' => $input['email'],
                                'password' => $input['password'],
                                'ip' => $request->ip(),
                            ];
                            $this->login(null,$data);
                            return redirect('/');
                        }
                    } catch (\Exception $e) {
                        $validator->errors()->add('db_error', '資料庫發生問題！<BR>' . substr($e, 0, 200) . '...');
                        return redirect()->back()->withErrors($validator->errors());
                    }
                }
                break;
            case 'facebook':
                return '成功登入… '.' 您的信箱是: '.$_REQUEST['email'];
                break;
        }
    }

    public function member_profile()
    {
        if(Auth::check()){
            $id = Auth::user()->id;
            $data = blog_member::find($id);
            if(isset($data)){
                return view('Home.Member.member-profile')->with(compact('data'));
            }
        }else{
            return redirect('/');
        }
    }

    public function member_profile_update($id)
    {
        $input = Input::except('_token','_method');

        $messages = [
            'password.confirmed' => '密碼確認不一致！',
        ];
        $validator = Validator::make($input, [
            'password' => 'between:4,16|confirmed',
        ], $messages);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors());
        }else{
            $in = Input::except('_token','_method','password_confirmation');
            $in['password'] = Hash::make($input['password']);
            $re = blog_member::find($id)->update($in);
            $errors = ($re)?'資料更新成功！':'資料庫發生問題！';
            return redirect()->back()->with(compact('errors'));
        }
    }

    public function member_add_service()
    {
        return view('Home.Member.member-add-service');
    }

    public function mad_action()
    {
        $input = Input::all();
        switch($input['connecttype']){
            case 'system':
                if(Auth::check()){
                    DB::table('blog_service_list')->insert(
                        [
                            'slist_type' => $input['slist_type'],
                            'slist_time' => date('Y-m-d H:i:s'),
                            'slist_user_id' => Auth::user()->id,
                            'slist_ip' => \Illuminate\Support\Facades\Request::ip(),
                            'slist_theme' => $input['slist_theme'],
                            'slist_question' => $input['slist_question']
                        ]
                    );
                    return redirect('member-re-service');
                }
                break;
            case 'email':
                //配置第三方邮件发送服务MAILGUN,須配置.env,config/mail.php及config/services.php共3項
                $value = '';
                Mail::send('welcome', ['key' => $value], function($message){
                    //$m->from('ge-10135@mail.taipei.gov.tw', 'Your Application');

                    $message->to('johnny800819@gmail.com')->subject('Johnny\'s blog, Your Reminder!');
                    echo 'Mail sended ! Please watch your mailbox with personal account.';
                    return $message;
                });
                break;
        }
    }

    public function member_re_service()
    {
        $data = blog_servicelist::where('slist_user_id',Auth::user()->id)->orderby('slist_time','desc')->paginate(4);
        return view('Home.Member.member-re-service')->with(compact('data'));
    }
}
