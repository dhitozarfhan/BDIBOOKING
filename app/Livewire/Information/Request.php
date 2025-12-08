<?php

namespace App\Livewire\Information;

use App\Models\InformationRequest;
use App\Enums\ResponseStatus;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class Request extends Component
{
    // View State
    public $currentView = 'form';
    public $registration_code = '';
    public $reportDetail;
    public $statusError = '';
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
    
    protected function rules()
    {
        if ($this->currentView === 'status') {
            return [
                'registration_code' => 'required|string|max:255',
            ];
        }

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
            'rule_accepted' => 'accepted',
        ];
    }

    protected $messages = [
        'grab_method.required' => 'Please select at least one acquisition method.',
        'grab_method.min' => 'Please select at least one acquisition method.',
        'rule_accepted.accepted' => 'You must accept the terms and conditions.',
    ];

    public function mount()
    {
        $currentRoute = request()->route()->getName();
        if (str_contains($currentRoute, 'information.request.status')) {
            $this->currentView = 'status';
        } else {
            $this->currentView = 'form';
        }
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

                        public function save()

                        {

                            try {

                                $validatedData = $this->validate();

                    

                                // Generate registration code

                                $registrationCode = $this->generateKodeRegister();

                    

                                $infoRequest = InformationRequest::create([

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

                                    'registration_code' => $registrationCode,

                                ]);

                    

                                // Buat "wadah jawaban"-nya (ReportProcess) secara otomatis

                                $infoRequest->process()->create([

                                    'response_status_id' => 1,

                                    'is_completed' => false,

                                ]);

                    

                                session()->flash('message', __('Your information request has been submitted successfully. We will process your request shortly. Registration Code: '));

                                session()->flash('registration_code', $registrationCode);

                    

                                $this->reset([

                                    'name', 'id_card_number', 'address', 'occupation', 'mobile', 'email',

                                    'content', 'used_for', 'grab_method', 'delivery_method', 'rule_accepted'

                                ]);

                                

                                $this->show_delivery_method = false;

                    

                            } catch (\Illuminate\Validation\ValidationException $e) {

                                Log::error('Validation failed.', ['errors' => $e->errors()]);

                                throw $e; // Re-throw validation exception for Livewire to handle

                            } catch (\Throwable $e) {

                                Log::error('An error occurred during the save process: ' . $e->getMessage(), [

                                    'file' => $e->getFile(),

                                    'line' => $e->getLine(),

                                    'trace' => $e->getTraceAsString(),

                                ]);

                                session()->flash('error', 'An unexpected error occurred. Please try again later.');

                            }

                        }

    private function generateKodeRegister()
    {
        do {
            $kode = strtoupper(substr(str_shuffle("ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789"), 0, 6));
            $exists = InformationRequest::where('registration_code', $kode)->exists();
        } while ($exists);

        return $kode;
    }

    public function checkStatus()
    {
        $this->validate([
            'registration_code' => 'required|string|max:255',
        ]);

        $infoRequest = InformationRequest::where('registration_code', $this->registration_code)->first();

        if ($infoRequest) {
            $process = $infoRequest->process; // HasOne relation

            $reportDetail = new \stdClass();
            $reportDetail->subject = __('Information Request');
            $reportDetail->name = $infoRequest->name;
            $reportDetail->mobile = $infoRequest->mobile ? substr($infoRequest->mobile, 0, -4) . 'xxxx' : '-';
            $reportDetail->time_insert = $infoRequest->created_at;
            $reportDetail->content = $infoRequest->content;

            if ($process) {
                $reportDetail->status = $process->response_status_id;
                $reportDetail->answer = $process->answer;
                $reportDetail->answer_attachment = $process->answer_attachment;
            } else {
                $reportDetail->status = ResponseStatus::Initiation->value;
                $reportDetail->answer = null;
                $reportDetail->answer_attachment = null;
            }
            
            // Add process history if needed, or just use the single process record
            // For consistency with WBS/Gratification which might have multiple processes (though usually one active flow)
            // We'll just pass the single process for now as per current structure

            $this->reportDetail = $reportDetail;
            $this->currentView = 'response';

        } else {
            session()->flash('statusError', __('Registration code not found.'));
            return redirect()->route('information.request.status');
        }
    }

    public function setView($view)
    {
        $this->currentView = $view;
        if ($view === 'form') {
            $this->reset(['registration_code', 'statusError', 'reportDetail']);
        }
    }

    public function render()
    {
        if ($this->currentView === 'status') {
            return view('livewire.information.request-status');
        } elseif ($this->currentView === 'response') {
            return view('livewire.information.request-response');
        }
        
        return view('livewire.information.request');
    }
}
