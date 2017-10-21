# Backend Developer  Post-interview test


This project was created by Georgios Lymperopoulos and holds the code for the Post-interview Test
for the backend developer position.

## Setup the Project

1. Make sure you have [Composer installed](https://getcomposer.org/).

2. Install the composer dependencies:

```bash
composer install
```

3. Start up the built-in PHP web server: (use PHP >= 7.0)

```bash
php bin/console server:run
```
### Task 1

Then find the two endpoints at:

#### Endpoint 1

```bash
http://localhost:8000/most_words
```

Required Params:
```bash
{
	"sentence": String,
	"output_language": String,
	"max_characters": Int
}
```

JSON Response:
```bash
{
    "original_sentence": String,
    "final_string": String,
    "final_string_translated": String,
    "duration_ms": Float
}
```

#### Endpoint 2

```bash
http://localhost:8000/best_strategy
```
Required Params:

```bash
{
	"game_state": Array
}
```
JSON Response:
```bash
{
    "direction_choice": Array,
    "value": Array,
    "array_index": Array,
    "duration_ms": Float
}
```

### Task 2

Logging has been added to the application.

For endpoint 1 the log file can be found in:
```bash
Root_Folder/api_logs/most_words_current_date.txt
```
For endpoint 2 the log file can be found in:
```bash
Root_Folder/api_logs/best_strategy_current_date.txt
```

### Task 3

For endpoint 1 the testing controller can be found in:
```bash
WordsControllerTest.php
```
For endpoint 2 the testing controller can be found in:
```bash
StrategyControllerTest.php
```

### Task 4

A technical documentation pdf has been created which can be found in:
```bash
other/API_Doc - folder
```

### JavaScript Task 1
A script has been created which can be found in:
```bash
other/JavaScript_Task_1 - folder
```