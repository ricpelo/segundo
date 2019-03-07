DROP TABLE IF EXISTS usuarios CASCADE;

CREATE TABLE usuarios
(
    id       BIGSERIAL    PRIMARY KEY
  , nombre   VARCHAR(255) NOT NULL UNIQUE
  , email    VARCHAR(255) NOT NULL UNIQUE
  , password VARCHAR(60)  NOT NULL
);

DROP TABLE IF EXISTS especialidades CASCADE;

CREATE TABLE especialidades
(
    id           BIGSERIAL    PRIMARY KEY
  , especialidad VARCHAR(255) NOT NULL
);

DROP TABLE IF EXISTS especialistas CASCADE;

CREATE TABLE especialistas
(
    id              BIGSERIAL    PRIMARY KEY
  , nombre          VARCHAR(255) NOT NULL
  , especialidad_id BIGINT       NOT NULL REFERENCES especialidades (id)
  , hora_minima     TIME         NOT NULL
  , hora_maxima     TIME         NOT NULL
  , duracion        INTERVAL     NOT NULL
);

DROP TABLE IF EXISTS citas CASCADE;

CREATE TABLE citas
(
    id              BIGSERIAL PRIMARY KEY
  , usuario_id      BIGINT    NOT NULL REFERENCES usuarios (id)
  , especialista_id BIGINT    NOT NULL REFERENCES especialistas (id)
  , instante        TIMESTAMP NOT NULL
);

INSERT INTO usuarios (nombre, email, password)
VALUES ('pepe', 'pepe@gmail.com', crypt('pepe', gen_salt('bf', 10)))
     , ('juan', 'juan@gmail.com', crypt('juan', gen_salt('bf', 10)));

INSERT INTO especialidades (especialidad)
VALUES ('Oftalmología')
     , ('Urología');

INSERT INTO especialistas (nombre, especialidad_id, hora_minima, hora_maxima, duracion)
VALUES ('Juan Pastor', 1, '15:00', '20:00', 'PT15M')
     , ('Francisco Reyes', 2, '17:00', '20:30', 'PT10M')
     , ('Antonio Díaz', 1, '16:00', '21:00', 'PT20M')
     , ('Francisco Montaño', 2, '17:00', '22:00', 'PT15M');

INSERT INTO citas (usuario_id, especialista_id, instante)
VALUES (1, 1, '2019-03-08 15:00:00')
     , (2, 1, '2019-03-08 15:15:00')
     , (1, 2, '2019-03-08 17:00:00');
