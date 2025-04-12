# Лабораторная работа №5. Работа с базой данных

## Студент
**Gachayev Dmitrii I2302**  
**Выполнено 11.04.2025**  

## Цель работы
Освоить архитектуру с единой точкой входа, подключение шаблонов для визуализации страниц, а также переход от хранения данных в файле к использованию базы данных (MySQL).

## Задание 1. Подготовка среды
Для работы буду использовать субд `PostgreSQL`. 

1. Создаю базу данных `recipe_book` через `pgAdmin`:

![image](screenshots/Screenshot_1.png)

2. Создаю таблицу `recipes` (запрос переделал под `postgresql`)
```sql
CREATE TABLE recipes (
  id SERIAL PRIMARY KEY,
  title VARCHAR(255) NOT NULL,
  category INT NOT NULL,
  ingredients TEXT,
  description TEXT,
  tags TEXT,
  steps TEXT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (category) REFERENCES categories(id) ON DELETE CASCADE
);
```

3. Далее создаю таблицу `categories` (запрос переделал под `postgresql`)
```sql
CREATE TABLE categories (
  id SERIAL PRIMARY KEY,
  name VARCHAR(100) NOT NULL UNIQUE,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

4. Создаю структуру проекта, ссылаясь на пример. Выходит следующим образом:

![image](screenshots/Screenshot_2.png)