CREATE TABLE IF NOT EXISTS rates (
    id VARCHAR(45) DEFAULT (UUID()) PRIMARY KEY,
    base_currency VARCHAR(3) NOT NULL,
    converting_currency VARCHAR(3) NOT NULL,
    value FLOAT NOT NULL,
    date_created TIMESTAMP DEFAULT (NOW())
);