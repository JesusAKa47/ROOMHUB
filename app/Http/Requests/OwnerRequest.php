<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OwnerRequest extends FormRequest
{
    public function authorize(): bool { return true; }
    public function rules(): array {
        return [
            'name'      => ['required','string','min:3','max:120'],
            'email'     => ['required','email','max:150','unique:owners,email,'.($this->owner->id ?? 'NULL')],
            'phone'     => ['required','string','min:7','max:20'],
            'type'      => ['required','in:persona,empresa'], // select
            'is_active' => ['required','in:0,1'],             // radio
            'notes'     => ['required','string','min:10','max:1000'], // textarea
            'avatar'    => [$this->owner ? 'nullable':'required','file','image','mimes:jpg,jpeg,png,webp','max:4096'], // file
            'since'     => ['required','date','before_or_equal:today'], // date
        ];
    }
}
