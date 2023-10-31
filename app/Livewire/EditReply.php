<?php

namespace App\Livewire;

use App\Http\Requests\UpdateReplyRequest;
use App\Jobs\UpdateReply;
use App\Policies\ReplyPolicy;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class EditReply extends Component
{
    use AuthorizesRequests;
    use DispatchesJobs;

    public $reply;

    public $body;

    public function render()
    {
        return view('livewire.edit-reply');
    }

    protected $listeners = ['submitted' => 'updateReply'];

    public function updateReply($body)
    {
        $this->body = $body;
        $this->authorize(ReplyPolicy::UPDATE, $this->reply);

        $this->validate((new UpdateReplyRequest())->rules());

        $this->dispatchSync(new UpdateReply($this->reply, Auth::user(), $this->body));

        $this->dispatch('replyEdited');
    }
}
