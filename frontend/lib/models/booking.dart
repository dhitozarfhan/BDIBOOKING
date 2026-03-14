import 'package:frontend/models/property.dart';
import 'package:frontend/models/room.dart';
import 'package:frontend/models/user.dart';

class Booking {
  final int? id;
  final String? idBooking;
  final int? customerId;
  final int? propertyId;
  final String? contactName;
  final String? contactEmail;
  final String? contactPhone;
  final String? institution;
  final String? bookingType;
  final int? quantity;
  final String status;
  final DateTime startDate;
  final DateTime endDate;
  final Property? property;
  final Room? room;
  final User? user;

  Booking({
    this.id,
    this.idBooking,
    this.customerId,
    this.propertyId,
    this.contactName,
    this.contactEmail,
    this.contactPhone,
    this.institution,
    this.bookingType,
    this.quantity,
    required this.status,
    required this.startDate,
    required this.endDate,
    this.property,
    this.room,
    this.user,
  });

  factory Booking.fromJson(Map<String, dynamic> json) {
    return Booking(
      id: json['id'],
      idBooking: json['id_booking'],
      customerId: json['customer_id'],
      propertyId:
          json['property_id'] ??
          (json['property'] != null ? json['property']['id'] : null),
      contactName: json['contact_name'],
      contactEmail: json['contact_email'],
      contactPhone: json['contact_phone'],
      institution: json['institution'],
      bookingType: json['booking_type'],
      quantity: json['quantity'],
      status: json['status'],
      startDate: DateTime.parse(json['start_date']),
      endDate: DateTime.parse(json['end_date']),
      property: json['property'] != null
          ? Property.fromJson(json['property'])
          : null,
      room: json['room'] != null ? Room.fromJson(json['room']) : null,
      user: json['user'] != null ? User.fromJson(json['user']) : null,
    );
  }

  Map<String, dynamic> toJson() {
    return {
      'status': status,
      'start_date': startDate.toIso8601String(),
      'end_date': endDate.toIso8601String(),
    };
  }
}
