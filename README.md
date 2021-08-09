# Payment

## Rodar

Basta utilizar o docker e rodar o "docker-compose up -d --build" na pasta do projeto.

- Durante o start, o container web espera 20 segundos para dar tempo do mysql criar o Schema antes de rodar as migrations, pois mesmo colocando o web como dependente do mysql no docker-compose, o web não espera o MySQL criar o Schema, mas pode ocorrer de o MySQL ainda não ter criado o Schema, então reinicie o container da web caso isso ocorra.
- Tentei deixar o processo de inicio mais suável possível.

## Sobre

A APP foi desenvolvida em Lumen, por se tratar de uma microframework que possui um bom desempenho, o banco utilizado foi o MySQL devido a necessidade de trabalhar com Transaction e ter uma estrutura relacional.

No Banco existe uma tabela de transações e uma tabela de balanços que armazena o saldo atual de cada usuário, a tabela de balanço é necessária para otimizar a velocidade das transações, pois calcular o saldo do usuário a cada nova transação seria muito custoso.

A notificação é feita atravez de JOB's em background, para realizar o desacoplamento da transação e evitar problemas de atraso na resposta da transação caso o serviço esteja indisponível.

A Arquitetura da api é MVC, com uma camada de serviços, uma camada de repositórios e entidades para tranferencia de dados entre as camadas. Foi seguido os princípios do SOLID.

Os testes foram realizados atravez do PHPUnit usando as ferramentas fornecidas pelo próprio lumen.

- Obs: Os testes apagam o banco.

A checagem do saldo do usuário é feita após gravar as transações e atualizar os saldos, isso evita que duas transações concorrentes leem o saldo ao mesmo tempo e o usuário fique com saldo negativo.

## Uso

### Criar usuário

POST /user

Request

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

Response 201

### Atualiza usuário

PUT /user

Request

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

Response 201

### Consulta usuário

PUT /user/1

Response

```json
{
    "id": 1,
    "firstName": "",
    "lastName": "",
    "cpf": "",
    "cnpj": null,
    "email": "",
    "type": "",
}
```

### Consulta as transações do usuário

GET /transaction/history/1

Response

```json
[
    {
        "id": 1,
        "userIdPayee": 1,
        "userIdPayer": null,
        "amount": 1000.65,
        "type": "default"
    }
]
```

### Consulta o saldo do usuário

GET /user/balance/1

Response

```json
{
    "id": 1,
    "userId": 1,
    "balance": 1000.65
}
```

### Adicionar fundos a carteira do usuário

POST /transaction/funds

Resquest

```json
{
    "user_id": 1,
    "amount": 500
}
```

Reponse 201

### Realizar uma transação

POST /transaction

Request

```json
{
    "payee": 1,
    "payer": 2,
    "value": 800
}
```

Response 201