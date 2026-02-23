<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalhes das Respostas - {{ $servidor->servidor_nome }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            .no-print { display: none; }
            body { background: white; padding: 0; }
            .shadow-md { shadow: none; border: 1px solid #ddd; }
        }
    </style>
</head>

<body class="bg-gray-100 p-4 md:p-8">
    <div class="max-w-4xl mx-auto">

        <div class="bg-white rounded-t-lg shadow-md p-6 border-b-4 border-blue-500">
            <div class="flex justify-between items-start">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">{{ $servidor->servidor_nome }}</h1>
                    <p class="text-gray-500 uppercase text-sm font-semibold">
                        {{ $servidor->orgao->orgao_nome ?? 'Órgão não informado' }}
                    </p>
                </div>
                <a href="{{ route('auditoria.pendentes') }}"
                    class="no-print text-blue-600 hover:underline text-sm font-medium">
                    ← Voltar para Pendentes
                </a>
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
            <h2 class="text-lg font-bold text-gray-700 mb-6 border-l-4 border-blue-500 pl-2">Respostas Enviadas pelo Servidor</h2>

            <div class="space-y-6">
                @forelse($servidor->respostas as $index => $resposta)
                    <div class="border-b pb-6 last:border-0">
                        <p class="text-gray-800 font-medium mb-3">
                            <span class="text-blue-500 mr-2">{{ $index + 1 }}.</span>
                            {{ $resposta->pergunta->texto_pergunta ?? 'Pergunta não encontrada' }}
                        </p>

                        <div class="flex flex-col gap-2">
                            @if (is_numeric($resposta->valor))
                                @php
                                    $labels = [
                                        1 => 'Insatisfeito',
                                        2 => 'Pouco Satisfeito',
                                        3 => 'Satisfeito',
                                        4 => 'Muito Satisfeito'
                                    ];
                                @endphp
                                <div class="flex items-center gap-4">
                                    <div class="flex gap-1">
                                        @for ($i = 1; $i <= 4; $i++)
                                            <span class="w-8 h-8 flex items-center justify-center rounded-full text-xs font-bold {{ $resposta->valor >= $i ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-400' }}">
                                                {{ $i }}
                                            </span>
                                        @endfor
                                    </div>
                                    <span class="text-sm font-bold text-blue-700 bg-blue-50 px-3 py-1 rounded-md border border-blue-100">
                                        {{ $labels[$resposta->valor] ?? 'N/A' }}
                                    </span>
                                </div>
                            @else
                                <div class="bg-gray-50 p-4 rounded-lg w-full border-l-2 border-gray-300 italic text-gray-700">
                                    "{{ $resposta->valor }}"
                                </div>
                            @endif
                        </div>
                    </div>
                @empty
                    <p class="text-center text-gray-500 py-10 italic">Nenhuma resposta encontrada.</p>
                @endforelse
            </div>

            <div class="mt-10 pt-6 border-t no-print">
                <form action="{{ route('auditoria.finalizar', $servidor->id) }}" method="POST">
                    @csrf

                    <div class="mb-8">
                        <label class="block text-sm font-bold text-gray-700 mb-4 uppercase tracking-wider">
                            Validação do Auditor (Ajuste de Impacto):
                        </label>
                        <div class="grid grid-cols-3 md:grid-cols-9 gap-1.5">
                            @foreach ([
                                -20 => ['label' => '-20%', 'color' => 'peer-checked:bg-red-800', 'desc' => 'Gravíss.'],
                                -15 => ['label' => '-15%', 'color' => 'peer-checked:bg-red-700', 'desc' => 'Grave'],
                                -10 => ['label' => '-10%', 'color' => 'peer-checked:bg-red-600', 'desc' => 'Crítico'],
                                -5  => ['label' => '-5%',  'color' => 'peer-checked:bg-orange-500', 'desc' => 'Atenção'],
                                0   => ['label' => '0%',   'color' => 'peer-checked:bg-blue-600', 'desc' => 'Neutro'],
                                5   => ['label' => '+5%',  'color' => 'peer-checked:bg-green-500', 'desc' => 'Bom'],
                                10  => ['label' => '+10%', 'color' => 'peer-checked:bg-green-600', 'desc' => 'Ótimo'],
                                15  => ['label' => '+15%', 'color' => 'peer-checked:bg-green-700', 'desc' => 'Superior'],
                                20  => ['label' => '+20%', 'color' => 'peer-checked:bg-emerald-600', 'desc' => 'Excelência'],
                            ] as $valor => $info)
                                <label class="cursor-pointer group">
                                    <input type="radio" name="ajuste_auditor" value="{{ $valor }}" class="hidden peer" {{ $valor == 0 ? 'checked' : '' }}>
                                    <div class="flex flex-col items-center py-2 px-1 border border-gray-200 rounded-lg bg-gray-50 transition-all hover:bg-gray-100 peer-checked:border-transparent {{ $info['color'] }} peer-checked:text-white shadow-sm text-center">
                                        <span class="text-xs md:text-sm font-black">{{ $info['label'] }}</span>
                                        <span class="text-[7px] md:text-[8px] uppercase font-bold opacity-80 leading-none mt-1">{{ $info['desc'] }}</span>
                                    </div>
                                </label>
                            @endforeach
                        </div>
                        <p class="text-xs text-gray-500 mt-3 italic">
                            * Use este ajuste para bonificar ou penalizar o desempenho com base nos comentários.
                        </p>
                    </div>

                    <div class="mb-6">
                        <label for="comentario" class="block text-sm font-bold text-gray-700 mb-2">
                            Parecer Final do Auditor:
                        </label>
                        <textarea name="comentario" id="comentario" rows="4" required
                            placeholder="Descreva aqui os pontos observados durante esta auditoria..."
                            class="w-full p-4 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none shadow-sm transition text-gray-600"></textarea>
                    </div>

                    <div class="flex justify-end items-center gap-4">
                        <button type="button" onclick="window.print()"
                            class="px-6 py-3 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition font-medium">
                            🖨️ Imprimir Detalhes
                        </button>

                        <button type="submit"
                            class="px-8 py-3 bg-green-600 text-white rounded-lg font-bold hover:bg-green-700 transition shadow-lg transform active:scale-95">
                            ✅ Finalizar Auditoria
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>