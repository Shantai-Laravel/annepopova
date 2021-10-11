
<script type="text/javascript" src="https://code.jquery.com/jquery-2.1.4.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

<ul class="cats-tree">
    <div><small>Subprodus</small> | <small>Parametru</small></div>
    @if (count($parameters) > 0)
        @foreach ($parameters as $key => $parameter)
            <ul class="props-list">
                <li>
                    <div class="form-group">
                        <label>
                            @if ($parameter->type === 'select' || $parameter->type === 'checkbox')
                                @php
                                    $catSubProdParam = 0;
                                    if (!is_null($category->subproductParameter)) {
                                        $catSubProdParam = $category->subproductParameter->parameter_id;
                                    }
                                @endphp
                                <input type="radio" name="dependable" value="{{ $parameter->id }}" {{ $catSubProdParam == $parameter->id ? 'checked' : '' }}>
                                <span class="space"></span>
                            @else
                                <span class="space"> <i class="fa fa-close"></i> </span>
                                <span class="space"></span>
                            @endif
                            <input type="checkbox" name="properties[]" id="prop-{{ $parameter->id }}"  value="{{ $parameter->id }}" {{ $category->paremeterCategory($parameter->id)->first() ? 'checked' : '' }}>
                            <span class="space"></span>
                            <span class="space"></span>
                            <span>{{ $parameter->translation->name }} [{{ $parameter->key }}]</span>
                        </label>
                    </div>
                </li>
            </ul>
        @endforeach
    @endif
    <hr>
    <a href="{{ url('/back/product-categories/remove-dependable-parameter/'. $category->id ) }}" onclick="return confirm('Warning!!! All suproducts will be removed!')" class="btn btn-primary btn-sm">
        <i class="fa fa-close"></i>
        Remove dependable parameter
    </a>
    <p class="text-danger">
        <small>* Warning! All suproducts will be removed!</small>
    </p>
</ul>

<!-- Modal -->
<div class="modal fade" id="submitWarning" role="dialog">
    <div class="modal-dialog text-center">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Warning!</h4>
            </div>
            <div class="modal-body">
                <p>Ati modificat Parametrii si/sau Subprodusele</p>
                <p>Aceste modificari vor fi implimentate pentru toate subcategoriile a categoriei date</p>
                <h6>De acord?</h6>
                <button type="button" name="button" class="btn btn-warning submitForce">DA</button>
                <button type="button" name="button" class="btn btn-success remove-dependable-status" >NU</button>
            </div>
        </div>
    </div>
</div>

<script>
    $("input[type='radio']").change(function () {
        let id =  $(this).val();
        $('#prop-'+id).prop('checked', this.checked);

        $("input[name='dependable-status']").prop('value', 'true');
    });

    $('.form-reg').on('submit', function(e){
        if ($("input[name='dependable-status']").val() == 'true' && $("input[name='submit-status']").val() == 'false' ) {
            e.preventDefault();
            $('#submitWarning').modal('show');
        }else {
            this.submit();
        }
    });

    $('.remove-dependable-status').on('click', function() {
        $("input[name='dependable-status']").prop('value', 'false');
        $('.form-reg').submit();
    });

    $('.submitForce').on('click', function() {
        $("input[name='submit-status']").prop('value', 'true');
        $('.form-reg').submit();
    });
</script>
