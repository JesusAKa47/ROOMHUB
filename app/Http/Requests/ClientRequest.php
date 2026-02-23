<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ClientRequest extends FormRequest
{
    public function authorize(): bool { return true; }
    public function rules(): array {
        return [
            'name'       => ['required','string','min:3','max:120'],
            'email'      => ['required','email','max:150','unique:clients,email,'.($this->client->id ?? 'NULL')],
            'phone'      => ['required','string','min:7','max:20'],
            'gender'     => ['required','in:hombre,mujer,otro'], // select
            'is_verified'=> ['required','in:0,1'],               // radio
            'bio'        => ['required','string','min:10','max:1000'], // textarea
            'id_scan'    => [$this->client ? 'nullable':'required','file','mimes:pdf,jpg,jpeg,png,webp','max:4096'], // file
            'birthdate'  => ['required','date','before:today'],  // date
        ];
    }
}
