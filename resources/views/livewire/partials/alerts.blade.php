{{-- resources/views/livewire/receptor/partials/alerts.blade.php --}}
<!-- Contenedor de Alertas -->
<div id="flash-container" class="fixed bottom-4 right-4 z-[9999] flex flex-col gap-3">
    @if (session()->has('message'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded shadow-xl flex items-center justify-between min-w-[300px]">
            <span>{{ session('message') }}</span>
            <button onclick="this.parentElement.remove()" class="ml-4 font-bold">✕</button>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded shadow-xl flex items-center justify-between min-w-[300px]">
            <span>{{ session('error') }}</span>
            <button onclick="this.parentElement.remove()" class="ml-4 font-bold">✕</button>
        </div>
    @endif
</div>

<script>
    (function() {
        const closeAlerts = () => {
            document.querySelectorAll('.bg-green-100, .bg-red-100').forEach(alert => {
                if (!alert.dataset.timerStarted) {
                    alert.dataset.timerStarted = "true";
                    setTimeout(() => {
                        alert.style.opacity = '0';
                        alert.style.transition = 'opacity 0.5s ease';
                        setTimeout(() => alert.remove(), 500);
                    }, 4000);
                }
            });
        };

        closeAlerts();
        
        const observer = new MutationObserver(closeAlerts);
        observer.observe(document.body, { childList: true, subtree: true });
    })();

    function confirmarEliminacion(bienId) {
        if (confirm('¿Estás segura de eliminar este bien? Esta acción no se puede deshacer.')) {
            Livewire.dispatch('eliminarBienConfirmado', { id: bienId });
        }
    }
</script>