<?php

namespace App\Http\Controllers;

use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DocumentsController extends Controller
{
    public static function index()
    {
        $documents = DB::table('documents')->where('business_id', UserController::get(Auth::id())->business_id)->get();
        foreach ($documents as $document) {
            $document_recipients = DB::table('documents_recipients')->where('document_id', $document->id)->get('recipient_id');
            $recipients = array();
            foreach ($document_recipients as $document_recipient) {
                array_push($recipients, $document_recipient->recipient_id);
            }
            $document->recipients = $recipients;
        }

        return $documents;
    }

    public static function drafts()
    {
        $documents = DB::table('documents')->where('business_id', UserController::get(Auth::id())->business_id)->where('status', 'draft')->get();
        foreach ($documents as $document) {
            $document_recipients = DB::table('documents_recipients')->where('document_id', $document->id)->get('recipient_id');
            $recipients = array();
            foreach ($document_recipients as $document_recipient) {
                array_push($recipients, $document_recipient->recipient_id);
            }
            $document->recipients = $recipients;
        }

        return $documents;
    }

    public static function outbox()
    {
        $documents = DB::table('documents')->where('business_id', UserController::get(Auth::id())->business_id)->where('status', 'doc')->where('author_id', Auth::id())->get();
        foreach ($documents as $document) {
            $document_recipients = DB::table('documents_recipients')->where('document_id', $document->id)->get('recipient_id');
            $recipients = array();
            foreach ($document_recipients as $document_recipient) {
                array_push($recipients, $document_recipient->recipient_id);
            }
            $document->recipients = $recipients;
        }

        return $documents;
    }

    public static function inbox()
    {
        $inbox = array();
        $documents = DB::table('documents')->where('business_id', UserController::get(Auth::id())->business_id)->where('status', 'doc')->get();
        foreach ($documents as $document) {
            $document_recipients = DB::table('documents_recipients')->where('recipient_id', Auth::id())->get('recipient_id');
            if (count($document_recipients) > 0) {
                $recipients = array();
                foreach ($document_recipients as $document_recipient) {
                    array_push($recipients, $document_recipient->recipient_id);
                }
                $document->recipients = $recipients;
                array_push($inbox, $document);
            }
        }

        return $inbox;
    }

    public function create(Request $request)
    {
        $creditionals = $request->validate([
            'title' => ['required'],
            'content' => ['required'],
            'status' => ['required'],
            'recipient-id' => ['required'],
        ]);

        $doc_id = DB::table('documents')->insertGetId([
            'title' => $creditionals['title'],
            'content' => $creditionals['content'],
            'status' => $creditionals['status'],
            'author_id' => Auth::id(),
            'business_id' => UserController::get(Auth::id())->business_id,
            'created_at' => new DateTime(),
        ]);

        foreach ($creditionals['recipient-id'] as $recipient_id) {
            DB::table('documents_recipients')->insert([
                'document_id' => $doc_id,
                'recipient_id' => $recipient_id,
                'created_at' => new DateTime(),
            ]);
        }
        return back()->withErrors([]);
    }
    
    public function update(Request $request)
    {
        $creditionals = $request->validate([
            'id' => ['required'],
            'title' => ['required'],
            'content' => ['required'],
            'status' => ['required'],
            'recipient-id' => ['required'],
        ]);

        DB::table('documents')->where('id', $creditionals['id'])->where('business_id', UserController::get(Auth::id())->business_id)->where('author_id',  Auth::id())->update([
            'title' => $creditionals['title'],
            'content' => $creditionals['content'],
            'status' => $creditionals['status'],
            'updated_at' => new DateTime(),
        ]);

        DB::table('documents_recipients')->where('document_id', $creditionals['id'])->delete();

        foreach ($creditionals['recipient-id'] as $recipient_id) {
            DB::table('documents_recipients')->insert([
                'document_id' => $creditionals['id'],
                'recipient_id' => $recipient_id,
                'created_at' => new DateTime(),
            ]);
        }

        return back()->withErrors([]);
    }
}
