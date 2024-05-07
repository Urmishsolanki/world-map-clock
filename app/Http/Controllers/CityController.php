<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Response;
use App\Models\City;
use App\Models\Worldcities;

class CityController extends Controller
{
    public function index()
    {
        $data = DB::table('cities')->get();
        return view('admin.city.index',compact('data'));
    }

    public function create()
    {
        if (Auth::user()){
            return view('admin.city.add');
        }else{
            return redirect('/login');
        }
    }

    public function store(Request $request)
    {
        $input = $request->all();
        if($input['city_id']==""){
            $request->validate(
                [
                    'name' => 'required',
                    'worldcity_id' =>'required|unique:cities',
                ],
                [
                    'name.required' => 'City name is required field.',
                    'worldcity_id.unique' => 'This city name is already Added.',
                ]
            );
            $city=new City();
            $city->name=$input['name'];
            $city->worldcity_id=$input['worldcity_id'];
            $city->created_at=date('Y-m-d H:i:s');
            $city->updated_at=NULL;
            $city->save();
            return response()->json(['success'=>0]);
        }else{
            $request->validate(
                [
                    'name' => 'required',
                    'worldcity_id' =>'required|unique:cities,worldcity_id,'.$input['city_id'],
                ],
                [
                    'name.required' => 'City name is required field.',
                    'worldcity_id.unique' => 'This city name is already Added.',
                ]
            );

            $city=City::find($input['city_id']);
            $city->name=$input['name'];
            $city->worldcity_id=$input['worldcity_id'];
            $city->updated_at=date('Y-m-d H:i:s');
            $city->save();
            return response()->json(['success'=>1]);
        }

    }

    public function show($id)
    {

    }

    public function edit($id)
    {
        if (Auth::user()){
            $city = City::find($id);
            return response()->json($city);
        }else{
            return redirect('/login');
        }
    }

    public function get_city(Request $request){
        if ($request->query) {
            $inpText = $request->all();
            $query = trim(preg_replace('/,.*$/', '', $inpText['query']));
            //echo $query; exit;
            $result = DB::table('worldcities')
                        ->join('states', 'worldcities.state_id', '=', 'states.id')
                        //->join('countries', 'worldcities.country_id', '=', 'countries.id')
                        ->select('worldcities.name','worldcities.id','worldcities.country_code','states.name as state_name')
                        ->where('worldcities.name', 'LIKE', "{$query}%")->orderby('worldcities.name','ASC')->limit(20)->get();
            $str="";
            if($result){
                foreach($result as $resultsData){
                    $str.="<li value=".$resultsData->id.">".$resultsData->name.", ".$resultsData->state_name." (".$resultsData->country_code.")"."</li>";
                }
            }else{
                $str="";
            }
            exit(json_encode(array('data' => $str)));
          }
    }

    public function update(Request $request, $id)
    {
        // $request->validate(
        //     [
        //         'name' => 'required|unique:cities,name,'.$id,
        //     ],
        //     [
        //         'name.required' => 'City name is required field.',
        //         'name.unique' => 'This city name is already Added.',
        //     ]
        // );

        // $input = $request->all();

        // $city=City::find($id);
        // $city->name=$input['name'];
        // $city->updated_at=date('Y-m-d H:i:s');
        // $city->save();

        // return redirect('home')->with('message','City Updated Successfully.');
    }

    public function destroy($id)
    {
        $city=City::find($id)->delete();
        return response()->json(['success'=>'City deleted successfully.']);
    }

    public function getCity(){
        $city = City::latest()->get();

        foreach($city as $citydata){
            $worldcity_id[]=$citydata->worldcity_id;
            $worldcities = DB::table('worldcities')
                        ->whereIn('id',$worldcity_id )
                        ->select(
                                'name',
                                'latitude',
                                'longitude',
                                'timezone'
                            )
                        ->get();

        }
        foreach ($worldcities as $worldcitiesValue) {
            $worldcitiesdata[] = array(
                                    'name' => $worldcitiesValue->name,
                                    'latitude' => $worldcitiesValue->latitude,
                                    'longitude' => $worldcitiesValue->longitude,
                                    'timezones' => $worldcitiesValue->timezone,
                                    );
        }

        exit(json_encode($worldcitiesdata));
    }
}

