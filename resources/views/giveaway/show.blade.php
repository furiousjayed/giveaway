<x-layout.public-layout :title="$giveaway->title" :meta-description="\Illuminate\Support\Str::limit(strip_tags($giveaway->description), 150)">
  <section class="giveaway-show-section">
    <div class="container">

      <div class="row justify-content-center">
        <div class="col-xl-11">

          <div class="giveaway-show-header">
            <a href="#" class="giveaway-back-link">
              <i class="bi bi-arrow-left"></i>
              Back to giveaways
            </a>

            <div class="giveaway-status-row">
              <span class="giveaway-status-badge giveaway-status-{{ $giveaway->status }}">
                {{ $giveaway->status }}
              </span>

              @if ($giveaway->ends_at)
                <span class="giveaway-date-text">
                  Ends {{ $giveaway->ends_at->format('M d, Y h:i A') }}
                </span>
              @endif
            </div>
          </div>

          <div class="row gy-4">
            <div class="col-lg-7">

              <div class="giveaway-info-card">
                <div class="giveaway-prize-icon">
                  <i class="bi bi-gift"></i>
                </div>

                <h1 class="giveaway-title">
                  {{ $giveaway->title }}
                </h1>

                @if ($giveaway->description)
                  <p class="giveaway-description">
                    {!! $giveaway->description !!}
                  </p>
                @endif

                <div class="giveaway-stats-grid">

                  <div class="giveaway-stat-item">
                    <span class="giveaway-stat-label">
                      Prize Amount
                    </span>

                    <span class="giveaway-stat-value">
                      @if ($giveaway->prize_amount)
                        {{ number_format($giveaway->prize_amount, 2) }}
                      @else
                        Not specified
                      @endif
                    </span>
                  </div>

                  <div class="giveaway-stat-item">
                    <span class="giveaway-stat-label">
                      Prize
                    </span>

                    <span class="giveaway-stat-value">
                      {{ $giveaway->prize_description ?? 'Prize details coming soon' }}
                    </span>
                  </div>

                  <div class="giveaway-stat-item">
                    <span class="giveaway-stat-label">
                      Participants
                    </span>

                    <span class="giveaway-stat-value">
                      {{ $giveaway->participants_count }} / {{ $giveaway->max_participants }}
                    </span>
                  </div>

                  <div class="giveaway-stat-item">
                    <span class="giveaway-stat-label">
                      Winners
                    </span>

                    <span class="giveaway-stat-value">
                      {{ $giveaway->winner_count }}
                    </span>
                  </div>

                </div>

                <div class="giveaway-progress-box">
                  @php
                    $percentage = $giveaway->max_participants > 0 ? min(100, round(($giveaway->participants_count / $giveaway->max_participants) * 100)) : 0;
                  @endphp

                  <div class="giveaway-progress-top">
                    <span>Entry progress</span>
                    <span>{{ $percentage }}%</span>
                  </div>

                  <div class="progress giveaway-progress">
                    <div class="progress-bar" role="progressbar" style="width: {{ $percentage }}%" aria-valuenow="{{ $percentage }}" aria-valuemin="0" aria-valuemax="100">
                    </div>
                  </div>
                </div>

                <div class="giveaway-time-grid">

                  <div>
                    <span class="giveaway-time-label">
                      Starts At
                    </span>

                    <strong>
                      {{ $giveaway->starts_at ? $giveaway->starts_at->format('M d, Y h:i A') : 'Immediately' }}
                    </strong>
                  </div>

                  <div>
                    <span class="giveaway-time-label">
                      Ends At
                    </span>

                    <strong>
                      {{ $giveaway->ends_at ? $giveaway->ends_at->format('M d, Y h:i A') : 'No end date' }}
                    </strong>
                  </div>

                </div>
              </div>

            </div>

            <div class="col-lg-5">

              <div class="giveaway-entry-card">
                <div class="giveaway-entry-header">
                  <h2>
                    Join this giveaway
                  </h2>

                  <p>
                    Enter your name and email. Each email and name can join only once.
                  </p>
                </div>

                @if ($giveaway->canJoin())
                  <form action="#" method="POST" class="giveaway-entry-form">
                    @csrf

                    <div class="mb-3">
                      <label for="name" class="form-label">
                        Full Name
                      </label>

                      <input type="text" name="name" id="name" value="{{ old('name', auth()->user()?->name) }}" class="form-control @error('name') is-invalid @enderror" placeholder="Enter your full name" required>

                      @error('name')
                        <div class="invalid-feedback">
                          {{ $message }}
                        </div>
                      @enderror
                    </div>

                    <div class="mb-4">
                      <label for="email" class="form-label">
                        Email Address
                      </label>

                      <input type="email" name="email" id="email" value="{{ old('email', auth()->user()?->email) }}" class="form-control @error('email') is-invalid @enderror" placeholder="Enter your email address" required>

                      @error('email')
                        <div class="invalid-feedback">
                          {{ $message }}
                        </div>
                      @enderror
                    </div>

                    <button type="submit" class="btn btn-app-primary w-100">
                      Submit Entry
                    </button>
                  </form>
                @else
                  <div class="giveaway-unavailable-box">
                    <i class="bi bi-info-circle"></i>
                    @if ($giveaway->isFull())
                      <h5>This giveaway is full</h5>
                      <p>The maximum participant limit has already been reached.</p>
                    @elseif($giveaway->hasEnded())
                      <h5>This giveaway has ended</h5>
                      <p>Entry submission is no longer available.</p>
                    @elseif(!$giveaway->hasStarted())
                      <h5>This giveaway has not started yet</h5>
                      <p>Please come back after the giveaway starts.</p>
                    @else
                      <h5>This giveaway is not active</h5>
                      <p>Entry submission is currently disabled.</p>
                    @endif
                  </div>
                @endif

              </div>

            </div>
          </div>

        </div>
      </div>

    </div>
  </section>
</x-layout.public-layout>
