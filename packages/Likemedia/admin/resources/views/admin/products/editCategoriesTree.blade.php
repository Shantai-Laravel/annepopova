<ul class="cats-tree">
@if (count(SelectProdsCatsTree(1, 0)) > 0)
@foreach (SelectProdsCatsTree(1, 0) as $key => $category)
<li>
    <ul>
        @if (count(SelectProdsCatsTree(1, $category->id)) > 0)
        <li>
            <div class="form-group">
                <label>
                    <input class="checkbox" type="checkbox" name="categories[]" value="{{ $category->id }}" {{ checkProductsSimilar($product->id, $category->id) ? 'checked' : ''}}>
                    <span>{{ $category->name }}</span>
                </label>
            </div>
            <ul>
                @foreach (SelectProdsCatsTree(1, $category->product_category_id) as $key => $category)
                @if (count(SelectProdsCatsTree(1, $category->id)) > 0)
                <li>
                    <div class="form-group">
                        <label>
                            <input class="checkbox" type="checkbox" name="categories[]" value="{{ $category->id }}" {{ checkProductsSimilar($product->id, $category->id) ? 'checked' : ''}}>
                            <span>{{ $category->name }}</span>
                        </label>
                    </div>
                    <ul>
                        @foreach (SelectProdsCatsTree(1, $category->product_category_id) as $key => $category)
                        <li>
                            <div class="form-group">
                                <label>
                                    <input class="checkbox" type="checkbox" name="categories[]" value="{{ $category->id }}" {{ checkProductsSimilar($product->id, $category->id) ? 'checked' : ''}}>
                                    <span>{{ $category->name }}</span>
                                </label>
                            </div>
                        </li>
                        @endforeach
                    </ul>
                </li>
                @else
                <li>
                    <div class="form-group">
                        <label>
                            <input class="checkbox" type="checkbox" name="categories[]" value="{{ $category->id }}" {{ checkProductsSimilar($product->id, $category->id) ? 'checked' : ''}}>
                            <span>{{ $category->name }}</span>
                        </label>
                    </div>
                </li>
                @endif
                @endforeach
            </ul>
        </li>
        @else
        <li>
            <div class="form-group">
                <label>
                    <input class="checkbox" type="checkbox" name="categories[]" value="{{ $category->id }}" {{ checkProductsSimilar($product->id, $category->id) ? 'checked' : ''}}>
                    <span>{{ $category->name }}</span>
                </label>
            </div>
        </li>
        @endif
    </ul>
</li>
@endforeach
@endif
</ul>

<script>
$(function() {
    $("input[type='checkbox']").change(function () {
        console.log('cds');
    $(this).parent().parent('.form-group').siblings('ul')
           .find("input[type='checkbox']")
           .prop('checked', this.checked);
    });
});
</script>
