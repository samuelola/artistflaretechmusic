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
                                  @include('dashboard.pages.music.musicplanA')
                                @elseif($subcount->subscription->subscription_name === 'Easy-Buy') 
                                  @include('dashboard.pages.music.musicplanB')
                                @elseif($subcount->subscription->subscription_name === 'FlarePro') 
                                  @include('dashboard.pages.music.musicplanC')  
                                @elseif($subcount->subscription->subscription_name === 'Standard-Label') 
                                  @include('dashboard.pages.music.musicplanD')    
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

<!-- <script>
   // Continuous queue worker trigger
   async function triggerQueue() {
    try {
        const token = document.querySelector('meta[name="csrf-token"]').content;

        await fetch('/trigger-queue', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': token,
                'Content-Type': 'application/json'
            },
        });

        // Repeat after 5 seconds
        setTimeout(triggerQueue, 5000);
        console.log('queue started')
    } catch (err) {
        console.error('Error triggering queue:', err);
        setTimeout(triggerQueue, 10000); 
    }
    
  }


triggerQueue();
</script> -->

<script>
  let releaseId = null;
$(function(){
  const csrf = $('meta[name="csrf-token"]').attr('content');
  
  let uploadedFilesMeta = []; // {audio_id, track_id, filename, path, duration_ms}

  function setProgress(step){
    const total = 6;
    const percent = Math.round((step/total) * 100);
    $('#progressBar').css('width', percent+'%');
    $('#progressLabel').text('Step '+step+' of '+total);
  }
  setProgress(1);

  // Back buttons: simple tab navigation only
document.getElementById('backToStep1')?.addEventListener('click', () => {
  new bootstrap.Tab(document.querySelector('#step1-tab')).show();
});

document.getElementById('backToStep2')?.addEventListener('click', () => {
  new bootstrap.Tab(document.querySelector('#step2-tab')).show();
});

document.getElementById('backToStep3')?.addEventListener('click', () => {
  new bootstrap.Tab(document.querySelector('#step3-tab')).show();
});

document.getElementById('backToStep4')?.addEventListener('click', () => {
  new bootstrap.Tab(document.querySelector('#step4-tab')).show();
});
document.getElementById('backToStep5')?.addEventListener('click', () => {
  new bootstrap.Tab(document.querySelector('#step5-tab')).show();
});



  // General save step (Step 1)
  $('#saveStep1').on('click', function(){
    const fields = {
      title: $('#title').val(),
      plan: $('#plan').val(),
      label_name: $('#label_name').val(),
      stereo_type: $('#stereo_type').val(),
      release_type : $('#release_type').val(),
      stereo_code  : $('#stereo_code').val(),	
      release_date : $('#release_date').val(),
      
      
    };
    $('#saveStep1Status').html('<span class="spinner-border spinner-border-sm spinner-small" role="status"></span>');
    $.ajax({
      url: '{{ route("releases.ajax.save") }}',
      method: 'POST',
      headers: {'X-CSRF-TOKEN': csrf},
      data: { fields: fields, music_release_id: releaseId },
      success(resp){
        releaseId = resp.music_release_id;
        $('#music_release_id').val(releaseId);

        if (resp.stereo_code) {
          $('#stereo_code').val(resp.stereo_code); //fill from backend
        }

        $('#saveStep1Status').html('<span class="badge bg-success saved-badge">Saved</span>');
        $('.is-invalid').removeClass('is-invalid');
        setProgress(2);
      },
      
      error(xhr) {
            if (xhr.status === 422) {
            // Parse the JSON error response
              let errors = xhr.responseJSON.errors;
              
              // Loop through all error messages
              for (let field in errors) {
                  alert(errors[field][0]); // show the first error message
                  $('#saveStep1Status').text('Upload failed'); // stop spinner after alert closed
              }
            } else {
                $('#saveStep1Status').text('Error uploading artwork'); 
            }
        }
    });
  });

 $('#goto2').on('click', function() {
  const step2Tab = new bootstrap.Tab(document.querySelector('#step2-tab'));
  step2Tab.show();
  setProgress(2);
});

  // Artwork upload
  $('#uploadArtworkBtn').on('click', function(){
    if (!releaseId) { alert('Save Step 1 first'); return; }
    let file = $('#artwork')[0].files[0];
    if (!file) { alert('Pick an artwork file'); return; }
    let fd = new FormData();
    fd.append('artwork', file);
    fd.append('music_release_id', releaseId);
    $('#artworkStatus').html('<span class="spinner-border spinner-border-sm spinner-small" role="status"></span>');
    $.ajax({
      url: '{{ route("releases.upload.artwork") }}',
      method: 'POST',
      data: fd,
      processData: false,
      contentType: false,
      headers: {'X-CSRF-TOKEN': csrf},
      success: function(resp) {
         $('#artworkStatus').html('<span class="badge bg-success saved-badge">Saved</span>');
         $('#artworkPreview').html('<img src="'+resp.artwork.url+'" alt="artwork" class="img-thumbnail" style="max-width:200px">');
      },
      
      error: function(xhr) {
        if (xhr.status === 422) {
            // Parse the JSON error response
            let errors = xhr.responseJSON.errors;
            
            // Loop through all error messages
            for (let field in errors) {
                alert(errors[field][0]); // show the first error message
                $('#artworkStatus').text('Upload failed'); // stop spinner after alert closed
            }
        } else {
            $('#artworkStatus').text('Error uploading artwork'); 
        }
    }
      
    });
  });

  $('#goto3').on('click', function() {
  const step3Tab = new bootstrap.Tab(document.querySelector('#step3-tab'));
  step3Tab.show();
  setProgress(3);
});

// Live preview when selecting artwork
$('#artwork').on('change', function(e) {
  const file = e.target.files[0];
  if (!file) {
    $('#artworkPreview').empty();
    return;
  }

  // Only image types
  if (!file.type.startsWith('image/')) {
    alert('Please select a valid image file.');
    $('#artwork').val('');
    return;
  }

  // Create preview
  const reader = new FileReader();
  reader.onload = function(ev) {
    $('#artworkPreview').html(`
      <img src="${ev.target.result}" 
           alt="Artwork Preview" 
           class="img-thumbnail" 
           style="max-width:200px;">
    `);
  };
  reader.readAsDataURL(file);
});


  // Audio selection + duration extraction before upload
  $('#audios').on('change', function(e) {
  $('#audioList').empty();
  uploadedFilesMeta = [];
  const files = [...e.target.files];
  if (!files.length) return;

  // Add Clear All button
  const clearAllBtn = $('<button type="button" class="btn btn-sm btn-danger mb-3" id="clearAllAudios">Clear All</button>');
  $('#audioList').append(clearAllBtn);

  files.forEach((file, index) => {
    const safeFilename = file.name;
    const row = $(`
  <div class="mb-3 p-2 border rounded bg-light position-relative audio-item"
       data-filename="${encodeURIComponent(safeFilename)}"
       data-track-id="">
    <button type="button" class="btn-close position-absolute top-0 end-0 m-2 remove-audio" title="Remove"></button>
    <strong>${safeFilename}</strong> 
    <span class="text-muted">(${Math.round(file.size / 1024)} KB)</span>
    <div class="small text-muted audio-status">Reading duration...</div>
  </div>
`);

    $('#audioList').append(row);

    const url = URL.createObjectURL(file);
    const audio = new Audio();
    audio.preload = 'metadata';
    audio.src = url;

    const player = $('<audio controls class="mt-2 w-100"></audio>');
    player.attr('src', url);
    row.append(player);

    audio.addEventListener('loadedmetadata', function() {
      const duration = Math.round(audio.duration * 1000); // ms
      row.find('.audio-status').text('Duration: ' + formatTimeMs(duration));
      uploadedFilesMeta.push({ id: index, file, filename: file.name, duration_ms: duration });
    });

    audio.addEventListener('error', function() {
      row.find('.audio-status').text('Could not read audio metadata');
    });
  });
});


// Remove a single audio (and its track if it exists)

$('#audioList').on('click', '.remove-audio', async function () {
  const item = $(this).closest('.audio-item');
  const filename = decodeURIComponent(item.attr('data-filename') || item.find('strong').text().trim());
  const musicReleaseId = $('#music_release_id').val();
  let trackId = item.attr('data-track-id') || '';

  if (!confirm(`Remove "${filename}" and its track?`)) return;

  // Try to find metadata if trackId is missing
  if (!trackId && uploadedFilesMeta.length > 0) {
    const meta = uploadedFilesMeta.find(f =>
      f.filename === filename || f.file?.name === filename || f.title === filename
    );
    if (meta && meta.track_id) trackId = meta.track_id;
  }

  // --- Always attempt backend deletion ---
  if (musicReleaseId) {
    try {
      item.find('.audio-status').html('<span class="spinner-border spinner-border-sm"></span> Deleting...');

      const resp = await fetch('/delete_audio_track', {
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
          'Content-Type': 'application/json',
        },
        body: JSON.stringify({
          music_release_id: musicReleaseId,
          track_id: trackId || null,
          filename: filename || null, // fallback for backend
        }),
      });

      const data = await resp.json();

      if (data.status !== 'ok') {
        alert('Server error removing track: ' + (data.message || 'Unknown error'));
        item.find('.audio-status').text('');
        return;
      }

      //Remove immediately from UI and memory
      item.remove();
      $(`#tracksContainer .track-card[data-track-id="${trackId}"]`).remove();
      uploadedFilesMeta = uploadedFilesMeta.filter(
        f => f.track_id !== trackId && f.filename !== filename
      );

      // Clean up UI if empty
      if (uploadedFilesMeta.length === 0) {
        $('#audios').val('');
        $('#audioList').empty();
        $('#tracksContainer').empty();
        $('#audioUploadStatus, #tracksSaveStatus').html('');
      }

    } catch (err) {
      console.error('Error deleting audio/track:', err);
      alert('Could not delete audio from server.');
      item.find('.audio-status').text('');
      return;
    }
  }
});






// Upgraded Clear All button handler

//Enhanced Clear All Audios & Tracks Handler
$('#audioList').on('click', '#clearAllAudios', async function() {
  if (!confirm('Are you sure you want to delete all uploaded audios and their tracks? This action cannot be undone.')) {
    return;
  }

  const musicReleaseId = $('#music_release_id').val();

  if (!musicReleaseId) {
    alert('No release ID found. Please save the release first.');
    return;
  }

  try {
    const response = await fetch('/clear_audios', {
      method: 'POST',
      headers: {
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        'Content-Type': 'application/json',
      },
      body: JSON.stringify({ music_release_id: musicReleaseId }),
    });

    const data = await response.json();

    if (data.status === 'ok') {
      alert('All audios and their tracks deleted successfully.');

      //Instantly clear frontend
      uploadedFilesMeta = [];
      $('#audios').val('');
      $('#audioList').empty();
      $('#tracksContainer').empty(); // <-- clears all track cards immediately
      $('#audioUploadStatus').html(''); // optional: reset upload status
      $('#tracksSaveStatus').html('');  // optional: reset track status
    } else {
      alert('Error deleting audios: ' + (data.message || 'Unknown error.'));
    }
  } catch (error) {
    console.error('Delete failed:', error);
    alert('An unexpected error occurred while deleting audios.');
  }
});



  function formatTimeMs(ms){
    if (!ms) return '0:00';
    const s = Math.floor(ms/1000);
    const mm = Math.floor(s/60);
    const ss = ('0'+(s%60)).slice(-2);
    return mm+':'+ss;
  }



  function pollAudioUploadStatus(cacheKey, callback) {
  const interval = setInterval(() => {
    $.get(`/audio_upload/status/${cacheKey}`, (res) => {
      if (res.status === 'ok') {
        clearInterval(interval);
        callback(res.files, res.music_release_id);
        
      }
    });
  }, 3000);
}

  // Upload audios to server via AJAX (multipart + sending durations for each filename)
  // Global array to store all tracks currently on page
window.__allTracks = window.__allTracks || [];

$('#uploadAudiosBtn').on('click', function () {

  if (!releaseId) { alert('Save Step 1 first'); return; }
  if (!uploadedFilesMeta.length) { 
    alert('Select audio files first and wait for durations to be extracted.'); return;
  }

  let fd = new FormData();
  fd.append('music_release_id', releaseId);
  uploadedFilesMeta.forEach(item => {
    fd.append('audios[]', item.file, item.filename);
  });
  let durationsMap = {};
  uploadedFilesMeta.forEach(item => durationsMap[item.filename] = item.duration_ms);
  fd.append('durations', JSON.stringify(durationsMap));

  // Progress UI
  $('#audioUploadStatus').html(`
    <div class="progress mt-2" style="height: 20px;">
      <div id="audioUploadProgress" class="progress-bar progress-bar-striped progress-bar-animated" 
           role="progressbar" style="width: 0%">0%</div>
    </div>
  `);

  $.ajax({
    url: '{{ route("releases.upload.audio") }}',
    method: 'POST',
    headers: { 'X-CSRF-TOKEN': csrf },
    data: fd,
    processData: false,
    contentType: false,

    xhr: function () {
  const xhr = new window.XMLHttpRequest();

  let displayProgress = 5; // start instantly (no 0% delay)

  // Set initial state
  $('#audioUploadProgress')
    .css('width', '5%')
    .text('Starting...');

  xhr.upload.addEventListener('progress', function (evt) {
    if (evt.lengthComputable) {

      const realProgress = (evt.loaded / evt.total) * 100;

      // Smooth easing toward real progress
      displayProgress += (realProgress - displayProgress) * 0.2;

      const percent = Math.min(99, Math.round(displayProgress));

      $('#audioUploadProgress')
        .css('width', percent + '%')
        .text(percent + '% Uploading...');
    }
  }, false);

  return xhr;
},

    success(resp) {
          if (resp.status === 'processing') {

            // STEP 1: ADD TRACKS IMMEDIATELY
            const files = resp.tracks || [];

            files.forEach(file => {
              const exists = window.__allTracks.find(t => t.track_id === file.track_id);
              if (!exists) window.__allTracks.push(file);
              else Object.assign(exists, file);
            });

            // IMPORTANT: show them instantly
            rebuildTrackUI();

            // STEP 2: show processing state
            $('#audioUploadProgress')
            .css('width', '100%')
            .text('Processing audio...');

            let dots = 0;

          const processingInterval = setInterval(() => {
            dots = (dots + 1) % 4;

            $('#audioUploadProgress')
              .text('Processing' + '.'.repeat(dots));

          }, 500);

            $('#audioUploadStatus').append(
              '<div class="text-muted mt-2">Uploading in background...</div>'
            );

            // STEP 3: poll in background (no UI blocking)
            pollAudioUploadStatus(resp.cache_key, (files, releaseId) => {

              files.forEach(file => {
                const exists = window.__allTracks.find(t => t.track_id === file.track_id);
                if (!exists) window.__allTracks.push(file);
                else Object.assign(exists, file); // update status + URL
              });

              // refresh UI with completed data
              rebuildTrackUI();

              clearInterval(processingInterval);

          $('#audioUploadProgress')
            .removeClass('progress-bar-animated progress-bar-striped')
            .addClass('bg-success')
            .text('Upload Complete');

              $('#audioUploadStatus').html(
                '<span class="badge bg-success saved-badge">Uploaded</span>'
              );
            });
          } else if (resp.status === 'ok') {
            // Normal upload completed immediately
            files = resp.tracks || resp.finalTracks || [];
            files.forEach(file => {
              const exists = window.__allTracks.find(t => t.filename === file.filename);
              if (!exists) window.__allTracks.push(file);
              else Object.assign(exists, file);
            });
            rebuildTrackUI();

            $('#audioUploadProgress')
              .removeClass('progress-bar-animated')
              .addClass('bg-success')
              .text('Upload Complete');

            $('#audioUploadStatus').html('<span class="badge bg-success saved-badge">Uploaded</span>');

          } else {
            $('#audioUploadProgress')
              .removeClass('progress-bar-animated')
              .addClass('bg-warning')
              .text('Waiting...');
          }
    },

    error(err) {
      console.error(err);
      $('#audioUploadProgress')
        .removeClass('progress-bar-animated')
        .addClass('bg-danger')
        .text('Upload Failed');
    }
  });

  // --- Helper to rebuild the track list UI ---
  function rebuildTrackUI() {
    const $tracksContainer = $('#tracksContainer');
    const $audioList = $('#audioList');
    $audioList.empty();
    $tracksContainer.empty();

    if (!window.__allTracks.length) {
      $audioList.html('<div class="text-muted">No audio tracks found.</div>');
      return;
    }

    const clearAllBtn = $('<button type="button" class="btn btn-sm btn-danger mb-3" id="clearAllAudios">Clear All</button>');
    $audioList.append(clearAllBtn);
    window.__allTracks.forEach(track => {
      const duration = formatTimeMs(track.duration_ms || 0);

      const audioPlayer = track.status === 'completed' && track.audio_url
      ? `<audio controls class="mt-2 w-100">
          <source src="${track.audio_url}" type="audio/mpeg">
        </audio>`
      : `<div class="text-warning mt-2">Processing audio...</div>`;
      // audio list
      $audioList.append(`
        <div class="mb-3 p-2 border rounded bg-light position-relative audio-item" data-track-id="${track.track_id || ''}">
          <button type="button" class="btn btn-sm btn-danger position-absolute top-0 end-0 m-1 remove-audio" data-track-id="${track.track_id || ''}">❌</button>
          <strong>${track.filename}</strong>
          <span class="text-muted">(${duration})</span>
          ${audioPlayer}
        </div>
      `);

      // tracks container (forms)
      const trackCard = $(`
        <div class="track-card border p-2 mb-2 rounded" data-track-id="${track.track_id}">
          <strong>${track.filename}</strong>
          <span class="text-muted ms-2">${duration}</span>
        </div>
      `);
      $tracksContainer.append(trackCard);
    });

    // Rebuild forms (your original function)
    buildTrackForms(window.__allTracks);

    // Reattach delete handlers
    if (typeof attachDeleteAudioHandler === 'function') attachDeleteAudioHandler();
  }

});



  $('#goto4').on('click', function() {
  const step4Tab = new bootstrap.Tab(document.querySelector('#step4-tab'));
  step4Tab.show();
  setProgress(4);
});

  // Build dynamic track forms based on uploadedFilesMeta
  function buildTrackForms(files){
    $('#tracksContainer').empty();

    files.forEach((f, index) => {
        const card = $(`
        <div class="card track-card mb-3" data-track-id="${f.track_id}">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <h5 style="font-size: 20px !important;">Track ${index+1}: <span class="track-file-name">${f.filename}</span></h5>
                </div>

                <input type="hidden" class="track-id" value="${f.track_id}">

                <div class="row mb-3 mt-3">
                    <div class="col-md-4">
                        <label>Track Title</label>
                        <input class="form-control track-title" value="${escapeHtml(f.filename.replace(/\.[^/.]+$/, ""))}">
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
                              <option value="{{$value->name}}">{{$value->name}}</option>
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
                        <option value="Download">Download</option>
                        <option value="Stream">Stream</option>
                      </select>
                    </div>
                    <div class="col-md-4">
                      <label>Genre(s)</label>
                      <select multiple="multiple" class="form-control track-genre js-example-basic-multiple">
                        @foreach($genres as $value)
                          <option value="{{$value->name}}">{{$value->name}}</option>
                        @endforeach
                      </select>
                    </div>
                  </div>

                  <div class="row mb-3">
                   
                    <div class="col-md-4">
                      <label>Duration</label>
                      <input class="form-control track-duration" type="text" value="${formatTimeMs(f.duration_ms)}" readonly>
                    </div>
                    <div class="col-md-4">
                      <label>ISRC Code</label>
                      <input class="form-control track-isrc" type="text" value="" readonly>
                    </div>
                    <div class="col-md-4">
                      <button type="button" class="btn btn-sm btn-outline-primary add-note-btn align-center">
                        <i class="bi bi-plus-circle"></i> Add Lyrics
                      </button>
                      <div class="notes-container mt-2" style="display: none;">
                        <textarea class="form-control track-lyrics" rows="3" placeholder="Enter lyrics..."></textarea>
                      </div>
                    </div>
                  </div>

                  <div class="mb-3">
                    <label>Preview Audio</label>
                    <audio controls class="w-100 mt-1">
                      <source src="${f.audio_url || ''}" type="audio/mpeg">
                    </audio>
                  </div>

                <!-- other fields remain unchanged -->

                <div class="participants-section">
                    <h6>Participants</h6>
                    <div class="participants-list"></div>
                    <button class="btn btn-sm btn-outline-primary add-participant">Add Participant</button>
                </div>
            </div>
        </div>
        `);

        // Initialize Select2s
        card.find('.js-example-basic-single, .js-example-basic-multiple').select2({ width: '100%' });

        // Restore multiselect values
        if (Array.isArray(f.genre)) card.find('.track-genre').val(f.genre).trigger('change');
        if (Array.isArray(f.for)) card.find('.track-for').val(f.for).trigger('change');
        if (f.language) card.find('.track-language').val(f.language).trigger('change');

        // --- Participants ---
        const list = card.find('.participants-list');
        list.empty();
        if (f.participants && f.participants.length > 0) {
            f.participants.forEach((p, idx) => {
                list.append(buildParticipantRowHtml({
                    participant: p.participant,
                    roles: Array.isArray(p.role) ? p.role : JSON.parse(p.role || '[]'),
                    payout: p.payout
                }, idx));
            });
        }
        // Otherwise: do NOT auto-add empty participant row

        $('#tracksContainer').append(card);
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






  // Generate ISRC (call server)
  $('#tracksContainer').on('click', '.gen-isrc', function(){
    const trackCard = $(this).closest('.track-card');
    const trackId = trackCard.data('track-id');
    $('#tracksSaveStatus').html('');
    const badge = trackCard.find('.isrc-badge');
    badge.html('<span class="spinner-border spinner-border-sm spinner-small" role="status"></span>');
    $.ajax({
      url: '{{ route("releases.generate.isrc") }}',
      method: 'POST',
      headers: {'X-CSRF-TOKEN': csrf},
      data: { music_release_id: $('#music_release_id').val(), track_id: trackId },
      success(resp){
        badge.html('<span class="badge bg-info text-dark">'+resp.isrc+'</span>');
      },
      error(){ badge.text('Error generating ISRC'); }
    });
  });

 $('#saveTracksBtn').on('click', function () {
    const tracks = [];
    let hasError = false;
    let errorMsg = '';

    $('#tracksContainer .track-card').each(function () {
    const $card = $(this);
    const isHidden = $card.hasClass('d-none') || !$card.is(':visible');
    const title = ($card.find('.track-title').val() || '').trim();
    const artist = ($card.find('.track-artist').val() || '').trim();

    // Remove if it's hidden or has no meaningful input
    if (isHidden || (!title && !artist)) {
        console.log('Removing unused track card:', title, artist);
        $card.remove();
    }
});

    // iterate over each track card
    $('#tracksContainer .track-card').each(function (index) {
        if (hasError) return false;

        const $card = $(this);
        const trackIndex = index + 1;

        const trackId = $card.data('track-id') || null;
        const title = ($card.find('.track-title').val() || '').trim();
        const artist = ($card.find('.track-artist').val() || '').trim();
        const feature_artist = ($card.find('.track-feature_artist').val() || '').trim();
        const instrumental = $card.find('.track-instrumental').val() || '';
        const language = $card.find('.track-language').val() || '';
        const parental = $card.find('.track-parental').val() || '';
        const genre = $card.find('.track-genre').val() || [];
        const stream_type = $card.find('.track-for').val() || [];
        const duration_text = $card.find('.track-duration').val() || '';
        const duration_ms = parseDurationTextToMs(duration_text);
        const track_lyrics = ($card.find('.track-lyrics').val() || '').trim();
        const iswc = ($card.find('.track-iswc').val() || '').trim();

        // Track-level validation
        if (!title) { hasError = true; errorMsg = `Track ${trackIndex}: Title is required.`; return false; }
        if (!artist) { hasError = true; errorMsg = `Track ${trackIndex}: Artist is required.`; return false; }
        if (!feature_artist) { hasError = true; errorMsg = `Track ${trackIndex}: Featured Artist is required.`; return false; }
        if (!instrumental) { hasError = true; errorMsg = `Track ${trackIndex}: Instrumental is required.`; return false; }
        if (!language) { hasError = true; errorMsg = `Track ${trackIndex}: Language is required.`; return false; }
        if (!parental) { hasError = true; errorMsg = `Track ${trackIndex}: Parental field is required.`; return false; }
        if (!Array.isArray(genre) || genre.length === 0) { hasError = true; errorMsg = `Track ${trackIndex}: At least one Genre is required.`; return false; }
        if (!Array.isArray(stream_type) || stream_type.length === 0) { hasError = true; errorMsg = `Track ${trackIndex}: Stream type (For) is required.`; return false; }

        
        //Participant validation
const participants = [];

// Get only *visible and filled* participant rows
const $participantRows = $card.find('.participant-row').filter(function () {
    const $row = $(this);
    const isHidden = $row.hasClass('d-none') || !$row.is(':visible');
    const name = ($row.find('.p-participant').val() || '').trim();
    const payout = ($row.find('.p-payout').val() || '').trim();
    return !isHidden && (name || payout); // keep only active/filled rows
});

if ($participantRows.length === 0) {
    hasError = true;
    errorMsg = `Track ${trackIndex}: At least one participant is required.`;
    return false;
}

let totalPayout = 0;

$participantRows.each(function (pIdx) {
    if (hasError) return false;

    const pIndex = pIdx + 1;
    const $row = $(this);
    const participantName = ($row.find('.p-participant').val() || '').trim();
    const roles = $row.find('.p-role').val() || [];
    const payoutStr = ($row.find('.p-payout').val() || '').trim();

    if (!participantName) {
        hasError = true;
        errorMsg = `Track ${trackIndex}, Participant ${pIndex}: Name is required.`;
        return false;
    }

    if (!Array.isArray(roles) || roles.length === 0) {
        hasError = true;
        errorMsg = `Track ${trackIndex}, Participant ${pIndex}: At least one role must be selected.`;
        return false;
    }

    if (!payoutStr) {
        hasError = true;
        errorMsg = `Track ${trackIndex}, Participant ${pIndex}: Payout is required.`;
        return false;
    }

    const payout = parseFloat(payoutStr);
    if (isNaN(payout) || payout < 0) {
        hasError = true;
        errorMsg = `Track ${trackIndex}, Participant ${pIndex}: Payout must be a non-negative number.`;
        return false;
    }

    totalPayout += payout;
    participants.push({ participant: participantName, roles, payout });
});

totalPayout = Math.round(totalPayout * 100) / 100;
if (!hasError && totalPayout !== 100) {
    hasError = true;
    errorMsg = `Track ${trackIndex}: Total participant payout must equal 100% (currently: ${totalPayout}%).`;
    return false;
}

        tracks.push({
            id: trackId,
            title,
            artist,
            feature_artist,
            instrumental,
            language,
            parental,
            genre,
            stream_type,
            duration_ms,
            participants,
            track_lyrics,
            iswc
        });
    });

    if (hasError) {
        alert(errorMsg);
        return;
    }

    // AJAX save
    $('#tracksSaveStatus').html('<span class="spinner-border spinner-border-sm spinner-small" role="status"></span>');

    $.ajax({
        url: '{{ route("releases.save.tracks") }}',
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        data: {
            music_release_id: $('#music_release_id').val(),
            tracks: tracks
        },
        success() {
            $('#tracksSaveStatus').html('<span class="badge bg-success saved-badge">Saved</span>');
        },
        error() {
            $('#tracksSaveStatus').text('Error saving tracks');
        }
    });
});





  function parseDurationTextToMs(text){
    // text like "3:45" or "00:03:45"
    const parts = text.split(':').map(p=>parseInt(p,10));
    if (parts.length===2) {
      return ((parts[0]*60)+parts[1]) * 1000;
    } else if (parts.length===3) {
      return ((parts[0]*3600)+(parts[1]*60)+parts[2]) * 1000;
    }
    return 0;
  }

  $('#goto5').on('click', function() {
  const step5Tab = new bootstrap.Tab(document.querySelector('#step5-tab'));
  step5Tab.show();
  setProgress(5);
});

  // Outlets
  $('#addOutlet').on('click', function(){
    $('#outletsContainer').append(`
      <div class="outlet-row mb-2">
        <input class="form-control mb-1 outlet_name" placeholder="Outlet name">
        <input class="form-control mb-1 outlet_id" placeholder="Outlet ID (optional)">
      </div>
    `);
  });

  // Save outlets
$('#saveOutletsBtn').on('click', function() {
  const outlets = [];

  $('#outletsForm tbody tr').each(function() {
    const checkbox = $(this).find('.row-checkbox');
    if (checkbox.is(':checked')) {
      const outlet_id = checkbox.val();
      const outlet_release_date = $(this).find('.outlet-date').val();
      outlets.push({ outlet_id, outlet_release_date });
    }
  });

  if (!outlets.length) {
    alert('Please select at least one outlet before saving.');
    return;
  }

  $('#outletsSaveStatus').html('<span class="spinner-border spinner-border-sm spinner-small" role="status"></span>');

  $.ajax({
    url: '{{ route("releases.save.outlets") }}',
    method: 'POST',
    headers: { 'X-CSRF-TOKEN': csrf },
    data: {
      music_release_id: $('#music_release_id').val(),
      outlets: outlets
    },
    success() {
      $('#outletsSaveStatus').html('<span class="badge bg-success saved-badge">Saved</span>');
      // setProgress(6);

    },
    error(xhr) {
      console.error(xhr);
      $('#outletsSaveStatus').text('Error saving outlets');
    }
  });
});

// Toggle all outlet checkboxes
$('#checkAll').on('change', function() {
  const isChecked = $(this).is(':checked');
  $('.row-checkbox').prop('checked', isChecked);

  // Optional: enable/disable date inputs
  $('#outletsForm tbody tr').each(function() {
    const dateInput = $(this).find('input[name="outlet_release_date"]');
    dateInput.prop('disabled', !isChecked);
  });
});

// Enable/disable date when individual checkboxes are clicked
$(document).on('change', '.row-checkbox', function() {
  const dateInput = $(this).closest('tr').find('input[name="outlet_release_date"]');
  dateInput.prop('disabled', !$(this).is(':checked'));
});


$('#goto6').on('click', function() {
  const step6Tab = new bootstrap.Tab(document.querySelector('#step6-tab'));
  step6Tab.show();
  setProgress(6);
});




// Add step 6

$('#saveVerificationBtn').on('click', function () {

        let btn = $(this);
        let status = $('#verificationSaveStatus');

        let form = document.getElementById('acct_verification');
        let formData = new FormData(form);
        formData.append('music_release_id', releaseId);

        btn.prop('disabled', true);
        status.html(`
            <span class="spinner-border spinner-border-sm me-2"></span>
            Saving...
        `);

        $.ajax({
            url: "{{ route('verification.store') }}",
            method: "POST",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: formData,
            processData: false,
            contentType: false,
            success: function (resp) {

                status.html(`
                    <span class="badge bg-success">
                        <i class="bi bi-check-circle"></i> Saved
                    </span>
                `);

                btn.prop('disabled', false);
                

            },
            error: function (xhr) {

                btn.prop('disabled', false);

                let msg = 'Something went wrong';

                if (xhr.responseJSON?.message) {
                    msg = xhr.responseJSON.message;
                }

                status.html(`
                    <span class="badge bg-danger">
                        ${msg}
                    </span>
                `);
            }
        });
    });



// End step 6

  
  // Final submit logic
  
  $('#submitReleaseBtn').on('click', function() {
  const releaseId = $('#music_release_id').val();
  if (!releaseId) {
    alert('Please complete and save Step 1 first.');
    return;
  }

  $('#submitStatus').html('<span class="spinner-border spinner-border-sm" role="status"></span>');

  $.ajax({
    url: '{{ route("releases.submit.final") }}',
    method: 'POST',
    headers: { 'X-CSRF-TOKEN': csrf },
    data: { music_release_id: releaseId },
    success(resp) {
      $('#submitStatus').html('<span class="badge bg-success">Submitted</span>');
      alert(resp.message || 'Your release has been successfully submitted!');
     
       let redirectUrl = "{{ route('music_product') }}"; 
       window.location.href = redirectUrl;
    },
    error(xhr) {
      $('#submitStatus').html('');

      if (xhr.status === 422 && xhr.responseJSON?.missing_fields) {
        const missing = xhr.responseJSON.missing_fields.join('\n- ');
        alert('Cannot submit. Please complete the following:\n\n- ' + missing);
      } else {
        alert('Submission failed,Reason missing fields');
        console.error(xhr);
      }
    }
  });
});


  // Utility: escape HTML
  function escapeHtml(text) {
    if (!text) return '';
    return String(text).replace(/[&<>"'`=\/]/g, function (s) {
      return ({ '&': '&amp;','<': '&lt;','>': '&gt;','"':'&quot;',"'":'&#39;','/':'&#x2F;','`':'&#x60;','=':'&#x3D;' })[s];
    });
  }

  // === Load existing draft when user opens the form ===
loadDraft();

function loadDraft() {
  $.ajax({
    url: '{{ route("releases.load.draft") }}',
    method: 'GET',
    success(resp) {
      //console.log('Response:', resp);
      if (resp.status !== 'ok') return;
      const r = resp.release;

      // Make sure releaseId is available globally
      releaseId = r.id;
      $('#music_release_id').val(releaseId);

      // === Step 1 fields ===
      $('#title').val(r.title);
      $('#plan').val(r.plan);
      $('#release_type').val(r.release_type);
      $('#stereo_type').val(r.stereo_type);
      $('#stereo_code').val(r.stereo_code);
      $('#label_name').val(r.label_name);
      $('#release_date').val(r.release_date);

      // --- Auto-apply +7 days to outlet dates on draft load ---
      if (r.release_date) {
        const baseDate = new Date(r.release_date);
        if (!isNaN(baseDate)) {
          baseDate.setDate(baseDate.getDate() + 7);

          const year = baseDate.getFullYear();
          const month = String(baseDate.getMonth() + 1).padStart(2, '0');
          const day = String(baseDate.getDate()).padStart(2, '0');
          const minDate = `${year}-${month}-${day}`;

          // Apply to all outlet date inputs
          $('#outletsForm input[name="outlet_release_date"]').each(function() {
            // Only set if empty or null, to avoid overwriting saved manual dates
            if (!$(this).val()) {
              $(this).val(minDate);
            }
            $(this).attr('min', minDate);
            $(this).removeAttr('max');
          });
        }
      }

      // === Artwork ===
      if (r.artworks && r.artworks.length > 0) {
        const art = r.artworks[0];
        $('#artworkPreview').html(
          `<img src="${art.url}" class="img-thumbnail" style="max-width:200px;">`
        );
        $('#artworkStatus').html('<span class="badge bg-success">Saved</span>');
      }

      // === Tracks ===
      if (r.tracks && r.tracks.length > 0) {
        uploadedFilesMeta = r.tracks.map(t => {
         // let audioUrl = '';

          // Handle flexible backend formats
          const audioUrl = t.audio_url || (t.audio_file ? t.audio_file.url : '');

          return {
            track_id: t.id,
            filename: t.filename || t.title || '',
            duration_ms: t.duration_ms || 0,
            isrc: t.isrc || '',
            audio_url: audioUrl,
            artist: t.artist || '',
            feature_artist: t.feature_artist || '',
            iswc: t.iswc || '',
            instrumental: t.instrumental || '',
            language: t.language || '',
            parental: t.parental || '',
            lyrics: t.lyrics || '',
            for: Array.isArray(t.for) ? t.for : (t.for ? JSON.parse(t.for) : []),
            genre: Array.isArray(t.genre) ? t.genre : (t.genre ? JSON.parse(t.genre) : [])
          };
        });

        // Build track forms and participants
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

        $('#tracksSaveStatus').html('<span class="badge bg-success">Loaded</span>');
      }
      

      // === Audio Upload Section ===
     

  if (uploadedFilesMeta.length > 0) {
  const clearAllBtn = $('<button type="button" class="btn btn-sm btn-danger mb-3" id="clearAllAudios">Clear All</button>');
  $('#audioList').append(clearAllBtn);

    uploadedFilesMeta.forEach((file, i) => {
    const isMissing = file.audio_file?.missing || false;
    const safeFilename = file.filename || file.title || '';
    const row = $(`
  <div class="mb-3 p-2 border rounded bg-light position-relative audio-item"
       data-filename="${encodeURIComponent(safeFilename)}"
       data-track-id="${file.track_id || ''}">
    <button type="button" class="btn-close position-absolute top-0 end-0 m-2 remove-audio" title="Remove"></button>
    <strong>${safeFilename}</strong>
    <span class="text-muted">(${formatTimeMs(file.duration_ms)})</span>
    <div class="small text-muted audio-status">${isMissing ? 'File missing on server' : ''}</div>
    ${
      isMissing
        ? '<div class="text-danger small mt-2">Audio file not found.</div>'
        : `<audio controls class="mt-2 w-100">
             <source src="${file.audio_url}" type="audio/mpeg">
             Your browser does not support the audio element.
           </audio>`
    }
  </div>
`);
    $('#audioList').append(row);
  });

  $('#audioUploadStatus').html('<span class="badge bg-success saved-badge">Saved</span>');
}


      // === Outlets ===
      if (r.outlets && r.outlets.length > 0) {
        $('#outletsSaveStatus').html('<span class="badge bg-success">Saved</span>');
        r.outlets.forEach(o => {
          $(`#check${o.outlet_id}`).prop('checked', true);
          $(`#check${o.outlet_id}`)
            .closest('tr')
            .find('input[name="outlet_release_date"]')
            .val(o.outlet_release_date || '');
        });
      }

      

        // === Verification ===
    if (r.verification && r.verification.exists) {
          restoreVerification(r.verification);
    }

      // end Verification //

      // === Progress ===
      setProgress(5);
      $('#progressLabel').append(' (Draft loaded)');
    },
    error(err) {
      //console.error('Error loading draft:', err);
      if (err.responseText) console.log('Server response:', err.responseText);
    }
  });
}

// load draft function end here 


}); // end ready
</script>

<script>
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



</script>

<script>
$(document).ready(function() {
  $('#formStep1 #release_date').on('change', function() {
    let selectedDate = new Date($(this).val());
    if (isNaN(selectedDate)) return;

    // Add 7 days
    selectedDate.setDate(selectedDate.getDate() + 7);

    // Format YYYY-MM-DD
    let year = selectedDate.getFullYear();
    let month = String(selectedDate.getMonth() + 1).padStart(2, '0');
    let day = String(selectedDate.getDate()).padStart(2, '0');
    let minDate = `${year}-${month}-${day}`;

    // Apply to all outlet release_date inputs
    $('#outletsForm input[name="outlet_release_date"]').each(function() {
      $(this).val(minDate);     // set the date
      $(this).attr('min', minDate); // block all dates before
      $(this).removeAttr('max');    // allow any date after
    });
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
// --- Drag & Drop for Audio Upload ---
const dropZone = $('#audioDropZone');
const fileInput = $('#audios');

// Highlight drop zone on drag
dropZone.on('dragover', function(e) {
  e.preventDefault();
  e.stopPropagation();
  dropZone.addClass('dragover');
});

dropZone.on('dragleave', function(e) {
  e.preventDefault();
  e.stopPropagation();
  dropZone.removeClass('dragover');
});

// Handle dropped files
dropZone.on('drop', function(e) {
  e.preventDefault();
  dropZone.removeClass('dragover');

  const files = e.originalEvent.dataTransfer.files;
  if (files.length) {
    fileInput[0].files = files; // Assign files to input
    fileInput.trigger('change'); // Trigger your existing handler
  }
});

// Function to calculate +7 days and apply to outlet dates
function applyOutletReleaseDates() {
  const mainDate = $('#release_date').val();
  if (!mainDate) return; // no date chosen yet

  const base = new Date(mainDate);
  if (isNaN(base)) return;

  // Add 7 days
  base.setDate(base.getDate() + 7);
  const plus7 = base.toISOString().split('T')[0]; // format YYYY-MM-DD

  // Apply to all outlet date inputs
  $('#outletsForm .outlet-date').each(function () {
    $(this).val(plus7);
    $(this).attr('min', plus7);
  });
}

//When user changes the main release date (Step 1)
$('#release_date').on('change', function () {
  applyOutletReleaseDates();
});

// When user navigates to Step 5 (Outlets)
$('button[data-bs-target="#step5"]').on('shown.bs.tab', function () {
  applyOutletReleaseDates();
});


</script>


<script>
function addSocialHandle() {
    const container = document.getElementById("socialHandles");

    const div = document.createElement("div");
    div.className = "input-group mb-2 mt-3";

    div.innerHTML = `
        <input 
            type="text" 
            name="social_media_handles[]" 
            class="form-control" 
            placeholder="e.g. Twitter: @username">

        <div class="input-group-append">
            <button 
                type="button" 
                class="btn btn-danger" 
                onclick="this.parentElement.parentElement.remove()">
                −
            </button>
        </div>
    `;

    container.appendChild(div);
}
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
    if (isRestoringDraft) return; // skip during draft restore

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