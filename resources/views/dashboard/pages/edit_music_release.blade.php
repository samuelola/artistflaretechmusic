@extends('dashboard.index')
@section('title')
  Dashboard
@endsection
@section('content')

@include('sweetalert::alert')

<style>

.nav-link.disabled {
    pointer-events: none;
    opacity: 0.5;
    cursor: not-allowed;
}

#audioUpload {
      display: none;
    }

    /* Styled label as button */
    .upload-label {
      display: inline-block;
      padding: 12px 24px;
      background: linear-gradient(135deg, #10b981, #059669);
      color: white;
      border-radius: 8px;
      cursor: pointer;
      font-size: 16px;
      font-weight: 600;
      transition: all 0.3s ease;
      margin-top: 16px;
      box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }

    /*.upload-label:hover {
      background: linear-gradient(135deg, #059669, #047857);
      transform: translateY(-2px);
    }*/

    .audio-preview {
      width: 380px;
      min-height: 80px;
      border: 2px dashed #cbd5e1;
      border-radius: 12px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 14px;
      color: #64748b;
      background-color: #f9fafb;
      margin-top: 20px;
      padding: 12px;
      box-shadow: inset 0 2px 4px rgba(0,0,0,0.05);
    }

    .audio-preview audio {
      width: 100%;
    }

    .file-size {
      font-size: 13px;
      color: #374151;
      font-weight: 500;
    }

.nav-link {
    display: block;
    padding: 0.5rem 1rem;
    color: #700084;
    text-decoration: none;
    background: 0 0;
    border: 0;
    transition: color .15s ease-in-out, background-color .15s ease-in-out, border-color .15s ease-in-out;
}

.nav-link:hover {
   color: #700084;
}

link.active {
    color: #000 !important;
   
}
    .image-preview {
      width: 200px;
      height: 200px;
      border: 2px dashed #ce11e7;
      border-radius: 10px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 14px;
      color: #999;
      overflow: hidden;
      background-color: #f9f9f9;
    }

    

    #imagePreview {
    background-image: url(../assets/images/user-grid/image_previeww.png) !important;
    background-size: cover !important;
    background-repeat: no-repeat !important;
    background-position: center !important;
}

    .image-preview img {
      width: 100%;
      height: 100%;
      object-fit: cover;
    }

    #imageUpload {
      display: none;
    }

    .upload-label {
      display: inline-block;
      padding: 12px 24px;
      background: linear-gradient(135deg, #700084, #ce11e7);
      color: white;
      border-radius: 8px;
      cursor: pointer;
      font-size: 16px;
      font-weight: 600;
      transition: all 0.3s ease;
      margin-top: 16px;
      box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }
  </style>

  

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
        <h6 class="fw-semibold mb-0">Release</h6>

     </div>

        <div class="row">
                <div class="col-md-12">
                        @if(session('error'))
                            
                            <div class="alert alert-danger bg-danger-100 text-danger-600 border-danger-100 px-24 py-11 mb-0 fw-semibold text-lg radius-8 d-flex align-items-center justify-content-between" role="alert">
                                    <div class="d-flex align-items-center gap-2">
                                        
                                        {!! session('error') !!} 
                                    </div>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif
                </div>
        </div>

   
            <!--new row -->
                <div class="row gy-4">
                   <div class="col-md-12">
                        <div class="card">
                           <div class="card-body">
                               <h6 class="mb-4 text-xl">Fill up your details and proceed next steps.</h6>
                              
                                <!-- Tabs -->
                                @if($subcount->subscription->subscription_name === 'Easy-Buy')
                                 @include('dashboard.pages.edit_release_steps.planA')
                                 @elseif($subcount->subscription->subscription_name === 'Basic') 
                                 @include('dashboard.pages.edit_release_steps.planB')
                                 @elseif($subcount->subscription->subscription_name === 'FlarePro') 
                                 @include('dashboard.pages.edit_release_steps.planC')  
                                 @elseif($subcount->subscription->subscription_name === 'Standard-Label') 
                                 @include('dashboard.pages.edit_release_steps.planD')   
                                @endif  
                           </div>
                        </div>   
                   </div>    
                </div>
            <!--end new row-->
          
          </div>
      </div>
    </div>
  </div>

  <!-- Hidden templates for roles and payouts -->
<select id="role-options-template" style="display:none;">
    <option value="">--Select--</option>
    @foreach($musical_roles as $value)
        <option value="{{ $value->name }}">{{ $value->name }}</option>
    @endforeach
</select>

<select id="payout-options-template" style="display:none;">
    <option value="">--Select--</option>
    @foreach($subscription_limit as $value)
        <option value="{{ $value->the_number }}">{{ $value->the_number }}</option>
    @endforeach
</select>

@endsection

@section('script')

<script>

    var id = $('#release_id').val();
  //  Handle Next Button
    $(".nextBtn").on('click', function(){
        let step = $(this).data('step');
        let form = $("#formStep"+step)[0];
        let formData = new FormData(form);
        formData.append('step', step);
        formData.append('release_id', $('#release_id').val());

         //  FRONTEND VALIDATION
            let hasError = false;
            let errorMessage = '';

            // Validate Participant fields
            $('input[name="participant[]"]').each(function(){
            if ($(this).val().trim() === '') {
                hasError = true;
                errorMessage = 'Please enter all participant name.';
                return false; // break loop
            }
            });

            if (!hasError) {
            // Validate Role fields
            $('select[name^="role"]').each(function(){
                if ($(this).val() === null || $(this).val().length === 0) {
                hasError = true;
                errorMessage = 'Please select at least one role for each participant.';
                return false;
                }
            });
            }

            if (!hasError) {
            // Validate Payout fields
            $('select[name="payout[]"]').each(function(){
                if ($(this).val() === null || $(this).val().trim() === '') {
                hasError = true;
                errorMessage = 'Please select payout percentage for each participant.';
                return false;
                }
            });
            }

            if (hasError) {
            alert(errorMessage);
            return;
            }
        
         $.ajaxSetup({
              headers: {
                  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
              }
          });
        $.ajax({
            url: "{{route('update_music_release') }}",
            method: "POST",
            data: formData,
            processData:false,
            contentType:false,
            success: function(res){
                let nextStep = step + 1;
                $('#registerTabs a[data-step="'+nextStep+'"]').tab('show');
            },
            error: function (error) {
                if (error.responseJSON && error.responseJSON.errors) {
                    let errors = error.responseJSON.errors;
                     // Check if role/payout/participant are missing together
                    if (errors.role || errors.payout || errors.participant) {
                        // Show a general message instead of individual errors
                        alert('Add at least one participant.');
                        return; // stop further processing
                    }

                    
                    $.each(error.responseJSON.errors, function(prefix, val){
                        $('.'+prefix+'_error').text(val[0]);
                        $('[name="'+prefix+'"]').off('input change').on('input change', function(){
                            if ($(this).val().trim() !== '') $('.'+prefix+'_error').text('');
                        });
                    });
                } else if (error.responseJSON && error.responseJSON.message) {
                    alert(error.responseJSON.message);
                }
            }
        });
    });

    $(".prevBtn").on('click', function(){
         let step = $(this).data('step');
         let prevStep = step - 1;
         $('#registerTabs a[data-step="'+prevStep+'"]').tab('show');
    });


    // Handle Submit

$(".submitBtn").on('click', function(){
        let step = $(this).data('step');
        let form = $("#formStep"+step)[0];
        let formData = new FormData(form);
        formData.append('step', step);
        formData.append('release_id', $('#release_id').val());
        //let form = $("#formStep5")[0];

        $.ajax({
            url: "{{ route('update_music_release') }}",
            method: "POST",
            data: formData,
            processData:false,
            contentType:false,
            success: function(res){
                if (res.status === 'success') {
                alert("Completed!");
                localStorage.removeItem("participants");
                let redirectUrl = "{{ route('dashboard') }}"; 
                window.location.href = redirectUrl;
                }
            },
            error: function (error) {
                if (error.responseJSON && error.responseJSON.errors) {
                    $.each(error.responseJSON.errors, function(prefix, val){
                        $('.'+prefix+'_error').text(val[0]);
                        $('[name="'+prefix+'"]').off('input change').on('input change', function(){
                            if ($(this).val().trim() !== '') $('.'+prefix+'_error').text('');
                        });
                    });
                } else if (error.responseJSON && error.responseJSON.message) {
                    alert(error.responseJSON.message);
                }
            }
        });
    });
</script>   

<script>
// Handle file input change to update preview
$('#imageUpload').on('change', function() {
    const file = this.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            $('#imagePreview').html('<img src="' + e.target.result + '" alt="Artwork" style="max-width: 200px; max-height: 200px;">');
        }
        reader.readAsDataURL(file);
    } else {
        // If no file selected, show existing image or default text
        @if(!empty($release_music->artwork_image))
            $('#imagePreview').html('<img src="{{ Storage::url($release_music->artwork_image) }}" alt="Artwork" style="max-width: 200px; max-height: 200px;">');
        @else
            $('#imagePreview').html('<span>Image Preview</span>');
        @endif
    }
});

</script>  

<script>
  // Select all checkbox
  const checkAll = document.getElementById('checkAll');
  const checkboxes = document.querySelectorAll('.row-checkbox');

  // When "Select All" is clicked
  checkAll.addEventListener('change', function() {
    checkboxes.forEach(cb => cb.checked = this.checked);
  });
</script>  

<script>
$(document).ready(function () {
    let rowIndex = 0;

    function addRow(data = {}) {
        let newRow = $('<div class="row mb-3 align-items-end"></div>');
        let roleOptions = $("#role-options-template").html();
        let payoutOptions = $("#payout-options-template").html();

        let col = `
            <div class="col-md-3">
                <label>Participant</label>
                <input type="text" name="participant[]" 
                       class="form-control" 
                       value="${data.participant || ''}" 
                       placeholder="Enter Participant" required>
            </div>
            <div class="col-md-3">
                <label>Roles</label>
                <select name="role[${rowIndex}][]" multiple class="form-control js-example-basic-single" style="width: 100% !important">
                    ${roleOptions}
                </select>
            </div>
            <div class="col-md-3">
                <label>Payout %</label>
                <select name="payout[]" class="form-control js-example-basic-single" style="width: 80% !important">
                    ${payoutOptions}
                </select>
            </div>
            <div class="col-md-3">
                <button type="button" class="btn btn-danger remove-row">Cancel</button>
            </div>
        `;

        newRow.append(col);
        $("#select-container").append(newRow);

        // Initialize Select2
        newRow.find(".js-example-basic-single").select2({
            placeholder: "--Select--",
            allowClear: true
        });

        // Populate selected roles and payout
        if (data.role) newRow.find("select[name^='role']").val(data.role).trigger("change");
        if (data.payout) newRow.find("select[name='payout[]']").val(data.payout).trigger("change");

        rowIndex++;
    }

    // Add new empty row
    $("#add-selects").click(function () {
        addRow();
    });

    // Remove row
    $(document).on("click", ".remove-row", function () {
        $(this).closest(".row").remove();
    });

    // Prefill participants from DB arrays
    @if(isset($participants) && !empty($participants))
        @php
            $participantArray = $participants['participant']; // ["part 1","part 2"]
            $roleArray = $participants['role']; // [["Lead Vocalist","Opera Singer"],["Mixing Engineer","Sound Designer"]]
            $payoutArray = $participants['payout']; // ["70","30"]
        @endphp

        @for($i = 0; $i < count($participantArray); $i++)
            addRow({
                participant: "{{ $participantArray[$i] }}",
                role: {!! json_encode($roleArray[$i]) !!},
                payout: "{{ $payoutArray[$i] }}"
            });
        @endfor
    @endif
});
</script>

<script>
    $(document).ready(function () {
    $("#audioUpload").on("change", function () {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function (e) {
                // Calculate size in MB (2 decimals)
                const sizeMB = (file.size / (1024 * 1024)).toFixed(2);

                $("#audioPreview").html(
                    '<audio controls>' +
                        '<source src="' + e.target.result + '" type="' + file.type + '">' +
                        'Your browser does not support the audio element.' +
                    '</audio>' +
                    '<div class="file-size">Size: ' + sizeMB + ' MB</div>'
                );
            };
            reader.readAsDataURL(file);
        } else {
            // If no file selected, show existing audio or default text
            @if(!empty($release_music->audio_file))
                $("#audioPreview").html(
                    '<audio controls>' +
                        '<source src="{{ Storage::url($release_music->audio_file) }}" type="audio/mpeg">' +
                        'Your browser does not support the audio element.' +
                    '</audio>' +
                    '<div class="file-size">Size: {{ $sizeMB }} MB</div>'
                );
            @else
                $("#audioPreview").html("<span>Audio Preview</span>");
            @endif
        }
    });
});

  </script>

  <script>
        // In your Javascript (external .js resource or <script> tag)
        $(document).ready(function() {
            $('.js-example-basic-single').select2();
        });
</script>
<script>
  $(document).ready(function() {
    $('.js-example-basic-multiple').select2({
      placeholder: "--Select--",
      allowClear: true
    });
  });
</script>
    

@endsection



