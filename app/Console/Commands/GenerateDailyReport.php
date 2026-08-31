<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use App\Models\Booking;
use App\Models\Passenger;
use App\Models\Flight;
use Illuminate\Support\Facades\Storage;

class GenerateDailyReport extends Command
{
    protected $signature = 'report:daily';
    protected $description = 'Generate daily report and store in storage';
    public function handle()
    {
        $today = now()->toDateString();
        $yesterday = now()->subDay()->toDateString();

        $stats = [
            'date' => $today,
            'total_bookings' => Booking::count(),
            'today_bookings' => Booking::whereDate('created_at', $today)->count(),
            'yesterday_bookings' => Booking::whereDate('created_at', $yesterday)->count(),
            'total_passengers' => Passenger::count(),
            'total_flights' => Flight::count(),
            'revenue_today' => Booking::whereDate('created_at', $today)->sum('total_price'),
            'revenue_total' => Booking::sum('total_price'),
            'status_breakdown' => [
                'pending' => Booking::where('status', 'pending')->count(),
                'confirmed' => Booking::where('status', 'confirmed')->count(),
                'cancelled' => Booking::where('status', 'cancelled')->count(),
                'completed' => Booking::where('status', 'completed')->count(),
            ],
        ];

        $content = "========================================\n";
        $content .= "        DAILY REPORT - {$today}\n";
        $content .= "========================================\n\n";

        $content .= "OVERVIEW\n";
        $content .= "----------------------------------------\n";
        $content .= "Total Bookings:     {$stats['total_bookings']}\n";
        $content .= "Today's Bookings:   {$stats['today_bookings']}\n";
        $content .= "Yesterday's:        {$stats['yesterday_bookings']}\n";
        $content .= "Total Passengers:   {$stats['total_passengers']}\n";
        $content .= "Total Flights:      {$stats['total_flights']}\n\n";

        $content .= "REVENUE\n";
        $content .= "----------------------------------------\n";
        $content .= "Today's Revenue:    \$" . number_format($stats['revenue_today'], 2) . "\n";
        $content .= "Total Revenue:      \$" . number_format($stats['revenue_total'], 2) . "\n\n";

        $content .= "BOOKING STATUS\n";
        $content .= "----------------------------------------\n";
        $content .= "Pending:    {$stats['status_breakdown']['pending']}\n";
        $content .= "Confirmed:  {$stats['status_breakdown']['confirmed']}\n";
        $content .= "Cancelled:  {$stats['status_breakdown']['cancelled']}\n";
        $content .= "Completed:  {$stats['status_breakdown']['completed']}\n\n";

        $content .= "========================================\n";
        $content .= "Report generated: " . now()->format('Y-m-d H:i:s') . "\n";
        $content .= "========================================\n";
        
        if (!Storage::exists('reports')) {
            Storage::makeDirectory('reports');
            $this->info('Created reports directory.');
        }

        $filename = "reports/daily-report-{$today}.txt";
        Storage::put($filename, $content);
        if (Storage::exists($filename)) {
            $this->info("Daily report saved to: storage/app/{$filename}");
            $this->line("Today's bookings: {$stats['today_bookings']}");
            $this->line("Today's revenue: \$" . number_format($stats['revenue_today'], 2));
        } else {
            $this->error("Failed to save report.");
        }

        return Command::SUCCESS;
    }
}
