@extends('layouts.admin')

@section('content')

    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <form action="{{ route('offers.store') }}" method="POST" enctype="multipart/form-data">
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
                    {{-- short desc --}}
                    <div class="form-group col-sm-7">
                        <label class="required" for="short_desc">Short desc</label>
                        <input class="en_short_desc form-control {{ $errors->has('short_desc') ? 'is-invalid' : '' }}" type="text"
                            name="short_desc" id="short_desc" value="{{ old('short_desc', '') }}">
                        @if($errors->has('short_desc'))
                            <div class="invalid-feedback">
                                {{ $errors->first('short_desc') }}
                            </div>
                        @endif
                    </div>
                    {{-- long desc --}}
                    <div class="form-group col-sm-7">
                        <label class="required" for="long_desc">Long desc</label>
                        <input class="en_long_desc form-control {{ $errors->has('long_desc') ? 'is-invalid' : '' }}" type="text"
                            name="long_desc" id="long_desc" value="{{ old('long_desc', '') }}">
                        @if($errors->has('long_desc'))
                            <div class="invalid-feedback">
                                {{ $errors->first('long_desc') }}
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
                    {{-- short desc --}}
                    <div class="form-group col-sm-7">
                        <label class="required" for="short_desc">Short desc</label>
                        <input class="ar_short_desc form-control {{ $errors->has('short_desc') ? 'is-invalid' : '' }}" type="text"
                            name="short_desc" id="short_desc" value="{{ old('short_desc', '') }}">
                        @if($errors->has('short_desc'))
                            <div class="invalid-feedback">
                                {{ $errors->first('short_desc') }}
                            </div>
                        @endif
                    </div>
                    {{-- long desc --}}
                    <div class="form-group col-sm-7">
                        <label class="required" for="long_desc">Long desc</label>
                        <input class="ar_long_desc form-control {{ $errors->has('long_desc') ? 'is-invalid' : '' }}" type="text"
                            name="long_desc" id="long_desc" value="{{ old('long_desc', '') }}">
                        @if($errors->has('long_desc'))
                            <div class="invalid-feedback">
                                {{ $errors->first('long_desc') }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            {{-- email --}}
            <div class="form-group ml-4">
                <label for="user_email" class="col-sm-2 col-form-label">email</label>
                <div class="col-sm-7">
                    <input type="user_email" name='user_email' class="user_email form-control {{$errors->first('user_email') ? "is-invalid" : "" }} " value="{{old('user_email')}}" id="user_email">
                    <div class="invalid-feedback">
                        {{ $errors->first('user_email') }}
                    </div>
                </div>
            </div>
            {{-- offer price --}}
            <div class="form-group ml-4">
                <label for="offer_price" class="col-sm-2 col-form-label">Offer price</label>
                <div class="col-sm-7">
                    <input type="number" name='offer_price' class="offer_price form-control {{$errors->first('offer_price') ? "is-invalid" : "" }} " value="{{old('offer_price')}}" id="offer_price">
                    <div class="invalid-feedback">
                        {{ $errors->first('offer_price') }}
                    </div>
                </div>
            </div>
            {{-- selling quantity --}}
            <div class="form-group ml-4">
                <label for="selling_quantity" class="col-sm-2 col-form-label">Selling quantity</label>
                <div class="col-sm-7">
                    <input type="number" name='selling_quantity' class="selling_quantity form-control {{$errors->first('selling_quantity') ? "is-invalid" : "" }} " value="{{old('selling_quantity')}}" id="selling_quantity">
                    <div class="invalid-feedback">
                        {{ $errors->first('selling_quantity') }}
                    </div>
                </div>
            </div>
            {{-- start date --}}
            <div class="form-group ml-4">
                <label for="started_at" class="col-sm-2 col-form-label">started date</label>
                <div class="col-sm-7">
                    <input type="date" name='started_at' class="started_at form-control {{$errors->first('started_at') ? "is-invalid" : "" }} " value="{{old('started_at')}}" id="started_at" >
                    <div class="invalid-feedback">
                        {{ $errors->first('started_at') }}
                    </div>
                </div>              
            </div>
            {{-- end date --}}
            <div class="form-group ml-4">
                <label for="ended_at" class="col-sm-2 col-form-label">End date</label>
                <div class="col-sm-7">
                    <input type="date" name='ended_at' class="ended_at form-control {{$errors->first('ended_at') ? "is-invalid" : "" }} " value="{{old('ended_at')}}" id="ended_at" >
                    <div class="invalid-feedback">
                        {{ $errors->first('ended_at') }}
                    </div>
                </div>              
            </div>
            {{-- products --}}
            <div class="form-group ml-4">
                <label for="storeProduct" class="col-sm-2 col-form-label">Products</label>
                <div class="col-sm-7">
                    <select name='storeProduct[]' class="form-control {{$errors->first('storeProduct') ? "is-invalid" : "" }} select2" id="storeProduct">
                        <option disabled selected>Choose one or more</option>
                        @foreach ($storeProducts as $storeProduct)
                            <option class="storeProduct" value="{{ $storeProduct->id }}">{{ $storeProduct->product->name }}</option>
                        @endforeach
                    </select>
                    <div class="invalid-feedback">
                        {{ $errors->first('storeProduct') }}
                    </div>
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
                        "short_desc":$('.en_short_desc').val(),
                        "long_desc":$('.en_long_desc').val(),
                        "locale":"en"
                    },
                    {
                        "name": $('.ar_name').val(),
                        "short_desc":$('.ar_short_desc').val(),
                        "long_desc":$('.ar_long_desc').val(),
                        "locale":"ar"
                    }
                ],
                "storeProduct":[
                    {
                        "store_product_id" : $('.storeProduct').val()
                    }
                ],
                "user_email":$('.user_email').val(),
                "offer_price":$('.offer_price').val(),
                "selling_quantity":$('.selling_quantity').val(),
                "started_at":$('.started_at').val(),
                "ended_at":$('.ended_at').val(),
                "is_active":"1",
                "is_offer":"1"
            }
            console.log(data);
            // $.ajaxSetup({
            //     headers:{
            //         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            //     }
            // });
            $.ajax({
                type:"GET",
                url:"/store",
                data:data,
                dataType:"json",
                success: function(res){
                    console.log(res);
                }
            });
        });
    });
 
</script>
@endsection