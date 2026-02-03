<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Histórico de Auditoria - Painel</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-4 md:p-8">
    <div class="max-w-6xl mx-auto">
        
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-800 border-l-4 border-amber-500 pl-3">
                    Histórico de Auditorias
                </h1>
                <p class="text-xs text-gray-500 ml-4 mt-1 uppercase tracking-widest">Consulta de Feedbacks Realizados</p>
            </div>
            <div class="flex gap-2">
                <a href="/admin" class="text-sm bg-white border border-gray-300 px-4 py-2 rounded shadow-sm hover:bg-gray-50 transition">
                    Painel Admin
                </a>
                <a href="{{ route('auditoria.pendentes') }}" class="text-sm bg-blue-600 text-white px-4 py-2 rounded shadow-sm hover:bg-blue-700 font-bold transition">
                    Ver Pendentes
                </a>
            </div>
        </div>

        <div class="bg-white p-5 rounded-xl shadow-sm mb-6 border border-gray-200">
            <form action="{{ route('auditoria.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-4 items-end">
                
                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Filtrar por Órgão</label>
                    <select name="orgao_id" class="w-full border-gray-300 rounded-lg shadow-sm text-sm focus:ring-amber-500 focus:border-amber-500 bg-gray-50 p-2">
                        <option value="">Todos os Órgãos</option>
                        @foreach($orgaos as $orgao)
                            <option value="{{ $orgao->id }}" {{ request('orgao_id') == $orgao->id ? 'selected' : '' }}>
                                {{ $orgao->orgao_nome }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Data</label>
                    <input type="date" name="data" value="{{ request('data') }}" 
                        class="w-full border-gray-300 rounded-lg shadow-sm text-sm focus:ring-amber-500 focus:border-amber-500 bg-gray-50 p-2">
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Nota Mín %</label>
                    <input type="number" name="nota_minima" value="{{ request('nota_minima') }}" placeholder="Ex: 80"
                        class="w-full border-gray-300 rounded-lg shadow-sm text-sm focus:ring-amber-500 focus:border-amber-500 bg-gray-50 p-2">
                </div>

                <div class="flex gap-2">
                    <button type="submit" class="bg-amber-500 text-white px-4 py-2 rounded-lg hover:bg-amber-600 text-sm font-bold flex-1 shadow-md transition">
                        Pesquisar
                    </button>
                    <a href="{{ route('auditoria.index') }}" class="bg-gray-100 text-gray-500 px-3 py-2 rounded-lg hover:bg-gray-200 text-sm border border-gray-300 transition text-center flex items-center justify-center">
                        Limpar
                    </a>
                </div>
            </form>
        </div>

        <div class="bg-white shadow-lg rounded-xl overflow-hidden border border-gray-200">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="p-4 text-xs font-bold text-gray-600 uppercase">Servidor Auditado</th>
                            <th class="p-4 text-xs font-bold text-gray-600 uppercase text-center">Data</th>
                            <th class="p-4 text-xs font-bold text-gray-600 uppercase text-center">Conformidade</th>
                            <th class="p-4 text-xs font-bold text-gray-600 uppercase text-center">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($feedbacks as $feedback)
                            <tr class="hover:bg-amber-50/30 transition">
                                <td class="p-4">
                                    <div class="font-bold text-gray-800">{{ $feedback->servidor->servidor_nome }}</div>
                                    <div class="text-[10px] text-amber-600 font-bold uppercase tracking-tight">
                                        {{ $feedback->servidor->orgao->orgao_nome ?? 'Órgão não vinculado' }}
                                    </div>
                                </td>
                                <td class="p-4 text-center text-gray-500 text-sm">
                                    {{ $feedback->created_at->format('d/m/Y') }}
                                </td>
                                <td class="p-4 text-center">
                                    <span class="inline-block px-3 py-1 rounded-full text-xs font-black shadow-sm
                                        {{ $feedback->nota_final >= 80 ? 'bg-green-100 text-green-700 border border-green-200' : 
                                           ($feedback->nota_final >= 50 ? 'bg-yellow-100 text-yellow-700 border border-yellow-200' : 'bg-red-100 text-red-700 border border-red-200') }}">
                                        {{ number_format($feedback->nota_final, 1) }}%
                                    </span>
                                </td>
                                <td class="p-4 text-center">
                                    <a href="{{ route('auditoria.show', $feedback->id) }}" class="inline-flex items-center gap-1 bg-white text-gray-700 border border-gray-300 px-3 py-1.5 rounded-lg text-xs font-bold hover:bg-amber-500 hover:text-white hover:border-amber-500 transition">
                                        Visualizar
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="p-16 text-center text-gray-400">
                                    <p class="text-4xl mb-4">🔍</p>
                                    Nenhuma auditoria encontrada para os filtros aplicados.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-6 px-2">
            {{ $feedbacks->links() }}
        </div>
        
    </div>
</body>
</html>