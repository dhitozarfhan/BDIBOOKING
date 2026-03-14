import 'dart:convert';
import 'package:flutter/material.dart';
import 'package:frontend/models/booking.dart';
import 'package:frontend/services/api_service.dart';

class BookingProvider with ChangeNotifier {
  List<Booking> _bookings = [];
  bool _isLoading = false;

  List<Booking> get bookings => _bookings;
  bool get isLoading => _isLoading;

  Future<void> fetchBookings() async {
    _isLoading = true;
    notifyListeners();

    try {
      final response = await ApiService.get('v1/bookingsAPK');
      if (response.statusCode == 200) {
        final List<dynamic> data = jsonDecode(response.body);
        _bookings = data.map((item) => Booking.fromJson(item)).toList();
      }
    } catch (e) {
      print('Error fetching bookings: $e');
    }

    _isLoading = false;
    notifyListeners();
  }

  Future<bool> createBooking(Booking booking) async {
    try {
      final response = await ApiService.post(
        'v1/bookingsAPK',
        booking.toJson(),
      );
      if (response.statusCode == 201) {
        await fetchBookings();
        return true;
      } else {
        print('Create booking failed: ${response.body}');
      }
    } catch (e) {
      print('Error creating booking: $e');
    }
    return false;
  }

  Future<bool> updateBooking(int id, Booking booking) async {
    try {
      final response = await ApiService.put(
        'v1/bookingsAPK/$id',
        booking.toJson(),
      );
      if (response.statusCode == 200) {
        await fetchBookings();
        return true;
      }
    } catch (e) {
      print('Error updating booking: $e');
    }
    return false;
  }

  Future<bool> deleteBooking(int id) async {
    try {
      final response = await ApiService.delete('v1/bookingsAPK/$id');
      if (response.statusCode == 200) {
        await fetchBookings();
        return true;
      }
    } catch (e) {
      print('Error deleting booking: $e');
    }
    return false;
  }

  Future<bool> cancelBooking(int id) async {
    try {
      final response = await ApiService.put('v1/bookingsAPK/$id', {
        'status': 'cancelled',
      });
      if (response.statusCode == 200) {
        await fetchBookings();
        return true;
      }
    } catch (e) {
      print('Error cancelling booking: $e');
    }
    return false;
  }
}
