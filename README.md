<h1>Задание «Мультивалютный банковский счет»</h1>
Разработать класс (пакет классов), реализующих функционал банковского
мультивалютного счета.<br><br>
<b>Требования к реализации:</b>b><br>
1. Мультивалютный счет обслуживает сбережения в нескольких валютах, которые
можно подключать и отключать от счета динамически. Пример валют:
российский рубль (RUB), доллар США (USD), евро (EUR).<br>
2. Одна из валют счета является основной (для разных счетов может быть
разной).<br>
3. Мультивалютный счет поддерживает операции пополнения/списания в
конкретной валюте.<br>
4. При попытке списания в конкретной валюте, нельзя списать больше количества
этой валюты на счете. Например, на счете 1000 RUB и 10 USD, при попытке
списать 11 USD, нельзя списать 10 USD из долларовой части счета, а
оставшуюся с рублевой.<br>
5. Курс валют со временем может изменяться. Базовый курс валют: EUR/RUB =
80, USD/RUB = 70, EUR/USD = 1<br>
6. Клиент должен иметь возможность изменить основную валюту счета.<br><br>
<b>Требования к коду:<b/><br>
1. Использование ООП, и дробление на функциональные классы.<br>
2. Использование типизации (можно использовать все возможности PHP 8.1).<br>
3. Основная бизнес-логика должна быть покрыта тестами.<br>
4. Соответствие кода PHP Standards Recommendations.<br><br>

<b>ПРИМЕЧАНИЯ:</b><br>
Не требуется использование фреймворков и БД<br>
Не требуется обеспечение 100% работоспособности кода (не будем его запускать и смотреть на результат выполнения)<br>
Важны только модели и их поведения с архитектурной точки зрения<br>
Тесты необязательны<br>
Для тестов можно использовать готовые инструменты (phpunit, codeception, или любой другой)<br>
