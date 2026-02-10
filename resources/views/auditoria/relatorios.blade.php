<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relatório Geral de Auditoria</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/imask/6.4.2/imask.min.js"></script>
</head>
<body class="bg-gray-50 p-4 md:p-8">
    <div class="max-w-5xl mx-auto bg-white p-6 md:p-10 shadow-sm rounded-sm relative border border-gray-200">
        
        <div class="flex justify-between items-center mb-8 border-b pb-4">
            <a href="/admin" class="flex items-center gap-2 text-sm font-bold text-gray-600 hover:text-amber-600 transition">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                </svg>
                Voltar para o Dashboard
            </a>
            <span class="text-[10px] text-gray-400 uppercase tracking-widest font-black">Módulo de Inteligência Gerencial</span>
        </div>

        <h1 class="text-3xl text-center text-gray-800 mb-10 font-light uppercase tracking-tighter">Relatório Geral de Auditoria</h1>

        <form action="{{ route('auditoria.relatorios') }}" method="GET" class="space-y-10">
            
            <div>
                <h2 class="text-center font-bold text-lg mb-4 uppercase tracking-tight text-gray-700">Período de Referência</h2>
                <div class="flex flex-col md:flex-row gap-4 justify-center items-center">
                    <div class="text-center">
                        <label class="block text-xs font-bold mb-1 uppercase text-gray-500">Início:</label>
                        <input type="text" name="data_inicio" id="data_inicio"
                               placeholder="dd/mm/aaaa"
                               value="{{ request('data_inicio') ? request('data_inicio') : (\Carbon\Carbon::parse($dataInicio ?? now()->startOfMonth())->format('d/m/Y')) }}" 
                               class="border border-gray-300 p-2 rounded w-64 text-center focus:ring-2 focus:ring-amber-500 outline-none shadow-sm font-mono">
                    </div>
                    <div class="text-center">
                        <label class="block text-xs font-bold mb-1 uppercase text-gray-500">Fim:</label>
                        <input type="text" name="data_fim" id="data_fim"
                               placeholder="dd/mm/aaaa"
                               value="{{ request('data_fim') ? request('data_fim') : (\Carbon\Carbon::parse($dataFim ?? now())->format('d/m/Y')) }}" 
                               class="border border-gray-300 p-2 rounded w-64 text-center focus:ring-2 focus:ring-amber-500 outline-none shadow-sm font-mono">
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8 border-t pt-8">
                <div class="text-center">
                    <h2 class="font-bold text-md mb-3 uppercase text-gray-700">Filtrar por Central:</h2>
                    <select name="central_id" class="border border-gray-300 p-3 rounded w-full bg-gray-50 focus:ring-2 focus:ring-amber-500 outline-none cursor-pointer text-sm">
                        <option value="">Todas as Centrais</option>
                        @foreach($centrals as $central)
                            <option value="{{ $central->id }}" {{ request('central_id') == $central->id ? 'selected' : '' }}>
                                {{ $central->central_nome }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="text-center">
                    <h2 class="font-bold text-md mb-3 uppercase text-gray-700">Filtrar por Órgão:</h2>
                    <select name="orgao_id" class="border border-gray-300 p-3 rounded w-full bg-gray-50 focus:ring-2 focus:ring-amber-500 outline-none cursor-pointer text-sm">
                        <option value="">Todos os Órgãos</option>
                        @foreach($orgaos as $orgao)
                            <option value="{{ $orgao->id }}" {{ request('orgao_id') == $orgao->id ? 'selected' : '' }}>
                                {{ $orgao->orgao_nome }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="py-6 border-t border-b border-gray-100 bg-gray-50/50 rounded-lg text-gray-500 text-center space-y-3">
                <p class="text-xs font-black uppercase text-gray-400 mb-2 tracking-widest">Opções de visualização de dados</p>
                <div class="flex flex-wrap justify-center gap-6 text-[11px] font-bold italic uppercase">
                    <span class="text-amber-600">● Agrupamento por Órgão</span>
                    <span class="text-amber-600">● Cálculo de Conformidade</span>
                    <span class="text-amber-600">● Quantitativo de Feedbacks</span>
                </div>
            </div>

            <div class="flex flex-col md:flex-row justify-center gap-4">
                <button type="submit" name="action" value="exportar" class="bg-green-600 text-white px-10 py-3 rounded hover:bg-green-700 font-bold uppercase text-xs shadow-md transition-all active:scale-95 flex items-center justify-center gap-2">
                    📥 Gerar planilha CSV
                </button>
                <button type="submit" name="action" value="visualizar" class="bg-gray-800 text-white px-10 py-3 rounded hover:bg-black font-bold uppercase text-xs shadow-md transition-all active:scale-95 flex items-center justify-center gap-2">
                    👁️ Visualizar na Tela
                </button>
            </div>
        </form>

        @if(isset($feedbacks) && isset($dadosAgrupados))
            <div class="mt-16 animate-fadeIn">
                <div class="bg-gray-200 p-3 text-center font-bold text-[11px] uppercase tracking-widest text-gray-700 border border-gray-300 border-b-0">
                    Resumo Geral de Conformidade por Unidade/Órgão
                </div>
                <table class="w-full border-collapse border border-gray-300 shadow-sm">
                    <thead class="bg-gray-50 text-[10px] uppercase font-black text-gray-600">
                        <tr>
                            <th class="border border-gray-300 p-4 text-left w-1/2">Órgão / Unidade</th>
                            <th class="border border-gray-300 p-4 text-center">Auditorias Realizadas</th>
                            <th class="border border-gray-300 p-4 text-center">Média de Nota</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm">
                        @forelse($dadosAgrupados as $unidade => $itens)
                        <tr class="hover:bg-amber-50/50 transition border-b border-gray-200">
                            <td class="border-r border-gray-300 p-4 font-bold text-gray-800">{{ $unidade }}</td>
                            <td class="border-r border-gray-300 p-4 text-center text-gray-600">{{ $itens->count() }}</td>
                            <td class="p-4 text-center">
                                <span class="px-3 py-1 rounded-full font-black text-xs {{ $itens->avg('nota_final') >= 80 ? 'bg-green-100 text-green-700' : 'bg-amber-100 text-amber-700' }}">
                                    {{ number_format($itens->avg('nota_final'), 1, ',', '.') }}%
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="p-12 text-center text-gray-400 italic font-light bg-gray-50">
                                Nenhum registro encontrado para os filtros selecionados.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                    @if($feedbacks->count() > 0)
                    <tfoot class="bg-gray-100 font-black text-gray-900 uppercase text-[11px]">
                        <tr>
                            <td class="border border-gray-300 p-4">TOTALIZADOR GERAL</td>
                            <td class="border border-gray-300 p-4 text-center">{{ $feedbacks->count() }}</td>
                            <td class="border border-gray-300 p-4 text-center text-lg text-amber-800">
                                {{ number_format($feedbacks->avg('nota_final'), 1, ',', '.') }}%
                            </td>
                        </tr>
                    </tfoot>
                    @endif
                </table>
                
                <div class="mt-6 flex justify-between items-center text-[10px] text-gray-400 uppercase tracking-widest font-bold italic">
                    <span>Sistema de Qualidade</span>
                    <span>Gerado em: {{ now()->format('d/m/Y H:i') }}</span>
                </div>
            </div>
        @endif
    </div>

    <script>
        const maskOptions = {
            mask: '00/00/0000'
        };

        IMask(document.getElementById('data_inicio'), maskOptions);
        IMask(document.getElementById('data_fim'), maskOptions);
    </script>

    <style>
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fadeIn { animation: fadeIn 0.6s ease-out forwards; }
    </style>
</body>
</html>