<?php

namespace App\Http\Controllers\Admin;

use App\Http\Model\blog_config;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;

class ConfigController extends Controller
{
    public function index()
    {
        $data = blog_config::orderBy('conf_order','asc')->get();

        foreach($data as $colum){
            switch($colum['field_type']){
                case 'input':
                    $colum['_html'] = '<input name="conf_content[]" class="form-control" style="width: 100%" type="text" value="'.$colum['conf_content'].'">';
                    break;
                case 'textarea':
                    $colum['_html'] = '<textarea name="conf_content[]" class="form-control" style="width: 100%" rows="5">'.$colum['conf_content'].'</textarea>';
                    break;
                case 'radio':
                    $parse1 = explode(',',$colum['field_value']);
                    $conbime = '';
                    foreach ($parse1 as $val){
                        $parse2 = explode('|',$val);
                        $chk = ($colum['conf_content'] == $parse2[0]) ? ' checked ' : '';
                        $conbime .= '<input type="radio" name="conf_content[]"  value="'.$parse2[0].'"'.$chk.'>'.$parse2[1].'&nbsp;&nbsp;&nbsp;&nbsp;';
                    }
                    //$colum['_html'] = $conbime;
                    $colum['_html'] = '<input name="conf_content[]" value="1" type="hidden">radio只要填了name attribute就沒辦法預設checked,尚未解決...';
                    break;
                case 'select':
                    $parse1 = explode(',',$colum['field_value']);
                    $conbime = '<select name="conf_content[]">';
                    foreach ($parse1 as $val){
                        $parse2 = explode('|',$val);
                        $sel = ($colum['conf_content'] == $parse2[0]) ? ' selected ' : '';
                        $conbime .= '<option value="'.$parse2[0].'"'.$sel.'>'.$parse2[1].'</option>';
                    }
                    $colum['_html'] = $conbime.'</select>';
                    break;
                default:
                    break;
            }
        }
        return view('Admin.Config.config-list')->with(compact('data'));
    }

    public function create()
    {
        return view('Admin.Config.add-config');
    }

    public function store()
    {
        $input = Input::except('_token');

        $messages = [
            'conf_title.required'  => '標題不可空白！',
            'conf_name.required'  => '名稱不可空白！',
            'conf_order.required' => '順序不可空白！',
            'conf_order.integer' => '順序請輸入整數！',
        ];
        $validator = Validator::make($input, [
            'conf_title' => 'required',
            'conf_name' => 'required',
            'conf_order' => 'required|integer',
        ], $messages);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors());
        }
        else{
            try{
                $re = blog_config::create($input);
                if($re){
                    return redirect('admin/config');
                }
            }catch(\Exception $e){
                $validator->errors()->add('db_error', '資料庫發生問題！<BR>'.substr($e,0,200).'...');
                return redirect()->back()->withErrors($validator->errors());
            }
        }
    }

    //admin/config/{config}/edit
    public function edit($id)
    {
        $data = blog_config::find($id);
        return view('Admin.Config.edit-config')->with(compact('data'));
    }

    public function update($conf_id)
    {
        $input = Input::except('_method','_token');
        if($input['field_type'] != 'radio' && $input['field_type'] != 'select')$input['field_value'] = '';

        $re = blog_config::find($conf_id)->update($input);
        if ($re){
            $this->putFile();
            return redirect('admin/config');
        }else{
            $errors = '資料庫發生問題！';
            return redirect()->back()->with(compact('errors'));
        }
    }

    public function destroy($id)
    {
        $data = blog_config::where('conf_id','=',$id);
        $e = $data->delete();
        $return = [
            'result' => $e,
        ];
        $this->putFile();
        return $return;
    }

    public function configEdit()
    {
        $input = Input::all();
        foreach ($input['conf_id'] as $k=>$v){
            $np = blog_config::find($v);
            if($np->conf_content != $input['conf_content'][$k]){
                $np->conf_content = $input['conf_content'][$k];
                $np->save();
            }
        }
        $this->putFile();
        return redirect('admin/config');
//        DB::table('users')
//            ->where('id', 1)
//            ->update(['votes' => 1]);
    }

    //將web配置寫入laravel config文件夾下，以便使用 ex:Config::get('web.web_title');
    public function putFile()
    {
        $data = blog_config::pluck('conf_content','conf_name')->all();
        $str = '<?php return '.var_export($data,true).';';
        $path = config_path().'/web.php';
        file_put_contents($path,$str);
    }
}
