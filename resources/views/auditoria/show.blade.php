<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Feedback - {{ $feedback->servidor->servidor_nome }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            .no-print { display: none; }
            body { background-color: white; padding: 0; }
            .shadow-md { shadow: none; border: 1px solid #e5e7eb; }
        }
    </style>
</head>
<body class="bg-gray-100 p-8">
    <div class="max-w-4xl mx-auto">
        
        <div class="bg-white rounded-t-lg shadow-md p-6 border-b-4 border-amber-500">
            <div class="flex justify-between items-start">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">{{ $feedback->servidor->servidor_nome }}</h1>
                    <p class="text-gray-500 uppercase text-sm font-semibold">{{ $feedback->servidor->orgao->orgao_nome ?? 'Órgão não informado' }}</p>
                </div>
                <div class="text-right">
                    <span class="block text-xs text-gray-400 uppercase font-bold">Nota de Conformidade</span>
                    <span class="text-3xl font-black text-amber-600">{{ number_format($feedback->nota_final, 1) }}%</span>
                </div>
            </div>
            
            <div class="grid grid-cols-2 gap-4 mt-6 text-sm text-gray-600">
                <div class="bg-gray-50 p-3 rounded">
                    <strong>Nível:</strong> {{ $feedback->servidor->nivel->nivel_nome ?? 'N/A' }}
                </div>
                <div class="bg-gray-50 p-3 rounded">
                    <strong>Central:</strong> {{ $feedback->servidor->central->central_nome ?? 'N/A' }}
                </div>
            </div>
        </div>

        <div class="bg-white shadow-md p-6 rounded-b-lg">
            
            @if($feedback->comentario)
            <div class="mb-8 p-5 bg-amber-50 border-l-4 border-amber-500 rounded-r-lg shadow-sm">
                <h3 class="text-amber-800 font-bold text-sm uppercase tracking-wider mb-2 flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path>
                    </svg>
                    Parecer Técnico / Observações
                </h3>
                <p class="text-gray-700 leading-relaxed italic">
                    "{{ $feedback->comentario }}"
                </p>
            </div>
            @endif

            <h2 class="text-lg font-bold text-gray-700 mb-6 border-l-4 border-amber-500 pl-2">Detalhamento da Auditoria</h2>

            <div class="space-y-6">
                @forelse($feedback->respostas as $index => $resposta)
                    <div class="border-b pb-4 last:border-0">
                        <p class="text-gray-800 font-medium mb-2">
                            <span class="text-amber-500 mr-2">{{ $index + 1 }}.</span>
                            {{-- Corrigido para 'pergunta' no singular conforme seu Model --}}
                            {{ $resposta->pergunta->texto_pergunta ?? 'Pergunta não encontrada' }}
                        </p>
                        
                        <div class="flex items-center gap-4">
                            <span class="px-3 py-1 rounded-full text-xs font-bold uppercase {{ strtolower($resposta->valor) == 'sim' || (is_numeric($resposta->valor) && $resposta->valor >= 3) ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                Resposta: {{ $resposta->valor }}
                            </span>
                        </div>
                    </div>
                @empty
                    <p class="text-center text-gray-500 py-10 italic">Nenhuma resposta encontrada para este feedback.</p>
                @endforelse
            </div>

            <div class="mt-10 pt-6 border-t flex justify-between items-center">
                <div class="text-xs text-gray-400">
                    <p><strong>Auditor Responsável:</strong> {{ $feedback->user->name ?? 'Sistema' }}</p>
                    <p><strong>Realizado em:</strong> {{ $feedback->created_at->format('d/m/Y H:i') }}</p>
                </div>
                
                <div class="no-print flex items-center">
                    <a href="{{ route('auditoria.index') }}" class="mr-4 text-gray-500 hover:underline text-sm font-medium">
                        Voltar ao Histórico
                    </a>
                    <button onclick="window.print()" class="px-6 py-2 bg-blue-600 text-white rounded-lg font-bold hover:bg-blue-700 transition shadow-lg flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                        </svg>
                        Imprimir
                    </button>
                </div>
            </div>
        </div>
    </div>
</body>
</html>