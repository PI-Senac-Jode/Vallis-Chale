
<h1>Vallis-Chalé</h1>

## 🏔️ Vallis Chalé - Plataforma de Reservas Integrada
<br>
Solução web moderna para conectar locatários e consumidores, unificando visualização, reserva e pagamento em uma experiência fluida, segura e rústica.

---

## ⭐ Objetivo do Projeto
<br>
O objetivo central do Vallis Chalés é facilitar a visualização e permitir a melhor acessibilidade para a locação e reserva de chalés. Através de uma criação web de alta performance, buscamos trazer benefícios claros para ambas as pontas do processo:

- Para o Locatário (Proprietário): Performance aprimorada na gestão de anúncios, reservas e recebimentos.

- Para o Consumidor (Hóspede): Facilidade de navegação, visualização clara dos chalés e agilidade no processo de agendamento.

---

## 🛠️ Funcionalidades Chave (Core Business)
<br>

A ideia central do sistema é simplificar a jornada do usuário, removendo fricções entre o interesse e a confirmação da reserva.

- Visualização e Acessibilidade: Interface intuitiva focada em destacar as qualidades rústicas e exclusivas dos chalés, facilitando a escolha do consumidor.

- Reserva Atrelada ao Login: Otimização do fluxo. O usuário tem a opção de efetivar a reserva no exato momento da autenticação (login), agilizando o processo.

- Pa-gamento Integrado: Inclusão de formas de pagamento diretamente no ato da reserva. Sem redirecionamentos desnecessários ou processos manuais.

- Segurança e Facilidade: Foco total n- a segurança das transações financeiras e dos dados dos usuários, proporcionando uma experiência de compra tranquila e fácil.

--- 
## 💻 Linguagens/Ferramentas Utilizadas
<br>

- Layout: Figma

- Frontend: HTML5, CSS3, Javascript

- Backend: PHP

- Banco de Dados: Xampp Mysql

--- 


##  📁 Estrutura de pastas

<br>

 ```
Vallis-Chale/
├───database
│      
├───frontend
│   ├───includes
│   └───styles
│   └───sections
├───pages
├───scripts
└───src
└───assets
└───img
```

---

### 📊 Diagrama do Banco de Dados
<br>

```mermaid
erDiagram
    CATEGORIAS_CHALE ||--o{ CHALE : "classifica"
    CLIENTE ||--o{ RESERVA : "realiza"
    CHALE ||--o{ RESERVA : "recebe"

    CATEGORIAS_CHALE {
        int id PK
        string nome UK
        text descricao
    }

    CLIENTE {
        string cpf PK
        string nome
        string email UK
    }

    CHALE {
        int id PK
        string nome UK
        text descricao
        string imagem_url
        json tags
        decimal preco_diaria
        tinyint capacidade_pessoas
        boolean disponibilidade
        int categoria_id FK
    }

    RESERVA {
        int id PK
        int id_chale FK
        string id_cliente FK
        date data_inicio
        date data_fim
        enum status
        timestamp data_criacao
        decimal valor_total
    }