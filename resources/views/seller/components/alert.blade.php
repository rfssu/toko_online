<script type="module">
    @if (session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Sukses',
            timer: 1000,
            theme: localStorage.getItem('theme') === 'halloween' ? 'dark' : 'light',
            text: '{{ session('success') }}',
            showConfirmButton: false
        });
    @endif

    @if (session('error'))
        Swal.fire({
            icon: 'error',
            title: 'Gagal',
            theme: localStorage.getItem('theme') === 'halloween' ? 'dark' : 'light',
            text: '{{ session('error') }}',
            showConfirmButton: false
        });
    @endif
</script>
