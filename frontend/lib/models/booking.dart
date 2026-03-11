import 'package:frontend/models/property.dart';
import 'package:frontend/models/user.dart';

class Booking {
  final int? id;
  final int propertyId;
  final int? userId;
  final String contactName;
  final String contactEmail;
  final String contactPhone;
  final String? institution;
  final DateTime startDate;
  final DateTime endDate;
  final String status;
  final Property? property;
  final User? user;

  Booking({
    this.id,
    required this.propertyId,
    this.userId,
    required this.contactName,
    required this.contactEmail,
    required this.contactPhone,
    this.institution,
    required this.startDate,
    required this.endDate,
    required this.status,
    this.property,
    this.user,
  });

  factory Booking.fromJson(Map<String, dynamic> json) {
    return Booking(
      id: json['id'],
      propertyId: json['property_id'],
      userId: json['user_id'],
      contactName: json['contact_name'],
      contactEmail: json['contact_email'],
      contactPhone: json['contact_phone'],
      institution: json['institution'],
      startDate: DateTime.parse(json['start_date']),
      endDate: DateTime.parse(json['end_date']),
      status: json['status'],
      property: json['property'] != null
          ? Property.fromJson(json['property'])
          : null,
      user: json['user'] != null ? User.fromJson(json['user']) : null,
    );
  }

  Map<String, dynamic> toJson() {
    return {
      'property_id': propertyId,
      'contact_name': contactName,
      'contact_email': contactEmail,
      'contact_phone': contactPhone,
      'institution': institution,
      'start_date': startDate.toIso8601String(),
      'end_date': endDate.toIso8601String(),
      'status': status,
    };
  }
}
