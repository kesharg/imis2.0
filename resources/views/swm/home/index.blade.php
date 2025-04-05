{{--Extend the main layout--}}
@extends('layouts.dashboard')
{{--Add sections for the main layout--}}
@section('title', 'SWM Dashboard')
{{--Add sections for the index layout--}}

@section('data-table')
    <table id="data-table" class="table table-bordered table-striped">
        <thead>
        <tr>
            <th>ID</th>
            <th>House Number</th>
            <th>Application Date</th>
            <th>Proposed Emptying Date</th>
            <th>Road</th>
{{--            <th>Assessment Status</th>--}}
            <th>Emptying Status</th>
            <th>Feedback Status</th>
            <th>Owner Name</th>
            <th>Ward</th>
            <th>Contact</th>
            <th>Service Provider</th>
            <th>Actions</th>
            {{--<th>Approved</th>
            <th>Containment</th>
            <th>User</th>
            <th>Applicant Gender</th>
            <th>Customer Gender</th>
            <th>Verified Status</th>
            <th>Applicant's Name</th>--}}
        </tr>
        </thead>
    </table>
@push('scripts')
    
@endpush


