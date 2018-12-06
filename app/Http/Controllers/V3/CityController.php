<?php

namespace App\Http\Controllers\V3;

use App\Modules\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;

class CityController extends Controller
{
    //
    public function __construct()
    {
        $this->handle = new User();
    }
    public function addCity(Request $post)
    {
        $id = Input::get('id',0);
        $data = [
            'name'=>$post->name,
            'parent_id'=>$post->parent_id?$post->parent_id:0
        ];
        if ($this->handle->addCity($id,$data)){
            return jsonResponse([
                'msg'=>'ok'
            ]);
        }
        throw new \Exception('参数错误！');
    }
    public function delCity()
    {
        $id = Input::get('id');
        if ($this->handle->delCity($id)){
            return jsonResponse([
                'msg'=>'ok'
            ]);
        }
        throw new \Exception('系统错误！');
    }
    public function getCities()
    {
        $name = Input::get('name','');
        $page = Input::get('page',1);
        $limit = Input::get('limit',10);
        $data = $this->handle->getCities($page,$limit,$name);
        return jsonResponse([
            'msg'=>'ok',
            'data'=>$data
        ]);
    }
    public function getCitiesTree()
    {
        $data = $this->handle->treeCities();
        return jsonResponse([
            'msg'=>'ok',
            'data'=>$data
        ]);
    }
}
