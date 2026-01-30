<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Feedback - {{ $feedback->servidor->servidor_nome }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
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
                    <span class="block text-xs text-gray-400 uppercase font-bold">Nota Final</span>
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
            <h2 class="text-lg font-bold text-gray-700 mb-6 border-l-4 border-amber-500 pl-2">Relatório de Auditoria Concluído</h2>

            <div class="space-y-6">
                @forelse($feedback->respostas as $index => $resposta)
                    <div class="border-b pb-4 last:border-0">
                        <p class="text-gray-800 font-medium mb-2">
                            <span class="text-amber-500 mr-2">{{ $index + 1 }}.</span>
                            {{-- Puxando a pergunta através da resposta vinculada ao feedback --}}
                            {{ $resposta->perguntas->texto_pergunta ?? 'Pergunta não encontrada' }}
                        </p>
                        
                        <div class="flex items-center gap-4">
                            <span class="px-3 py-1 rounded-full text-xs font-bold uppercase {{ strtolower($resposta->valor) == 'sim' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                {{ $resposta->valor }}
                            </span>
                        </div>
                    </div>
                @empty
                    <p class="text-center text-gray-500 py-10 italic">Nenhuma resposta encontrada para este feedback.</p>
                @endforelse
            </div>

            <div class="mt-10 pt-6 border-t flex justify-between items-center">
                <p class="text-xs text-gray-400">Auditado por: {{ $feedback->user->name }} em {{ $feedback->created_at->format('d/m/Y H:i') }}</p>
                <div>
                    <a href="{{ route('auditoria.index') }}" class="mr-4 text-gray-500 hover:underline text-sm font-medium">Voltar</a>
                    <button onclick="window.print()" class="px-6 py-2 bg-blue-600 text-white rounded-lg font-bold hover:bg-blue-700 transition shadow-lg">
                        Imprimir Relatório
                    </button>
                </div>
            </div>
        </div>
    </div>
</body>
</html>