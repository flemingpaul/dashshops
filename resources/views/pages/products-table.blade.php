<div class="table-responsive">
    <table class="table table-flush" id="products-list">
        <thead class="thead-light">
            <tr>
                <th>Product</th>
                <th>Category</th>
                <th>Variation Count</th>
                <th>Retailer</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($products ?? [] as $p)
            <tr>
                <td class="text-sm" style="word-wrap: break-word;">
                    <div class="d-flex">

                        <img class="w-10 ms-3" style="max-width:40px; height: 40px; object-fit: cover;" src="{{\App::make('url')->to('images/'.$p->image)}}" alt="">
                        <h6 class="ms-3 my-auto" style="font-size: 10pt;">{{$p->product_name}}</h6>
                    </div>
                </td>
                <td class="text-sm">
                    {{$p->category_name}}

                </td>
                <td class="text-sm">
                    {{$p->total_variants}}

                </td>

                <td class="text-sm">{{$p->business_name}}</td>
                <td class="text-sm">
                    @if($p->min_price != $p->max_price)
                    ${{number_format((float)$p->min_price,2)}} - ${{number_format((float)$p->max_price,2)}}<br>

                    @else
                    ${{number_format((float)$p->max_price,2)}}
                    @endif
                </td>
                <td class="text-sm">{{$p->total_quantity}}</td>
                <td>
                    @if(filter_var($p->status,FILTER_VALIDATE_BOOLEAN))
                    <span class="badge badge-success badge-sm m-1">Approved</span>
                    @else
                    <span class="badge badge-dark badge-sm m-1">Denied</span>
                    @endif




                </td>
                <td class="text-sm">
                    <a href="{{route('view-product',['id'=>$p->id])}}" target="_blank" data-bs-toggle="tooltip" data-bs-original-title="Preview product">
                        <i class="fas fa-eye text-secondary"></i>
                    </a>
                    <a href="{{\App::make('url')->to('sellers/products/add?id='.$p->id)}}" target="_blank" class="mx-3" data-bs-toggle="tooltip" data-bs-original-title="Edit product">
                        <i class="fas fa-user-edit text-secondary"></i>
                    </a>
                    <a href="javascript:;" onclick="deleteProduct('{{$p->id}}')" data-bs-toggle="tooltip" data-bs-original-title="Delete product">
                        <i class="fas fa-trash text-secondary"></i>
                    </a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>