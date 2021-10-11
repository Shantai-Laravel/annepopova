@section('left-menu')
<aside class="sidebar">
    <div class="sidebar-container">
        <div class="sidebar-header">
            <span class="brand">
                <i class="fa fa-adn"></i>  Admin Panel
            </span>
        </div>
        <nav class="menu">
            @if(!is_null($menu))
            <ul class="nav metismenu" id="sidebar-menu">
                <li>
                    <a href="/back">
                        <i class="fa fa-dashboard"></i>Control Panel
                    </a>
                </li>
                @foreach($menu as $m) @if($m->src == 'products') @include('admin::admin.partials.productCategories') @else
                <li class="{{ request()->segment(2) == $m->src  ? 'active' : ''}}">
                    <a class="{{ count(hasSubmodule($m->id)) > 0 ? 'drop-down' : '' }}" href="/back/{{ $m->src }}">
                      <i class="fa {{ $m->icon }}"></i>
                      {{ $m->name ?? '' }}
                      {!! count(hasSubmodule($m->id)) > 0 ? '<i class="fa arrow"></i></a>' : '' !!}
                    </a>
                    @if(count(hasSubmodule($m->id)))
                    <ul class="drop-hd">
                        @foreach(hasSubmodule($m->id) as $module)
                        <li>
                            <a href="/back/{{ $module->src }}">
                              {{ $module->name }}
                              </a>
                        </li>
                        @endforeach
                    </ul>
                    @endif
                </li>
                @endif @endforeach
            </ul>
            @endif
        </nav>
    </div>
</aside>
@stop
