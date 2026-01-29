<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Feedback - {{ $feedback->servidor->servidor_nome }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-8">
    <div class="max-w-5xl mx-auto">
        
        <div class="bg-white rounded-t-lg shadow-md p-6 border-b">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">Relatório de Auditoria</h1>
                    <p class="text-gray-500 uppercase text-xs font-bold tracking-wider">
                        {{ $feedback->servidor->orgao->orgao_nome ?? 'Órgão N/A' }}
                    </p>
                </div>
                <div class="text-right">
                    <span class="block text-sm text-gray-400">Nota de Conformidade</span>
                    <span class="text-4xl font-black {{ $feedback->nota_final >= 80 ? 'text-green-600' : 'text-red-600' }}">
                        {{ number_format($feedback->nota_final, 1) }}%
                    </span>
                </div>
            </div>
        </div>

        <div class="bg-gray-50 border-b p-4 grid grid-cols-3 gap-4 text-sm shadow-sm">
            <div><strong>Servidor:</strong> {{ $feedback->servidor->servidor_nome }}</div>
            <div><strong>Auditor:</strong> {{ $feedback->user->name }}</div>
            <div><strong>Data:</strong> {{ $feedback->created_at->format('d/m/Y H:i') }}</div>
        </div>

        <div class="bg-white shadow-md rounded-b-lg p-6">
            <h2 class="text-lg font-bold text-gray-700 mb-6">Detalhamento das Questões</h2>

            <div class="space-y-4">
                @foreach($feedback->respostas as $index => $resposta)
                    <div class="flex items-start justify-between p-4 rounded-lg border {{ strtolower($resposta->valor) == 'sim' ? 'bg-green-50 border-green-100' : 'bg-red-50 border-red-100' }}">
                        <div class="flex-1">
                            <span class="text-xs font-bold text-gray-400 uppercase tracking-tighter block mb-1">
                                Pilar: {{ $resposta->pilar->pilar_value ?? 'N/A' }}
                            </span>
                            <p class="text-gray-800 font-medium">
                                <span class="mr-2">{{ $index + 1 }}.</span>
                                {{ $resposta->pergunta->texto_pergunta ?? 'Questão removida' }}
                            </p>
                        </div>
                        <div class="ml-4">
                            <span class="px-4 py-1 rounded-full text-xs font-black uppercase {{ strtolower($resposta->valor) == 'sim' ? 'text-green-700' : 'text-red-700' }}">
                                {{ $resposta->valor }}
                            </span>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-8 flex justify-between">
                <a href="{{ route('auditoria.index') }}" class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition font-bold">
                    Voltar para o Histórico
                </a>
                <button onclick="window.print()" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-bold shadow-lg">
                    Imprimir Relatório
                </button>
            </div>
        </div>
    </div>
</body>
</html>