<?php

namespace App\Models;

use App\Http\Controllers\PlanController;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shift extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'type',
        'title',
        'group',
        'description',
        'start',
        'end',
        'team_size',
        'contact_name',
        'contact_email',
        'contact_phone',
    ];

    /**
     * Export shifts
     */
    public function export() {
      return [
        'shift','', $this->type, $this->title, $this->description,
        $this->start, $this->end, $this->team_size
      ];
    }

    /**
     * @inheritDoc
     * @param array $options
     * @return bool
     */
    public function save(array $options = [])
    {
        // Empty types
        if(empty($this->type)) {
            $this->type = '';
        }
        return parent::save($options);
    }

    /**
     * Shift belongs to a plan
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function plan() {
        return $this->belongsTo(Plan::class);
    }

    /**
     * Shift can have many subscriptions
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function subscriptions() {
        return $this->hasMany(Subscription::class);
    }

    /**
     * Color for amount of filled spaces
     * 100% filled => green
     * 80% - 99% filled => yellow
     * below 80% filled => red
     * 
     * @return string
     */
    public function getSubscriptionsPercentageColor(): string {
        $currentAmount = $this->subscriptions->count();
        $requiredAmount = $this->team_size;
        $percentage = $currentAmount / $requiredAmount;
        if ($percentage >= 1) {
            return 'green';
        } elseif ($percentage >= 0.8) {
            return 'yellow';
        } else {
            return 'red';
        }
    }

    public function getTimespan(): string
    {
        $start = \Illuminate\Support\Facades\Date::parse($this->start);
        $end = \Illuminate\Support\Facades\Date::parse($this->end);

        // Same day
        if ($start->isSameDay($end)) {
            return $start->isoFormat("dd. DD.MM.YYYY HH:mm") . ' - ' . $end->isoFormat("HH:mm");
        }
        // Different day
        else {
            return $start->isoFormat("dd.. DD.MM.YYYY HH:mm") . ' - ' . $end->isoFormat("dd. DD.MM.YYYY HH:mm");
        }
    }

    public function hasContactInfo(): bool
    {
        return $this->contact_name || $this->contact_email || $this->contact_phone;
    }

    public function getContactInfo(): string
    {
        $out = "";

        if ($this->contact_name) {
            $out .= $this->contact_name;
        }
        if ($this->contact_email && $this->contact_phone) {
            $out .= $this->contact_name ? " (" : "";
            $out .= $this->contact_email . ", ";
            $out .= $this->contact_phone;
            $out .= $this->contact_name ? ")" : "";
        }
        elseif ($this->contact_email || $this->contact_phone) {
            $out .= $this->contact_name ? " (" : "";
            $out .= $this->contact_email ?? "";
            $out .= $this->contact_phone ?? "";
            $out .= $this->contact_name ? ")" : "";
        }

        return $out;
    }

    public function getSubscriptionsEmailString(): string
    {
        $emails = [];

        foreach ($this->subscriptions as $subscription) {
            
            // display-name: "John Doe (Joe)"
            $email = '"'
                . $subscription->name
                . ($subscription->nickname ? ' (' . $subscription->nickname . ')' : '')
                . '"'
            ;

            $email .= ' ';

            // angle-addr: <john@doe.com>
            $email .= '<' . $subscription->email . '>';

            $emails[] = $email;
        }

        return implode(', ', $emails);
    }

    public function buildShiftInfoForEmail():string
    {
        $out = $this->title;
        $out .= '<br/>';
        $out .= PlanController::buildDateString($this->start, $this->end, true);

        return $out;
    }
}
