
-- Modelo Relacional: Tabela clientes

CREATE DATABASE IF NOT EXISTS con23128_clientes;
USE con23128_clientes;

DROP TABLE IF EXISTS clientes;

CREATE TABLE clientes (
  id INT(11) NOT NULL AUTO_INCREMENT,
  nome VARCHAR(100) NOT NULL,
  email VARCHAR(100) NOT NULL,
  telefone VARCHAR(20),
  imagem VARCHAR(255),
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id)
);
