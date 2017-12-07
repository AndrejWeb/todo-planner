@extends('layouts.app')

@push('head_styles')
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
@endpush

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading"><h4>My Profile</h4></div>
                <div class="panel-body">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif

                        @if (count($errors) > 0)
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form method="post" action="{{ route('update_account', [ 'id' => Auth::user()->id ]) }}">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Name</label>
                                <input type="text" name="name" class="form-control" id="name" placeholder="Name" value="{{ Auth::user()->name }}">
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1">Email Address</label>
                                <input type="email" name="email" class="form-control" id="email" placeholder="Email Address" value="{{ Auth::user()->email }}">
                            </div>
                            <hr/>
                            <div class="alert alert-info">In order to change your password, enter your current password and the new password you want to use.</div>
                            <div class="form-group">
                                <label for="password">Current Password</label>
                                <input type="password" name="current_password" class="form-control" id="current_password"  placeholder="Current Password">
                            </div>

                            <div class="form-group">
                                <label for="exampleInputPassword1">New Password</label>
                                <input type="password" name="password" class="form-control" id="password" placeholder="New Password">
                            </div>

                            <div class="form-group">
                                <label for="password_confirmation">Confirm New Password</label>
                                <input type="password" name="password_confirmation" class="form-control" id="password_confirmation" placeholder="Confirm New Password" />
                            </div>
                            {{ csrf_field() }}
                            {{ method_field('PUT') }}
                            <button type="submit" class="btn btn-primary btn-lg">Update</button>
                        </form>
                        <hr />
                        <form method="post" action="{{ route('delete_account', [ 'id' => Auth::user()->id ]) }}" id="deleteAccountForm">
                            <input type="button" id="delete-account" class="btn btn-danger btn-lg" value="Delete Account" />
                            {{ csrf_field() }}
                            {{ method_field('DELETE') }}
                        </form>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('footer_scripts')
<script>
    $(document).ready(function() {
        $("#delete-account").click(function() {
            if(confirm("Are you sure you want to delete your account? This will also delete all entries you've created. This action is irreversible."))
            {
                $("#deleteAccountForm").submit();
            }
        });
    });
</script>
@endpush
