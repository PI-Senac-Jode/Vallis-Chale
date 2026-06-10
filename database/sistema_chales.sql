-- Cria o banco principal usado pelo arquivo config.php.
CREATE DATABASE IF NOT EXISTS sistema_chales;
USE sistema_chales;

-- Tabela de categorias usadas para classificar os chales.
CREATE TABLE categorias_chale (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    descricao TEXT
);

-- Tabela de clientes. O CPF e usado como chave primaria.
CREATE TABLE cliente (
    cpf VARCHAR(14) PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE
);

-- Tabela de chales exibidos no site e administrados no painel.
-- categoria_id cria o relacionamento com categorias_chale.
CREATE TABLE chale (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    descricao TEXT,
    imagem_url VARCHAR(255),
    preco_diaria DECIMAL(10,2) NOT NULL,
    datas_disponiveis JSON,
    disponibilidade BOOLEAN DEFAULT TRUE,
    categoria_id INT,
    FOREIGN KEY (categoria_id) REFERENCES categorias_chale(id)
);

-- Tabela de reservas feitas pelos clientes.
-- id_chale liga a reserva ao chale e id_cliente liga ao CPF do cliente.
CREATE TABLE reserva (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_chale INT NOT NULL,
    id_cliente VARCHAR(14) NOT NULL,
    data_inicio DATE NOT NULL,
    data_fim DATE NOT NULL,
    status VARCHAR(50),
    FOREIGN KEY (id_chale) REFERENCES chale(id),
    FOREIGN KEY (id_cliente) REFERENCES cliente(cpf) 
);
