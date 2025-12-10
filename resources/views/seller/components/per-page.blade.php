<select onchange="this.form.submit()" name="per_page" class="select select-bordered sm:w-auto">
    @foreach ([10, 25, 50, 100] as $val)
        <option value="{{ $val }}" {{ request('per_page', 10) == $val ? 'selected' : '' }}>
            {{ $val }}
        </option>
    @endforeach
</select>
