# Controle-Financeiro

Documentação do Sistema de Controle Financeiro Familiar
1. Visão Geral do Sistema
Este sistema de controle financeiro familiar foi desenvolvido para ajudar os usuários a gerenciar suas finanças pessoais, com foco no controle de entradas (receitas) e saídas (despesas), além de proporcionar uma visão geral das contas a pagar e do saldo disponível.

Funcionalidades Principais:
Login e Registro: Permite aos usuários criarem contas e fazerem login no sistema.
Dashboard: Exibe o resumo financeiro do usuário, com gráficos, saldo atual, total de entradas e saídas.
Lançamento de Entradas: Formulário para adicionar novas fontes de receita (ex. salário, freelance).
Lançamento de Saídas: Formulário para adicionar despesas e contas a pagar (ex. contas de serviços, compras, etc).
Histórico de Lançamentos: Lista todos os lançamentos (entradas e saídas), com possibilidade de editar e excluir.
Contas a Pagar: Registra as contas com data de vencimento, valor e status (pendente/pago).
Edição e Exclusão de Lançamentos: Permite ao usuário editar ou excluir lançamentos de entradas, saídas e contas a pagar.
2. Estrutura do Banco de Dados
Tabelas:
2.1 Usuários
Tabela para armazenar informações sobre os usuários cadastrados no sistema.

sql
Copiar código
CREATE TABLE usuarios (
    id_usuario INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    senha VARCHAR(255) NOT NULL,
    data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
2.2 Entradas
Tabela para armazenar as entradas financeiras (receitas) do usuário.

sql
Copiar código
CREATE TABLE entradas (
    id_entrada INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT,
    valor DECIMAL(10, 2),
    data DATE,
    categoria VARCHAR(100),
    descricao TEXT,
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario)
);
2.3 Saídas
Tabela para armazenar as saídas financeiras (despesas) do usuário.

sql
Copiar código
CREATE TABLE saidas (
    id_saida INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT,
    valor DECIMAL(10, 2),
    data DATE,
    categoria VARCHAR(100),
    descricao TEXT,
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario)
);
2.4 Contas a Pagar
Tabela para armazenar as contas a pagar do usuário, com status de pagamento.

sql
Copiar código
CREATE TABLE contas_pagar (
    id_conta INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT,
    descricao VARCHAR(255),
    data_vencimento DATE,
    valor DECIMAL(10, 2),
    status VARCHAR(20) DEFAULT 'Pendente',
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario)
);
3. Arquitetura do Sistema
O sistema é composto por três camadas principais:

3.1 Camada de Apresentação (Frontend)
HTML: Estrutura das páginas.
CSS: Estilos para deixar o sistema visualmente agradável e responsivo.
JavaScript: Funcionalidades interativas como confirmação de ações (ex: exclusão de conta) e submissão de formulários.
3.2 Camada de Lógica (Backend)
PHP: Utilizado para processar as solicitações do usuário, como adicionar/editar/excluir dados, além de interagir com o banco de dados.
SQL: Linguagem usada para consultas ao banco de dados (ex: adicionar, editar e excluir registros).
3.3 Banco de Dados
MySQL: Utilizado para armazenar as informações do sistema, como usuários, entradas, saídas e contas a pagar.
4. Fluxo de Uso
4.1 Login e Registro
O usuário acessa a página de login.
Caso não tenha uma conta, ele pode criar uma na página de registro.
Ao fazer login, o usuário é redirecionado para o Dashboard, onde verá um resumo financeiro.
4.2 Lançamento de Entradas e Saídas
O usuário pode adicionar entradas (salário, freelance) e saídas (despesas, compras) nas respectivas páginas.
O sistema armazena as informações no banco de dados, associando-as ao usuário.
4.3 Contas a Pagar
O usuário pode registrar as contas a pagar, incluindo data de vencimento, valor e status.
O status da conta pode ser atualizado para "Pago" quando o pagamento for realizado.
4.4 Edição e Exclusão de Lançamentos
O usuário pode editar ou excluir entradas, saídas e contas a pagar no histórico.
Ao editar, ele altera os dados da transação no banco de dados.
Ao excluir, a transação é removida permanentemente do banco de dados.
5. Como Instalar e Configurar
5.1 Requisitos
Servidor Web (Apache ou Nginx)
PHP 7.4 ou superior
Banco de dados MySQL
Navegador Web
5.2 Passos para Instalação
Clone o Repositório (se aplicável):

Se o código estiver em um repositório Git, faça o clone para o servidor.
Configuração do Banco de Dados:

Crie o banco de dados novosp58_contas.
Execute os comandos SQL para criar as tabelas (as que mostramos acima).
Configuração de Conexão ao Banco de Dados:

No arquivo db_connection.php, substitua as credenciais do banco de dados (host, username, password, dbname) pelas suas próprias configurações.
Configuração do Servidor:

Configure seu servidor web para apontar para a pasta onde o código do sistema está localizado.
Acessar o Sistema:

Abra o navegador e vá até o endereço configurado para o sistema (ex: http://localhost/controle-financeiro).
6. Instruções de Uso
6.1 Login e Registro
Na página de login, o usuário pode inserir seu email e senha para acessar sua conta.
Se o usuário não tiver uma conta, ele pode clicar no link de registro e preencher as informações necessárias (nome, email, senha).
6.2 Dashboard
O Dashboard exibe um resumo das entradas e saídas do usuário, incluindo gráficos de distribuição financeira.
O saldo atual, total de entradas e saídas também é mostrado na tela.
6.3 Lançamento de Entradas e Saídas
O usuário pode acessar as páginas Lançamento de Entradas e Lançamento de Saídas para adicionar novos registros financeiros.
Para cada lançamento, o usuário deve informar o valor, data, categoria e uma descrição opcional.
6.4 Contas a Pagar
O usuário pode registrar suas contas a pagar, com data de vencimento, valor e status.
Ao pagar a conta, o status pode ser alterado para Pago.
6.5 Histórico
O usuário pode visualizar todas as entradas e saídas no Histórico, com a possibilidade de editar ou excluir os registros.
7. Considerações Finais
Este sistema foi desenvolvido para ser simples, intuitivo e eficaz no controle das finanças pessoais. No entanto, existem várias melhorias que podem ser feitas, como adicionar funcionalidades de orçamento, integração com APIs bancárias e até mesmo a possibilidade de gerar relatórios e gráficos mais avançados.

Caso o sistema cresça, pode ser necessário implementar mais recursos de segurança, como a autenticação em duas etapas (2FA), e recursos de backup para garantir que os dados dos usuários estejam sempre seguros.

Apêndice
8.1 Tecnologias Utilizadas
Frontend: HTML, CSS, JavaScript.
Backend: PHP.
Banco de Dados: MySQL.
Servidor Web: Apache ou Nginx.
