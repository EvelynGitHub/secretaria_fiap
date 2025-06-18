# Secretaria - Desafio FIAP

<center>

![Imagem de capa](https://github.com/EvelynGitHub/assets-readme/blob/main/img/capa.png)

</center>
<center>
![GitHub](https://img.shields.io/github/license/EvelynGitHub/assets-readme)
</center>

# Sobre o Projeto:

Este projeto consiste no desenvolvimento de uma aplicação para a secretaria da FIAP, focada no gerenciamento administrativo de alunos, turmas e matrículas.

## Funcionalidades Principais:
**Alunos**: Cadastro, listagem, edição e exclusão de alunos, que devem ter nome, data de nascimento, CPF, e-mail e senha.

**Turmas**: Cadastro, listagem, edição e exclusão de turmas, que armazenam nome e descrição. Na listagem, é exibida a quantidade de alunos por turma. As turmas são paginadas a cada 10 itens.

**Matrículas**: Permite a matrícula de alunos em turmas e a visualização dos alunos matriculados em uma turma específica.

## Regras de Negócio Implementadas:
- Listagens ordenadas alfabeticamente por padrão.
- Nomes de alunos e turmas com no mínimo 3 caracteres.
- Validação obrigatória de todos os campos e validade dos dados.
- Restrição de matrícula de um aluno duas vezes no mesmo curso.
- Um aluno só pode ser cadastrado uma única vez por CPF ou e-mail.
- Senhas devem seguir o padrão de no forte.
- Busca de alunos pelo nome.

## Bônus Implementados:
- Endpoint de login para administradores com autenticação via e-mail e senha.
- Todos os endpoint de Alunos, Turmas e Matricula são acessíveis somente por um usuário logado.

## Estrutura idealizada
- Tudo dentro de Core pode ser 'recortado e colado' dentro de outro projeto com as implementações do contratos
- UUID como identificador no frontend e ID no backend
- Simulação de blacklist (que poderia ser feita com Redis, por exemplo) feita com arquivo .txt


## Testes realizados
✅ - representa teste com PHPUnit

#### Aluno: nome, data de nascimento, CPF, e-mail e senha
0 - Testar acesso com e sem token
1 - Verificar se os dados de cadastros estão validos✅
2 - Verificar se permite cadastro de mesmo CPF✅
3 - Verificar se permite cadastro de mesmo email✅
4 - Verificar se data de nascimento é cadastrada certo a depender do formato ✅
5 - Verificar se atualização está correta ✅
6 - Testar o que acontece se não deseja atualiza todos os campos ✅
7 - Tentar atuallizar CPF para outro ja existente ✅
8 - Tentar atuallizar EMAIL para outro ja existente
9 - Realizar teste de senha forte
10 - Verificar se a listagem está retornando ordenada pelo nome ✅
11 - Verificar se a paginação está correta ✅
12 - Testar obtenção de aluno não existente✅
13 - Verificar se retorna aluno corretamente✅
14 - Testar exclusão de aluno
15 - Testar listagem de aluno por turma caso a turma não exista
16 - Testar listagem de aluno por turma caso a turma exista
17 - Testar cadastrar nome com menos de 3 caracteres ✅

#### Turma: nome, descricao
0 - Testar acesso com e sem token
1 - Testar o cadastro
2 - Testar a edição completa e parcial
4 - Testar a ordenação da listagem pelo nome da turma
5 - Testar o filtro por nome da listagem
6 - Verificar se turma existe
7 - Obter uma turma corretamente
8 - Testar exclusão de turma
9 - Testar nome com menos de 3 caracteres
10 - Testar quantidade de alunos na listagem de turmas

#### Matricula
0 - Testar acesso com e sem token
1 - Testar matricular um aluno inexistente✅
2 - Testar matricular um aluno em um turma inexistente✅
3 - Testar cadastrar o mesmo aluno duas vezes na mesma turma✅
4 - Testar o cadastro de aluno na turma com sucesso✅

### Admin
1 - Testar login com email e/ou senha incorretos
2 - Testar login com retorno de sucesso

<!-- Para deixar as imagens uma embaixo da outra, devesse colocar os links um embaixo outro com  duas quebras de linha -->



## Modelo conceitual

### MER

![Modelo de Entidade e Relacionamento](https://github.com/EvelynGitHub/assets-readme/blob/main/desafio-fiap/mer-fiap.png)

# Tecnologias utilizadas:

## Back end
- PHP
- Docker
- Swagger
- MySql


## Front end

- HTML / CSS / JS / 
- Bootstrap


# Por onde começar:

## Back end

Pré-requisitos: Docker, make, git

```bash
# clonar repositório
git clone https://github.com/EvelynGitHub/secretaria_fiap

# entrar na pasta do projeto back end
cd secretaria_fiap

# executar o projeto
make build
make up
make composer-install


# executar o testes
make test

# parar o projeto
make down

```

# Como começar a usar:

Documentação da API: http://localhost:8080/doc/#/

Frontend da aplicação: http://localhost:8080/

Os dado do admin padrão já estão no swagger e no formulário de login

# Autor

**Evelyn Francisco Brandão**

https://linkedin.com/in/evelyn-brandão

[EvelynGitHub](https://github.com/EvelynGitHub/)

