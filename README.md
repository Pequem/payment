# Payment

## Rodar

Basta utilizar o docker e rodar o "docker-compose up -d --build" na pasta do projeto.

## Sobre

A APP foi desenvolvida em Lumen, por se tratar de uma microframework que possui um bom desempenho, o banco utilizado foi o MySQL devido a necessidade de trabalhar com Transaction e ter uma estrutura relacional.

No Banco existe uma tabela de transação e uma tabela de balanço que armazena o saldo atual de cada usuário, a tabela de balanço é necessária para otimizar a velocidade das transações, pois calcular o saldo do usuário a cada nova transação seria muito custoso.

A notificação é feita atravez de JOBs em background, para realizar o desacoplamento da transação e evitar problemas de atraso na resposta da transação caso o serviço esteja indisponível.

A Arquitetura da api é MVC, com uma camada de serviço, uma camada de repositório e entidades para tranferencia de dados entre camadas. Foi seguido os padrões do solid.

Os testes foram realizados atravez do PHPUnit usando as ferramentas fornecidas pelo próprio lumen.

A checagem do saldo do usuário é feita após gravar as transações e atualizar os saldos, isso evita que duas transações concorrentes leem o saldo ao mesmo tempo e o usuário fique com saldo negativo.

## Uso

### Criar usuário

POST /user

Response 201

```json
{
    "first_name": "",
    "last_name": "",
    "cnpj":"",
    "cpf":"",
    "email":"",
    "password":"",
    "type": ""
}
```

- O CPF e CNPJ não precisam estar presentes ao mesmo tempo.
- Para usuários comuns o tipo é default e para lojistas é store.

### Adicionar fundos a carteira do usuário

POST /transaction/funds

Reponse 201

```json
{
    "user_id": 1,
    "amount": 500
}
```

### Realizar uma transação

POST /transaction

Response 201

```json
{
    "payee": 1,
    "payer": 2,
    "value": 800
}
```