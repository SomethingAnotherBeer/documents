# documents
# Заголовки запроса

Для всех url в приложении определены следующие заголовки запроса:

"Content-Type: application/json"

"accept:json"

За исключением следующих url, требуется указать заголовок "authorization: <token_key>"

1) http://servername/api/registration

2) http://servername/api/login

3) http://servername/api/documents/all

# 1) Регистрация
url: http://servername/api/registration

Метод: POST

Формат тела запроса:
```
{
	"login": "user_login",
	"password": "user_password"
}
```
Формат ответа:

```
{
	"login":"user_login",
	"password":"user_password"
}
```
# 2) Аутентификация
## 2.1) Аутентификация

url: http:/servername/api/login

Метод: POST

Формат тела запроса:

```
{
	"login":"user_login",
	"password":"user_password"
}
```
формат ответа:
```
{
	"tokenKey":"your_token_key",
	"tokenUntill":int_timestamp
}
```

## 2.2) Выход из системы

url: http://servername/api/logout

Метод: POST

Формат ответа:
```
{
	"message":"Вы успешно вышли из системы"
}
```
# 3) Документ
## 3.1) Создание документа

url: http://servername/api/document/create

Метод: POST 

Формат тела запроса:
```
{
	"document_payload":
	{
		"actor": "The fox",
		"meta":
		{
			"type": "quick",
			"color": "brown"
		},

		"actions":
		[
			{
				"action": "jump over",
				"actor": "lazy dog"
			}
		]
	}
}
```
Формат ответа:
```
{	
	"documentKey":"your_document_key",
	"documentStatus":"draft",
	"documentPayload":
	{
		"actor":"The fox",
		"meta":
		{
			"type":"quick",
			"color":"brown"
		},
		"actions":
		[
			{
				"action":"jump over",
				"actor":"lazy dog"
			}
		]
	},
	"createAt":"2023-01-26 14:43:52",
	"modifyAt":"2023-01-26 14:43:52"
}
```

## 3.2) Редактирование документа

url: http://servername/document/{document_key}/patch

Метод: PATCH

Формат тела запроса:
```
{
	"document_payload":
	{
		"meta":
		{
			"type":"cunning",
			"color":null
		},

		"actions":
		[
			{
				"action":"eat",
				"actor":"blob"
			},
			{
				"action":"run away"
			}
		]
	}
}
```
Формат ответа:
```
{
	"documentKey":"your_document_key",
	"documentStatus":"draft",
	"documentPayload":
	{
		"actor":"The fox",
		"meta":
		{
			"type":"cunning"
		},
		"actions":
		[
			{
				"action":"eat",
				"actor":"blob"
			},
			{
				"action":"run away"
			}
		]
	},
	"createAt":"2023-01-26 14:43:52",
	"modifyAt":"2023-01-26 14:56:37"
}
```

## 3.3) Публикация документа

url: http://documentsone111/api/document/{document_key}/publish

Метод: POST

Формат ответа:
```
{
	"documentKey":"your_document_key",
	"documentStatus":"published",
	"documentPayload":
	{
		"actor":"The fox",
		"meta":
		{
			"type":"cunning"
		},
		"actions":
		[
			{
				"action":"eat",
				"actor":"blob"
			},
			{
				"action":"run away"
			}
		]
	},
	"createAt":"2023-01-26 14:43:52",
	"modifyAt":"2023-01-26 14:56:37"
}
```

## 3.4) Получить список всех документов

Данный метод осуществляет выборку всех опубликованных документов

url: http://servername/api/documents/get

Метод: GET

Параметры запроса: page (int)

Формат ответа:
```
{
	"documentItems":
	[
		{
			"documentKey":"your_document_key",
			"documentStatus":"published",
			"documentPayload":
			{
				"actor":"The fox",
				"meta":
				{
					"type":"cunning"
				},
				"actions":
				[
					{
						"action":"eat",
						"actor":"blob"
					},
					{
						"action":"run away"
					}
				]
			},
			"createAt":"2023-01-26 14:43:52",
			"modifyAt":"2023-01-26 14:56:37"
			}
	]
}
```

## 3.4) Получить мои документы

url: http://servername/api/documents/my

Метод: GET

Параметры запроса: page (int)

Формат ответа:
```
{
	"documentItems":
	[
		{
			"documentKey":"your_document_key",
			"documentStatus":"published",
			"documentPayload":
			{
				"actor":"The fox",
				"meta":
				{
					"type":"cunning"
				},
				"actions":
				[
					{
						"action":"eat",
						"actor":"blob"
					},
					{
						"action":"run away"
					}
				]
			},
			"createAt":"2023-01-26 14:43:52",
			"modifyAt":"2023-01-26 14:56:37"
			}
	]
}
```

## 3.5) Получить мои документы по статусу документов

url: http://servername/api/documents/my/{document_status}

Метод: GET

Параметры запроса: page (int)

Пример: http://servername/api/documents/my/published

Ответ:
```
{
	"documentItems":
	[
		{
			"documentKey":"1ai3l4vz-n590-c0g3-1ng0-36u2bwstyl71",
			"documentStatus":"published",
			"documentPayload":
			{
				"actor":"The fox",
				"meta":
				{
					"type":"cunning"
				},
				"actions":
				[
					{
						"action":"eat",
						"actor":"blob"
					},
					{
						"action":"run away"
					}
				]
			},
			"createAt":"2023-01-26 14:43:52",
			"modifyAt":"2023-01-26 14:56:37"
			}
	]
}
```

Пример: http://servername/api/documents/my/draft

Ответ:
```
{
	"documentItems":[]
}
```

## 3.6) Удалить документ

url: http://servername/api/document/{document_key}/delete

Метод: DELETE

Формат ответа:
```
{
	"documentKey":"your_document_key",
	"documentStatus":"published",
	"documentPayload":
	{
		"actor":"The fox",
		"meta":
		{
			"type":"cunning"
		},
		"actions":
		[
			{
				"action":"eat",
				"actor":"blob"
			},
			{
				"action":"run away"
			}
		]
	},
	"createAt":"2023-01-26 14:43:52",
	"modifyAt":"2023-01-26 14:56:37"
}
```
















