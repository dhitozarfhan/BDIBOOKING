<?php
echo json_encode(\App\Models\Booking::select('bookable_type')->distinct()->pluck('bookable_type'));
