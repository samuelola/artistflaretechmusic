                    
                         <ul class="nav nav-tabs custom-tabs" id="releaseTabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="step1-tab" data-bs-toggle="tab" data-bs-target="#step1" type="button" role="tab">
                                Release Details
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="step2-tab" data-bs-toggle="tab" data-bs-target="#step2" type="button" role="tab">
                                Artwork
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="step3-tab" data-bs-toggle="tab" data-bs-target="#step3" type="button" role="tab">
                                Stereo (Audio)
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="step4-tab" data-bs-toggle="tab" data-bs-target="#step4" type="button" role="tab">
                                Track Details
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="step5-tab" data-bs-toggle="tab" data-bs-target="#step5" type="button" role="tab">
                                Outlets
                                </button>
                            </li>
                             <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="step6-tab" data-bs-toggle="tab" data-bs-target="#step6" type="button" role="tab">
                                            Verification Details</button>
                            </li>
                            </ul>

                        <div class="card">
                            <div class="card-body">
                                <div class="tab-content border p-3" id="releaseTabsContent">
                                 
                                 <!-- STEP 1: RELEASE DETAILS -->    
                        
                                <div class="tab-pane fade show active" id="step1">
                                    <div style="margin-top:20px;" class="alert alert-warning bg-transparent text-warning-600 border-warning-600 px-24 py-11 mb-0 fw-semibold text-lg radius-8 d-flex" role="alert">
                                    💡<p style="color:#4B5563;" class="mb-0  text-sm">
                                        To avoid release rejection, please ensure your Release Name (Album, EP, or Single) does not include “Limited Edition”, “Full Version”, “Edited”, “Cover from”, “Remix”, “Instrumental”, “Atmos”, “Spatial Audio” or Track numbers in the title.
                                    </p>
                                        
                                    </div>
                                    <form id="formStep1" style="margin-top:20px">
                                        <input type="hidden" id="music_release_id" name="music_release_id" value="{{$release->id ?? ''}}"/>

                                        <div class="row mt-3">
                                            <div class="col-md-6">                                 
                                                    <label>Select Plan</label>
                                                    <input type="text" name="plan" id="plan" class="form-control" value="{{$subcount->subscription->subscription_name ?? ''}}" readonly>  
                                            </div>
                                                <div class="col-md-6">
                                                        
                                                    <label>Release Type</label>
                                                    <input type="text" name="release_type" id="release_type" class="form-control" value="{{$subcount->subscription->track_file_quality ?? ''}}" readonly>
                                                    
                                                </div>
                                        </div>

                                        <div class="row mt-3">
                                            <div class="col-md-6">
                                                                                        
                                                    <label>Title</label>
                                                   <input class="form-control" name="title" id="title" value="">
                                                  
                                            </div>
                                            <div class="col-md-6">
                                                    
                                                <label>Stereo Type</label>
                                                <input type="text" name="stereo_type" class="form-control" id="stereo_type" value="">
                                                
                                            </div>
                                        </div>

                                        <div class="row mt-3">
                                            <div class="col-md-6">
                                                                                        
                                                    <label>Stereo EAN Code</label>
                                                    <input type="number" 
                                                    class="form-control" 
                                                    name="stereo_code" 
                                                    id="stereo_code" 
                                                    value="" 
                                                    readonly>   
                                            </div>
                                                <div class="col-md-6">
                                                        
                                                    <label>Label Name</label>
                                                    <input type="text" name="label_name" class="form-control" id="label_name" value="">
                                                    
                                                </div>
                                        </div>
                                        
                                        <div class="row mt-3">
                                            <div class="col-md-6">
                                                        <label>Release Date</label>
                                                        <input type="date" name="release_date" id="release_date" class="form-control" value="">
                                            </div>  
                                        </div>
                                        <span id="saveStep1Status" class="mt-3"></span>
                                        <div class="mt-3">
                                            <button type="button" class="btn btn-primary-600" id="saveStep1">Save Details</button>
                                            <button type="button" class="btn btn-secondary" id="goto2">Next: Artwork →</button>
                                        </div>
                                    </form>
                                    </div>

                                    <!-- Step 2: Artwork -->
                                   <div class="tab-pane fade" id="step2">
                                     <form id="formStep2" enctype="multipart/form-data">
                                        <div class="mb-3">
                                            <label>Upload Artwork</label>
                                            <input type="file" class="form-control" id="artwork" name="artwork" accept="image/*">
                                        </div>

                                        <span id="artworkStatus"></span>
                                        <div id="artworkPreview" class="mt-3"></div>

                                        <div class="mt-3 d-flex align-items-center gap-2">
                                            <button type="button" class="btn btn-primary-600" id="uploadArtworkBtn">Save Artwork</button>
                                            <button type="button" class="btn btn-secondary" id="goto3">Next: Stereo →</button>
                                            <button type="button" class="btn btn-outline-secondary ms-auto" id="backToStep1">← Back</button>
                                        </div>
                                      </form>
                                    </div>

                                    <!-- Step 3: Stereo (upload audios) -->
                                    <div class="tab-pane fade" id="step3">
                                         <form id="formStep3" enctype="multipart/form-data">
                                        <div class="mb-3">
                                            <label>Upload Audio Files (multiple)</label>
                                            <div id="audioDropZone"
                                            class="border border-primary border-2 rounded-3 p-4 text-center bg-light position-relative"
                                            style="cursor:pointer;">
                                            <i class="bi bi-music-note-beamed fs-1 text-primary"></i>
                                            <p class="mt-2 mb-0 text-secondary">Drag & drop audio files here, or click to browse</p>
                                            <small class="text-muted d-block">Supported formats: MP3, WAV, AAC, etc.</small>
                                            <input type="file" id="audios" name="audios[]" accept="audio/*" multiple
                                                class="position-absolute top-0 start-0 w-100 h-100 opacity-0">
                                            </div>
                                        </div>

                                        <div id="audioList" class="mt-3"></div>
                                        <span id="audioUploadStatus"></span>

                                        <div class="mt-3 d-flex align-items-center gap-2">
                                            <button type="button" class="btn btn-primary-600" id="uploadAudioBtn">Save Audio(s)</button>
                                            <button type="button" class="btn btn-secondary" id="goto4">Next: Track Details →</button>
                                            <button type="button" class="btn btn-outline-secondary ms-auto" id="backToStep2">← Back</button>
                                        </div>
                                        </form>
                                    </div>

                                    <!-- STEP 4: Track Details -->
                                     <div class="tab-pane fade" id="step4">
                                        <div id="tracksContainer" class="mt-3"></div>
                                        <span id="tracksSaveStatus" class="mt-3"></span>
                                        

                                        <div class="mt-3 d-flex align-items-center gap-2">
                                        <button class="btn btn-primary-600" id="saveTracksBtn">Save Tracks</button>
                                        <button class="btn btn-secondary" id="goto5">Next: Outlets →</button>
                                        <button type="button" class="btn btn-outline-secondary ms-auto" id="backToStep3">← Back</button>
                                        </div>
                                    </div>


                                    <!-- Step 5: Outlets -->
                                    <!--Updated Step 5: Outlets -->
                                    <div class="tab-pane fade" id="step5">
                                        <form id="outletsForm">
                                        <table class="table bordered-table mb-0">
                                            <thead>
                                            <tr>
                                                <th>
                                                <div class="form-check style-check d-flex align-items-center">
                                                    <input class="form-check-input" type="checkbox" id="checkAll">
                                                    <label class="form-check-label" for="checkAll">Outlets</label>
                                                </div>
                                                </th>
                                                <th>Release Date</th>
                                                <th>Release Status</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($stores as $store)
                                                <tr>
                                                <td>
                                                    <div class="form-check style-check d-flex align-items-center">
                                                    <input class="form-check-input row-checkbox" type="checkbox" 
                                                        name="outlets[]" value="{{ $store->id }}"
                                                        id="check{{ $store->id }}">
                                                    <label class="form-check-label" for="check{{ $store->id }}">{{ $store->name ?? '' }}</label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <input type="date" name="outlet_release_date[{{ $store->id }}]"
                                                    id="outlet_release_date_{{ $store->id }}" class="form-control outlet-date">
                                                </td>
                                                <td>
                                                    <span
                                                    class="bg-danger-focus text-danger-main px-24 py-4 rounded-pill fw-medium text-sm">
                                                    @if($release->distributed == 'yes')
                                                        {{ 'Distributed'}}
                                                    @elseif($release->distributed == 'no')
                                                        {{ 'Not Distributed' }}
                                                    @endif    
                                                    </span>
                                                </td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>

                                        <span class="mt-3" id="outletsSaveStatus"></span>

                                       
                                        </form>
                                         <div class="mt-3 d-flex align-items-center gap-2">
                                            <button type="button" class="btn btn-primary-600" id="saveOutletsBtn">Save Outlets</button>
                                            <button class="btn btn-secondary" id="goto6">Next: Verification →</button>
                                           
                                            <button type="button" class="btn btn-outline-secondary ms-auto" id="backToStep4">← Back</button>
                                        </div>
                                    </div> <!--end of tab plain -->

                                    <div class="tab-pane fade" id="step6">
                                        <form id="acct_verification">
                                            <div class="row mt-3">
                                              <div class="col-md-2"></div>
                                                <div class="col-md-8">       
                                                    <div>                          
                                                        <label>Select Official ID Card</label>
                                                        <select class="form-control official_id" name="official_id"  class="form-control js-example-basic-single" style="width: 100% !important">
                                                            <option value="">--Select--</option>
                                                            <option value="NIN">NIN</option>
                                                            <option value="voterscard">Voter's Card</option>
                                                            <option value="Driverslicense">Driver's License</option>
                                                        </select>    
                                                     </div><!--end div-->
                                                     <div class="mt-3" id="the_doc">
                                                         <label>Upload Id</label>
                                                         <input type="file" name="upload_doc" id="upload_doc" class="form-control">

                                                     </div>
                                                     <div class="mt-3">
                                                         <label>Account Number</label>
                                                         <input type="text" name="account_number" id="account_number" class="form-control">

                                                     </div><!--end div-->
                                                      <div class="mt-3" id="the_bank">
                                                        <label>Bank</label>
                                                        <select id="bank" name="bank" class="form-control js-example-basic-single" style="width: 100% !important">
                                                            <option>--Select Bank--</option>
                                                              @foreach($rels as $val)
                                                                <option value="{{$val->code}}">{{$val->name}}</option>
                                                              @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="col-12 mt-3" id="account_parent">
                                                        <label>Account Name</label>
                                                        <input id="account_name" type="text" name="account_name" class="form-control" readonly>
                                                        <i class="fa fa-spinner fa-spin input-loader" id="inputLoader"></i>
                                                    </div>
                                                     <div class="mt-3">
                                                           <label>Social Handles</label>
                                                            <div id="socialHandles">
                                                                <div class="input-group mb-2">
                                                                    <input 
                                                                        type="text" 
                                                                        name="social_media_handles[]" 
                                                                        class="form-control" 
                                                                        placeholder="e.g. Instagram: @username">

                                                                    <div class="input-group-append">
                                                                        <button 
                                                                            type="button" 
                                                                            class="btn btn-success" 
                                                                            onclick="addSocialHandle()">
                                                                            +
                                                                        </button>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                     </div><!--end div-->
                                                     <div class="mt-3">
                                                            <label>Video Link</label>
                                                            <input 
                                                                type="url" 
                                                                name="video_links" 
                                                                class="form-control" 
                                                                placeholder="https://youtube.com/your-video" id="youtube_linkk">
                                                     </div>

                                                      <span class="mt-3" id="verificationSaveStatus"></span>
                                                        <div class="mt-3 d-flex align-items-center gap-2">
                                                            <button type="button" class="btn btn-primary-600" id="saveVerificationBtn">Save Verification</button>
                                                            <button type="button" class="btn btn-success" id="updateReleaseBtn">
                                                            <i class="bi bi-check-circle"></i> Update Release    
                                                            </button>
                                                            <span id="submitStatus"></span>
                                                            <button type="button" class="btn btn-outline-secondary ms-auto" id="backToStep5">← Back</button>
                                                        </div>
                                                </div>
                                               

                                              <div class="col-md-2"></div>
                                            </div>
                                        </form>
                                    </div><!--end of tab plain -->

                                  </div><!-- end of tab content -->
                              </div><!-- end of card body -->
                          </div><!-- end of card -->

                          <div class="modal fade" id="docPreviewModal" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-xl modal-dialog-centered">
                                <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">ID Document Preview</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>

                                    <div class="modal-body p-0">
                                        <div id="docPreviewContent" class="doc-preview-wrapper">
                                            <!-- injected -->
                                        </div>
                                    </div>

                                </div>
                            </div>
                            </div> 