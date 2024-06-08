<?php

namespace App\Http\Controllers;

use App\Jobs\SlowMailJob;
use App\Models\Job;
use App\Models\User;
use App\Models\JobType;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\JobApplication;
use App\Mail\jobNotificationMail;
use App\Models\SavedJob;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use RealRashid\SweetAlert\Facades\Alert;

class JobsController extends Controller
{
    // This is method for showing jobs page
    public function index(Request $request)
    {
        $category = Category::where('status', 1)->get();
        $jobType = JobType::where('status', 1)->get();
        $jobs = Job::where('status', 1);

        // Search Using Keywords
        if (!empty($request->keywords)) {
            $jobs = $jobs->where(function ($query) use ($request) {
                $query->orWhere('title', 'like', '%' . $request->keywords . '%');
                $query->orWhere('keywords', 'like', '%' . $request->keywords . '%');
            });
        }


        // search using location
        if (!empty($request->location)) {
            // dd($request->all()); 
            $jobs = $jobs->where(function ($query) use ($request) {
                $query->orWhere('location', 'like', '%' . $request->location . '%');
                $query->orWhere('keywords', 'like', '%' . $request->location . '%');
            });
        }

        // search using category
        if (!empty($request->category)) {
            $jobs = $jobs->where('category_id', $request->category);
        }

        // search using experience
        if (!empty($request->experience)) {
            $jobs = $jobs->where('experience', $request->experience);
        }

        $jobTypeArray = [];
        // search using jobType
        if (!empty($request->jobType)) {
            $jobTypeArray = explode(',', $request->jobType);
            $jobs = $jobs->whereIn('job_type_id', $jobTypeArray);
        }

        if ($request->sort == '0') {

            $jobs = $jobs->orderBy('created_at');
        } else {

            $jobs = $jobs->orderBy('created_at', 'DESC');
        }

        $jobs = $jobs->paginate(0);
        return view('front.jobs', [
            'categories' => $category,
            'jobTypes' => $jobType,
            'jobs' => $jobs,
            'jobTypeArray' => $jobTypeArray,
        ]);
    }

    public function showJobDetails($id)
    {
        $job = Job::where([
            'id' => $id,
            'status' => 1
        ])->first();

        if ($job == null) {
            abort(404);
        }
        $savedJob = false;

        if (Auth::check()) {

            $savedJob = SavedJob::where(['user_id' => Auth::user()->id, 'job_id' => $id])->exists();
        }

        // Fetch Applicants
         $jobApplicant = JobApplication::where('job_id',$id)->with('user')->get();

        return view('front.jobDetail', ['job' => $job, 'savedJob' => $savedJob, 'jobApplicants' => $jobApplicant]);
    }

    public function aaplyJob(Request $request, Auth $authService)
    {
        $jobID = $request->jobID;
        $job = Job::find($jobID);

        // If record not match or null
        if ($job == null) {
            return response()->json([
                'errorType' => 'dataNotMatch'
            ]);
        }
        
        $employerID = $job->user_id;
        $userLoginID = $authService::user()->id;
        
        // If user try to appy their own job
        if ($employerID == $userLoginID) {
            return response()->json([
                'errorType' => 'ownerMatch'
            ]);
        }
        
        // User apply for more than one time
        if (JobApplication::where([
            'user_id' => $userLoginID,
            'job_id' => $jobID
            ])->exists()){
                return response()->json([
                    'errorType' => 'moreApplies'
                ]);
            }

            // user try to apply when aaplied use is more than vacancy
            $vacancy  = (int) $request->vacancy;
            $totalAppliedUser = JobApplication::where('job_id',$jobID)->count();
            
            if($vacancy <= $totalAppliedUser){
                return response()->json([
                    'errorType' => 'vacancyNotAvailable'
                ]);
            }

        $jobApplication = new JobApplication();
        $jobApplication->job_id = $jobID;
        $jobApplication->user_id = $userLoginID;
        $jobApplication->employer_id = $employerID;
        $jobApplication->applied_date = now();
        $jobApplication->save();


        session()->flash('success', Alert::info('Application Successful!', 'You have successfully applied for the job.'));
        return response()->json([
            'status' => true
        ]);
    }

    public function saveJob(Request $request)
    {
        $id = $request->jobID;

        if (Job::find($id)->exists() == false) {
            return response()->json([
                'errorType' => 'dataNotMatch',
            ]);
        }

        // if user Aleray Saved Job

        if (SavedJob::where(['user_id' => Auth::user()->id, 'job_id' => $id])->exists() == true) {
            return response()->json([
                'errorType' => 'moreSaves',
            ]);
        }

        $savedJob = new SavedJob();
        $savedJob->job_id = $id;
        $savedJob->user_id = Auth::user()->id;
        $savedJob->save();

        session()->flash('success', Alert::info('Application Successful!', 'You have successfully saved for the job.'));
        return response()->json([
            'status' => true,
        ]);
    }
}
