-- Criar o banco 'cadastros_adianti'
-- CREATE SCHEMA `cadastros_adianti` DEFAULT CHARACTER SET utf8mb4 ;

-- Criar a Tabela
CREATE TABLE IF NOT EXISTS usuarios (
    id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    nome_usuario VARCHAR(100) NOT NULL UNIQUE,
    senha VARCHAR(32) NOT NULL
) DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS clientes (
    id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    nome_cliente VARCHAR(150) NOT NULL UNIQUE,
    telefone VARCHAR(11) NOT NULL, -- 048 9 9662 1023
    cep INT(8) NOT NULL
) DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS produtos (
    id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    nome_produto VARCHAR(150) NOT NULL,
	valor DECIMAL(10,2)
) DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS vendas (
    id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    quantidade INT(5) NOT NULL,
	total DECIMAL(10,2) NOT NULL,
    data_venda DATE NOT NULL,
    cliente_id INT UNSIGNED NOT NULL,
    produto_id INT UNSIGNED NOT NULL,
    FOREIGN KEY (cliente_id) REFERENCES clientes (id),
    FOREIGN KEY (produto_id) REFERENCES produtos (id)
) DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- INSERTs
-- Usuario
INSERT INTO usuarios (nome_usuario, senha) VALUES ('master', 'master00');
INSERT INTO usuarios (nome_usuario, senha) VALUES ('usuario1', '12345678');
INSERT INTO usuarios (nome_usuario, senha) VALUES ('usuario2', '87654321');
INSERT INTO usuarios (nome_usuario, senha) VALUES ('usuario3', '14785236');
INSERT INTO usuarios (nome_usuario, senha) VALUES ('usuario4', '63258741');
INSERT INTO usuarios (nome_usuario, senha) VALUES ('teste', '123456');
-- Atualizar as senha para deixá criptografadas
UPDATE usuarios SET senha = md5(senha) WHERE id in(1, 2, 3, 4, 5);
UPDATE usuarios SET senha = md5('123456') WHERE id in(8);

-- Cliente
INSERT INTO clientes (nome_cliente, telefone, cep) VALUES ('José Nunes', '48999999999', 88801500);
INSERT INTO clientes (nome_cliente, telefone, cep) VALUES ('Maria Lopes', '48988888888', 88801014);
INSERT INTO clientes (nome_cliente, telefone, cep) VALUES ('Jarbas da Silva', '48977777777', 88801030);
INSERT INTO clientes (nome_cliente, telefone, cep) VALUES ('Ludes Maria', '48922223333', 88802050);
INSERT INTO clientes (nome_cliente, telefone, cep) VALUES ('Carlos Souza', '48922224444', 88801445);

-- Produto
INSERT INTO produtos (nome_produto, valor) VALUES ('Caneca branca sem estampa', 20.00);
INSERT INTO produtos (nome_produto, valor) VALUES ('Boné branco sem estampa', 40.00);
INSERT INTO produtos (nome_produto, valor) VALUES ('Camiseta preta sem estampa', 50.00);
INSERT INTO produtos (nome_produto, valor) VALUES ('Camiseta branca sem estampa', 50.00);
INSERT INTO produtos (nome_produto, valor) VALUES ('Caneca dourada sem estampa', 30.00);

-- Venda
INSERT INTO vendas (quantidade, total, data_venda, cliente_id, produto_id) VALUES ( 1, 40.00, '2024-03-01', 2, 2);
INSERT INTO vendas (quantidade, total, data_venda, cliente_id, produto_id) VALUES (2, 80.00, '2024-03-08', 3, 2);
INSERT INTO vendas (quantidade, total, data_venda, cliente_id, produto_id) VALUES (1, 20.00, '2024-03-08', 3, 1);
INSERT INTO vendas (quantidade, total, data_venda, cliente_id, produto_id) VALUES (3, 150.00, '2024-03-09', 1, 3);
INSERT INTO vendas (quantidade, total, data_venda, cliente_id, produto_id) VALUES (4, 120.00, '2024-03-15', 5, 5);

-- SELECT
SELECT * FROM usuarios;
SELECT * FROM clientes;
SELECT * FROM produtos;
SELECT * FROM vendas;

-- Consultar a senha com criptografia usando o md5() nativo no MySQL
SELECT md5(senha) as 'Senha Criptografada' FROM usuarios WHERE id in(1, 2, 3, 4, 5);

-- INNER não vai trazer os registros dos clientes que não tiver vendas
SELECT ven.cliente_id as 'Tabela Vendas', cli.id as 'Tabela cliente', cli.nome_cliente as 'Tabela nomecliente'
FROM clientes cli
INNER JOIN vendas ven ON ven.cliente_id = cli.id
WHERE cli.id = 7;
--
SELECT ven.produto_id as 'Tabela Vendas', pro.id as 'Tabela Produto', pro.nome_produto as 'Tabela nomeproduto'
FROM produtos pro
INNER JOIN vendas ven ON ven.produto_id = pro.id
WHERE pro.id = 2;
--
SELECT ven.id, cli.nome_cliente, pro.nome_produto, ven.quantidade, ven.total, ven.data_venda 
FROM vendas ven
INNER JOIN clientes cli ON cli.id = ven.cliente_id
INNER JOIN produtos pro ON pro.id = ven.produto_id
WHERE ven.id = 1;

-- Data formatada
SELECT ven.id, cli.nome_cliente, pro.nome_produto, ven.quantidade, ven.total, DATE_FORMAT(ven.data_venda , '%d/%m/%Y') AS 'Data Formatada'
FROM vendas ven
INNER JOIN clientes cli ON cli.id = ven.cliente_id
INNER JOIN produtos pro ON pro.id = ven.produto_id
WHERE ven.id = 1;

SELECT ven.id, cli.nome_cliente, pro.nome_produto, ven.quantidade, ven.total, DATE_FORMAT(ven.data_venda , '%d/%m/%Y') AS 'Data Formatada'
FROM vendas ven
INNER JOIN clientes cli ON cli.id = ven.cliente_id
INNER JOIN produtos pro ON pro.id = ven.produto_id;


-- Excluir Tabelas
/*
DROP TABLE IF EXISTS vendas;
DROP TABLE IF EXISTS usuarios;
DROP TABLE IF EXISTS produtos;
DROP TABLE IF EXISTS clientes;
*/
