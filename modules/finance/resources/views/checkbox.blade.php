@if(isset($value) & isset($name) && isset($text))
    <div class="form-group">
        <label class="md-check">
            <input class="{{ $class ?? '' }}"
                   value="{{ $value }}"
                   name="{{ $name }}"
                   type="checkbox">
            <i class="{{ $class ?? 'blue' }}"></i>
            {{ $text }}
        </label>
    </div>
@endif