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
            <h2>Create Offer</h2>
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
            </div>
            {{--  --}}
            
            <div class="form-group ml-4">
                <label for="product" class="col-sm-2 col-form-label">Products</label>
                <div class="col-sm-7">
                    <select name='product' class="form-control {{$errors->first('product') ? "is-invalid" : "" }} "
                            id="product">
                        <option disabled selected>Choose one or more</option>
                        {{-- @foreach ($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach --}}
                    </select>
                    @error('product')
                    <small class="form-text text-danger"> {{ $message }}</small>
                    @enderror
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

@section('scripts')

<script>
    $(document).ready(function(){
        $(document).on('click','.add_offer',function(e){
            e.preventDefault();
            // console.log("hello");
            var data = {
                "Offer":[
                    {
                        "name": $('.en_name').val(),
                        "short_desc":"It is a German cream that differs from moisturizing skin creams",
                        "long_desc":"It is a German cream that differs from skin moisturizing creams, which are always light and must be applied more than once a day to obtain an appropriate result",
                        "locale":"en"
                    },
                    {
                        "name": $('.ar_name').val(),
                        "short_desc":"هو كريم ألماني يختلف عن كريمات ترطيب البشرة",
                        "long_desc":"هو كريم ألماني يختلف عن كريمات ترطيب البشرة التي دائما ما تكون خفيفة ويجب تطبيقها أكثر من مرة يوميا للحصول على نتيجة مناسبة",
                        "locale":"ar"
                    }
                ],
                "storeProduct":[
                    {
                        "store_product_id" : 3
                    }
                ],
                "user_email":"superadministrator@app.com",
                "offer_price":"5000",
                "selling_quantity":"2",
                "started_at":"2022-04-8 05:10:25",
                "ended_at":"2022-04-12 05:10:28",
                "is_active":"1",
                "is_offer":"1"
            }
        });
    });
 
</script>
@endsection