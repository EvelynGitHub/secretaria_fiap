CREATE DATABASE IF NOT EXISTS example_fiap;

USE example_fiap;

-- Tabela de alunos
CREATE TABLE
    alunos (
        id INT AUTO_INCREMENT PRIMARY KEY,
        uuid CHAR(36) NOT NULL UNIQUE,
        nome VARCHAR(100) NOT NULL,
        cpf CHAR(11) NOT NULL UNIQUE,
        email VARCHAR(100) NOT NULL UNIQUE,
        senha_hash VARCHAR(255) NOT NULL,
        data_nascimento DATE NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    );

-- Tabela de turmas
CREATE TABLE
    turmas (
        id INT AUTO_INCREMENT PRIMARY KEY,
        uuid CHAR(36) NOT NULL UNIQUE,
        nome VARCHAR(100) NOT NULL,
        descricao TEXT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    );

-- Tabela de matrículas
CREATE TABLE
    matriculas (
        id INT AUTO_INCREMENT PRIMARY KEY,
        uuid CHAR(36) NOT NULL UNIQUE,
        aluno_id INT NOT NULL,
        turma_id INT NOT NULL,
        data_matricula DATETIME DEFAULT CURRENT_TIMESTAMP,
        UNIQUE KEY aluno_turma_unica (aluno_id, turma_id),
        FOREIGN KEY (aluno_id) REFERENCES alunos (id) ON DELETE CASCADE,
        FOREIGN KEY (turma_id) REFERENCES turmas (id) ON DELETE CASCADE
    );

-- Tabela de admins
CREATE TABLE
    admins (
        id INT AUTO_INCREMENT PRIMARY KEY,
        uuid CHAR(36) NOT NULL UNIQUE,
        nome VARCHAR(100) NOT NULL,
        email VARCHAR(100) NOT NULL UNIQUE,
        senha_hash VARCHAR(255) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    );

-- Alunos
INSERT INTO
    alunos (
        uuid,
        nome,
        cpf,
        email,
        senha_hash,
        data_nascimento
    )
VALUES
    (
        UUID (),
        'Ana Souza Silva',
        '12345678901',
        'ana@exemplo.com',
        '$2y$10$CasrKgPY81OfMYleTxRnZO3f8pyBnOHyqTO4aNogXdqwwabKJL6sm',
        '2000-05-12'
    ),
    (
        UUID (),
        'Carlos Lima',
        '98765432100',
        'carlos@exemplo.com',
        '$2y$10$St4KrWRTZWtX0dHYKFjYKe2WFmvFl3V49hOEfXHqNsZzD/v9yWgoC',
        '1999-08-30'
    );

-- Turmas
INSERT INTO
    turmas (uuid, nome, descricao)
VALUES
    (UUID (), 'Turma A', 'Curso de Tecnologia da FIAP'),
    (
        UUID (),
        'Turma B',
        'Curso de Inovação e Empreendedorismo'
    );

-- Admins
INSERT INTO
    admins (uuid, nome, email, senha_hash)
VALUES
    (
        UUID (),
        'Administrador',
        'admin@fiap.com.br',
        '$2y$10$hbpU8EU5i7wDKmqAUEar0.3TjB2FY6zqJ3pQHGjCgHGcy6YsSsg5y'
    );