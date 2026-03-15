import 'dart:convert';
import 'package:frontend/models/user.dart';
import 'package:frontend/services/api_service.dart';
import 'package:shared_preferences/shared_preferences.dart';

class AuthService {
  Future<Map<String, dynamic>> login(String email, String password) async {
    final response = await ApiService.post('loginAPK', {
      'username': email, // AuthController expects 'username' based on user's manual edit
      'password': password,
    });

    if (response.statusCode == 200) {
      final data = jsonDecode(response.body);
      // Unwrapping 'data' from the success response
      final responseData = data['data'];
      final token = responseData['token'];
      final user = User.fromJson(responseData['user']);

      final prefs = await SharedPreferences.getInstance();
      await prefs.setString('auth_token', token);
      await prefs.setString('user', jsonEncode(user.toJson()));

      return {'success': true, 'user': user};
    } else {
      final errorData = jsonDecode(response.body);
      return {
        'success': false,
        'message': errorData['message'] ?? 'Login failed',
      };
    }
  }

  Future<void> logout() async {
    await ApiService.post('logout', {});
    final prefs = await SharedPreferences.getInstance();
    await prefs.remove('auth_token');
    await prefs.remove('user');
  }

  Future<User?> getCurrentUser() async {
    final prefs = await SharedPreferences.getInstance();
    final userStr = prefs.getString('user');
    if (userStr != null) {
      return User.fromJson(jsonDecode(userStr));
    }
    return null;
  }

  static Future<String?> getToken() async {
    final prefs = await SharedPreferences.getInstance();
    return prefs.getString('auth_token');
  }
}
