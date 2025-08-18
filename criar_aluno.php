<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link rel="icon" href="src/projeto.png" type="image/png">
  <title>Cadastrar Aluno</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center p-4">
  <main class="w-full max-w-3xl bg-white shadow-lg rounded-2xl p-6">
    <h1 class="text-2xl font-bold mb-1">Cadastrar Aluno</h1>

    <div id="msg" class="hidden mb-4 p-3 rounded-lg"></div>

    <form id="formAluno" class="grid grid-cols-1 md:grid-cols-2 gap-4" method="POST" action="salvar_aluno.php" novalidate>

      <div class="md:col-span-2">
        <label class="block text-sm font-medium mb-1" for="nome_aluno">Nome do aluno</label>
        <input id="nome_aluno" name="nome_aluno" type="text" maxlength="50" required class="w-full border rounded-xl p-2.5 focus:outline-none focus:ring-2">
      </div>

      <div>
        <label class="block text-sm font-medium mb-1" for="cpf_aluno">CPF (somente números)</label>
        <input id="cpf_aluno" name="cpf_aluno" type="text" inputmode="numeric" pattern="\d{11}" maxlength="11" required class="w-full border rounded-xl p-2.5 focus:outline-none focus:ring-2">
        <p class="text-xs text-gray-500 mt-1">11 dígitos, sem pontos e traço.</p>
      </div>

      <div>
        <label class="block text-sm font-medium mb-1" for="codigo_tecnico">Código Técnico</label>
        <input id="codigo_tecnico" name="codigo_tecnico" type="text" pattern="\d{6,10}" minlength="6" maxlength="10" required class="w-full border rounded-xl p-2.5 focus:outline-none focus:ring-2">
        <p class="text-xs text-gray-500 mt-1">Entre 6 e 10 dígitos, somente números.</p>
      </div>

      <div class="md:col-span-2">
        <label class="block text-sm font-medium mb-1" for="nome_escola">Nome da escola</label>
        <select id="nome_escola" name="nome_escola" required class="w-full border rounded-xl p-2.5 focus:outline-none focus:ring-2">
          <option value="">Selecione a escola</option>
          <?php
            include "conn.php";

            $sql = "SELECT * FROM escola ORDER BY nome_escola ASC";
            $escolas = mysqli_query($mysqli, $sql);

            if($escolas){
              foreach($escolas as $escola){
                echo "<option value='".$escola['nome_escola']."'>".$escola['nome_escola']."</option>";
              }
            }
          ?>
        </select>
      </div>

      <div class="md:col-span-2">
        <label class="block text-sm font-medium mb-1" for="ano">Ano</label>
        <input id="ano" name="ano" type="text" pattern="\d{4}" maxlength="4" required class="w-full border rounded-xl p-2.5 focus:outline-none focus:ring-2">
      </div>

      <div class="md:col-span-2 flex items-center gap-3 mt-2">
        <button type="submit" class="px-5 py-2.5 rounded-2xl bg-blue-600 text-white font-semibold hover:bg-blue-700">Salvar</button>
        <button type="reset" class="px-5 py-2.5 rounded-2xl bg-gray-200 text-gray-800 hover:bg-gray-300">Limpar</button>
      </div>
    </form>
  </main>
</body>
</html>
