<div class="form-check">
    <input type="checkbox" value="{{ $om->id }}" {{ old('om.'.$om->id)==$om->id ?'checked':'' }} name="om[{{ $om->id }}]"  class="mischeck form-check-input @error('om.'.$om->id) is-invalid @enderror" id="om-{{ $om->id }}">
</div>