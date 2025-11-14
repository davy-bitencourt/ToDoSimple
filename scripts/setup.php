<?php
// Atenção !!!
// Para criar os arquivos JSON e popular dados iniciais, execute no terminal
// "php scripts/setup.php"

require_once __DIR__ . '/../app/models/Armazenamento.php';

// (Verifica se os arquivos JSON existem na pasta storage. Caso não existam, cria arquivos vazios.)
if (!file_exists(__DIR__ . '/../storage/pratos.json')) {
  // (Cria um arquivo "pratos.json" com uma lista vazia)
  Armazenamento::salvar('pratos', []);
}
if (!file_exists(__DIR__ . '/../storage/pedidos.json')) {
  Armazenamento::salvar('pedidos', []);
}
if (!file_exists(__DIR__ . '/../storage/funcionarios.json')) {
  Armazenamento::salvar('funcionarios', []);
}

// popular json de funcionários
$funcionarios = Armazenamento::carregar('funcionarios');
if (count($funcionarios) === 0) {
  $funcionarios[] = [
    'id' => 1,
    'nome' => 'Admin',
    'email' => 'adm@adm',
    'senha_hash' => password_hash('adm', PASSWORD_DEFAULT),
    'is_admin' => 1, // (1 indica que é admin)
  ];
  $funcionarios[] = [
    'id' => 2,
    'nome' => 'Usuário',
    'email' => 'user@local',
    'senha_hash' => password_hash('123456', PASSWORD_DEFAULT),
    'is_admin' => 0, // (0 indica que não é admin)
  ];
  
  //Salva a lista atualizada em um arquivo
  Armazenamento::salvar('funcionarios', $funcionarios);
}

// popular json de pratos
$pratos = Armazenamento::carregar('pratos');
if (count($pratos) === 0) {
  $pratos[] = [
    'id' => 1,
    'nome' => 'Spaghetti da Casa',
    'preco' => 39.90,
    'descricao' => 'Spaghetti italiano envolvido em um molho de tomate feito na cozinha, com alho suavemente dourado, azeite extra virgem, parmesão recém-ralado e folhas de manjericão fresco. (Porção de 400 g)'
  ];
  $pratos[] = [
    'id' => 2,
    'nome' => 'Parmegiana da Casa',
    'preco' => 49.90,
    'descricao' => 'Bife à parmegiana com arroz e fritas.'
  ];
  $pratos[] = [
    'id' => 3,
    'nome' => 'Salada da Casa',
    'preco' => 29.90,
    'descricao' => 'Alface, croutons, parmesão e molho Caesar.'
  ];

  //Salva os pratos em um arquivo
  Armazenamento::salvar('pratos', $pratos);
}

echo "Arquivos JSON prontos em /storage\n";