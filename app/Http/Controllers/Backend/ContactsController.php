<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Contact;

class ContactsController extends Controller
{
    /**
     * Display a listing of Contact Customers.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $contacts = Contact::paginate(15);
        return view('backend.contacts.index', compact('contacts'));
    }
}
