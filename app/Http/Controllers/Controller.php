<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Exception;
use PHPMailer\PHPMailer\PHPMailer;
use DB;
use Illuminate\Support\HtmlString;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
    public function generateOTP($len)
    {
        try {
            $generator = "135792468";
            $result = "";
            for ($i = 1; $i <= $len; $i++) {
                $result .= substr($generator, (rand() % (strlen($generator))), 1);
            }
            return $result;
        } catch (Exception $e) {
            $response = ['status' => 'failed', 'message' => 'Throw in Catch Section', 'error' => ['message' => $e->getMessage(), 'code' => $e->getCode(), 'string' => $e->__toString()]];
            return response()->json($response);
        }
    }


    // ========== [ Compose Email ] ================
    public function composeEmail($user_ip, $email, $subject, $message, $frmID = '')
    {
        require base_path("vendor/autoload.php");
        $mail = new PHPMailer(true);     // Passing `true` enables exceptions

        try {
            $i = 0;
            $datetime = date("Y-m-d H:i:s");
            $htmlString = new HtmlString($message);
            $insertlog =  DB::table('emaillog')->insert([
                'details' =>  $htmlString->toHtml(),
                'subject' => $subject,
                'email' => $email,
                'ip' => $user_ip,
                'datetime' => $datetime,
                'status' => 1,
                'fromemail' => ''
            ]);
            $insert_id = DB::getPdo()->lastInsertId();
            $mail->isSMTP();

            recheckMail:
            $fromMail = '';
            if ($frmID != '') {
                $conT = "`emailkey` = '$frmID' ORDER BY `id` DESC LIMIT 1";
            } else {
                $conT = "`emailkey` = 'all' ORDER BY `id` DESC LIMIT 1";
            }


            $email_config = DB::table('email_config')->where('deletes', '0')
                ->where('status', '0')
                ->whereRaw($conT)->get();

            if ($email_config->count() > 0) {


                // $row = mysqli_fetch_assoc($get_Email);
                $fromMail = $email_config[0]->setFrom;
                $smtpAu = boolval($email_config[0]->SMTPAuth) ? true : false;
                $mail->Host = $email_config[0]->Host;
                $mail->SMTPAuth = $smtpAu;
                $mail->Username = $email_config[0]->Username;
                $mail->Password = $email_config[0]->Password;
                $mail->SMTPSecure = $email_config[0]->SMTPSecure;
                $mail->Port = $email_config[0]->Port;
                $mail->setFrom($fromMail, $email_config[0]->fromname);
                $mail->AddReplyTo($email_config[0]->AddReplyTo, 'LITTLE DRAW');
                if ($email_config[0]->char_set != '') {
                    $mail->CharSet = $email_config[0]->char_set;
                }

                if ($email_config[0]->Encoding != '') {
                    $mail->Encoding = $email_config[0]->Encoding;
                }
            } else {
                $frmID = '';
                if ($i == 0) {
                    $i++;
                    goto recheckMail;
                }
            }

         
            $mail->addAddress($email);

            $mail->Subject = $subject;
            $mail->isHTML(true);
            $mail->Body = $message;




            if (!$mail->send()) {
                $er = json_encode($mail->ErrorInfo);

                $update = DB::table('emaillog')->where('id', $insert_id)
                    ->update([
                        'sendstatus' => 'FAILED',
                        'error_info' => $er,
                        'fromemail' => $fromMail,
                    ]);
                // $update = mysqli_query($con, "UPDATE `emaillog` SET `sendstatus` = 'FAILED', `error_info` = '$er', `fromemail` = '$fromMail' WHERE `emaillog`.`id` = $insert_id;");
                return false;
            } else {
                
                $update = DB::table('emaillog')->where('id', $insert_id)
                    ->update([
                        'sendstatus' => 'SUCCESS',
                        'fromemail' => $fromMail,
                    ]);
                // $update = mysqli_query($con, "UPDATE `emaillog` SET `sendstatus` = 'SUCCESS', `fromemail` = '$fromMail' WHERE `emaillog`.`id` = $insert_id;");
                return true;
            }


        } catch (Exception $e) {
            $response = ['status' => 'failed', 'message' => 'Throw in Catch Section', 'error' => ['message' => $e->getMessage(), 'code' => $e->getCode(), 'string' => $e->__toString()]];
            return response()->json($response);
        }
    }
}
