@extends('layouts.admin')

@section('content')

    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <form action="" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="form-group m-4">
            <h2>Create Stores</h2>
        </div>
        <div class="container">
            <!-- Nav tabs -->
            <ul class="nav nav-tabs">
                <li class="nav-item">
                    <a class="nav-link active" data-toggle="tab" href="#english">English</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#arabic">Arabic</a>
                </li>
            </ul>

            <!-- Tab panes -->
            <div class="tab-content">
                <div id="english" class="container tab-pane active"><br>
                    {{-- name --}}
                    <div class="form-group col-sm-7">
                        <label class="required" for="name">Name</label>
                        <input class="en_name form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text"
                            name="name" id="name" value="{{ old('name', '') }}">
                        @if($errors->has('name'))
                            <div class="invalid-feedback">
                                {{ $errors->first('name') }}
                            </div>
                        @endif
                    </div>
                    
                </div>
                <div id="arabic" class="container tab-pane fade"><br>
                    {{-- name --}}
                    <div class="form-group col-sm-7">
                        <label class="required" for="name">Name</label>
                        <input class="ar_name form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text"
                            name="name" id="name" value="{{ old('name', '') }}">
                        @if($errors->has('name'))
                            <div class="invalid-feedback">
                                {{ $errors->first('name') }}
                            </div>
                        @endif
                    </div>
                </div>
                <div class="form-group ml-4">
                <label for="storeProduct" class="col-sm-2 col-form-label">Categories</label>
                <div class="col-sm-7">
                    <select name='storeProduct[]' class="form-control {{$errors->first('storeProduct') ? "is-invalid" : "" }} select2" id="storeProduct" multiple>
                        <option disabled selected>Choose one or more</option>
                        {{-- @foreach ($permissions as $permission)
                            <option value="{{ $permission->id }}">{{ $permission->name }}</option>
                        @endforeach --}}
                    </select>
                    <div class="invalid-feedback">
                        {{ $errors->first('storeProduct') }}
                    </div>
                </div>
            </div>
            <div class="form-group ml-4">
                <label for="storeProduct" class="col-sm-2 col-form-label">Sections</label>
                <div class="col-sm-7">
                    <select name='storeProduct[]' class="form-control {{$errors->first('storeProduct') ? "is-invalid" : "" }} select2" id="storeProduct" multiple>
                        <option disabled selected>Choose one or more</option>
                        {{-- @foreach ($permissions as $permission)
                            <option value="{{ $permission->id }}">{{ $permission->name }}</option>
                        @endforeach --}}
                    </select>
                    <div class="invalid-feedback">
                        {{ $errors->first('storeProduct') }}
                    </div>
                </div>
            </div>

            <div class="form-group ml-4">
            <label for="storeProduct" class="col-sm-2 col-form-label">Logo</label>
                <input type="file" class="form-control ml-2 col-sm-7" id="inputGroupFile02">
              
                </div>
            </div>
            
            <div class="form-group ml-4">
                <div class="col-sm-3">
                    <button type="submit" class="btn btn-primary add_offer">Create</button>
                </div>
            </div>
        </div>
    </form>
@endsection