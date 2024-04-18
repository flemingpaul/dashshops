<div class="table-responsive">
    <table class="table table-flush" id="products-list">
        <thead class="thead-light">
            <tr>
                <th>Product</th>
                <th>Variation</th>
                <th>Store - Category</th>
                <th>Price</th>
                <th>SKU</th>
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
                        
                        <img class="w-10 ms-3" style="min-width:60px" src="{{\Config::get('app.api_url').$p['image']}}" alt="hoodie">
                        <h6 class="ms-3 my-auto" style="font-size: 10pt;">{{$p["product_name"]}}</h6>
                    </div>
                </td>
                <td class="text-sm">
                    <?php
                    $e = explode(';',$p['variant_types']);
                    $ev = explode(';',$p['variant_type_values']);
                    for($i=0;$i<count($e);$i++){
                        if ($i == 0) {
                            if ($e[$i] != "-") {
                                echo '<span class="badge bg-gradient-primary badge-sm m-1">' . $e[$i] . ' | '.$ev[$i].'</span>';
                            }
                        } else if ($i == 1) {
                            if ($e[$i] != "-") {
                                echo '<span class="badge bg-gradient-info badge-sm m-1">' . $e[$i] . ' | '.$ev[$i] . '</span>';
                            }
                        } else if ($i == 2) {
                            if ($e[$i] != "-") {
                                echo '<span class="badge bg-gradient-dark badge-sm m-1">' .$e[$i] . ' | '.$ev[$i]. '</span>';
                            }
                        }
                    }
                    ?>
                    
                </td>
                <td class="text-sm">{{$p['store_name']}} - {{$p['category_name']}}</td>
                <td class="text-sm">
                @if(filter_var($p['on_sale'],FILTER_VALIDATE_BOOLEAN))
                    &#8358;{{number_format((float)$p['sale_price'],2)}}<br>
                    <del>&#8358;{{number_format((float)$p['price'],2)}}</del>
                    @else
                    &#8358;{{number_format((float)$p['price'],2)}}
                    @endif
                </td>
                <td class="text-sm">{{$p['sku']}}</td>
                <td class="text-sm">{{$p['quantity']}}</td>
                <td>
                    @if(filter_var($p['status'],FILTER_VALIDATE_BOOLEAN))
                    <span class="badge badge-success badge-sm m-1">Active</span>
                    @else
                    <span class="badge badge-dark badge-sm m-1">Disabled</span>
                    @endif

                    @if(filter_var($p['on_sale'],FILTER_VALIDATE_BOOLEAN))
                    <span class="badge badge-info badge-sm m-1">On Sale</span>
                    @endif

                    @if((int)$p["quantity"]==0)
                    <span class="badge badge-danger badge-sm m-1">Out of Stock</span>
                    @elseif((int)$p["quantity"] <= (int)$p['low_stock_value'])
                    <span class="badge badge-warning badge-sm m-1">Low stock</span>
                    @endif
                    
                </td>
                <td class="text-sm">
                    <a href="{{\App::make('url')->to('product/'.$p['alias'])}}" target = "_blank" data-bs-toggle="tooltip" data-bs-original-title="Preview product">
                        <i class="fas fa-eye text-secondary"></i>
                    </a>
                    <a href="{{\App::make('url')->to('sellers/products/add?id='.$p['product_id'])}}"  target = "_blank" class="mx-3" data-bs-toggle="tooltip" data-bs-original-title="Edit product">
                        <i class="fas fa-user-edit text-secondary"></i>
                    </a>
                    <a href="javascript:;" onclick="deleteProduct('{{$p['product_id']}}')" data-bs-toggle="tooltip" data-bs-original-title="Delete product">
                        <i class="fas fa-trash text-secondary"></i>
                    </a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>