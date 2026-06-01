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


INSERT INTO chale (nome, descricao, preco_diaria, datas_disponiveis, disponibilidade, categoria_id) VALUES
('Chalé do Bosque', 'Chalé aconchegante cercado por natureza.', 250.00, '["2026-06-01", "2026-06-02"]', TRUE, 1),
('Chalé da Montanha', 'Vista espetacular com lareira privativa.', 450.00, '["2026-06-10", "2026-06-11"]', TRUE, 2),
('Refúgio das Flores', 'Perfeito para casais, ambiente romântico.', 300.00, '["2026-06-05"]', TRUE, 1),
('Chalé Luxo Premium', 'Hidromassagem, ar condicionado e luxo total.', 850.00, '["2026-07-01", "2026-07-02"]', TRUE, 3),
('Cabana Rústica', 'Estilo vintage aconchegante com fogão a lenha.', 200.00, '["2026-06-20"]', TRUE, 1),
('Chalé Horizonte', 'Vista panorâmica do vale ao nascer do sol.', 500.00, '["2026-06-15", "2026-06-16"]', TRUE, 2),
('Suíte Master Serra', 'Conforto absoluto com varanda gourmet.', 900.00, '["2026-07-10"]', TRUE, 3),
('Chalé do Lago', 'Ideal para quem busca paz e tranquilidade.', 380.00, '["2026-06-08", "2026-06-09"]', TRUE, 2),
('Chalé Aconchego', 'Compacto, bem equipado e muito confortável.', 220.00, '["2026-06-25"]', TRUE, 1),
('Chalé Vista Real', 'A experiência premium com vista deslumbrante.', 1200.00, '["2026-08-01"]', TRUE, 3);


INSERT INTO reserva (id_chale, id_cliente, data_inicio, data_fim, status) VALUES
(1, '111.111.111-11', '2026-06-01', '2026-06-03', 'Confirmada'),
(2, '222.222.222-22', '2026-06-10', '2026-06-12', 'Pendente'),
(3, '333.333.333-33', '2026-06-05', '2026-06-07', 'Confirmada'),
(4, '444.444.444-44', '2026-07-01', '2026-07-05', 'Confirmada'),
(5, '555.555.555-55', '2026-06-20', '2026-06-22', 'Cancelada'),
(6, '666.666.666-66', '2026-06-15', '2026-06-17', 'Confirmada'),
(7, '777.777.777-77', '2026-07-10', '2026-07-12', 'Pendente'),
(8, '888.888.888-88', '2026-06-08', '2026-06-10', 'Confirmada'),
(9, '999.999.999-99', '2026-06-25', '2026-06-27', 'Pendente'),
(10, '101.101.101-10', '2026-08-01', '2026-08-05', 'Confirmada');

 
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