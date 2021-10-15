<?php

namespace App\Http\Controllers\Dashboard;

use App\DataTables\MessageDatatable;
use App\Http\Controllers\Controller;
use App\Message;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function index(MessageDatatable $messageDatatable)
    {
        return $messageDatatable->render('dashboard.messages.index');
    }

    public function show(Message $message){

        return view('dashboard.messages.show', compact('message'));
    }
}
