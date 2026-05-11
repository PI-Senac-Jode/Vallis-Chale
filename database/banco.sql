CREATE DATABASE sistema_chales;
USE sistema_chales;

 

-- =========================================
-- TABELA DE CATEGORIAS DE CHALÉ
-- =========================================
CREATE TABLE categorias_chale (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    descricao TEXT
);

 

-- TABELA DE CLIENTE
-- =========================================
CREATE TABLE cliente (
    cpf VARCHAR(14) PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE
);

 

-- =========================================
-- TABELA DE CHALÉ
-- =========================================
CREATE TABLE chale (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    descricao TEXT,
    preco_diaria DECIMAL(10,2) NOT NULL,
    datas_disponiveis JSON,
    disponibilidade BOOLEAN DEFAULT TRUE,
    categoria_id INT,
    FOREIGN KEY (categoria_id) REFERENCES categorias_chale(id)
);

 

-- =========================================
-- TABELA DE RESERVA
-- =========================================
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

 
