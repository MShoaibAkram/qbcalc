@extends("layouts.main")

@section('title', 'Send Bills to QB')

@section('breadcump')
<div class="col-sm-6">
    <h1 class="m-0">{{ __('Send Bills to QB') }}</h1>
</div>
<div class="col-sm-6">
    <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item"><a href="{{ route('backend.dashboard.index') }}">{{ __('Home') }}</a></li>
        <li class="breadcrumb-item active">{{ __('Send Bills to QB') }}</li>
    </ol>
</div>
@endsection

@section('main')
<script>
    const stepper = {
        instance : ''
    }
    $(document).ready(() => {
        stepper.instance = new Stepper($(".bs-stepper")[0], {
            linear: true,
            animation: false
        });
    })
    const nextStep = () => {
        stepper.instance.next();
    }
    const completeStep = () => {
        
    }
    const resetStep = () => {
        stepper.instance.reset();
    }
</script>
<div id="section" class="row justify-content-center">
    <div class="col-7">
        <!-- small box -->
        <div class="small-box p-3">
            <div class="bs-stepper">
                <div class="bs-stepper-header" role="tablist">
                  <!-- your steps here -->
                  <div class="step" data-target="#firstStepPart">
                    <button type="button" class="step-trigger" role="tab" aria-controls="first-step" id="firstStepTrigger">
                      <span class="bs-stepper-circle">1</span>
                      <span class="bs-stepper-label">Confirmation</span>
                    </button>
                  </div>
                  <div class="line"></div>
                  <div class="step" data-target="#secondStepPart">
                    <button type="button" class="step-trigger" role="tab" aria-controls="second-step" id="secondStepTrigger">
                      <span class="bs-stepper-circle">2</span>
                      <span class="bs-stepper-label">Enter Posting Date</span>
                    </button>
                  </div>
                </div>
                <div class="bs-stepper-content">
                  <!-- your steps content here -->
                  <div id="firstStepPart" class="content" role="tabpanel" aria-labelledby="first-step-trigger">
                      <div class="row">
                            In order to process payments, an expense account named 'Commissions'
                             must be set up as a GL account, and each rep name must be set up as a vendor.
                             If you have already created the account, then click Yes to continue--otherwise,
                             click No
                      </div>
                      <div class="d-flex justify-content-center mt-5"><button class="btn btn-success" onclick="nextStep()">Next</button></div>
                  </div>
                  <div id="secondStepPart" class="content" role="tabpanel" aria-labelledby="second-step-trigger">
                    <div class="row form-group">
                        <div class="col-9">
                            <label>Please enter a posting date for the QuickBooks Bills</label>
                        </div>
                        <div class="col-3 d-flex flex-column">
                            <button class="btn btn-success" onclick="completeStep()">OK</button>
                            <button class="btn btn-danger mt-2" onclick="resetStep()">Cancel</button>
                        </div>
                    </div>
                    <div class="mt-2">
                        <div class="form-group">
                            <input class="form-control form-control-border w-50" type="date" id="date" name="date"/>
                        </div>
                    </div>
                  </div>
                </div>
              </div>
            
        </div>
    </div>
</div>
@endsection