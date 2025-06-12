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
    -- Aluno 1
    (
        UUID (),
        'Ana Souza Silva',
        '12345678901',
        'ana@exemplo.com',
        '$2y$10$CasrKgPY81OfMYleTxRnZO3f8pyBnOHyqTO4aNogXdqwwabKJL6sm',
        '2000-05-12'
    ),
    -- Aluno 2
    (
        UUID (),
        'Carlos Lima',
        '98765432100',
        'carlos@exemplo.com',
        '$2y$10$CasrKgPY81OfMYleTxRnZO3f8pyBnOHyqTO4aNogXdqwwabKJL6sm',
        '1999-08-30'
    ),
    -- Aluno 3
    (
        UUID (),
        'Mariana Fernandes',
        '11223344550',
        'mariana@exemplo.com',
        '$2y$10$CasrKgPY81OfMYleTxRnZO3f8pyBnOHyqTO4aNogXdqwwabKJL6sm',
        '2001-02-15'
    ),
    -- Aluno 4
    (
        UUID (),
        'João Pereira',
        '22334455660',
        'joao@exemplo.com',
        '$2y$10$CasrKgPY81OfMYleTxRnZO3f8pyBnOHyqTO4aNogXdqwwabKJL6sm',
        '1998-11-23'
    ),
    -- Aluno 5
    (
        UUID (),
        'Juliana Oliveira',
        '33445566770',
        'juliana@exemplo.com',
        '$2y$10$CasrKgPY81OfMYleTxRnZO3f8pyBnOHyqTO4aNogXdqwwabKJL6sm',
        '2002-07-01'
    ),
    -- Aluno 6
    (
        UUID (),
        'Fernando Costa',
        '44556677880',
        'fernando@exemplo.com',
        '$2y$10$CasrKgPY81OfMYleTxRnZO3f8pyBnOHyqTO4aNogXdqwwabKJL6sm',
        '1997-04-10'
    ),
    -- Aluno 7
    (
        UUID (),
        'Isabela Rocha',
        '55667788990',
        'isabela@exemplo.com',
        '$2y$10$CasrKgPY81OfMYleTxRnZO3f8pyBnOHyqTO4aNogXdqwwabKJL6sm',
        '2000-09-05'
    ),
    -- Aluno 8
    (
        UUID (),
        'Rafael Santos',
        '66778899000',
        'rafael@exemplo.com',
        '$2y$10$CasrKgPY81OfMYleTxRnZO3f8pyBnOHyqTO4aNogXdqwwabKJL6sm',
        '1999-01-28'
    ),
    -- Aluno 9
    (
        UUID (),
        'Larissa Guedes',
        '77889900110',
        'larissa@exemplo.com',
        '$2y$10$CasrKgPY81OfMYleTxRnZO3f8pyBnOHyqTO4aNogXdqwwabKJL6sm',
        '2003-12-19'
    ),
    -- Aluno 10
    (
        UUID (),
        'Daniel Azevedo',
        '88990011220',
        'daniel@exemplo.com',
        '$2y$10$CasrKgPY81OfMYleTxRnZO3f8pyBnOHyqTO4aNogXdqwwabKJL6sm',
        '1996-06-03'
    ),
    -- Aluno 11
    (
        UUID (),
        'Sophia Martins',
        '99001122330',
        'sophia@exemplo.com',
        '$2y$10$CasrKgPY81OfMYleTxRnZO3f8pyBnOHyqTO4aNogXdqwwabKJL6sm',
        '2001-10-25'
    ),
    -- Aluno 12
    (
        UUID (),
        'Gabriel Alves',
        '00112233440',
        'gabriel@exemplo.com',
        '$2y$10$CasrKgPY81OfMYleTxRnZO3f8pyBnOHyqTO4aNogXdqwwabKJL6sm',
        '2004-03-08'
    ),
    -- Aluno 13
    (
        UUID (),
        'Manuela Dias',
        '10203040500',
        'manuela@exemplo.com',
        '$2y$10$CasrKgPY81OfMYleTxRnZO3f8pyBnOHyqTO4aNogXdqwwabKJL6sm',
        '2000-01-01'
    ),
    -- Aluno 14
    (
        UUID (),
        'Lucas Morais',
        '21324354650',
        'lucas@exemplo.com',
        '$2y$10$CasrKgPY81OfMYleTxRnZO3f8pyBnOHyqTO4aNogXdqwwabKJL6sm',
        '1995-11-11'
    ),
    -- Aluno 15
    (
        UUID (),
        'Beatriz Castro',
        '32435465760',
        'beatriz@exemplo.com',
        '$2y$10$CasrKgPY81OfMYleTxRnZO3f8pyBnOHyqTO4aNogXdqwwabKJL6sm',
        '2002-05-07'
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