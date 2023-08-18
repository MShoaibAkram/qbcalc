@extends("layouts.main")

@section('title', 'Update History')

@section('breadcump')
<div class="col-sm-6">
    <h1 class="m-0">{{ __('Update History') }}</h1>
</div>
<div class="col-sm-6">
    <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item"><a href="{{ route('backend.dashboard.index') }}">{{ __('Home') }}</a></li>
        <li class="breadcrumb-item active">{{ __('Update History') }}</li>
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
                      <span class="bs-stepper-label">Date Processed</span>
                    </button>
                  </div>
                </div>
                <div class="bs-stepper-content">
                  <!-- your steps content here -->
                  <div id="firstStepPart" class="content" role="tabpanel" aria-labelledby="first-step-trigger">
                      <div class="row">
                          <div class="col-2">

                          </div>
                          <div class="col-10">
                              This function saves invoices to history and flags those
                              invoices (or payments) as processed.
                              If you wish to send Bills to Quickbooks you must do so before
                              performing this function.
                              <br>
                              Do you wish to wish to Update History now?
                          </div>
                      </div>
                      <div class="d-flex justify-content-center mt-5"><button class="btn btn-success" onclick="nextStep()">Next</button></div>
                  </div>
                  <div id="secondStepPart" class="content" role="tabpanel" aria-labelledby="second-step-trigger">
                      <div class="row form-group">
                        <label for="processDate" class="form-control-label">Processing Date/Time Stamp</label>
                        <input type="date" id="processDate" class="form-control form-control-border"/>
                      </div>
                      <div class="row justify-content-center mt-2">
                          <button class="btn btn-success" onclick="completeStep()">OK</button>
                          <button class="btn btn-danger ml-2" onclick="resetStep()">Cancel</button>
                      </div>
                  </div>
                </div>
              </div>
        </div>
    </div>
</div>
@endsection