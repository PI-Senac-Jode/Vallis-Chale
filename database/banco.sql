-- 1. CRIANDO O BANCO
CREATE DATABASE IF NOT EXISTS sistema_chales;
USE sistema_chales;

-- 2. TABELA DE CATEGORIAS
CREATE TABLE categorias_chale (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    descricao TEXT NOT NULL
);

-- 3. TABELA DE CLIENTE (Corrigida)
CREATE TABLE cliente (
    cpf VARCHAR(14) PRIMARY KEY NOT NULL UNIQUE,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE
);

-- 4. TABELA DE CHALÉ
CREATE TABLE chale (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    descricao TEXT NOT NULL,
    preco_diaria DECIMAL(10, 2) NOT NULL,
    datas_disponiveis JSON, -- Armazena arrays de datas ou metadados
    disponibilidade BOOLEAN DEFAULT TRUE,
    categoria_id INT,
    FOREIGN KEY (categoria_id) REFERENCES categorias_chale(id)
);

-- 5. TABELA DE RESERVA
CREATE TABLE reserva (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_chale INT NOT NULL,
    id_cliente VARCHAR(14) NOT NULL,
    data_inicio DATE NOT NULL,
    data_fim DATE NOT NULL,
    status VARCHAR(50) DEFAULT 'Pendente',
    FOREIGN KEY (id_chale) REFERENCES chale(id),
    FOREIGN KEY (id_cliente) REFERENCES cliente(cpf)
);

-- 6. TABELA DE PAGAMENTO
CREATE TABLE pagamento (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_reserva INT NOT NULL UNIQUE,
    valor DECIMAL(10, 2) NOT NULL,
    data_pagamento DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_reserva) REFERENCES reserva(id)
);

-- ==========================================
-- POPULANDO AS TABELAS (5 REGISTROS CADA)
-- ==========================================

INSERT INTO categorias_chale (nome, descricao) VALUES 
('Luxo', 'Suítes com hidromassagem e vista para a montanha'),
('Standard', 'Conforto básico para casais'),
('Família', 'Amplo espaço com 3 quartos e cozinha'),
('Rústico', 'Cabanas de madeira com lareira central'),
('Premium', 'Piscina privativa e serviço de quarto 24h');

INSERT INTO cliente (cpf, nome, email) VALUES 
('111.111.111-11', 'Ana Silva', 'ana.silva@email.com'),
('222.222.222-22', 'Bruno Souza', 'bruno.s@email.com'),
('333.333.333-33', 'Carla Mendes', 'carla.m@email.com'),
('444.444.444-44', 'Diego Lima', 'diego.l@email.com'),
('555.555.555-55', 'Elena Rosa', 'elena.r@email.com');

INSERT INTO chale (nome, descricao, preco_diaria, categoria_id) VALUES 
('Chalé Alpino', 'Estilo suíço com deck de madeira', 450.00, 1),
('Refúgio do Sol', 'Perto da trilha principal', 250.00, 2),
('Casarão Verde', 'Ideal para grupos de até 8 pessoas', 750.00, 3),
('Cabana Tronco', 'Experiência imersiva na floresta', 320.00, 4),
('Suíte Imperial', 'O melhor do nosso resort', 1200.00, 5);

INSERT INTO reserva (id_chale, id_cliente, data_inicio, data_fim, status) VALUES 
(1, '111.111.111-11', '2026-05-10', '2026-05-15', 'Confirmada'),
(2, '222.222.222-22', '2026-06-01', '2026-06-03', 'Pendente'),
(3, '333.333.333-33', '2026-05-20', '2026-05-25', 'Confirmada'),
(4, '444.444.444-44', '2026-07-12', '2026-07-15', 'Cancelada'),
(5, '555.555.555-55', '2026-08-05', '2026-08-10', 'Confirmada');

INSERT INTO pagamento (id_reserva, valor) VALUES 
(1, 2250.00),
(3, 3750.00),
(5, 6000.00),
(2, 500.00),
(4, 0.00); -- Reserva cancelada ou estornada