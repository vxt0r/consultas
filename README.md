# Gerenciador de consultas
### PHP, MySQL, HTML e CSS
### Bibliotecas usadas : Phpmailer
- A aplicação permite criar consultas definindo o nome e email do paciente com a data e hora da consulta.
- A aplicação não permite que o usuário crie consultas com a mesma data e hora.
- O usuário pode confirmar ou desmarcar uma consulta, enviando um email ao paciente, em ambos os casos.
#### <a href="https://youtu.be/qY9bIYvgSFw" target="_blank" rel="noopener noreferrer">Veja um vídeo demonstrativo</a>

#### Como usar
**É necessário criar um arquivo chamado Config.php na pasta app, com duas constantes informando suas configurações do servidor SMTP e da conexão com o banco de dados (MySQL).**
    
    <?php
    define('MAIL',[
        'host'=>'Seu servidor smtp',
        'port'=>'Porta do seu servidor smtp',
        'user'=>'Conta que enviará os emails',
        'password'=>'Código que permite usar a conta para disparar emails',
        'from_name'=>'Nome do remetente',
        'from_email'=>'Email do remetente',
    ]);

    define('DB',[
        'host' => '',
        'db' => '',
        'user' => '',
        'pass' => ''
    ]);
    ?>

**Crie a seguinte tabela :**

    create table consulta(
        id int primary key auto_increment,
        nome_paciente varchar(100) not null,
        email_paciente varchar(100) not null,
        data_hora datetime not null,
        confirmada boolean default false
    );

**Após isso, é necessário somente instalar as dependências e rodar o servidor para o funcionamento da aplicação.**

    composer install
    cd public
    php -S localhost:8080 