<?php

namespace App\Helpers;

use Carbon\Carbon;

class PickupTimeHelper
{
    /**
     * Get available pickup time slots for next 7 days
     * 
     * @return array
     */
    public static function getAvailableSlots(): array
    {
        $slots = [];
        $now = Carbon::now();

        // Define time slots (store hours)
        $slotTimes = [
            ['09:00', '12:00'],
            ['12:00', '15:00'],
            ['15:00', '17:00'],
        ];

        // Generate slots for next 7 days
        for ($i = 0; $i < 7; $i++) {
            $date = $now->copy()->addDays($i);

            // Skip Sundays (store closed)
            if ($date->isSunday()) {
                continue;
            }

            foreach ($slotTimes as [$start, $end]) {
                $slotStart = $date->copy()->setTimeFromTimeString($start);

                // Skip past slots for today
                if ($slotStart->isPast()) {
                    continue;
                }

                $slots[] = [
                    'datetime' => $slotStart->format('Y-m-d H:i:s'),
                    'label' => $date->isoFormat('ddd, D MMM') . ' (' . $start . ' - ' . $end . ')',
                    'available' => true, // Can add capacity check later
                    'is_today' => $date->isToday(),
                    'is_tomorrow' => $date->isTomorrow(),
                ];
            }
        }

        return $slots;
    }

    /**
     * Format pickup time for display
     * 
     * @param Carbon|string|null $datetime
     * @return string
     */
    public static function formatPickupTime($datetime): string
    {
        if (!$datetime) {
            return 'Belum ditentukan';
        }

        $carbon = $datetime instanceof Carbon ? $datetime : Carbon::parse($datetime);

        if ($carbon->isToday()) {
            return 'Hari ini, ' . $carbon->format('H:i');
        }

        if ($carbon->isTomorrow()) {
            return 'Besok, ' . $carbon->format('H:i');
        }

        return $carbon->isoFormat('ddd, D MMM Y - HH:mm');
    }
}
