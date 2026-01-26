<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Responder Auditoria</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        input[type="radio"]:checked + span { background-color: #2563eb; color: white; border-color: #2563eb; }
        .radio-sim:checked + span { background-color: #10b981 !important; border-color: #10b981 !important; }
        .radio-nao:checked + span { background-color: #ef4444 !important; border-color: #ef4444 !important; }
    </style>
</head>
<body class="bg-gray-50 min-h-screen p-4 md:p-10">

    <div class="max-w-4xl mx-auto">
        <div class="mb-8 text-center">
            <h1 class="text-3xl font-bold text-gray-800">Formulário de Auditoria</h1>
            <p class="text-gray-600 mt-2">Responda as questões abaixo de acordo com sua rotina.</p>
        </div>

        <form action="{{ route('auditoria.salvar') }}" method="POST" class="space-y-6">
            @csrf

            @foreach($perguntas as $index => $pergunta)
                <div class="bg-white shadow-sm border border-gray-200 rounded-xl p-6 transition hover:shadow-md">
                    <div class="flex items-start gap-4">
                        <span class="bg-blue-100 text-blue-700 font-bold px-3 py-1 rounded-full text-sm">
                            {{ $index + 1 }}
                        </span>
                        
                        <div class="flex-1">
                            <h2 class="text-lg font-medium text-gray-800 mb-4">{{ $pergunta->texto_pergunta }}</h2>

                            @if($pergunta->tipo === 'nota')
                                <div class="grid grid-cols-5 gap-2 md:gap-4">
                                    @foreach(range(1, 5) as $nota)
                                        <label class="cursor-pointer">
                                            <input type="radio" name="respostas[{{ $pergunta->id }}]" value="{{ $nota }}" required class="hidden">
                                            <span class="block text-center py-3 border-2 border-gray-200 rounded-lg font-bold text-gray-500 hover:border-blue-300 transition-all">{{ $nota }}</span>
                                        </label>
                                    @endforeach
                                </div>

                            @elseif($pergunta->tipo === 'sim_não' || $pergunta->tipo === 'sim_nao')
                                <div class="flex gap-4">
                                    <label class="flex-1 cursor-pointer">
                                        <input type="radio" name="respostas[{{ $pergunta->id }}]" value="Sim" required class="hidden radio-sim">
                                        <span class="block text-center py-3 border-2 border-gray-200 rounded-lg font-bold text-gray-500 hover:border-green-300 transition-all">Sim</span>
                                    </label>
                                    <label class="flex-1 cursor-pointer">
                                        <input type="radio" name="respostas[{{ $pergunta->id }}]" value="Não" required class="hidden radio-nao">
                                        <span class="block text-center py-3 border-2 border-gray-200 rounded-lg font-bold text-gray-500 hover:border-red-300 transition-all">Não</span>
                                    </label>
                                </div>

                            @elseif($pergunta->tipo === 'texto')
                                <textarea name="respostas[{{ $pergunta->id }}]" rows="3" required 
                                    class="w-full px-4 py-2 border-2 border-gray-200 rounded-lg focus:border-blue-500 outline-none transition-all"
                                    placeholder="Descreva sua resposta..."></textarea>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach

            <div class="flex justify-center pt-6 pb-12">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-4 px-12 rounded-full shadow-lg transition transform hover:scale-105">
                    Enviar Auditoria
                </button>
            </div>
        </form>
    </div>
</body>
</html>