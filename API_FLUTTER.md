# API de RoomHub para Flutter

## Base URL

```
http://tu-servidor.com/api
```

En desarrollo local:
```
http://127.0.0.1:8000/api
```

---

## Autenticación

### POST `/api/register`

Registro de usuario. Devuelve token y user (queda logueado).

**Body (JSON):**
```json
{
  "name": "Tu nombre",
  "email": "usuario@ejemplo.com",
  "password": "tu_password",
  "password_confirmation": "tu_password",
  "device_name": "flutter-app"
}
```

**Respuesta 201:** Igual que login (token, token_type, expires_in, user).

**Errores:** `422` con mensaje de validación (email duplicado, contraseña corta, etc.).

---

### POST `/api/login`

Iniciar sesión y obtener token.

**Body (JSON):**
```json
{
  "email": "usuario@ejemplo.com",
  "password": "tu_password",
  "device_name": "flutter-app"
}
```

- `device_name` es opcional (por defecto: "flutter-app").

**Respuesta 200:**
```json
{
  "token": "abc123...",
  "token_type": "Bearer",
  "expires_in": 2592000,
  "user": {
    "id": 1,
    "name": "Usuario",
    "email": "usuario@ejemplo.com",
    "role": "client",
    "status": "active",
    "owner_id": null,
    "client_id": 1,
    "is_admin": false,
    "is_owner": false,
    "is_client": true
  }
}
```

**Errores:**
- `401`: Credenciales incorrectas
- `403`: Cuenta suspendida
- `429`: Demasiados intentos (rate limit)

---

### POST `/api/logout`

Cerrar sesión y revocar el token actual.

**Headers:**
```
Authorization: Bearer {tu_token}
```

**Respuesta 200:**
```json
{
  "message": "Sesión cerrada correctamente."
}
```

---

### GET `/api/user`

Obtener el usuario autenticado.

**Headers:**
```
Authorization: Bearer {tu_token}
```

**Respuesta 200:**
```json
{
  "user": {
    "id": 1,
    "name": "Usuario",
    "email": "usuario@ejemplo.com",
    "role": "client",
    "status": "active",
    "owner_id": null,
    "client_id": 1,
    "is_admin": false,
    "is_owner": false,
    "is_client": true
  }
}
```

---

## Uso en Flutter

1. Guarda el `token` después del login (SharedPreferences o similar).
2. En cada petición a la API, añade el header:
   ```
   Authorization: Bearer {token}
   ```
3. Si recibes `401`, redirige al login y borra el token almacenado.
4. Llama a `/api/logout` antes de cerrar sesión para revocar el token en el servidor.

### Ejemplo con http/dio

```dart
// Login
final response = await http.post(
  Uri.parse('$baseUrl/api/login'),
  headers: {'Content-Type': 'application/json'},
  body: jsonEncode({
    'email': email,
    'password': password,
  }),
);
final data = jsonDecode(response.body);
final token = data['token'];
final user = data['user'];

// Peticiones autenticadas
final authResponse = await http.get(
  Uri.parse('$baseUrl/api/user'),
  headers: {
    'Authorization': 'Bearer $token',
    'Content-Type': 'application/json',
  },
);
```

---

## Seguridad

- Los tokens expiran en **30 días**.
- No compartas el token; es secreto como una contraseña.
- Usa **HTTPS** en producción.
