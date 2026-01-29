<div class="border-0 card w-100 rounded-0" id="kt_drawer_chat_messenger" wire:ignore.self>
    <div class="card-header pe-5" id="kt_drawer_chat_messenger_header">
        <div class="card-title">
            <div class="d-flex justify-content-center flex-column me-3">
                <a href="#" class="mb-2 text-gray-900 fs-4 fw-bold text-hover-primary me-1 lh-1">IA Miora</a>
                <div class="mb-0 lh-1">
                    <span class="badge badge-success badge-circle w-10px h-10px me-1"></span>
                    <span class="fs-7 fw-semibold text-muted">Activo</span>
                </div>
            </div>
        </div>
        <div class="card-toolbar">
            <div class="btn btn-sm btn-icon btn-active-color-primary" id="kt_drawer_chat_close">
                <i class="ki-outline ki-cross-square fs-2"></i>
            </div>
        </div>
    </div>
    @if ($cargando)
        <div class="mb-4 d-flex flex-column align-items-start">
            <div class="p-3 rounded bg-light-secondary text-muted">
                <strong>IA Miora:</strong><br>
                <span class="spinner-border spinner-border-sm me-2"></span>Escribiendo respuesta...
            </div>
        </div>
    @else
        <div class="card-body" id="kt_drawer_chat_messenger_body">
            <div class="scroll-y me-n5 pe-5" data-kt-element="messages" style="max-height: 60vh; overflow-y: auto;">
                @foreach ($historial as $msg)
                    <div class="d-flex flex-column mb-4 align-items-{{ $msg['usuario'] === 'tú' ? 'end' : 'start' }}">
                        <div class="p-3 rounded bg-light-primary text-dark">
                            <strong>{{ $msg['usuario'] }}:</strong><br>
                            {{ $msg['texto'] }}
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="pt-4 card-footer" id="kt_drawer_chat_messenger_footer">
            <form wire:submit.prevent="enviar" class="d-flex flex-stack">
                <textarea id="chat-input" wire:model.defer="mensaje" class="mb-3 form-control form-control-flush" rows="1"
                    placeholder="Pregúntame algo y presiona Enter..."></textarea>
            </form>
        </div>
    @endif


    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const textarea = document.getElementById('chat-input');

                textarea.addEventListener('keydown', function(e) {
                    if (e.key === 'Enter' && !e.shiftKey) {
                        e.preventDefault(); // evita salto de línea
                        textarea.closest('form').dispatchEvent(new Event('submit', {
                            bubbles: true
                        }));
                    }
                });
            });
        </script>
    @endpush

</div>
