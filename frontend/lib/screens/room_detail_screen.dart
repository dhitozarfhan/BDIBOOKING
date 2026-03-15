import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import 'package:frontend/providers/room_provider.dart';
import 'package:frontend/models/room.dart';
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
      Provider.of<RoomProvider>(context, listen: false)
          .fetchRooms(propertyId: widget.property.id);
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
 
            // Room Inventory Section
            Padding(
              padding: const EdgeInsets.symmetric(horizontal: 24.0, vertical: 12.0),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Row(
                    mainAxisAlignment: MainAxisAlignment.spaceBetween,
                    children: [
                      Text(
                        'Room Inventory',
                        style: GoogleFonts.poppins(
                          fontSize: 20,
                          fontWeight: FontWeight.bold,
                          color: const Color(0xFF1E293B),
                        ),
                      ),
                      ElevatedButton.icon(
                        onPressed: () => _showRoomFormDialog(context),
                        icon: const Icon(Icons.add, size: 18),
                        label: const Text('Add Room'),
                        style: ElevatedButton.styleFrom(
                          padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 8),
                          backgroundColor: const Color(0xFF3B82F6),
                        ),
                      ),
                    ],
                  ),
                  const SizedBox(height: 16),
                  Consumer<RoomProvider>(
                    builder: (ctx, roomProvider, _) {
                      if (roomProvider.isLoading) {
                        return const Center(child: CircularProgressIndicator());
                      }
                      
                      if (roomProvider.rooms.isEmpty) {
                        return _buildEmptyState('No Rooms Added', 'Tap "Add Room" to create inventory.');
                      }

                      return ListView.builder(
                        shrinkWrap: true,
                        physics: const NeverScrollableScrollPhysics(),
                        itemCount: roomProvider.rooms.length,
                        itemBuilder: (ctx, index) {
                          final room = roomProvider.rooms[index];
                          return _buildRoomTile(room);
                        },
                      );
                    },
                  ),
                ],
              ),
            ),

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

  Widget _buildEmptyState(String title, String subtitle) {
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
            Icon(Icons.inventory_2_outlined, size: 48, color: Colors.grey.shade300),
            const SizedBox(height: 16),
            Text(
              title,
              style: GoogleFonts.inter(fontSize: 16, color: Colors.grey.shade600, fontWeight: FontWeight.w500),
            ),
            const SizedBox(height: 8),
            Text(
              subtitle,
              textAlign: TextAlign.center,
              style: GoogleFonts.inter(fontSize: 14, color: Colors.grey.shade500),
            ),
          ],
        ),
      ),
    );
  }

  Widget _buildRoomTile(Room room) {
    return Card(
      margin: const EdgeInsets.only(bottom: 8),
      elevation: 0,
      shape: RoundedRectangleBorder(
        borderRadius: BorderRadius.circular(12),
        side: BorderSide(color: Colors.grey.shade200),
      ),
      child: ListTile(
        title: Text(
          'Room ${room.roomNumber}',
          style: GoogleFonts.poppins(fontWeight: FontWeight.bold),
        ),
        subtitle: Text(
          room.floor != null ? 'Floor ${room.floor}' : 'Level: Unknown',
          style: GoogleFonts.inter(fontSize: 12),
        ),
        trailing: Row(
          mainAxisSize: MainAxisSize.min,
          children: [
            _buildRoomStatusBadge(room.status),
            const SizedBox(width: 8),
            IconButton(
              icon: const Icon(Icons.edit_outlined, size: 20, color: Colors.blue),
              onPressed: () => _showRoomFormDialog(context, room: room),
            ),
            IconButton(
              icon: const Icon(Icons.delete_outline, size: 20, color: Colors.red),
              onPressed: () => _confirmDeleteRoom(room),
            ),
          ],
        ),
      ),
    );
  }

  Widget _buildRoomStatusBadge(String status) {
    Color bg;
    Color text;
    switch (status) {
      case 'available':
        bg = Colors.green.shade50;
        text = Colors.green.shade700;
        break;
      case 'use':
        bg = Colors.blue.shade50;
        text = Colors.blue.shade700;
        break;
      case 'maintenance':
        bg = Colors.orange.shade50;
        text = Colors.orange.shade700;
        break;
      case 'cleaned':
        bg = Colors.purple.shade50;
        text = Colors.purple.shade700;
        break;
      default:
        bg = Colors.grey.shade50;
        text = Colors.grey.shade700;
    }

    return Container(
      padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 4),
      decoration: BoxDecoration(color: bg, borderRadius: BorderRadius.circular(8)),
      child: Text(
        status.toUpperCase(),
        style: GoogleFonts.inter(fontSize: 10, fontWeight: FontWeight.bold, color: text),
      ),
    );
  }

  void _confirmDeleteRoom(Room room) async {
    final confirm = await showDialog<bool>(
      context: context,
      builder: (ctx) => AlertDialog(
        title: const Text('Delete Room'),
        content: Text('Are you sure you want to delete Room ${room.roomNumber}?'),
        actions: [
          TextButton(onPressed: () => Navigator.pop(ctx, false), child: const Text('Cancel')),
          ElevatedButton(
            onPressed: () => Navigator.pop(ctx, true),
            style: ElevatedButton.styleFrom(backgroundColor: Colors.red),
            child: const Text('Delete'),
          ),
        ],
      ),
    );

    if (confirm == true) {
      await Provider.of<RoomProvider>(context, listen: false).deleteRoom(room.id!);
    }
  }

  void _showRoomFormDialog(BuildContext context, {Room? room}) {
    final isEditing = room != null;
    final numberController = TextEditingController(text: room?.roomNumber);
    final floorController = TextEditingController(text: room?.floor);
    String selectedStatus = room?.status ?? 'available';

    showDialog(
      context: context,
      builder: (ctx) => StatefulBuilder(
        builder: (context, setState) => AlertDialog(
          title: Text(isEditing ? 'Edit Room' : 'Add New Room'),
          content: SingleChildScrollView(
            child: Column(
              mainAxisSize: MainAxisSize.min,
              children: [
                TextField(
                  controller: numberController,
                  decoration: const InputDecoration(labelText: 'Room Number (e.g. 101)'),
                ),
                const SizedBox(height: 16),
                TextField(
                  controller: floorController,
                  decoration: const InputDecoration(labelText: 'Floor (Optional)'),
                ),
                const SizedBox(height: 16),
                DropdownButtonFormField<String>(
                  value: selectedStatus,
                  items: ['available', 'use', 'maintenance', 'cleaned']
                      .map((s) => DropdownMenuItem(value: s, child: Text(s.toUpperCase())))
                      .toList(),
                  onChanged: (val) => setState(() => selectedStatus = val!),
                  decoration: const InputDecoration(labelText: 'Status'),
                ),
              ],
            ),
          ),
          actions: [
            TextButton(onPressed: () => Navigator.pop(ctx), child: const Text('Cancel')),
            ElevatedButton(
              onPressed: () async {
                if (numberController.text.isEmpty) return;

                final roomData = Room(
                  id: room?.id,
                  propertyId: widget.property.id!,
                  roomNumber: numberController.text,
                  floor: floorController.text,
                  status: selectedStatus,
                );

                final provider = Provider.of<RoomProvider>(context, listen: false);
                bool success;
                if (isEditing) {
                  success = await provider.updateRoom(roomData);
                } else {
                  success = await provider.addRoom(roomData);
                }

                if (success) {
                  Navigator.pop(ctx);
                }
              },
              child: Text(isEditing ? 'Update' : 'Add Room'),
            ),
          ],
        ),
      ),
    );
  }
}
