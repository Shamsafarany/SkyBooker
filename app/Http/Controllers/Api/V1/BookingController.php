<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\BookingCollection;
use App\Http\Resources\Api\V1\PassengerCollection;
use App\Models\Booking;
use Illuminate\Http\Request;
use App\Http\Resources\Api\V1\BookingResource;
use App\Http\Requests\Booking\StoreBookingRequest;
use App\Http\Requests\Booking\UpdateBookingRequest;
use App\Http\Resources\Api\V1\TicketCollection;
use App\Models\Ticket;
use OpenApi\Attributes as OA;

#[OA\Tag(
    name: 'Bookings',
    description: 'API Endpoints for managing Bookings'
)]
class BookingController extends Controller
{

    #[OA\Get(
        path: '/api/v1/bookings',
        tags: ['Bookings'],
        summary: 'List all bookings with pagination',
        parameters: [
            new OA\Parameter(
                name: 'page',
                description: 'Page number',
                in: 'query',
                schema: new OA\Schema(type: 'integer', default: 1)
            ),
            new OA\Parameter(
                name: 'per_page',
                description: 'Items per page',
                in: 'query',
                schema: new OA\Schema(type: 'integer', default: 15)
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'List of bookings with pagination'
            )
        ]
    )]
    public function index()
    {
        $bookings = Booking::with(['user', 'flight', 'passengers'])->paginate(15);
        return new BookingCollection($bookings);
    }

    #[OA\Post(
        path: '/api/v1/bookings',
        tags: ['Bookings'],
        summary: 'Create a new booking',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['user_id', 'flight_id', 'number_of_seats', 'total_price', 'status', 'passengers'],
                properties: [
                    new OA\Property(property: 'user_id', type: 'integer', example: 1),
                    new OA\Property(property: 'flight_id', type: 'integer', example: 1),
                    new OA\Property(property: 'number_of_seats', type: 'integer', example: 2),
                    new OA\Property(property: 'total_price', type: 'number', format: 'float', example: 999.98),
                    new OA\Property(property: 'status', type: 'string', example: 'pending'),
                    new OA\Property(property: 'notes', type: 'string', example: 'Window seats preferred', nullable: true),
                    new OA\Property(property: 'special_requests', type: 'string', example: 'Wheelchair assistance', nullable: true),
                    new OA\Property(
                        property: 'passengers',
                        type: 'array',
                        items: new OA\Items(
                            properties: [
                                new OA\Property(property: 'first_name', type: 'string', example: 'John'),
                                new OA\Property(property: 'last_name', type: 'string', example: 'Doe'),
                                new OA\Property(property: 'email', type: 'string', example: 'john@example.com'),
                                new OA\Property(property: 'phone', type: 'string', example: '+1234567890', nullable: true),
                                new OA\Property(property: 'date_of_birth', type: 'string', example: '1990-01-01', nullable: true),
                                new OA\Property(property: 'nationality', type: 'string', example: 'USA'),
                                new OA\Property(property: 'passport_number', type: 'string', example: 'AB123456'),
                                new OA\Property(property: 'id_number', type: 'string', example: 'ID123456'),
                                new OA\Property(property: 'seat_number', type: 'string', example: '12A', nullable: true),
                                new OA\Property(property: 'meal_preference', type: 'string', example: 'vegetarian', nullable: true),
                                new OA\Property(property: 'status', type: 'string', example: 'pending', nullable: true),
                            ]
                        )
                    ),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: 'Booking created successfully'
            ),
            new OA\Response(
                response: 422,
                description: 'Validation error'
            )
        ]
    )]
    public function store(StoreBookingRequest $request)
    {
        $booking = Booking::create($request->validated());
        $booking->load(['user', 'flight', 'passengers.ticket']);
        
        return response()->json(
            new BookingResource($booking),
            201
        );
    }

    #[OA\Get(
        path: '/api/v1/bookings/{booking}',
        tags: ['Bookings'],
        summary: 'Get booking by ID',
        parameters: [
            new OA\Parameter(
                name: 'booking',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer')
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Booking retrieved successfully'
            ),
            new OA\Response(
                response: 404,
                description: 'Booking not found'
            )
        ]
    )]
    public function show(Booking $booking)
    {
        $booking->load(['user', 'flight', 'passengers']);
        return new BookingResource($booking);
    }

    #[OA\Put(
        path: '/api/v1/bookings/{booking}',
        tags: ['Bookings'],
        summary: 'Update a booking',
        parameters: [
            new OA\Parameter(
                name: 'booking',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer')
            )
        ],
        requestBody: new OA\RequestBody(
            required: false,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'user_id', type: 'integer', example: 2),
                    new OA\Property(property: 'flight_id', type: 'integer', example: 2),
                    new OA\Property(property: 'number_of_seats', type: 'integer', example: 3),
                    new OA\Property(property: 'total_price', type: 'number', format: 'float', example: 1499.97),
                    new OA\Property(property: 'status', type: 'string', example: 'confirmed'),
                    new OA\Property(property: 'notes', type: 'string', example: 'Updated notes'),
                    new OA\Property(property: 'special_requests', type: 'string', example: 'Updated requests'),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Booking updated successfully'
            )
        ]
    )]
    public function update(UpdateBookingRequest $request, Booking $booking)
    {
        $booking->update($request->validated());
        $booking->load(['user', 'flight']);
        return new BookingResource($booking);
    }

    #[OA\Delete(
        path: '/api/v1/bookings/{booking}',
        tags: ['Bookings'],
        summary: 'Delete a booking',
        parameters: [
            new OA\Parameter(
                name: 'booking',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer')
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Booking deleted successfully'
            ),
            new OA\Response(
                response: 404,
                description: 'Booking not found'
            )
        ]
    )]
    public function destroy(Booking $booking)
    {
        $booking->delete();
        return response()->json(null, 204);
    }
        #[OA\Get(
        path: '/api/v1/bookings/{booking}/passengers',
        tags: ['Bookings'],
        summary: 'Get all passengers for a booking',
        parameters: [
            new OA\Parameter(
                name: 'booking',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer')
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'List of passengers for the booking'
            ),
            new OA\Response(
                response: 404,
                description: 'Booking not found'
            )
        ]
    )]
    public function passengers(Booking $booking)
    {
        $passengers = $booking->passengers()
            ->with(['ticket'])
            ->paginate(15);
        
        return new PassengerCollection($passengers);
    }
    #[OA\Get(
    path: '/api/v1/bookings/{booking}/tickets',
    tags: ['Bookings'],
    summary: 'Get all tickets for a booking',
    parameters: [
        new OA\Parameter(
            name: 'booking',
            in: 'path',
            required: true,
            schema: new OA\Schema(type: 'integer')
        )
    ],
    responses: [
        new OA\Response(
            response: 200,
            description: 'List of tickets for the booking'
        ),
        new OA\Response(
            response: 404,
            description: 'Booking not found'
        )
    ]
)]
    public function tickets(Booking $booking)
    {
        $tickets = Ticket::whereHas('passenger', function ($query) use ($booking) {
            $query->where('booking_id', $booking->id);
        })->with(['passenger'])->paginate(15);
        
        return new TicketCollection($tickets);
    }
    }
