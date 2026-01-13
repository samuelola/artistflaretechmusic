@extends('dashboard.index')
@section('title')
  Dashboard
@endsection
@section('content')

@include('sweetalert::alert')

 <style>

     .doc-preview-wrapper {
    width: 100%;
    height: 80vh;
    display: flex;
    justify-content: center;
    align-items: center;
    overflow: auto;
    background: #f8f9fa;
}

.doc-preview-wrapper img {
    max-width: 100%;
    max-height: 100%;
    transition: transform 0.3s ease;
    transform-origin: center center;
    cursor: zoom-in;
}

.doc-preview-wrapper img.zoomed {
    transform: scale(2);
    cursor: zoom-out;
}


     .note-item {
        position: relative;
      }
      .note-item textarea {
        padding-right: 2rem;
      }
      .remove-note-btn {
        font-size: 1rem;
        line-height: 1;
        padding: 0 6px;
      }
    .progress-bar{
      background-color:#700084;
    }
    .saved-badge { display:inline-block; margin-left:8px; }
    .spinner-small { width: 1rem; height:1rem; border-width: .15rem; }
    .track-card { margin-bottom:1rem; }

    #audioList audio {
    border-radius: 6px;
    background: #fff;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    }

    /* Base styling */
.custom-tabs {
  background-color: #fff;
  border-radius: 0.75rem;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
  overflow: hidden;
  border: none;
}

/* Each tab item */
.custom-tabs .nav-link {
  border: none;
  border-bottom: 3px solid transparent;
  color: #6c757d;
  font-weight: 500;
  padding: 0.75rem 1.25rem;
  background-color: transparent;
  transition: all 0.3s ease;
  position: relative;
}

/* Hover state */
.custom-tabs .nav-link:hover {
  color: #700084;
  background-color: #f8f9fa;
}

/* Active tab */
.custom-tabs .nav-link.active {
  color: #700084;
  border-bottom-color: #700084;
  background-color: #f8f9fa;
  font-weight: 600;
}

/* Optional underline animation */
.custom-tabs .nav-link::after {
  content: "";
  position: absolute;
  left: 50%;
  bottom: 0;
  width: 0%;
  height: 3px;
  background-color: #700084;
  transition: all 0.3s ease;
  transform: translateX(-50%);
}

.custom-tabs .nav-link.active::after {
  width: 50%;
}

/* Responsive tab alignment */
@media (max-width: 768px) {
  .custom-tabs {
    flex-wrap: wrap;
  }

  .custom-tabs .nav-item {
    flex: 1 1 50%;
    text-align: center;
  }
}

#audioDropZone.dragover {
  background-color: #e7f3ff;
  border-color: #700084;
  transform: scale(1.02);
}

#audioDropZone i {
  transition: transform 0.3s ease;
}

#audioDropZone.dragover i {
  transform: rotate(-10deg) scale(1.2);
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
  <!-- <h6 class="fw-semibold mb-0">All Subscriptions</h6> -->

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
                                 <div class="mb-3">
                                    <div class="progress" style="height: 18px;">
                                        <div id="progressBar" class="progress-bar" role="progressbar" style="width:0%"></div>
                                    </div>
                                    <small id="progressLabel" class="text-muted">Step 1 of 6</small>
                                </div>
                                <!-- Tabs as steps -->

                                @if($subcount->subscription->subscription_name === 'Basic')
                                  @include('dashboard.pages.edit_music.musicplanA')
                                @elseif($subcount->subscription->subscription_name === 'Easy-Buy') 
                                  @include('dashboard.pages.edit_music.musicplanB')
                                @elseif($subcount->subscription->subscription_name === 'FlarePro') 
                                  @include('dashboard.pages.edit_music.musicplanC')  
                                @elseif($subcount->subscription->subscription_name === 'Standard-Label') 
                                  @include('dashboard.pages.edit_music.musicplanD')    
                                @endif 
                              
                        </div>
                  </div>
            <!--end new row-->
          
          </div>
      </div>
    </div>
  </div>

@endsection

@section('script')

<script>
$(document).ready(function () {

// -------------------------------
// GLOBAL VALIDATION HELPER
// -------------------------------
function showValidationError($container, message) {
  $container.html(`
    <div class="alert alert-danger mt-2">
      <i class="bi bi-exclamation-triangle-fill"></i> ${message}
    </div>
  `);
  $('html, body').animate({ scrollTop: $container.offset().top - 100 }, 300);
}

  // === TAB NAVIGATION ===
    // Generic function to move between tabs
    function goToTab(tabId) {
        $(`button[data-bs-target="${tabId}"]`).tab('show');
    }

    // Next buttons
    $('#goto2').on('click', function() { goToTab('#step2'); });
    $('#goto3').on('click', function() { goToTab('#step3'); });
    $('#goto4').on('click', function() { goToTab('#step4'); });
    $('#goto5').on('click', function() { goToTab('#step5'); });
    $('#goto6').on('click', function() { goToTab('#step6'); });

    // Back buttons
    $('#backToStep1').on('click', function() { goToTab('#step1'); });
    $('#backToStep2').on('click', function() { goToTab('#step2'); });
    $('#backToStep3').on('click', function() { goToTab('#step3'); });
    $('#backToStep4').on('click', function() { goToTab('#step4'); });
    $('#backToStep5').on('click', function() { goToTab('#step5'); });

    // Optional: Auto-scroll to top on tab change
    $('button[data-bs-toggle="tab"]').on('shown.bs.tab', function () {
        window.scrollTo({ top: 0, behavior: 'smooth' });
    });

    
  const releaseId = $('#music_release_id').val();
  if (releaseId) {
    loadEditRelease(releaseId);
  }

  // When user changes release date, auto-apply +7 days to outlets
  $('#release_date').on('change', function () {
    applyOutletReleaseDates();
  });

  // When user navigates to Step 5 (Outlets)
  $('button[data-bs-target="#step5"]').on('shown.bs.tab', function () {
    applyOutletReleaseDates();
  });
});

/* -------------------------------------------------------------------------- */
/*                         1. Load Release for Editing                        */
/* -------------------------------------------------------------------------- */

function loadEditRelease(releaseId) {
  $.ajax({
    url: `/releases/load-edit/${releaseId}`,
    method: 'GET',
    success(resp) {
      if (resp.status !== 'ok') return;
      const r = resp.release;

      /* ------------------------------ Step 1 ----------------------------- */
      $('#title').val(r.title || '');
      $('#plan').val(r.plan || '');
      $('#release_type').val(r.release_type || '');
      $('#stereo_type').val(r.stereo_type || '');
      // $('#stereo_code').val(r.stereo_code || '');
      $('#stereo_code').val('');
      $('#label_name').val(r.label_name || '');
      $('#release_date').val(r.release_date || '');
      
      $('#saveStep1Status').html('<span class="badge bg-success-subtle text-success border border-success rounded-pill px-3 py-2">Saved</span>');
      /* ------------------------------ Step 2 ----------------------------- */
      if (r.artworks && r.artworks.length > 0) {
        const art = r.artworks[0];
        $('#artworkPreview').html(`
          <img src="${art.url}" class="img-thumbnail" style="max-width:200px;">
        `);
        $('#artworkStatus').html('<span style="padding-top: 5px !important;padding-bottom: 5px !important;" class="badge bg-success-subtle text-success border border-success rounded-pill px-3 py-2">Saved</span>');
      }

      /* ------------------------------ Step 3 ----------------------------- */
      let uploadedFilesMeta = [];
      if (r.tracks && r.tracks.length > 0) {
        uploadedFilesMeta = r.tracks.map(t => ({
          track_id: t.id,
          filename: t.filename || t.title,
          duration_ms: t.duration_ms || 0,
          isrc: t.isrc || '',
          audio_url: t.audio_url || (t.audio_file ? t.audio_file.url : ''),
          artist: t.artist || '',
          feature_artist: t.feature_artist || '',
          iswc: t.iswc || '',
          instrumental: t.instrumental || '',
          language: t.language || '',
          parental: t.parental || '',
          lyrics: t.lyrics || '',
          for: Array.isArray(t.for) ? t.for : (t.for ? JSON.parse(t.for) : []),
          genre: Array.isArray(t.genre) ? t.genre : (t.genre ? JSON.parse(t.genre) : []),
          participants: t.participants || []
        }));

        // Show in audio preview list with delete button
        $('#audioList').html('');
        uploadedFilesMeta.forEach(f => {
          const duration = formatTimeMs(f.duration_ms);
          $('#audioList').append(`
            <div class="mb-3 p-2 border rounded bg-light position-relative audio-item" data-track-id="${f.track_id}">
              <button type="button" class="btn btn-sm btn-danger position-absolute top-0 end-0 m-1 delete-audio-btn" data-track-id="${f.track_id}" title="Delete">
                ❌
              </button>
              <strong>${f.filename}</strong>
              <span class="text-muted">(${duration})</span>
              <audio controls class="mt-2 w-100">
                <source src="${f.audio_url}" type="audio/mpeg">
              </audio>
            </div>
          `);

          $('#audioUploadStatus').html('<span class="badge bg-success-subtle text-success border border-success rounded-pill px-3 py-2">Saved</span>');
           
        });

        // Build track detail cards (Step 4)
        buildTrackForms(uploadedFilesMeta);

        // Restore participants and lyrics
        r.tracks.forEach(t => {
          const trackCard = $(`#tracksContainer .track-card[data-track-id="${t.id}"]`);
          const list = trackCard.find('.participants-list');
          list.empty();

          if (t.participants && t.participants.length > 0) {
            t.participants.forEach((p, idx) => {
              list.append(buildParticipantRowHtml({
                participant: p.participant,
                roles: Array.isArray(p.role) ? p.role : JSON.parse(p.role || '[]'),
                payout: p.payout
              }, idx));
            });
          } else {
            list.append(buildParticipantRowHtml({}, 0));
          }

          // Restore saved lyrics
          if (t.lyrics) {
            const notesContainer = trackCard.find('.notes-container');
            if (notesContainer.length) {
              notesContainer.show().find('textarea.track-lyrics').val(t.lyrics);
              trackCard.find('.add-note-btn').html('<i class="bi bi-dash-circle"></i> Hide Lyrics');
            }
          }
        });

        //$('#tracksSaveStatus').html('<span class="badge bg-success-subtle text-success border border-success rounded-pill px-3 py-2">Saved</span>');
      }

      // STORE authoritative existing tracks immediately on page load
      window.__lastTracks = r.tracks.map(t => ({
          track_id: t.id,
          filename: t.filename || t.title || "",
          title: t.title || "",
          duration_ms: t.duration_ms || 0,
          isrc: t.isrc || "",
          audio_url: t.audio_url || (t.audio_file ? t.audio_file.url : ""),
          artist: t.artist || "",
          feature_artist: t.feature_artist || "",
          iswc: t.iswc || "",
          instrumental: t.instrumental || "",
          language: t.language || "",
          parental: t.parental || "",
          lyrics: t.lyrics || "",
          for: Array.isArray(t.for) ? t.for : (t.for ? JSON.parse(t.for) : []),
          genre: Array.isArray(t.genre) ? t.genre : (t.genre ? JSON.parse(t.genre) : []),
          participants: (t.participants || []).map(p => ({
              participant: p.participant || "",
              roles: Array.isArray(p.role) ? p.role : JSON.parse(p.role || "[]"),
              payout: p.payout || ""
          }))
      }));


      /* ------------------------------ Step 4 ----------------------------- */
      if (r.outlets && r.outlets.length > 0) {
        r.outlets.forEach(o => {
          $(`#check${o.outlet_id}`).prop('checked', true);
          $(`#check${o.outlet_id}`)
            .closest('tr')
            .find('input[name="outlet_release_date"]')
            .val(o.outlet_release_date || '');
        });
        $('#outletsSaveStatus').html('<span class="badge bg-success-subtle text-success border border-success rounded-pill px-3 py-2">Saved</span>');
        
      }

      /* ------------------------------ Verification ----------------------------- */
      if (r.verification && r.verification.exists) {
          console.log(r.verification);
            restoreVerification(r.verification);
      }

      console.log('Edit release loaded:', r);
    },
    error(err) {
      console.log('Error loading edit data:', err.responseText);
    }
  });
}  //end loadeditRelease here



 


    /* -------------------------------------------------------------------------- */
/*                 Verification fill dependant             */
/* -------------------------------------------------------------------------- */

let isRestoringDraft = false;

function restoreVerification(v) {
    // Show bank and account sections (they are hidden by default)
    $('#the_bank').show();
    $('#account_parent').show();

    // Now proceed with restoring values
    isRestoringDraft = true;

    // Official ID
    $('.official_id').val(v.official_id).trigger('change');

    // Account number
    $('#account_number').val(v.account_number || '');

    // Bank (Select2-safe)
    if (v.bank_code) {
        setTimeout(() => {
            $('#bank').val(v.bank_code).trigger('change.select2');
        }, 200);
    }

    // Account name
    setTimeout(() => {
        $('#account_name').val(v.account_name || '');
        isRestoringDraft = false; // allow normal change events
    }, 200);

    // Video link
    $('#youtube_linkk').val(v.video_link || '');

    // Social media handles
    if (Array.isArray(v.social_media_handles) && v.social_media_handles.length > 0) {
        const container = $('#socialHandles');
        container.empty();
        v.social_media_handles.forEach((handle, index) => {
            container.append(`
                <div class="input-group mb-2 mt-3">
                  <input type="text" name="social_media_handles[]" class="form-control" value="${handle}">
                  <div class="input-group-append">
                    <button type="button" class="btn ${index === 0 ? 'btn-success' : 'btn-danger'}"
                      onclick="${index === 0 ? 'addSocialHandle()' : '$(this).closest(\'.input-group\').remove()'}">
                      ${index === 0 ? '+' : '−'}
                    </button>
                  </div>
                </div>
            `);
        });
    }

    // Uploaded document preview
    if (v.id_document_url) {
        $('#the_doc').append(`
            <div class="mt-2">
                <button 
                    type="button"
                    class="btn btn-outline-secondary btn-sm"
                    onclick="previewDocument('${v.id_document_url}')">
                    View uploaded ID document
                </button>
            </div>
            <small class="text-muted">
                Upload only if you want to replace the existing document
            </small>
        `);
    }

    


    // Status badge
    $('#verificationSaveStatus').html('<span class="badge bg-success">Saved</span>');
}


function previewDocument(url) {

    const container = $('#docPreviewContent');
    container.empty();

    const extension = url.split('.').pop().toLowerCase();

    if (['jpg', 'jpeg', 'png'].includes(extension)) {
        container.html(`
            <img 
                src="${url}" 
                id="previewImage"
                alt="ID Document">
        `);

        // Toggle zoom on click
        container.off('click').on('click', '#previewImage', function () {
            $(this).toggleClass('zoomed');
        });

    } else if (extension === 'pdf') {
        container.html(`
            <iframe 
                src="${url}" 
                style="width:100%; height:100%; border:none;">
            </iframe>
        `);
    } else {
        container.html(`<p class="text-danger">Unsupported file type</p>`);
    }

    $('#docPreviewModal').modal('show');
}




/* -------------------------------------------------------------------------- */
/*                        2. Build Track Detail Cards                         */
/* -------------------------------------------------------------------------- */

function escapeHtml(text) {
  if (typeof text !== 'string') return '';
  return text
    .replace(/&/g, "&amp;")
    .replace(/</g, "&lt;")
    .replace(/>/g, "&gt;")
    .replace(/"/g, "&quot;")
    .replace(/'/g, "&#039;");
}


/* -------------------------------------------------------------------------- */
/*                       buildTrackForms()                          */
/* -------------------------------------------------------------------------- */

function buildTrackForms(files, append = false) {
  const container = $('#tracksContainer');

  // --- Maintain old tracks if appending ---
  const existingTracks = {};
  container.find('.track-card').each(function() {
    const id = $(this).data('track-id');
    if (id) existingTracks[id] = true;
  });

  // --- Clear container only if not appending ---
  if (!append) container.empty();

  // --- Determine numbering ---
  const existingCount = container.find('.track-card').length;

  files.forEach((f, index) => {
    const trackId = f.track_id || '';
    if (append && existingTracks[trackId]) {
      //Skip duplicate tracks (already rendered)
      return;
    }

    const trackNumber = existingCount + index + 1;
    const duration = formatTimeMs(f.duration_ms || 0);
    const title = escapeHtml(f.filename ? f.filename.replace(/\.[^/.]+$/, "") : '');
    

    const card = $(` 
      <div class="card track-card mb-3" data-track-id="${trackId}">
        <div class="card-body">
          <div class="d-flex justify-content-between align-items-start">
            <h5 style="font-size: 20px !important;">
              Track ${trackNumber}: <span class="track-file-name">${f.filename || 'Untitled'}</span>
            </h5>
          </div>

          <input type="hidden" class="track-id" value="${trackId}">

          <div class="row mb-3 mt-3">
            <div class="col-md-4">
              <label>Track Title</label>
              <input class="form-control track-title" value="${title}">
            </div>
            <div class="col-md-4">
              <label>Artist</label>
              <input class="form-control track-artist" value="${f.artist || ''}">
            </div>
            <div class="col-md-4">
              <label>Feature Artist</label>
              <input class="form-control track-feature_artist" value="${f.feature_artist || ''}">
            </div>
          </div>

          <div class="row mb-3">
            <div class="col-md-4">
              <label>ISWC (optional)</label>
              <input class="form-control track-iswc" value="${f.iswc || ''}">
            </div>
            <div class="col-md-4">
              <label>Instrumental</label>
              <select class="form-control track-instrumental js-example-basic-single">
                <option value="">--Select--</option>
                <option value="Yes" ${f.instrumental === 'Yes' ? 'selected' : ''}>Yes</option>
                <option value="No" ${f.instrumental === 'No' ? 'selected' : ''}>No</option>
              </select>
            </div>
            <div class="col-md-4">
              <label>Language</label>
              <select class="form-control track-language js-example-basic-single">
                <option value="">--Select--</option>
                @foreach($languages as $value)
                  <option value="{{ $value->name }}">{{ $value->name }}</option>
                @endforeach
              </select>
            </div>
          </div>

          <div class="row mb-3">
            <div class="col-md-4">
              <label>Parental Advisory</label>
              <select class="form-control track-parental js-example-basic-single">
                <option value="">--Select--</option>
                <option value="Clean" ${f.parental === 'Clean' ? 'selected' : ''}>Clean</option>
                <option value="Explicit" ${f.parental === 'Explicit' ? 'selected' : ''}>Explicit</option>
                <option value="Not Required" ${f.parental === 'Not Required' ? 'selected' : ''}>Not Required</option>
              </select>
            </div>
            <div class="col-md-4">
              <label>For</label>
              <select multiple="multiple" class="form-control track-for js-example-basic-multiple">
                <option value="Download" ${f.for === 'Download' ? 'selected' : ''}>Download</option>
                <option value="Stream" ${f.for === 'Stream' ? 'selected' : ''}>Stream</option>
              </select>
            </div>
            <div class="col-md-4">
              <label>Genre(s)</label>
              <select multiple="multiple" class="form-control track-genre js-example-basic-multiple">
                @foreach($genres as $value)
                  <option value="{{ $value->name }}">{{ $value->name }}</option>
                @endforeach
              </select>
            </div>
          </div>

          <div class="row mb-3">
            <div class="col-md-4">
              <label>Duration</label>
              <input class="form-control track-duration" type="text" value="${duration}" readonly>
            </div>
            <div class="col-md-4">
              <label>ISRC Code</label>
              <input class="form-control track-isrc" type="text" value="" readonly>
            </div>
            <div class="col-md-4">
              <button type="button" class="btn btn-sm btn-outline-primary add-note-btn">
                <i class="bi bi-plus-circle"></i> Add Lyrics
              </button>
              <div class="notes-container mt-2" style="display: ${f.lyrics ? 'block' : 'none'};">
                <textarea class="form-control track-lyrics" rows="3" placeholder="Enter lyrics...">${f.lyrics || ''}</textarea>
              </div>
            </div>
          </div>

          <div class="mb-3">
            <label>Preview Audio</label>
            <audio controls class="w-100 mt-1">
              <source src="${f.audio_url || ''}" type="audio/mpeg">
              Your browser does not support audio playback.
            </audio>
          </div>

          <div class="participants-section">
            <h6>Participants</h6>
            <div class="participants-list"></div>
            <button type="button" class="btn btn-sm btn-outline-primary add-participant">Add Participant</button>
          </div>
        </div>
      </div>
    `);

    // === Initialize Select2 ===
    card.find('.js-example-basic-single, .js-example-basic-multiple').select2({ width: '100%' });

    // === Restore multi-select values ===
    if (Array.isArray(f.genre)) card.find('.track-genre').val(f.genre).trigger('change');
    if (Array.isArray(f.for)) card.find('.track-for').val(f.for).trigger('change');
    if (f.language) card.find('.track-language').val(f.language).trigger('change');

    // === Restore participants ===
    const participantsList = card.find('.participants-list');
    if (f.participants && Array.isArray(f.participants) && f.participants.length > 0) {
      f.participants.forEach(p => participantsList.append(buildParticipantRowHtml(p)));
    } else {
      participantsList.append(buildParticipantRowHtml());
    }

    // === Append to container ===
    container.append(card);
  });
}




$(document).on('click', '.add-note-btn', function () {
  const $btn = $(this);
  const $parent = $btn.closest('.col-md-4');
  const $notes = $parent.find('.notes-container');

  if (!$notes.length) return; // safety
  if ($notes.is(':visible')) {
    $notes.slideUp(200);
    $btn.html('<i class="bi bi-plus-circle"></i> Add Lyrics');
  } else {
    $notes.slideDown(250);
    $btn.html('<i class="bi bi-dash-circle"></i> Hide Lyrics');
  }
});


/* -------------------------------------------------------------------------- */
/*                        3. Build Participant Row                            */
/* -------------------------------------------------------------------------- */

function buildParticipantRowHtml(data = {}, rowIndex = 0) {
  // Use Blade variables for options
  const rolesOptions = `@foreach($musical_roles as $value)
        <option value="{{$value->name}}">{{$value->name}}</option>
      @endforeach`;

  const payoutOptions = `@foreach($subscription_limit as $value)
        <option value="{{$value->the_number}}">{{$value->the_number}}</option>
      @endforeach`;

  const row = $(`
    <div class="row g-2 participant-row mb-3 p-2 border rounded mt-3">
      <div class="col-md-3">
        <label>Participant</label>
        <input type="text" name="participant[]" 
               class="form-control p-participant" 
               value="${data.participant || ''}" 
               placeholder="Enter Participant" required>
        <span class="text-danger error-text participant_error"></span>
      </div>

      <div class="col-md-3">
        <label>Roles</label>
        <select name="role[${rowIndex}][]" multiple="multiple" 
                class="form-control js-example-basic-single p-role" 
                style="width:100%">
          ${rolesOptions}
        </select>
        <span class="text-danger error-text role_error"></span>
      </div>

      <div class="col-md-3">
        <label>Payout %</label>
        <select name="payout[]" class="form-control js-example-basic-single p-payout"
                style="width: 100% !important">
          ${payoutOptions}
        </select>
        <span class="text-danger error-text payout_error"></span>
      </div>

      <div class="col-md-3 d-flex align-items-center">
        <button type="button" class="btn btn-danger remove-row">Cancel</button>
      </div>
    </div>
  `);

  // Initialize Select2 for roles (multi-select)
  row.find('.p-role').select2({
    placeholder: 'Select roles',
    width: '100%'
  });

  // Initialize Select2 for payout (single-select)
  row.find('.p-payout').select2({
    placeholder: 'Select payout',
    width: '100%',
    minimumResultsForSearch: Infinity
  });

  // Set selected roles if provided
  if (data.roles && Array.isArray(data.roles)) {
    row.find('.p-role').val(data.roles).trigger('change');
  }

  // Set selected payout if provided
  if (data.payout) {
    row.find('.p-payout').val(data.payout).trigger('change');
  }

  return row;
}


 // participant add/remove handlers (delegated)
 $('#tracksContainer').on('click', '.add-participant', function () {
  const list = $(this).closest('.participants-section').find('.participants-list');
  const rowIndex = list.children().length; // for unique role name
  list.append(buildParticipantRowHtml({}, rowIndex));
});

 $('#tracksContainer').on('click', '.remove-row', function () {
  $(this).closest('.participant-row').remove();
});



/* -------------------------------------------------------------------------- */
/*                         4. Helper: Format Duration                         */
/* -------------------------------------------------------------------------- */

function formatTimeMs(ms) {
  const minutes = Math.floor(ms / 60000);
  const seconds = Math.floor((ms % 60000) / 1000);
  return `${minutes}:${seconds < 10 ? '0' : ''}${seconds}`;
}

/* -------------------------------------------------------------------------- */
/*                 5. Helper: Auto-apply +7 days to outlets                   */
/* -------------------------------------------------------------------------- */

function applyOutletReleaseDates() {
  const mainDate = $('#release_date').val();
  if (!mainDate) return;

  const base = new Date(mainDate);
  if (isNaN(base)) return;

  base.setDate(base.getDate() + 7);
  const plus7 = base.toISOString().split('T')[0];

  $('#outletsForm .outlet-date').each(function () {
    $(this).val(plus7);
    $(this).attr('min', plus7);
  });
}


/* -------------------------------------------------------------------------- */
/*                  GLOBAL CSRF FIX FOR LARAVEL MULTISTEP FORM                 */
/* -------------------------------------------------------------------------- */
$.ajaxSetup({
  headers: {
    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
  }
});









/* -------------------------------------------------------------------------- */
/*                 Update  Individual Steps for Multi-Step Form               */
/* -------------------------------------------------------------------------- */

// === Step 1: Basic Info ===
$('#saveStep1').on('click', function () {
  const id = $('#music_release_id').val();
  const $status = $('#saveStep1Status'); // container for messages

  // Clear previous status and show spinner
  $status.html(`
    <div class="d-flex align-items-center text-primary">
      <div class="spinner-border spinner-border-sm me-2" role="status"></div>
      <span>Saving basic info, please wait...</span>
    </div>
  `);

  $.ajax({
    url: `/update_basic/${id}`,
    method: 'PUT',
    data: $('#formStep1').serialize(),
    success: function (res) {
      // Success badge
      $status.html(`
        <span class="badge bg-success-subtle text-success border border-success rounded-pill px-3 py-2">
           Basic info updated successfully!
        </span>
      `);

      // Optional: auto fade out success after 3 seconds
      setTimeout(() => $status.fadeOut('slow', () => $status.empty().show()), 3000);
    },
    error: function (xhr) {
      if (xhr.status === 422 && xhr.responseJSON?.errors) {
        const errors = xhr.responseJSON.errors;
        let msg = '<ul class="mb-0 ps-3">';
        for (const field in errors) {
          msg += `<li>${errors[field].join(', ')}</li>`;
        }
        msg += '</ul>';
        $status.html(`
          <div class="alert alert-warning mt-2">
            <strong>Validation failed:</strong>${msg}
          </div>
        `);
      } else {
        $status.html(`
          <div class="alert alert-danger mt-2">
             Error saving basic info. Please try again.
          </div>
        `);
      }
    }
  });
});


// === Step 2: Artwork ===

// Preview artwork immediately when selected
$('#artwork').on('change', function (e) {
  const file = e.target.files[0];
  if (!file) return;

  

  // Validate image type (optional)
  if (!file.type.startsWith('image/')) {
    alert('Please upload a valid image file.');
    $(this).val('');
    return;
  }

  const reader = new FileReader();
  reader.onload = function (e) {
    $('#artworkPreview').html(`
      <div class="text-center">
        <img src="${e.target.result}" 
             alt="Artwork Preview" 
             class="img-fluid rounded shadow-sm" 
             style="max-width: 250px; border: 2px solid #ddd;"/>
      </div>
    `);
  };
  reader.readAsDataURL(file);
});

// Handle save artwork
$('#uploadArtworkBtn').on('click', function () {
  const $status = $('#artworkStatus');
  const id = $('#music_release_id').val();
  const formData = new FormData($('#formStep2')[0]);
  formData.append('_token', $('meta[name="csrf-token"]').attr('content'));

  // Clear status and add spinner
  $status.html(`
    <div class="d-flex align-items-center text-primary">
      <div class="spinner-border spinner-border-sm me-2" role="status"></div>
      <span>Uploading artwork, please wait...</span>
    </div>
  `);

  $.ajax({
    url: `/update_artwork/${id}`,
    method: 'POST',
    data: formData,
    contentType: false,
    processData: false,
    success: (res) => {

      $status.html(`
        <span style="padding-top: 5px !important;
    padding-bottom: 5px !important;" class="badge bg-success-subtle text-success border border-success rounded-pill px-3 py-2">
          Artwork updated successfully!
        </span>
      `);

      // Fade out success after 3 seconds
      setTimeout(() => $status.fadeOut('slow', () => $status.empty().show()), 3000);

      // Optionally refresh the preview with the stored file (if backend returns path)
      if (res.artwork_url) {
        $('#artworkPreview').html(`
          <div class="text-center mt-2">
            <img src="${res.artwork_url}?t=${Date.now()}" 
                 alt="Updated Artwork" 
                 class="img-fluid rounded shadow-sm" 
                 style="max-width: 200px; border: 2px solid #28a745;"/>
          </div>
        `);
      }
    },
    error: (xhr) => {
      if (xhr.status === 422 && xhr.responseJSON?.errors) {
        const errors = xhr.responseJSON.errors;
        let msg = '<ul class="mb-0">';
        for (const field in errors) {
          msg += `<li>${errors[field].join(', ')}</li>`;
        }
        msg += '</ul>';
        $('#artworkStatus').html(`
          <div class="alert alert-warning mt-2">${msg}</div>
        `);
      } else {
        $('#artworkStatus').html(`
          <div class="alert alert-danger mt-2">
            Error uploading artwork. Please try again.
          </div>
        `);
      }
    }
  });
});



// === Step 3: Audio ===


let deletedAudioIds = [];

// Instant local preview when files are selected
$('#audios').on('change', function (e) {
  const files = e.target.files;
  const $audioList = $('#audioList');

  if (files.length === 0) return;

  // Append local previews (do not clear existing)
  Array.from(files).forEach((file) => {
    if (!file.type.startsWith('audio/')) return;

    const audioUrl = URL.createObjectURL(file);
    const audio = new Audio(audioUrl);

    audio.addEventListener('loadedmetadata', function () {
      const durationMs = Math.floor(audio.duration * 1000);
      const durationFormatted = formatTimeMs(durationMs);

      // Use data-filename (not data-track-id) for local preview. We'll match by filename after server returns new track IDs.
      const item = $(`
        <div class="mb-3 p-2 border rounded bg-light audio-item position-relative border-dashed" data-filename="${escapeHtml(file.name)}">
          <button type="button" class="btn btn-sm btn-danger position-absolute top-0 end-0 m-1 delete-audio-btn" title="Delete">
            ❌
          </button>
          <strong>${escapeHtml(file.name)}</strong>
          <span class="text-muted">(${durationFormatted})</span>
          <audio controls class="mt-2 w-100">
            <source src="${audioUrl}" type="${file.type}">
          </audio>
        </div>
      `);

      // store filename with jQuery data as well
      item.data('filename', file.name);
      $audioList.append(item);
    });
  });
});


/* -------------------------------------------------------------------------- */
/*        Helper: Map Server Tracks → Local Previews After Upload             */
/* -------------------------------------------------------------------------- */
function updateAudioListWithServerTracks(tracks = []) {
  if (!Array.isArray(tracks) || tracks.length === 0) return;

  tracks.forEach(t => {
    const filename = t.filename || t.title || '';
    if (!filename) return;

    const $preview = $(`.audio-item`).filter(function () {
      const df = $(this).data('filename') || $(this).attr('data-filename') || '';
      return df === filename;
    }).first();

    if ($preview.length) {
      // Assign correct IDs after upload
      $preview.attr('data-track-id', t.track_id || t.id || '');
      $preview.attr('data-audio-id', t.audio_id || t.audio_file_id || '');
      $preview.data('track-id', t.track_id || t.id || '');
      $preview.data('audio-id', t.audio_id || t.audio_file_id || '');

      const $delBtn = $preview.find('.delete-audio-btn').first();
      $delBtn.attr('data-track-id', t.track_id || t.id || '');
      $delBtn.data('track-id', t.track_id || t.id || '');

      // Update audio URL from server
      if (t.audio_url) {
        const $source = $preview.find('audio source').first();
        if ($source.length) {
          $source.attr('src', t.audio_url);
          const audioEl = $preview.find('audio').get(0);
          if (audioEl) audioEl.load();
        }
      }
    } else {
      // Append server-only track if not found in preview
      const $audioList = $('#audioList');
      const duration = formatTimeMs(t.duration_ms || 0);
      const $item = $(`
        <div class="mb-3 p-2 border rounded bg-light position-relative audio-item" 
             data-track-id="${t.track_id || t.id || ''}" 
             data-audio-id="${t.audio_id || t.audio_file_id || ''}">
          <button type="button" 
                  class="btn btn-sm btn-danger position-absolute top-0 end-0 m-1 delete-audio-btn" 
                  data-track-id="${t.track_id || t.id || ''}" title="Delete">
            ❌
          </button>
          <strong>${escapeHtml(t.filename || t.title || 'Untitled')}</strong>
          <span class="text-muted">(${duration})</span>
          <audio controls class="mt-2 w-100">
            <source src="${t.audio_url || ''}" type="audio/mpeg">
          </audio>
        </div>
      `);
      $audioList.append($item);
    }
  });

  // Rebind delete handler (in case new items were added)
  attachDeleteAudioHandler();
}




// =======================
// === Upload Handler ===
// =======================

const $status = $('#audioUploadStatus');

$('#uploadAudioBtn').on('click', function (e) {
    e.preventDefault();

    const releaseId = $('#music_release_id').val();
    const files = $('#audios')[0].files;
    const $status = $('#audioUploadStatus');

    if (!releaseId) return alert('Missing release ID.');

    const formData = new FormData();
    formData.append('_token', $('meta[name="csrf-token"]').attr('content'));

    // Detect existing tracks = metadata update
    const isUpdate = $('#tracksContainer .track-card').length > 0;
    formData.append('is_update', isUpdate ? '1' : '0');

    // Deleted files
    if (deletedAudioIds.length > 0) {
        deletedAudioIds.forEach(id => {
           formData.append('deleted_audio_ids[]', id);
        });
    }

    
        // ------------------------------
    // BLOCK ANY ATTEMPT WITHOUT FILES
    // ------------------------------
    if (files.length === 0) {
        alert("Please select at least one audio file before uploading.");
        return;
    }



    const durations = {};
    let loaded = 0;

    [...files].forEach(file => {
        formData.append("audios[]", file);

        const audio = new Audio(URL.createObjectURL(file));
        audio.addEventListener("loadedmetadata", () => {
            durations[file.name] = Math.floor(audio.duration * 1000);
            loaded++;

            if (loaded === files.length) {
                formData.append("durations", JSON.stringify(durations));
                submitAjax(formData, true);
            }
        });

        // in case metadata fails to load (rare), add a timeout fallback
        setTimeout(() => {
            if (!durations[file.name]) {
                durations[file.name] = 0;
                loaded++;
                if (loaded === files.length) {
                    formData.append("durations", JSON.stringify(durations));
                    submitAjax(formData, true);
                }
            }
        }, 3000);
    });

    // ────────────────────────────────────────────────
    // AJAX SUBMITTER
    // ────────────────────────────────────────────────
    function submitAjax(formData, hasFiles) {
        $('#uploadAudioBtn').prop('disabled', true).text('Processing...');

        $status.html(loader(hasFiles ? "Uploading audio..." : "Updating track metadata..."));

        $.ajax({
            url: `/update_audios/${releaseId}`,
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,

            xhr: function () {
                const xhr = new window.XMLHttpRequest();

                if (hasFiles) {
                    xhr.upload.addEventListener("progress", e => {
                        if (e.lengthComputable) {
                            const percent = Math.round((e.loaded / e.total) * 100);
                            $status.html(`<div class="text-primary small">Uploading... ${percent}%</div>`);
                        }
                    });
                }
                return xhr;
            },

            success(resp) {
                deletedAudioIds = [];
                $('#uploadAudioBtn').prop('disabled', false).text('Upload Audio');

                if (!resp || !resp.status) return showError("Unexpected server response.");

                // If backend gave a cacheKey -> poll until job finishes
                if (resp.cacheKey) {
                    startPolling(resp.cacheKey);
                    return;
                }

                // Immediate response: look for tracks in resp
                const tracks = resp.tracks || resp.finalTracks || resp.files || [];
                finalizeTrackUpdate(tracks, resp.message || 'Audio uploaded');
            },

            error(xhr) {
                $('#uploadAudioBtn').prop('disabled', false).text('Upload Audio');

                let msg = 'Upload failed.';
                if (xhr.status === 422 && xhr.responseJSON?.errors) {
                    msg = Object.values(xhr.responseJSON.errors).flat().join('<br>');
                }

                showError(msg);
            }
        });
    }

    // ────────────────────────────────────────────────
    // POLLING QUEUE STATUS (safe, tolerant, timeout)
    // ────────────────────────────────────────────────
    function startPolling(cacheKey) {
        $status.html(loader("Processing audio..."));

        let attempts = 0;
        const maxAttempts = 60; // ~90 seconds if interval=1500ms (adjust as desired)
        const intervalMs = 1500;

        const poll = setInterval(() => {
            attempts++;

            $.getJSON(`/check_audio_upload/${cacheKey}`)
                .done(function (resp) {
                    // ensure we got an object
                    if (!resp || typeof resp !== 'object') {
                        if (attempts >= maxAttempts) {
                            clearInterval(poll);
                            onPollTimeout();
                        }
                        return;
                    }

                    // If backend still returns pending -> DO NOT update UI
                    if (resp.status === 'pending' || resp.status === 'processing') {
                        return;
                    }

                    // Only accept data when status === "done"
                    if (resp.status === 'done') {
                        clearInterval(poll);

                        const tracks = resp.tracks || resp.finalTracks || [];

                        // IMPORTANT: ensure full list is returned
                        if (!Array.isArray(tracks) || tracks.length === 0) {
                            showError("Processing completed but no tracks returned.");
                            return;
                        }

                        finalizeTrackUpdate(tracks, resp.message || "Audio processing complete!");
                        return;
                    }

                    // If failed
                    if (resp.status === 'failed') {
                        clearInterval(poll);
                        showError(resp.message || "Processing failed.");
                        return;
                    }

                    

                    // Stop polling on any non-pending response
                    clearInterval(poll);

                    // RESP shape tolerant: tracks, finalTracks, files
                    const tracks = resp.tracks || resp.finalTracks || resp.files || [];

                    if (resp.status === 'done' || Array.isArray(tracks) && tracks.length > 0) {
                        finalizeTrackUpdate(tracks, resp.message || 'Audio processing complete!');
                    } else if (resp.status === 'failed') {
                        showError(resp.message || 'Processing failed.');
                    } else {
                        // fallback: if there are tracks, accept them, otherwise timeout fallback
                        if (Array.isArray(tracks) && tracks.length > 0) {
                            finalizeTrackUpdate(tracks, resp.message || 'Audio processing complete!');
                        } else {
                            onPollTimeout();
                        }
                    }
                })
                .fail(function () {
                    // network error or 500 — stop after maxAttempts
                    if (attempts >= maxAttempts) {
                        clearInterval(poll);
                        onPollTimeout();
                    }
                });

        }, intervalMs);

        function onPollTimeout() {
            // Try to gracefully recover: fetch release data once (non-polling)
            $status.html(loader("Finalizing — fetching latest tracks..."));

            $.getJSON(`/releases/load-edit/${releaseId}`)
                .done(function (resp) {
                    if (resp.status === 'ok' && resp.release?.tracks) {
                        const tracks = resp.release.tracks.map(t => ({
                            track_id: t.id,
                            filename: t.filename || t.title,
                            duration_ms: t.duration_ms || 0,
                            isrc: t.isrc || '',
                            audio_url: t.audio_url || (t.audio_file ? t.audio_file.url : '')
                        }));
                        finalizeTrackUpdate(tracks, 'Processing complete (fallback).');
                    } else {
                        showError('Processing timed out. Please refresh the page.');
                    }
                })
                .fail(function () {
                    showError('Processing timed out. Please refresh the page.');
                });
        }
    }

    // ────────────────────────────────────────────────
    // Final UI update (single authoritative source)
    // ────────────────────────────────────────────────
    function finalizeTrackUpdate(tracks, message) {
        // Normalize files -> tracks shape if needed
        let normalized = [];

        if (!Array.isArray(tracks)) tracks = [];

        // If App B returned 'files' with original_name/path/url
        // map them to track-like entries if there is no track_id
        normalized = tracks.map((t) => {
            // if t has original_name/url => it's from AppB
            if (t.original_name || t.url) {
                return {
                    track_id: t.track_id || null,
                    filename: t.original_name || t.filename || t.title || '',
                    title: t.title || t.original_name || '',
                    duration_ms: t.duration_ms || 0,
                    isrc: t.isrc || '',
                    audio_url: t.url || t.audio_url || ''
                };
            }

            // otherwise assume it's already a track object
            return {
                track_id: t.track_id || t.id || null,
                filename: t.filename || t.title || '',
                title: t.title || '',
                duration_ms: t.duration_ms || 0,
                isrc: t.isrc || '',
                audio_url: t.audio_url || ''
            };
        });

        // ---------------------------------------
        // MERGE old tracks + new tracks
        // ---------------------------------------
        let merged = [];

        if (Array.isArray(window.__lastTracks) && window.__lastTracks.length) {
            merged = window.__lastTracks.filter(t =>
                !deletedAudioIds.includes(String(t.track_id))
            );
        }

        // append new tracks (avoid duplicates)
        tracks.forEach(t => {
            if (!merged.some(x => String(x.track_id) === String(t.track_id))) {
                merged.push(t);
            }
        });

        // Save authoritative list
        window.__lastTracks = merged;

        // Render final UI
        updateUITracks(merged);
        showSuccess(message || 'Audio processing complete!');

    }

    // ────────────────────────────────────────────────
    // Small helpers
    // ────────────────────────────────────────────────
    function updateUITracks(tracks) {
        const $audioList = $('#audioList');
        $audioList.empty();

        if (!Array.isArray(tracks) || tracks.length === 0) {
            $audioList.html('<div class="text-muted">No audio tracks found.</div>');
            $('#tracksContainer').empty();
            return;
        }

        tracks.forEach(track => {
            const duration = formatTimeMs(track.duration_ms || 0);

            $audioList.append(`
                <div class="mb-3 p-2 border rounded bg-light position-relative audio-item"
                     data-track-id="${track.track_id}">
                     <button type="button"
                             class="btn btn-sm btn-danger position-absolute top-0 end-0 m-1 delete-audio-btn"
                             data-track-id="${track.track_id}">❌</button>

                     <strong>${track.filename}</strong>
                     <span class="text-muted">(${duration})</span>

                     <audio controls class="mt-2 w-100">
                         <source src="${track.audio_url}">
                     </audio>
                </div>
            `);
        });

        // Rebuild track forms using the authoritative tracks list
        buildTrackForms(tracks);
    }

    function showSuccess(msg) {
        $status.html(`<span class="badge bg-success-subtle text-success">${msg}</span>`);
    }

    function showError(msg) {
        $status.html(`<div class="alert alert-danger mt-2">${msg}</div>`);
    }

    function loader(text) {
        return `
            <div class="d-flex align-items-center text-info">
                <div class="spinner-border spinner-border-sm me-2"></div>
                <span>${text}</span>
            </div>
        `;
    }
});



// === Step 4: Track Details ===
$('#saveTracksBtn').on('click', function() {
  const id = $('#music_release_id').val();
  const data = collectReleaseData(); // your existing function collecting tracks
  const $status = $('#tracksSaveStatus');

  // clear old errors
  $('.participant-row .participant_error, .participant-row .role_error, .participant-row .payout_error').text('');
  $('.track-level-error').remove();

  // PER-TRACK validation
  let hasError = false;
  let errorMessages = [];

  data.tracks.forEach((track, tIndex) => {
    const trackNumber = tIndex + 1;

    // track-level minimal checks
    if (!track.title || !String(track.title).trim()) {
      hasError = true;
      errorMessages.push(`Track ${trackNumber}: Title is required.`);
    }
    if (!track.artist || !String(track.artist).trim()) {
      hasError = true;
      errorMessages.push(`Track ${trackNumber}: Artist is required.`);
    }
    if (!track.feature_artist || !String(track.feature_artist).trim()) {
      hasError = true;
      errorMessages.push(`Track ${trackNumber}: Featured Artist is required.`);
    }
    if (!track.instrumental) {
      hasError = true;
      errorMessages.push(`Track ${trackNumber}: Instrumental is required.`);
    }
    if (!track.language) {
      hasError = true;
      errorMessages.push(`Track ${trackNumber}: Language is required.`);
    }
    if (!track.parental) {
      hasError = true;
      errorMessages.push(`Track ${trackNumber}: Parental is required.`);
    }
    if (!Array.isArray(track.genre) || track.genre.length === 0) {
      hasError = true;
      errorMessages.push(`Track ${trackNumber}: At least one genre is required.`);
    }
    if (!Array.isArray(track.stream_type) || track.stream_type.length === 0) {
      hasError = true;
      errorMessages.push(`Track ${trackNumber}: At least one stream/type is required.`);
    }

    // participants checks
    const participants = Array.isArray(track.participants) ? track.participants : [];
    if (participants.length === 0) {
      hasError = true;
      errorMessages.push(`Track ${trackNumber}: At least one participant is required.`);
    } else {
      let totalPayout = 0;
      participants.forEach((p, pIndex) => {
        const partIndex = pIndex + 1;
        const participantName = (p.participant || '').toString().trim();
        const roles = Array.isArray(p.roles) ? p.roles : (p.roles ? [p.roles] : []);
        const payoutRaw = p.payout;
        const payoutNum = payoutRaw === '' || payoutRaw === undefined ? NaN : parseFloat(payoutRaw);

        if (!participantName) {
          hasError = true;
          errorMessages.push(`Track ${trackNumber}, Participant ${partIndex}: Name is required.`);
        }
        if (!Array.isArray(roles) || roles.length === 0) {
          hasError = true;
          errorMessages.push(`Track ${trackNumber}, Participant ${partIndex}: At least one role is required.`);
        }
        if (isNaN(payoutNum)) {
          hasError = true;
          errorMessages.push(`Track ${trackNumber}, Participant ${partIndex}: Payout must be a number.`);
        } else if (payoutNum < 0) {
          hasError = true;
          errorMessages.push(`Track ${trackNumber}, Participant ${partIndex}: Payout must be non-negative.`);
        } else {
          totalPayout += payoutNum;
        }
      });

      // Round to two decimals before comparison
      totalPayout = Math.round(totalPayout * 100) / 100;
      if (totalPayout !== 100) {
        hasError = true;
        errorMessages.push(`Track ${trackNumber}: Participants payouts must sum to exactly 100% (currently ${totalPayout}%).`);
      }
    }
  });

  // If there are errors, show them and stop
  if (hasError) {
    // Build a compact UI error block
    const $errBox = $('<div class="alert alert-danger track-level-error"></div>');
    const $ul = $('<ul class="mb-0"></ul>');
    errorMessages.forEach(m => $ul.append(`<li>${m}</li>`));
    $errBox.append('<strong>Validation failed:</strong>').append($ul);

    $status.html($errBox);
    $('html, body').animate({ scrollTop: $status.offset().top - 100 }, 250);
    return; // STOP: do not submit
  }

  // show spinner
  $status.html(`
    <div class="d-flex align-items-center text-primary">
      <div class="spinner-border spinner-border-sm me-2" role="status"></div>
      <span>Saving tracks, please wait...</span>
    </div>
  `);

  // AJAX submit
  $.ajax({
    url: `/update_tracks/${id}`,
    method: 'PUT',
    data: {
      _token: $('meta[name="csrf-token"]').attr('content'),
      tracks: data.tracks
    },
    success: function (res) {
      $status.html(`
        <span class="badge bg-success-subtle text-success border border-success rounded-pill px-3 py-2">
          ${res.message || 'Tracks saved'}
        </span>
      `);
      setTimeout(() => $status.fadeOut('slow', () => $status.empty().show()), 3000);
    },
    error: function (xhr) {
      if (xhr.status === 422 && xhr.responseJSON?.errors) {
        const errors = xhr.responseJSON.errors;
        let msg = '<ul class="mb-0">';
        for (const f in errors) msg += `<li>${errors[f].join(', ')}</li>`;
        msg += '</ul>';
        $status.html(`<div class="alert alert-warning mt-2"><strong>Validation failed:</strong>${msg}</div>`);
      } else {
        $status.html(`<div class="alert alert-danger mt-2">Error saving tracks. Please try again.</div>`);
      }
    }
  });
});




// === Step 5: Outlets ===
$('#saveOutletsBtn').on('click', function() {
  const id = $('#music_release_id').val();
  const _token = $('meta[name="csrf-token"]').attr('content');
  const outlets = [];

  // Build proper array of objects for validation
  $('#outletsForm tbody tr').each(function() {
    const $row = $(this);
    const checkbox = $row.find('.row-checkbox');

    if (checkbox.is(':checked')) {
      const outletId = parseInt(checkbox.val(), 10);
      const outletDate = $row.find('.outlet-date').val();

      if (outletId && outletDate) {
        outlets.push({
          outlet_id: outletId,
          outlet_release_date: outletDate
        });
      }
    }
  });

  if (outlets.length === 0) {
    alert('Please select at least one outlet and provide its release date.');
    return;
  }

  // Disable button & show spinner
  const $btn = $(this);
  const originalText = $btn.html();
  $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span> Saving...');

  $.ajax({
    url: `/update_outlets/${id}`,
    method: 'PUT',
    data: { _token, outlets },
    success: res => {
      alert(res.message);

      // Show success badge
      $('#outletsSaveStatus').html(`
        <span class="badge bg-success-subtle text-success border border-success rounded-pill px-3 py-2">
          Outlets updated successfully
        </span>
      `);
      setTimeout(() => $('#outletsSaveStatus').fadeOut('slow', () => $('#outletsSaveStatus').empty().show()), 3000);

      // Restore button
      $btn.prop('disabled', false).html(originalText);
    },
    error: xhr => {
      $btn.prop('disabled', false).html(originalText);
      if (xhr.status === 422 && xhr.responseJSON?.errors) {
        const errors = xhr.responseJSON.errors;
        let msg = 'Validation failed:\n';
        for (const field in errors) msg += `- ${errors[field].join(', ')}\n`;
        alert(msg);
      } else {
        alert('Error: ' + xhr.responseText);
      }
    }
  });
});


// === Step 6: Verification ===

$('#saveVerificationBtn').on('click', function () {

    let btn = $(this);
    let status = $('#verificationSaveStatus');

    let form = document.getElementById('acct_verification');
    let formData = new FormData(form);

    const id = $('#music_release_id').val();

    // METHOD SPOOFING (CRITICAL)
    formData.append('_method', 'PUT');

    // FORCE STANDARD FIELDS
    formData.set('official_id', $('.official_id').val());
    formData.set('bank', $('#bank').val());
    formData.set('account_number', $('#account_number').val());
    formData.set('account_name', $('#account_name').val());
    formData.set('video_links', $('#youtube_linkk').val());

    // REBUILD SOCIAL HANDLES
    formData.delete('social_media_handles[]');

    let handles = [];
    $('input[name="social_media_handles[]"]').each(function () {
        let val = $(this).val().trim();
        if (val !== '') {
            handles.push(val);
        }
    });

    if (handles.length === 0) {
        status.html(`<span class="badge bg-danger">Add at least one social handle</span>`);
        return;
    }

    handles.forEach(h => {
        formData.append('social_media_handles[]', h);
    });

    // DEBUG — YOU SHOULD SEE VALUES NOW
    for (let pair of formData.entries()) {
        console.log(pair[0], pair[1]);
    }

    btn.prop('disabled', true);
    status.html(`
        <span class="spinner-border spinner-border-sm me-2"></span>
        Saving...
    `);

    $.ajax({
        url: `/update_verification/${id}`,
        method: "POST", //  MUST BE POST
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: formData,
        processData: false,
        contentType: false,

        success(resp) {
            status.html(`<span class="badge bg-success">Saved</span>`);
            btn.prop('disabled', false);
        },

        error(xhr) {
            btn.prop('disabled', false);
            console.log(xhr.responseJSON);
            status.html(`<span class="badge bg-danger">${xhr.responseJSON?.message}</span>`);
        }
    });
});




// === Final Submit ===
$('#updateReleaseBtn').on('click', function() {
  const id = $('#music_release_id').val();
  $.ajax({
    url: `/update_final/${id}`,
    method: 'POST',
    data: { _token: $('meta[name="csrf-token"]').attr('content') },
    success: res => alert(res.message),
    error: xhr => alert('Error: ' + xhr.responseText)
  });
});



/* -------------------------------------------------------------------------- */
/*                           Collect Release Data                             */
/* -------------------------------------------------------------------------- */
function collectReleaseData() {
  const data = {
    tracks: [],
    outlets: []
  };

  // === Collect Tracks ===
  $('#tracksContainer .track-card').each(function() {
    const card = $(this);
    const track = {
      track_id: card.find('.track-id').val(),
      title: card.find('.track-title').val(),
      artist: card.find('.track-artist').val(),
      feature_artist: card.find('.track-feature_artist').val(),
      iswc: card.find('.track-iswc').val(),
      instrumental: card.find('.track-instrumental').val(),
      language: card.find('.track-language').val(),
      parental: card.find('.track-parental').val(),
      stream_type: card.find('.track-for').val() || [],
      genre: card.find('.track-genre').val() || [],
      lyrics: card.find('.track-lyrics').val(),
      isrc: card.find('.track-isrc').val(),
      duration_ms: card.find('.track-duration').val() || 0,
      participants: []
    };

    // Collect participants
    card.find('.participant-row').each(function() {
      const row = $(this);
      track.participants.push({
        participant: row.find('.p-participant').val(),
        roles: row.find('.p-role').val() || [],
        payout: row.find('.p-payout').val()
      });
    });

    data.tracks.push(track);
  });

  // === Collect Outlets ===
  $('#outletsForm input[type="checkbox"]:checked').each(function() {
    const row = $(this).closest('tr');
    data.outlets.push({
      outlet_id: $(this).val(),
      outlet_release_date: row.find('.outlet-date').val()
    });
  });

  return data;
}


/* -------------------------------------------------------------------------- */
/*                    Delete Audio Immediately (No Reload)                    */
/* -------------------------------------------------------------------------- */

function attachDeleteAudioHandler() {
  $(document).off('click', '.delete-audio-btn');

  $(document).on('click', '.delete-audio-btn', function (e) {
    e.preventDefault();
    const $btn = $(this);
    const $audioItem = $btn.closest('.audio-item');

    const trackId =
      $btn.attr('data-track-id') ||
      $btn.data('track-id') ||
      $audioItem.attr('data-track-id') ||
      $audioItem.data('track-id') ||
      null;

    const isNewUpload = !trackId;

    if (!confirm('Are you sure you want to delete this audio?')) return;

    // NEW: If it's a temporary (not yet saved) upload
    if (isNewUpload) {
      $audioItem.fadeOut(300, function () { $(this).remove(); });
      return;
    }

    $.ajax({
      url: `/delete_audio/${trackId}`,
      method: 'DELETE',
      headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },

      beforeSend() {
        $btn.prop('disabled', true).text('⏳');
      },

      success(resp) {
        if (resp.status === 'ok') {

          // ----------------------------------------------------------
          // ✅ FIX: Remove from local cache so it will NEVER reappear
          // ----------------------------------------------------------
          if (Array.isArray(window.__lastTracks)) {
            window.__lastTracks = window.__lastTracks.filter(
              t => String(t.track_id) !== String(trackId)
            );
          }

          // Also store deleted IDs in case upload handler checks them
          if (typeof deletedAudioIds !== "undefined") {
            deletedAudioIds.push(String(trackId));
          }

          // Remove UI elements
          $audioItem.fadeOut(300, () => $audioItem.remove());
          $(`#tracksContainer .track-card[data-track-id="${trackId}"]`).fadeOut(300, function () {
            $(this).remove();
          });

          $('#audioUploadStatus').html(`
            <span class="badge bg-danger-subtle text-danger border border-danger rounded-pill px-3 py-2">
              Audio deleted successfully
            </span>
          `);

          setTimeout(() => {
            $('#audioUploadStatus').fadeOut('slow', () => $('#audioUploadStatus').empty().show());
          }, 3000);

        } else {
          alert(resp.message || 'Failed to delete audio.');
        }
      },

      error(err) {
        console.error(err);
        alert('Error deleting audio.');
      },

      complete() {
        $btn.prop('disabled', false).text('❌');
      },
    });
  });
}


// Bind delete event once initially
attachDeleteAudioHandler();



// === Handle "Check All" functionality ===
$(document).on('change', '#checkAll', function() {
    const isChecked = $(this).is(':checked');
    $('.row-checkbox').prop('checked', isChecked);
});

// === Keep "Check All" synced when individual boxes are clicked ===
$(document).on('change', '.row-checkbox', function() {
    const allChecked = $('.row-checkbox').length === $('.row-checkbox:checked').length;
    $('#checkAll').prop('checked', allChecked);
});



</script>



<script type="text/javascript">
    $(document).ready(function() {
        $('#account_number').keyup(function() {
            $('#the_bank').show();
            $('#account_parent').show();
        });
        
    });
</script>

<script type="text/javascript">
    $(document).ready(function() {
        $('#the_bank').hide();
        $('#account_parent').hide();
        
    });
</script>

<script type="text/javascript">
    $(document).ready(function() {
        $('#the_doc').hide();
        
    });
</script>

<script type="text/javascript">
    $(document).ready(function() {
        $('.official_id').change(function() {
            $('#the_doc').show();
        });
        
    });
</script>


<script>
  $('#bank').change(function() {
    if (isRestoringDraft) return; 

    var bank_code = $(this).val();
    var account_number = $("#account_number").val();
    $("#inputLoader").show();

    $.ajax({
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        url: "{{ route('resolve_account') }}",
        type: "POST",
        data: { bank_code: bank_code, account_number: account_number },
        success: function(response) {
            if (response.success) {
                $('#account_name').val(response.data.data.account_name);
            } else {
                $('#account_name').val('');
                alert(response.message);
            }
        },
        error: function(xhr) {
            console.error(xhr.responseText);
        },
        complete: function() {
            $("#inputLoader").hide();
        }
    });
});


</script>


<script>
$(document).ready(function() {
    $('#youtube_linkk').on('blur', function() {
        var youtube_url = $(this).val().trim();
        if (youtube_url === '') return; // skip empty

        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: "{{ route('releases.youtube') }}", // define this route
            type: 'POST',
            data: { youtube_url: youtube_url },
            success: function(response) {
                if (response.valid === false) {
                    alert(response.message || 'Invalid YouTube URL');
                    $('#youtube_linkk').val(''); // optional: clear input
                } else {
                    console.log('Video is valid:', response);
                }
            },
            error: function(xhr) {
                console.error(xhr.responseText);
                alert('An error occurred while validating the video');
            }
        });
    });
});
</script>


@endsection    