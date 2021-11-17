@props(['name', 'required' => true, 'label' => null])

<div class="form-control">
    @if ($label)
        <label class="label">
            <span class="label-text">{{ $label }} @if($required) <span class="text-red-600">*</span> @endif</span>
        </label>
    @endif
    <input class="input input-bordered" type="text" name="{{ $name }}" />
    @error($name)
        <label class="label">
            <span class="label-text-alt text-red-600">{{ $message }}</span>
        </label>
    @enderror
</div>
