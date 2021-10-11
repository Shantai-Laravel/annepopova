<li class="{{ request()->segment(2) == $m->src  ? 'active' : ''}}">
    <a class="drop-down" href="{{ url('/back/'.$m->src) }}">
        <i class="fa {{ $m->icon }}"></i> {{ $m->name ?? '' }} <i class="fa arrow"></i>
    </a>
    <ul class="drop-hd">
        @if (count(SelectProdsCatsTree($lang->id, 0)) > 0)
        @foreach (SelectProdsCatsTree($lang->id, 0) as $key => $category)
        <li>
            <ul class="drop-hd">
                @if (count(SelectProdsCatsTree($lang->id, $category->id)) > 0)
                <li>
                    <a class="drop-down"
                        href="{{ route('products.category', [$category->product_category_id]) }}">&ndash; {{ $category->name }}
                    <i class="fa {{ !empty(SelectProdsCatsTree($lang->id, $category->id)) ? 'arrow' : '' }}"></i> </a>
                    <ul class="drop-hd">
                        @foreach (SelectProdsCatsTree($lang->id, $category->product_category_id) as $key => $category)
                        @if (count(SelectProdsCatsTree($lang->id, $category->id)) > 0)
                        <li>
                            <a class="drop-down"
                                href="{{ route('products.category', [$category->product_category_id]) }}">&ndash;&ndash; {{ $category->name }}
                            <i class="fa arrow"></i></a>
                            <ul class="drop-hd">
                                @foreach (SelectProdsCatsTree($lang->id, $category->product_category_id) as $key => $category)
                                <li>
                                    <a href="{{ route('products.category', [$category->product_category_id]) }}">&ndash;&ndash;&ndash; {{ $category->name }} </a>
                                </li>
                                @endforeach
                            </ul>
                        </li>
                        @else
                        <li>
                            <a href="{{ route('products.category', [$category->product_category_id]) }}">&ndash;&ndash; {{ $category->name }}</a>
                        </li>
                        @endif
                        @endforeach
                    </ul>
                </li>
                @else
                <li>
                    <a href="{{ route('products.category', [$category->product_category_id]) }}">&ndash; {{ $category->name }} </a>
                </li>
                @endif
            </ul>
        </li>
        @endforeach
        @endif
    </ul>
</li>
<script>
    $().ready(function () {
        $('.arrow').each(function (index, value) {
            $('.arrow').eq(index).parent().addClass('drop-down');
        });
    });
</script>
