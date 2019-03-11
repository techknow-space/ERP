<div class="row">
    <div class="col">
        @if(session()->has('success'))
            @foreach(session('success') as $message)
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{$message}}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endforeach
        @endif
        @if(session()->has('error'))
            @foreach(session('error') as $message)
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{$message}}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endforeach
        @endif
    </div>
</div>
