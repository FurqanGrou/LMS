<?php

namespace App\Http\Controllers\Dashboard;

use App\AlertMessage;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AlertMessageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $messages = AlertMessage::query()->get();
        return view('admins.alert_messages.index', ['messages' => $messages]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(AlertMessage $alertMessage)
    {
        return ['id' => $alertMessage->id, 'content' => $alertMessage->content, 'content_en' => $alertMessage->content_en];
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, AlertMessage $alertMessage)
    {
        $request->validate([
            'message_content' => 'required|string',
            'message_content_en' => 'required|string',
        ]);

        $alertMessage->update([
            'content' => $request->message_content,
            'content_en' => $request->message_content_en,
        ]);

        return ['status' => true, 'content' => $alertMessage->content, 'content_en' => $alertMessage->content_en];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

}
