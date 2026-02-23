# Copiar a tu proyecto Flutter (roomhub)

## 1. Dependencias en `pubspec.yaml`

```yaml
dependencies:
  http: ^1.2.0
  shared_preferences: ^2.2.2
  # ... las que ya tengas (provider, flutter_localizations, intl, etc.)
```

Luego ejecuta: `flutter pub get`

---

## 2. Archivos que debes tener (copia desde esta carpeta)

Asegúrate de tener esta estructura en tu proyecto y **sustituye** los archivos que ya tienes por los de esta carpeta:

| En tu proyecto (roomhub) | Copiar desde flutter_integration |
|--------------------------|----------------------------------|
| `lib/config/api_config.dart` | `lib/config/api_config.dart` (nuevo) |
| `lib/models/user_model.dart` | `lib/models/user_model.dart` |
| `lib/services/roomhub_api.dart` | `lib/services/roomhub_api.dart` |
| `lib/storage/token_storage.dart` | `lib/storage/token_storage.dart` |
| `lib/providers/auth_provider.dart` | `lib/providers/auth_provider.dart` |
| `lib/main.dart` | `lib/main.dart` |
| `lib/screens/login_screen.dart` | `lib/screens/login_screen.dart` |
| `lib/screens/register_screen.dart` | `lib/screens/register_screen.dart` |
| `lib/screens/auth_gate.dart` | `lib/screens/auth_gate.dart` |

---

## 3. Crear carpetas si no existen

- `lib/config/`
- `lib/models/`
- `lib/services/`
- `lib/storage/`

---

## 4. URL del backend

Edita **`lib/config/api_config.dart`** según donde corra tu Laravel:

- **Emulador Android:** `http://10.0.2.2:8000`
- **iOS Simulator / mismo PC:** `http://127.0.0.1:8000`
- **Dispositivo físico en la misma red:** `http://TU_IP:8000` (ej. `http://192.168.1.10:8000`)

---

## 5. Pantalla principal tras el login

En **`lib/screens/auth_gate.dart`** la variable `homeWidget` es un ejemplo. Sustituye `homeWidget` por tu pantalla real (por ejemplo tu `HomeScreen` o el widget que tenga el bottom navigation):

```dart
Widget get homeWidget => const HomeScreen();  // o tu pantalla principal
```

---

## Resumen de cambios

- **main.dart:** Se añade `await TokenStorage.init();` antes de `runApp`.
- **auth_provider.dart:** Usa la API real (login con email + contraseña, logout, loadUser) y mantiene `isLoggedIn`, `userName`, `role` para el resto de la app.
- **login_screen.dart:** Envía email y contraseña a `auth.login(email, password)`, muestra carga y errores con SnackBar.
- **auth_gate.dart:** Al abrir la app intenta cargar el usuario si hay token; si está logueado muestra `homeWidget`, si no muestra `LoginScreen`.
