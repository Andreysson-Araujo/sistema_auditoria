<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalhes das Respostas - {{ $servidor->servidor_nome }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-8">
    <div class="max-w-4xl mx-auto">
        
        <div class="bg-white rounded-t-lg shadow-md p-6 border-b-4 border-blue-500">
            <div class="flex justify-between items-start">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">{{ $servidor->servidor_nome }}</h1>
                    <p class="text-gray-500 uppercase text-sm font-semibold">{{ $servidor->orgao->orgao_nome ?? 'Órgão não informado' }}</p>
                </div>
                <a href="{{ route('auditoria.pendentes') }}" class="text-blue-600 hover:underline text-sm font-medium">← Voltar para Pendentes</a>
            </div>
            
            <div class="grid grid-cols-2 gap-4 mt-6 text-sm text-gray-600">
                <div class="bg-gray-50 p-3 rounded">
                    <strong>Nível:</strong> {{ $servidor->nivel->nivel_nome ?? 'N/A' }}
                </div>
                <div class="bg-gray-50 p-3 rounded">
                    <strong>Central:</strong> {{ $servidor->central->central_nome ?? 'N/A' }}
                </div>
            </div>
        </div>

        <div class="bg-white shadow-md p-6 rounded-b-lg">
            <h2 class="text-lg font-bold text-gray-700 mb-6 border-l-4 border-blue-500 pl-2">Respostas Enviadas</h2>

            <div class="space-y-6">
                @forelse($servidor->respostas as $index => $resposta)
                    <div class="border-b pb-4 last:border-0">
                        <p class="text-gray-800 font-medium mb-2">
                            <span class="text-blue-500 mr-2">{{ $index + 1 }}.</span>
                            {{-- AJUSTADO: Usando o nome correto da coluna --}}
                            {{ $resposta->pergunta->texto_pergunta ?? 'Pergunta não encontrada' }}
                        </p>
                        
                        <div class="flex items-center gap-4">
                            <span class="px-3 py-1 rounded-full text-xs font-bold uppercase {{ strtolower($resposta->valor) == 'sim' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                {{ $resposta->valor }}
                            </span>
                        </div>
                    </div>
                @empty
                    <p class="text-center text-gray-500 py-10 italic">Nenhuma resposta encontrada para este servidor.</p>
                @endforelse
            </div>

            <div class="mt-10 pt-6 border-t flex justify-end">
                <button type="button" onclick="window.print()" class="mr-4 px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">Imprimir</button>
                
                {{-- AJUSTADO: Rota para finalizar a auditoria --}}
                <form action="{{ route('auditoria.finalizar', $servidor->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="px-6 py-2 bg-green-600 text-white rounded-lg font-bold hover:bg-green-700 transition shadow-lg">
                        Finalizar e Gerar Feedback
                    </button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>