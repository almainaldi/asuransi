@extends('dashboard')

@section('content')
<div class="container">
    <form method="POST" action="{{ route('save', ['customer_id' => $customer->id]) }}">
        @method('patch')
        @csrf
        <div class="form-group">
            <label for="exampleInputEmail1">Name</label>
            <input type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter email" name="name" value="{{ $customer->name }}">
        </div>
        <div class="form-group">
            <label for="exampleInputEmail1">Email</label>
            <input type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter email" name="email" value="{{ $customer->email }}">
        </div>
        <div class="form-group">
            <label for="exampleInputEmail1">Address</label>
            <input type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter email" name="address" value="{{ $customer->address }}">
        </div>
        <div class="form-group">
            <label for="exampleInputEmail1">Age</label>
            <input type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter email" name="age" value="{{ $customer->age }}">
        </div>

        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
</div>

@endsection