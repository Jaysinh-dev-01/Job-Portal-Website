<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Models\JobApplication;
use App\Http\Controllers\Controller;
use RealRashid\SweetAlert\Facades\Alert;

class ApplicantController extends Controller
{
    public function index(){
        $applicants = JobApplication::with(['job','user'])->orderBy('created_at','DESC')->paginate(10);
        
        return view('admin.applicants.applicantsList',[
            'applicants' => $applicants
        ]);
    }

    public function deleteApplication(Request $request){
        
        $user = JobApplication::where([
            'id' => $request->jobAppId
        ])->exists();
           
        if ($user == false) {
            session()->flash('error', Alert::error('Oops!', "Job Application not found"));
            return response()->json([
                'status' => true
            ]);
        }

        JobApplication::find($request->jobAppId)->delete();
        session()->flash('success', Alert::success('Success', "Job Application Deleted Successfully"));

        return response()->json([
            'status' => true
        ]); 
    }
}
