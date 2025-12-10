<div class="form-control">
    <input type="text" name="search" placeholder="Cari..." value="{{ request('search') }}"
        class="input input-bordered w-full" />
</div>
<button type="submit" class="btn btn-primary w-full sm:w-auto">
    <i class="ri-search-line"></i>
</button>
<a href="{{ $indexRoute() }}" class="btn btn-error w-full sm:w-auto">
    <i class="ri-refresh-line"></i>
</a>
