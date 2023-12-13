@extends('layouts.app')

@section('content')
    @if (session()->has('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    @if (session()->has('warning'))
        <div class="alert alert-warning">
            {{ session('warning') }}
        </div>
    @endif
    @if (session()->has('message'))
        <div class="alert alert-primary">
            {{ session('message') }}
        </div>
    @endif
    {{-- <Product-View></Product-View> --}}
    <div class="row top-space ">
        @foreach (array_chunk($products->all(), 3) as $chunk)
            @foreach ($chunk as $product)
                <div class="col-md-4 p-3">
                    <div class="card text-center" style="">
                        <img class="card-img-top" src="{{ asset($product->getImageURL()) }}" alt="Card image cap">
                        <div class="card-body">
                            <h5 class="card-title">{{ $product->pdct_name }}</h5>
                            <p class="card-text">{{ $product->pdct_description }}</p>
                            <p class="card-text">Price: {{ $product->pdct_price }}</p>
                            <p class="card-text">Available Quantity: {{ $product->pdct_qty }}</p>                    
                            <form action="{{ route('cart.addToCart', ['product_id' => $product->id, 'quantity' => 1]) }}"
                                method="post">
                                @csrf
                                @method('post')
                                <button type="submit" class="btn btn-primary">Add to Cart</button>
                            </form>
                        </div>
                    </div>

                </div>
            @endforeach
        @endforeach
    </div>
@endsection
