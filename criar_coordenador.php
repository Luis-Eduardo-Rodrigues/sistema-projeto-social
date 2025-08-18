<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="icon" href="src/projeto.png" type="image/png">
  <title>Cadastrar Coordenador</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 min-h-screen flex items-center justify-center p-4">
  <main class="w-full max-w-3xl bg-white shadow-lg rounded-2xl p-6">
    <h1 class="text-2xl font-bold mb-4">Cadastrar Coordenador</h1>

    <form method="POST" action="salvar_coordenador.php" class="grid grid-cols-1 md:grid-cols-2 gap-4">

      <div class="md:col-span-2">
        <label class="block text-sm font-medium mb-1" for="nome_usuario">Nome Completo</label>
        <input type="text" name="nome_usuario" id="nome_usuario" required
          class="w-full border rounded-xl p-2.5 focus:outline-none focus:ring-2">
      </div>

      <div>
        <label class="block text-sm font-medium mb-1" for="senha">Senha</label>
        <input type="password" name="senha" id="senha" required
          class="w-full border rounded-xl p-2.5 focus:outline-none focus:ring-2">
      </div>

      <div>
        <label class="block text-sm font-medium mb-1" for="cargo">Cargo</label>
        <input type="text" name="cargo" id="cargo" value="Coordenador" readonly
          class="w-full border rounded-xl p-2.5 bg-gray-100 text-gray-700">
      </div>


      <div class="md:col-span-2">
        <label class="block text-sm font-medium mb-1" for="nome_escola">Nome da escola</label>
        <select id="nome_escola" name="nome_escola" required
          class="w-full border rounded-xl p-2.5 focus:outline-none focus:ring-2">
          <option value="">Selecione a escola</option>
          <?php
          include "conn.php";

          $sql = "SELECT * FROM escola ORDER BY nome_escola ASC";
          $escolas = mysqli_query($mysqli, $sql);

          if ($escolas) {
            foreach ($escolas as $escola) {
              echo "<option value='" . $escola['nome_escola'] . "'>" . $escola['nome_escola'] . "</option>";
            }
          }
          ?>
        </select>
      </div>

      <div>
        <label class="block text-sm font-medium mb-1" for="cpf">CPF</label>
        <input type="text" name="cpf" id="cpf" maxlength="11" required
          class="w-full border rounded-xl p-2.5 focus:outline-none focus:ring-2">
      </div>

      <div>
        <label class="block text-sm font-medium mb-1" for="usuario">Usuário</label>
        <input type="text" name="usuario" id="usuario" required
          class="w-full border rounded-xl p-2.5 focus:outline-none focus:ring-2">
      </div>

      <div class="md:col-span-2 flex gap-3 mt-4">
        <button type="submit"
          class="px-5 py-2.5 rounded-2xl bg-blue-600 text-white font-semibold hover:bg-blue-700">Salvar</button>
        <button type="reset" class="px-5 py-2.5 rounded-2xl bg-gray-200 text-gray-800 hover:bg-gray-300">Limpar</button>
      </div>

    </form>
  </main>
</body>

</html>