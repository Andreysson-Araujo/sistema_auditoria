<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Respostas Pendentes</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 p-8">
    <div class="max-w-6xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Servidores Aguardando Auditoria</h1>
            <a href="{{ route('auditoria.index') }}" class="text-sm bg-gray-200 px-4 py-2 rounded hover:bg-gray-300">Ver Histórico (Feedbacks)</a>
        </div>

        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <table class="w-full text-left border-collapse">
                <thead class="bg-blue-600 text-white">
                    <tr>
                        <th class="p-4">Servidor</th>
                        <th class="p-4">Órgão</th>
                        <th class="p-4">Total de Respostas</th>
                        <th class="p-4 text-center">Ação</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($servidores as $servidor)
                        <tr class="border-b hover:bg-gray-50 transition">
                            <td class="p-4 font-semibold">{{ $servidor->servidor_nome }}</td>
                            <td class="p-4 text-gray-600">{{ $servidor->orgao->orgao_nome ?? 'N/A' }}</td>
                            <td class="p-4 text-gray-500">{{ $servidor->respostas_count }} questões respondidas</td>
                            <td class="p-4 text-center">
                                <a href="{{ route('auditoria.show_respostas', $servidor->id) }}" 
                                   class="bg-green-500 text-white px-4 py-2 rounded text-sm hover:bg-green-600">
                                    Realizar Auditoria
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="p-8 text-center text-gray-500">Nenhum servidor com respostas pendentes.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>