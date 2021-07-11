@extends('layouts.mainAdmin')

@section('content')
<div class="d-flex justify-content-center">
    <div class="card-body login-card-body col-lg-5">
       <h3>Change Password</h3>
        <form action={{route('crew.updatePassword')}} method="post">
            @csrf
            @method('PUT')
            <div class="input-group mb-3">
                <input type="password" name="password" class="form-control" placeholder="Password">
                <div class="input-group-append">
                    <div class="input-group-text">
                        <span class="fas fa-lock"></span>
                    </div>
                </div>
            </div>
            <div class="input-group mb-3">
                <input type="password" name="password_confrim" class="form-control" placeholder="Confirm Password">
                <div class="input-group-append">
                    <div class="input-group-text">
                        <span class="fas fa-lock"></span>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <button type="submit" class="btn bg-navy btn-block">Change password</button>
                </div>
            </div>
        </form>
    </div>
</div> 
@endsection
