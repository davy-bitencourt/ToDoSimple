# Restaurante MVC simples (PHP + JSON)

Projeto desenvolvido  na aula de Gerência de Configuração. Sem uso de banco de dados SQL, utilizando arquivos JSON para armazenamento.

O cliente pode montar um carrinho, ajustar quantidades e finalizar o pedido com um único checkout.

## Como rodar

1) Criar arquivos JSON iniciais (execute no terminal):
   php scripts/setup.php

2) Subir o servidor embutido do PHP:
   php -S localhost:8000 -t public

3) Acessar:
- Cliente: http://localhost:8000/
- Funcionário/Admin: http://localhost:8000/?r=func/entrar

Credenciais (email e senha):
- admin@adm / adm  (administrador)
- user@local / 123456  (usuário comum)
