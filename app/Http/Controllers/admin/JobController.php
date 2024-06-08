<?php

namespace App\Http\Controllers\admin;

use App\Models\Job;
use App\Models\JobType;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Validator;

class JobController extends Controller
{
    public function index(){
       $jobs =  Job::orderBy('created_at','DESC')->paginate(10);
       return view('admin.jobs.jobsList',['jobs'=>$jobs]);
    }

    public function editJob($id){
        $categories = Category::orderBy('name', 'ASC')->get();
        $JobTypes = JobType::orderBy('name', 'ASC')->get();
       $job = Job::findOrFail($id);
       return view('admin.jobs.jobEdit',['job'=>$job,'categories'=>$categories,'jobTypes'=>$JobTypes]);
    }

    public function updateJob(Request $request, $id)
    {
       

        $validation = Validator::make($request->all(), [
            'title' => 'required | min:5 | max:35',
            'category' => 'required',
            'jobType' => 'required',
            'vacancy' => 'required | integer',
            'location' => 'required',
            'experience' => 'required',
            'description' => 'required | max:500',
            'company_name' => 'required | min:3 | max:35',

        ]);

        if ($validation->passes()) {
            $job = Job::find($id);
            $job->title = $request->title;
            $job->category_id = $request->category;
            $job->job_type_id = $request->jobType;
            $job->vacancy = $request->vacancy;
            $job->salary = $request->salary;
            $job->location = $request->location;
            $job->description = $request->description;
            $job->benefits = $request->benefits;
            $job->responsibility = $request->responsibility;
            $job->qualifications = $request->qualifications;
            $job->keywords = $request->keywords;
            $job->experience = $request->experience;
            $job->company_name = $request->company_name;
            $job->company_location = $request->company_location;
            $job->company_website = $request->website;
            $job->isFeatured = (!empty($request->isFeatured)) ? $request->isFeatured : 0;
            $job->status = $request->status;
            $job->save();

            session()->flash('success', Alert::success('Success', 'Your Job Updated Successfully'));

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

    public function deleteJob(Request $request){
        
        $user = Job::where([
            'id' => $request->jobId
        ])->exists();
           
        if ($user == false) {
            session()->flash('error', Alert::error('Oops!', "Job not found"));
            return response()->json([
                'status' => true
            ]);
        }

        Job::find($request->jobId)->delete();
        session()->flash('success', Alert::success('Success', "Job Deleted Successfully"));

        return response()->json([
            'status' => true
        ]); 
    }
}
