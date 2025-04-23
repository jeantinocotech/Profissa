<!-- resources/views/welcome.blade.php -->
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>CareerConnect - Conectando Conselheiros e Buscadores de Carreira</title>
    @vite('resources/css/app.css')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
</head>
<body class="antialiased bg-gray-50">
    <!-- Cabeçalho -->
    <header class="bg-white shadow">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <div class="text-xl font-bold text-indigo-600">
                        CareerConnect
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    @if (Route::has('login'))
                        <div>
                            @auth
                                <a href="{{ url('/dashboard') }}" class="text-indigo-600 hover:text-indigo-800">Dashboard</a>
                            @else
                                <a href="{{ route('login') }}" class="px-4 py-2 rounded-md text-white bg-indigo-600 hover:bg-indigo-700">Entrar</a>
                                
                                @if (Route::has('register'))
                                    <a href="{{ route('register') }}" class="ml-4 px-4 py-2 rounded-md border border-indigo-600 text-indigo-600 hover:bg-indigo-50">Cadastrar</a>
                                @endif
                            @endauth
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </header>

    <!-- Banner Principal -->
    <section class="relative bg-indigo-700">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
            <div class="lg:flex lg:items-center lg:justify-between">
                <div class="lg:w-1/2">
                    <h1 class="text-4xl font-extrabold tracking-tight text-white sm:text-5xl md:text-6xl">
                        Conectando carreiras e futuros
                    </h1>
                    <p class="mt-6 text-xl text-indigo-100">
                        Encontre orientação especializada ou compartilhe sua experiência profissional para ajudar outros a trilharem novos caminhos.
                    </p>
                    <div class="mt-10 flex space-x-4">
                        <a href="{{ route('register') }}" class="px-6 py-3 border border-transparent text-base font-medium rounded-md text-indigo-700 bg-white hover:bg-indigo-50">
                            Começar agora
                        </a>
                        <a href="#como-funciona" class="px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-indigo-800 hover:bg-indigo-900">
                            Saiba mais
                        </a>
                    </div>
                </div>
                <div class="mt-10 lg:mt-0 lg:w-1/2 lg:flex lg:justify-end">
                    <img class="h-auto w-full max-w-md rounded-lg shadow-xl" src="/api/placeholder/600/400" alt="Profissionais conversando">
                </div>
            </div>
        </div>
        <div class="absolute bottom-0 inset-x-0 h-1/2 bg-gray-50"></div>
    </section>

    <!-- Como Funciona -->
    <section id="como-funciona" class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h2 class="text-3xl font-extrabold text-gray-900 sm:text-4xl">
                    Como funciona?
                </h2>
                <p class="mt-4 max-w-2xl mx-auto text-xl text-gray-500">
                    Um processo simples para conectar aconselhamento e decisões de carreira.
                </p>
            </div>

            <div class="mt-16">
                <div class="grid grid-cols-1 gap-8 md:grid-cols-2">
                    <!-- Conselheiro de Carreira -->
                    <div class="bg-white overflow-hidden shadow rounded-lg">
                        <div class="px-6 py-8">
                            <div class="flex items-center mb-4">
                                <div class="bg-indigo-100 rounded-md p-3">
                                    <i class="fas fa-user-tie text-2xl text-indigo-600"></i>
                                </div>
                                <h3 class="ml-4 text-2xl font-bold text-gray-900">
                                    Conselheiro de Carreira
                                </h3>
                            </div>
                            <p class="text-gray-600">
                                Compartilhe sua experiência profissional e conhecimento para ajudar pessoas que desejam iniciar ou mudar de carreira.
                            </p>
                            <ul class="mt-6 space-y-4">
                                <li class="flex">
                                    <i class="fas fa-check-circle text-green-500 mt-1 mr-2"></i>
                                    <span>Crie um perfil destacando sua experiência</span>
                                </li>
                                <li class="flex">
                                    <i class="fas fa-check-circle text-green-500 mt-1 mr-2"></i>
                                    <span>Compartilhe insights sobre sua área de atuação</span>
                                </li>
                                <li class="flex">
                                    <i class="fas fa-check-circle text-green-500 mt-1 mr-2"></i>
                                    <span>Ajude pessoas a tomar decisões melhores</span>
                                </li>
                            </ul>
                            <div class="mt-8">
                                <a href="{{ route('register') }}" class="w-full flex items-center justify-center px-4 py-2 border border-transparent rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700">
                                    Torne-se um conselheiro
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Pessoa em Busca de Carreira -->
                    <div class="bg-white overflow-hidden shadow rounded-lg">
                        <div class="px-6 py-8">
                            <div class="flex items-center mb-4">
                                <div class="bg-indigo-100 rounded-md p-3">
                                    <i class="fas fa-search text-2xl text-indigo-600"></i>
                                </div>
                                <h3 class="ml-4 text-2xl font-bold text-gray-900">
                                    Buscador de Carreira
                                </h3>
                            </div>
                            <p class="text-gray-600">
                                Obtenha informações valiosas sobre diferentes carreiras para tomar decisões mais informadas sobre seu futuro profissional.
                            </p>
                            <ul class="mt-6 space-y-4">
                                <li class="flex">
                                    <i class="fas fa-check-circle text-green-500 mt-1 mr-2"></i>
                                    <span>Explore diferentes áreas profissionais</span>
                                </li>
                                <li class="flex">
                                    <i class="fas fa-check-circle text-green-500 mt-1 mr-2"></i>
                                    <span>Conecte-se com profissionais experientes</span>
                                </li>
                                <li class="flex">
                                    <i class="fas fa-check-circle text-green-500 mt-1 mr-2"></i>
                                    <span>Receba conselhos personalizados para sua situação</span>
                                </li>
                            </ul>
                            <div class="mt-8">
                                <a href="{{ route('register') }}" class="w-full flex items-center justify-center px-4 py-2 border border-transparent rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700">
                                    Encontre orientação
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Depoimentos -->
    <section class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h2 class="text-3xl font-extrabold text-gray-900 sm:text-4xl">
                    O que estão dizendo
                </h2>
                <p class="mt-4 max-w-2xl mx-auto text-xl text-gray-500">
                    Experiências reais de pessoas que utilizaram nossa plataforma.
                </p>
            </div>

            <div class="mt-16 grid grid-cols-1 gap-8 md:grid-cols-3">
                <!-- Depoimento 1 -->
                <div class="bg-gray-50 rounded-lg p-6 shadow-sm">
                    <div class="flex items-center mb-4">
                        <div class="h-12 w-12 rounded-full bg-indigo-100 flex items-center justify-center">
                            <i class="fas fa-user text-indigo-600"></i>
                        </div>
                        <div class="ml-4">
                            <h4 class="text-lg font-medium text-gray-900">Ana Silva</h4>
                            <p class="text-gray-600">Desenvolvedora Web</p>
                        </div>
                    </div>
                    <p class="text-gray-600">
                        "Como conselheira, ajudar pessoas a encontrar seu caminho na tecnologia tem sido uma experiência incrível. É gratificante compartilhar meus conhecimentos."
                    </p>
                </div>

                <!-- Depoimento 2 -->
                <div class="bg-gray-50 rounded-lg p-6 shadow-sm">
                    <div class="flex items-center mb-4">
                        <div class="h-12 w-12 rounded-full bg-indigo-100 flex items-center justify-center">
                            <i class="fas fa-user text-indigo-600"></i>
                        </div>
                        <div class="ml-4">
                            <h4 class="text-lg font-medium text-gray-900">Pedro Santos</h4>
                            <p class="text-gray-600">Estudante</p>
                        </div>
                    </div>
                    <p class="text-gray-600">
                        "Graças aos conselhos que recebi, consegui clareza sobre qual graduação seguir. As mentoras me ajudaram a descobrir meus pontos fortes."
                    </p>
                </div>

                <!-- Depoimento 3 -->
                <div class="bg-gray-50 rounded-lg p-6 shadow-sm">
                    <div class="flex items-center mb-4">
                        <div class="h-12 w-12 rounded-full bg-indigo-100 flex items-center justify-center">
                            <i class="fas fa-user text-indigo-600"></i>
                        </div>
                        <div class="ml-4">
                            <h4 class="text-lg font-medium text-gray-900">Carla Oliveira</h4>
                            <p class="text-gray-600">Em transição de carreira</p>
                        </div>
                    </div>
                    <p class="text-gray-600">
                        "Estava insegura sobre mudar completamente de área aos 35 anos. O suporte que recebi me deu confiança para dar esse passo importante."
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Call to Action -->
    <section class="py-16 bg-indigo-700">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h2 class="text-3xl font-extrabold text-white sm:text-4xl">
                    Pronto para iniciar sua jornada?
                </h2>
                <p class="mt-4 max-w-2xl mx-auto text-xl text-indigo-100">
                    Cadastre-se hoje e comece a transformar sua carreira ou ajude outros a encontrarem seu caminho.
                </p>
                <div class="mt-8 flex justify-center">
                    <a href="{{ route('register') }}" class="px-6 py-3 border border-transparent text-base font-medium rounded-md text-indigo-700 bg-white hover:bg-indigo-50">
                        Criar minha conta
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Rodapé -->
    <footer class="bg-gray-800">
        <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div>
                    <h3 class="text-xl font-bold text-white">CareerConnect</h3>
                    <p class="mt-4 text-gray-300">
                        Conectando pessoas em busca de orientação profissional com conselheiros experientes.
                    </p>
                </div>
                <div>
                    <h3 class="text-lg font-medium text-white">Links</h3>
                    <ul class="mt-4 space-y-2">
                        <li><a href="#" class="text-gray-300 hover:text-white">Sobre nós</a></li>
                        <li><a href="#" class="text-gray-300 hover:text-white">Como funciona</a></li>
                        <li><a href="#" class="text-gray-300 hover:text-white">FAQ</a></li>
                        <li><a href="#" class="text-gray-300 hover:text-white">Contato</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="text a-lg font-medium text-white">Siga-nos</h3>
                    <div class="mt-4 flex space-x-6">
                        <a href="#" class="text-gray-300 hover:text-white">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="#" class="text-gray-300 hover:text-white">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="#" class="text-gray-300 hover:text-white">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <a href="#" class="text-gray-300 hover:text-white">
                            <i class="fab fa-linkedin-in"></i>
                        </a>
                    </div>
                </div>
            </div>
            <div class="mt-8 border-t border-gray-700 pt-8 flex justify-between">
                <p class="text-gray-300">&copy; 2025 CareerConnect. Todos os direitos reservados.</p>
                <div class="flex space-x-6">
                    <a href="#" class="text-gray-300 hover:text-white">Termos</a>
                    <a href="#" class="text-gray-300 hover:text-white">Privacidade</a>
                </div>
            </div>
        </div>
    </footer>
</body>
</html>