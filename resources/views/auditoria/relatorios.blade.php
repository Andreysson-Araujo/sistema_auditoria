<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Relatórios Gerenciais</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-8">
    <div class="max-w-6xl mx-auto">
        <h1 class="text-3xl font-bold text-gray-800 mb-8">📊 Relatórios e Indicadores</h1>

        <form action="{{ route('auditoria.relatorios') }}" method="GET" class="bg-white p-6 rounded-xl shadow-sm mb-8 grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase">Central</label>
                <select name="central_id" class="w-full border-gray-300 rounded-lg p-2 text-sm">
                    <option value="">Todas as Centrais</option>
                    @foreach($centrals as $c)
                        <option value="{{ $c->id }}" {{ request('central_id') == $c->id ? 'selected' : '' }}>{{ $c->central_nome }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase">Início</label>
                <input type="date" name="data_inicio" value="{{ request('data_inicio') }}" class="w-full border-gray-300 rounded-lg p-2 text-sm">
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase">Fim</label>
                <input type="date" name="data_fim" value="{{ request('data_fim') }}" class="w-full border-gray-300 rounded-lg p-2 text-sm">
            </div>
            <button type="submit" class="bg-blue-600 text-white p-2 rounded-lg font-bold hover:bg-blue-700 transition">Gerar Relatório</button>
        </form>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white p-6 rounded-xl border-l-8 border-blue-500 shadow-sm">
                <p class="text-sm text-gray-500 font-bold uppercase">Total de Auditorias</p>
                <p class="text-3xl font-black text-gray-800">{{ $totalAuditorias }}</p>
            </div>
            <div class="bg-white p-6 rounded-xl border-l-8 border-green-500 shadow-sm">
                <p class="text-sm text-gray-500 font-bold uppercase">Média de Conformidade</p>
                <p class="text-3xl font-black text-gray-800">{{ number_format($mediaGeral, 1) }}%</p>
            </div>
            <div class="bg-white p-6 rounded-xl border-l-8 border-amber-500 shadow-sm">
                <p class="text-sm text-gray-500 font-bold uppercase">Órgãos Auditados</p>
                <p class="text-3xl font-black text-gray-800">{{ $porOrgao->count() }}</p>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm overflow-hidden border border-gray-200">
            <table class="w-full text-left">
                <thead class="bg-gray-50 border-b">
                    <tr>
                        <th class="p-4 text-xs font-bold text-gray-600 uppercase">Órgão</th>
                        <th class="p-4 text-xs font-bold text-gray-600 uppercase text-center">Qtd. Auditorias</th>
                        <th class="p-4 text-xs font-bold text-gray-600 uppercase">Progresso Médio</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($porOrgao as $nomeOrgao => $dados)
                    <tr>
                        <td class="p-4 font-bold text-gray-700">{{ $nomeOrgao ?: 'Não Identificado' }}</td>
                        <td class="p-4 text-center text-gray-600">{{ $dados['quantidade'] }}</td>
                        <td class="p-4 w-1/3">
                            <div class="flex items-center gap-3">
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $dados['media'] }}%"></div>
                                </div>
                                <span class="text-sm font-bold text-gray-700">{{ number_format($dados['media'], 1) }}%</span>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>