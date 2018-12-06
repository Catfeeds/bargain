<?php
/**
 * Created by PhpStorm.
 * User: zeng
 * Date: 2018/12/5
 * Time: 下午5:24
 */

namespace App\Modules\City;


use App\Modules\City\Model\City;
use Illuminate\Support\Facades\DB;

trait CityHandle
{
    /**
     * 添加城市
     * @param $id
     * @param $data
     * @param bool $returnId
     * @return bool|mixed
     */
    public function addCity($id,$data,$returnId=false)
    {
        $city = $id?City::find($id):new City();
        foreach ($data as $key => $value){
            $city->$key = $value;
        }
        if ($city->save()){
            return $returnId?$city->id:true;
        }
        return false;
    }
    public function delCity($id)
    {
        return City::find($id)->delete();
    }
    public function getCity($id)
    {
        return City::find($id);
    }
    public function getCities(int $page=1,int $limit=10,string $name='',bool $returnArray=false)
    {
        $db = DB::table('cities');
        if (strlen($name)!=0){
            $db->where('name','like','%'.$name.'%');
        }
        $db->limit($limit)->offset(($page-1)*$limit)->orderBy('id','DESC');
        $count = $db->count();
        $data = $returnArray?$db->get()->toArray():$db->get();
        return [
            'data'=>$data,
            'count'=>$count
        ];
    }
    public function treeCities()
    {
        $cities = City::all()->toArray();
        $level1 = array_merge(array_filter($cities,function ($item){
            return $item['parent_id']==0;
        }));
        if (!empty($level1)){
            $count = count($level1);
            for ($i=0;$i<$count;$i++){
                $id = $level1[$i]['id'];
                $swap = array_merge(array_filter($cities,function ($item) use($id){
                    return $item['parent_id']==$id;
                }));
                if (!empty($swap)){
                    $swapCount = count($swap);
                    for ($j=0;$j<$swapCount;$j++){
                        $id = $swap[$j]['id'];
                        $child = array_merge(array_filter($cities,function ($item) use($id){
                            return $item['parent_id']==$id;
                        }));
                        $swap[$j]['child'] = $child;
                    }
                }
                $level1[$i]['child'] = $swap;
            }
        }
        return $level1;
    }
}