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
            <a href="/admin" class="text-sm bg-gray-200 px-4 py-2 rounded hover:bg-gray-300">Voltar ao Painel</a>
            <a href="{{ route('auditoria.pendentes') }}" class="text-sm bg-gray-200 px-4 py-2 rounded hover:bg-gray-300">Ver Respostas</a>
        </div>

        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <table class="w-full text-left border-collapse">
                <thead class="bg-amber-500 text-white">
                    <tr>
                        <th class="p-4">ID</th>
                        <th class="p-4">Servidor Auditado</th>
                        <th class="p-4">Auditor (Admin)</th>
                        <th class="p-4">Data</th>
                        <th class="p-4 text-center">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($feedbacks as $feedback)
                        <tr class="border-b hover:bg-gray-50 transition">
                            <td class="p-4 font-mono text-sm">#{{ $feedback->id }}</td>
                            <td class="p-4 font-semibold">{{ $feedback->servidor->servidor_nome }}</td>
                            <td class="p-4 text-gray-600">{{ $feedback->user->name }}</td>
                            <td class="p-4 text-gray-500">{{ $feedback->created_at->format('d/m/Y H:i') }}</td>
                            <td class="p-4 text-center">
                                <button class="bg-blue-500 text-white px-3 py-1 rounded text-sm hover:bg-blue-600">
                                    Ver Respostas
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="p-8 text-center text-gray-500">Nenhuma auditoria realizada ainda.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>