<?php

declare(strict_types=1);

namespace SecretariaFiap\Adapter\PDO;

use PDO;
use PDOException;
use SecretariaFiap\Core\Contratos\Repositorio\AlunoRepositorio;
use SecretariaFiap\Core\Entidade\Aluno;
use SecretariaFiap\Core\Entidade\Senha;
use SecretariaFiap\Helpers\Paginacao;
use SecretariaFiap\Infra\Banco\Conexao;

class AlunoRepositorioPDO implements AlunoRepositorio
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = Conexao::obter();
    }

    public function cadastrar(Aluno $aluno): Aluno
    {
        $sql = "INSERT INTO alunos (uuid, nome, cpf, email, senha_hash, data_nascimento) 
                VALUES (:uuid, :nome, :cpf, :email, :senha, :nascimento)";
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                ':uuid' => $aluno->getUuid(),
                ':nome' => $aluno->getNome(),
                ':cpf' => $aluno->getCpf(),
                ':email' => $aluno->getEmail(),
                ':senha' => $aluno->getSenha(),
                ':nascimento' => $aluno->getDataNascimento()->format('Y-m-d'),
            ]);
            $aluno->setId((int) $this->pdo->lastInsertId());
            return $aluno;
        } catch (PDOException $e) {
            throw new \RuntimeException("Erro ao cadastrar aluno: " . $e->getMessage());
        }
    }

    public function editar(Aluno $aluno): bool
    {
        $sql = "UPDATE alunos SET nome = :nome, email = :email, senha_hash = :senha, data_nascimento = :nascimento 
                WHERE uuid = :uuid";
        try {
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute([
                ':nome' => $aluno->getNome(),
                ':email' => $aluno->getEmail(),
                ':senha' => $aluno->getSenha(),
                ':nascimento' => $aluno->getDataNascimento()->format('Y-m-d'),
                ':uuid' => $aluno->getUuid(),
            ]);
        } catch (PDOException $e) {
            return false;
        }
    }

    public function deletar(string $uuid): bool
    {
        try {
            $stmt = $this->pdo->prepare("DELETE FROM alunos WHERE uuid = :uuid");
            return $stmt->execute([':uuid' => $uuid]);
        } catch (PDOException $e) {
            return false;
        }
    }

    public function buscarPorUuid(string $uuid): ?Aluno
    {
        $stmt = $this->pdo->prepare("SELECT * FROM alunos WHERE uuid = :uuid");
        $stmt->execute([':uuid' => $uuid]);
        $dados = $stmt->fetch(PDO::FETCH_ASSOC);
        return $dados ? $this->mapearParaEntidade($dados) : null;
    }

    public function buscarPorCpf(string $cpf): ?Aluno
    {
        $stmt = $this->pdo->prepare("SELECT * FROM alunos WHERE cpf = :cpf");
        $stmt->execute([':cpf' => $cpf]);
        $dados = $stmt->fetch(PDO::FETCH_ASSOC);
        return $dados ? $this->mapearParaEntidade($dados) : null;
    }

    /**
     * Lista alunos por filtros, retornando um objeto de paginação.
     *
     * @param string $nome O nome a ser filtrado.
     * @param int $offset O offset para a paginação.
     * @param int $limit O limite de registros por página.
     * @return Paginacao<Aluno> Um objeto Paginacao contendo uma lista de objetos Aluno.
     */
    public function listarPorFiltros(string $nome, int $offset = 0, int $limit = 10): Paginacao
    {
        // 1. Consulta para obter os dados da página atual
        $sqlDados = "SELECT * FROM alunos 
            WHERE nome LIKE :nome 
            ORDER BY alunos.nome ASC 
            LIMIT :offset, :limit ";
        $stmtDados = $this->pdo->prepare($sqlDados);
        $stmtDados->bindValue(':nome', "%{$nome}%");
        $stmtDados->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmtDados->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmtDados->execute();

        $alunos = [];
        while ($row = $stmtDados->fetch(PDO::FETCH_ASSOC)) {
            $alunos[] = $this->mapearParaEntidade($row);
        }

        // 2. Consulta separada para obter o total de registros
        $sqlTotal = "SELECT COUNT(id) FROM alunos WHERE nome LIKE :nome ";
        $stmtTotal = $this->pdo->prepare($sqlTotal);
        $stmtTotal->bindValue(':nome', "%{$nome}%");
        $stmtTotal->execute();
        $totalRegistros = $stmtTotal->fetchColumn();

        $totalPaginas = (int) ceil($totalRegistros / $limit);
        $paginaAtual = (int) ($offset / $limit) + 1;

        return new Paginacao(
            paginaAtual: $paginaAtual,
            totalRegistros: (int) $totalRegistros,
            totalPaginas: $totalPaginas,
            limite: $limit,
            itens: $alunos
        );
    }

    /**
     * Lista alunos por turma, retornando um objeto de paginação.
     *
     * @param string $uuidTurma O UUID da turma a ser filtrada.
     * @param int $offset O offset para a paginação.
     * @param int $limit O limite de registros por página.
     * @return Paginacao<Aluno> Um objeto Paginacao contendo uma lista de objetos Aluno.
     */
    public function listarPorTurma(string $uuidTurma, int $offset = 0, int $limit = 10): Paginacao
    {
        // 1. Consulta para obter os dados da página atual
        $sqlDados = "SELECT alunos.* 
            FROM alunos 
            JOIN matriculas ON alunos.id = matriculas.aluno_id 
            JOIN turmas ON matriculas.turma_id = turmas.id 
            WHERE turmas.uuid = :uuidTurma
            ORDER BY alunos.nome ASC
            LIMIT :offset, :limit";

        $stmtDados = $this->pdo->prepare($sqlDados);
        $stmtDados->bindValue(':uuidTurma', $uuidTurma);
        $stmtDados->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmtDados->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmtDados->execute();

        $alunos = [];
        while ($row = $stmtDados->fetch(PDO::FETCH_ASSOC)) {
            $alunos[] = $this->mapearParaEntidade($row);
        }

        // 2. Consulta separada para obter o total de registros
        $sqlTotal = "SELECT COUNT(alunos.id) 
            FROM alunos 
            JOIN matriculas ON alunos.id = matriculas.aluno_id 
            JOIN turmas ON matriculas.turma_id = turmas.id 
            WHERE turmas.uuid = :uuidTurma
            ORDER BY alunos.nome ASC";
        $stmtTotal = $this->pdo->prepare($sqlTotal);
        $stmtTotal->bindValue(':uuidTurma', $uuidTurma);
        $stmtTotal->execute();
        $totalRegistros = $stmtTotal->fetchColumn();

        $totalPaginas = (int) ceil($totalRegistros / $limit);
        $paginaAtual = (int) ($offset / $limit) + 1;

        return new Paginacao(
            paginaAtual: $paginaAtual,
            totalRegistros: (int) $totalRegistros,
            totalPaginas: $totalPaginas,
            limite: $limit,
            itens: $alunos
        );
    }

    private function mapearParaEntidade(array $dados): Aluno
    {
        $aluno = new Aluno(
            $dados['nome'],
            $dados['cpf'],
            $dados['email'],
            Senha::criarAPartirDoHash($dados['senha_hash']),
            new \DateTime($dados['data_nascimento']),
            $dados['uuid']
        );
        $aluno->setId((int) $dados['id']);
        return $aluno;
    }
}
