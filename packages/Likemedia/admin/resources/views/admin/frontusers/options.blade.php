@foreach ($locationItems as $locationItem)
    <option value="{{$locationItem->id}}">{{$locationItem->name}}</option>
@endforeach
