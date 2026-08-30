<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\Admin\BookingService;

class DashboardController extends Controller
{
    public function __construct(
        private BookingService $bookingService
    ) {}

    public function index(){
        return view('admin.index');
    }

    public function admin(){
        $latestBookings = $this->bookingService->getLatestBookings(5);
        return view('admin.dashboard', ['latestBookings' => $latestBookings]);
    }


}
