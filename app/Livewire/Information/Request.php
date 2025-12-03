<?php

namespace App\Livewire\Information;

use App\Models\InformationRequest;
use Livewire\Component;

class Request extends Component
{
    // Personal Information
    public $name = '';
    public $id_card_number = '';
    public $address = '';
    public $occupation = '';
    public $mobile = '';
    public $email = '';
    
    // Request Details
    public $content = '';
    public $used_for = '';
    
    // Grab Method (checkboxes)
    public $grab_method = [];
    
    // Delivery Method (conditional checkboxes)
    public $delivery_method = [];
    public $show_delivery_method = false;
    
    // Compliance
    public $rule_accepted = false;
    
    // Captcha
    public $captcha = '';
    public $captcha_value = '';

    protected function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'id_card_number' => 'required|string|max:255',
            'address' => 'required|string',
            'occupation' => 'required|string|max:255',
            'mobile' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'content' => 'required|string',
            'used_for' => 'required|string',
            'grab_method' => 'required|array|min:1',
            'delivery_method' => 'required_if:show_delivery_method,true|array|min:1',
            'rule_accepted' => 'accepted',
            'captcha' => 'required|string',
        ];
    }

    protected $messages = [
        'grab_method.required' => 'Please select at least one acquisition method.',
        'grab_method.min' => 'Please select at least one acquisition method.',
        'delivery_method.required_if' => 'Please select at least one delivery method.',
        'delivery_method.min' => 'Please select at least one delivery method.',
        'rule_accepted.accepted' => 'You must accept the terms and conditions.',
        'captcha.required' => 'Please enter the captcha code.',
    ];

    public function mount()
    {
        $this->generateCaptcha();
    }

    public function updated($propertyName)
    {
        // Check if hardcopy or softcopy is selected
        if ($propertyName === 'grab_method') {
            $this->show_delivery_method = !empty(array_intersect($this->grab_method, ['hardcopy', 'softcopy']));
            
            // Clear delivery_method if not shown
            if (!$this->show_delivery_method) {
                $this->delivery_method = [];
            }
        }
    }

    public function generateCaptcha()
    {
        // Simple captcha implementation
        $characters = '23456789ABCDEFGHJKLMNPQRSTUVWXYZ';
        $this->captcha_value = '';
        for ($i = 0; $i < 6; $i++) {
            $this->captcha_value .= $characters[rand(0, strlen($characters) - 1)];
        }
        session(['captcha_value' => $this->captcha_value]);
    }

    public function refreshCaptcha()
    {
        $this->generateCaptcha();
        $this->captcha = '';
    }

    public function save()
    {
        // Validate captcha
        if (strtoupper($this->captcha) !== session('captcha_value')) {
            $this->addError('captcha', 'Captcha code is incorrect.');
            $this->refreshCaptcha();
            return;
        }

        $this->validate();

        // Generate registration code
        $registrationCode = $this->generateKodeRegister();

        InformationRequest::create([
            'name' => $this->name,
            'id_card_number' => $this->id_card_number,
            'address' => $this->address,
            'occupation' => $this->occupation,
            'mobile' => $this->mobile,
            'email' => $this->email,
            'content' => $this->content,
            'used_for' => $this->used_for,
            'grab_method' => $this->grab_method,
            'delivery_method' => $this->delivery_method,
            'rule_accepted' => $this->rule_accepted,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'status' => 'pending',
            'registration_code' => $registrationCode,
        ]);

        session()->flash('message', __('Your information request has been submitted successfully. We will process your request shortly. Registration Code: ') . $registrationCode);
        session()->flash('registration_code', $registrationCode);

        $this->reset([
            'name', 'id_card_number', 'address', 'occupation', 'mobile', 'email',
            'content', 'used_for', 'grab_method', 'delivery_method', 'rule_accepted', 'captcha'
        ]);
        
        $this->show_delivery_method = false;
        $this->refreshCaptcha();
    }

    private function generateKodeRegister()
    {
        do {
            $kode = strtoupper(substr(str_shuffle("ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789"), 0, 6));
            $exists = InformationRequest::where('registration_code', $kode)->exists();
        } while ($exists);

        return $kode;
    }

    public function render()
    {
        return view('livewire.information.request');
    }
}
