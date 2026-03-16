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

                <form id="step1Form" class="form-step active" enctype="multipart/form-data">
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
                                <option value="">Select</option>
                                @foreach($all_countries as $country)
                                <option value="{{$country->name}}">{{$country->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Country of Residence *</label>
                            <select class="form-select select2" name="country">
                                <option value="">Select</option>
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

                    <h6 class="mt-4">Promotion Links (Optional)</h6>
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

                    <h6 class="mt-4">Identity Verification</h6>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>ID Type *</label>
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

                    <div class="d-flex justify-content-between mt-3">
                        
                        <button type="submit" class="btn btn-primary">Save & Next</button>
                    </div>
                </form>


                <form id="step2Form" class="form-step">
                    <div class="step-title mb-3" style="font-weight: 600;">Artist Role & Rights Ownership</div>

                    <div class="mb-3">
                        <label>Your Role *</label>
                        <select class="form-select" name="role">
                            <option value="">Select</option>
                            <option value="Artist">Artist</option>
                            <option value="Producer">Producer</option>
                            <option value="Songwriter">Songwriter</option>
                            <option value="Label Representative">Label Representative</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label>Rights Ownership *</label>
                        <select class="form-select" id="ownershipSelect" name="rights_ownership">
                            <option value="100">I own 100% of the master recording</option>
                            <option value="co">I co-own the master recording</option>
                            <option value="represent">I represent the rights holder</option>
                            <option value="authorized">I have written authorization to submit this music</option>
                        </select>
                    </div>

                    <div id="coOwnershipSection" style="display:none;">
                        <div class="mb-3">
                            <label>Your Ownership Percentage (%)</label>
                            <input type="number" class="form-control" min="1" max="100" name="ownership_percentage">
                        </div>

                        <h6 class="mt-3">Other Rights Holders</h6>
                        <div id="rightsHolders"></div>
                        <button type="button" class="btn btn-outline-primary mt-2" id="addHolder">Add Rights Holder</button>
                    </div>

                    <div class="d-flex justify-content-between mt-3">
                        <button type="button" class="btn btn-secondary" id="step2Back">Back</button>
                        <button type="submit" class="btn btn-primary">Save & Next</button>
                    </div>
                </form>

                <form id="step3Form" class="form-step" style="display:none;" enctype="multipart/form-data">
                        <div class="step-title mb-3" style="font-weight: 600;">Song Upload & Metadata</div>

                        <div class="mb-3">
                            <label>Upload Song *</label>
                            <input type="file" class="form-control" name="song_file">
                        </div>

                        <div class="mb-3">
                            <label>Song Title *</label>
                            <input type="text" class="form-control" name="song_title">
                        </div>

                        <div class="mb-3">
                            <label>Genre *</label>
                            <input type="text" class="form-control" name="genre">
                        </div>

                        <div class="mb-3">
                            <label>Release Date *</label>
                            <input type="date" class="form-control" name="release_date">
                        </div>

                        <div class="d-flex justify-content-between mt-3">
                            <button type="button" class="btn btn-secondary" id="step3Back">Back</button>
                            <button type="submit" class="btn btn-primary">Save & Next</button>
                        </div>
                  </form>

                  <!--end of form -->

                  <form id="step4Form" class="form-step" style="display:none;">
                    <div class="step-title mb-3" style="font-weight: 600;">Songwriter & Publishing</div>

                    <table class="table">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Role</th>
                                <th>Percentage</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody id="contributorsBody"></tbody>
                    </table>
                    <button type="button" class="btn btn-outline-primary" id="addContributor">Add Contributor</button>

                    <div class="d-flex justify-content-between mt-3">
                        <button type="button" class="btn btn-secondary" id="step4Back">Back</button>
                        <button type="submit" class="btn btn-primary">Save & Next</button>
                    </div>
                </form>

                <!--step 5-->
                <form id="step5Form" class="form-step" style="display:none;">
                  <div class="step-title mb-3" style="font-weight: 600;">Copyright & Rights</div>

                  <div class="form-check mb-2">
                      <input class="form-check-input" type="checkbox" name="confirm_ownership">
                      <label class="form-check-label">I confirm that I own or control the rights to the submitted recordings.</label>
                  </div>
                  <div class="form-check mb-2">
                      <input class="form-check-input" type="checkbox" name="no_infringement">
                      <label class="form-check-label">The submitted recordings do not infringe on any third-party copyrights.</label>
                  </div>
                  <div class="form-check mb-2">
                      <input class="form-check-input" type="checkbox" name="samples_cleared">
                      <label class="form-check-label">All samples used in the recordings are properly cleared.</label>
                  </div>
                  <div class="form-check mb-2">
                      <input class="form-check-input" type="checkbox" name="no_disputes">
                      <label class="form-check-label">No legal disputes exist regarding the ownership of these works.</label>
                  </div>
                  <div class="form-check mb-2">
                      <input class="form-check-input" type="checkbox" name="authorized_submission">
                      <label class="form-check-label">I have the authority to submit these recordings for catalog evaluation.</label>
                  </div>

                  <div class="d-flex justify-content-between mt-3">
                      <button type="button" class="btn btn-secondary" id="step5Back">Back</button>
                      <button type="submit" class="btn btn-primary">Save & Next</button>
                  </div>
              </form>
              <!--step 5-->

              <!--step 6-->
              <form id="step6Form" class="form-step" style="display:none;">
                  <div class="step-title mb-3" style="font-weight: 600;">Payment Information</div>

                  <div class="mb-3">
                      <label>Preferred Payout Method *</label>
                      <select class="form-select" id="payoutMethod" name="payout_method">
                          <option value="">Select</option>
                          <option value="bank">Bank Transfer</option>
                          <option value="mobile">Mobile Money</option>
                          <option value="other">Other</option>
                      </select>
                  </div>

                  <div id="bankFields" style="display:none;">
                      <div class="mb-3"><label>Bank Name</label><input type="text" class="form-control" name="bank_name"></div>
                      <div class="mb-3"><label>Account Name</label><input type="text" class="form-control" name="account_name"></div>
                      <div class="mb-3"><label>Account Number</label><input type="number" class="form-control" name="account_number"></div>
                      <div class="mb-3"><label>Country</label>
                          <select class="form-select" name="bank_country">
                              <option value="">Select</option>
                              @foreach($all_countries as $country)
                              <option value="{{$country->name}}">{{$country->name}}</option>
                              @endforeach
                          </select>
                      </div>
                  </div>

                  <div id="mobileFields" style="display:none;">
                      <div class="mb-3"><label>Mobile Money Provider</label><input type="text" class="form-control" name="mobile_provider"></div>
                      <div class="mb-3"><label>Mobile Number</label><input type="tel" class="form-control" name="mobile_number"></div>
                  </div>

                  <div id="otherFields" style="display:none;">
                      <div class="mb-3"><label>Other Payment Details</label><input type="text" class="form-control" name="other_details"></div>
                  </div>

                  <div class="d-flex justify-content-between mt-3">
                      <button type="button" class="btn btn-secondary" id="step6Back">Back</button>
                      <button type="submit" class="btn btn-primary">Save & Next</button>
                  </div>
              </form>
              <!--step 6-->   

              <!--step 7-->
                <form id="step7Form" class="form-step" style="display:none;">
                    <div class="step-title mb-3" style="font-weight: 600;">Review & Submit</div>

                    <div class="alert alert-warning">
                        <p>Songs will be reviewed before selection.</p>
                        <p>Submission does not guarantee payout.</p>
                        <p>Selected artists will receive payment within 3 months.</p>
                    </div>

                    <div class="form-check mb-2">
                        <input type="checkbox" class="form-check-input" name="understand_review">
                        <label class="form-check-label">I understand and accept the review process.</label>
                    </div>
                    <div class="form-check mb-2">
                        <input type="checkbox" class="form-check-input" name="understand_no_guarantee">
                        <label class="form-check-label">I understand that submission does not guarantee selection or payment.</label>
                    </div>
                    <div class="form-check mb-2">
                        <input type="checkbox" class="form-check-input" name="agree_terms">
                        <label class="form-check-label">I agree to the FlareTechMusic Terms & Conditions.</label>
                    </div>

                    <div class="mb-3">
                        <label>Digital Signature - Full Legal Name *</label>
                        <input type="text" class="form-control" name="digital_name">
                    </div>
                    <div class="mb-3">
                        <label>Date</label>
                        <input type="text" class="form-control" name="digital_date" value="{{ date('Y-m-d') }}" readonly>
                    </div>

                    <div class="d-flex justify-content-between mt-3">
                        <button type="button" class="btn btn-secondary" id="step7Back">Back</button>
                        <button type="submit" class="btn btn-success">Submit Catalog for Review</button>
                    </div>
                </form>
              <!--step 7-->

                   </div>
              </div>
            <!--end new container-->
          
          </div>
      </div>
    </div>
  </div>

@endsection

@section('script')


   


@endsection



