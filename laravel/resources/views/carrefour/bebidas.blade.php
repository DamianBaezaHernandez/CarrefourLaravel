@extends('layouts.main')
@section('contenido')
    <div class="container">
        <div class="row mt-5">
            @foreach($products as $product)
            <div class="col-md-6 col-lg-3 g-2">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="col-6">
                            <img src="<?php echo "$product->image"?>" class="img-fluid">
                        </div>
                        <div class="col-12">
                            <span>{{$product->price}} €</span>
                        </div>
                        <div class="col-12">
                            <span>{{$product->name}}</span>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="col-12">
                            <a href="" class="btn btn-primary">Añadir</a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        <div class="row">
            <div class="col">
                <span>{{$products->links()}}</span>
            </div>
        </div>
    </div>
@endsection
