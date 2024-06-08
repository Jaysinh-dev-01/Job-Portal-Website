<div class="card-body p-0">
    <ul class="list-group list-group-flush ">
        <li class="list-group-item d-flex justify-content-between p-3">
            <a href="{{ route('admin.users') }}">Users</a>
        </li>
        <li class="list-group-item d-flex justify-content-between align-items-center p-3">
            <a href="{{ route('admin.jobs') }}">Jobs</a>
        </li>
        <li class="list-group-item d-flex justify-content-between align-items-center p-3">
            <a href="{{ route('admin.application') }}">Job Application</a>
        </li>
    
        <li class="list-group-item d-flex justify-content-between align-items-center p-3">
            <a href="{{ route('account.logout') }}">Logout</a>
        </li>
    </ul>
</div>