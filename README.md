
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

- Pagamento Integrado: Inclusão de formas de pagamento diretamente no ato da reserva. Sem redirecionamentos desnecessários ou processos manuais.

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
├───backend
├───database
├───frontEnd
│   ├───includes
│   └───styles
│       └───sections
├───pages
├───scripts
└───src
    └───assets
        └───img
```

## 🚀 Como Instalar e Executar o Projeto (XAMPP)
<br>

<p>Siga os passos abaixo para configurar o ambiente de desenvolvimento local utilizando o XAMPP:</p>

<ol>
  <li>
    <strong>Baixar e Instalar o XAMPP:</strong><br>
    Certifique-se de ter o <a href="https://www.apachefriends.org/" target="_blank">XAMPP</a> instalado em sua máquina com suporte para <code>PHP 8.x</code> ou superior.
  </li>
  <br>
  <li>
    <strong>Clonar ou Mover o Projeto:</strong><br>
    Mova a pasta completa do projeto <code>Vallis-Chale</code> para o diretório de arquivos públicos do XAMPP (<code>htdocs</code>):
    <ul>
      <li><strong>Windows:</strong> <code>C:\xampp\htdocs\Vallis-Chale</code></li>
      <li><strong>Linux:</strong> <code>/opt/lampp/htdocs/Vallis-Chale</code></li>
      <li><strong>macOS:</strong> <code>/Applications/XAMPP/xamppfiles/htdocs/Vallis-Chale</code></li>
    </ul>
  </li>
  <br>
  <li>
    <strong>Iniciar os Serviços:</strong><br>
    Abra o painel de controle do XAMPP e clique em <kbd>Start</kbd> nos módulos <strong>Apache</strong> e <strong>MySQL</strong>.
  </li>
  <br>
  <li>
    <strong>Configurar o Banco de Dados:</strong>
    <ul>
      <li>Abra o navegador e acesse: <a href="http://localhost/phpmyadmin/" target="_blank">http://localhost/phpmyadmin/</a></li>
      <li>Crie um novo banco de dados clicando em <strong>Novo</strong> (recomenda-se nomear de acordo com a configuração de conexão do projeto).</li>
      <li>Com o banco selecionado, clique na aba <strong>Importar</strong>.</li>
      <li>Escolha o arquivo <code>.sql</code> localizado dentro da pasta <code>database/</code> do projeto e clique em <strong>Executar</strong> no final da página.</li>
    </ul>
  </li>
  <br>
  <li>
    <strong>Ajustar Conexão com o Banco (Se necessário):</strong><br>
    Caso precise alterar as credenciais de acesso ao banco de dados, verifique o arquivo de configuração de conexão dentro da pasta <code>backend/</code>. O padrão do XAMPP é:
    <pre><code>Host: localhost
Usuário: root
Senha: "" (vazio)</code></pre>
  </li>
</ol>

---

## 🕹️ Como Usar
<br>

<p>Depois de configurar o ambiente local com o XAMPP, utilize a plataforma seguindo o fluxo abaixo:</p>

<ol>
  <li>
    <strong>Acessar a Plataforma:</strong><br>
    Abra o navegador de sua preferência e digite o endereço:
    <pre><code>http://localhost/Vallis-Chale/</code></pre>
    <em>(Caso a página inicial esteja dentro de uma subpasta específica, utilize o caminho completo, como <code>http://localhost/Vallis-Chale/pages/index.php</code>)</em>.
  </li>
  <br>
  <li>
    <strong>Explorar os Chalés:</strong><br>
    Na interface principal, navegue pelas opções de chalés disponíveis, filtre por categorias e consulte os detalhes específicos de cada acomodação, como capacidade de pessoas e preço da diária.
  </li>
  <br>
  <li>
    <strong>Fluxo de Autenticação:</strong><br>
    Para simular ou efetuar uma reserva, acesse a área de Login. Se for o primeiro acesso do usuário, realize o cadastro inserindo os dados básicos solicitados (Nome, CPF e E-mail).
  </li>
  <br>
  <li>
    <strong>Realizar uma Reserva:</strong><br>
    Após estar autenticado, selecione o chalé desejado, insira o período de estadia (Data de Início e Data de Fim) e siga o fluxo otimizado para o fechamento.
  </li>
</ol>
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


