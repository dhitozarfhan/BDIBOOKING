import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import 'package:google_fonts/google_fonts.dart';
import 'package:flutter_animate/flutter_animate.dart';
import 'package:frontend/providers/property_provider.dart';
import 'package:frontend/providers/booking_provider.dart';

class OverviewScreen extends StatefulWidget {
  @override
  _OverviewScreenState createState() => _OverviewScreenState();
}

class _OverviewScreenState extends State<OverviewScreen> {
  @override
  void initState() {
    super.initState();
    WidgetsBinding.instance.addPostFrameCallback((_) {
      Provider.of<PropertyProvider>(context, listen: false).fetchProperties();
      Provider.of<BookingProvider>(context, listen: false).fetchBookings();
    });
  }

  Widget _buildStatCard({
    required String title,
    required String value,
    required IconData icon,
    required Color color,
    required Color bgColor,
  }) {
    return Card(
      elevation: 2,
      shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(16)),
      child: Padding(
        padding: const EdgeInsets.all(20.0),
        child: Row(
          children: [
            Container(
              height: 56,
              width: 56,
              decoration: BoxDecoration(
                color: bgColor,
                borderRadius: BorderRadius.circular(12),
              ),
              child: Icon(icon, size: 32, color: color),
            ),
            const SizedBox(width: 16),
            Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Text(
                  title,
                  style: GoogleFonts.inter(
                    fontSize: 14,
                    fontWeight: FontWeight.w500,
                    color: Colors.grey.shade600,
                  ),
                ),
                Text(
                  value,
                  style: GoogleFonts.poppins(
                    fontSize: 28,
                    fontWeight: FontWeight.bold,
                    color: const Color(0xFF1E293B),
                  ),
                ),
              ],
            ),
          ],
        ),
      ),
    );
  }

  Future<void> _refreshData() async {
    // Refresh all the providers
    await Future.wait([
      Provider.of<PropertyProvider>(context, listen: false).fetchProperties(),
      Provider.of<BookingProvider>(context, listen: false).fetchBookings(),
    ]);
  }

  @override
  Widget build(BuildContext context) {
    final properties = Provider.of<PropertyProvider>(context).properties;
    final bookings = Provider.of<BookingProvider>(context).bookings;

    final availableRooms = properties
        .where((p) => p.status == 'available')
        .length;
    final occupiedRooms = properties
        .where((p) => p.status == 'occupied')
        .length;

    // Calculate bookings today
    final now = DateTime.now();
    final todayBookings = bookings.where((b) {
      if (b.status == 'cancelled') return false;
      return b.startDate.year == now.year &&
          b.startDate.month == now.month &&
          b.startDate.day == now.day;
    }).length;

    return Scaffold(
      backgroundColor: const Color(0xFFF8FAFC),
      body: RefreshIndicator(
        onRefresh: _refreshData,
        color: const Color(0xFF3B82F6),
        backgroundColor: Colors.white,
        child: CustomScrollView(
          physics: const AlwaysScrollableScrollPhysics(),
          slivers: [
            SliverToBoxAdapter(
              child: Padding(
                padding: const EdgeInsets.all(24.0),
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Text(
                      'Welcome back, Admin 👋',
                      style: GoogleFonts.poppins(
                        fontSize: 24,
                        fontWeight: FontWeight.bold,
                        color: const Color(0xFF1E3A8A),
                      ),
                    ).animate().fadeIn(duration: 400.ms).slideX(begin: -0.05),
                    const SizedBox(height: 8),
                    Text(
                      'Here is the overview of your room capacities and bookings.',
                      style: GoogleFonts.inter(
                        fontSize: 16,
                        color: Colors.grey.shade600,
                      ),
                    ).animate().fadeIn(delay: 200.ms),
                    const SizedBox(height: 32),

                    // Wrap handles overflowing on smaller screens
                    Wrap(
                      spacing: 16,
                      runSpacing: 16,
                      children: [
                        SizedBox(
                          width: MediaQuery.of(context).size.width >= 600
                              ? (MediaQuery.of(context).size.width - 64) / 2
                              : double.infinity,
                          child: _buildStatCard(
                            title: 'Total Properties',
                            value: '${properties.length}',
                            icon: Icons.meeting_room,
                            color: const Color(0xFF1E40AF), // blue-800
                            bgColor: const Color(0xFFDBEAFE), // blue-100
                          ),
                        ).animate().fadeIn(delay: 300.ms).scale(),

                        SizedBox(
                          width: MediaQuery.of(context).size.width >= 600
                              ? (MediaQuery.of(context).size.width - 64) / 2
                              : double.infinity,
                          child: _buildStatCard(
                            title: 'Available Rooms',
                            value: '$availableRooms',
                            icon: Icons.check_circle_outline,
                            color: const Color(0xFF16A34A), // green-600
                            bgColor: const Color(0xFFDCFCE7), // green-100
                          ),
                        ).animate().fadeIn(delay: 400.ms).scale(),

                        SizedBox(
                          width: MediaQuery.of(context).size.width >= 600
                              ? (MediaQuery.of(context).size.width - 64) / 2
                              : double.infinity,
                          child: _buildStatCard(
                            title: 'Occupied/Maintenance',
                            value:
                                '${occupiedRooms + properties.where((p) => p.status == 'maintenance').length}',
                            icon: Icons.do_not_disturb_alt,
                            color: const Color(0xFFDC2626), // red-600
                            bgColor: const Color(0xFFFEE2E2), // red-100
                          ),
                        ).animate().fadeIn(delay: 500.ms).scale(),

                        SizedBox(
                          width: MediaQuery.of(context).size.width >= 600
                              ? (MediaQuery.of(context).size.width - 64) / 2
                              : double.infinity,
                          child: _buildStatCard(
                            title: 'Bookings Today',
                            value: '$todayBookings',
                            icon: Icons.today,
                            color: const Color(0xFFD97706), // amber-600
                            bgColor: const Color(0xFFFEF3C7), // amber-100
                          ),
                        ).animate().fadeIn(delay: 600.ms).scale(),
                      ],
                    ),

                    const SizedBox(height: 40),
                    Text(
                      'Quick Actions',
                      style: GoogleFonts.poppins(
                        fontSize: 18,
                        fontWeight: FontWeight.w600,
                        color: const Color(0xFF1E293B),
                      ),
                    ),
                    const SizedBox(height: 16),

                    Row(
                      children: [
                        Expanded(
                          child: ElevatedButton.icon(
                            onPressed: () {
                              Scaffold.of(context).openDrawer();
                            },
                            icon: const Icon(Icons.menu),
                            label: const Text('Open Menu'),
                            style: ElevatedButton.styleFrom(
                              backgroundColor: Colors.white,
                              foregroundColor: const Color(0xFF3B82F6),
                              elevation: 1,
                              shape: RoundedRectangleBorder(
                                borderRadius: BorderRadius.circular(12),
                                side: const BorderSide(
                                  color: Color(0xFFE2E8F0),
                                ),
                              ),
                            ),
                          ),
                        ),
                      ],
                    ).animate().fadeIn(delay: 800.ms),
                  ],
                ),
              ),
            ),
          ],
        ),
      ),
    );
  }
}
