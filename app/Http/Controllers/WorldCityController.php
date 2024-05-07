<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Worldcities;
use Illuminate\Support\Facades\DB;
use DataTables;

class WorldCityController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index(Request $request)
    {
        $data = Worldcities::select('id','name','country_code','created_at','updated_at')->orderBy('id','desc');
        if ($request->ajax()) {
            return Datatables::of($data)
                ->addColumn('created_at', function($row){
                        if($row->created_at !=""){
                            $created_at = date('d/m/Y h:i A', strtotime($row->created_at));
                            return $created_at;
                        }else{
                            $created_at ="";
                            return $created_at;
                        }
                })
                ->addColumn('updated_at', function($row){
                        if($row->updated_at !=""){
                            $updated_at = date('d/m/Y h:i A', strtotime($row->updated_at));
                            return $updated_at;
                        }else{
                            $updated_at ="";
                            return $updated_at;
                        }
                })
                ->addColumn('action', function($row){
                    $msg = "'Do you want to delete this City?'";
                            $action = '<a href="'.url('/world-city/'.$row->id.'/edit').'" class="editProduct badge badge-pill bg-success inv-badge">Edit</a>
                                <a href="javascript:void(0);" id="delete-world-city" data-toggle="tooltip" data-original-title="Delete" data-id="'.$row->id.'" class="badge badge-pill bg-success inv-badge deletecity">
                                    Delete
                                </a>';
                            return $action;
                    // if($row->status==1){
                    //     $msg = "'Do you want to delete this City?'";
                    //         $action = '<a href="'.url('/world-city/'.$row->id.'/edit').'" class="editProduct badge badge-pill bg-success inv-badge">Edit</a>
                    //             <a href="javascript:void(0);" id="delete-world-city" data-toggle="tooltip" data-original-title="Delete" data-id="'.$row->id.'" class="badge badge-pill bg-success inv-badge deletecity">
                    //                 Delete
                    //             </a>';
                    //         return $action;
                    // }else{
                    //     $msg = "'Do you want to delete this City?'";
                    //         $action = '<a href="javascript:void(0);" class="badge badge-pill bg-success inv-badge" id="EditAcess">Edit</a>
                    //             <a href="javascript:void(0);" id="DeleteAcess" data-toggle="tooltip" data-original-title="Delete" class="badge badge-pill bg-success inv-badge">
                    //                 Delete
                    //             </a>';
                    //         return $action;
                    // }
                })

                ->rawColumns(['id','name','country_code','created_at','updated_at','action'])
                ->make(true);
        }
        return view('admin.worldcity.index-world-city');
    }
    public function create()
    {
        $countryData=DB::table('countries')->select('name','iso2')->get();
        return view('admin.worldcity.insert-world-city',compact(['countryData']));
    }
    public function store(Request $request)
    {
        $input=$request->all();
        $request->validate(
            [
                'country' => 'required',
                'name' => 'required|unique:worldcities',
                'timezone' => 'required',
                'latitude' => 'required|numeric',      //|between:-90,90'
                'longitude' => 'required|numeric',     //|between:-180,180
            ]
        );

        $Worldcities=new Worldcities();
        $Worldcities->name=$input['name'];
        $Worldcities->timezone=$input['timezone'];
        $Worldcities->latitude=$input['latitude'];
        $Worldcities->longitude=$input['longitude'];
        $Worldcities->country_code=$input['country'];
        $Worldcities->created_at=date('Y-m-d H:i:s');
        $Worldcities->status=1;
        $Worldcities->updated_at=NULL;
        $Worldcities->save();
        return back()->with("success", "City added successfully.");
    }
    public function show($id)
    {
        //
    }
    public function edit($id)
    {
        $countryData=DB::table('countries')->select('name','iso2')->get();
        $data = Worldcities::select('id','name','country_code','latitude','longitude','timezone')->find($id);
        return view('admin.worldcity.update-world-city',compact(['countryData','data']));
    }
    public function update(Request $request, $id)
    {
        $input=$request->all();
        $request->validate(
            [
                'country' => 'required',
                'name' => 'required|unique:worldcities,name,'.$id,
                'timezone' => 'required',
                'latitude' => 'required|numeric',      //|between:-90,90'
                'longitude' => 'required|numeric',     //|between:-180,180
            ]
        );
        $Worldcities=Worldcities::find($id);
        $Worldcities->name=$input['name'];
        $Worldcities->timezone=$input['timezone'];
        $Worldcities->latitude=$input['latitude'];
        $Worldcities->longitude=$input['longitude'];
        $Worldcities->country_code=$input['country'];
        $Worldcities->updated_at=date('Y-m-d H:i:s');
        $Worldcities->save();
        return redirect('world-city')->with("success", "City Updated successfully.");
    }
    public function destroy($id)
    {
        $city=Worldcities::find($id)->delete();
        return response()->json(['success'=>'City deleted successfully.']);
    }
}
