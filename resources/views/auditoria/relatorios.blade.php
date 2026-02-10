<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relatório Geral de Auditoria</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 p-4 md:p-8">
    <div class="max-w-5xl mx-auto bg-white p-6 md:p-10 shadow-sm rounded-sm border border-gray-200">
        
        <div class="flex justify-between items-center mb-8 border-b pb-4">
            <a href="/admin" class="flex items-center gap-2 text-sm font-bold text-gray-600 hover:text-amber-600 transition">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                </svg>
                Voltar para o Dashboard
            </a>
            <span class="text-[10px] text-gray-400 uppercase tracking-widest font-black">Módulo de Auditoria</span>
        </div>

        <h1 class="text-3xl text-center text-gray-800 mb-10 font-light uppercase tracking-tighter">Relatório de Desempenho</h1>

        <form action="{{ route('auditoria.relatorios') }}" method="GET" class="space-y-10">
            
            <div>
                <h2 class="text-center font-bold text-lg mb-4 uppercase text-gray-700">Selecione o Período</h2>
                <div class="flex flex-col md:flex-row gap-4 justify-center items-center">
                    <div class="text-center">
                        <label class="block text-xs font-bold mb-1 uppercase text-gray-500">Data Inicial:</label>
                        <input type="date" name="data_inicio" 
                               value="{{ $dataInicio }}" 
                               class="border border-gray-300 p-2 rounded w-64 text-center focus:ring-2 focus:ring-amber-500 outline-none shadow-sm cursor-pointer">
                    </div>
                    <div class="text-center">
                        <label class="block text-xs font-bold mb-1 uppercase text-gray-500">Data Final:</label>
                        <input type="date" name="data_fim" 
                               value="{{ $dataFim }}" 
                               class="border border-gray-300 p-2 rounded w-64 text-center focus:ring-2 focus:ring-amber-500 outline-none shadow-sm cursor-pointer">
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 border-t pt-8">
                <div class="text-center">
                    <h2 class="font-bold text-sm mb-3 uppercase text-gray-600">Central:</h2>
                    <select name="central_id" class="border border-gray-300 p-3 rounded w-full bg-gray-50 text-sm outline-none focus:ring-2 focus:ring-amber-500">
                        <option value="">Todas as Centrais</option>
                        @foreach($centrals as $central)
                            <option value="{{ $central->id }}" {{ request('central_id') == $central->id ? 'selected' : '' }}>
                                {{ $central->central_nome }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="text-center">
                    <h2 class="font-bold text-sm mb-3 uppercase text-gray-600">Órgão:</h2>
                    <select name="orgao_id" class="border border-gray-300 p-3 rounded w-full bg-gray-50 text-sm outline-none focus:ring-2 focus:ring-amber-500">
                        <option value="">Todos os Órgãos</option>
                        @foreach($orgaos as $orgao)
                            <option value="{{ $orgao->id }}" {{ request('orgao_id') == $orgao->id ? 'selected' : '' }}>
                                {{ $orgao->orgao_nome }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="flex flex-col md:flex-row justify-center gap-4 border-t pt-8">
                <button type="submit" name="action" value="visualizar" class="bg-gray-800 text-white px-10 py-3 rounded hover:bg-black font-bold uppercase text-xs transition shadow-md">
                    Visualizar Dados
                </button>
                <button type="submit" name="action" value="exportar" class="bg-green-600 text-white px-10 py-3 rounded hover:bg-green-700 font-bold uppercase text-xs transition shadow-md">
                    Exportar CSV
                </button>
            </div>
        </form>

        @if(isset($feedbacks) && isset($dadosAgrupados))
            <div class="mt-16 animate-fadeIn">
                <div class="bg-gray-100 p-3 text-center font-bold text-[10px] uppercase tracking-widest text-gray-600 border border-gray-300 border-b-0">
                    Resultado consolidado da auditoria
                </div>
                <table class="w-full border-collapse border border-gray-300 shadow-sm">
                    <thead class="bg-gray-50 text-[10px] uppercase font-black text-gray-500">
                        <tr>
                            <th class="border border-gray-300 p-4 text-left">Órgão</th>
                            <th class="border border-gray-300 p-4 text-center">Qtd Auditorias</th>
                            <th class="border border-gray-300 p-4 text-center">Conformidade</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm">
                        @forelse($dadosAgrupados as $unidade => $itens)
                        <tr class="hover:bg-amber-50/30 transition border-b">
                            <td class="border-r border-gray-300 p-4 font-bold text-gray-800">{{ $unidade }}</td>
                            <td class="border-r border-gray-300 p-4 text-center text-gray-600">{{ $itens->count() }}</td>
                            <td class="p-4 text-center">
                                <span class="px-3 py-1 rounded-full font-bold text-[11px] {{ $itens->avg('nota_final') >= 80 ? 'bg-green-100 text-green-700' : 'bg-amber-100 text-amber-700' }}">
                                    {{ number_format($itens->avg('nota_final'), 1, ',', '.') }}%
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="3" class="p-10 text-center text-gray-400 italic font-light">Nenhum dado encontrado para o período.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    <style>
        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
        .animate-fadeIn { animation: fadeIn 0.5s ease-out forwards; }
    </style>
</body>
</html>