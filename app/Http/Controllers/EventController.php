<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Event;

class EventController extends Controller
{	
    public function __construct()
    {
        $this->middleware('auth');
    }

	public function index()
    {
    	return view('pages.events.index');
    }

    public function loadCalendar()
    {
        return view('pages.events.load');
    }

    public function loadEventData()
    {
    	$events = Event::all();
    	return $events;
    }

    public function create() 
    {
        $event = new Event;
        return view('pages.events.create', compact('event'));
    }

    public function store(Request $request)
    {
        // Validate Form
        $this->validate($request,[
            'title' => 'required',
            'start' => 'required|date',
            'color' => 'required|string',
        ]);

    	$event = new Event;
    	$event->title = $request->input('title');
        $event->description = $request->input('description');
    	$event->start = $request->input('start');
        $event->end = $request->input('end');
        $event->reminder_date = $request->input('reminder_date');
        $event->color = $request->input('color');
        $event->created_by = auth()->user()->id;
        $event->save();
        $event->event_employee()->attach($request->input('employees'));

        if($event) {
            return response()->json([
                'message' => 'Event Added Successfully',
                'start' => $event->start
            ], 200);
        }
    }

    public function show($id)
    {
        $event = Event::findOrFail($id);
        return view('pages.events.create', compact('event'));
    }

    public function updateEvent(Request $request, $id)
    {
        // Validate Form
        $this->validate($request,[
            'title' => 'required',
            'start' => 'required|date',
            'color' => 'required',
        ]);

    	$event = Event::findOrFail($id);
    	$event->title = $request->input('title');
        $event->description = $request->input('description');
        $event->start = $request->input('start');
        $event->end = $request->input('end');
        $event->reminder_date = $request->input('reminder_date');
        $event->color = $request->input('color');
    	$event->save();
        $event->event_employee()->sync($request->input('employees'));

        if($event) {
            return response()->json([
                'message' => 'Event Updated',
                'start' => $event->start
            ], 200);
        }
    }

    // Delete Event
    public function deleteEvent($id)
    {
        $event = Event::findOrFail($id);
        $event->event_employee()->detach();
    	$event->delete();

        if($event) {
            return response()->json([
                'message' => 'Event Has Been Removed',
                'start' => $event->start
            ], 200);
        }
    }
}
