    <div class="">
        @if ($iduser == 0)
        @else
            @foreach ($mensajes as $item)
                @if ($item->emisor == Auth::user()->id)
                    <!-- Reciever Message-->
                    <div class="media w-50 ml-auto mb-3">
                        <div class="media-body">
                            <div class="bg-primary rounded py-2 px-3 mb-2">
                                <p class="text-small mb-0 text-white">{{ $item->mensaje }}</p>
                            </div>
                            <p class="small text-muted">{{ $item->hora }}| {{ $item->fecha }}</p>
                        </div>
                    </div>
                @else
                    <!-- Sender Message-->
                    <div class="media w-50 mb-3">
                        <div class="media-body ml-3">
                            <div class="rounded py-2 px-3 mb-2 bg-warning">
                                <p class="text-small mb-0 text-white">{{ $item->mensaje }}</p>
                            </div>
                            <p class="small text-muted"> {{ $item->hora }}| {{ $item->fecha }}</p>
                        </div>
                    </div>
                @endif
            @endforeach
        @endif
    </div>
