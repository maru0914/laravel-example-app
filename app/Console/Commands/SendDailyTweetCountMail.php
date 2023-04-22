<?php

namespace App\Console\Commands;

use App\Mail\DailyTweetCount;
use App\Models\User;
use App\Services\TweetService;
use Illuminate\Console\Command;
use Illuminate\Contracts\Mail\Mailer;

class SendDailyTweetCountMail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mail:send-daily-tweet-count-mail';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '前日のつぶやき数を集計してつぶやきを促すメールを送ります。';

    /**
     * Execute the console command.
     */
    public function handle(TweetService $tweetService, Mailer $mailer)
    {
        $tweetCount = $tweetService->countYesterdayTweets();

        $users = User::get();

        foreach ($users as $user) {
            $mailer->to($user->email)
                ->send(new DailyTweetCount($user, $tweetCount));
        }
    }
}
