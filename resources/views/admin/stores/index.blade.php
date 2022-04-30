@extends('layouts.admin')

@section('content')


        <h1 class="h3 mb-2 text-gray-800">Stores</h1>
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <div>
        <a href="{{ route('stores.create') }}" class="btn btn-success mb-1">Create Store</a>
        <a href="{{ route('stores.edit') }}" class="btn btn-success mb-1">Update Store</a>
        </div>
        <div class="search">
            <label for=""> <i class="fa fa-search"></i></label>
            <input type="text" placeholder="Search">
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Section</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>
<style>
    .card-header{
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    @media (max-width:568px) {
    .card-header{
        flex-direction: column;
    }
    }
    .card-header .search{
        position: relative;
    }
    .card-header .search label i{
        position: absolute;
        right: 10px;
        top: 50%;
        transform: translateY(-50%);

    }
    .card-header .search input{
        outline:none;
        border: 2px solid #c1c1c1;
        padding: 4px;
        border-radius: 3px;
    }

</style>
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
                                <td>'+'1'+'</td>\
                                <td>'+'store-name'+'</td>\
                                <td>'+'store-section'+'</td>\
                                <td>'+'store-status'+'</td>\
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
