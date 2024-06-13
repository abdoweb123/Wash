@props(['value' => null,'name', 'parameter' => ''])
<option {{ $attributes->merge(['class'=>'form-group']) }} {{ $parameter }} value="{{ $value }}">{{ $name }}</option>
