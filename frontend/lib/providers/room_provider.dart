import 'package:flutter/material.dart';
import 'package:frontend/models/room.dart';
import 'package:frontend/services/api_service.dart';
import 'dart:convert';

class RoomProvider with ChangeNotifier {
  List<Room> _rooms = [];
  bool _isLoading = false;

  List<Room> get rooms => _rooms;
  bool get isLoading => _isLoading;

  Future<void> fetchRooms({int? propertyId}) async {
    _isLoading = true;
    notifyListeners();

    try {
      final endpoint = propertyId != null 
          ? 'rooms?property_id=$propertyId' 
          : 'rooms';
          
      final response = await ApiService.get(endpoint);

      if (response.statusCode == 200) {
        final Map<String, dynamic> responseData = json.decode(response.body);
        if (responseData['success'] == true) {
          final List<dynamic> data = responseData['data'];
          _rooms = data.map((json) => Room.fromJson(json)).toList();
        }
      }
    } catch (e) {
      debugPrint('Error fetching rooms: $e');
    } finally {
      _isLoading = false;
      notifyListeners();
    }
  }

  Future<bool> addRoom(Room room) async {
    try {
      final response = await ApiService.post('rooms', room.toJson());

      if (response.statusCode == 201) {
        final Map<String, dynamic> responseData = json.decode(response.body);
        if (responseData['success'] == true) {
          final newRoom = Room.fromJson(responseData['data']);
          _rooms.insert(0, newRoom);
          notifyListeners();
          return true;
        }
      }
      return false;
    } catch (e) {
      debugPrint('Error adding room: $e');
      return false;
    }
  }

  Future<bool> updateRoom(Room room) async {
    try {
      final response = await ApiService.put('rooms/${room.id}', room.toJson());

      if (response.statusCode == 200) {
        final Map<String, dynamic> responseData = json.decode(response.body);
        if (responseData['success'] == true) {
          final updatedRoom = Room.fromJson(responseData['data']);
          final index = _rooms.indexWhere((r) => r.id == room.id);
          if (index != -1) {
            _rooms[index] = updatedRoom;
            notifyListeners();
          }
          return true;
        }
      }
      return false;
    } catch (e) {
      debugPrint('Error updating room: $e');
      return false;
    }
  }

  Future<bool> deleteRoom(int id) async {
    try {
      final response = await ApiService.delete('rooms/$id');

      if (response.statusCode == 200) {
        _rooms.removeWhere((room) => room.id == id);
        notifyListeners();
        return true;
      }
      return false;
    } catch (e) {
      debugPrint('Error deleting room: $e');
      return false;
    }
  }
}
