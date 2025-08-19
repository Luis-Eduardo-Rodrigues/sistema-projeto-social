<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tela Secretaria - Sistema de Controle</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.0/css/all.min.css" integrity="sha512-DxV+EoADOkOygM4IR9yXP8Sb2qwgidEmeqAEmDKIOfPRQZOWbXCzLC6vjbZyy0vPisbH2SyW27+ddLVCN+OMzQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            font-family: Poppins
        }

        .swiper-button-prev,
        .swiper-button-next {
            color: #279E5E;
        }
    </style>
</head>

<body class="bg-gray-100 text-gray-800">

    <header class="h-40 relative">
        <img src="./src/header.png" alt="Imagem Cabeçalho" class="w-full h-full object-cover">
        <a href="index.php" class="absolute top-11 right-4 transform -translate-y-1/2 text-[#2CA965] flex items-center space-x-2 cursor-pointer">
            <i class="fa-solid fa-right-to-bracket text-3xl"></i>
            <span class="flex flex-col leading-tight text-lg font-semibold">
                <span>Acessar o</span>
                <span>Sistema</span>
            </span>
        </a>
    </header>



    <!-- Carrossel -->
    <section class="container mx-auto px-4 py-8">
        <div class="swiper">
            <div class="swiper-wrapper">
                <!-- Slides -->
                <div class="swiper-slide">
                    <div class="bg-white shadow rounded-xl p-6 border border-gray-200 max-w-2xl mx-auto text-left">
                        <h2 class="text-2xl sm:text-3xl font-extrabold text-[#279E5E] mb-4 leading-snug">
                            Qual o objetivo do<br>Pé de Meia Crateús?
                        </h2>
                        <p class="text-base sm:text-lg text-neutral-700 mb-4">
                            Oferecer um <span class="font-semibold">incentivo financeiro educacional</span> aos estudantes do
                            <span class="font-semibold">9º ano do Ensino Fundamental</span> (Rede Municipal)
                            e dos <span class="font-semibold">1º, 2º e 3º anos do Ensino Médio</span> (Estadual e Federal) da rede pública.
                        </p>
                        <div class="rounded-lg bg-yellow-100 px-4 py-3">
                            <p class="text-yellow-800 font-extrabold text-sm uppercase tracking-wide mb-1">Meta</p>
                            <p class="text-neutral-800 text-sm sm:text-base">
                                Nossa maior meta é <span class="font-semibold">combater a evasão escolar</span>, ajudar os alunos a permanecerem na escola e fomentar a <span class="font-semibold">conclusão dos estudos</span>.
                            </p>
                        </div>

                    </div>
                </div>


                <div class="swiper-slide flex justify-center">
                    <div class="bg-white shadow rounded-xl p-6 border border-gray-200 max-w-2xl mx-auto text-left">
                        <h2 class="text-2xl sm:text-3xl font-extrabold text-[#279E5E] mb-4 leading-snug">
                            Como funciona o Programa<br>Pé de Meia Crateús?
                        </h2>
                        <p class="text-base sm:text-lg text-neutral-700 mb-4">
                            O benefício será depositado em forma de <span class="font-semibold">poupança educacional</span>,
                            direto em uma conta no <span class="font-semibold">Caixa Tem</span>, no nome do próprio estudante.
                        </p>
                        <div class="rounded-lg bg-yellow-100 px-4 py-3 inline-block">
                            <p class="text-yellow-800 font-extrabold text-sm uppercase tracking-wide mb-1">Valor do Benefício</p>
                            <p class="text-neutral-800 text-sm sm:text-base">
                                O valor de <span class="font-semibold">R$100 reais mensais</span> será depositado na conta
                                durante <span class="font-semibold">10 meses</span>.
                            </p>
                        </div>
                    </div>
                </div>


                <div class="swiper-slide">
                    <div class="bg-white shadow rounded-xl p-6 border border-gray-200 max-w-2xl mx-auto text-left">
                        <h2 class="text-2xl sm:text-3xl font-extrabold text-[#279E5E] mb-4 leading-snug">
                            O que é o Programa<br>Pé de Meia Crateús?
                        </h2>
                        <p class="text-base sm:text-lg text-neutral-700 mb-4">
                            Esse é um programa de <span class="font-semibold">bolsa estudantil</span>, criado com o objetivo de garantir que mais estudantes
                            concluam os estudos com apoio e incentivo.
                        </p>
                        <div class="rounded-lg bg-yellow-100 px-4 py-3">
                            <p class="text-yellow-800 font-extrabold text-sm uppercase tracking-wide mb-1">Base Legal</p>
                            <p class="text-neutral-800 text-sm sm:text-base">
                                Esse programa é regulamentado pela <span class="font-semibold">Lei Municipal nº 1.256/2025</span>,
                                que estabelece os critérios e diretrizes para a concessão da bolsa.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="swiper-slide">
                    <div class="bg-white shadow rounded-xl p-6 border border-gray-200 max-w-2xl mx-auto text-left">

                        <!-- Título -->
                        <h2 class="text-2xl sm:text-3xl font-extrabold text-[#279E5E] mb-4 leading-snug">
                            Quem pode participar?
                        </h2>

                        <!-- Texto de introdução -->
                        <p class="text-base sm:text-lg text-neutral-700 mb-6">
                            Para receber o benefício, o estudante deve:
                        </p>

                        <!-- Lista de requisitos -->
                        <ul class="space-y-3 text-neutral-800 text-sm sm:text-base mx-auto inline-block text-left">
                            <li class="flex items-center gap-2">
                                <span class="text-[#279E5E] font-bold">✔</span>
                                <span>Estar matriculado no <span class="font-semibold">9º ano do Ensino Fundamental</span>.</span>
                            </li>
                            <li class="flex items-center gap-2">
                                <span class="text-[#279E5E] font-bold">✔</span>
                                <span>Ser estudante de <span class="font-semibold">Crateús do Ensino Médio (1º, 2º ou 3º ano)</span> em escolas da rede pública.</span>
                            </li>
                            <li class="flex items-center gap-2">
                                <span class="text-[#279E5E] font-bold">✔</span>
                                <span>Manter frequência mínima de <span class="font-semibold">80%</span> nas aulas.</span>
                            </li>
                            <li class="flex items-center gap-2">
                                <span class="text-[#279E5E] font-bold">✔</span>
                                <span>Ter média anual igual ou superior a <span class="font-semibold">6,0</span>.</span>
                            </li>
                            <li class="flex items-center gap-2">
                                <span class="text-[#279E5E] font-bold">✔</span>
                                <span>Concluir o ano com aprovação.</span>
                            </li>
                        </ul>

                    </div>
                </div>


            </div>
            <div class="swiper-button-prev"></div>
            <div class="swiper-button-next"></div>
        </div>
    </section>

    <!-- SwiperJS -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script>
        const swipere = new Swiper('.swiper', {
            loop: true,
            autoplay: {
                delay: 12000,
                disableOnInteraction: false,
            },
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },
        });
    </script>


    <!-- Calendário -->
    <section class="container mx-auto px-4 py-8">
        <h2 class="text-2xl font-bold text-center text-[#279E5E] mb-6">CALENDÁRIO DE PAGAMENTOS – PÉ DE MEIA MUNICIPAL</h2>

        <div class="grid md:grid-cols-2 gap-8">
            <!-- Calendário em grade -->
            <div class="bg-white shadow rounded-xl p-6 border border-gray-200">
                <h3 id="calendar-title" class="text-lg font-semibold text-center mb-4"></h3>
                <div id="calendar-grid" class="grid grid-cols-7 gap-2 text-center text-sm"></div>
            </div>

            <!-- Datas -->
            <div class="bg-white shadow rounded-xl p-6 border border-gray-200">
                <h3 class="text-lg font-semibold text-[#279E5E]">1º semestre</h3>
                <ul class="list-disc list-inside text-sm mb-6">
                    <li>1ª parcela: 31/3 a 07/4/25</li>
                    <li>2ª parcela: 23 a 30/4/25</li>
                    <li>3ª parcela: 26/5 a 2/6/25</li>
                    <li>4ª parcela: 23 a 30/6/25</li>
                    <li>5ª parcela: 28/7 a 4/8/25</li>
                </ul>
                <h3 class="text-lg font-semibold text-[#279E5E]">2º semestre</h3>
                <ul class="list-disc list-inside text-sm">
                    <li>1ª parcela: 25/8 a 1º/9/25</li>
                    <li>2ª parcela: 29/9 a 6/10/25</li>
                    <li>3ª parcela: 27/10 a 3/11/25</li>
                    <li>4ª parcela: 24/11 a 2/12/25</li>
                </ul>
            </div>
        </div>
    </section>

    <footer class="w-full flex items-center py-2 justify-around mt-24 border-t-2 border-[#357950]">
        <div>
            <img src="./src/logo-caixa.png" class="w-40" alt="Logo da Caixa">
        </div>
        <div>
            <img src="./src/logo-gov.png" class="w-40" alt="Logo do Governo Federal">
        </div>
        <div>
            <img src="./src/logo-estado.png" class="w-40" alt="Logo do Estado">
        </div>
    </footer>

    <!-- SwiperJS -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script>
        const swiper = new Swiper('.swiper', {
            loop: true,
            pagination: {
                el: '.swiper-pagination',
                clickable: true
            },
        });

        // -------- Calendário Automático --------
        const mes = 8;
        const ano = 2025;
        const calendarTitle = document.getElementById("calendar-title");
        const calendarGrid = document.getElementById("calendar-grid");

        // Cabeçalho
        const weekDays = ["Seg", "Ter", "Qua", "Qui", "Sex", "Sab", "Dom"];
        weekDays.forEach(d => {
            const div = document.createElement("div");
            div.textContent = d;
            div.classList.add("font-bold");
            calendarGrid.appendChild(div);
        });

        function gerarCalendario(diasPagamento) {
            calendarTitle.textContent = new Date(ano, mes).toLocaleString("pt-BR", {
                month: "long",
                year: "numeric"
            });

            const primeiroDiaSemana = new Date(ano, mes, 1).getDay();
            const diasNoMes = new Date(ano, mes + 1, 0).getDate();
            const offset = (primeiroDiaSemana + 6) % 7;

            // espaços antes do primeiro dia
            for (let i = 0; i < offset; i++) {
                const vazio = document.createElement("div");
                calendarGrid.appendChild(vazio);
            }

            // dias do mês
            for (let d = 1; d <= diasNoMes; d++) {
                const dia = document.createElement("div");
                dia.textContent = d;
                dia.classList.add("p-2", "rounded");
                if (diasPagamento.includes(d)) {
                    dia.classList.add("bg-green-200", "font-bold");
                }
                calendarGrid.appendChild(dia);
            }
        }

        // Simulação de API
        async function carregarPagamentos() {
            // Aqui você pode trocar pelo seu endpoint real
            // Ex: const res = await fetch("/api/pagamentos?mes=9&ano=2025");
            // const data = await res.json();
            const data = {
                dias: [10, 20]
            }; // Fake API
            gerarCalendario(data.dias);
        }

        carregarPagamentos();
    </script>
</body>

</html>