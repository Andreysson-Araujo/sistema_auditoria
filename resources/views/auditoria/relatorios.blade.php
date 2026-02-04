<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Relatório Geral de Auditoria</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 p-6">
    <div class="max-w-5xl mx-auto bg-white p-8 shadow-sm rounded-sm">
        <div class="max-w-5xl mx-auto bg-white p-8 shadow-sm rounded-sm relative">
    
    <div class="flex justify-between items-center mb-6 border-b pb-4">
        <a href="/admin" class="flex items-center gap-2 text-sm font-bold text-gray-600 hover:text-amber-600 transition">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
            </svg>
            Voltar para o Dashboard
        </a>
        <span class="text-xs text-gray-400 uppercase tracking-widest font-semibold">Módulo de Inteligência</span>
    </div>

    <h1 class="text-2xl text-center text-gray-800 mb-8 font-light uppercase tracking-tighter">Relatório Geral de Auditoria</h1>

        <form action="{{ route('auditoria.relatorios') }}" method="GET">
            <div class="mb-8">
                <h2 class="text-center font-bold text-lg mb-4">Período</h2>
                <div class="flex gap-4 justify-center">
                    <div class="text-center">
                        <label class="block text-xs font-bold mb-1">Início:</label>
                        <input type="date" name="data_inicio" value="{{ request('data_inicio', '2021-01-01') }}" class="border p-2 rounded w-64">
                    </div>
                    <div class="text-center">
                        <label class="block text-xs font-bold mb-1">Fim:</label>
                        <input type="date" name="data_fim" value="{{ request('data_fim', date('Y-m-d')) }}" class="border p-2 rounded w-64">
                    </div>
                </div>
            </div>

            <div class="mb-8 text-center">
                <h2 class="text-center font-bold text-lg mb-4">Escolha uma Central:</h2>
                <select name="central_id" class="border p-2 rounded w-full max-w-2xl">
                    <option value="">Todas as Centrais</option>
                    @foreach($centrals as $central)
                        <option value="{{ $central->id }}" {{ request('central_id') == $central->id ? 'selected' : '' }}>
                            {{ $central->central_nome }}
                        </option>
                    @endforeach
                </select>
            </div>

            <hr class="my-8">

            <div class="flex justify-center gap-4">
                <button type="submit" name="action" value="exportar" class="bg-green-600 text-white px-6 py-2 rounded hover:bg-green-700 font-bold uppercase text-sm">
                    Gerar planilha
                </button>
                <button type="submit" name="action" value="visualizar" class="bg-green-600 text-white px-6 py-2 rounded hover:bg-green-700 font-bold uppercase text-sm">
                    Visualizar planilha
                </button>
            </div>
        </form>

        @if(isset($feedbacks))
            <div class="mt-12">
                <div class="bg-gray-300 p-2 text-center font-bold text-sm uppercase">
                    Dados totais referentes aos feedbacks por unidade durante o período
                </div>
                <table class="w-full border-collapse border">
                    <thead>
                        <tr class="bg-white">
                            <th class="border p-2">Unidade (Órgão)</th>
                            <th class="border p-2 text-center">Qtd. Auditorias</th>
                            <th class="border p-2 text-center">Média Conformidade</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($dadosAgrupados as $orgao => $itens)
                        <tr>
                            <td class="border p-2 font-bold">{{ $orgao }}</td>
                            <td class="border p-2 text-center">{{ $itens->count() }}</td>
                            <td class="border p-2 text-center">{{ number_format($itens->avg('nota_final'), 1) }}%</td>
                        </tr>
                        @endforeach
                        <tr class="bg-gray-100 font-bold">
                            <td class="border p-2">Total Geral</td>
                            <td class="border p-2 text-center">{{ $feedbacks->count() }}</td>
                            <td class="border p-2 text-center">{{ number_format($feedbacks->avg('nota_final'), 1) }}%</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            
        @endif
    </div>
</body>
</html>