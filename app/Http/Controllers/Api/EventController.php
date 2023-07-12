<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Event;
use App\Http\Resources\Event as EventResource;

class EventController extends Controller
{

    // Get All Event
    public function index()
    {
        $events = Event::all();
        return EventResource::collection($events);
    }

    // Create New Event
    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'required',
            'start' => 'required'
        ]);
        
        $event = new Event;
        $event->title = $request->input('title');
        $event->start = $request->input('start');
        $event->end = $request->input('end');
        $event->cssClass = "blue";
        $event->save();

        return response()->json([
            'message' => 'Successfully Add New Event',
            'data' => new EventResource($event),
            'url' => '/api/v1/event',
            'method' => 'POST'
        ], 200);
    }

    // Detail Event
    public function show($id)
    {
        $event = Event::findOrFail($id);
        return response()->json([
            'message' => 'Get Single Event',
            'data' => new EventResource($event),
            'url' => '/api/v1/event/' . $event->id,
            'method' => 'GET'
        ], 200);
    }

    // Update Event
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'title' => 'required',
            'start' => 'required'
        ]);

        $event = Event::findOrFail($id);
        $event->title = $request->input('title');
        $event->start = $request->input('start');
        $event->end = $request->input('end');
        $event->cssClass = "blue";
        $event->save();

        return response()->json([
            'message' => 'Event Updated',
            'data' => new EventResource($event),
            'url' => '/api/v1/event/' . $event->id,
            'method' => 'PUT'
        ], 200);
    }

    // Delete Event
    public function destroy($id)
    {
        $event = Event::findOrFail($id);
        $event->delete();

        return response()->json([
            'message' => 'Event Deleted',
            'data' => new EventResource($event),
            'url' => '/api/v1/event/' . $event->id,
            'method' => 'DELETE'
        ], 200);        
    }
}
