<ul class="cabNav">
    <li class="{{ Request::segment(3) == 'personal-data' ? 'active' : '' }}">
        <a href="{{ url('/'.$lang->lang.'/account/personal-data') }}">
            {{ trans('vars.Cabinet.viewPersonalData') }}
        </a>
    </li>
    <li class="{{ Request::segment(3) == 'cart' ? 'active' : '' }}">
        <a href="{{ url('/'.$lang->lang.'/account/cart') }}">
            {{ trans('vars.Cabinet.viewCart') }}
        </a>
    </li>
    <li class="{{ Request::segment(3) == 'wishlist' ? 'active' : '' }}">
        <a href="{{ url('/'.$lang->lang.'/account/wishlist') }}">
            {{ trans('vars.Cabinet.viewWishlist') }}
        </a>
    </li>
    <li class="{{ Request::segment(3) == 'promocodes' ? 'active' : '' }}">
        <a href="{{ url('/'.$lang->lang.'/account/promocodes') }}">
            {{ trans('vars.Cabinet.promocodes') }}
        </a>
    </li>
    <li class="{{ Request::segment(3) == 'history' ? 'active' : '' }}">
        <a href="{{ url('/'.$lang->lang.'/account/history') }}">
            {{ trans('vars.Cabinet.viewHistory') }}
        </a>
    </li>
    <li>
        <a href="{{ url('/'. $lang->lang .'/'. $site . '/logout') }}">
            {{ trans('vars.Auth.logout') }}
        </a>
    </li>
</ul>
