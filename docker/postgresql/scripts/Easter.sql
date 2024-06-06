CREATE OR REPLACE FUNCTION Easter(Year integer)
RETURNS date
LANGUAGE plpgsql
IMMUTABLE
AS $$
DECLARE
g CONSTANT integer := Year % 19;
c CONSTANT integer := Year / 100;
h CONSTANT integer := (c - c/4 - (8*c + 13)/25 + 19*g + 15) % 30;
i CONSTANT integer := h - (h/28)*(1 - (h/28)*(29/(h + 1))*((21 - g)/11));
j CONSTANT integer := (Year + Year/4 + i + 2 - c + c/4) % 7;
p CONSTANT integer := i - j;
BEGIN
RETURN make_date(
  Year,
  3 + (p + 26)/30,
  1 + (p + 27 + (p + 6)/40) % 31
);
END;
$$;

CREATE OR REPLACE FUNCTION Easter_Nested_Subqueries(Year integer)
RETURNS DATE
LANGUAGE sql
STRICT
IMMUTABLE
AS $$
SELECT make_date(Year, easter_month, easter_day)
FROM (
  SELECT *,
    3 + (p + 26)/30 AS easter_month,
    1 + (p + 27 + (p + 6)/40) % 31 AS easter_day
  FROM (
    SELECT *,
      i - j AS p
    FROM (
      SELECT *,
      (Year + Year/4 + i + 2 - c + c/4) % 7 AS j
      FROM (
        SELECT *,
          h - (h/28)*(1 - (h/28)*(29/(h + 1))*((21 - g)/11)) AS i
        FROM (
          SELECT *,
            (c - c/4 - (8*c + 13)/25 + 19*g + 15) % 30 AS h
          FROM (
            SELECT
              Year % 19 AS g,
              Year / 100 AS c
          ) AS Q1
        ) AS Q2
      ) AS Q3
    ) AS Q4
  ) AS Q5
) AS Q6
$$;

CREATE OR REPLACE FUNCTION Easter_Lateral(Year integer)
RETURNS DATE
LANGUAGE sql
STRICT
IMMUTABLE
AS $$
SELECT make_date(Year, easter_month, easter_day)
FROM (VALUES (Year % 19, Year / 100)) AS Q1(g,c)
JOIN LATERAL (VALUES ((c - c/4 - (8*c + 13)/25 + 19*g + 15) % 30)) AS Q2(h) ON TRUE
JOIN LATERAL (VALUES (h - (h/28)*(1 - (h/28)*(29/(h + 1))*((21 - g)/11)))) AS Q3(i) ON TRUE
JOIN LATERAL (VALUES ((Year + Year/4 + i + 2 - c + c/4) % 7)) AS Q4(j) ON TRUE
JOIN LATERAL (VALUES (i - j)) AS Q5(p) ON TRUE
JOIN LATERAL (VALUES (3 + (p + 26)/30, 1 + (p + 27 + (p + 6)/40) % 31)) AS Q6(easter_month, easter_day) ON TRUE
$$;