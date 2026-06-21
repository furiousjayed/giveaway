<x-filament-panels::page>
  @php
    $record = $this->record;

    $homeScorers = collect(is_array($record->home_scorers) ? $record->home_scorers : []);
    $awayScorers = collect(is_array($record->away_scorers) ? $record->away_scorers : []);

    $localStartAt = $record->start_at?->copy()->timezone($record->timezone);
    $utcStartAt = $record->start_at?->copy()->timezone('UTC');
    $kickoffIso = $utcStartAt?->toIso8601String();

    $status = $record->is_finished ? 'Final' : ($record->start_at && $record->start_at->isPast() ? 'Live' : 'Scheduled');

    $stageLabel = match ($record->group_code) {
        'R32' => 'Round of 32',
        'R16' => 'Round of 16',
        'QF' => 'Quarter Final',
        'SF' => 'Semi Final',
        '3RD' => 'Third Place',
        'FINAL' => 'Final',
        default => $record->group_code ? "Group {$record->group_code}" : '-',
    };

    $matchType = $record->match_type ? str($record->match_type)->headline()->toString() : '-';

    $homeInitial = mb_substr($record->home_team_name ?? 'H', 0, 1);
    $awayInitial = mb_substr($record->away_team_name ?? 'A', 0, 1);
  @endphp

  <script>
    window.fixtureCountdown = function(config) {
      return {
        kickoffAt: config.kickoffAt,
        isFinished: config.isFinished,
        label: 'Calculating...',
        interval: null,

        start() {
          this.stop();
          this.tick();

          this.interval = setInterval(() => {
            this.tick();
          }, 1000);
        },

        stop() {
          if (this.interval) {
            clearInterval(this.interval);
            this.interval = null;
          }
        },

        destroy() {
          this.stop();
        },

        tick() {
          if (!this.kickoffAt) {
            this.label = '-';
            this.stop();
            return;
          }

          if (this.isFinished) {
            this.label = 'Final';
            this.stop();
            return;
          }

          const kickoffDate = new Date(this.kickoffAt);
          const diff = kickoffDate.getTime() - Date.now();

          if (diff <= 0) {
            this.label = 'Match started';
            return;
          }

          this.label = 'Starts in ' + this.format(diff);
        },

        format(milliseconds) {
          const totalSeconds = Math.max(0, Math.floor(milliseconds / 1000));

          const days = Math.floor(totalSeconds / 86400);
          const hours = Math.floor((totalSeconds % 86400) / 3600);
          const minutes = Math.floor((totalSeconds % 3600) / 60);
          const seconds = totalSeconds % 60;

          if (days > 0) {
            return `${days}d ${hours}h ${minutes}m ${seconds}s`;
          }

          if (hours > 0) {
            return `${hours}h ${minutes}m ${seconds}s`;
          }

          return `${minutes}m ${seconds}s`;
        },
      };
    };

    window.fixtureUserLocalTime = function(kickoffAt) {
      return {
        label: 'Detecting your local time...',
        timezone: 'Detecting timezone...',

        init() {
          if (!kickoffAt) {
            this.label = '-';
            this.timezone = '-';
            return;
          }

          this.timezone = Intl.DateTimeFormat().resolvedOptions().timeZone || 'Unknown timezone';

          this.label = new Intl.DateTimeFormat(undefined, {
            year: 'numeric',
            month: 'short',
            day: '2-digit',
            hour: 'numeric',
            minute: '2-digit',
            timeZoneName: 'short',
          }).format(new Date(kickoffAt));
        },
      };
    };
  </script>

  <style>
    .wc-page {
      max-width: 1180px;
      margin: 0 auto;
      color: #f8fafc;
    }

    .wc-card {
      background: #111827;
      border: 1px solid rgba(255, 255, 255, 0.08);
      border-radius: 18px;
      box-shadow: 0 18px 45px rgba(0, 0, 0, 0.28);
      overflow: hidden;
    }

    .wc-scoreboard {
      background:
        linear-gradient(135deg, rgba(15, 23, 42, 0.96), rgba(3, 7, 18, 0.98)),
        radial-gradient(circle at top left, rgba(16, 185, 129, 0.16), transparent 35%);
    }

    .wc-header {
      padding: 22px 28px;
      border-bottom: 1px solid rgba(255, 255, 255, 0.08);
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 20px;
    }

    .wc-kicker {
      font-size: 12px;
      letter-spacing: 0.18em;
      text-transform: uppercase;
      color: #94a3b8;
      font-weight: 800;
      margin-bottom: 8px;
    }

    .wc-meta {
      display: flex;
      flex-wrap: wrap;
      gap: 10px;
      color: #cbd5e1;
      font-size: 14px;
      font-weight: 600;
    }

    .wc-pill {
      display: inline-flex;
      align-items: center;
      border-radius: 999px;
      padding: 7px 12px;
      font-size: 12px;
      font-weight: 900;
      letter-spacing: 0.08em;
      text-transform: uppercase;
      white-space: nowrap;
    }

    .wc-pill-final {
      background: #e5e7eb;
      color: #111827;
    }

    .wc-pill-live {
      background: #dc2626;
      color: white;
    }

    .wc-pill-scheduled {
      background: #2563eb;
      color: white;
    }

    .wc-main-score {
      display: grid;
      grid-template-columns: 1fr 320px 1fr;
      align-items: stretch;
    }

    .wc-team {
      padding: 34px 30px;
      display: flex;
      align-items: center;
      gap: 18px;
    }

    .wc-team.home {
      justify-content: flex-end;
      text-align: right;
    }

    .wc-team.away {
      justify-content: flex-start;
      text-align: left;
    }

    .wc-logo {
      width: 74px;
      height: 74px;
      border-radius: 50%;
      background: #f8fafc;
      color: #020617;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 30px;
      font-weight: 950;
      flex-shrink: 0;
    }

    .wc-team-label {
      color: #94a3b8;
      text-transform: uppercase;
      letter-spacing: 0.15em;
      font-size: 12px;
      font-weight: 900;
      margin-bottom: 8px;
    }

    .wc-team-name {
      color: #ffffff;
      font-size: 32px;
      line-height: 1.05;
      font-weight: 950;
    }

    .wc-score-box {
      background: rgba(255, 255, 255, 0.04);
      border-left: 1px solid rgba(255, 255, 255, 0.08);
      border-right: 1px solid rgba(255, 255, 255, 0.08);
      padding: 32px 20px;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      text-align: center;
    }

    .wc-score {
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 22px;
    }

    .wc-score-number {
      font-size: 82px;
      line-height: 1;
      font-weight: 950;
      color: #ffffff;
      letter-spacing: -0.06em;
    }

    .wc-score-dash {
      font-size: 42px;
      color: #64748b;
      font-weight: 900;
    }

    .wc-time {
      margin-top: 14px;
      color: #94a3b8;
      font-size: 13px;
      font-weight: 800;
      letter-spacing: 0.08em;
      text-transform: uppercase;
    }

    .wc-grid {
      margin-top: 22px;
      display: grid;
      grid-template-columns: repeat(4, 1fr);
      gap: 16px;
    }

    .wc-stat {
      background: #111827;
      border: 1px solid rgba(255, 255, 255, 0.08);
      border-radius: 16px;
      padding: 18px;
    }

    .wc-stat-label {
      font-size: 11px;
      color: #94a3b8;
      text-transform: uppercase;
      letter-spacing: 0.14em;
      font-weight: 900;
      margin-bottom: 8px;
    }

    .wc-stat-value {
      font-size: 20px;
      color: #ffffff;
      font-weight: 900;
    }

    .wc-content {
      margin-top: 22px;
      display: grid;
      grid-template-columns: 2fr 1fr;
      gap: 22px;
    }

    .wc-section-header {
      padding: 18px 22px;
      border-bottom: 1px solid rgba(255, 255, 255, 0.08);
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .wc-section-title {
      font-size: 16px;
      font-weight: 950;
      color: white;
    }

    .wc-section-subtitle {
      font-size: 12px;
      color: #94a3b8;
      margin-top: 3px;
    }

    .wc-scorers {
      display: grid;
      grid-template-columns: 1fr 1fr;
    }

    .wc-scorer-team {
      padding: 22px;
    }

    .wc-scorer-team+.wc-scorer-team {
      border-left: 1px solid rgba(255, 255, 255, 0.08);
    }

    .wc-scorer-top {
      display: flex;
      justify-content: space-between;
      gap: 16px;
      align-items: center;
      margin-bottom: 16px;
    }

    .wc-small-team {
      font-size: 18px;
      color: white;
      font-weight: 950;
    }

    .wc-small-score {
      font-size: 36px;
      line-height: 1;
      color: white;
      font-weight: 950;
    }

    .wc-goal-list {
      display: grid;
      gap: 10px;
    }

    .wc-goal {
      background: rgba(255, 255, 255, 0.045);
      border: 1px solid rgba(255, 255, 255, 0.07);
      border-radius: 12px;
      padding: 12px 14px;
      color: #e5e7eb;
      font-size: 14px;
      font-weight: 700;
    }

    .wc-empty {
      color: #94a3b8;
      background: rgba(255, 255, 255, 0.035);
      border-radius: 12px;
      padding: 14px;
      font-size: 14px;
    }

    .wc-side {
      display: grid;
      gap: 22px;
    }

    .wc-info-body {
      padding: 20px 22px;
      display: grid;
      gap: 16px;
    }

    .wc-info-row {
      display: grid;
      grid-template-columns: 120px 1fr;
      gap: 14px;
      align-items: start;
    }

    .wc-info-label {
      color: #94a3b8;
      font-size: 12px;
      font-weight: 900;
      letter-spacing: 0.12em;
      text-transform: uppercase;
    }

    .wc-info-value {
      color: #ffffff;
      font-size: 14px;
      font-weight: 800;
      line-height: 1.45;
    }

    .wc-bottom {
      margin-top: 22px;
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 16px;
    }

    .wc-countdown {
      margin-top: 14px;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      border-radius: 999px;
      background: rgba(16, 185, 129, 0.12);
      border: 1px solid rgba(16, 185, 129, 0.22);
      color: #6ee7b7;
      padding: 8px 14px;
      font-size: 13px;
      font-weight: 900;
      letter-spacing: 0.04em;
    }

    .wc-time-card {
      background: rgba(255, 255, 255, 0.035);
      border: 1px solid rgba(255, 255, 255, 0.07);
      border-radius: 14px;
      padding: 16px;
    }

    .wc-user-time-card {
      background: rgba(37, 99, 235, 0.10);
      border-color: rgba(37, 99, 235, 0.22);
    }

    .wc-countdown-card {
      background: rgba(16, 185, 129, 0.10);
      border-color: rgba(16, 185, 129, 0.22);
    }

    .wc-muted {
      margin-top: 6px;
      color: #94a3b8;
      font-size: 12px;
      font-weight: 700;
    }

    @media (max-width: 1100px) {
      .wc-main-score {
        grid-template-columns: 1fr;
      }

      .wc-team.home,
      .wc-team.away {
        justify-content: center;
        text-align: center;
      }

      .wc-score-box {
        border-left: none;
        border-right: none;
        border-top: 1px solid rgba(255, 255, 255, 0.08);
        border-bottom: 1px solid rgba(255, 255, 255, 0.08);
      }

      .wc-grid {
        grid-template-columns: repeat(2, 1fr);
      }

      .wc-content {
        grid-template-columns: 1fr;
      }
    }

    @media (max-width: 720px) {
      .wc-header {
        flex-direction: column;
        align-items: flex-start;
      }

      .wc-grid,
      .wc-bottom,
      .wc-scorers {
        grid-template-columns: 1fr;
      }

      .wc-scorer-team+.wc-scorer-team {
        border-left: none;
        border-top: 1px solid rgba(255, 255, 255, 0.08);
      }

      .wc-score-number {
        font-size: 64px;
      }

      .wc-team-name {
        font-size: 26px;
      }
    }
  </style>

  <div class="wc-page">
    <section class="wc-card wc-scoreboard">
      <div class="wc-header">
        <div>
          <div class="wc-kicker">FIFA World Cup 2026</div>

          <div class="wc-meta">
            <span>{{ $stageLabel }}</span>
            <span>•</span>
            <span>Matchday {{ $record->matchday ?? '-' }}</span>
            <span>•</span>
            <span>{{ $record->stadium_name }}</span>
          </div>
        </div>

        <span class="wc-pill {{ $status === 'Final' ? 'wc-pill-final' : ($status === 'Live' ? 'wc-pill-live' : 'wc-pill-scheduled') }}">
          {{ $status }}
        </span>
      </div>

      <div class="wc-main-score">
        <div class="wc-team home">
          <div>
            <div class="wc-team-label">Home</div>
            <div class="wc-team-name">{{ $record->home_team_name ?? 'TBD' }}</div>
          </div>

          <div class="wc-logo">{{ $homeInitial }}</div>
        </div>

        <div class="wc-score-box">
          <div class="wc-score">
            <span class="wc-score-number">{{ $record->home_score }}</span>
            <span class="wc-score-dash">-</span>
            <span class="wc-score-number">{{ $record->away_score }}</span>
          </div>

          <div wire:key="score-user-local-time-{{ $record->id }}-{{ $record->updated_at?->timestamp }}" class="wc-time" x-data="fixtureUserLocalTime(@js($kickoffIso))" x-init="init()">
            <span x-text="label">
              Detecting your timezone...
            </span>
          </div>

          <div wire:key="score-countdown-{{ $record->id }}-{{ $record->updated_at?->timestamp }}-{{ (int) $record->is_finished }}" class="wc-countdown" x-data="fixtureCountdown({
              kickoffAt: @js($kickoffIso),
              isFinished: @js($record->is_finished),
          })" x-init="start()" x-text="label">
            Calculating countdown...
          </div>
        </div>

        <div class="wc-team away">
          <div class="wc-logo">{{ $awayInitial }}</div>

          <div>
            <div class="wc-team-label">Away</div>
            <div class="wc-team-name">{{ $record->away_team_name ?? 'TBD' }}</div>
          </div>
        </div>
      </div>
    </section>

    <section class="wc-grid">
      <div class="wc-stat">
        <div class="wc-stat-label">Stage</div>
        <div class="wc-stat-value">{{ $stageLabel }}</div>
      </div>

      <div class="wc-stat">
        <div class="wc-stat-label">Match Type</div>
        <div class="wc-stat-value">{{ $matchType }}</div>
      </div>

      <div class="wc-stat">
        <div class="wc-stat-label">Stream</div>
        <div class="wc-stat-value">{{ $record->is_stream_enabled ? 'Enabled' : 'Disabled' }}</div>
      </div>

      <div class="wc-stat">
        <div class="wc-stat-label">Finished</div>
        <div class="wc-stat-value">{{ $record->is_finished ? 'Yes' : 'No' }}</div>
      </div>
    </section>

    <section class="wc-content">
      <div class="wc-card">
        <div class="wc-section-header">
          <div>
            <div class="wc-section-title">Scoring Summary</div>
            <div class="wc-section-subtitle">Goalscorers and match score</div>
          </div>
        </div>

        <div class="wc-scorers">
          <div class="wc-scorer-team">
            <div class="wc-scorer-top">
              <div>
                <div class="wc-team-label">Home</div>
                <div class="wc-small-team">{{ $record->home_team_name ?? 'Home Team' }}</div>
              </div>

              <div class="wc-small-score">{{ $record->home_score }}</div>
            </div>

            @if ($homeScorers->isNotEmpty())
              <div class="wc-goal-list">
                @foreach ($homeScorers as $scorer)
                  <div class="wc-goal">⚽ {{ $scorer }}</div>
                @endforeach
              </div>
            @else
              <div class="wc-empty">No scorers recorded</div>
            @endif
          </div>

          <div class="wc-scorer-team">
            <div class="wc-scorer-top">
              <div>
                <div class="wc-team-label">Away</div>
                <div class="wc-small-team">{{ $record->away_team_name ?? 'Away Team' }}</div>
              </div>

              <div class="wc-small-score">{{ $record->away_score }}</div>
            </div>

            @if ($awayScorers->isNotEmpty())
              <div class="wc-goal-list">
                @foreach ($awayScorers as $scorer)
                  <div class="wc-goal">⚽ {{ $scorer }}</div>
                @endforeach
              </div>
            @else
              <div class="wc-empty">No scorers recorded</div>
            @endif
          </div>
        </div>
      </div>

      <div class="wc-side">
        <div class="wc-card">
          <div class="wc-section-header">
            <div>
              <div class="wc-section-title">Venue</div>
              <div class="wc-section-subtitle">Host stadium details</div>
            </div>
          </div>

          <div class="wc-info-body">
            <div class="wc-info-row">
              <div class="wc-info-label">Stadium</div>
              <div class="wc-info-value">{{ $record->stadium_name }}</div>
            </div>

            <div class="wc-info-row">
              <div class="wc-info-label">City</div>
              <div class="wc-info-value">{{ $record->city }}</div>
            </div>

            <div class="wc-info-row">
              <div class="wc-info-label">Country</div>
              <div class="wc-info-value">{{ $record->host_country }}</div>
            </div>

            <div class="wc-info-row">
              <div class="wc-info-label">Timezone</div>
              <div class="wc-info-value">{{ $record->timezone }}</div>
            </div>
          </div>
        </div>

        <div class="wc-card">
          <div class="wc-section-header">
            <div>
              <div class="wc-section-title">Kickoff</div>
              <div class="wc-section-subtitle">Host, UTC, and your local time</div>
            </div>
          </div>

          <div class="wc-info-body">
            <div class="wc-time-card">
              <div class="wc-info-label">Host Local</div>
              <div class="wc-info-value">
                {{ $localStartAt?->format('M d, Y') ?? '-' }}<br>
                {{ $localStartAt?->format('h:i A T') ?? '-' }}

                <div class="wc-muted">
                  {{ $record->timezone }}
                </div>
              </div>
            </div>

            <div class="wc-time-card">
              <div class="wc-info-label">UTC</div>
              <div class="wc-info-value">
                {{ $utcStartAt?->format('M d, Y') ?? '-' }}<br>
                {{ $utcStartAt?->format('h:i A T') ?? '-' }}
              </div>
            </div>

            <div wire:key="user-local-time-{{ $record->id }}-{{ $record->updated_at?->timestamp }}" class="wc-time-card wc-user-time-card" x-data="fixtureUserLocalTime(@js($kickoffIso))" x-init="init()">
              <div class="wc-info-label">Your Local</div>
              <div class="wc-info-value">
                <span x-text="label">Detecting your local time...</span>

                <div class="wc-muted" x-text="timezone">
                  Detecting timezone...
                </div>
              </div>
            </div>

            <div wire:key="kickoff-countdown-{{ $record->id }}-{{ $record->updated_at?->timestamp }}-{{ (int) $record->is_finished }}" class="wc-time-card wc-countdown-card" x-data="fixtureCountdown({
                kickoffAt: @js($kickoffIso),
                isFinished: @js($record->is_finished),
            })" x-init="start()">
              <div class="wc-info-label">Countdown</div>
              <div class="wc-info-value" x-text="label">
                Calculating...
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <section class="wc-bottom">
      <div class="wc-stat">
        <div class="wc-stat-label">Group Code</div>
        <div class="wc-stat-value">{{ $record->group_code ?? '-' }}</div>
      </div>
    </section>
  </div>
</x-filament-panels::page>
