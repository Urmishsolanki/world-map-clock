<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\City;
use App\Models\Worldcities;
use DataTables;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index(Request $request)
    {

        $data = City::latest()->orderBy('id','desc')->get();

        if ($request->ajax()) {
            return Datatables::of($data)
                ->addColumn('id', function($row){
                        if($row->id !="" ){
                            $id = $row->id;
                            return $id;
                        }else{
                            $id ="";
                            return $id;
                        }
                    })
                ->addColumn('name', function($row){
                        if($row->name !="" ){
                            $name = $row->name;
                            return $name;
                        }else{
                            $name ="";
                            return $name;
                        }
                    })
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
                            $updated_at ="-";
                            return $updated_at;
                        }
                    })
                ->addColumn('action', function($row){
                    $msg = "'Do you want to delete this City?'";
                        $action = '<a href="javascript:void(0);" class="editProduct badge badge-pill bg-success inv-badge" data-id="'.$row->id.'">Edit</a>
                            <a href="javascript:void(0);" id="delete-city" data-toggle="tooltip" data-original-title="Delete" data-id="'.$row->id.'" class="badge badge-pill bg-success inv-badge deletecity">
                                Delete
                            </a>';
                        return $action;
                    })

                ->rawColumns(['id','name','created_at','updated_at','action'])
                ->make(true);
        }
        return view('admin.index');
    }

    public function userView(){
        if (Auth::check())
        {
            $id = Auth::user()->id;
            $user = DB::table('users')->where('id',"=",$id)->get();

            return view('admin.update-profile',compact('user'));
        }
    }

    public function userUpdate(Request $request){
        if (Auth::check())
            {
                $id = Auth::user()->id;

                $request->validate([
                    'name' => 'required|max:50',
                    'email' =>'required|email|max:50|unique:users,email,'.$id ,
                ]);

                $values = array('name' => $request->name,'email' =>$request->email);
                DB::table('users')->where('id',"=",$id)->update($values);

                if($request->old_password!=""){
                    $request->validate([
                        'old_password' => 'required',
                        'password' => ['required', 'string', 'min:8', 'confirmed'],
                    ], [
                        'old_password.required' => 'Please enter your old password.',
                        'password.required' => 'Please enter a new password.',
                        'password.string' => 'The password must be a string.',
                        'password.min' => 'The password must be at least 8 characters.',
                        'password.confirmed' => 'The password confirmation does not match.',
                    ]);
                    if(Hash::check($request->old_password , auth()->user()->password)) {
                        if(!Hash::check($request->password , auth()->user()->password)) {
                           $user = User::find(auth()->id());
                           $user->update([
                               'password' => bcrypt($request->password)
                           ]);
                            return back()->with("success", "Password changed successfully!");
                        }
                        return back()->with("error", "New password can not be the old password!");
                    }
                    return back()->with("error", "Old password does not matched!");
                }
                return redirect('user')->with('success','Your Info Updated.');
            }
    }
}
