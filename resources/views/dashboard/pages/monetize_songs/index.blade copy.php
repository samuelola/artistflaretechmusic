@extends('dashboard.index')
@section('title')
  Monetize Songs
@endsection
@section('content')

@include('sweetalert::alert')

 <style>

body{
background:#f5f7fb;
font-family:system-ui;
}

.wizard-card{
background:white;
border-radius:16px;
padding:40px;
box-shadow:0 15px 45px rgba(0,0,0,0.08);
max-width:1000px;
margin:auto;
}

.form-step{
display:none;
animation:fade .4s;
}

.form-step.active{
display:block;
}

@keyframes fade{
from{opacity:0;transform:translateY(10px);}
to{opacity:1;transform:translateY(0);}
}

/* STEP NAVIGATION */

.wizard-steps{
display:flex;
justify-content:space-between;
margin-bottom:25px;
}

.step{
flex:1;
text-align:center;
cursor:pointer;
}

.step-circle{
width:36px;
height:36px;
border-radius:50%;
background:#dee2e6;
margin:auto;
line-height:36px;
font-weight:600;
}

.step.active .step-circle{
background:#700084;
color:white;
}

.step.completed .step-circle{
background:#198754;
color:white;
}

.step-label{
font-size:12px;
margin-top:6px;
}

/* UPLOAD */

.upload-box{
border:2px dashed #d9d9d9;
border-radius:12px;
padding:30px;
text-align:center;
cursor:pointer;
background:#fafafa;
transition:.3s;
}

.upload-box:hover{
border-color:#700084;
background:#f0f4ff;
}

/* REVIEW */

.review-box{
background:#f9fafc;
padding:20px;
border-radius:10px;
margin-bottom:15px;
}

</style>
<!-- Select2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<!-- Select2 JS -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<main class="dashboard-main">
  <div class="navbar-header">
    <div class="row align-items-center justify-content-between">
    <div class="col-auto">
      <div class="d-flex flex-wrap align-items-center gap-4">
        <button type="button" class="sidebar-toggle">
          <iconify-icon icon="heroicons:bars-3-solid" class="icon text-2xl non-active"></iconify-icon>
          <iconify-icon icon="iconoir:arrow-right" class="icon text-2xl active"></iconify-icon>
        </button>
        <button type="button" class="sidebar-mobile-toggle">
          <iconify-icon icon="heroicons:bars-3-solid" class="icon"></iconify-icon>
        </button>
        <form class="navbar-search">
          <input type="text" name="search" placeholder="Search">
          <iconify-icon icon="ion:search-outline" class="icon"></iconify-icon>
        </form>
      </div>
    </div>
    @include('dashboard.subheader')
    </div>
  </div> 

  @include('dashboard.ping')
  
  <div class="dashboard-main-body">
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
  <!-- <h6 class="fw-semibold mb-0">All Subscriptions</h6> -->



        

   
            <!--new container -->
              <div class="container mt-5">
                   <div class="wizard-card">
                      <h5 class="mb-3 text-center">Catalog Submission & Ownership Verification Form</h5>
                      <div class="progress mb-4">
                         <div class="progress-bar" style="background-color:#700084;" id="progressBar" style="width:14%"></div>
                      </div>
                      
                       <!-- STEP INDICATOR -->
                          <div class="wizard-steps" style="margin-top:30px;">

                              <div class="step active" data-step="0">
                                  <div class="step-circle">1</div>
                                  <div class="step-label">Identity</div>
                              </div>

                              <div class="step" data-step="1">
                                  <div class="step-circle">2</div>
                                  <div class="step-label">Role</div>
                              </div>

                              <div class="step" data-step="2">
                                  <div class="step-circle">3</div>
                                  <div class="step-label">Song</div>
                              </div>

                              <div class="step" data-step="3">
                                  <div class="step-circle">4</div>
                                  <div class="step-label">Songwriters</div>
                              </div>

                              <div class="step" data-step="4">
                                  <div class="step-circle">5</div>
                                  <div class="step-label">Copyright</div>
                              </div>

                              <div class="step" data-step="5">
                                  <div class="step-circle">6</div>
                                  <div class="step-label">Payment</div>
                              </div>

                              <div class="step" data-step="6">
                                  <div class="step-circle">7</div>
                                  <div class="step-label">Submit</div>
                              </div>

                          </div>
                       <!-- End Step Indicator-->

                        <form id="artistWizard" enctype="multipart/form-data">

                        <!-- STEP 1 -->

                          <div class="form-step active">
                             <div class="step-title mb-3" style="font-weight: 600;">Artist Identity</div>
                              <div class="row">
                                  <div class="col-md-6 mb-3">
                                      <label>Full Legal Name *</label>
                                      <input type="text" class="form-control" name="full_name">
                                  </div>

                                  <div class="col-md-6 mb-3">
                                      <label>Artist / Stage Name *</label>
                                      <input type="text" class="form-control" name="stage_name">
                                  </div>

                                  <div class="col-md-6 mb-3">
                                      <label>Date of Birth *</label>
                                      <input type="date" class="form-control" name="dob">
                                  </div>

                                  <div class="col-md-6 mb-3">
                                        <label>Nationality *</label>
                                        <select class="form-select select2" name="nationality">
                                            <option>Select</option>
                                            @foreach($all_countries as $country)
                                            <option value="{{$country->name}}">{{$country->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label>Country of Residence *</label>
                                        <select class="form-select select2" name="country">
                                            <option>Select</option>
                                            @foreach($all_countries as $country)
                                            <option value="{{$country->name}}">{{$country->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>


                                  <div class="col-md-6 mb-3">
                                      <label>Phone *</label>
                                      <input type="tel" class="form-control" name="phone">
                                  </div>

                                  <div class="col-md-6 mb-3">
                                      <label>Email *</label>
                                      <input type="email" class="form-control" name="email">
                                  </div>
                              </div>

                              <h6 style="font-size: 18px !important;" class="mt-4">Promotion Links (Optional)</h6>
                              <div class="row">
                                  <div class="col-md-6 mb-3">
                                      <label>YouTube Video</label>
                                      <input type="url" class="form-control" name="youtube">
                                  </div>
                                  <div class="col-md-6 mb-3">
                                      <label>Instagram</label>
                                      <input type="text" class="form-control" name="instagram">
                                  </div>
                                  <div class="col-md-6 mb-3">
                                      <label>Facebook</label>
                                      <input type="text" class="form-control" name="facebook">
                                  </div>
                                  <div class="col-md-6 mb-3">
                                      <label>TikTok</label>
                                      <input type="text" class="form-control" name="tiktok">
                                  </div>
                              </div>

                              <h6 style="font-size: 18px !important;" class="mt-4">Identity Verification</h6>
                              <div class="row">
                                  <div class="col-md-6 mb-3">
                                      <label>ID Type</label>
                                      <select class="form-select" name="id_type">
                                          <option value="">Select</option>
                                          <option value="Passport">Passport</option>
                                          <option value="National ID">National ID</option>
                                          <option value="Driver's License">Driver's License</option>
                                      </select>
                                  </div>
                                  <div class="col-md-6 mb-3">
                                      <label>Upload Government ID *</label>
                                      <input type="file" class="form-control" name="government_id">
                                  </div>
                              </div>

                              <button type="submit" class="btn btn-primary mt-3" id="step1Submit">Save & Continue</button>

                          </div>


                        <!-- STEP 2 -->

                        <div class="form-step">

                            <div class="step-title mb-3" style="font-weight: 600;">Artist Role & Rights Ownership</div>

                            <div class="mb-3">
                                <label>Your Role</label>
                                <select class="form-select">
                                    <option>Artist</option>
                                    <option>Producer</option>
                                    <option>Songwriter</option>
                                    <option>Label Representative</option>
                                </select>
                            </div>

                            <div class="mb-3">
                              <label>Rights Ownership</label>

                                <select class="form-select" id="ownershipSelect">
                                  <option value="100">I own 100% of the master recording</option>
                                  <option value="co">I co-own the master recording</option>
                                  <option value="represent">I represent the rights holder</option>
                                  <option value="authorized">I have written authorization to submit this music</option>
                                </select>
                              </div>


                              <!-- CO-OWNERSHIP SECTION -->

                              <div id="coOwnershipSection" style="display:none;">

                              <div class="mb-3">
                                <label>Your Ownership Percentage (%)</label>
                                <input type="number" class="form-control" min="1" max="100" placeholder="Enter your ownership percentage">
                              </div>


                              <h6 class="mt-3">Other Rights Holders</h6>

                              <div id="rightsHolders"></div>

                              <button type="button" class="btn btn-outline-primary-600 mt-2" id="addHolder">
                              Add Rights Holder
                              </button>

                              </div>

                        </div>


                        <!-- STEP 3 -->

                        <div class="form-step">

                            <div class="step-title mb-3" style="font-weight: 600;">Song Upload & Metadata</div>

                            <div class="upload-box mb-3">
                                Upload Song File<br>
                                <small>Drag & drop MP3 / WAV</small>
                                <input type="file" hidden>
                            </div>

                        </div>


                        <!-- STEP 4 -->

                          <div class="form-step">

                              <div class="step-title mb-3" style="font-weight: 600;">Songwriter & Publishing</div>

                              <h6 class="mb-3">Contributors</h6>

                              <div class="table-responsive">

                                  <table class="table table-bordered align-middle" id="contributorsTable">

                                      <thead class="table-light">
                                          <tr>
                                              <th>Name</th>
                                              <th>Role</th>
                                              <th>Percentage (%)</th>
                                              <th style="width:80px;">Action</th>
                                          </tr>
                                      </thead>

                                      <tbody id="contributorsBody">

                                          <tr>

                                              <td>
                                                  <input type="text" class="form-control" placeholder="Contributor name">
                                              </td>

                                              <td>
                                                  <select class="form-select">
                                                      <option>Songwriter</option>
                                                      <option>Producer</option>
                                                      <option>Composer</option>
                                                      <option>Lyricist</option>
                                                  </select>
                                              </td>

                                              <td>
                                                  <input type="number" class="form-control percentage" min="1" max="100" placeholder="%">
                                              </td>

                                              <td>
                                                  <button type="button" class="btn btn-danger removeContributor">X</button>
                                              </td>

                                          </tr>

                                      </tbody>

                                  </table>

                              </div>

                              <button type="button" class="btn btn-outline-primary mt-2" id="addContributor">
                                  Add Contributor
                              </button>

                              <div class="mt-3 text-muted">
                                  Total Percentage: <strong id="totalPercent">0%</strong>
                              </div>

                          </div>


                        

                          <!-- STEP 5 -->

                            <div class="form-step">

                                <div class="step-title mb-3" style="font-weight: 600;">Copyright & Rights</div>

                                <p class="text-muted mb-4">
                                    Please confirm the following statements before continuing.
                                </p>

                                <div class="form-check d-flex align-items-start mb-3">
                                    <input class="form-check-input me-2 mt-1 rights-check" type="checkbox" id="rights1">
                                    <label class="form-check-label" for="rights1">
                                        I confirm that I own or control the rights to the submitted recordings.
                                    </label>
                                </div>

                                <div class="form-check d-flex align-items-start mb-3">
                                    <input class="form-check-input me-2 mt-1 rights-check" type="checkbox" id="rights2">
                                    <label class="form-check-label" for="rights2">
                                        The submitted recordings do not infringe on any third-party copyrights.
                                    </label>
                                </div>

                                <div class="form-check d-flex align-items-start mb-3">
                                    <input class="form-check-input me-2 mt-1 rights-check" type="checkbox" id="rights3">
                                    <label class="form-check-label" for="rights3">
                                        All samples used in the recordings are properly cleared.
                                    </label>
                                </div>

                                <div class="form-check d-flex align-items-start mb-3">
                                    <input class="form-check-input me-2 mt-1 rights-check" type="checkbox" id="rights4">
                                    <label class="form-check-label" for="rights4">
                                        No legal disputes exist regarding the ownership of these works.
                                    </label>
                                </div>

                                <div class="form-check d-flex align-items-start mb-3">
                                    <input class="form-check-input me-2 mt-1 rights-check" type="checkbox" id="rights5">
                                    <label class="form-check-label" for="rights5">
                                        I have the authority to submit these recordings for catalog evaluation.
                                    </label>
                                </div>

                                <div class="alert alert-warning mt-4">
                                    <strong>Important:</strong> Providing false information may lead to rejection of your submission or removal from
                                    the platform.
                                </div>

                            </div>
                        <!-- End STEP 5 -->

                       <!-- STEP 6 -->


                          <div class="form-step">

                              <div class="step-title mb-3" style="font-weight: 600;">Payment Information</div>

                              <div class="mb-3">
                                  <label>Preferred Payout Method *</label>
                                  <select class="form-select" id="payoutMethod" required>
                                      <option value="">Select Method</option>
                                      <option value="bank">Bank Transfer</option>
                                      <option value="mobile">Mobile Money</option>
                                      <option value="other">Other</option>
                                  </select>
                              </div>

                              <!-- BANK FIELDS -->

                              <div id="bankFields" style="display:none;">

                                  <div class="mb-3">
                                      <label>Bank Name *</label>
                                      <input type="text" class="form-control" id="bankName">
                                  </div>

                                  <div class="mb-3">
                                      <label>Account Name *</label>
                                      <input type="text" class="form-control" id="bankAccountName">
                                  </div>

                                  <div class="mb-3">
                                      <label>Account Number *</label>
                                      <input type="number" class="form-control" id="bankAccountNumber">
                                  </div>

                                  <div class="mb-3">
                                      <label>Country *</label>
                                      <select class="form-select" id="bankCountry">
                                          <option>Select Country</option>
                                          <option>USA</option>
                                          <option>UK</option>
                                          <option>Nigeria</option>
                                      </select>
                                  </div>

                              </div>

                              <!-- MOBILE MONEY FIELDS -->

                              <div id="mobileFields" style="display:none;">

                                  <div class="mb-3">
                                      <label>Mobile Money Number *</label>
                                      <input type="tel" class="form-control" id="mobileNumber">
                                  </div>

                                  <div class="mb-3">
                                      <label>Account Name *</label>
                                      <input type="text" class="form-control" id="mobileAccountName">
                                  </div>

                                  <div class="mb-3">
                                      <label>Country *</label>
                                      <select class="form-select" id="mobileCountry">
                                          <option>Select Country</option>
                                          <option>Ghana</option>
                                          <option>Kenya</option>
                                          <option>Nigeria</option>
                                      </select>
                                  </div>

                              </div>

                              <!-- OTHER METHOD FIELDS -->

                              <div id="otherFields" style="display:none;">

                                  <div class="mb-3">
                                      <label>Description / Account Info *</label>
                                      <input type="text" class="form-control" id="otherAccountInfo">
                                  </div>

                              </div>

                          </div>

                        <!-- STEP 7 -->

                          <div class="form-step">

                              <div class="step-title mb-3" style="font-weight: 600;">Review & Submit</div>

                              <!-- Final Notice -->
                              <div class="alert alert-info">
                                  <p><strong>Important Notice:</strong></p>
                                  <ul>
                                      <li>Songs will be reviewed before selection.</li>
                                      <li>Submission does not guarantee payout.</li>
                                      <li>Selected artists will receive payment within 3 months.</li>
                                  </ul>
                              </div>

                              <!-- Required Confirmations -->
                              <h6 class="mb-3">Confirmations</h6>

                              <div class="form-check mb-2">
                                  <input class="form-check-input review-check" type="checkbox" id="confirm1" required>
                                  <label class="form-check-label" for="confirm1">
                                      I understand and accept the review process.
                                  </label>
                              </div>

                              <div class="form-check mb-2">
                                  <input class="form-check-input review-check" type="checkbox" id="confirm2" required>
                                  <label class="form-check-label" for="confirm2">
                                      I understand that submission does not guarantee selection or payment.
                                  </label>
                              </div>

                              <div class="form-check mb-3">
                                  <input class="form-check-input review-check" type="checkbox" id="confirm3" required>
                                  <label class="form-check-label" for="confirm3">
                                      I agree to the FlareTechMusic Terms & Conditions.
                                  </label>
                              </div>

                              <!-- Digital Signature -->
                              <h6 class="mb-3">Digital Signature</h6>

                              <div class="mb-3">
                                  <label>Full Legal Name *</label>
                                  <input type="text" class="form-control" id="digitalName" required>
                              </div>

                              <div class="mb-3">
                                  <label>Date</label>
                                  <input type="text" class="form-control" id="digitalDate" readonly>
                              </div>

                              <!-- Submit Button -->
                              <button type="submit" class="btn btn-success btn-lg">Submit Catalog for Review</button>

                          </div>


                        <!-- NAVIGATION -->

                        <div class="d-flex justify-content-between mt-4">

                            <button type="button" class="btn btn-secondary" id="prevBtn">Back</button>

                            <button type="button" class="btn btn-primary-600" id="nextBtn">Next</button>

                        </div>

                        </form>

                   </div>
              </div>
            <!--end new container-->
          
          </div>
      </div>
    </div>
  </div>

@endsection

@section('script')
   

<script>
document.addEventListener("DOMContentLoaded", function () {

    let currentStep = 0;

    const steps = document.querySelectorAll(".form-step");
    const indicators = document.querySelectorAll(".step");

    const nextBtn = document.getElementById("nextBtn");
    const prevBtn = document.getElementById("prevBtn");

    function updateWizard() {
        // Show the correct step
        steps.forEach((step, i) => {
            step.classList.toggle("active", i === currentStep);
        });

        // Update step indicators
        indicators.forEach((indicator, i) => {
            indicator.classList.toggle("active", i <= currentStep);
        });

        // Update progress bar
        let progress = ((currentStep + 1) / steps.length) * 100;
        document.getElementById("progressBar").style.width = progress + "%";

        // Hide back button on first step
        prevBtn.style.display = currentStep === 0 ? "none" : "inline-block";

        // Change next button text on last step
        nextBtn.innerText = currentStep === steps.length - 1 ? "Submit" : "Next";
    }

    // Next button
    nextBtn.addEventListener("click", function () {
        if (currentStep < steps.length - 1) {
            currentStep++;
            updateWizard();
        } else {
            document.getElementById("artistWizard").submit();
        }
    });

    // Back button
    prevBtn.addEventListener("click", function () {
        if (currentStep > 0) {
            currentStep--;
            updateWizard();
        }
    });

    // CLICKABLE STEP INDICATORS
    indicators.forEach(stepIndicator => {
        stepIndicator.addEventListener("click", function () {
            let target = parseInt(this.dataset.step);
            currentStep = target;  // allow moving forward or backward
            updateWizard();
        });
    });

    // Initialize wizard
    updateWizard();

});
</script>


<!--Step 1 submission-->


<script>
$(document).ready(function(){

    $('#step1Submit').on('click', function(e){
        e.preventDefault();

        let formData = new FormData(this);


        // Remove previous validation errors
        $('.invalid-feedback').remove();
        $('.is-invalid').removeClass('is-invalid');

        $.ajax({
            url: "{{ route('artist.step1') }}", // Laravel route
            method: "POST",
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            },
            success: function(response){
                if(response.success){
                    alert("Step 1 saved successfully!");
                    // Move wizard to next step
                    currentStep++;
                    updateWizard();
                    // Save artist ID for future steps if needed
                    window.artistId = response.artist_id;
                }
            },
            error: function(xhr){
                if(xhr.status === 422){
                    let errors = xhr.responseJSON.errors;
                    $.each(errors, function(field, messages){
                        let input = $('[name="'+field+'"]');
                        input.addClass('is-invalid');
                        input.after('<div class="invalid-feedback">'+messages[0]+'</div>');
                    });
                } else {
                    alert("An error occurred. Please try again.");
                }
            }
        });
    });

});
</script>

<!--End Step 1 submission-->


<script>

const ownershipSelect = document.getElementById("ownershipSelect");
const coOwnershipSection = document.getElementById("coOwnershipSection");
const holdersContainer = document.getElementById("rightsHolders");
const addHolderBtn = document.getElementById("addHolder");

/* SHOW/HIDE CO OWNERSHIP */

ownershipSelect.addEventListener("change", function(){

if(this.value === "co"){
coOwnershipSection.style.display = "block";
}else{
coOwnershipSection.style.display = "none";
}

});


/* ADD RIGHTS HOLDER */

addHolderBtn.addEventListener("click", function(){

const holder = document.createElement("div");

holder.classList.add("row","mb-3","holder-row");

holder.innerHTML = `

<div class="col-md-4">
    <input type="text" class="form-control" placeholder="Name">
</div>

<div class="col-md-4">
    <input type="text" class="form-control" placeholder="Role">
</div>

<div class="col-md-3">
    <input type="number" class="form-control" min="1" max="100" placeholder="% Ownership">
</div>

<div class="col-md-1">
    <button type="button" class="btn btn-danger removeHolder">X</button>
</div>

`;

holdersContainer.appendChild(holder);

});


/* REMOVE HOLDER */

document.addEventListener("click", function(e){

if(e.target.classList.contains("removeHolder")){
e.target.closest(".holder-row").remove();
}

});

</script>



<script>

const contributorsBody = document.getElementById("contributorsBody");
const addContributorBtn = document.getElementById("addContributor");
const totalPercent = document.getElementById("totalPercent");


/* ADD CONTRIBUTOR */

addContributorBtn.addEventListener("click", function(){

const row = document.createElement("tr");

row.innerHTML = `

<td>
    <input type="text" class="form-control" placeholder="Contributor name">
</td>

<td>
    <select class="form-select">
        <option>Songwriter</option>
        <option>Producer</option>
        <option>Composer</option>
        <option>Lyricist</option>
    </select>
</td>

<td>
    <input type="number" class="form-control percentage" min="1" max="100" placeholder="%">
</td>

<td>
    <button type="button" class="btn btn-danger removeContributor">X</button>
</td>

`;

contributorsBody.appendChild(row);

});


/* REMOVE CONTRIBUTOR */

document.addEventListener("click", function(e){

if(e.target.classList.contains("removeContributor")){
e.target.closest("tr").remove();
calculateTotal();
}

});


/* CALCULATE TOTAL PERCENTAGE */

document.addEventListener("input", function(e){

if(e.target.classList.contains("percentage")){
calculateTotal();
}

});

function calculateTotal(){

let total = 0;

document.querySelectorAll(".percentage").forEach(function(input){

let val = parseFloat(input.value);

if(!isNaN(val)){
total += val;
}

});

totalPercent.innerText = total + "%";

}

</script>

<script>
const payoutMethod = document.getElementById("payoutMethod");
const bankFields = document.getElementById("bankFields");
const mobileFields = document.getElementById("mobileFields");
const otherFields = document.getElementById("otherFields");

payoutMethod.addEventListener("change", function() {
    const val = this.value;

    bankFields.style.display = val === "bank" ? "block" : "none";
    mobileFields.style.display = val === "mobile" ? "block" : "none";
    otherFields.style.display = val === "other" ? "block" : "none";

    // Clear hidden fields when switching
    if(val !== "bank") bankFields.querySelectorAll("input, select").forEach(i => i.value = "");
    if(val !== "mobile") mobileFields.querySelectorAll("input, select").forEach(i => i.value = "");
    if(val !== "other") otherFields.querySelectorAll("input, select").forEach(i => i.value = "");
});
</script>


<script>
// Auto-fill today's date in STEP 7
const digitalDate = document.getElementById("digitalDate");
const today = new Date();
digitalDate.value = today.toLocaleDateString();
</script>

<script>
$(document).ready(function() {
    $('.select2').select2({
        placeholder: "Select an option",
        allowClear: true
    });
});
</script>

@endsection



