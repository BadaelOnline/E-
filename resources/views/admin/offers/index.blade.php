@extends('layouts.admin')

@section('content')

<h1 class="h3 mb-2 text-gray-800">Offers</h1>

@if (session('success'))
<div class="alert alert-success">
    {{ session('success') }}
</div>
@endif

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <a href="{{ route('offers.create') }}" class="btn btn-success">Create Offer</a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Desc</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@section('scripts')

<script>
    $(document).ready(function(){
        fetchOffer();
        function fetchOffer(){
            $.ajax({
                type:"GET",
                url:"offers/getAll",
                dataType:"json",
                success: function(res){
                    console.log(res.Offer.data);
                    $('tbody').html("");
                    $.each(res.Offer.data , function(key,item){
                        $('tbody').append(
                            '<tr>\
                                <td>'+item.id+'</td>\
                                <td>'+item.name+'</td>\
                                <td>'+item.short_desc+'</td>\
                                <td>\
                                    <button type="button" value="'+item.id+'" class="edit btn btn-info btn-sm">Edit</button>\
                                    <button type="button" value="'+item.id+'" class="delete btn btn-danger btn-sm">Delete</button>\
                                </td>\
                            </tr>');
                    });
                }
            });
        }
    });
 
</script>
@endsection