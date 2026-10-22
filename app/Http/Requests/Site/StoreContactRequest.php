<?php

namespace App\Http\Requests\Site;

/**
 * Contact us form  (field names are the ones already used by contact.blade.php).
 */
class StoreContactRequest extends SiteFormRequest
{
    public function rules()
    {
        return [
            'contact_full_name' => $this->nameRules(),
            'contact_email'     => $this->emailRules(),
            'contact_phone'     => $this->phoneRules(),
            'contact_subject'   => 'required|string|max:180',
            'contact_message'   => 'required|string|max:5000',
        ];
    }

    public function attributes()
    {
        return [
            'contact_full_name' => 'name',
            'contact_email'     => 'email',
            'contact_phone'     => 'phone number',
            'contact_subject'   => 'subject',
            'contact_message'   => 'message',
        ];
    }

    /** Form field names -> database columns. Only these fields are ever saved. */
    public function payload()
    {
        return [
            'name'    => $this->input('contact_full_name'),
            'email'   => $this->input('contact_email'),
            'phone'   => $this->input('contact_phone'),
            'subject' => $this->input('contact_subject'),
            'message' => $this->input('contact_message'),
        ];
    }
}
