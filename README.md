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
	"login":"someuserone",
	"password":"111222333444"
}
```




