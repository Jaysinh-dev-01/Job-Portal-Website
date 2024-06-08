<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Job;
use App\Models\JobApplication;
use App\Models\JobType;
use App\Models\SavedJob;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class AccountController extends Controller
{
    // This Show the Ragistraion page
    public function registration()
    {
        return view('front.account.registration');
    }

    // This is for Save User
    public function processRegistration(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required | email | unique:users,email',
            'password' => 'required | min:5 | same:confirm_password',
            'confirm_password' => 'required'
        ]);

        if ($validation->passes()) {
            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = Hash::make($request->password);
            $user->save();

            session()->flash('success', Alert::success('Registered', 'You are Successfully Registered'));

            return response()->json([
                'status' => true,
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validation->errors()
            ]);
        }
    }

    // This Show the Login page
    public function login()
    {
        return view('front.account.login');
    }

    public function authenicate(Request $request)
    {
        $request->validate([
            'email' => 'required | email',
            'password' => 'required | min:5',
        ]);

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            return redirect()->route('account.profile');
        } else {
            session()->flash('error', Alert::error('Unauthorized', 'You are not authenticated. Please Enter Currect information.'));
            return redirect()->route('account.login');
        }
    }

    public function profile()
    {
        $id = Auth::user()->id;
        $user = User::find($id);
        return view('front.account.profile', [
            'user' => $user
        ]);
    }

    public function userProfileUpdate(Request $request)
    {
        $id = Auth::user()->id;
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
            session()->flash('success', Alert::success('Updated', 'Your Profile Successfully Updated'));
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

    public function logout()
    {
        Auth::logout();
        return redirect()->route('account.login');
    }


    public function updateProfile(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'image' => 'required|image'
        ]);

        if ($validation->passes()) {

            $id = Auth::user()->id;
            $image = $request->image;
            $ext = $image->getClientOriginalExtension();
            $imageName = $id . "-" . time() . "." . $ext;
            $image->move(public_path('/profile_pic/'), $imageName);

            $sourcePath = public_path('/profile_pic/' . $imageName);
            $manager = new ImageManager(Driver::class);
            $image = $manager->read($sourcePath);
            $image->cover(150, 150);
            $image->toPng()->save(public_path('/profile_pic/thumb/' . $imageName));

            File::delete(public_path('/profile_pic/thumb/' . Auth::user()->image));
            File::delete(public_path('/profile_pic/' . Auth::user()->image));

            User::where('id', $id)->update(['image' => $imageName]);

            session()->flash('success', Alert::info('Success', 'Your Image Updated Successfully'));
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

    public function createJob()
    {
        $categories = Category::orderBy('name', 'ASC')->where('status', 1)->get();
        $JobTypes = JobType::orderBy('name', 'ASC')->where('status', 1)->get();
        return view('front.account.job.create', [
            'categories' => $categories,
            'jobTypes' => $JobTypes
        ]);
    }

    public function saveJob(Request $request)
    {

        $validation = Validator::make($request->all(), [
            'title' => 'required | min:5 | max:35',
            'category' => 'required',
            'jobType' => 'required',
            'vacancy' => 'required | integer',
            'location' => 'required',
            'experience' => 'required',
            'description' => 'required | max:700',
            'company_name' => 'required | min:3 | max:35',

        ]);

        if ($validation->passes()) {

            $job = new Job();
            $job->title = $request->title;
            $job->category_id = $request->category;
            $job->job_type_id = $request->jobType;
            $job->user_id = Auth::user()->id;
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
            $job->save();

            session()->flash('success', Alert::success('Success', 'Your Job Added Successfully'));

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

    public function myJobs()
    {
        $jobs = Job::where('user_id', Auth::user()->id)->with('jobType')->orderBy('created_at', 'DESC')->paginate(10);
        
       
        
        return view('front.account.job.my-job', [
            'jobs' => $jobs,
           
        ]);
    }

    public function editJob(Request $request, $id)
    {
        $categories = Category::orderBy('name', 'ASC')->where('status', 1)->get();
        $JobTypes = JobType::orderBy('name', 'ASC')->where('status', 1)->get();
        $job = Job::where([
            'user_id' => Auth::user()->id,
            'id' => $id
        ])->first();

        if ($job == null) {
            abort(404);
        }

        return view('front.account.job.edit', [
            'categories' => $categories,
            'jobTypes' => $JobTypes,
            'job' => $job
        ]);
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
            $job->user_id = Auth::user()->id;
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

    public function deleteJob(Request $request)
    {
        $job = Job::where([
            'user_id' => Auth::user()->id,
            'id' => $request->jobID
        ])->first();

        if ($job == null) {
            session()->flash('error', Alert::error('Oops!', "Eighter Job deleted or not found"));
            return response()->json([
                'status' => true
            ]);
        }

        Job::where('id', $request->jobID)->delete();
        session()->flash('success', Alert::success('Success', "Your Record Deleted Successfully"));

        return response()->json([
            'status' => true
        ]);
    }


    public function jobAppliedByUser()
    {
        $jobApplications = JobApplication::where('user_id', Auth::user()->id)->with('job', 'job.totalJobApplications')->orderBy('created_at', 'DESC')->paginate(10);

        return view('front.account.job.jobAppliedByUser', [
            'jobApplications' => $jobApplications
        ]);
    }

    public function removeAppliedJob(Request $request, Auth $authenicateService)
    {

        $applicationExists = JobApplication::where([
            'id' => $request->removeJobID,
            'user_id' => $authenicateService::id()
        ])->exists();


        if ($applicationExists == false) {
            session()->flash('error', Alert::error('Error!', 'Job application not found'));
            return response()->json(['status' => false]);
        }


        $deleted = JobApplication::find($request->removeJobID)->delete();
        if ($deleted) {
            session()->flash('success', Alert::success('Deleted!', 'Job application Deleted Successfully'));
            return response()->json(['status' => true]);
        } else {
            session()->flash('error', Alert::error('Not Deleted!', 'Error Encounter During Deleation'));
            return response()->json(['status' => false]);
        }
    }


    public function savedJob()
    {
        $savedJobs = SavedJob::where([
            'user_id' => Auth::user()->id,
        ])->with(['job', 'job.totalJobApplications'])->orderBy('created_at', 'DESC')->paginate(10);


        return view('front.account.job.savedJobs', [
            'savedJobs' => $savedJobs
        ]);
    }

    public function deleteSavedJob(Request $request,Auth $authenicateService)
    {
       
        $applicationExists = SavedJob::where([
            'id' => $request->removeSavedJobID,
            'user_id' => $authenicateService::id()
        ])->exists();


        if ($applicationExists == false) {
            session()->flash('error', Alert::error('Error!', 'Saved job was not found'));
            return response()->json(['status' => false]);
        }


        $deleted = SavedJob::find($request->removeSavedJobID)->delete();
        if ($deleted) {
            session()->flash('success', Alert::success('Deleted!', 'Saved Job application Deleted Successfully'));
            return response()->json(['status' => true]);
        } else {
            session()->flash('error', Alert::error('Not Deleted!', 'Error Encounter During Deleation'));
            return response()->json(['status' => false]);
        }
    }


    public function updatePassword(Request $request){
       $validation = Validator::make($request->all(),[
        'old_password' => 'required',
        'new_password' => 'required | min:5',
        'confirm_password' => 'required | same:new_password',
       ]);

       if ($validation->fails()){
            return response()->json([
                'status'=>false,
                'errors' => $validation->errors()
            ]);
       }
       


       if (Hash::check($request->old_password,Auth::user()->password) == false){
        session()->flash('error',Alert::error('Opps!',"The Old Password is Wrong Plese try again"));
            return response()->json([
                'status'=> true
            ]);
       }

       $user = User::find(Auth::user()->id);
       $user->password = Hash::make($request->new_password);
       $user->save();

       session()->flash('success',Alert::success("Success","Your Password Successfully Updated"));
       return response()->json([
            'status'=>true
       ]);
    }
}
