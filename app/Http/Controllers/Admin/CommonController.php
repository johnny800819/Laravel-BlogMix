<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;

class CommonController extends Controller
{
    public function upload()
    {
        $file = Input::file('Filedata');
        if($file->isValid()){
            $tempName = $file->getFileName(); //seems likes php9372.tmp
            $realPath = $file->getRealPath(); //temp routes
            $entension = $file->getClientOriginalExtension();
            $mimeType = $file->getMimeType(); //mimetype likes image/jpeg
            $newName = date('YmdHis').mt_rand(100,300).'.'.$entension;
            $newPath = storage_path().'/uploads';
            $file->move($newPath,$newName);

            $data = array(
                'tempName' => $tempName,
                'realPath' => $realPath,
                'filePath' => 'uploads/'.$newName,
                'newName' => $newName,
            );
            return json_encode($data);
        }
    }
}
