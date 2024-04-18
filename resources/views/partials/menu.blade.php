<ul class="nav flex-column">
    <li class="nav-item analytics @if($active=='analytics') active @endif" style="background-image: url({{asset('images/analytics.png')}}); background-size: cover;">
        <a class="nav-link" aria-current="page" href="{{ route('analytics') }}">Analytics</a>
    </li>
    @if(auth()->user()->admin)
    <li class="nav-item add-retailer @if($active=='add-retailer') active @endif" style="background-image: url({{asset('images/retailer.png')}}); background-size: cover">
        <a class="nav-link" href="{{ route('add-retailers') }}">Add Retailer</a>
    </li>
    @endif
    <li class="nav-item coupon @if($active=='add-coupon') active @endif" style="background-image: url({{asset('images/coupon.png')}}); background-size: cover">
        <a class="nav-link" href="{{ route('add-coupon') }}">Add Coupon</a>
    </li>
    <li class="nav-item coupons @if($active=='coupons') active @endif" style="background-image: url({{asset('images/view.png')}}); background-size: cover">
        <a class="nav-link" href="{{ route('coupons') }}">View Coupons</a>
    </li>
    <li class="nav-item coupons @if($active=='products') active @endif" style="background-image: url({{asset('images/retailer.png')}}); background-size: cover">
        <a class="nav-link" href="{{ route('products') }}">View Products</a>
    </li>
    @if(auth()->user()->admin)
    <li class="nav-item retailer @if($active=='retailers') active @endif" style="background-image: url({{asset('images/users.png')}}); background-size: cover">
        <a class="nav-link" href="{{ route('retailers') }}">View Retailers</a>
    </li>
    @endif
    @if(auth()->user()->admin == 1)
    <li class="nav-item users mb-2 @if($active=='users') active @endif" style="background-image: url({{asset('images/coupon.png')}}); background-size: cover">
        <a class="nav-link" href="{{ route('members') }}">View Users</a>
    </li>
    @endif
</ul>