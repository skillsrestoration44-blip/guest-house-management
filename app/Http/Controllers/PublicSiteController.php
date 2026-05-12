<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\OnlineBookingRequest;
use App\Models\Room;
use App\Models\RoomType;
use App\Models\Service;
use App\Models\WebsitePage;
use App\Models\CodeSetting;
use App\Services\CodeGeneratorService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class PublicSiteController extends Controller
{
    public function rooms(Request $request): JsonResponse
    {
        $query = Room::query()
            ->with(['roomType:id,name,description,default_price_per_night,max_guests']);

        if (Schema::hasColumn('rooms', 'status')) {
            $query->whereNotIn('status', ['maintenance', 'blocked']);
        }

        $rooms = $query->orderBy('id')->take((int) $request->input('limit', 12))->get();

        $data = $rooms->map(function ($room) {
            return [
                'id' => $room->id,
                'name' => $room->roomType?->name ?? ('Room ' . ($room->room_number ?? $room->id)),
                'room_no' => $room->room_number,
                'description' => $room->description ?? $room->roomType?->description,
                'price_per_night' => $room->price_per_night ?? $room->roomType?->default_price_per_night ?? 0,
                'capacity' => $room->max_guests ?? $room->roomType?->max_guests ?? 2,
                'image_url' => 'https://picsum.photos/seed/room-' . $room->id . '/640/420',
                'room_type' => $room->roomType,
            ];
        });

        return response()->json(['data' => $data]);
    }

    public function room(int $id): JsonResponse
    {
        $room = Room::query()
            ->with(['roomType', 'facilities:id,name'])
            ->findOrFail($id);

        return response()->json([
            'data' => [
                'id' => $room->id,
                'name' => $room->roomType?->name ?? ('Room ' . ($room->room_number ?? $room->id)),
                'room_no' => $room->room_number,
                'description' => $room->description ?? $room->roomType?->description,
                'price_per_night' => $room->price_per_night ?? $room->roomType?->default_price_per_night ?? 0,
                'capacity' => $room->max_guests ?? $room->roomType?->max_guests ?? 2,
                'amenities' => $room->facilities?->pluck('name')->all() ?: ['Free Wi-Fi', 'Air Conditioning', 'Hot Shower', 'TV'],
                'room_type' => $room->roomType,
            ],
        ]);
    }

    public function branches(): JsonResponse
    {
        $branches = Branch::query()
            ->select('id', 'code', 'name', 'phone', 'address', 'email')
            ->orderBy('name')
            ->get();

        return response()->json(['data' => $branches]);
    }

    public function roomTypes(): JsonResponse
    {
        $types = RoomType::query()
            ->select('id', 'name', 'description', 'default_price_per_night', 'max_guests')
            ->orderBy('name')
            ->get();

        return response()->json(['data' => $types]);
    }

    public function services(): JsonResponse
    {
        $query = Service::query();
        if (Schema::hasColumn('services', 'status')) {
            $query->where('status', 'active');
        }
        $services = $query->orderBy('name')->get(['id', 'name', 'category', 'unit', 'price', 'description']);

        return response()->json(['data' => $services]);
    }

    public function page(string $slug): JsonResponse
    {
        $page = WebsitePage::query()
            ->where('slug', $slug)
            ->firstOrFail(['id', 'title', 'slug', 'content', 'meta_title', 'meta_description']);

        return response()->json(['data' => $page]);
    }

    public function submitBooking(Request $request, CodeGeneratorService $codes): JsonResponse
    {
        $data = $request->validate([
            'guest_name' => 'required|string|max:150',
            'email' => 'required|email|max:150',
            'phone' => 'nullable|string|max:50',
            'branch_id' => 'nullable|exists:branches,id',
            'room_type_id' => 'nullable|exists:room_types,id',
            'check_in_date' => 'required|date|after_or_equal:today',
            'check_out_date' => 'required|date|after:check_in_date',
            'adults' => 'required|integer|min:1|max:10',
            'children' => 'nullable|integer|min:0|max:10',
            'special_requests' => 'nullable|string|max:2000',
        ]);

        $total_guests = ($data['adults'] ?? 1) + ($data['children'] ?? 0);

        CodeSetting::firstOrCreate(
            ['code_type' => 'online_booking'],
            ['prefix' => 'OB', 'next_number' => 1, 'digit_length' => 6, 'example' => 'OB-000001']
        );

        $req = OnlineBookingRequest::create([
            'branch_id' => $data['branch_id'] ?? Branch::query()->orderBy('id')->value('id'),
            'request_no' => $codes->next('online_booking'),
            'guest_name' => $data['guest_name'],
            'phone' => $data['phone'] ?? '',
            'email' => $data['email'],
            'room_type_id' => $data['room_type_id'] ?? null,
            'check_in_date' => $data['check_in_date'],
            'check_out_date' => $data['check_out_date'],
            'total_guests' => $total_guests,
            'status' => 'pending',
            'note' => $data['special_requests'] ?? null,
        ]);

        return response()->json([
            'message' => 'Booking request received.',
            'data' => [
                'id' => $req->id,
                'booking_no' => $req->request_no,
                'status' => $req->status,
            ],
        ], 201);
    }

    public function lookupBooking(Request $request): JsonResponse
    {
        $data = $request->validate([
            'booking_no' => 'required|string|max:50',
            'email' => 'required|email|max:150',
        ]);

        $req = OnlineBookingRequest::query()
            ->where('request_no', $data['booking_no'])
            ->where('email', $data['email'])
            ->first();

        if (! $req) {
            return response()->json(['message' => 'Not found.'], 404);
        }

        $statusMap = [
            'pending' => 'pending',
            'approved' => 'confirmed',
            'rejected' => 'rejected',
            'cancelled' => 'rejected',
        ];

        return response()->json([
            'data' => [
                'booking_no' => $req->request_no,
                'guest_name' => $req->guest_name,
                'check_in_date' => optional($req->check_in_date)->toDateString(),
                'check_out_date' => optional($req->check_out_date)->toDateString(),
                'status' => $statusMap[$req->status] ?? 'pending',
                'note' => $req->note,
            ],
        ]);
    }

    public function contact(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'required|string|max:150',
            'email' => 'required|email|max:150',
            'subject' => 'nullable|string|max:200',
            'message' => 'required|string|max:5000',
        ]);

        // For now, just acknowledge. Future: persist or send mail.
        return response()->json(['message' => 'Thank you, we will get back to you within 24 hours.']);
    }
}
