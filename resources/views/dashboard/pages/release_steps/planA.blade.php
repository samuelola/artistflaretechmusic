<ul class="nav nav-tabs" id="registerTabs" style="margin-top:60px;">
                                    <li class="nav-item"><a class="nav-link active" data-step="1" href="#step1" data-bs-toggle="tab">RELEASE DETAILS</a></li>
                                    <li class="nav-item"><a class="nav-link" data-step="2" href="#step2" data-bs-toggle="tab">ARTWORK</a></li>
                                    <li class="nav-item"><a class="nav-link" data-step="3" href="#step3" data-bs-toggle="tab">STEREO TRACK(S)</a></li>
                                    <li class="nav-item"><a class="nav-link" data-step="4" href="#step4" data-bs-toggle="tab">TRACK DETAILS</a></li>    
                                    <li class="nav-item"><a class="nav-link" data-step="5" href="#step5" data-bs-toggle="tab">OUTLET DETAILS</a></li>
                                </ul>
                                <div class="tab-content p-3 border border-top-0">
                                  <input type="hidden" name="release_id" id="release_id" value="">
                                     <div class="tab-pane fade show active" id="step1">
                                        <form id="formStep1">
                                            @csrf
                                            <input type="hidden" name="step" value="1">
                                            
                                             <div style="margin-top:20px;" class="alert alert-warning bg-transparent text-warning-600 border-warning-600 px-24 py-11 mb-0 fw-semibold text-lg radius-8 d-flex" role="alert">
                                                    💡<p style="color:#4B5563;" class="mb-0  text-sm">
                                                      To avoid release rejection, please ensure your Release Name (Album, EP, or Single) does not include “Limited Edition”, “Full Version”, “Edited”, “Cover from”, “Remix”, “Instrumental”, “Atmos”, “Spatial Audio” or Track numbers in the title.
                                                    </p>
                                                        
                                                    </div>
                                            <div class="row" style="margin-top:20px;">
                                                   
                                                <div class="col-md-6">
                                                        
                                                            <label>Select Plan</label>
                                                            <input type="text" name="plan" class="form-control" value="{{$subcount->subscription->subscription_name}}" readonly>
                                                            <span class="text-danger error-text pp_error"></span>
                                                        
                                                </div>
                                                <div class="col-md-6">
                                                        
                                                            <label>Release Type</label>
                                                            <input type="text" name="release_type" class="form-control" value="{{$subcount->subscription->track_file_quality}}" readonly>
                                                            <span style="margin-bottom: 16px;" class="text-danger error-text tt_error"></span>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                            <label>Release Title</label>
                                                            <input type="text" name="release_title" class="form-control">
                                                            <span style="margin-bottom: 16px;" class="text-danger error-text release_title_error"></span>
                                                </div>
                                                <div class="col-md-6">
                                                            <label>Stereo ID Type</label>
                                                            <input type="text" name="stereo_type" class="form-control">
                                                            <span style="margin-bottom: 16px;" class="text-danger error-text stereo_type_error"></span>
                                                </div>
                                                
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                            <label>Stereo EAN Code</label>
                                                            <input type="number" name="stereo_code" class="form-control">
                                                            <span style="margin-bottom: 16px;" class="text-danger error-text stereo_code_error"></span>
                                                </div>
                                                <div class="col-md-6">
                                                            <label>Label Name</label>
                                                            <input type="text" name="label_name" class="form-control">
                                                            <span style="margin-bottom: 16px;" class="text-danger error-text label_name_error"></span>
                                                </div>
                                                
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                            <label>Original Release Date</label>
                                                            <input type="date" name="release_date" class="form-control">
                                                            <span style="margin-bottom: 16px;" class="text-danger error-text  release_date_error"></span>
                                                </div>  
                                            </div>
                                            <button type="button" class="btn btn-primary-600 nextBtn" data-step="1">Next</button>
                                        </form>
                                    </div>

                                    <!-- Step 2 -->
                                    <div class="tab-pane fade" id="step2">
                                        <form id="formStep2" enctype="multipart/form-data">
                                            @csrf
                                            <input type="hidden" name="step" value="2">
                                            
                                            <div class="mb-3">
                                                <label>Upload Artwork</label>
                                                <div style="margin-top:12px;">
                                                    <div class="form-switch switch-primary d-flex align-items-center gap-3">
                                                        <span>
                                                            <div class="image-preview" id="imagePreview">
                                                            <span>Image Preview</span>
                                                            </div>
                                                            <input name="artwork_image" style="margin-top:16px;" type="file" id="imageUpload" accept="image/*">
                                                            <label for="imageUpload" class="upload-label">Choose an Image</label>

                                                            <!-- Hidden input to hold the existing image path -->
                                                            <input type="hidden" name="existing_artwork_image" id="existingArtworkImage" value="">
                                                            
                                                        </span>
                                                        <span style="margin-bottom: 16px;" class="text-danger error-text  artwork_image_error"></span>
                                                        <span style="margin-left: 150px;margin-top: -30px;">
                                                            <p class="card-text text-neutral-600" style="font-size: 18px;font-weight: 500;">Cover Art</p>
                                                            <p class="card-text text-neutral-600" >We recommend square images ranging from 1400x1400 to 4000x4000 pixels</p>
                                                            <p class="card-text text-neutral-600" >We accept JPG, JPEG, PNG, BMP, TIF, TIFF or GIF formats</p> 
                                                            <p class="card-text text-neutral-600" >Minimum image size is 100KB and maximum is 10MB</p> 
                                                            <p class="card-text text-neutral-600" >Stores will reject images that contain URLs, logos or branding,
                                                            blurry or pixelated images without permission, images with prices or references to physical packaging, and pornographic imagery.</p> 
                                                                
                                                        </span>
                                                        
                                                            
                                                    </div>
                                                    
                                                </div>
                                            </div>
                                            <div style="margin-top:20px;">
                                             <button type="button" class="btn btn-secondary prevBtn" data-step="2">Back</button>
                                             <button type="button" class="btn btn-primary-600 nextBtn" data-step="2">Next</button>
                                            </div>
                                        </form>
                                    </div>

                                    <!-- Step 3 -->
                                    <div class="tab-pane fade" id="step3">
                                        <form id="formStep3" enctype="multipart/form-data">
                                            @csrf
                                            <input type="hidden" name="step" value="3">
                                            
                                            <div class="mb-3">
                                                <label>Upload an Audio File</label>

                                                    <div style="margin-top:20px;" class="alert alert-warning bg-transparent text-warning-600 border-warning-600 px-24 py-11 mb-0 fw-semibold text-lg radius-8 d-flex" role="alert">
                                                    💡<p style="color:#4B5563;" class="mb-0  text-sm">To avoid release delay or rejection, please ensure that there is no silence longer than 20 seconds before or after the music, and that the song does not end abruptly</p>
                                                        
                                                    </div>
                                                    
                                                <!-- Hidden file input -->
                                                <input name="audioUpload" type="file" id="audioUpload" accept=".mp3,.ogg,audio/*">

                                                <input type="hidden" name="existing_audio" id="existingAudio" value="">

                                                <div class="form-switch switch-primary d-flex align-items-center gap-3">
                                                    
                                                    <div class="audio-preview" id="audioPreview">
                                                        <span>Audio Preview</span>
                                                    </div>
                                                    <label for="audioUpload" class="upload-label">Choose an Audio</label>

                                                </div>

                                                <span class="text-danger error-text audioUpload_error"></span>
                                            </div>
                                            <button type="button" class="btn btn-secondary prevBtn" data-step="3">Back</button>
                                            <button type="button" class="btn btn-primary-600 nextBtn" data-step="3">Next</button>
                                        </form>
                                    </div>
                                    
                                    <!-- Step 4 -->
                                    <div class="tab-pane fade" id="step4">
                                        <form id="formStep4" enctype="multipart/form-data">
                                            @csrf
                                            <input type="hidden" name="step" value="4">
                                            
                                            <div class="row">
                                                <div class="col-md-6">
                                                  
                                                  
                                                        <label>Track Details</label>
                                                        <input type="text" name="track_details" class="form-control">
                                                        <span class="text-danger error-text track_details_error"></span>
                                                           
                                                </div>
                                                <div class="col-md-6">
                                                   
                                                        <label>Artist</label>
                                                        <input type="text" name="artist" class="form-control" value="{{Auth::user()->first_name}} {{Auth::user()->last_name}}">
                                                        <span class="text-danger error-text artist_error"></span>
                                                   
                                                  
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                  
                                                        <label>Genre(s)</label>
                                                        <select name="genre[]" multiple="multiple" class="form-control js-example-basic-multiple" style="width: 100% !important">
                                                          
                                                          
                                                          @foreach($genres as $value)
                                                              <option value="{{$value->name}}">{{$value->name}}</option>
                                                          @endforeach
                                                        </select>
                                                        <span class="text-danger error-text genre_error"></span>
                                                         
                                                </div>
                                                <div class="col-md-6">
                                                   
                                                        <label>Featured Artist</label>
                                                        <input type="text" name="featured_artist" class="form-control" >
                                                        <span class="text-danger error-text featured_artist_error"></span>
                                                   
                                                  
                                                </div>
                                            </div>

                                            <div class="row">
                                                
                                                <div class="col-md-6">
                                                   
                                                        <label>ISRC</label>
                                                        <input type="text" name="isrc" class="form-control" placeholder="Enter ISRC">
                                                        <span class="text-danger error-text isrc_error"></span>
                                                   
                                                </div>
                                                <div class="col-md-6">
                                                   
                                                        <label>ISWC (optional)</label>
                                                        <input type="text" name="iswc" class="form-control" >
                                                        <span class="text-danger error-text iswc_error"></span>
                                                   
                                                </div>
                                            </div>

                                            <div class="row">
                                                
                                                <div class="col-md-6">
                                                   
                                                        <label>Instrumental</label>
                                                        <select name="instrumental" class="form-control js-example-basic-single" style="width: 100% !important">
                                                                <option value="">--Select--</option>
                                                                <option value="Yes">Yes</option>
                                                                <option value="No">No</option>
                                                                
                                                        </select>
                                                        <span class="text-danger error-text instrumental_error"></span>
                                                   
                                                </div>
                                                <div class="col-md-6">
                                                   
                                                        <label>Language</label>
                                                        <select name="language" class="form-control js-example-basic-single" style="width: 100% !important">
                                                                <option value="">--Select--</option>
                                                                @foreach($languages as $value)
                                                                  <option value="{{$value->name}}">{{$value->name}}</option>
                                                                @endforeach
                                                                
                                                        </select>
                                                        <span class="text-danger error-text language_error"></span>
                                                   
                                                </div>
                                            </div>

                                            <div class="row">
                                                
                                                <div class="col-md-6">
                                                   
                                                        <label>Parental Advisory</label>
                                                        <select name="parent_advice" class="form-control js-example-basic-single" style="width: 100% !important">
                                                                <option value="">--Select--</option>
                                                                <option value="Clean">Clean</option>
                                                                <option value="Explicit">Explicit</option>
                                                                <option value="Not Required">Not Required</option>
                                                        </select>
                                                        <span class="text-danger error-text parent_advice_error"></span>
                                                   
                                                </div>
                                                <div class="col-md-6">
                                                   
                                                        <label>For</label>
                                                        <select name="stream_type[]" multiple="multiple"  class="form-control js-example-basic-multiple" style="width: 100% !important">
                                                                
                                                                <option value="Download">Download</option>
                                                                <option value="Stream">Stream</option>
                                                                 
                                                        </select>
                                                        <span class="text-danger error-text stream_type_error"></span>
                                                   
                                                </div>
                                              <div class="row">
                                                 <div class="col-md-12">
                                                     <div id="select-container"></div>
                                                      <div class="d-flex justify-content-end mb-3">
                                                        <button type="button" id="add-selects" class="btn btn-primary-600">
                                                          Add Participant
                                                        </button>
                                                      </div>
                                                 </div>
                                              </div>
                                            </div>
                                            <button type="button" class="btn btn-secondary prevBtn" data-step="4">Back</button>
                                            <button type="button" class="btn btn-primary-600 nextBtn" data-step="4">Next</button>
                                        </form>
                                    </div>

                                    <!-- Step 5 -->
                                    <div class="tab-pane fade" id="step5">
                                        <form id="formStep5" enctype="multipart/form-data">
                                            @csrf
                                            <input type="hidden" name="step" value="5">
                                            
                                      <table class="table bordered-table mb-0">
                                        <thead>
                                          <tr>
                                            <th scope="col">
                                              <div class="form-check style-check d-flex align-items-center">
                                                <input class="form-check-input" type="checkbox" value="" id="checkAll">
                                                <label class="form-check-label" for="checkAll">
                                                  Outlets
                                                </label>
                                              </div>
                                            </th>
                                            <th scope="col">Release Start</th>
                                            <th scope="col">Status</th>
                                            
                                          </tr>
                                        </thead>
                                        <tbody>

                                         @foreach($stores as $store)
                                           <tr>
                                            <td>
                                              <div class="form-check style-check d-flex align-items-center">
                                                <input class="form-check-input row-checkbox"
                                                type="checkbox" name="stores[]"  value="{{$store->id}}" id="check1">
                                                <label class="form-check-label" for="check1">
                                                  {{$store->name}}
                                                </label>
                                              </div>
                                            </td>
                                            <td>{{$store->release_date ?? ''}}</td>
                                            
                                            <td> <span class="bg-danger-focus text-danger-main px-24 py-4 rounded-pill fw-medium text-sm">Not Distributed</span> </td>
                                            
                                          </tr>
                                         @endforeach 
                                          
                                        </tbody>
                                      </table>                                   
   
                                           <div style="margin-top:20px">
                                            <button type="button" class="btn btn-secondary prevBtn" data-step="5">Back</button>
                                            <button type="button" class="btn btn-success submitBtn" data-step="5">Submit</button>
                                          </div> 
                                        </form>
                                    </div>

                                    <!-- end of Step 5 -->

                                </div>