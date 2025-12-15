@extends('dashboard.index')
@section('title')
  Dashboard
@endsection
@section('content')

@include('sweetalert::alert')

 <style>

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
                                    <small id="progressLabel" class="text-muted">Step 1 of 5</small>
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
$(function () {
  const csrf = $('meta[name="csrf-token"]').attr('content');
  let releaseId = $('#music_release_id').val();
  let uploadedFilesMeta = [];

  // Load release data
  loadReleaseData();

  /** ========== LOAD EXISTING RELEASE ========== **/
  function loadReleaseData() {
    $.ajax({
      url: `/releases/${releaseId}/edit-data`, // backend route: releases.edit.data
      method: 'GET',
      success(resp) {
        if (resp.status !== 'ok') return alert('Failed to load release.');
        const r = resp.release;

        // === Basic info ===
        $('#title').val(r.title);
        $('#plan').val(r.plan);
        $('#release_type').val(r.release_type);
        $('#stereo_type').val(r.stereo_type);
        $('#stereo_code').val(r.stereo_code);
        $('#label_name').val(r.label_name);
        $('#release_date').val(r.release_date);

        // === Artwork ===
        if (r.artworks?.length) {
          const art = r.artworks[0];
          $('#artworkPreview').html(
            `<img src="${art.url}" class="img-thumbnail" style="max-width:200px;">`
          );
        }

        // === Audios + Tracks ===
        if (r.tracks?.length) {
          uploadedFilesMeta = r.tracks.map(t => ({
            track_id: t.id,
            filename: t.audio_file?.filename || t.title || '',
            titlee: t.title || '',
            artist: t.artist || '',
            feature_artist: t.feature_artist || '',
            iswc: t.iswc || '',
            instrumental: t.instrumental || '',
            language: t.language || '',
            parental: t.parental || '',
            genre: Array.isArray(t.genre) ? t.genre : JSON.parse(t.genre || '[]'),
            for: Array.isArray(t.for) ? t.for : JSON.parse(t.for || '[]'),
            lyrics: t.lyrics || '',
            isrc: t.isrc || '',
            duration_ms: t.duration_ms || 0,
            audio_url: t.audio_url || '',
            participants: t.participants || []
          }));
          buildTrackForms(uploadedFilesMeta);
        }

        // === Outlets ===
        if (r.outlets?.length) {
          r.outlets.forEach(o => {
            $(`#check${o.outlet_id}`).prop('checked', true);
            $(`#check${o.outlet_id}`)
              .closest('tr')
              .find('input[name="outlet_release_date"]')
              .val(o.outlet_release_date || '');
          });
        }
      },
      error(err) {
        console.error('Error loading release data', err);
        alert('Could not load release data.');
      }
    });
  }

  /** ========== UPDATE ARTWORK ========== **/
  $('#uploadArtworkBtn').on('click', function () {
    const file = $('#artwork')[0].files[0];
    if (!file) return alert('Select an image first.');

    const fd = new FormData();
    fd.append('artwork', file);
    fd.append('music_release_id', releaseId);

    $('#artworkStatus').html('<span class="spinner-border spinner-border-sm"></span>');

    $.ajax({
      url: `/releases/${releaseId}/update-artwork`,
      method: 'POST',
      data: fd,
      processData: false,
      contentType: false,
      headers: { 'X-CSRF-TOKEN': csrf },
      success(resp) {
        $('#artworkPreview').html(`<img src="${resp.artwork.url}" class="img-thumbnail" style="max-width:200px;">`);
        $('#artworkStatus').html('<span class="badge bg-success">Updated</span>');
      },
      error() {
        $('#artworkStatus').text('Error updating artwork.');
      }
    });
  });

  /** ========== AUDIO UPLOAD (WASABI DIRECT UPLOAD) ========== **/
  $('#uploadAudiosBtn').on('click', function () {
    if (!$('#audios')[0].files.length) return alert('Select files to upload.');

    const files = [...$('#audios')[0].files];
    const uploadPromises = files.map(uploadToWasabi);

    Promise.all(uploadPromises)
      .then(results => {
        // save metadata to backend
        $.ajax({
          url: `/releases/${releaseId}/update-audios`,
          method: 'POST',
          headers: { 'X-CSRF-TOKEN': csrf },
          data: { files: results },
          success(resp) {
            uploadedFilesMeta = resp.tracks;
            buildTrackForms(uploadedFilesMeta);
            $('#audioUploadStatus').html('<span class="badge bg-success">Updated</span>');
          },
          error(err) {
            console.error(err);
            alert('Error saving audio metadata.');
          }
        });
      })
      .catch(err => console.error('Upload error:', err));
  });

  async function uploadToWasabi(file) {
    // get signed URL from backend
    const res = await $.get(`/wasabi/signed-url?filename=${encodeURIComponent(file.name)}`);
    const { upload_url, public_url } = res;

    // upload directly to Wasabi
    await fetch(upload_url, { method: 'PUT', body: file });

    return {
      filename: file.name,
      size: file.size,
      duration_ms: await getAudioDuration(file),
      audio_url: public_url
    };
  }

  function getAudioDuration(file) {
    return new Promise(resolve => {
      const audio = document.createElement('audio');
      audio.src = URL.createObjectURL(file);
      audio.addEventListener('loadedmetadata', () => {
        resolve(Math.round(audio.duration * 1000));
      });
    });
  }

  /** ========== TRACK BUILDER ========== **/
  function buildTrackForms(files) {
    $('#tracksContainer').empty();
    files.forEach((f, index) => {
      const card = $(`
        <div class="card track-card mb-3" data-track-id="${f.track_id}">
          <div class="card-body">
            <h5>Track ${index + 1}: ${f.filename}</h5>
            <div class="row mb-3">
              <div class="col-md-4">
                <label>Title</label>
                <input class="form-control track-title" value="${f.titlee}">
              </div>
              <div class="col-md-4">
                <label>Artist</label>
                <input class="form-control track-artist" value="${f.artist}">
              </div>
              <div class="col-md-4">
                <label>Feature Artist</label>
                <input class="form-control track-feature_artist" value="${f.feature_artist}">
              </div>
            </div>

            <div class="mb-3">
              <label>Lyrics</label>
              <textarea class="form-control track-lyrics" rows="3">${f.lyrics || ''}</textarea>
            </div>

            <div class="mb-3">
              <audio controls class="w-100">
                <source src="${f.audio_url}" type="audio/mpeg">
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

      // Populate participants
      const list = card.find('.participants-list');
      (f.participants || []).forEach((p, idx) => {
        list.append(buildParticipantRowHtml(p, idx));
      });

      if (!f.participants?.length) list.append(buildParticipantRowHtml({}, 0));

      $('#tracksContainer').append(card);
    });
  }

  function buildParticipantRowHtml(data = {}, idx = 0) {
    const rolesOptions = `@foreach($musical_roles as $v)<option value="{{$v->name}}">{{$v->name}}</option>@endforeach`;
    const payoutOptions = `@foreach($subscription_limit as $v)<option value="{{$v->the_number}}">{{$v->the_number}}</option>@endforeach`;

    const row = $(`
      <div class="row g-2 participant-row mb-3 p-2 border rounded mt-2">
        <div class="col-md-3"><input class="form-control p-participant" value="${data.participant || ''}" placeholder="Name"></div>
        <div class="col-md-3">
          <select multiple class="form-control js-example-basic-multiple p-role">${rolesOptions}</select>
        </div>
        <div class="col-md-3">
          <select class="form-control js-example-basic-single p-payout">${payoutOptions}</select>
        </div>
        <div class="col-md-3 d-flex align-items-center"><button type="button" class="btn btn-danger remove-row">Remove</button></div>
      </div>
    `);
    row.find('.p-role').val(data.roles || []).trigger('change');
    row.find('.p-payout').val(data.payout || '').trigger('change');
    return row;
  }

  // Add/remove participants
  $('#tracksContainer').on('click', '.add-participant', function () {
    const list = $(this).closest('.participants-section').find('.participants-list');
    list.append(buildParticipantRowHtml({}, list.children().length));
  });
  $('#tracksContainer').on('click', '.remove-row', function () {
    $(this).closest('.participant-row').remove();
  });

  /** ========== SAVE TRACK CHANGES ========== **/
  $('#saveTracksBtn').on('click', function () {
    const tracks = [];
    $('#tracksContainer .track-card').each(function () {
      const $card = $(this);
      const id = $card.data('track-id');
      const title = $card.find('.track-title').val();
      const artist = $card.find('.track-artist').val();
      const feature_artist = $card.find('.track-feature_artist').val();
      const lyrics = $card.find('.track-lyrics').val();
      const participants = [];

      $card.find('.participant-row').each(function () {
        const p = $(this);
        participants.push({
          participant: p.find('.p-participant').val(),
          roles: p.find('.p-role').val() || [],
          payout: p.find('.p-payout').val()
        });
      });

      tracks.push({ id, title, artist, feature_artist, lyrics, participants });
    });

    $.ajax({
      url: `/releases/${releaseId}/update-tracks`,
      method: 'POST',
      headers: { 'X-CSRF-TOKEN': csrf },
      data: { tracks },
      success() {
        $('#tracksSaveStatus').html('<span class="badge bg-success">Saved</span>');
      },
      error() {
        alert('Error updating tracks');
      }
    });
  });
});
</script>





@endsection    



