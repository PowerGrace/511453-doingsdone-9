USE doingsdone;

INSERT INTO projects (title, id_user)
VALUES 
('Входящие', 1),
('Учёба', 2),
('Работа', 2),
('Домашние дела', 1),
('Авто', 1);

INSERT INTO users (name_user, email, password)
VALUES
('Лиза', 'girl@mail.ru', '7777'),
('Иван', 'boy@mail.ru', '9999');

INSERT INTO tasks (status, name, deadline, id_name, id_user)
VALUES
(0, 'Собеседование в IT компании', '2019-12-01', 3, 2),
(0, 'Выполнить тестовое задание', '2018-12-25', 3, 2),
(1, 'Сделать задание первого раздела', '2018-12-21', 2, 2),
(0, 'Встреча с другом', null, 1, 1),
(0, 'Купить корм для кота', null, 4, 1),
(0, 'Заказать пиццу', null, 4, 1);

/*получить список из всех проектов для одного пользователя*/

SELECT name_user, title FROM projects 
JOIN users
ON projects.id_user = users.id_user 
WHERE projects.id_user = 1;

/* объединить проекты с задачами */

SELECT projects.title, COUNT(*) tasks
FROM projects
JOIN tasks
ON projects.id_name = tasks.id_name
GROUP BY projects.title;

/*получить список из всех задач для одного проекта*/

SELECT projects.title, tasks.name FROM projects
JOIN tasks
ON projects.id_name = tasks.id_name
WHERE projects.id_name = 4;

/*пометить задачу как выполненную*/

UPDATE tasks SET status = 1
WHERE id_task = 1;

/*обновить название задачи по её идентификатору*/

UPDATE tasks SET name = 'Новая задача'
WHERE id_task = 2;








