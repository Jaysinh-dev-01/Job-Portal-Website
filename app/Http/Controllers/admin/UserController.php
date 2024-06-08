<?php

namespace App\Http\Controllers\admin;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function index(){
        $users = User::orderBy('created_at','DESC')->paginate(10);
        return view('admin.users.list',['users'=>$users]);
    }
    
    public function editUser($id){
        $user = User::findOrFail($id);
        return view('admin.users.editUser',['user'=>$user]);
    }

    public function updateUser(Request $request,$id){
       
        $validation = Validator::make($request->all(), [
            'name' => 'required|min:5|max:25',
            'email' => 'required | email | unique:users,email,' . $id . ',id',
        ]);

        if ($validation->passes()) {
            $user = User::find($id);
            $user->name = $request->name;
            $user->email = $request->email;
            $user->designation = $request->designation;
            $user->mobile = $request->mobile;
            $user->save();
            session()->flash('success', Alert::success('Updated', 'User Profile Successfully Updated'));
            return response()->json([
                'status' => true,
                'errors' => null
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validation->errors()
            ]);
        }
    }

    public function deleteUser(Request $request){
        
        $user = User::where([
            'id' => $request->userId
        ])->exists();
           
        if ($user == false) {
            session()->flash('error', Alert::error('Oops!', "User not found"));
            return response()->json([
                'status' => true
            ]);
        }

        User::find($request->userId)->delete();
        session()->flash('success', Alert::success('Success', "User Record Deleted Successfully"));

        return response()->json([
            'status' => true
        ]); 
    }
}
