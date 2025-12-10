@php
    $action = $model->id ? route('barangs.update', $model->id) : route('barangs.store');
@endphp

<div class="p-6">
    <h2 class="text-xl font-bold mb-4">{{ empty($model->id) ? 'Tambah Barang' : 'Edit Barang' }}</h2>

    <form method="POST" action="{{ $action }}" id="form-elem" class="space-y-3" enctype="multipart/form-data">
        @csrf

        @if ($model->id)
            @method('PUT')
        @endif

        <div class="form-control">
            <input type="text" name="nama_barang" placeholder="Nama Barang" class="input input-bordered w-full" />
        </div>

        <div class="form-control">

            <fieldset class="fieldset">
                <legend class="fieldset-legend">Pilih Gambar</legend>
                <input type="file" name="upload_gambar" class="file-input file-input-bordered w-full" />
            </fieldset>
            @if ($model->file('gambar')->hasFile())
                <div class="mt-4">
                    <p class="text-sm mb-2 font-bold">Gambar Saat Ini:</p>
                    <div class="avatar">
                        <div class="w-24 rounded">
                            <img src="{{ $model->file('gambar')->preview() }}" alt="Preview" />
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <div class="form-control">
            <input type="number" name="harga" placeholder="Harga" class="input input-bordered w-full" />
        </div>

        <div class="form-control">
            <input type="number" name="stok" placeholder="Stok" class="input input-bordered w-full" />
        </div>

        <div class="form-control">
            <textarea name="keterangan" placeholder="Keterangan" class="textarea textarea-bordered w-full"></textarea>
        </div>

        <button type="submit" class="btn btn-primary w-full">
            {{ empty($model->id) ? 'Simpan' : 'Update' }}
        </button>
    </form>
</div>
<script id="data-json" type="application/json">
    {!! $model->toJson(JSON_FORCE_OBJECT) !!}
</script>

<script type="module">
    jsonScriptToFormFields('#form-elem', '#data-json');
    $('#form-elem').formAjaxSubmit();
</script>
