@extends('front.layouts.app')
@section('main')
    <section class="section-5 bg-2">
        <div class="container py-5">
            <div class="row">
                <div class="col">
                    <nav aria-label="breadcrumb" class=" rounded-3 p-3 mb-4">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                            <li class="breadcrumb-item active">Jobs</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-3">
                    @include('admin.sidebar')
                </div>
                <div class="col-lg-9">
                    <div class="card border-0 shadow mb-4">
                        <div class="card-body card-form">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h3 class="fs-4 mb-1">Applications Details</h3>
                                </div>

                            </div>
                            <div class="table-responsive">
                                <table class="table ">
                                    <thead class="bg-light">
                                        <tr>
                                            <th scope="col">Id</th>
                                            <th scope="col">Title</th>
                                            <th scope="col">Status</th>
                                           
                                            <th scope="col">Created By</th>
                                            <th scope="col">Applied By</th>
                                            <th scope="col">Applied Date</th>
                                            <th scope="col">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody class="border-0">
                                        @if ($applicants->isNotEmpty())
                                            @foreach ($applicants as $applicant)
                                                <tr class="active">
                                                    <td>
                                                        <div class="job-name fw-500">{{ $loop->index + 1 }}</div>

                                                    </td>
                                                    <td>
                                                        <p>{{ $applicant->job->title }}</p>
                                                        <p>Applications:{{ $applicant->job->totalJobApplications->count() }}</p>

                                                    </td>
                                                    
                                                    <td class="{{  $applicant->job->status == '0' ? 'text-danger' : 'text-success' }}">
                                                        {{ $applicant->job->status == '1' ? 'Active' : 'Block' }} </td>
                                                    <td>{{ $applicant->job->user->name }}</td>
                                                    <td>{{ $applicant->user->name }}</td>
                                                    <td>{{ \Carbon\Carbon::parse($applicant->applied_date)->format('d M, Y') }}</td>
                                                    <td>
                                                        <div class="action-dots ">
                                                            <ul class="flex">
                                                                <i style="cursor: pointer; text-align: center; font-size: 20px" class="fas fa-trash-alt" onclick="confirmDeleteJob({{ $applicant->id }})"></i>
                                                            </ul>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @endif

                                    </tbody>

                                </table>
                                <div>
                                    {{ $applicants->links() }}
                                </div>
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
        function confirmDeleteJob(id) {
            Swal.fire({
                title: "Are you sure?",
                text: "You won't be able to revert this!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, delete it!"
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: "POST",
                        url: "{{ route('admin.deleteApplication') }}",
                        data: {
                            jobAppId: id
                        },
                        dataType: "json",
                        success: function(response) {
                            if (response.status == true) {

                                window.location.reload();
                            }
                        }
                    });
                }
            });
        }
    </script>
@endsection
