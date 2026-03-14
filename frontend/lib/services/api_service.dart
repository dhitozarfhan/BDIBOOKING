import 'dart:convert';
import 'package:http/http.dart' as http;
import 'package:flutter/foundation.dart';
import 'dart:io' show Platform;
import 'package:shared_preferences/shared_preferences.dart';

class ApiService {
  static String get baseUrl {
    if (kIsWeb) {
      return 'http://localhost:8000/api';
    }
    try {
      if (Platform.isAndroid) {
        return 'http://10.0.2.2:8000/api';
      }
    } catch (e) {
      // Platform.isAndroid might throw on web
    }
    return 'http://127.0.0.1:8000/api';
  }

  static Future<Map<String, String>> getHeaders() async {
    final prefs = await SharedPreferences.getInstance();
    final token = prefs.getString('auth_token');

    return {
      'Content-Type': 'application/json',
      'Accept': 'application/json',
      'X-API-KEY': 'BDI_APK_USER_9x8d7f6e5w4q3a2z1s',
      if (token != null) 'Authorization': 'Bearer $token',
    };
  }

  static Future<http.Response> get(String endpoint) async {
    final headers = await getHeaders();
    // Use v1 for specific endpoints if needed, or update baseUrl
    String path = endpoint;
    if ([
      'properties',
      'rooms',
      'bookings',
    ].contains(endpoint.split('/').first)) {
      path = 'v1/$endpoint';
    }

    try {
      return await http
          .get(Uri.parse('$baseUrl/$path'), headers: headers)
          .timeout(const Duration(seconds: 10));
    } catch (e) {
      return http.Response(
        jsonEncode({'message': 'Connection Error: $e'}),
        500,
      );
    }
  }

  static Future<http.Response> post(
    String endpoint,
    Map<String, dynamic> body,
  ) async {
    final headers = await getHeaders();
    try {
      return await http
          .post(
            Uri.parse('$baseUrl/$endpoint'),
            headers: headers,
            body: jsonEncode(body),
          )
          .timeout(const Duration(seconds: 10));
    } catch (e) {
      // Return a 500-like response so the app doesn't hang
      return http.Response(
        jsonEncode({'message': 'Connection Error: $e'}),
        500,
      );
    }
  }

  static Future<http.Response> put(
    String endpoint,
    Map<String, dynamic> body,
  ) async {
    final headers = await getHeaders();
    try {
      return await http
          .put(
            Uri.parse('$baseUrl/$endpoint'),
            headers: headers,
            body: jsonEncode(body),
          )
          .timeout(const Duration(seconds: 10));
    } catch (e) {
      return http.Response(
        jsonEncode({'message': 'Connection Error: $e'}),
        500,
      );
    }
  }

  static Future<http.Response> delete(String endpoint) async {
    final headers = await getHeaders();
    try {
      return await http
          .delete(Uri.parse('$baseUrl/$endpoint'), headers: headers)
          .timeout(const Duration(seconds: 10));
    } catch (e) {
      return http.Response(
        jsonEncode({'message': 'Connection Error: $e'}),
        500,
      );
    }
  }
}
