@extends('layouts.main')
@section('content')
<main class="container">
    <section>
        <div class="titlebar">
            <h1>Products</h1>
            <a href="{{ route('product.create') }}" class="btn-link">Add Product</a>
        </div>
        @if ($message = Session::get('success'))
            <script type="text/javascript">
                const Toast = Swal.mixin({
                    toast: true,
                    position: "top-end",
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                    didOpen: (toast) => {
                        toast.onmouseenter = Swal.stopTimer;
                        toast.onmouseleave = Swal.resumeTimer;
                    }
                    });
                        Toast.fire({
                        icon: "success",
                        title: "{{ $message }}"
                });
            </script>
        @endif
        <div class="table">
            <div class="table-filter">
                <div>
                    <ul class="table-filter-list">
                        <li>
                            <p class="table-filter-link link-active">All</p>
                        </li>
                    </ul>
                </div>
            </div>
            <form method="GET" action="{{ route('product.index') }}" accept-charset="UTF-8" role="search">
                <div class="table-search">   
                    <div>
                        <button class="search-select">
                        Search Product
                        </button>
                        <span class="search-select-arrow">
                            <i class="fas fa-caret-down"></i>
                        </span>
                    </div>
                    <div class="relative">
                        <input class="search-input" type="text" name="search" placeholder="Search product..." name="search" value="{{ request('search') }}">
                    </div>
                </div>
            </form>
            <div class="table-product-head">
                <p>Image</p>
                <p>Name</p>
                <p>Category</p>
                <p>Inventory</p>
                <p>Size</p>
                <p>Actions</p>
            </div>
            <div class="table-product-body">
                @if (count($listproduct) > 0)
                    @foreach ($listproduct as $product)
                        <img src="{{ asset('images/' . $product->image) }}"/>
                        <p>{{ $product->name }}</p>
                        <p>{{ $product->category }}</p>
                        <p>{{ $product->quantity }}</p>
                        <p>{{ $product->size }}</p>
                        <div>     
                            <a href="{{ route('product.edit', $product->id) }}" class="btn-link btn btn-success">
                                <i class="fas fa-pencil-alt" ></i>
                            </a>
                            <form method="post" action="{{ route('product.destroy', $product->id) }}">
                                @method('delete')
                                @csrf
                                <button class="btn btn-danger" onclick="deleteConfirm(event)" >
                                    <i class="far fa-trash-alt"></i>
                                </button>
                            </form>
                        </div>
                    @endforeach
                @else
                    <p>product not found</p>
                @endif
        
            </div>
            <div class="table-paginate">
                {{ $listproduct->links('layouts.pagination') }}
            </div>
        </div>
    </section>
</main>
<script>
    window.deleteConfirm = function (e) {
        e.preventDefault();
        var form = e.target.form;
        const swalWithBootstrapButtons = Swal.mixin({
            customClass: {
                confirmButton: "btn btn-success",
                cancelButton: "btn btn-danger"
            },
            buttonsStyling: false
        });
        swalWithBootstrapButtons.fire({
            title: "Apakah Anda yakin?",
            text: "Anda tidak akan bisa mengembalikannya!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Ya, hapus!",
            cancelButtonText: "Tidak, batalkan!",
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                // Jika pengguna mengonfirmasi, kirimkan formulir
                form.submit();
            } else if (
                /* Baca lebih lanjut tentang penanganan penolakan di bawah ini */
                result.dismiss === Swal.DismissReason.cancel
            ) {
                swalWithBootstrapButtons.fire({
                    title: "Dibatalkan",
                    text: "Produk Anda aman :)",
                    icon: "error"
                });
            }
        });
    }
</script>
@endsection