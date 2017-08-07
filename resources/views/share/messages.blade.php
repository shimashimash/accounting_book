@php(
    $messages = is_array($messages)
        ? $messages
        : ($messages instanceof \Illuminate\Support\ViewErrorBag)
            ? $messages->all()
            : [$messages]
)
@if (! empty($messages))
    <div class="clearfix mb10">
        <p class="message-bg bg-{{ isset($class) ? $class : 'danger' }} ">
            @foreach ($messages as $message)
                {{ $message }}<br>
            @endforeach
        </p>
    </div>
@endif
