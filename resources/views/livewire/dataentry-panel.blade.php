{{-- resources/views/livewire/dataentry-panel.blade.php --}}
<div class="space-y-8">

    @include('livewire.dataentry._header')

    @include('livewire.dataentry._alertas')

    @include('livewire.dataentry._buscador')

    @include('livewire.dataentry._pendientes')

    @include('livewire.dataentry._form-grupo')

    @include('livewire.dataentry._form-individual')

    @include('livewire.dataentry._modal-exportar')

    @include('livewire.dataentry._modal-completados')

    @include('livewire.dataentry._modal-detalles')

    <script>
        window.addEventListener('descargar-excel', event => {
            const inicio = event.detail.inicio;
            const fin = event.detail.fin;
            const url = `/exportar-excel/${inicio}/${fin}`;
            window.open(url, '_blank');
        });
    </script>

    <script>
    window.addEventListener('scroll-a-formulario', () => {
        // Espera a que Livewire termine de renderizar el DOM
        setTimeout(() => {
            const form = document.getElementById('form-documentacion');
            if (form) {
                form.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        }, 150);
    });
</script>

</div>