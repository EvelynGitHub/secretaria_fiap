<?php

declare(strict_types=1);

namespace Tests\Unit;

use DateTime;
use PHPUnit\Framework\TestCase;
use SecretariaFiap\Adapter\PDO\AlunoRepositorioPDO;
use SecretariaFiap\Adapter\PDO\MatriculaRepositorioPDO;
use SecretariaFiap\Adapter\PDO\TurmaRepositorioPDO;
use SecretariaFiap\Core\CasosUso\Matricula\MatricularAluno;
use SecretariaFiap\Core\CasosUso\Matricula\InputObject;
use SecretariaFiap\Core\Entidade\Aluno;
use SecretariaFiap\Core\Entidade\Senha;
use SecretariaFiap\Core\Entidade\Turma;
use Tests\ConexaoTeste;

class MatriculaTest extends TestCase
{

    private AlunoRepositorioPDO $alunoRepo;
    private TurmaRepositorioPDO $turmaRepo;
    private MatriculaRepositorioPDO $matriculaRepo;
    private MatricularAluno $casoUso;

    protected function setUp(): void
    {
        $this->alunoRepo = new AlunoRepositorioPDO();
        $this->turmaRepo = new TurmaRepositorioPDO();
        $this->matriculaRepo = new MatriculaRepositorioPDO();
        $this->casoUso = new MatricularAluno(
            $this->alunoRepo,
            $this->turmaRepo,
            $this->matriculaRepo
        );

        $pdo = ConexaoTeste::obter();
        $pdo->exec("DELETE FROM matriculas");
        $pdo->exec("DELETE FROM alunos");
        $pdo->exec("DELETE FROM turmas");
    }

    /** @test */
    public function test_deve_lancar_excecao_ao_matricular_aluno_inexistente()
    {
        $turma = new Turma('Turma Teste', 'Descricao');
        $this->turmaRepo->cadastrar($turma);

        // $this->expectExceptionMessage('Aluno não encontrado.');

        $input = InputObject::create([
            'uuid_aluno' => 'uuid-invalido',
            'uuid_turma' => $turma->getUuid()
        ]);

        $output = $this->casoUso->executar($input);
        // Afirmar
        $this->assertEquals('Aluno não encontrado.', $output->mensagem);
    }

    /** @test */
    public function test_deve_lancar_excecao_ao_matricular_em_turma_inexistente()
    {
        $aluno = new Aluno('Maria', '12345678900', 'maria@teste.com', Senha::criarAPartirDoHash('Senha123!'), new DateTime('1990-01-01'));
        $this->alunoRepo->cadastrar($aluno);

        // $this->expectExceptionMessage('Turma não encontrada.');

        $input = InputObject::create([
            'uuid_aluno' => $aluno->getUuid(),
            'uuid_turma' => 'uuid-invalido'
        ]);


        $output = $this->casoUso->executar($input);
        // Afirmar
        $this->assertEquals('Turma não encontrada.', $output->mensagem);
    }

    /** @test */
    public function test_nao_deve_matricular_mesmo_aluno_duas_vezes_na_mesma_turma()
    {
        $aluno = new Aluno('Lucas', '78945612300', 'lucas@teste.com', Senha::criarAPartirDoHash('Senha123!'), new DateTime('1992-02-02'));
        $turma = new Turma('Turma A', 'Descricao A');

        $this->alunoRepo->cadastrar($aluno);
        $this->turmaRepo->cadastrar($turma);

        $input = InputObject::create([
            'uuid_aluno' => $aluno->getUuid(),
            'uuid_turma' => $turma->getUuid()
        ]);

        $output1 = $this->casoUso->executar($input);

        $output2 = $this->casoUso->executar($input);

        $this->assertEquals("{$aluno->getNome()} matriculado(a) em {$turma->getNome()} com sucesso.", $output1->mensagem);
        $this->assertEquals('Aluno já está matriculado nesta turma.', $output2->mensagem);
    }

    /** @test */
    public function test_deve_matricular_aluno_em_turma_com_sucesso()
    {
        $aluno = new Aluno('Julia', '32165498700', 'julia@teste.com', Senha::criarAPartirDoHash('Senha123!'), new DateTime('1995-05-05'));
        $turma = new Turma('Turma B', 'Descricao B');

        $this->alunoRepo->cadastrar($aluno);
        $this->turmaRepo->cadastrar($turma);

        $input = InputObject::create([
            'uuid_aluno' => $aluno->getUuid(),
            'uuid_turma' => $turma->getUuid()
        ]);

        $output = $this->casoUso->executar($input);

        $this->assertEquals("{$aluno->getNome()} matriculado(a) em {$turma->getNome()} com sucesso.", $output->mensagem);
    }

}
