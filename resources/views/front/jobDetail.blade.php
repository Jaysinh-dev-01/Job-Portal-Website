@extends('front.layouts.app')
@section('main')
    <section class="section-4 bg-2">
        <div class="container pt-5">
            <div class="row">
                <div class="col">
                    <nav aria-label="breadcrumb" class=" rounded-3 p-3">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="{{ route('jobs') }}"><i class="fa fa-arrow-left"
                                        aria-hidden="true"></i> &nbsp;Back to Jobs</a></li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
        <div class="container job_details_area">
            <div class="row pb-5">
                <div class="col-md-8">
                    <div class="card shadow border-0">
                        <div class="job_details_header">
                            <div class="single_jobs white-bg d-flex justify-content-between">
                                <div class="jobs_left d-flex align-items-center">

                                    <div class="jobs_conetent">
                                        <a href="#">
                                            <h4>{{ $job->title }}</h4>
                                        </a>
                                        <div class="links_locat d-flex align-items-center">
                                            <div class="location">
                                                <p> <i class="fa fa-map-marker"></i> {{ $job->location }}</p>
                                            </div>
                                            <div class="location">
                                                <p> <i class="fa fa-clock-o"></i> {{ $job->jobType->name }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="jobs_right">
                                    <div class="apply_now">
                                        <button
                                            class="heart_mark btn btn-success {{ $savedJob == true ? 'saved-Job' : '' }}"
                                            onclick="confirmSaveJob({{ $job->id }})"> <i class="fas fa-heart"
                                                style="display: flex;
                                            align-items: center;
                                            justify-content: center; font-size: 20px"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="descript_wrap white-bg">
                            <div class="single_wrap">
                                <h4>Job description</h4>
                                <p>{!! nl2br($job->description) !!}</p>
                            </div>
                            @if ($job->responsibility != null)
                                <div class="single_wrap">
                                    <h4>Responsibility</h4>
                                    <ul>
                                        <li>{!! nl2br($job->responsibility) !!}</li>
                                    </ul>
                                </div>
                            @endif
                            @if ($job->qualifications != null)
                                <div class="single_wrap">
                                    <h4>Qualifications</h4>
                                    <ul>
                                        <li>{!! nl2br($job->qualifications) !!}</li>
                                    </ul>
                                </div>
                            @endif
                            @if ($job->benefits != null)
                                <div class="single_wrap">
                                    <h4>Benefits</h4>
                                    <p>{!! nl2br($job->benefits) !!}</p>
                                </div>
                            @endif
                            <div class="border-bottom"></div>
                            <div class="pt-3 text-end">
                                @if (Auth::check())
                                    <button onclick="confirmSaveJob({{ $job->id }})"
                                        class="btn btn-secondary">Save</button>
                                @else
                                    <a href="javascript:void(0)" class="btn btn-secondary" disabled>Login to Save</a>
                                @endif

                                @if (Auth::check())
                                    <button onclick="confirmApplyJob({{ $job->id }},{{ $job->vacancy }})"
                                        class="btn btn-primary">Apply</button>
                                @else
                                    <a href="javascript:void(0)" class="btn btn-primary" disabled>Login to Apply</a>
                                @endif
                            </div>
                        </div>
                    </div>
                    @if (Auth::check() && Auth::user()->id == $job->user_id)
                        <div class="card shadow border-0 mt-4">
                            <div class="job_details_header">
                                <div class="single_jobs white-bg d-flex justify-content-between">
                                    <div class="jobs_left d-flex align-items-center">

                                        <div class="jobs_conetent">
                                            <h4>Job Applicants</h4>
                                        </div>
                                    </div>
                                    <div class="jobs_right">
                                    </div>
                                </div>
                            </div>
                            <div class="descript_wrap white-bg">
                                <table class="table table-stripped">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Applied Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if ($jobApplicants->isNotEmpty())
                                            @foreach ($jobApplicants as $jobApplicant)
                                                <tr>
                                                    <td>{{ $jobApplicant->user->name }}</td>
                                                    <td>{{ $jobApplicant->user->email }}</td>
                                                    <td>{{ \Carbon\Carbon::parse($jobApplicant->applied_date)->format('d M, Y') }}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @else
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endif
                </div>
                <div class="col-md-4">
                    <div class="card shadow border-0">
                        <div class="job_sumary">
                            <div class="summery_header pb-1 pt-4">
                                <h3>Job Summery</h3>
                            </div>
                            <div class="job_content pt-3">
                                <ul>
                                    <li>Published on: <span>{{ \Carbon\Carbon::parse($job->created_at)->format('d M, Y') }}
                                        </span></li>

                                    <li>Vacancy:
                                        <span class="{{ $job->vacancy <= $jobApplicants->count() ? 'text-danger' : '' }}">{{ $job->vacancy <= $jobApplicants->count() ? 'Not Available' : (int) $job->vacancy - (int) $jobApplicants->count() }}</span>
                                    </li>
                                    @if ($job->salary != null)
                                        <li>Salary: <span>{{ $job->salary }}</span></li>
                                    @endif
                                    <li>Location: <span>{{ $job->company_location }}</span></li>
                                    <li>Job Nature: <span>{{ $job->category->name }}</span></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="card shadow border-0 my-4">
                        <div class="job_sumary">
                            <div class="summery_header pb-1 pt-4">
                                <h3>Company Details</h3>
                            </div>
                            <div class="job_content pt-3">
                                <ul>
                                    <li>Name:
                                        <span>{{ $job->company_name != null ? $job->company_name : 'No Info' }}</span>
                                    </li>
                                    @if ($job->company_location != null)
                                        <li>Locaion: <span>{{ $job->company_location }}</span></li>
                                    @endif

                                    @if ($job->company_website != null)
                                        <li>Webite: <span><a
                                                    href="http://{{ urlencode($job->company_website) }}">{{ $job->company_website }}</a></span>
                                        </li>
                                    @endif

                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection


@section('customJs')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

    <script>
        function confirmApplyJob(id, vacancy) {
            Swal.fire({
                title: "Confirm Job Application",
                text: "Are you sure you want to apply for this job?",
                icon: "question",
                showCancelButton: true,
                confirmButtonColor: "#007BFF", // Adjust the color to your preference
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, apply now!"
            }).then((result) => {
                if (result.isConfirmed) {
                    console.log("Starting");
                    $.ajax({
                        type: "POST",
                        url: "{{ route('job.apply') }}",
                        data: {
                            jobID: id,
                            vacancy: vacancy
                        },
                        dataType: "json",
                        success: function(response) {
                            if (response.status == true) {
                                window.location.reload();
                            } else {
                                let errorMessage =
                                    "An error occurred while applying for the job. Please try again later.";

                                if (response.errorType == 'dataNotMatch') {
                                    errorMessage = "Job Doesn't Exist This Time.";
                                } else if (response.errorType == 'ownerMatch') {
                                    errorMessage = "Job's Owner cannot apply thier own company.";
                                } else if (response.errorType == 'moreApplies') {
                                    errorMessage = "You cannot apply this job for more then one time";
                                } else if (response.errorType == 'vacancyNotAvailable') {
                                    errorMessage = "Vacancy Not Available";
                                }

                                Swal.fire({
                                    title: "Application Failed",
                                    text: errorMessage,
                                    icon: "error"
                                });
                            }
                        }
                    });
                }
            });
        }


        function confirmSaveJob(id) {

            $.ajax({
                type: "POST",
                url: "{{ route('job.save') }}",
                data: {
                    jobID: id,

                },
                dataType: "json",
                success: function(response) {
                    if (response.status == true) {
                        window.location.reload();
                    } else {
                        let errorMessage =
                            "An error occurred while applying for the job. Please try again later.";

                        if (response.errorType == 'dataNotMatch') {
                            errorMessage = "Job Doesn't Exist This Time.";
                        } else if (response.errorType == 'ownerMatch') {
                            errorMessage = "Job's Owner cannot apply thier own company.";
                        } else if (response.errorType == 'moreSaves') {
                            errorMessage = "You cannot save this job for more then one time";
                        }
                        Swal.fire({
                            title: "Application Failed",
                            text: errorMessage,
                            icon: "error"
                        });
                    }
                }
            });
        }
    </script>
@endsection
