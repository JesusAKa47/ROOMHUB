import 'package:shared_preferences/shared_preferences.dart';

/// Guarda y lee el token. Mantén _token en memoria para poder leerlo de forma síncrona
/// en el API client (getToken). Añade en pubspec.yaml: shared_preferences: ^2.2.2
class TokenStorage {
  TokenStorage._();

  static const _key = 'roomhub_api_token';

  /// Token en memoria para lectura síncrona (actualízalo al cargar/guardar/borrar).
  static String? _token;

  static Future<void> init() async {
    final prefs = await SharedPreferences.getInstance();
    _token = prefs.getString(_key);
  }

  static Future<void> saveToken(String token) async {
    _token = token;
    final prefs = await SharedPreferences.getInstance();
    await prefs.setString(_key, token);
  }

  /// Síncrono: devuelve el token en memoria (llama init() al arranque de la app).
  static String? getToken() => _token;

  static Future<void> clearToken() async {
    _token = null;
    final prefs = await SharedPreferences.getInstance();
    await prefs.remove(_key);
  }
}
