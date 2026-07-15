<?php

namespace App\Mail;

use App\Models\DeviceActivity;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class IncidentAlertMail extends Mailable
{
    use Queueable, SerializesModels;

    public DeviceActivity $activity;
    public User           $recipient;
    public string         $eventLabel;
    public array          $sensorData;

    /**
     * @param  DeviceActivity  $activity   The triggering event
     * @param  User            $recipient  Who will receive this email
     */
    public function __construct(DeviceActivity $activity, User $recipient)
    {
        $this->activity  = $activity;
        $this->recipient = $recipient;

        // Human-readable event label
        $this->eventLabel = match (strtolower($activity->event_type ?? '')) {
            'cry_detected' => 'Baby Cry Detected',
            'dht'          => 'Temperature / Humidity Alert',
            'cradle'       => 'Cradle Motion Event',
            default        => ucwords(str_replace('_', ' ', $activity->event_type ?? 'Unknown Event')),
        };

        // Decode JSON payload once
        $raw = $activity->payload ?? '';
        $decoded = null;
        if (is_string($raw) && str_starts_with(trim($raw), '{')) {
            $decoded = json_decode($raw, true);
        }
        $this->sensorData = is_array($decoded) ? $decoded : [];
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '🚨 IoT Baby Cradle Alert — ' . $this->eventLabel,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.incident_alert',
        );
    }
}
