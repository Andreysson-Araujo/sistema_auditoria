<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel de Auditoria - Feedbacks</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 p-8">
    <div class="max-w-6xl mx-auto">
        
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Histórico de Auditorias (Feedbacks)</h1>
            <div class="flex gap-2">
                <a href="/admin" class="text-sm bg-gray-200 px-4 py-2 rounded hover:bg-gray-300">Voltar ao Painel</a>
                <a href="{{ route('auditoria.pendentes') }}" class="text-sm bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 font-bold">Ver Pendentes</a>
            </div>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-sm mb-6 border border-gray-200">
            <form action="{{ route('auditoria.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                
                <div>
                    <label for="orgao_id" class="block text-xs font-bold text-gray-600 uppercase mb-1">Órgão</label>
                    <select name="orgao_id" id="orgao_id" class="w-full border-gray-300 rounded-md shadow-sm text-sm p-2 bg-gray-50 focus:ring-amber-500 focus:border-amber-500">
                        <option value="">Todos os Órgãos</option>
                        @foreach($orgaos as $orgao)
                            <option value="{{ $orgao->id }}" {{ request('orgao_id') == $orgao->id ? 'selected' : '' }}>
                                {{ $orgao->orgao_nome }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="data" class="block text-xs font-bold text-gray-600 uppercase mb-1">Data da Auditoria</label>
                    <input type="date" name="data" id="data" value="{{ request('data') }}" 
                        class="w-full border-gray-300 rounded-md shadow-sm text-sm p-2 bg-gray-50 focus:ring-amber-500 focus:border-amber-500">
                </div>

                <div>
                    <label for="nota_minima" class="block text-xs font-bold text-gray-600 uppercase mb-1">Nota Mínima (%)</label>
                    <input type="number" name="nota_minima" id="nota_minima" value="{{ request('nota_minima') }}" placeholder="Ex: 80"
                        class="w-full border-gray-300 rounded-md shadow-sm text-sm p-2 bg-gray-50 focus:ring-amber-500 focus:border-amber-500">
                </div>

                <div class="flex gap-2">
                    <button type="submit" class="bg-amber-500 text-white px-4 py-2 rounded-md hover:bg-amber-600 text-sm font-bold flex-1 transition">
                        Pesquisar
                    </button>
                    <a href="{{ route('auditoria.index') }}" class="bg-gray-100 text-gray-500 px-4 py-2 rounded-md hover:bg-gray-200 text-sm border border-gray-300 transition text-center">
                        Limpar
                    </a>
                </div>
            </form>
        </div>

        <div class="bg-white shadow-md rounded-lg overflow-hidden border border-gray-200">
            <table class="w-full text-left border-collapse">
                <thead class="bg-amber-500 text-white">
                    <tr>
                        <th class="p-4">Servidor Auditado</th>
                        <th class="p-4">Auditor</th>
                        <th class="p-4 text-center">Data</th>
                        <th class="p-4 text-center">Conformidade</th>
                        <th class="p-4 text-center">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($feedbacks as $feedback)
                        <tr class="border-b hover:bg-gray-50 transition">
                            <td class="p-4 text-sm">
                                <div class="font-semibold text-gray-800">{{ $feedback->servidor->servidor_nome }}</div>
                                <div class="text-[10px] text-gray-500 uppercase tracking-wider">{{ $feedback->servidor->orgao->orgao_nome ?? 'Sem Órgão' }}</div>
                            </td>
                            <td class="p-4 text-gray-600 text-sm italic">{{ $feedback->user->name ?? 'N/A' }}</td>
                            <td class="p-4 text-center text-gray-500 text-sm">{{ $feedback->created_at->format('d/m/Y') }}</td>
                            
                            <td class="p-4 text-center">
                                <span class="px-3 py-1 rounded-full text-xs font-bold 
                                    {{ $feedback->nota_final >= 80 ? 'bg-green-100 text-green-700 border border-green-200' : 
                                       ($feedback->nota_final >= 50 ? 'bg-yellow-100 text-yellow-700 border border-yellow-200' : 'bg-red-100 text-red-700 border border-red-200') }}">
                                    {{ number_format($feedback->nota_final, 1) }}%
                                </span>
                            </td>

                            <td class="p-4 text-center">
                                <a href="{{ route('auditoria.show', $feedback->id) }}" class="inline-block bg-blue-500 text-white px-4 py-1.5 rounded-full text-xs hover:bg-blue-600 shadow-sm transition">
                                    Ver Detalhes
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="p-12 text-center text-gray-400 italic">Nenhuma auditoria encontrada com esses filtros.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            {{ $feedbacks->links() }}
        </div>

    </div>
</body>
</html>