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
                        <input type="hidden" id="artistId" value="{{ $artist->id ?? '' }}">
                        <div class="form-step active">

                           <form id="step1Form" enctype="multipart/form-data">
                            
                             <div class="step-title mb-3" style="font-weight: 600;">Artist Identity</div>
                              <div class="row">
                                  <div class="col-md-6 mb-3">
                                      <label>Full Legal Name *</label>
                                      <input type="text" class="form-control" readonly name="full_name" value="{{$user->first_name}} {{$user->last_name}}">
                                  </div>

                                  <div class="col-md-6 mb-3">
                                      <label>Artist / Stage Name *</label>
                                      <input type="text" class="form-control" name="stage_name" value="{{ old('stage_name', $artist->stage_name ?? '') }}">
                                  </div>

                                  <div class="col-md-6 mb-3">
                                      <label>Date of Birth *</label>
                                      <input type="date" class="form-control" name="dob" value="{{ old('dob', $artist->dob ?? '') }}">
                                  </div>

                                  <div class="col-md-6 mb-3">
                                        <label>Nationality *</label>
                                        <select class="form-select select2" name="nationality">
                                            <option>Select</option>
                                            @foreach($all_countries as $country)
                                            <option value="{{$country->name}}" {{$country->name == $user_country->name ? 'selected' : ''}}>{{$country->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                     
                                    <div class="col-md-6 mb-3">
                                        <label>Country of Residence *</label>
                                        <select class="form-select select2" name="country">
                                            <option>Select</option>
                                            @foreach($all_countries as $country)
                                            <option value="{{$country->name}}" {{$country->name == $user_country->name ? 'selected' : ''}}>{{$country->name}}</option>
                                            @endforeach
                                           
                                        </select>
                                    </div>


                                  <div class="col-md-6 mb-3">
                                      <label>Phone *</label>
                                      <input type="tel" class="form-control" name="phone" value="{{ old('phone', $artist->phone ?? '') }}">
                                  </div>

                                  <div class="col-md-6 mb-3">
                                      <label>Email *</label>
                                      <input type="email" class="form-control" name="email" readonly value="{{$user->email}}">
                                  </div>
                              </div>

                              <h6 style="font-size: 18px !important;" class="mt-4">Promotion Links (Optional)</h6>
                              <div class="row">
                                  <div class="col-md-6 mb-3">
                                      <label>YouTube Video</label>
                                      <input type="url" class="form-control" name="youtube" value="{{ old('youtube', $artist->youtube ?? '') }}">
                                  </div>
                                  <div class="col-md-6 mb-3">
                                      <label>Instagram</label>
                                      <input type="text" class="form-control" name="instagram" value="{{ old('instagram', $artist->instagram ?? '') }}">
                                  </div>
                                  <div class="col-md-6 mb-3">
                                      <label>Facebook</label>
                                      <input type="text" class="form-control" name="facebook" value="{{ old('facebook', $artist->facebook ?? '') }}">
                                  </div>
                                  <div class="col-md-6 mb-3">
                                      <label>TikTok</label>
                                      <input type="text" class="form-control" name="tiktok" value="{{ old('tiktok', $artist->tiktok ?? '') }}">
                                  </div>
                              </div>

                              <h6 style="font-size: 18px !important;" class="mt-4">Identity Verification</h6>
                              <div class="row">
                                  <div class="col-md-6 mb-3">
                                      <label>ID Type *</label>
                                      <select class="form-select select-2" name="id_type">
                                            <option value="">Select</option>
                                            <option value="Passport" {{ ($artist->id_type ?? '') == 'Passport' ? 'selected' : '' }}>Passport</option>
                                            <option value="National ID" {{ ($artist->id_type ?? '') == 'National ID' ? 'selected' : '' }}>National ID</option>
                                            <option value="Driver's License" {{ ($artist->id_type ?? '') == "Driver's License" ? 'selected' : '' }}>Driver's License</option>
                                      </select>
                                  </div>
                                  
                                  <div class="col-md-6 mb-3">
                                      <label>Upload  ID *</label>
                                      <input type="file" class="form-control" name="government_id">
                                        @if(!empty($artist->government_id_path))
                                            
                                                @php
                                                    $govtPath = $artist->government_id_path ?? 'default.jpg';
                                                    $storageUrl = rtrim(config('app.website_storage_link'), '/');
                                                @endphp
                                                <a href="javascript:void(0)" 
                                                class="view-id-link btn btn-sm btn-primary-600 mt-3" 
                                                data-img="{{ $storageUrl . '/storage/' . ltrim($govtPath, '/') }}">
                                                View Image
                                                </a>
                                            
                                        @endif
                                  </div>
                              </div>

                              <button type="submit" class="btn btn-primary-600 mt-3" id="step1Submit">Save & Continue</button>

                            </form>
                          </div>
<!-- Modal -->
<div class="modal modal-lg fade" id="viewIDModal" tabindex="-1" aria-labelledby="viewIDModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="viewIDModalLabel">Uploaded ID</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body text-center">
        <img id="uploadedIDImage" src="" alt="Government ID" class="img-fluid">
      </div>
    </div>
  </div>
</div>

                        <div class="form-step">

                              <form id="step2Form">

                                  <div class="step-title mb-3">Artist Role & Rights Ownership</div>

                                  <div class="mb-3">
                                      <label>Your Role</label>
                                      <select class="form-select" name="role">
                                          <option value="">Select</option>
                                          <option {{ ($step2->role ?? '') == 'Artist' ? 'selected' : '' }}>Artist</option>
                                          <option {{ ($step2->role ?? '') == 'Producer' ? 'selected' : '' }}>Producer</option>
                                          <option {{ ($step2->role ?? '') == 'Songwriter' ? 'selected' : '' }}>Songwriter</option>
                                          <option {{ ($step2->role ?? '') == 'Label Representative' ? 'selected' : '' }}>Label Representative</option>
                                      </select>
                                  </div>

                                  <div class="mb-3">
                                      <label>Rights Ownership</label>
                                       <select class="form-select" id="ownershipSelect">
                                            <option value="100" {{ ($step2->ownership_type ?? '') == '100' ? 'selected' : '' }}>I own 100% of the master recording</option>
                                            <option value="co" {{ ($step2->ownership_type ?? '') == 'co' ? 'selected' : '' }}>I co-own the master recording</option>
                                            <option value="represent" {{ ($step2->ownership_type ?? '') == 'represent' ? 'selected' : '' }}>I represent the rights holder</option>
                                            <option value="authorized" {{ ($step2->ownership_type ?? '') == 'authorized' ? 'selected' : '' }}>I have written authorization to submit this music</option>
                                        </select>
                                  </div>

                                  <!-- CO-OWNERSHIP SECTION -->

                              <div id="coOwnershipSection" style="display:none;">

                                  <div class="mb-3">
                                    <label>Your Ownership Percentage (%)</label>
                                    <input type="number" class="form-control" min="1" max="100" value="{{ $step2->ownership_percentage ?? '' }}" placeholder="Enter your ownership percentage">
                                  </div>


                                  <h6 style="font-size:18px !important;" class="mt-3">Other Rights Holders</h6>

                                  <div id="rightsHolders"></div>

                                  <button type="button" class="btn btn-outline-primary-600 mt-2" id="addHolder">
                                  Add Rights Holder
                                  </button>

                              </div>

                                  <button type="submit" id="step2Submit" class="btn btn-primary-600 mt-3">Save & Continue</button>

                              </form>

                          </div>

                          <div class="form-step">

                            
                           <form id="step3Form" enctype="multipart/form-data">

                                <div class="step-title mb-3" style="cursor:pointer;">Song Upload</div>

                            <div class="mb-3">
                                <label>Upload Songs (MP3 / WAV)</label>
                                <input type="file" id="songFiles" class="form-control" multiple accept=".mp3,.wav">
                            </div>

                            <div id="songsContainer">
                                <div id="songsContainer">
                                    @foreach($songsOwner as $index => $song)
                                    <div class="song-block mb-4 border p-3 mt-3" data-index="{{ $index }}">
                                        <h6>{{ $song->title }}</h6>

                                        <div class="row mb-2">
                                            <div class="col-md-6">
                                                <label>Song Title *</label>
                                                <input type="text" class="form-control" name="songs[{{ $index }}][title]" value="{{ $song->title }}" required>
                                            </div>
                                            <div class="col-md-6">
                                                <label>Artist Name *</label>
                                                <input type="text" class="form-control" name="songs[{{ $index }}][artist_name]" value="{{ $song->artist_name }}" required>
                                            </div>
                                        </div>

                                        <div class="row mt-3">
                                            <div class="col-md-6">
                                                <label>Release Year *</label>
                                                <input type="number" class="form-control" name="songs[{{ $index }}][release_year]" value="{{ $song->release_year }}" required>
                                            </div>
                                            <div class="col-md-6">
                                                <label>Genre *</label>
                                                <select class="form-select" name="songs[{{ $index }}][genre]" required>
                                                    <option value="">Select</option>
                                                    @foreach($genres as $genre)
                                                        <option value="{{ $genre->name }}" {{ $song->genre == $genre->name ? 'selected' : '' }}>{{ $genre->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="row mt-3">
                                            <div class="col-md-6">
                                                <label>Duration *</label>
                                                <input type="text" class="form-control duration-field" name="songs[{{ $index }}][duration]" value="{{ $song->duration }}" readonly required>
                                            </div>
                                            <div class="col-md-6">
                                                <label>Distribution Status *</label>
                                                <select class="form-select" name="songs[{{ $index }}][distribution_status]" required>
                                                    <option value="">Select</option>
                                                    <option value="released" {{ $song->distribution_status=='released'?'selected':'' }}>Released</option>
                                                    <option value="unreleased" {{ $song->distribution_status=='unreleased'?'selected':'' }}>Unreleased</option>
                                                    <option value="previously_distributed" {{ $song->distribution_status=='previously_distributed'?'selected':'' }}>Previously Distributed</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="row mt-3">
                                            <div class="col-md-6">
                                                <label>Spotify URL</label>
                                                <input type="url" class="form-control" name="songs[{{ $index }}][spotify_link]" value="{{ $song->spotify_link }}" placeholder="https://">
                                            </div>
                                            <div class="col-md-6">
                                                <label>Apple Music URL</label>
                                                <input type="url" class="form-control" name="songs[{{ $index }}][apple_link]" value="{{ $song->apple_link }}" placeholder="https://">
                                            </div>
                                        </div>

                                        <div class="row mt-3">
                                            <div class="col-md-6">
                                                <label>Audiomack URL</label>
                                                <input type="url" class="form-control" name="songs[{{ $index }}][audiomack_link]" value="{{ $song->audiomack_link }}" placeholder="https://">
                                            </div>
                                            <div class="col-md-6">
                                                <label>YouTube URL</label>
                                                <input type="url" class="form-control" name="songs[{{ $index }}][youtube_link]" value="{{ $song->youtube_link }}" placeholder="https://">
                                            </div>
                                        </div>

                                        <button type="button" class="btn btn-danger remove-song mt-3">Remove</button>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                                

                                <button type="submit" id="step3Submit" class="btn btn-primary">Save & Continue</button>

                            </form>


                          </div>


                           <div class="form-step">

                                <form id="step4Form">
                                    <div class="step-title mb-3" style="font-weight: 600;">Songwriter & Publishing</div>

                                    <h6 class="mb-3">Contributors</h6>

                                    <div id="step4SongsContainer">
                                        @foreach($songsOwner as $song)
                                        <div class="song-contributors-block mb-4 border p-3">
                                            <h6>{{ $song->title }}</h6>
                                            <input type="hidden" name="song_id[]" value="{{ $song->id }}">

                                            <table class="table table-bordered align-middle contributorsTable">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th>Name</th>
                                                        <th>Role</th>
                                                        <th>Percentage (%)</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($song->contributors as $contributor)
                                                    <tr>
                                                        <td><input type="text" class="form-control" value="{{ $contributor->name }}"></td>
                                                        <td>
                                                            <select class="form-select contributor-role">
                                                                @foreach($musical_roles as $role)
                                                                    <option value="{{ $role->name }}" 
                                                                        @if(isset($contributor) && $contributor->role == $role->name) selected @endif>
                                                                        {{ $role->name }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </td>
                                                        <td><input type="number" class="form-control percentage" value="{{ $contributor->percentage }}"></td>
                                                        <td><button type="button" class="btn btn-danger removeContributor">X</button></td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>

                                            <button type="button" class="btn btn-outline-primary addContributorBtn">Add Contributor</button>
                                            <!-- <div class="mt-2 text-muted">Total Percentage: <strong class="totalPercent">0%</strong></div> -->
                                        </div>
                                        @endforeach
                                    </div>

                                   

                                    <button type="button" class="btn btn-primary mt-3" id="step4Submit">Save & Continue</button>
                                </form>

                           </div>


                          <div class="form-step">

                              <form id="step5Form">
                                    <div class="step-title mb-3" style="font-weight: 600;">Copyright & Rights</div>

                                    <div class="alert alert-warning mt-4">
                                        <strong>Important:</strong> Providing false information may lead to rejection of your submission or removal from the platform.
                                    </div>

                                    <p class="text-muted mb-4">
                                        Please confirm the following statements before continuing.
                                    </p>

                                    @foreach([
                                        'rights1' => 'I confirm that I own or control the rights to the submitted recordings.',
                                        'rights2' => 'The submitted recordings do not infringe on any third-party copyrights.',
                                        'rights3' => 'All samples used in the recordings are properly cleared.',
                                        'rights4' => 'No legal disputes exist regarding the ownership of these works.',
                                        'rights5' => 'I have the authority to submit these recordings for catalog evaluation.'
                                    ] as $id => $label)
                                        <div class="form-check d-flex align-items-start mb-3">
                                            <input class="form-check-input me-2 mt-1 rights-check" 
                                              type="checkbox" 
                                              id="{{ $id }}" 
                                              name="{{ $id }}"
                                              {{ isset($rights) && $rights->$id ? 'checked' : '' }}
                                              >
                                            <label class="form-check-label" for="{{ $id }}">{{ $label }}</label>
                                        </div>
                                    @endforeach

                                    <button type="submit" class="btn btn-primary mt-3" id="step5Submit">Save & Continue</button>
                                </form>


                          </div>


                        <div class="form-step">

                           <form id="step6Form">

                              <div class="step-title mb-3" style="font-weight: 600;">Payment Information</div>

                              <div class="mb-3">
                                  <label>Preferred Payout Method *</label>
                                  
                                  <select id="payoutMethod" class="form-select" required>
                                        <option value="">Select Method</option>
                                        <option value="bank" {{ optional($payment)->payout_method == 'bank' ? 'selected' : '' }}>Bank</option>
                                        <option value="mobile" {{ optional($payment)->payout_method == 'mobile' ? 'selected' : '' }}>Mobile</option>
                                        <option value="other" {{ optional($payment)->payout_method == 'other' ? 'selected' : '' }}>Other</option>
                                   </select>
                              </div>

                              <!-- BANK FIELDS -->

                              <div id="bankFields" style="display:none;">

                                  <div class="mb-3">
                                      <label>Bank Name *</label>
                                      <select class="form-control" id="bankName">
                                          <option>Select Bank</option>
                                          @foreach($banks as $bank)
                                              <option value="{{$bank->code}}"
                                              {{ optional($payment)->bank_name == $bank->code ? 'selected' : '' }}
                                              >{{$bank->name}}</option>
                                          @endforeach
                                      </select>
                                  </div>

                                  <div class="mb-3">
                                      <label>Account Number *</label>
                                      <input type="number" class="form-control" id="bankAccountNumber"  value="{{ optional($payment)->account_number }}">
                                  </div>

                                  <div class="mb-3">
                                      <label>Account Name *</label>
                                      <input type="text" class="form-control" id="bankAccountName"  value="{{ optional($payment)->account_name }}">
                                  </div>

                                  <div class="mb-3">
                                      <label>Country *</label>
                                      <select class="form-select" id="bankCountry">
                                          <option>Select Country</option>
                                          @foreach($all_countries as $country)
                                          <option value="{{$country->name}}"
                                           {{ optional($payment)->country == $country->name ? 'selected' : '' }}
                                          >{{$country->name}}</option>
                                          @endforeach
                                         
                                      </select>
                                  </div>

                                   

                              </div>

                              <!-- MOBILE MONEY FIELDS -->

                              <div id="mobileFields" style="display:none;">

                                  <div class="mb-3">
                                      <label>Mobile Money Number *</label>
                                      <input type="tel" class="form-control" id="mobileNumber"  value="{{ optional($payment)->mobile_number }}">
                                  </div>

                                  <div class="mb-3">
                                      <label>Account Name *</label>
                                      <input type="text" class="form-control" id="mobileAccountName"  value="{{ optional($payment)->account_name }}">
                                  </div>

                                  <div class="mb-3">
                                      <label>Country *</label>
                                      <select class="form-select" id="mobileCountry">
                                          <option>Select Country</option>
                                          @foreach($all_countries as $country)
                                          <option value="{{$country->name}}"
                                          {{ optional($payment)->country == $country->name ? 'selected' : '' }}
                                          >{{$country->name}}</option>
                                         
                                          @endforeach
                                      </select>
                                  </div>

                              </div>

                              <!-- OTHER METHOD FIELDS -->

                              <div id="otherFields" style="display:none;">

                                  <div class="mb-3">
                                      <label>Description / Account Info *</label>
                                      <input type="text" class="form-control" id="otherAccountInfo" value="{{ optional($payment)->other_info }}">
                                  </div>

                              </div>

                              <button type="submit" class="btn btn-primary mt-3">Save & Continue</button>

                            </form>

                          </div>


                        <div class="form-step">
                            <form id="finalSubmitForm">

                               <!-- Final Notice -->
                              <div class="alert alert-info">
                                  <p><strong>Important Notice:</strong></p>
                                  <ul>
                                      <li>Songs will be reviewed before selection.</li>
                                      <li>Submission does not guarantee payout.</li>
                                      <li>Selected artists will receive payment within 3 months.</li>
                                  </ul>
                              </div>

                                <div class="step-title mb-3">Review & Submit</div>

                               <h6 class="mb-3" style="font-size: 18px !important;">Digital Signature</h6>

                              <div class="mb-3">
                                  <label>Full Legal Name *</label>
                                  <input type="text" class="form-control" id="digitalName"
                                  value="{{ optional($submission)->digital_name }}"
                                   required>
                              </div>

                              <div class="mb-3">
                                  <label>Date</label>
                                  <input type="text" class="form-control" id="digitalDate"
                                  value="{{ optional($submission)->digital_date }}"
                                   readonly>
                              </div>

                              <div class="form-check d-flex align-items-center mt-3">
                                    <input 
                                        class="form-check-input me-2" 
                                        type="checkbox" 
                                        id="agreeTerms"
                                        {{ optional($submission)->agree_terms ? 'checked' : '' }}
                                    >
                                    <label class="form-check-label mb-0" for="agreeTerms">
                                        I agree to the <a href="#" target="_blank">Terms & Conditions</a>
                                    </label>
                                </div>

                                <button type="submit" class="btn btn-success btn-lg mt-3">
                                    Submit Catalog for Review
                                </button>

                            </form>

                        </div>

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

let currentStep = 0;

document.addEventListener("DOMContentLoaded", function () {

const steps = document.querySelectorAll(".form-step");
const indicators = document.querySelectorAll(".step");
const prevBtn = document.getElementById("prevBtn");
const progressBar = document.getElementById("progressBar");

window.updateWizard = function () {

    steps.forEach((step, i) => {
        step.classList.remove("active");
        if(i === currentStep){
            step.classList.add("active");
        }
    });

    indicators.forEach((indicator, i) => {

        indicator.classList.remove("active");

        if(i <= currentStep){
            indicator.classList.add("active");
        }

    });

    // Progress calculation
    let progress = ((currentStep + 1) / steps.length) * 100;
    progressBar.style.width = progress + "%";

    // Hide back button on step 0
    if(prevBtn){
        prevBtn.style.display = currentStep === 0 ? "none" : "inline-block";
    }

};


/* BACK BUTTON */

if(prevBtn){

prevBtn.addEventListener("click", function(){

if(currentStep > 0){

currentStep--;
updateWizard();

}

});

}


/* CLICKABLE STEPS */

indicators.forEach(stepIndicator => {

stepIndicator.addEventListener("click", function(){

let target = parseInt(this.dataset.step);

currentStep = target; // allow jumping anywhere
updateWizard();

});

});


updateWizard();

});

</script>


<!--Step 1 submission-->


<script>

$(document).ready(function(){

    $('#step1Form').on('submit', function(e){

    e.preventDefault();

    let formData = new FormData(this);
    let $btn = $('#step1Submit');

    $('.invalid-feedback').remove();
    $('.is-invalid').removeClass('is-invalid');

    $btn.prop('disabled', true);
        let originalText = $btn.html();
        $btn.html('<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Saving...');

    $.ajax({

        url: "{{route('artist.step1')}}",
        method: "POST",
        data: formData,
        processData:false,
        contentType:false,
        headers:{
        'X-CSRF-TOKEN': "{{ csrf_token() }}"
        },

        success:function(response){

        if(response.success){

            alert('Step 1 saved successfully!');
            $('#artistId').val(response.artist_id);
            currentStep++;
            updateWizard();

        }

        },

        error:function(xhr){
    $btn.prop('disabled', false).html(originalText);
    console.log(xhr.responseJSON); // DEBUG

    $('.invalid-feedback').remove();
    $('.is-invalid').removeClass('is-invalid');

    if(xhr.status === 422){

        let errors = xhr.responseJSON.errors;

        $.each(errors, function(field, msg){

            let input = $('[name="'+field+'"], [name="'+field+'[]"]');

            if(input.length){

                input.addClass('is-invalid');

                // Select2 fix
                if(input.hasClass('select2')){
                    input.next('.select2-container')
                        .find('.select2-selection')
                        .addClass('is-invalid');
                }

                input.closest('.mb-3').append(
                    '<div class="invalid-feedback d-block">'+msg[0]+'</div>'
                );

            }

        });

        let firstError = $('.is-invalid').first();

        if(firstError.length){
            $('html, body').animate({
                scrollTop: firstError.offset().top - 100
            }, 500);
        }

    }

}

      });

    });

});

</script>

<!--End Step 1 submission-->

<!--Step 2 submission-->
<script>
$(document).ready(function(){

    $('#step2Form').on('submit', function(e){
        e.preventDefault();

        let artistId = $('#artistId').val();
        if(!artistId) {
            alert('Please save Step 1 first.');
            return;
        }

        let $btn = $('#step2Submit');
        $btn.prop('disabled', true);
        let originalText = $btn.html();
        $btn.html('<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Saving...');

        // Gather form data
        let coOwners = [];
        $('#rightsHolders .holder-row').each(function(){

        let name = $(this).find('input').eq(0).val();
        let role = $(this).find('select').val();
        let percentage = $(this).find('input').eq(1).val();

        // Skip empty rows
        if(!name && !role && !percentage){
            return;
        }

        coOwners.push({
            name: name,
            role: role,
            percentage: percentage
        });

        });

        let formData = {
            role: $('[name="role"]').val(),
            ownership_type: $('#ownershipSelect').val(),
            ownership_percentage: $('#coOwnershipSection input[type="number"]').val() || null,
            co_owners: coOwners
        };

        // Clear previous errors
        $('.invalid-feedback').remove();
        $('.is-invalid').removeClass('is-invalid');

    
        // VALIDATE OWNERSHIP TOTAL
        let ownershipType = $('#ownershipSelect').val();

        if(ownershipType === 'co'){

            let total = 0;

            // Your percentage
            let yourPercent = parseFloat($('#coOwnershipSection input[type="number"]').val()) || 0;
            total += yourPercent;

            // Co-owners percentage
            $('#rightsHolders .holder-row').each(function(){
               let val = parseFloat($(this).find('input').eq(1).val()) || 0;
                total += val;
            });

            if(total !== 100){
                alert('Total ownership must equal 100%. Current total: ' + total + '%');
                return; // STOP submission
            }

        }

        let hasError = false;

$('#rightsHolders .holder-row').each(function(){

    let name = $(this).find('input').eq(0).val();
    let role = $(this).find('select').val();
    let percentage = $(this).find('input').eq(1).val();

    if(!name || !role || !percentage){
        alert('All co-owner fields are required');
        hasError = true;
        return false; // break loop
    }

});

if(hasError) return;

        $.ajax({
            url: "{{ route('artist.step2') }}",
            method: "POST",
            data: formData,
            headers:{
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            },
            success: function(response){
                if(response.success){
                    alert('Step 2 saved successfully!');
                    currentStep++;
                    updateWizard();
                }
            },
            error: function(xhr){
                $btn.prop('disabled', false).html(originalText);
                if(xhr.status === 422){
                    let errors = xhr.responseJSON.errors;
                    $.each(errors, function(field, msgs){
                        let input;
                        if(field.startsWith('co_owners')) {
                            // Handle co_owners validation dynamically
                            let idx = field.match(/\d+/)[0];
                            let subField = field.split('.').pop();
                            input = $('#rightsHolders .holder-row').eq(idx).find('input, select').filter(function(){
                                return $(this).attr('placeholder').toLowerCase().includes(subField);
                            });
                        } else {
                            input = $('[name="'+field+'"]');
                        }
                        input.addClass('is-invalid');
                        input.after('<div class="invalid-feedback">'+msgs[0]+'</div>');
                    });
                } else {
                    alert('An error occurred. Please try again.');
                }
            }
        });

    });

});
</script>
  
<!--End Step 2 submission-->

<!-- allows you to select multiple -->
<script>
$('.upload-box').on('click', function(){
    $('#songFiles')[0].click(); // use native click
});
</script>
<!-- end -->



<!-- Step 3 submission -->




<script>
$(document).ready(function(){

    const fileInput = $('#songFiles');
    const container = $('#songsContainer');
    const submitBtn = $('#step3Submit');

    let songIndex = 0;
    let uploadedFiles = {}; //  use object (NOT array)

    // ===============================
    // SELECT FILES
    // ===============================
    fileInput.on('change', function(){

        let files = this.files;

        if(!files.length) return;

        for(let i = 0; i < files.length; i++){

            let file = files[i];
            let currentIndex = songIndex;

            // store file properly
            uploadedFiles[currentIndex] = file;

            let html = `
                <div class="song-block mb-4 border p-3 mt-3" data-index="${currentIndex}">

                    <h6>${file.name}</h6>

                    <div class="row mb-2">
                        <div class="col-md-6">
                            <label>Song Title *</label>
                            <input type="text" class="form-control" 
                                name="songs[${currentIndex}][title]" 
                                value="${file.name.replace(/\.[^/.]+$/, '')}" required>
                        </div>
                        <div class="col-md-6">
                            <label>Artist Name *</label>
                            <input type="text" class="form-control" 
                                name="songs[${currentIndex}][artist_name]" required>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-6">
                            <label>Release Year *</label>
                            <input type="number" class="form-control" 
                                name="songs[${currentIndex}][release_year]" 
                                value="${new Date().getFullYear()}" required>
                        </div>
                        <div class="col-md-6">
                            <label>Genre *</label>
                            <select class="form-select" 
                                name="songs[${currentIndex}][genre]" required>
                                <option value="">Select</option>
                                @foreach($genres as $genre)
                                <option value="{{$genre->name}}">{{$genre->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-6">
                            <label>Duration *</label>
                            <input type="text" class="form-control duration-field" 
                                name="songs[${currentIndex}][duration]" 
                                readonly required>
                        </div>
                        <div class="col-md-6">
                            <label>Distribution Status *</label>
                            <select class="form-select" 
                                name="songs[${currentIndex}][distribution_status]" required>
                                <option value="">Select</option>
                                <option value="released">Released</option>
                                <option value="unreleased">Unreleased</option>
                                <option value="previously_distributed">Previously Distributed</option>
                            </select>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-6">
                            <label>Spotify URL</label>
                            <input type="url" class="form-control" 
                                name="songs[${currentIndex}][spotify_link]">
                        </div>
                        <div class="col-md-6">
                            <label>Apple Music URL</label>
                            <input type="url" class="form-control" 
                                name="songs[${currentIndex}][apple_link]">
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-6">
                            <label>Audiomack URL</label>
                            <input type="url" class="form-control" 
                                name="songs[${currentIndex}][audiomack_link]">
                        </div>
                        <div class="col-md-6">
                            <label>YouTube URL</label>
                            <input type="url" class="form-control" 
                                name="songs[${currentIndex}][youtube_link]">
                        </div>
                    </div>

                    <button type="button" class="btn btn-danger remove-song mt-3">
                        Remove
                    </button>

                </div>
            `;

            let block = $(html);
            container.append(block);

            // ===============================
            // AUTO DURATION
            // ===============================
            let audio = document.createElement('audio');
            audio.preload = 'metadata';

            audio.onloadedmetadata = function(){
                let duration = audio.duration;
                let minutes = Math.floor(duration / 60);
                let seconds = Math.floor(duration % 60);
                seconds = seconds < 10 ? '0' + seconds : seconds;

                block.find('.duration-field').val(`${minutes}:${seconds}`);
            };

            audio.src = URL.createObjectURL(file);

            songIndex++;
        }

        this.value = ''; // reset input
    });

    // ===============================
    // REMOVE SONG
    // ===============================
    $(document).on('click', '.remove-song', function(){

        let block = $(this).closest('.song-block');
        let index = block.data('index');

        delete uploadedFiles[index]; // correct

        block.remove();
    });

    // ===============================
    // SUBMIT FORM
    // ===============================
    $('#step3Form').on('submit', function(e){
    e.preventDefault();

    if(Object.keys(uploadedFiles).length === 0){
        alert('Please select at least one song.');
        return;
    }

    let formData = new FormData();
    let i = 0;

    $('.song-block').each(function(){

        let index = $(this).data('index');

        // append inputs
        $(this).find('input, select').each(function(){
            let name = $(this).attr('name');

            // normalize index
            let newName = name.replace(/\[\d+\]/, `[${i}]`);

            formData.append(newName, $(this).val());
        });

        // append correct file
        if(uploadedFiles[index]){
            formData.append(`files[${i}]`, uploadedFiles[index]);
        }

        i++;
    });

    // ===============================
    // SPINNER
    // ===============================
    submitBtn.prop('disabled', true);
    submitBtn.html('<span class="spinner-border spinner-border-sm"></span> Saving...');

    $.ajax({
        url: "{{ route('artist.step3') }}",
        type: "POST",
        data: formData,
        processData: false,
        contentType: false,
        headers:{
            'X-CSRF-TOKEN': "{{ csrf_token() }}"
        },
        xhr: function() {
            let xhr = new window.XMLHttpRequest();
            xhr.upload.addEventListener("progress", function(evt) {
                if (evt.lengthComputable) {
                    let percentComplete = Math.round((evt.loaded / evt.total) * 100);
                    // Update spinner text with percentage
                    submitBtn.html(`<span class="spinner-border spinner-border-sm"></span> Uploading ${percentComplete}%`);
                }
            }, false);
            return xhr;
        },
        success: function(response){
            if(response.success){
                submitBtn.prop('disabled', false).html('Save & Continue');
                alert('All songs uploaded successfully!');
                currentStep++;
                updateWizard();
            }
        },
        error: function(xhr){
            submitBtn.prop('disabled', false).html('Save & Continue');

            if(xhr.status === 422){
                let errors = xhr.responseJSON.errors;

                $('.is-invalid').removeClass('is-invalid');
                $('.invalid-feedback').remove();

                $.each(errors, function(field, msgs){
                    let input = $('[name="'+field+'"]');
                    input.addClass('is-invalid');
                    input.after('<div class="invalid-feedback">'+msgs[0]+'</div>');
                });

            } else {
                alert('An error occurred. Please try again.');
            }
        }
    });

});

});
</script>
<!-- End Step 3 submission -->


<!--step 4 --->
<script>
   $(document).ready(function(){

    // Role options
    let roleOptions = `@foreach($musical_roles as $role)
        <option value="{{ $role->name }}">{{ $role->name }}</option>
    @endforeach`;

    // Add contributor
    $(document).on('click', '.addContributorBtn', function(){
        let tbody = $(this).siblings('table').find('tbody');
        tbody.append(`
            <tr>
                <td><input type="text" class="form-control contributor-name" placeholder="Contributor name"></td>
                <td>
                    <select class="form-select contributor-role">${roleOptions}</select>
                </td>
                <td><input type="number" class="form-control contributor-percentage" placeholder="%"></td>
                <td><button type="button" class="btn btn-danger removeContributor">X</button></td>
            </tr>
        `);
    });

    // Remove contributor
    $(document).on('click', '.removeContributor', function(){
        $(this).closest('tr').remove();
    });

    // Save contributors via AJAX
    $('#step4Submit').on('click', function(e){
        e.preventDefault(); // prevent reload

        let btn = $(this);
        let data = [];
        let hasError = false;
        let errorMsg = "";

        $('.song-contributors-block').each(function(){
            let songId = $(this).find('input[name="song_id[]"]').val();
            let contributors = [];
            let totalPercent = 0;

            $(this).find('tbody tr').each(function(){
                let name = $(this).find('.contributor-name').val();
                let role = $(this).find('.contributor-role').val();
                let percentage = parseFloat($(this).find('.contributor-percentage').val());

                if(name && role && !isNaN(percentage)){
                    contributors.push({name, role, percentage});
                    totalPercent += percentage;
                }
            });

            if(contributors.length === 0){
                hasError = true;
                errorMsg = `Please add at least one contributor for the song: "${$(this).find('h6').text()}"`;
                return false;
            }

            if(totalPercent !== 100){
                hasError = true;
                errorMsg = `Total percentage for song "${$(this).find('h6').text()}" must be 100%. Currently: ${totalPercent}%`;
                return false;
            }

            data.push({artist_owner_song_id: songId, contributors: contributors});
        });

        if(hasError){
            alert(errorMsg);
            return;
        }

        btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span> Saving...');

        $.ajax({
            url: "{{ route('artist.step4') }}",
            type: "POST",
            data: {data: data, _token: "{{ csrf_token() }}"},
            success: function(res){
                if(res.success){
                    btn.prop('disabled', false).html('Save & Continue');
                    alert('Contributors saved successfully!');
                    currentStep++;
                    updateWizard();
                }
                
                
            },
            error: function(xhr){
                alert('An error occurred. Please try again.');
                btn.prop('disabled', false).html('Save & Continue');
            }
        });
    });

});
</script>
<!--end step 4-->

<!-- step 5-->
<script>
$(document).ready(function(){

    $('#step5Form').on('submit', function(e){
        e.preventDefault();

        let btn = $('#step5Submit');
        let allChecked = true;

        $('.rights-check').each(function(){
            if(!$(this).is(':checked')){
                allChecked = false;
            }
        });

        if(!allChecked){
            alert('Please confirm all statements before continuing.');
            return;
        }

        // Show spinner
        btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span> Saving...');

        // Collect data
        let data = {};
        $('.rights-check').each(function(){
            data[$(this).attr('id')] = $(this).is(':checked') ? 1 : 0;
        });

        $.ajax({
            url: "{{ route('artist.step5') }}",
            type: "POST",
            data: {...data, _token: "{{ csrf_token() }}"},
            success: function(res){
                alert('Step 5 saved successfully!');
                btn.prop('disabled', false).html('Save & Continue');
                // move to next step if using wizard
                currentStep++;
                updateWizard();
            },
            error: function(xhr){
                alert('An error occurred. Please try again.');
                btn.prop('disabled', false).html('Save & Continue');
            }
        });
    });

});
</script>

<!-- end step 5-->

<!-- step 6 --> 
<script>
$('#step6Form').on('submit', function(e){
    e.preventDefault();

    let method = $('#payoutMethod').val();

    let data = {
        payout_method: method,
        _token: "{{ csrf_token() }}"
    };

    // ========================
    // CLIENT VALIDATION
    // ========================
    if(!method){
        alert('Please select payout method');
        return;
    }

    if(method === 'bank'){
        data.bank_name = $('#bankName').val();
        data.account_name = $('#bankAccountName').val();
        data.account_number = $('#bankAccountNumber').val();
        data.country = $('#bankCountry').val();

        if(!data.bank_name || !data.account_name || !data.account_number){
            alert('Please fill all bank details');
            return;
        }
    }

    if(method === 'mobile'){
        data.mobile_number = $('#mobileNumber').val();
        data.account_name = $('#mobileAccountName').val();
        data.country = $('#mobileCountry').val();

        if(!data.mobile_number || !data.account_name){
            alert('Please fill all mobile money details');
            return;
        }
    }

    if(method === 'other'){
        data.other_info = $('#otherAccountInfo').val();

        if(!data.other_info){
            alert('Please provide account info');
            return;
        }
    }

    let btn = $(this).find('button');

    btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span> Saving...');

    $.ajax({
        url: "{{ route('artist.step6') }}",
        type: "POST",
        data: data,
        success: function(res){
            alert('Payment info saved!');
            btn.prop('disabled', false).html('Save & Continue');
            currentStep++;
            updateWizard();
        },
        error: function(xhr){
            btn.prop('disabled', false).html('Save & Continue');

            if(xhr.status === 422){
                let errors = xhr.responseJSON.errors;
                alert(Object.values(errors).flat().join('\n'));
            }else{
                alert('Something went wrong');
            }
        }
    });
});
</script>
<!-- end step 6 -->

<!--final submission-->
<script>
$('#finalSubmitForm').on('submit', function(e){
    e.preventDefault();

    let name = $('#digitalName').val();
    let date = $('#digitalDate').val();
    let agreed = $('#agreeTerms').is(':checked');

    if(!name){
        alert('Please enter your full legal name');
        return;
    }

    if(!agreed){
        alert('You must agree to the Terms & Conditions');
        return;
    }

    let btn = $(this).find('button');

    btn.prop('disabled', true)
       .html('<span class="spinner-border spinner-border-sm"></span> Submitting...');

    $.ajax({
        url: "{{ route('artist.final.submit') }}",
        type: "POST",
        data: {
            digital_name: name,
            digital_date: date,
            agree_terms: agreed ? 1 : 0,
            _token: "{{ csrf_token() }}"
        },
        success: function(res){
            alert('Catalog submitted successfully! Awaiting Approval');
            window.location = "{{route('dashboard')}}";
        },
        error: function(xhr){
            btn.prop('disabled', false)
               .html('Submit Catalog for Review');

            if(xhr.status === 422){
                let errors = xhr.responseJSON.errors;
                alert(Object.values(errors).flat().join('\n'));
            }else{
                alert('Something went wrong');
            }
        }
    });
});
</script>
<!--end final submission-->
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

    <select class="form-select">
            <option value="">Select</option>
            <option>Artist</option>
            <option>Producer</option>
            <option>Songwriter</option>
            <option>Label Representative</option>
    </select>
    
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

document.addEventListener("DOMContentLoaded", function(){

    let coOwners = @json($step2->co_owners ?? []);

    if(coOwners.length > 0){

        // Show co-ownership section
        document.getElementById("coOwnershipSection").style.display = "block";

        coOwners.forEach(function(owner){

            const holder = document.createElement("div");
            holder.classList.add("row","mb-3","holder-row");

            holder.innerHTML = `
                <div class="col-md-4">
                    <input type="text" class="form-control" value="${owner.name}">
                </div>

                <div class="col-md-4">
                    <select class="form-select">
                        <option value="">Select</option>
                        <option ${owner.role === 'Artist' ? 'selected' : ''}>Artist</option>
                        <option ${owner.role === 'Producer' ? 'selected' : ''}>Producer</option>
                        <option ${owner.role === 'Songwriter' ? 'selected' : ''}>Songwriter</option>
                        <option ${owner.role === 'Label Representative' ? 'selected' : ''}>Label Representative</option>
                    </select>
                </div>

                <div class="col-md-3">
                    <input type="number" class="form-control" value="${owner.percentage}">
                </div>

                <div class="col-md-1">
                    <button type="button" class="btn btn-danger removeHolder">X</button>
                </div>
            `;

            document.getElementById("rightsHolders").appendChild(holder);

        });

    }

});
</script>





<script>
$(document).ready(function(){

    const payoutMethod = $('#payoutMethod');

    function toggleFields(){
        let val = payoutMethod.val();

        $('#bankFields, #mobileFields, #otherFields').hide();

        if(val === 'bank'){
            $('#bankFields').show();
        }else if(val === 'mobile'){
            $('#mobileFields').show();
        }else if(val === 'other'){
            $('#otherFields').show();
        }
    }

    payoutMethod.on('change', toggleFields);

    toggleFields(); // run on load (important for reload)

});
</script>



<script>
$(document).ready(function() {

    $('.select2').select2({
        placeholder: "Select an option",
        allowClear: true
    });

    // FORCE SELECTED VALUE
    let selectedCountry = "{{ $user_country->name ?? '' }}";

    if(selectedCountry){
        $('select[name="country"]').val(selectedCountry).trigger('change');
    }

    if(selectedCountry){
        $('select[name="nationality"]').val(selectedCountry).trigger('change');
    }

});
</script>


<script>

// HANDLE INPUTS + NORMAL SELECTS
$(document).on('input change', 'input, textarea, select', function(){

    let input = $(this);

    clearFieldError(input);

});


//  HANDLE SELECT2 (VERY IMPORTANT)
$(document).on('change', '.select2', function(){

    let input = $(this);

    clearFieldError(input);

});


// CLEAR FUNCTION (REUSABLE)
function clearFieldError(input){

    // Remove red border
    input.removeClass('is-invalid');

    // Remove error message
    input.closest('.mb-3').find('.invalid-feedback').remove();

    // FIX SELECT2 UI
    if(input.hasClass('select2')){
        input.next('.select2-container')
            .find('.select2-selection')
            .removeClass('is-invalid');
    }

}

</script>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const viewLinks = document.querySelectorAll('.view-id-link');
    const uploadedImg = document.getElementById('uploadedIDImage');

    viewLinks.forEach(link => {
        link.addEventListener('click', function () {
            const imgSrc = this.dataset.img;
            uploadedImg.src = imgSrc;

            // Show Bootstrap modal
            const myModal = new bootstrap.Modal(document.getElementById('viewIDModal'));
            myModal.show();
        });
    });

});
</script>




<script>
$(document).ready(function(){

    function verifyAccount(){

        let accountNumber = $('#bankAccountNumber').val();
        let bankCode = $('#bankName').val();

        if(accountNumber.length === 10 && bankCode){

            $('#bankAccountName').val('Checking...');

            $.ajax({
                url: "{{ route('resolve_account') }}",
                type: "POST",
                data: {
                    account_number: accountNumber,
                    bank_code: bankCode,
                    _token: "{{ csrf_token() }}"
                },
                success: function(res){
                    if(res.success){
                        $('#bankAccountName').val(res.data.data.account_name);
                    }else{
                        $('#bankAccountName').val('');
                        alert(res.message);
                    }
                },
                error: function(){
                    $('#bankAccountName').val('');
                    alert('Verification failed');
                }
            });

        }
    }

    // Trigger when user finishes typing
    $('#bankAccountNumber, #bankName').on('change keyup', function(){
        verifyAccount();
    });

});
</script>

<script>
$(document).ready(function(){
    let today = new Date().toISOString().split('T')[0];
    $('#digitalDate').val(today);
});
</script>


@endsection



