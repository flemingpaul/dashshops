<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use App\Models\CouponClicks;
use App\Models\CouponDownloads;
use App\Models\CouponRedeemed;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\DB;


class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    function getTotalDownloads()
    {
        if (\Auth::user()->admin == 1) {
            return CouponDownloads::sum('Downloads');
        } else {
            return CouponDownloads::leftJoin('coupons', 'coupons_download.coupon_id', '=', 'coupons.id')->leftJoin('retailers', 'coupons.retailer_id', '=', 'retailers.id')->where('retailers.created_by', \Auth::user()->id)->sum('Downloads');
        }
    }
    function getTotalClicks()
    {
        if (\Auth::user()->admin == 1) {
            return CouponClicks::sum('clicks');
        } else {
            return CouponClicks::leftJoin('coupons', 'coupons_clicks.coupon_id', '=', 'coupons.id')->leftJoin('retailers', 'coupons.retailer_id', '=', 'retailers.id')->where('retailers.created_by', \Auth::user()->id)->sum('clicks');
        }
    }
    function getTotalRedemptions()
    {
        if (\Auth::user()->admin == 1) {
            return CouponRedeemed::count();
        } else {
            return CouponRedeemed::leftJoin('coupons', 'coupon_redemption.coupon_id', '=', 'coupons.id')->leftJoin('retailers', 'coupons.retailer_id', '=', 'retailers.id')->where('retailers.created_by', \Auth::user()->id)->count();
        }
    }
    function getSelectDBRawProducts(){
        $str = 'products.*, 
        (select min(product_variation.price) where product_variation.product_id = products.id) as min_price,
        (select max(product_variation.price) where product_variation.product_id = products.id) as max_price, 
        (select count(id) from product_variation where product_id=products.id) as total_variants,
        (select sum(quantity) from product_variation where product_id=products.id) as total_quantity,
        (select min(product_variation.sale_price) where product_variation.product_id = products.id) as min_sale_price,
        (select max(product_variation.sale_price) where product_variation.product_id = products.id) as max_sale_price, 
        (select max(product_variation.on_sale) where product_variation.product_id = products.id and product_variation.status = 1) as product_is_on_sale, 
        (select id from product_favorites where product_favorites.product_variation_id=product_variation.id and product_favorites.user_id=" . $userid . " limit 0,1) as favorite, 
        retailers.business_name,retailers.banner_image,retailers.business_address, retailers.city, retailers.state, retailers.phone_number, retailers.email,retailers.business_description,
        retailers.longitude,retailers.latitude, retailers.from_mobile, retailers.from_mobile, categories.name as category_name';
        return DB::raw($str);
    }

    function getSelectDBRawCartDisplay()
    {
        $str = 'products.*,product_variation.id as product_variation_id, product_variation.price,product_variation.sale_price,product_variation.on_sale,product_variation.quantity as product_quantity,product_variation.low_stock_value, product_variation.status,
        (select min(product_variation.price) where product_variation.product_id = products.id) as min_price,
        (select max(product_variation.price) where product_variation.product_id = products.id) as max_price, 
        (select min(product_variation.sale_price) where product_variation.product_id = products.id) as min_sale_price,
        (select max(product_variation.sale_price) where product_variation.product_id = products.id) as max_sale_price, 
        (select max(product_variation.on_sale) where product_variation.product_id = products.id and product_variation.status = 1) as product_is_on_sale, 
        (select id from product_favorites where product_favorites.product_variation_id=product_variation.id and product_favorites.user_id=" . $userid . " limit 0,1) as favorite, 
        retailers.business_name,retailers.banner_image,retailers.business_address, retailers.city, retailers.state, retailers.phone_number, retailers.email,retailers.business_description,
        retailers.longitude,retailers.latitude, retailers.from_mobile, retailers.from_mobile, categories.name as category_name';
        return DB::raw($str);
    }

    function uniqueArray($array, $property)
    {
        $tempArray = array_unique(array_column($array, $property));
        $onePropertyUniqueArrayOfObjects = array_values(array_intersect_key($array, $tempArray));
        return $onePropertyUniqueArrayOfObjects;
    }


    function getSelectDBRawCart()
    {
        $str = 'cart.*,
        products.product_name, products.id as product_id, products.image,product_variation.status,
        product_variation.quantity as stock_value, product_variation.low_stock_value, product_variation.sale_price,product_variation.price,
        product_variation.variant_types,product_variation.variant_type_values,product_variation.on_sale,
        retailers.business_name,retailers.banner_image,retailers.business_address, retailers.city, retailers.state, retailers.phone_number, retailers.email,retailers.business_description,
        retailers.longitude,retailers.latitude, retailers.from_mobile, retailers.from_mobile, categories.name as category_name';
        return DB::raw($str);
    }
    function strright($str)
    {
        $strpos = strpos($str, "|");

        if ($strpos === false) {
            return $str;
        } else {
            return substr($str, $strpos + 1);
        }
    }
    function getProductDetail($productVariationIds)
    {
        $products = DB::table('product_variation')
        ->join('products', 'products.id', '=', 'product_variation.product_id')
        ->join('retailers', 'retailers.id', '=', 'products.store_id')
        ->join('categories', 'categories.id', '=', 'products.category_id')
        ->select($this->getSelectDBRawCartDisplay())
            ->where('products.status', '=', 1)
            ->where('product_variation.status', '=', 1);

        $products = $products->whereNested(function ($q) use ($productVariationIds) {
            foreach ($productVariationIds as $id) {
                $q = $q->orWhere("product_variation.id", $id);
            }
        });
        $products = $products->groupBy(
            "product_variation.id");
        $products = $products->orderBy("products.product_name");
        $products = $products->get();

        return $products;
    }
}
