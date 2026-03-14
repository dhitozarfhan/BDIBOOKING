import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import 'package:intl/intl.dart';
import 'package:google_fonts/google_fonts.dart';
import 'package:flutter_animate/flutter_animate.dart';
import 'package:frontend/models/property.dart';
import 'package:frontend/models/booking.dart';
import 'package:frontend/providers/booking_provider.dart';

class RoomDetailScreen extends StatefulWidget {
  final Property property;

  const RoomDetailScreen({Key? key, required this.property}) : super(key: key);

  @override
  _RoomDetailScreenState createState() => _RoomDetailScreenState();
}

class _RoomDetailScreenState extends State<RoomDetailScreen> {
  @override
  void initState() {
    super.initState();
    WidgetsBinding.instance.addPostFrameCallback((_) {
      Provider.of<BookingProvider>(context, listen: false).fetchBookings();
    });
  }

  Widget _buildStatusBadge(String status) {
    Color bg;
    Color textColors;
    String label = status.toUpperCase();

    if (status == 'available') {
      bg = const Color(0xFFDCFCE7); // green-100
      textColors = const Color(0xFF166534); // green-800
    } else if (status == 'occupied') {
      bg = const Color(0xFFFEE2E2); // red-100
      textColors = const Color(0xFF991B1B); // red-800
    } else {
      bg = const Color(0xFFFFEDD5); // orange-100
      textColors = const Color(0xFF9A3412); // orange-800
    }

    return Container(
      padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 8),
      decoration: BoxDecoration(
        color: bg,
        borderRadius: BorderRadius.circular(20),
      ),
      child: Text(
        label,
        style: GoogleFonts.inter(
          fontSize: 14,
          fontWeight: FontWeight.bold,
          color: textColors,
        ),
      ),
    );
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: const Color(0xFFF8FAFC),
      appBar: AppBar(title: Text('Room Details', style: GoogleFonts.poppins())),
      body: SingleChildScrollView(
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.stretch,
          children: [
            // Header Info Card
            Container(
              color: Colors.white,
              padding: const EdgeInsets.all(24),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Row(
                    mainAxisAlignment: MainAxisAlignment.spaceBetween,
                    children: [
                      Expanded(
                        child: Text(
                          widget.property.name,
                          style: GoogleFonts.poppins(
                            fontSize: 28,
                            fontWeight: FontWeight.bold,
                            color: const Color(0xFF1E293B),
                          ),
                        ),
                      ),
                      _buildStatusBadge(widget.property.status),
                    ],
                  ),
                  const SizedBox(height: 8),
                  if (widget.property.description != null &&
                      widget.property.description!.isNotEmpty) ...[
                    Text(
                      widget.property.description!,
                      style: GoogleFonts.inter(
                        color: Colors.grey.shade700,
                        height: 1.5,
                      ),
                    ),
                    const SizedBox(height: 16),
                  ],
                  Row(
                    children: [
                      const Icon(Icons.groups, color: Colors.grey),
                      const SizedBox(width: 8),
                      Text(
                        'Maximum Capacity: ${widget.property.capacity} Pax',
                        style: GoogleFonts.inter(
                          fontSize: 15,
                          fontWeight: FontWeight.w500,
                        ),
                      ),
                    ],
                  ),
                ],
              ),
            ).animate().fadeIn(duration: 400.ms).slideY(begin: -0.05),

            // Booking History
            Padding(
              padding: const EdgeInsets.all(24.0),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Text(
                    'Booking History & Schedule',
                    style: GoogleFonts.poppins(
                      fontSize: 20,
                      fontWeight: FontWeight.bold,
                      color: const Color(0xFF1E293B),
                    ),
                  ),
                  const SizedBox(height: 16),

                  Consumer<BookingProvider>(
                    builder: (ctx, provider, _) {
                      if (provider.isLoading) {
                        return const Center(
                          child: Padding(
                            padding: EdgeInsets.all(32),
                            child: CircularProgressIndicator(),
                          ),
                        );
                      }

                      // Filter bookings only for this specific property
                      final List<Booking> roomBookings = provider.bookings
                          .where((b) => b.propertyId == widget.property.id)
                          .toList();

                      // Sort by date (newest/upcoming first)
                      roomBookings.sort(
                        (a, b) => b.startDate.compareTo(a.startDate),
                      );

                      if (roomBookings.isEmpty) {
                        return Container(
                          padding: const EdgeInsets.all(32),
                          decoration: BoxDecoration(
                            color: Colors.white,
                            borderRadius: BorderRadius.circular(16),
                            border: Border.all(color: Colors.grey.shade200),
                          ),
                          child: Center(
                            child: Column(
                              children: [
                                Icon(
                                  Icons.event_available,
                                  size: 60,
                                  color: Colors.grey.shade300,
                                ),
                                const SizedBox(height: 16),
                                Text(
                                  'No Booking History Found',
                                  style: GoogleFonts.inter(
                                    fontSize: 16,
                                    color: Colors.grey.shade600,
                                    fontWeight: FontWeight.w500,
                                  ),
                                ),
                                const SizedBox(height: 8),
                                Text(
                                  'This room has not been reserved yet.',
                                  style: GoogleFonts.inter(
                                    fontSize: 14,
                                    color: Colors.grey.shade500,
                                  ),
                                ),
                              ],
                            ),
                          ),
                        ).animate().fadeIn(delay: 200.ms);
                      }

                      return ListView.builder(
                        shrinkWrap: true,
                        physics: const NeverScrollableScrollPhysics(),
                        itemCount: roomBookings.length,
                        itemBuilder: (ctx, index) {
                          final booking = roomBookings[index];
                          final isCancelled = booking.status == 'cancelled';
                          final isPast =
                              booking.endDate.isBefore(DateTime.now()) &&
                              !isCancelled;

                          Color cardBorderColor = Colors.transparent;
                          if (isCancelled)
                            cardBorderColor = Colors.red.shade200;
                          else if (isPast)
                            cardBorderColor = Colors.grey.shade300;
                          else
                            cardBorderColor = const Color(
                              0xFF3B82F6,
                            ).withOpacity(0.5); // Upcoming

                          return Container(
                                margin: const EdgeInsets.only(bottom: 12),
                                decoration: BoxDecoration(
                                  color: isCancelled
                                      ? const Color(0xFFFAFAFA)
                                      : Colors.white,
                                  borderRadius: BorderRadius.circular(12),
                                  border: Border.all(color: cardBorderColor),
                                  boxShadow: [
                                    BoxShadow(
                                      color: Colors.black.withOpacity(0.02),
                                      blurRadius: 4,
                                      offset: const Offset(0, 2),
                                    ),
                                  ],
                                ),
                                child: ListTile(
                                  contentPadding: const EdgeInsets.all(16),
                                  title: Row(
                                    mainAxisAlignment:
                                        MainAxisAlignment.spaceBetween,
                                    children: [
                                      Text(
                                        '${booking.contactName} (${booking.institution ?? 'Individual'})',
                                        style: GoogleFonts.poppins(
                                          fontWeight: FontWeight.bold,
                                          color: isCancelled
                                              ? Colors.grey
                                              : const Color(0xFF1E293B),
                                          decoration: isCancelled
                                              ? TextDecoration.lineThrough
                                              : null,
                                        ),
                                      ),
                                      Container(
                                        padding: const EdgeInsets.symmetric(
                                          horizontal: 8,
                                          vertical: 4,
                                        ),
                                        decoration: BoxDecoration(
                                          color: isCancelled
                                              ? Colors.red.shade50
                                              : (isPast
                                                    ? Colors.grey.shade100
                                                    : Colors.blue.shade50),
                                          borderRadius: BorderRadius.circular(
                                            8,
                                          ),
                                        ),
                                        child: Text(
                                          isCancelled
                                              ? 'CANCELLED'
                                              : (isPast
                                                    ? 'COMPLETED'
                                                    : 'UPCOMING'),
                                          style: GoogleFonts.inter(
                                            fontSize: 11,
                                            fontWeight: FontWeight.bold,
                                            color: isCancelled
                                                ? Colors.red.shade700
                                                : (isPast
                                                      ? Colors.grey.shade600
                                                      : Colors.blue.shade700),
                                          ),
                                        ),
                                      ),
                                    ],
                                  ),
                                  subtitle: Padding(
                                    padding: const EdgeInsets.only(top: 8.0),
                                    child: Row(
                                      children: [
                                        Icon(
                                          Icons.access_time,
                                          size: 16,
                                          color: Colors.grey.shade600,
                                        ),
                                        const SizedBox(width: 8),
                                        Text(
                                          '${DateFormat('MMM d, yyyy').format(booking.startDate)} • ${DateFormat('HH:mm').format(booking.startDate)} - ${DateFormat('HH:mm').format(booking.endDate)}',
                                          style: GoogleFonts.inter(
                                            color: Colors.grey.shade700,
                                            fontWeight: FontWeight.w500,
                                          ),
                                        ),
                                      ],
                                    ),
                                  ),
                                ),
                              )
                              .animate()
                              .fadeIn(delay: (50 * index).ms)
                              .slideX(begin: 0.05);
                        },
                      );
                    },
                  ),
                ],
              ),
            ),
          ],
        ),
      ),
    );
  }
}
