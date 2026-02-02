<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Servidor - Auditoria</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center p-4">

    <div class="max-w-lg w-full bg-white shadow-xl rounded-2xl p-8 border border-gray-200">
        <div class="text-center mb-8">
            <h1 class="text-3xl font-extrabold text-gray-900">Identificação</h1>
            <p class="text-gray-500 mt-2">Olá! Por favor, identifique-se para iniciar o formulário de auditoria.</p>
        </div>

        @if(session('success'))
            <div class="mb-6 p-4 bg-green-100 border-l-4 border-green-500 text-green-700">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('servidores.store') }}" method="POST" class="space-y-5">
            @csrf

            <div>
                <label for="servidor_nome" class="block text-sm font-semibold text-gray-700 mb-1">Nome Completo</label>
                <input type="text" name="servidor_nome" id="servidor_nome" required 
                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition shadow-sm"
                    placeholder="Ex: João Silva Sauro">
            </div>

            <div>
                <label for="nivel_id" class="block text-sm font-semibold text-gray-700 mb-1">Seu Cargo / Nível</label>
                <select name="nivel_id" id="nivel_id" required 
                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none bg-white shadow-sm appearance-none">
                    <option value="">Selecione seu nível...</option>
                    @foreach($nivels as $nivel)
                        <option value="{{ $nivel->id }}">{{ $nivel->nivel_nome }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="central_id" class="block text-sm font-semibold text-gray-700 mb-1">Sua Central</label>
                <select name="central_id" id="central_id" required 
                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none bg-white shadow-sm appearance-none">
                    <option value="">Selecione a central...</option>
                    @foreach($centrals as $central)
                        <option value="{{ $central->id }}">{{ $central->central_nome }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="orgao_id" class="block text-sm font-semibold text-gray-700 mb-1">Seu Órgão / Secretaria</label>
                <select name="orgao_id" id="orgao_id" required 
                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none bg-white shadow-sm appearance-none">
                    <option value="">Selecione o órgão...</option>
                    @foreach($orgaos as $orgao)
                        <option value="{{ $orgao->id }}">{{ $orgao->orgao_nome }}</option>
                    @endforeach
                </select>
            </div>

            <div class="pt-4">
                <button type="submit" 
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-4 rounded-xl shadow-lg transform active:scale-95 transition-all duration-150">
                    Iniciar Questionario
                </button>
            </div>
            <div class="relative my-8">
            

        <div class="text-center">
            <a href="/admin/login" 
                class="inline-flex items-center text-sm font-semibold text-blue-600 hover:text-blue-800 transition-colors duration-200">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                </svg>
                Login
            </a>
        </div>
        </form>

        <div class="mt-8 text-center text-xs text-gray-400">
            Detin &copy; {{ date('Y') }}
        </div>
    </div>
    <script>
        console.log("Feito por Andreysson Araujo - Detin");
    </script>
</body>
</html>