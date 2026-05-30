
-- =========================================
-- INSERT CATEGORIAS (20 REGISTROS)
-- =========================================
INSERT INTO categorias_chale (nome, descricao) VALUES
('Luxo', 'Chalés de alto padrão'),
('Romântico', 'Ideal para casais'),
('Familiar', 'Espaço para famílias'),
('Montanha', 'Vista para montanhas'),
('Lago', 'Próximo ao lago'),
('Econômico', 'Baixo custo'),
('Premium', 'Experiência premium'),
('Rústico', 'Estilo rústico'),
('Piscina', 'Com piscina privativa'),
('Spa', 'Com hidromassagem e spa'),
('Pet Friendly', 'Aceita animais'),
('Aventura', 'Próximo a trilhas'),
('Praia', 'Próximo ao mar'),
('Natureza', 'Contato com natureza'),
('Executivo', 'Para viagens rápidas'),
('Temático', 'Decoração temática'),
('Colonial', 'Estilo colonial'),
('Moderno', 'Arquitetura moderna'),
('Minimalista', 'Design minimalista'),
('VIP', 'Serviços exclusivos');

 

-- =========================================
-- INSERT CLIENTES (20 REGISTROS)
-- =========================================
INSERT INTO cliente (cpf, nome, email) VALUES
('111.111.111-11', 'Ana Souza', 'ana@email.com'),
('222.222.222-22', 'Carlos Lima', 'carlos@email.com'),
('333.333.333-33', 'Fernanda Alves', 'fernanda@email.com'),
('444.444.444-44', 'Lucas Pereira', 'lucas@email.com'),
('555.555.555-55', 'Juliana Costa', 'juliana@email.com'),
('666.666.666-66', 'Marcos Silva', 'marcos@email.com'),
('777.777.777-77', 'Patricia Rocha', 'patricia@email.com'),
('888.888.888-88', 'Rafael Gomes', 'rafael@email.com'),
('999.999.999-99', 'Bianca Martins', 'bianca@email.com'),
('101.101.101-10', 'Gabriel Santos', 'gabriel@email.com'),
('202.202.202-20', 'Camila Ferreira', 'camila@email.com'),
('303.303.303-30', 'Thiago Oliveira', 'thiago@email.com'),
('404.404.404-40', 'Larissa Mendes', 'larissa@email.com'),
('505.505.505-50', 'Bruno Carvalho', 'bruno@email.com'),
('606.606.606-60', 'Vanessa Ribeiro', 'vanessa@email.com'),
('707.707.707-70', 'Eduardo Barbosa', 'eduardo@email.com'),
('808.808.808-80', 'Amanda Dias', 'amanda@email.com'),
('909.909.909-90', 'Ricardo Melo', 'ricardo@email.com'),
('121.121.121-12', 'Sofia Castro', 'sofia@email.com'),
('131.131.131-13', 'Daniel Nunes', 'daniel@email.com');

 

-- =========================================
-- INSERT CHALÉS (20 REGISTROS)
-- =========================================
INSERT INTO chale 
(nome, descricao, preco_diaria, datas_disponiveis, disponibilidade, categoria_id)
VALUES
('Chalé Aurora', 'Vista incrível', 450.00, '["2026-06-01","2026-06-15"]', TRUE, 1),
('Chalé Lua', 'Ambiente romântico', 380.00, '["2026-06-10"]', TRUE, 2),
('Chalé Família Feliz', 'Grande espaço', 600.00, '["2026-07-01"]', TRUE, 3),
('Chalé Montanha Azul', 'Vista montanhosa', 500.00, '["2026-08-01"]', TRUE, 4),
('Chalé Lago Verde', 'Beira do lago', 420.00, '["2026-09-01"]', TRUE, 5),
('Chalé Econômico 1', 'Baixo custo', 200.00, '["2026-06-20"]', TRUE, 6),
('Chalé Premium Gold', 'Luxo total', 900.00, '["2026-07-10"]', TRUE, 7),
('Chalé Rústico', 'Madeira e pedra', 350.00, '["2026-08-20"]', TRUE, 8),
('Chalé Piscina VIP', 'Piscina aquecida', 850.00, '["2026-09-15"]', TRUE, 9),
('Chalé Spa Relax', 'Com spa privativo', 780.00, '["2026-10-01"]', TRUE, 10),
('Chalé Pet Amigo', 'Aceita pets', 300.00, '["2026-06-25"]', TRUE, 11),
('Chalé Trilha', 'Perto das trilhas', 410.00, '["2026-07-18"]', TRUE, 12),
('Chalé Praia Sol', 'Vista para praia', 950.00, '["2026-08-12"]', TRUE, 13),
('Chalé Natureza Viva', 'Muito verde', 430.00, '["2026-09-30"]', TRUE, 14),
('Chalé Executivo', 'Prático e rápido', 390.00, '["2026-10-05"]', TRUE, 15),
('Chalé Medieval', 'Tema medieval', 550.00, '["2026-11-01"]', TRUE, 16),
('Chalé Colonial', 'Arquitetura clássica', 620.00, '["2026-12-01"]', TRUE, 17),
('Chalé Smart', 'Tecnologia moderna', 730.00, '["2026-12-15"]', TRUE, 18),
('Chalé Zen', 'Minimalista e clean', 480.00, '["2026-12-20"]', TRUE, 19),
('Chalé Diamond', 'Experiência VIP', 1200.00, '["2026-12-30"]', TRUE, 20);

 

-- =========================================
-- INSERT RESERVAS (20 REGISTROS)
-- =========================================
INSERT INTO reserva
(id_chale, id_cliente, data_inicio, data_fim, status)
VALUES
(1, '111.111.111-11', '2026-06-01', '2026-06-05', 'Confirmada'),
(2, '222.222.222-22', '2026-06-02', '2026-06-06', 'Pendente'),
(3, '333.333.333-33', '2026-06-03', '2026-06-07', 'Confirmada'),
(4, '444.444.444-44', '2026-06-04', '2026-06-08', 'Cancelada'),
(5, '555.555.555-55', '2026-06-05', '2026-06-09', 'Confirmada'),
(6, '666.666.666-66', '2026-06-06', '2026-06-10', 'Pendente'),
(7, '777.777.777-77', '2026-06-07', '2026-06-11', 'Confirmada'),
(8, '888.888.888-88', '2026-06-08', '2026-06-12', 'Confirmada'),
(9, '999.999.999-99', '2026-06-09', '2026-06-13', 'Cancelada'),
(10, '101.101.101-10', '2026-06-10', '2026-06-14', 'Confirmada'),
(11, '202.202.202-20', '2026-06-11', '2026-06-15', 'Pendente'),
(12, '303.303.303-30', '2026-06-12', '2026-06-16', 'Confirmada'),
(13, '404.404.404-40', '2026-06-13', '2026-06-17', 'Confirmada'),
(14, '505.505.505-50', '2026-06-14', '2026-06-18', 'Cancelada'),
(15, '606.606.606-60', '2026-06-15', '2026-06-19', 'Confirmada'),
(16, '707.707.707-70', '2026-06-16', '2026-06-20', 'Confirmada'),
(17, '808.808.808-80', '2026-06-17', '2026-06-21', 'Pendente'),
(18, '909.909.909-90', '2026-06-18', '2026-06-22', 'Confirmada'),
(19, '121.121.121-12', '2026-06-19', '2026-06-23', 'Confirmada'),
(20, '131.131.131-13', '2026-06-20', '2026-06-24', 'Confirmada');

 

-- =========================================
-- CONSULTAS PARA TESTE
-- =========================================

 

-- Listar todos os chalés
SELECT * FROM chale;

 

-- Listar clientes
SELECT * FROM cliente;

 

-- Reservas confirmadas
SELECT * FROM reserva
WHERE status = 'Confirmada';

 

-- Chalés com diária acima de 500
SELECT nome, preco_diaria
FROM chale
WHERE preco_diaria > 500;

 

-- Quantidade de reservas por status
SELECT status, COUNT(*) AS quantidade
FROM reserva
GROUP BY status;

 

-- Mostrar reservas com nome do cliente e chalé
SELECT
    r.id,
    c.nome AS cliente,
    ch.nome AS chale,
    r.data_inicio,
    r.data_fim,
    r.status
FROM reserva r
INNER JOIN cliente c
ON r.id_cliente = c.cpf
INNER JOIN chale ch
ON r.id_chale = ch.id;