<?php 
namespace App\Helpers;

use App\Helpers\Curl;

class EmailHelper {
	public static function send($recipients, $message) {
		$key = env('SENDGRID_API_KEY');

		$sendgrid_email = ['personalizations' => []];
		foreach ($recipients as $index => $recipient) {
            $sendgrid_email['personalizations'][$index]['subject'] = $message['subject'];
            $sendgrid_email['personalizations'][$index][ 'to' ] = [$recipient];
        }

        $from_email = $message['from_email']; 
        $from_name = $message['from_name'];     
        $content = $message['content'];

        $sendgrid_email[ 'content' ][] = [ 'type' => 'text/html', 'value' => $content ];
        $sendgrid_email[ 'from' ] = [ 'email' => $from_email, 'name' => $from_name ];

        $url = "https://api.sendgrid.com/v3/mail/send";
        $params = $sendgrid_email;
        $header = ['Authorization: Bearer ' . $key , 'Content-Type: application/json'];

        $resp = Curl::post($url , $params , $header, [], true);
        return $resp;
	}

	public static function sendResetPasswordEmail($user) {
		$reset_link = env('APP_URL').'/auth/reset/'.$user->reset_token;
		$message['from_email'] = env('FROM_EMAIL');
		$message['from_name'] = env('FROM_NAME');
		$message['subject'] = 'Forgot Password';
		$message['content'] = view('forgot-password', ['email' => $user->email, 'reset_link' => $reset_link])->render();
		EmailHelper::send([['email' => $user->email]], $message);
	}
}