import 'dart:convert';
import 'package:flutter/material.dart';
import 'package:frontend/models/property.dart';
import 'package:frontend/services/api_service.dart';

class PropertyProvider with ChangeNotifier {
  List<Property> _properties = [];
  bool _isLoading = false;

  List<Property> get properties => _properties;
  bool get isLoading => _isLoading;

  List<Property> get availableRooms =>
      _properties.where((p) => p.status == 'available').toList();

  Future<void> fetchProperties() async {
    _isLoading = true;
    notifyListeners();

    try {
      final response = await ApiService.get('properties');
      if (response.statusCode == 200) {
        final List<dynamic> data = jsonDecode(response.body);
        _properties = data.map((item) => Property.fromJson(item)).toList();
      }
    } catch (e) {
      print('Error fetching properties: $e');
    }

    _isLoading = false;
    notifyListeners();
  }

  Future<bool> createProperty(Property property) async {
    try {
      final response = await ApiService.post('properties', property.toJson());
      if (response.statusCode == 201) {
        await fetchProperties();
        return true;
      }
    } catch (e) {
      print('Error creating property: $e');
    }
    return false;
  }

  Future<bool> updateProperty(int id, Property property) async {
    try {
      final response = await ApiService.put(
        'properties/$id',
        property.toJson(),
      );
      if (response.statusCode == 200) {
        await fetchProperties();
        return true;
      }
    } catch (e) {
      print('Error updating property: $e');
    }
    return false;
  }

  Future<bool> deleteProperty(int id) async {
    try {
      final response = await ApiService.delete('properties/$id');
      if (response.statusCode == 200) {
        await fetchProperties();
        return true;
      }
    } catch (e) {
      print('Error deleting property: $e');
    }
    return false;
  }
}
