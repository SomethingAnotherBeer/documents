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

