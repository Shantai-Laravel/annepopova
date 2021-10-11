<ul>
  <li>
    <label for="country">Country</label>
    <select name="country" class="name filterCountries" data-id="0" id="country">
        <option disabled selected>Выберите страну</option>
        @foreach ($countries as $onecountry)
            <option {{!empty($user->address) && $user->address->country == $onecountry->id ? 'selected' : '' }} value="{{$onecountry->id}}">{{$onecountry->translation->name}}</option>
        @endforeach
    </select>
  </li>
  <li>
    <label for="region">Region</label>
    <input type="text" name="region" class="name" id="region" value="{{$user->address->region}}">
  </li>
  <li>
    <label for="location">City</label>
    <input type="text" name="location" class="name" id="location" value="{{$user->address->location}}">
  </li>
  <li>
      <label for="address">Address</label>
      <input type="text" name="address" class="name" id="address" value="{{$user->address->address}}">
  </li>
  <li>
      <label for="code">Code</label>
      <input type="text" name="code" class="name" id="code" value="{{$user->address->code}}">
  </li>
  <li>
      <label for="apartment">Apartment</label>
      <input type="text" name="apartment" class="name" id="apartment" value="{{$user->address->apartment}}">
  </li>
  <ul>
      <li>
        <label for="country">Payment</label>
        <select name="payment_id" class="name filterCountries">
            <option disabled selected>Choose method</option>
            @foreach ($payments as $payment)
                <option {{ $user->payment_id == $payment->id ? 'selected' : '' }} value="{{$payment->id}}">{{$payment->translation->name}}</option>
            @endforeach
        </select>
      </li>
  </ul>
</ul>
