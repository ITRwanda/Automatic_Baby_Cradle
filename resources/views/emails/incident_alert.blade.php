<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>IoT Baby Cradle Alert</title>
<!--
    Inline styles only — maximum email client compatibility.
    Tested against Gmail, Outlook, Apple Mail.
-->
</head>
<body style="margin:0;padding:0;background:#f1f5f9;font-family:'Segoe UI',Arial,sans-serif;">

{{-- ── Outer wrapper ────────────────────────────────────────── --}}
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0"
       style="background:#f1f5f9;padding:32px 0;">
  <tr>
    <td align="center">

      {{-- ── Email card (max 600 px) ─────────────────────────── --}}
      <table role="presentation" width="600" cellspacing="0" cellpadding="0" border="0"
             style="max-width:600px;width:100%;background:#ffffff;border-radius:16px;
                    overflow:hidden;box-shadow:0 4px 24px rgba(0,0,0,.10);">

        {{-- ── Header banner ──────────────────────────────────── --}}
        @php
            $evt = strtolower($activity->event_type ?? '');
            $isCry    = $evt === 'cry_detected';
            $isDht    = $evt === 'dht';
            $isCradle = $evt === 'cradle';

            $bgColor   = $isCry ? '#be123c' : ($isDht ? '#0369a1' : '#0f766e');
            $lightBg   = $isCry ? '#fff1f2' : ($isDht ? '#f0f9ff' : '#f0fdfa');
            $accentBg  = $isCry ? '#fecdd3' : ($isDht ? '#bae6fd' : '#99f6e4');
            $accentTxt = $isCry ? '#9f1239' : ($isDht ? '#075985' : '#0f766e');

            $icon  = $isCry ? '🔔' : ($isDht ? '🌡️' : '🛏️');
            $device = $activity->device;
            $family = $device?->family;
            $caregiver = $device?->user;

            $temp   = $sensorData['temperature'] ?? null;
            $hum    = $sensorData['humidity']    ?? null;
            $sound  = $sensorData['sound_level'] ?? null;
            $tAlert = !empty($sensorData['temp_alert']);
            $hAlert = !empty($sensorData['humid_alert']);
        @endphp

        <tr>
          <td style="background:{{ $bgColor }};padding:28px 32px 20px;">
            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0">
              <tr>
                <td>
                  {{-- Brand --}}
                  <div style="font-size:12px;font-weight:700;text-transform:uppercase;
                               letter-spacing:.1em;color:rgba(255,255,255,.7);margin-bottom:8px;">
                    IoT Baby Monitor
                  </div>
                  {{-- Title --}}
                  <div style="font-size:24px;font-weight:800;color:#ffffff;line-height:1.2;">
                    {{ $icon }} {{ $eventLabel }}
                  </div>
                  <div style="font-size:13px;color:rgba(255,255,255,.8);margin-top:6px;">
                    Incident detected on <strong style="color:#fff;">{{ $device?->device_name ?? 'Unknown Device' }}</strong>
                    &nbsp;·&nbsp; {{ now()->format('d M Y, H:i:s') }}
                  </div>
                </td>
              </tr>
            </table>
          </td>
        </tr>

        {{-- ── Greeting ─────────────────────────────────────── --}}
        <tr>
          <td style="padding:24px 32px 0;">
            <p style="margin:0;font-size:15px;color:#0f172a;">
              Hello <strong>{{ $recipient->name ?? $recipient->email }}</strong>,
            </p>
            <p style="margin:10px 0 0;font-size:14px;color:#475569;line-height:1.6;">
              An automated alert has been triggered by one of your assigned baby cradle devices.
              Please review the details below and take any necessary action.
            </p>
          </td>
        </tr>

        {{-- ── Alert summary box ───────────────────────────── --}}
        <tr>
          <td style="padding:20px 32px 0;">
            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0"
                   style="background:{{ $lightBg }};border:1.5px solid {{ $accentBg }};
                          border-radius:12px;overflow:hidden;">
              <tr>
                <td style="padding:16px 20px;border-bottom:1px solid {{ $accentBg }};">
                  <div style="font-size:11px;font-weight:700;text-transform:uppercase;
                               letter-spacing:.08em;color:{{ $accentTxt }};margin-bottom:2px;">
                    Event Type
                  </div>
                  <div style="font-size:16px;font-weight:800;color:{{ $bgColor }};">
                    {{ $icon }} {{ $eventLabel }}
                  </div>
                </td>
              </tr>
              <tr>
                <td style="padding:16px 20px;">
                  <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0">
                    <tr>
                      <td style="width:50%;padding-bottom:12px;vertical-align:top;">
                        <div style="font-size:11px;color:#64748b;font-weight:600;text-transform:uppercase;letter-spacing:.05em;">Device</div>
                        <div style="font-size:14px;font-weight:700;color:#0f172a;margin-top:2px;">
                          {{ $device?->device_name ?? '—' }}
                        </div>
                        <div style="font-size:11px;color:#94a3b8;font-family:monospace;">
                          {{ $device?->device_token ?? '' }}
                        </div>
                      </td>
                      <td style="width:50%;padding-bottom:12px;vertical-align:top;">
                        <div style="font-size:11px;color:#64748b;font-weight:600;text-transform:uppercase;letter-spacing:.05em;">Family</div>
                        <div style="font-size:14px;font-weight:700;color:#0f172a;margin-top:2px;">
                          {{ $family?->family_name ?? 'Unassigned' }}
                        </div>
                      </td>
                    </tr>
                    <tr>
                      <td style="width:50%;vertical-align:top;">
                        <div style="font-size:11px;color:#64748b;font-weight:600;text-transform:uppercase;letter-spacing:.05em;">Assigned Caregiver</div>
                        <div style="font-size:14px;font-weight:700;color:#0f172a;margin-top:2px;">
                          {{ $caregiver?->name ?? 'Not assigned' }}
                        </div>
                        <div style="font-size:11px;color:#94a3b8;">
                          {{ $caregiver?->email ?? '' }}
                        </div>
                      </td>
                      <td style="width:50%;vertical-align:top;">
                        <div style="font-size:11px;color:#64748b;font-weight:600;text-transform:uppercase;letter-spacing:.05em;">Time</div>
                        <div style="font-size:14px;font-weight:700;color:#0f172a;margin-top:2px;">
                          {{ $activity->created_at?->format('H:i:s') }}
                        </div>
                        <div style="font-size:11px;color:#94a3b8;">
                          {{ $activity->created_at?->format('d M Y') }}
                        </div>
                      </td>
                    </tr>
                  </table>
                </td>
              </tr>
            </table>
          </td>
        </tr>

        {{-- ── Sensor readings (only if payload has data) ──── --}}
        @if($temp !== null || $hum !== null || $sound !== null)
        <tr>
          <td style="padding:20px 32px 0;">
            <div style="font-size:12px;font-weight:700;text-transform:uppercase;
                         letter-spacing:.08em;color:#94a3b8;margin-bottom:10px;">
              Sensor Readings
            </div>
            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0">
              <tr>

                @if($temp !== null)
                <td style="width:33%;padding-right:8px;">
                  <div style="background:{{ $tAlert ? '#fff1f2' : '#f0fdf4' }};
                               border:1.5px solid {{ $tAlert ? '#fecdd3' : '#bbf7d0' }};
                               border-radius:10px;padding:12px 14px;text-align:center;">
                    <div style="font-size:22px;margin-bottom:4px;">🌡️</div>
                    <div style="font-size:20px;font-weight:800;
                                color:{{ $tAlert ? '#be123c' : '#15803d' }};">
                      {{ $temp }}°C
                    </div>
                    <div style="font-size:11px;color:#64748b;margin-top:2px;">Temperature</div>
                    @if($tAlert)
                    <div style="font-size:10px;font-weight:700;color:#be123c;margin-top:4px;">⚠ Out of range</div>
                    @endif
                  </div>
                </td>
                @endif

                @if($hum !== null)
                <td style="width:33%;padding-right:{{ $sound !== null ? '8px' : '0' }};">
                  <div style="background:{{ $hAlert ? '#fff7ed' : '#f0fdf4' }};
                               border:1.5px solid {{ $hAlert ? '#fed7aa' : '#bbf7d0' }};
                               border-radius:10px;padding:12px 14px;text-align:center;">
                    <div style="font-size:22px;margin-bottom:4px;">💧</div>
                    <div style="font-size:20px;font-weight:800;
                                color:{{ $hAlert ? '#c2410c' : '#15803d' }};">
                      {{ $hum }}%
                    </div>
                    <div style="font-size:11px;color:#64748b;margin-top:2px;">Humidity</div>
                    @if($hAlert)
                    <div style="font-size:10px;font-weight:700;color:#c2410c;margin-top:4px;">⚠ Out of range</div>
                    @endif
                  </div>
                </td>
                @endif

                @if($sound !== null)
                <td style="width:33%;">
                  <div style="background:#fff1f2;border:1.5px solid #fecdd3;
                               border-radius:10px;padding:12px 14px;text-align:center;">
                    <div style="font-size:22px;margin-bottom:4px;">🔊</div>
                    <div style="font-size:20px;font-weight:800;color:#be123c;">
                      {{ $sound }}
                    </div>
                    <div style="font-size:11px;color:#64748b;margin-top:2px;">Sound Level</div>
                    <div style="font-size:10px;font-weight:700;color:#be123c;margin-top:4px;">⚠ Crying</div>
                  </div>
                </td>
                @endif

              </tr>
            </table>
          </td>
        </tr>
        @endif

        {{-- ── Action steps ─────────────────────────────────── --}}
        <tr>
          <td style="padding:20px 32px 0;">
            <div style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:12px;padding:16px 20px;">
              <div style="font-size:12px;font-weight:700;text-transform:uppercase;
                           letter-spacing:.08em;color:#94a3b8;margin-bottom:10px;">
                Recommended Actions
              </div>
              @if($isCry)
              <ul style="margin:0;padding-left:18px;color:#475569;font-size:13px;line-height:2;">
                <li>Check on the baby immediately.</li>
                <li>Contact the assigned caregiver if you cannot attend.</li>
                <li>Review the incident report in the dashboard.</li>
              </ul>
              @elseif($isDht)
              <ul style="margin:0;padding-left:18px;color:#475569;font-size:13px;line-height:2;">
                <li>Check the room temperature and humidity.</li>
                @if($tAlert)<li>Temperature is outside the safe range (16°C – 35°C).</li>@endif
                @if($hAlert)<li>Humidity is outside the safe range (30% – 80%).</li>@endif
                <li>Adjust the room environment if needed.</li>
              </ul>
              @else
              <ul style="margin:0;padding-left:18px;color:#475569;font-size:13px;line-height:2;">
                <li>Review the incident in the dashboard.</li>
                <li>Ensure the cradle and baby are safe.</li>
              </ul>
              @endif
            </div>
          </td>
        </tr>

        {{-- ── CTA button ───────────────────────────────────── --}}
        <tr>
          <td style="padding:24px 32px;">
            <table role="presentation" cellspacing="0" cellpadding="0" border="0">
              <tr>
                <td style="border-radius:10px;background:{{ $bgColor }};">
                  <a href="{{ config('app.url') }}/{{ auth()->check() && auth()->user()->role?->name === 'caregiver' ? 'caregiver' : 'family' }}/reports"
                     style="display:inline-block;padding:12px 28px;font-size:14px;font-weight:700;
                            color:#ffffff;text-decoration:none;border-radius:10px;">
                    View Full Report →
                  </a>
                </td>
              </tr>
            </table>
          </td>
        </tr>

        {{-- ── Footer ──────────────────────────────────────── --}}
        <tr>
          <td style="background:#f8fafc;border-top:1px solid #e2e8f0;
                     padding:18px 32px;text-align:center;">
            <p style="margin:0;font-size:11px;color:#94a3b8;line-height:1.6;">
              This is an automated alert from <strong style="color:#475569;">IoT Baby Monitor</strong>.<br>
              You are receiving this because you are assigned to device
              <strong style="color:#475569;">{{ $device?->device_name ?? 'Unknown' }}</strong>.<br>
              Please do not reply to this email.
            </p>
          </td>
        </tr>

      </table>
      {{-- /card --}}

    </td>
  </tr>
</table>

</body>
</html>
