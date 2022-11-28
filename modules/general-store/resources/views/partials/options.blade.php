@isset($options)
    @if(isset($placeholer))
        <option value="">{{ $placeholer }}</option>
    @else
        <option value="">Select & Search</option>
    @endif
    @foreach($options as $key => $value)
        <option value="{{ $key }}">{{ $value }}</option>
    @endforeach
@endisset
