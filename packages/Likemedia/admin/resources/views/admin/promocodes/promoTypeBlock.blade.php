<script src="{{asset('admin/js/datepicker.js')}}"></script>

<div class="col-md-3">
    <div class="form-group">
        <label for="treshold">Treshold</label>
        <input type='number' class="form-control"  name="treshold" value="{{ !is_null($promoType) ? $promoType->treshold : '' }}" required/>
    </div>
</div>
<div class="col-md-3">
    <div class="form-group">
        <label for="discount">Discount %</label>
        <input type='number' class="form-control"  name="discount" value="{{ !is_null($promoType) ? $promoType->discount : '' }}" required/>
    </div>
</div>
<div class="col-md-3">
    <div class="form-group">
        <label for="valid_from">Valid From</label>
        <input type='date' class="form-control datepicker-from" autocomplete="off" data-position="left top" name="valid_from" value="{{ date('Y-m-d', strtotime($date ?? date('d.m.Y'))) }}"/>
    </div>
</div>
<div class="col-md-3">
    <div class="form-group">
        <label for="valid_to">Valid To <small class="text-danger">{{ !is_null($promoType) ? '+ '.$promoType->period .'days' : ''}}</small></label>
        <input type='date' class="form-control"  autocomplete="off" data-position="left top" name="valid_to"  value="{{ !is_null($promoType) ? date('Y-m-d', strtotime($date .' + '.$promoType->period.' days')) : '' }}" />
    </div>
</div>
<div class="col-md-12">
    <hr>
</div>
<div class="col-md-7">
    <div class="col-md-3 text-right">
        <label> Period: </label>
    </div>
    <div class="col-md-5">
        {{ !is_null($promoType) ? $promoType->period : '0' }} days
    </div> <br><hr>
</div>
<div class="col-md-7">
    <div class="col-md-3 text-right">
        <label> Times: </label>
    </div>
    <div class="col-md-5">
         {{ !is_null($promoType) ? $promoType->times : '0' }}
    </div> <br><hr>
</div>
<div class="col-md-7">
    <div class="col-md-3 text-right">
        <label> Was used: </label>
    </div>
    <div class="col-md-5">
        0
    </div> <br><hr>
</div>
<div class="col-md-7">
    <div class="col-md-3 text-right">
        <label> User: </label>
    </div>
    <div class="col-md-5">
        ----
    </div> <br><hr>
</div>
