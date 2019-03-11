<form class="form-inline">
    <div class="form-group">
        <label for="appLocationSelect"></label>
        <select class="form-control" name="appLocationSelect" id="appLocationSelect">
            @foreach(\App\Models\Location::all() as $location)
                <option
                    value="{{$location->id}}"
                    @if($location->id == \App\Http\Controllers\HelperController::getCurrentLocation()->id)
                    selected
                    @endif
                >{{$location->location}}</option>
            @endforeach
        </select>
    </div>
</form>
