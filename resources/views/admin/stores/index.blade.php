@extends('layouts.admin')

@section('content')
    <div>Stores</div>
            <a href="{{ route('stores.create') }}" class="btn btn-success">Create Store</a>
        <a href="{{ route('stores.edit') }}" class="btn btn-success">Update Store</a>
@endsection
