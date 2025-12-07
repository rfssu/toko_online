<script type="module">
    @if (session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Sukses',
            timer: 1000,
            text: '{{ session('success') }}',
            showConfirmButton: false
        });
    @endif

    @if (session('error'))
        Swal.fire({
            icon: 'error',
            title: 'Gagal',
            text: '{{ session('error') }}',
            showConfirmButton: false
        });
    @endif
</script>
