ALTER TABLE fecho_valor
    ADD COLUMN campos_guardados VARCHAR(255) NOT NULL DEFAULT '';

UPDATE fecho_valor
SET campos_guardados = 'moeda_10,moeda_20,moeda_50,moeda_100,moeda_200,dinheiro_200,dinheiro_500,dinheiro_1000,dinheiro_2000,dinheiro_5000,tpa,notas'
WHERE campos_guardados = '';
