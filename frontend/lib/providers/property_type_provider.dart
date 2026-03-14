import 'dart:convert';
import 'package:flutter/material.dart';
import 'package:frontend/models/property_type.dart';
import 'package:frontend/services/api_service.dart';

class PropertyTypeProvider with ChangeNotifier {
  List<PropertyType> _propertyTypes = [];
  bool _isLoading = false;

  List<PropertyType> get propertyTypes => _propertyTypes;
  bool get isLoading => _isLoading;

  Future<void> fetchPropertyTypes() async {
    _isLoading = true;
    notifyListeners();

    try {
      final response = await ApiService.get('v1/property-typesAPK');
      if (response.statusCode == 200) {
        final List<dynamic> data = jsonDecode(response.body);
        _propertyTypes = data
            .map((item) => PropertyType.fromJson(item))
            .toList();
      }
    } catch (e) {
      print('Error fetching property types: $e');
    }

    _isLoading = false;
    notifyListeners();
  }

  Future<bool> createPropertyType(PropertyType type) async {
    try {
      final response = await ApiService.post(
        'v1/property-typesAPK',
        type.toJson(),
      );
      if (response.statusCode == 201) {
        await fetchPropertyTypes();
        return true;
      }
    } catch (e) {
      print('Error creating property type: $e');
    }
    return false;
  }

  Future<bool> updatePropertyType(int id, PropertyType type) async {
    try {
      final response = await ApiService.put(
        'v1/property-typesAPK/$id',
        type.toJson(),
      );
      if (response.statusCode == 200) {
        await fetchPropertyTypes();
        return true;
      }
    } catch (e) {
      print('Error updating property type: $e');
    }
    return false;
  }

  Future<bool> deletePropertyType(int id) async {
    try {
      final response = await ApiService.delete('v1/property-typesAPK/$id');
      if (response.statusCode == 200) {
        await fetchPropertyTypes();
        return true;
      }
    } catch (e) {
      print('Error deleting property type: $e');
    }
    return false;
  }
}
