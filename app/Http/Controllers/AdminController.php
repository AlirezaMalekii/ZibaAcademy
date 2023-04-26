<?php

namespace App\Http\Controllers;

use App\Course;
use App\Mail\HelpAndLicenseMail;
use App\Models\Category;
use App\Models\HlsSecret;
use App\Models\MeetingServer;
use App\Models\Organization;
use App\Models\Server;
use App\Models\Video;
use App\User;
use Carbon\Carbon;
use FFMpeg\Format\Video\X264;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;
use Kavenegar\KavenegarApi;
use ProtoneMedia\LaravelFFMpeg\Exporters\EncodingException;
use ProtoneMedia\LaravelFFMpeg\FFMpeg\CopyFormat;
use ProtoneMedia\LaravelFFMpeg\Support\FFMpeg;
use App\Models\User as UserModel;

class AdminController extends Controller
{
//    protected $kavenegar_api_key = "3346556D364B7265575A675A7756554A6D306246686357724264686739493341";
//    protected $kavenegar_api_key = "4E6B4E466855477469583479354A6F356335315451706E734130575A627164766C63544F52476F567775673D";
    protected $kavenegar_api_key = "516D78726A465034314335615567546F4A7A6532466A7336556864424630756654497A4357394A683173553D";
    protected $bbb_node_token = '^VJHRXU$RLUbpRUh^R+N?Y-zcP8MzEDcwt!tqu_Y%@MRxu!JAjxLn&ttt';
    protected $video_encryption_server_token = 'Pk7gUE5@vCfemqly1Os8cVFQ3uNPmZf9c&W6uGmoqn1AiF@gle';

  /*  protected function check_admin(UserModel $user){
        if ($user->level==='admin'){
            do{
                $token=Str::random(70);
            }while(UserModel::where('admin_token',$token)->first());
            $user->update(['admin_token'=>$token]);
        }
    }*/
    public static function code($model, $type, $length = 4)
    {
        do {
            $code = Str::random($length);
            $check_code = $model::where($type, $code)->get();
        } while (!$check_code->isEmpty());
        return $code;
    }

    public function resize($realPath, $sizes, $imagePath, $fileName, $quality = 60, $format = null)
    {
        $images['original'] = $imagePath . $fileName;
        foreach ($sizes as $size) {
            $images[$size] = $imagePath . "_{$size}" . $fileName;
            $width = explode('x', $size)[0];
            $height = explode('x', $size)[1];
//            if (!file_exists($images[$size])) {
//                if (!mkdir($images[$size], 666, true) && !is_dir($images[$size])) {
//                    throw new \RuntimeException(sprintf('Directory "%s" was not created', $images[$size]));
//                }
//            }
            Image::make($realPath)->resize($width, $height)->save(public_path($images[$size]), $quality, $format);
        }
        return $images;
    }



    function xml2array($xmlObject, $out = array())
    {
        foreach ((array)$xmlObject as $index => $node)
            $out[$index] = (is_object($node)) ? $this->xml2array($node) : $node;

        return $out;
    }




    function convert2english($string)
    {
        $newNumbers = range(0, 9);
        // 1. Persian HTML decimal
        $persianDecimal = array('&#1776;', '&#1777;', '&#1778;', '&#1779;', '&#1780;', '&#1781;', '&#1782;', '&#1783;', '&#1784;', '&#1785;');
        // 2. Arabic HTML decimal
        $arabicDecimal = array('&#1632;', '&#1633;', '&#1634;', '&#1635;', '&#1636;', '&#1637;', '&#1638;', '&#1639;', '&#1640;', '&#1641;');
        // 3. Arabic Numeric
        $arabic = array('٠', '١', '٢', '٣', '٤', '٥', '٦', '٧', '٨', '٩');
        // 4. Persian Numeric
        $persian = array('۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹');

        $string = str_replace($persianDecimal, $newNumbers, $string);
        $string = str_replace($arabicDecimal, $newNumbers, $string);
        $string = str_replace($arabic, $newNumbers, $string);
        return str_replace($persian, $newNumbers, $string);
    }


    function parse_number($number, $dec_point)
    {
        if (empty($dec_point)) {
            $locale = localeconv();
            $dec_point = $locale['decimal_point'];
        }
        return (float)str_replace($dec_point, '.', preg_replace('/[^\d' . preg_quote($dec_point) . ']/', '', $number));
    }


    function ip_info($ip = NULL, $purpose = "location", $deep_detect = TRUE)
    {
        $output = NULL;
        if (filter_var($ip, FILTER_VALIDATE_IP) === FALSE) {
            $ip = $_SERVER["REMOTE_ADDR"];
            if ($deep_detect) {
                if (filter_var(@$_SERVER['HTTP_X_FORWARDED_FOR'], FILTER_VALIDATE_IP))
                    $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
                if (filter_var(@$_SERVER['HTTP_CLIENT_IP'], FILTER_VALIDATE_IP))
                    $ip = $_SERVER['HTTP_CLIENT_IP'];
            }
        }
        $purpose = str_replace(array("name", "\n", "\t", " ", "-", "_"), NULL, strtolower(trim($purpose)));
        $support = array("country", "countrycode", "state", "region", "city", "location", "address");
        $continents = array(
            "AF" => "Africa",
            "AN" => "Antarctica",
            "AS" => "Asia",
            "EU" => "Europe",
            "OC" => "Australia (Oceania)",
            "NA" => "North America",
            "SA" => "South America"
        );
        if (filter_var($ip, FILTER_VALIDATE_IP) && in_array($purpose, $support)) {
            $ipdat = @json_decode(file_get_contents("http://www.geoplugin.net/json.gp?ip=" . $ip));
            if (@strlen(trim($ipdat->geoplugin_countryCode)) == 2) {
                switch ($purpose) {
                    case "location":
                        $output = array(
                            "city" => @$ipdat->geoplugin_city,
                            "state" => @$ipdat->geoplugin_regionName,
                            "country" => @$ipdat->geoplugin_countryName,
                            "country_code" => @$ipdat->geoplugin_countryCode,
                            "continent" => @$continents[strtoupper($ipdat->geoplugin_continentCode)],
                            "continent_code" => @$ipdat->geoplugin_continentCode
                        );
                        break;
                    case "address":
                        $address = array($ipdat->geoplugin_countryName);
                        if (@strlen($ipdat->geoplugin_regionName) >= 1)
                            $address[] = $ipdat->geoplugin_regionName;
                        if (@strlen($ipdat->geoplugin_city) >= 1)
                            $address[] = $ipdat->geoplugin_city;
                        $output = implode(", ", array_reverse($address));
                        break;
                    case "city":
                        $output = @$ipdat->geoplugin_city;
                        break;
                    case "state":
                        $output = @$ipdat->geoplugin_regionName;
                        break;
                    case "region":
                        $output = @$ipdat->geoplugin_regionName;
                        break;
                    case "country":
                        $output = @$ipdat->geoplugin_countryName;
                        break;
                    case "countrycode":
                        $output = @$ipdat->geoplugin_countryCode;
                        break;
                }
            }
        }
        return $output;
    }


    public function send_sms_lookup($data_array)
    {
        $api = new KavenegarApi($this->kavenegar_api_key);
        if (isset($data_array['token3'])) {
            $result = $api->VerifyLookup($data_array['receptor'], replace_space_with_underline($data_array['token']), replace_space_with_underline($data_array['token2']), replace_space_with_underline($data_array['token3']), $data_array['template'], $type = null);
        } elseif (isset($data_array['token2'])) {
            $result = $api->VerifyLookup($data_array['receptor'], replace_space_with_underline($data_array['token']), replace_space_with_underline($data_array['token2']), $token3 = null, $data_array['template'], $type = null);
        } else {
            $result = $api->VerifyLookup($data_array['receptor'], replace_space_with_underline($data_array['token']), $token2 = null, $token3 = null, $data_array['template'], $type = null);
        }
        return $result;
    }


    public function send_mail($email, $subject, $message)
    {
        $url = "https://api.elasticemail.com/v2/email/send";
        $apiKey = "43767646-d8d8-4295-ba0f-16bfdc95e789";

        $client = new Client();
        $response = $client->request('POST', $url, [
            'form_params' => [
                'msg_from' => "mailer@liveamooz.com",
                'msg_from_name' => "لایوآموز",
                'msg_to' => $email,
                'subject' => $subject,
//                'body_text' => $message,
                'body_html' => $message,
                'apikey' => $apiKey
            ]
        ]);

        return $response;

    }


    protected function createFilename(UploadedFile $file, $driver)
    {
        return $driver . "_" . Carbon::now()->timestamp . "_" . $file->getClientOriginalName();
    }

    public function createParentFilePath($file_extension, $driver)
    {
        if ($file_extension === "mp4"){
            $parentFilePath = "/upload/videos/";

            if (!Storage::disk($driver)->exists($parentFilePath)) {
                Storage::disk($driver)->makeDirectory($parentFilePath, 0755);
                Storage::disk($driver)->setVisibility("/upload", 'public');
                Storage::disk($driver)->setVisibility("/upload/videos", 'public');
            }
        }else{
            $parentFilePath = "/upload/files/{$file_extension}/";

            if (!Storage::disk($driver)->exists($parentFilePath)) {
                Storage::disk($driver)->makeDirectory($parentFilePath, 0755);
                Storage::disk($driver)->setVisibility("/upload", 'public');
                Storage::disk($driver)->setVisibility("/upload/files", 'public');
                Storage::disk($driver)->setVisibility("/upload/files/{$file_extension}", 'public');
            }
        }


        return $parentFilePath;
    }


}
